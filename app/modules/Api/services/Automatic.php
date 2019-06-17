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
    if ($result)
      $data['id'] = $id;

    return $result ? $this->show($data) : $this->show([]);

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

    $rules = [];
    $message = [];

    foreach ($list as $key => $val) {

      if (strpos($val['validate_type'], '|') && strpos($val['validate_message'], '|')) {

        $rules[$val['column_name']] = $val['validate_type'];
        $moreRules = explode('|', $val['validate_type']);
        $moreMessage = explode('|', $val['validate_message']);
        $count = count($moreMessage);
        if (count($moreRules) !== $count) {
          debugMessage(__FILE__ . '中' . __METHOD__ . '方法 验证 数组长度不一致');
          break;
        }

        for ($j = 0; $j < $count; $j++) {
          $ruleName = strpos($moreRules[$j], ':') ? (explode(':', $moreRules[$j]))[0] : $moreRules[$j];
          $message[$val['column_name'] . '.' . $ruleName] = $moreMessage[$j];
        }
      } else {
        $rules[$val['column_name']] = $val['validate_type'];
        $message[$val['column_name'] . '.' . $val['validate_type']] = $val['validate_message'];
      }
    }

    return ['rules' => $rules, 'message' => $message];
  }

  public function getListPage($where, $page_num, $page_size, $entityId, $fields) {

    $entityInfo = $this->entityModel->getOne($entityId, ['listorder', 'table_name']);

    $result = $this->automaticModel->getListPage($where, $fields, $page_num, $page_size, $entityInfo['listorder'], $entityInfo['table_name']);

    $columnList = $this->attributeService->getAttrbuteByColumn($fields);
    $columnList = isset($columnList['result']) ? $columnList['result'] : [];

    //$fieldComparison = array_column($columnList, 'input_type', 'column_name');

    //找到要替换的字段
    $replaceGroup = ['select', 'checkbox'];
    $replaceField = [];
    $changeColumnList = []; //转换数组，通过字段名可以吵到对应的数据
    foreach ($columnList as $key => $val) {
      if (in_array($val['input_type'], $replaceGroup))
        $replaceField[] = $val['column_name'];

      $changeColumnList[$val['column_name']] = $val;
    }

    //循环result列表  替换对应的参数
    foreach ($result as $key => &$value) {

      foreach ($replaceField as $sunK => $sunV) {
        $value['src_' . $sunV] = $value[$sunV];
        $value[$sunV] = $this->chooseValue($value[$sunV], $changeColumnList[$sunV]);
      }

    }

    return $result ? $this->show($result) : $this->show([]);
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

    return isset($data[$val]) ? $data[$val] : $val;
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
        $data[$k] = $v;
      }

    } else if ($params['options_multi_type'] == 2) {

      if (strpos($params['options'], 'wnattr') !== FALSE) {

        $temp = explode('=', $params['options']);
        if (count($temp) != 2 or !is_numeric($temp[1]))
          return $data;

        $where = [
          getWhereCondition('pid', $temp[1])
        ];

        $list = $this->wnattrModel->getList($where, ['id', 'title', 'pid'], 'sort_id asc');
        $data = array_column($list, 'title', 'id');
      }
    }
    $cacheDatas[$params['id']] = $data;
    return $data;
  }


}