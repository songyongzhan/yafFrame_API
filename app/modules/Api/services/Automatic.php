<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2019/5/15
 * Time: 23:24
 * Email: 574482856@qq.com
 */

defined('APP_PATH') OR exit('No direct script access allowed');


class AutomaticService extends BaseService {

  /**
   * @method delete
   * @param int $id <require|number> id不能为空|id必须是数字
   * @param int $entityId <require|number> entityId不能为空|entityId必须是数字
   * @return array
   * @throws InvalideException
   * 2019/5/17 21:59
   */
  public function delete($id, $entityId) {

    $entityInfo = $this->entityModel->getOne($entityId, ['table_name']);
    $result = $this->automaticModel->delete($id, $entityInfo['table_name']);
    return $result > 0 ? $this->show(['row' => $result, 'id' => $id]) : $this->show([], StatusCode::DATA_NOT_EXISTS);

  }

  /**
   * @method getOne
   * @param int $id <require|number> id不能为空|id必须是数字
   * @param int $entityId <require|number> entityId不能为空|entityId必须是数字
   * 2019/5/17 21:54
   * @return array
   */
  public function getOne($id, $entityId, $fields = '*') {

    $entityInfo = $this->entityModel->getOne($entityId, ['table_name']);

    $result = $this->automaticModel->getOne($id, $fields, $entityInfo['table_name']);

    return $result ? $this->show($result) : $this->show([], StatusCode::DATA_NOT_EXISTS);
  }


  /**
   * @method update
   * @param int $id <require|number> id不能为空|id必须是数字
   * @param $data
   * @param int $entityId <require|number> entityId不能为空|entityId必须是数字
   * @return array
   * @throws InvalideException
   * 2019/5/17 21:52
   */
  public function update($id, $data, $entityId) {
    //获取验证规则
    $validateParams = $this->getValideByEntityId($entityId);

    //自动数据验证
    $this->validateData($validateParams, $data);

    $entityInfo = $this->entityModel->getOne($entityId, ['table_name']);

    $result = $this->automaticModel->update($id, $data, $entityInfo['table_name']);
    if (!$result) {
      showApiException('更新失败');
    }

    $data['id'] = $id;
    return $this->show($data);

  }

  /**
   * 添加数据
   * @method add
   * @param $data
   * @param int $entityId <require|number> 实体id不能为空|实体id必须是数字
   * 2019/5/17 21:07
   */
  public function add($data, $entityId) {


    //获取验证规则
    $validateParams = $this->getValideByEntityId($entityId);

    //自动数据验证
    $this->validateData($validateParams, $data);

    $entityInfo = $this->entityModel->getOne($entityId, ['table_name']);

    $lastInsertId = $this->automaticModel->insert($data, $entityInfo['table_name']);

    if ($lastInsertId) {
      $data['id'] = $lastInsertId;
      return $this->show($data);
    } else {
      return $this->show([], StatusCode::INSERT_FAILURE);
    }

  }


  protected function validateData($validateParams, $data) {

    //'add' =>
    //array (
    //  'title' => 'require',
    //  'field_str' => 'require',
    //  'group_str' => 'require',
    //),

    //'add' =>
    //array (
    //  'title.require' => '名称不能为空',
    //  'field_str.require' => '查询的字段名不能为空',
    //  'group_str.require' => '分组规则不能为空',
    //),
    if (($result = validate($validateParams['rules'], $data, $validateParams['message'])) && is_array($result)) {
      showApiException($result['errMsg']);
    }
  }

  /**
   * 根据entityid获取到验证规则
   * @method getValidaByEntityId
   * @param $entityId
   * @return array
   * @throws InvalideException
   * 2019/5/17 21:41
   */
  public function getValideByEntityId($entityId) {

    $list = $this->entitycolumnService->getviewlist($entityId);

    if (!isset($list['result'])) {
      debugMessage('list 返回值不包含 result');
      return ['rules' => [], 'message' => []];
    }

    foreach ($list['result'] as $key => $val) {
      if (empty($val['validate_type']) || empty($val['validate_message'])) {
        continue;
      }

      if (strpos($val['validate_type'], '|') && strpos($val['validate_message'], '|')) {

        $rules[$val['input_name']] = trim($val['validate_type'], '|');
        $moreRules = explode('|', trim($val['validate_type'], '|'));
        $moreMessage = explode('|', trim($val['validate_message'], '|'));
        $count = count($moreMessage);
        if (count($moreRules) !== $count) {
          debugMessage(__FILE__ . '中' . __METHOD__ . '方法 验证 数组长度不一致');
          break;
        }

        for ($j = 0; $j < $count; $j++) {
          $ruleName = strpos($moreRules[$j], ':') ? (explode(':', $moreRules[$j]))[0] : $moreRules[$j];
          $message[$val['input_name'] . '.' . $ruleName] = $moreMessage[$j];
        }
      } else {
        $rules[$val['input_name']] = $val['validate_type'];
        $message[$val['input_name'] . '.' . $val['validate_type']] = $val['validate_message'];
      }
    }

    return ['rules' => $rules, 'message' => $message];
  }

