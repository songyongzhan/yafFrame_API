<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/17
 * Time: 14:08
 * Email: 574482856@qq.com
 */

defined('APP_PATH') OR exit('No direct script access allowed');

class BaseModel extends CoreModel {

  use TraitCommon;

  private static $_object = [];
  /**
   * @var MysqliDb
   */
  protected $_db;
  /**
   * @var table
   */
  protected $table;

  protected $id = 'id'; //表主键

  private $_querySqls; //执行过的sql语句

  public $prefix = ''; //表前缀
  /**
   * 创建时间的字段  设置成 protected 在子类中修改
   * @var string
   */
  protected $createtime = 'createtime';
  /**
   * 更新时的字段  设置成 protected 在子类中修改
   * @var string
   */
  protected $updatetime = 'updatetime';

  /**
   * 是否自动添加 createtime  和 updatetime
   * 如果设置true 则所有表中必须包含此字段，否则报错
   * @var bool
   */
  protected $autoaddtime;

  protected $output_time_format = FALSE;
  //输入格式，默认为false 则时间戳输出，可以定义 Y-m-d H:i:s 格式化输出


  /**
   * 是否真实删除，默认为false  即逻辑删除,不清空数据，status=-1
   * @var bool
   */
  protected $realDelete = FALSE;

  public static $header = [

  ];

  protected function _init() {
    $this->_db = Yaf_Registry::has('db') ? Yaf_Registry::get('db') : NULL;
    $this->table = is_null($this->table) ? $this->prefix . strtolower(substr(get_class($this), 0, -5)) : $this->table;
    if (is_null($this->autoaddtime))
      $this->autoaddtime = Tools_Config::getConfig('db.mysql.auto_addtime');

    $this->prefix = Tools_Config::getConfig('db.mysql.prefix');
    if (!$this->output_time_format)
      $this->output_time_format = (defined('DB_AUTOTIME_OUT_FORMAT') && DB_AUTOTIME_OUT_FORMAT) ? DB_AUTOTIME_OUT_FORMAT : FALSE;
  }

  /**
   * 添加数据到数据库
   * @param array $data
   * @param null $table
   * @return bool
   */
  public function insert($data, $table = NULL) {
    is_null($table) || $this->table = $table;

    $result = $this->_db->insert($this->table, $this->autoAddtimeData($data, 'insert'));
    $this->_querySqls[] = $this->getLastQuery();
    $this->_logSql();
    return $result ? $this->_db->getInsertId() : 0;
  }

  /**
   * 批量插入数据
   * @param $data
   * @param null $table
   * @return bool|string
   */
  public final function inserMulti($data, $table = NULL) {
    is_null($table) || $this->table = $table;
    $ids = $this->_db->insertMulti($this->table, $data);
    $this->autoaddtime && debugMessage('未自动补充createtime和updatetime');
    $this->_logSql();
    if (!$ids)
      return FALSE;
    else
      return implode(',', $ids);
  }


  public final function update($where, $data, $table = NULL) {
    is_null($table) || $this->table = $table;
    $data = $this->autoAddtimeData($data);
    $this->setCond($where);
    $result = $this->_db->update($this->table, $data);
    $this->_logSql();
    return $result ? $this->_db->count : 0;
  }


  /**
   * 删除
   * @param $where
   * @param null $table
   * @return int 返回受影响的行数
   * @throws InvalideException
   */
  public final function delete($where, $table = NULL) {
    is_null($table) || $this->table = $table;
    $this->setCond($where);
    $result = $this->realDelete ? $this->_db->delete($this->table) : $this->update($where, ['status' => -1]);
    $this->realDelete || $this->_logSql();
    return $result ? $this->_db->count : 0;
  }

  public final function getOne($where, $fileds = [], $table = NULL) {
    is_null($table) || $this->table = $table;
    empty($fileds) && $fileds = '*';
    $this->setCond($where);
    $result = $this->_db->getOne($this->table, $fileds);
    if (isset($result['updatetime']) && $this->output_time_format)
      $result['updatetime'] = date($this->output_time_format, $result['updatetime']);

    if (isset($result['createtime']) && $this->output_time_format)
      $result['createtime'] = date($this->output_time_format, $result['createtime']);



    $this->_logSql();
    return $result;
  }

