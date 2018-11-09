<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/17
 * Time: 14:08
 * Email: songyongzhan@qianbao.com
 */

class BaseModel extends CoreModel {

  use TraitCommon;

  /**
   * @var MysqliDb
   */
  private $_db;

  protected $table;
  protected $id = 'id'; //表主键

  private $_querySqls; //执行过的sql语句

  protected $prefix = ''; //表前缀
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
  protected $autoaddtime = FALSE;

  public static $header = [

  ];

  protected function _init() {
    $this->_db = Yaf_Registry::has('db') ? Yaf_Registry::get('db') : NULL;
    $this->table = $this->prefix . strtolower(substr(get_class($this), 0, -5));
    $this->autoaddtime = Tools_Config::getConfig('db.mysql.auto_addtime');
    $this->prefix = Tools_Config::getConfig('db.mysql.prefix');
  }

  /**
   * 添加数据到数据库
   * @param array $data
   * @param null $table
   * @return bool
   */
  public function insert($data, $table = NULL) {
    is_null($table) || $this->table = $table;
    $insertId = $this->_db->insert($this->table, $this->autoAddtimeData($data, 'insert'));
    $this->_querySqls[] = $this->getLasqQuery();
    return $insertId;
  }

  /**
   * 批量插入数据
   * @param $data
   * @param null $table
   * @return bool|string
   */
  public function inserMulti($data, $table = NULL) {
    is_null($table) || $this->table = $table;
    $ids = $this->_db->insertMulti($this->table, $data);
    $this->_querySqls[] = $this->getLasqQuery();
    if (!ids)
      return FALSE;
    else
      return implode(',', $ids);
  }


  public function update($where, $data, $table = NULL) {
    is_null($table) || $this->table = $table;
    $data = $this->autoAddtimeData($data);
    $this->setCond($where);
    $result = $this->_db->update($this->table, $data);
    $this->_querySqls[] = $this->getLasqQuery();
    return $result;
  }


  public function del($where, $table = NULL) {
    is_null($table) || $this->table = $table;
    $this->setCond($where);
    $result = $this->_db->delete($this->table);
    $this->_querySqls[] = $this->getLasqQuery();
    return $result;
  }

  public function getOne($where, $fileds = [], $table = NULL) {
    is_null($table) || $this->table = $table;
    empty($fileds) && $fileds = '*';
    $this->setCond($where);
    $result = $this->_db->getOne($this->table, $fileds);
    $this->_querySqls[] = $this->getLasqQuery();
    return $result;
  }

  /**
   * 按条件返回返回条件中的数据 最大条数限制100条
   * @param $where
   * @param array $fileds
   * @param null $table
   * @return array
   * @throws InvalideException
   */
  public function getList($where, $fileds = [], $order = '', $table = NULL) {
    is_null($table) || $this->table = $table;
    empty($fileds) && $fileds = '*';
    $this->setCond($where);
    empty($order) && $order = $this->id . ' desc';
    list($orderField, $orderType) = explode(' ', $order);
    $this->_db->orderBy($orderField, $orderType);
    $result = $this->_db->get($this->table, [0, 100], $fileds);
    $this->_querySqls[] = $this->getLasqQuery();
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
  public function getListPage($where = [], $fileds = [], $pageNum = 1, $pageSize = PAGESIZE, $order = '', $table = NULL) {
    is_null($table) || $this->table = $table;
    empty($fileds) && $fileds = '*';
    $this->setCond($where);
    empty($order) && $order = $this->id . ' desc';
    list($orderField, $orderType) = explode(' ', $order);
    $this->_db->orderBy($orderField, $orderType);
    $this->_db->pageLimit = $pageSize;
    $result = $this->_db->paginate($this->table, $pageNum, $fileds);
    $this->_querySqls[] = $this->getLasqQuery();
    return [
      'totalPage' => $this->_db->totalPages,
      'totalCount' => $this->_db->totalCount,
      'result' => $result,
      'pageNum' => $pageNum,
      'pageSize' => $pageSize
    ];
  }


  /**
   * 拼装where条件
   * @param $where
   * @throws InvalideException
   */
  private function setCond($where) {
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
        if (is_array($val))
          $this->_db->where($key, $val['val'], isset($val['operator']) ? $val['operator'] : '=', isset($val['condition']) ? $val['condition'] : 'AND');
        else
          $this->_db->where($key, $val);

      }
    }
  }

  /**
   * 判断数据库中表是否存在
   * @param $table
   * @param bool $autoAddPrefix 是否自动添加表前缀
   * @return bool
   */
  private function tableExists($table, $autoAddPrefix = TRUE) {
    $table = $autoAddPrefix ? $this->prefix . $table : $table;
    return $this->_db->tableExists($table);
  }

  /**
   * 自动处理添加 createtime  updatetime
   * @param array $data
   * @param string $fun
   * @return array
   */
  private function autoAddtimeData($data, $fun = NULL) {
    if ($this->autoaddtime) {
      if (!is_null($fun) && $fun === 'insert') {
        $data[$this->createtime] = time();
        $data[$this->updatetime] = time();
      } else
        $data[$this->updatetime] = time();
    }
    return $data;
  }

  /**
   * 支持执行sql语句
   * @param string $sql sql中需要带问号的语句
   * @param array $params 数组 与sql 中问号一一对应
   * @return array
   * @throws InvalideException
   */
  public function query($sql, $params) {
    if (empty($sql)) throw new InvalideException('sql param error.', 500);
    $result = $this->_db->rawQuery($sql, $params);
    $this->_querySqls[] = $this->getLasqQuery();
    return $result;
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
  public function getLasqQuery() {
    return $this->_db->getLastQuery();
  }


}