  public function getListPage($where, $page_num, $page_size, $entityId, $fields) {

    $entityInfo = $this->entityModel->getOne($entityId, ['listorder', 'table_name', 'searchcolumn']);

    //fields 这个有可能会不存在因此需要进行去掉 因为字段有可能会删除，但是字段没有及时删除。

    $fields = $this->fileterFields($fields, $entityId);

    $result = $this->automaticModel->getListPage($where, $fields, $page_num, $page_size, $entityInfo['listorder'], $entityInfo['table_name']);

    $columnList = $this->attributeService->getAttrbuteByColumn($fields, $entityId);
    $columnList = isset($columnList['result']) ? $columnList['result'] : [];

    //$fieldComparison = array_column($columnList, 'input_type', 'column_name');

    //找到要替换的字段
    $replaceGroup = ['select', 'checkbox', 'radio'];
    $replaceField = [];

    $changeColumnList = []; //转换数组，通过字段名可以找到对应的数据
    foreach ($columnList as $key => $val) {
      if (in_array($val['input_type'], $replaceGroup))
        $replaceField[] = $val['input_name'];

      $changeColumnList[$val['input_name']] = $val;
    }

    if ($replaceField) {
      //循环result列表  替换对应的参数
      foreach ($result['list'] as $key => &$value) {
        foreach ($replaceField as $sunK => $sunV) {
          //echo "<pre>";
          //var_dump($value[$sunV], $changeColumnList[$sunV],$sunV);
          $value['src_' . $sunV] = $value[$sunV];
          $value[$sunV] = $this->chooseValue($value[$sunV], $changeColumnList[$sunV]);
        }
      }
      unset($value);
    }


    //获取搜索需要显示的数据
    $entityColumnList = $this->entitycolumnService->getviewlist($entityId)['result'];
    $searchColumnArr = explode(',', trim($entityInfo['searchcolumn'], ','));
    $entityColumnData = [];
    if ($searchColumnArr) {
      foreach ($entityColumnList as $value) {
        if (in_array($value['input_name'], $searchColumnArr)) {
          $entityColumnData[] = $value;
        }
      }
    }
    $result['searchcolumn'] = $entityInfo['searchcolumn'];
    $result['entityColumnData'] = $entityColumnData;

    $tableHeader = $this->getTableHeader($columnList, $fields);
    $result['tableHeader'] = jsonencode($tableHeader); //这里为啥要转换为json呢  是 保持一个顺序
    $tableHeaderFields = array_keys($tableHeader);
    $result['datalistField'] = jsonencode(array_combine($tableHeaderFields, $tableHeaderFields));

    return $result ? $this->show($result) : $this->show([]);
  }


  /**
   * 组装列表展示的数据
   * @method bo
   * @param $tableHeader
   * @return mixed
   * 2019/8/11 11:12
   */
  private function getTableHeader($columnList, $fields) {
    //id,title,nav,sort_id,updatetime,createtime,article_type,is_show

    //严格按照 $fields顺序显示
    $keyMap = [
      'id' => '序号',
      'status' => '状态',
      'sort_id' => '排序id',
      'updatetime' => '更新时间',
      'createtime' => '创建时间',
      'id' => '序号',
    ];

    $data = array_column($columnList, 'input_label', 'input_name');

    $keyMap = array_merge($keyMap, $data);
    if (is_string($fields)) {
      $fields = explode(',', $fields);
    }

    $tableHeader = [];

    foreach ($fields as $key => $val) {
      if (isset($keyMap[$val])) {
        $tableHeader[$val] = $keyMap[$val];
      } else {
        $tableHeader[$val] = $val;
      }
    }

    return $tableHeader;
  }

  /**
   * 通过 $val 找到对应的值
   * @method chooseValue
   * @param $val
   * @param $options
   * 2019/5/17 22:45
   */
  private function chooseValue($val, $params) {

    //options
    $data = $this->getOptionsByItem($params);

    $dataVal = [];
    if ($data) {
      $dataVal = array_column($data, 'title', 'id');
    }

    return isset($dataVal[$val]) ? $dataVal[$val] : $val;

  }