  /**
   * 按条件返回返回条件中的数据 最大条数限制100条
   * @param $where
   * @param array $fileds
   * @param null $table
   * @param int $maxSize 系统默认做了一个限制，如果不限制请传递0
   * @return array
   * @throws InvalideException
   */
  public final function getList($where, $fileds = [], $order = '', $table = NULL, $maxSize = 1000, $group = '') {
    if ($table) $this->table = $table;
    empty($fileds) && $fileds = '*';
    $this->setCond($where);
    empty($order) && $order = $this->id . ' desc';
    list($orderField, $orderType) = explode(' ', $order);

    //设置group
    if ($group) {
      $group = explode(',', trim($group));
      foreach ($group as $field) {
        $this->_db->groupBy($field);
      }
    }

    $this->_db->orderBy($orderField, $orderType);
    $rowNum = [0, abs($maxSize)];
    $maxSize === 0 && $rowNum = NULL;
    $result = $this->_db->get($this->table, $rowNum, $fileds);
    $result = array_map(function ($value) {
      if (isset($value['updatetime']) && $this->output_time_format)
        $value['updatetime'] = date($this->output_time_format, $value['updatetime']);

      if (isset($value['createtime']) && $this->output_time_format)
        $value['createtime'] = date($this->output_time_format, $value['createtime']);
      return $value;
    }, $result);
    $this->_logSql();
    return $result;
  }


  /**
   * 返回搜索条件中的总数量
   * @param $where
   * @param null $table
   * @return mixed
   * @throws InvalideException
   * 2019/5/11 6:31
   */
  public final function getCount($where, $table = NULL) {
    is_null($table) || $this->table = $table;
    $this->setCond($where);
    $result = $this->_db->getValue($this->table, "count(id)");
    $this->_logSql();
    return $result;
  }

  /**
   * 分页
   * @param $where
   * @param array $fileds
   * @param int $pageNum
   * @param int $pageSize
   * @param null $table
   */
  public final function getListPage($where = [], $fileds = [], $pageNum = 1, $pageSize = PAGESIZE, $order = '', $table = NULL) {
    is_null($table) || $this->table = $table;
    empty($fileds) && $fileds = '*';
    $this->setCond($where);
    empty($order) && $order = $this->id . ' desc';
    list($orderField, $orderType) = explode(' ', $order);
    $this->_db->orderBy($orderField, $orderType);
    $this->_db->pageLimit = $pageSize;
    $result = $this->_db->paginate($this->table, $pageNum, $fileds);
    $result = array_map(function ($value) {
      if (isset($value['updatetime']) && $this->output_time_format)
        $value['updatetime'] = date($this->output_time_format, $value['updatetime']);

      if (isset($value['createtime']) && $this->output_time_format)
        $value['createtime'] = date($this->output_time_format, $value['createtime']);
      return $value;
    }, $result);
    $this->_logSql();
    return page_data($result, $this->_db->totalCount, $pageNum, $pageSize, $this->_db->totalPages);
  }


  /**
   * 获取view中的数据
   * @param $viewName
   * @param bool $prefix
   */
  public function getViewData($viewName, $field = '') {
    $result = $this->_db->get($viewName, NULL, $field);
    $this->_logSql();
    return $result;
  }

  /**
   * 记录并处理sql
   */
  protected final function _logSql() {
    $lastQuerySql = $this->getLastQuery();
    $this->_querySqls[] = $lastQuerySql;
    isEnv() && debugMessage('MYSQL:' . $lastQuerySql);

    if ($this->_db->getLastErrno() > 0)
      debugMessage('MYSQL:' . $this->_db->getLastErrno() . ', Err:' . $this->_db->getLastError());
  }

  /**
   * 拼装where条件
   * @param $where
   * @throws InvalideException
   */
  protected final function setCond($where) {

    if (!$this->realDelete) {
      //$dbwhere = $this->_db->getWhere();
      //$dbwhere = array_column($dbwhere, 1);
      //$flag = FALSE;
      //foreach ($dbwhere as $val) {
      //  if (stristr($val, 'status')) {
      //    $flag = TRUE;
      //    break;
      //  }
      //}
      //如果逻辑删除，需要拼装status
      //if (!$flag) {
      //  $this->_db->where('status', -1, '>');
      //  debugMessage('系统自动添加了逻辑删除过滤值 status ');
      //}

      $this->_db->where('status', -1, '>');
      debugMessage('系统自动添加了逻辑删除过滤值 status ');
    }

    if (!$where) return;
    $map = [];
    if (is_numeric($where))
      $this->_db->where($this->id, $where);
    else if (is_array($where))
      $map = $where;
    else
      throw new InvalideException('$where param error.', 500);

    //切记，这里只是实现了where 条件 其他的条件，请在业务中 自行实现
    if ($map) {
      foreach ($map as $key => $val) {
        $this->_db->where($val['field'], $val['val'], isset($val['operator']) ? $val['operator'] : '=', isset($val['condition']) ? $val['condition'] : 'AND');
      }
    }
  }