  /**
   * 获取选项的
   * @method getOptionsByItem
   * @param $params
   * @return array
   * @throws InvalideException
   * 2019/5/18 0:42
   */
  public function getOptionsByItem($params) {

    static $cacheDatas;

    if (isset(self::$cacheDatas[$params['id']]))
      return $cacheDatas[$params['id']];


    $data = [];
    if ($params['options_multi_type'] == 1) {
      foreach (explode(';', trim($params['options'], ';')) as $key => $value) {
        list($k, $v) = explode('=', $value);
        $data[] = [
          'title' => $v,
          'id' => $k
        ];
      }

    } else if ($params['options_multi_type'] == 2) {

      if (strpos($params['options'], 'wnattr') !== FALSE) {

        $temp = explode('wnattr', $params['options']);
        if (count($temp) != 2)
          return $data;

        $temp[1] = intval(str_replace('option', '', $temp[1]));

        //获取wnattr所有数据
        $wnattrList = $this->wnattrService->getList([])['result'];

        //如果是0 则说明要全部数据
        if ($temp[1] == 0) {
          $data = $wnattrList;
        } else {
          $targetData = menu_group_list($wnattrList, $temp[1]);
          //$result = menu_sort(sort_by_sort_id());
          $data = moreW2data($targetData, 'sun');
        }
      }
    }
    $cacheDatas[$params['id']] = $data;
    return $data;
  }


  /**
   * 根据条目得到默认值是什么
   * @method getDefaultVavlue
   * @param $params
   * @return string
   * 2019/7/20 14:43
   */
  public function getDefaultValue($params) {

    $data = '';

    if (!isset($params['options_multi_type']) || !$params['options_multi_type']) {
      return $data;
    }

    if (in_array($params['options_multi_type'], [1, 2])) {
      $data = trim($params['options'], ';');
    } else {
      $data = $params['default_value'];
    }
    return $data;
  }

  /**
   * @method _call
   * @param string $name <required> name 也是方法名必须不能为空
   * @param $arguments
   * 2019/8/7 23:50
   */
  public function _call($method, $arguments, $data) {


    $method = strtolower($method[0]) . substr($method, 1);

    //判断是否包含有 entity_id  如果不包含，则直接返回
    if (!isset($data['entity_id']) || !$data['entity_id']) {
      showApiException('entity_id 实体id不存在');
    }

    $entityId = $data['entity_id'];

    //这里的方法，都是这个类中不存在的，所以需要使用 entity_id 得到 对象的类型，这样去调用service 来完成任务

    $entityInfo = $this->entityModel->getOne($entityId, ['listorder', 'table_name']);

    //分三种情况  $entityInfo['table_name'];   小写  大写  下划线

    $service = $entityInfo['table_name'] . 'Service';

    $nameClass = ucfirst($service);

    $existsModule = (strtolower(getRequest()->getModuleName()) != strtolower(Tools_Config::getConfig('application.dispatcher.defaultModule')));

    //不能传递参数
    if ($existsModule && checkInclude($nameClass)) {
      if (class_exists($nameClass)) {

        $reflect = new Reflec($nameClass);
        $methodResult = $reflect->getMethod($method);
        if ($methodResult->isPublic()) {
          return $this->$nameClass->$method($entityId, $data);
        } else {
          showApiException($nameClass . '中的' . $method . '方法不存在或不可调用');
        }
        //通过反射，拿到这个类 是否有这个方法 是否可以调用
        //$method = new ReflectionMethod($nameClass, $name);
        //if ($method->isPublic()) {
        //  return $this->$nameClass->$name($entityId);
        //} else {
        //  showApiException($nameClass . '中的' . $name . '方法不存在或不可调用');
        //}
      }
    }
    showApiException('调用的' . $method . '方法不存在');
  }

  /**
   * 判断是否含有当前entity的控制器类，如果包含且存在这个方法，则调用自己的方法
   * @method havSelfControllerMethod
   * 2019/8/11 23:47
   */
  public function havSelfControllerMethod($method, $entityId) {

    if (!strpos($method, 'Action'))
      $method = strtolower($method[0]) . substr($method, 1) . 'Action';

    //判断是否包含有 entity_id  如果不包含，则直接返回
    if (!$entityId) {
      showApiException('entity_id 实体id不存在');
    }

    //这里的方法，都是这个类中不存在的，所以需要使用 entity_id 得到 对象的类型，这样去调用service 来完成任务

    $entityInfo = $this->entityModel->getOne($entityId, ['listorder', 'table_name']);

    $controller = $entityInfo['table_name'] . 'Controller';

    $nameClass = ucfirst($controller);

    $existsModule = (strtolower(getRequest()->getModuleName()) != strtolower(Tools_Config::getConfig('application.dispatcher.defaultModule')));

    if ($existsModule && checkInclude($nameClass)) {
      if (class_exists($nameClass)) {

        $reflect = new Reflec($nameClass);
        $methodResult = $reflect->getMethod($method);
        if ($methodResult && $methodResult->isPublic()) {
          return ['className' => $nameClass, 'method' => $method];
        }
      }
    }
    return FALSE;
  }

  /**
   * 过滤掉表中不存在的字段
   * @method fileterFields
   * @param $fields
   * @param $entityId
   * 2019/8/11 17:07
   */
  private function fileterFields($fields, $entityId) {

    $tableFilds = $this->entitycolumnService->getTableField($entityId)['result'];

    if (is_string($fields)) {
      $fields = explode(',', $fields);
    }

    if (!is_array($fields)) {
      showApiException('fields 格式错误');
    }


    return array_intersect($tableFilds, $fields);
  }


}