  /**
   * 判断数据库中表是否存在
   * @param $table
   * @param bool $autoAddPrefix 是否自动添加表前缀
   * @return bool
   */
  public final function tableExists($table, $autoAddPrefix = FALSE) {
    $table = $autoAddPrefix ? $this->prefix . $table : $table;
    return $this->_db->tableExists($table);
  }

  /**
   * 获取表相关信息
   * @param $table
   * @param string $filed
   * @return mixed
   */
  public final function getTableInfo($table, $filed = '*') {
    $table = strpos($table, '.') ? $table : str_replace($this->prefix, '', $table);
    return $this->_db->getTableScnema($table, $filed);
  }

  /**
   * 创建临时表
   * @return bool
   */
  public function cloneTmpTable($srcTable = '') {
    $srcTable = str_replace($this->prefix, '', $srcTable);
    $srcTable === '' && $srcTable = $this->table;

    $srcTable = $this->prefix . $srcTable;

    $sql = 'CREATE TEMPORARY TABLE %s ENGINE=MyISAM AS SELECT * FROM %s WHERE %s < 0';

    $temporaryTable = $srcTable . time();

    $sql = sprintf($sql, $temporaryTable, $srcTable, $this->id);

    $this->query($sql);

    if ($this->_db->getLastErrno() > 0)
      return FALSE;
    else
      return str_replace($this->prefix, '', $temporaryTable);

  }

  /**
   * @param $tmpTable
   * @param string $distTable
   */
  public function copyData($tmpTable, $distTable = '') {
    $tmpTable = str_replace($this->prefix, '', $tmpTable);
    $distTable = str_replace($this->prefix, '', $distTable);

    if ($distTable === '')
      $distTable = $this->prefix . $this->table;
    else
      $distTable = $this->prefix . $distTable;

    $data = $this->getTableInfo($distTable);
    if ($data) {
      $field = array_column($data, 'COLUMN_NAME', 'COLUMN_NAME');
      unset($field[$this->id]);
      $field = implode(',', $field);
    } else
      $field = NULL;

    if (!$field)
      return FALSE;

    $sql = sprintf('INSERT INTO %s(%s) select %s from %s', $distTable, $field, $field, $this->prefix . $tmpTable);

    debugMessage('copyData temporary to table Sql:' . $sql);

    $this->query($sql);
    $this->query(sprintf('DROP TABLE %s', $this->prefix . $tmpTable));

    if ($this->_db->getLastErrno() > 0)
      return FALSE;
    else
      return TRUE;
  }


  /**
   * 自动处理添加 createtime  updatetime
   * @param array $data
   * @param string $fun
   * @param boolean $otherCall 如果外部调用，此参数设置为true 可以调用
   * @return array
   */
  protected final function autoAddtimeData($data, $fun = NULL, $otherCall = FALSE) {
    if (($this->autoaddtime) || $otherCall) {
      debugMessage('开启自动添加时间戳 updatetime  createtime');
      if (!is_null($fun) && $fun === 'insert') {
        $data[$this->createtime] = time();
        $data[$this->updatetime] = time();
      } else
        $data[$this->updatetime] = time();
    }
    return $data;
  }

  /**
   * 支持执行sql语句并返回结果
   * @param string $sql sql中需要带问号的语句
   * @param array $params 数组 与sql 中问号一一对应
   * @return array
   * @throws InvalideException
   */
  public function query($sql, $params = []) {
    if (empty($sql)) throw new InvalideException('sql param error.', 500);
    $result = $this->_db->rawQuery($sql, $params);
    $result = array_map(function ($value) {
      if (isset($value['updatetime']) && $this->output_time_format)
        $value['updatetime'] = date($this->output_time_format, $value['updatetime']);

      if (isset($value['createtime']) && $this->output_time_format)
        $value['createtime'] = date($this->output_time_format, $value['createtime']);
      return $value;
    }, $result);
    $this->_logSql();
    return $result;
  }

  /**
   * 返回影响的条数
   * @param string $sql sql中需要带问号的语句
   * @param array $params 数组 与sql 中问号一一对应
   * @return string
   * @throws InvalideException
   */
  public function exec($sql, $params = []) {
    if (empty($sql)) throw new InvalideException('sql param error.', 500);
    $this->_db->rawQuery($sql, $params);
    $this->_querySqls[] = $this->getLastQuery();
    return $this->_db->count;
  }

  /**
   * 获取此次执行的sql
   * @return mixed
   */
  public function getSqls() {
    return $this->_querySqls;
  }

  /**
   * 放回当前处理的url
   * @return string
   */
  public function getLastQuery() {
    return $this->_db->getLastQuery();
  }

  /**
   * 开启数据库事务
   */
  public function startTransaction() {
    return $this->_db->startTransaction();
  }

  /**
   * 回滚事务
   * @return bool
   */
  public function rollback() {
    return $this->_db->rollback();
  }

  /**
   * 提交事务
   * @return bool
   */
  public function commit() {
    return $this->_db->commit();
  }

  /**
   * 用于检测是否在事务中，如果在，就会自动回滚
   * @return bool
   */
  public function _transaction_status_check() {
    return $this->_db->_transaction_status_check();
  }


  /**
   * 切换数据库
   * @param $name
   * @return $this
   * @throws Exception
   */
  public function chooseConnection($name) {
    return $this->_db->connection($name);
  }

  /**
   * 开启sql调试
   * @param bool $enable
   * @param null $stripPrefix
   */
  public function startTrace($enable = TRUE, $stripPrefix = NULL) {
    $this->_db->setTrace($enable, $stripPrefix);
  }

  /**
   * 获取调试信息
   * @return array
   */
  public function getTrace() {
    return $this->_db->trace;
  }

  public function setTable($table) {
    if (!$table) return FALSE;
    $this->table = $this->prefix . strtolower($table);
    return TRUE;
  }

  /**
   * 获取类表名
   * @method getTable
   * @return table
   * 2019/5/11 16:09
   */
  public function getTable() {
    return $this->table;
  }

  /**
   * 根据表获取表字段
   * @method getFields
   * @param null $table
   * @return array
   * 2019/5/11 16:28
   */
  public function getFields($table = NULL) {
    is_null($table) && $table = $this->table;

    $data = $this->getTableInfo($table);
    if ($data)
      return array_column($data, 'COLUMN_NAME', 'COLUMN_NAME');
    else
      return [];
  }


  /**
   * 获取当前表 下一个id
   * @method getNextInsertId
   * @return int
   * 2019/5/11 16:40
   */
  public function getNextInsertId($table = NULL) {

    is_null($table) && $table = $this->table;
    $data = $this->query("show table status like '{$table}' ");
    return isset($data['Auto_increment']) ? $data['Auto_increment'] : 0;

  }

  /**
   * 自动声明变量
   * @param $name
   * @return mixed|null
   */
  public final function __get($name) {
    $value = NULL;
    if (in_array($name, self::$_object) && is_callable((self::$_object)[$name]))
      $value = (self::$_object)[$name]();
    else if (in_array($name, self::$_object))
      $value = (self::$_object)[$name];
    else if (strpos($name, 'Model') || strpos($name, 'Service')) {
      $nameClass = ucfirst($name);

      if (class_exists($nameClass)) {
        (strtolower(getRequest()->getModuleName()) != strtolower(Tools_Config::getConfig('application.dispatcher.defaultModule'))) && checkInclude($nameClass);
        $value = new ProxyModel(new $nameClass());
        $this->$name = $value;
      } else if (strpos($name, 'Model')) { //若调用model不存在，就new BaseModel并重新设置table
        if ((strtolower(getRequest()->getModuleName()) != strtolower(Tools_Config::getConfig('application.dispatcher.defaultModule')))) {
          $baseModel = new BaseModel();
          $baseModel->setTable(strtolower(substr($name, 0, -5)));
          $value = new ProxyModel($baseModel);
          $this->$name = $value;
        }
      }
    }
    return $value;
  }

  /**
   * 过滤数据
   * @method filterData
   * @param $data
   * 2019/5/11 16:55
   */
  protected function filterData($data) {
    //过滤字段不存在的数据
    $fields = $this->getFields();
    foreach ($data as $key => $val) {
      if (!in_array($key, $fields))
        unset($data[$key]);
    }
    return $data;
  }

}