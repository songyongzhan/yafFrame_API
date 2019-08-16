<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2019/5/6
 * Time: 22:34
 * Email: 574482856@qq.com
 */

defined('APP_PATH') OR exit('No direct script access allowed');


class EntitycolumnService extends BaseService {

  protected $field = ['id', 'attribute_id', 'entity_id', 'input_name', 'input_label', 'commenttxt', 'default_value', 'input_width', 'validate_message', 'sort_id'];

  /**
   * 根据实体id获取元素列表
   * @method getList
   * @param int $id <require|number> 实体id不能为空|id必须是数字
   * @return array
   * @throws InvalideException
   * 2019/5/10 22:47
   */
  public function getList($id, $fileds = '*') {
    if ($fileds == '*')
      $fileds = $this->field;

    $where = [
      getWhereCondition('entity_id', $id)
    ];
    $result = $this->entitycolumnModel->getList($where, $fileds, 'sort_id asc');
    return $this->show($result);
  }

  /**
   * 批量接口一次性插入多个
   * @method add
   * @param int $entityId <require|number> 实体id不能为空|实体id必须是数字
   * @param $data
   * @return array
   * 2019/5/10 22:25
   */
  public function add($entityId, $data) {

    if (!$data)
      return $this->show([], StatusCode::INSERT_FAILURE);

    $insertData = [];
    $attributeIds = []; //获取到提交数中所有的属性id
    //切割字符串
    $dataArr = explode('mOO$pp', $data);
    foreach ($dataArr as $key => $val) {
      if ($val) {
        $temp = ['entity_id' => $entityId];
        foreach (explode(',,', $val) as $kval) {
          $split = explode('=|=', $kval, 2);
          $temp[$split[0]] = isset($split[1]) ? $split[1] : '';
        }

        $insertData[] = $temp;
      }
    }

    //批量插入到数据库
    $lastInsertId = $this->entitycolumnModel->saveMulti($entityId, $insertData);
    if ($lastInsertId) {
      return $this->show(['ids' => $lastInsertId]);
    } else
      return $this->show([], StatusCode::INSERT_FAILURE);

  }

  /**
   * 删除
   * @param int $entityId <require|number> 实体id不能为空|实体id必须是数字
   * @param int $id <require|number> id不能为空|id必须是数字
   * @return array
   */
  public function delete($entityId, $id) {
    $result = $this->entitycolumnModel->entityColumnDelete($entityId, $id);
    return $result > 0 ? $this->show(['row' => $result, 'id' => $id]) : $this->show([], StatusCode::DATA_NOT_EXISTS);
  }

  /**
   * 获取单个信息
   * @param int $id <require|number> id不能为空|id必须是数字
   * @param string $fileds
   * @return mixed
   */
  public function getOne($id, $fileds = '*') {
    if ($fileds == '*')
      $fileds = array_merge($this->field, ['ext', 'listcolumn']);

    $result = $this->entitycolumnModel->getOne($id, $fileds);
    return $result ? $this->show($result) : $this->show([], StatusCode::DATA_NOT_EXISTS);
  }

  /**
   * 微调 增加一个新的元素使用
   * @method addSignone
   * @param int $entityId <require|number> 实体id不能为空|实体id必须是数字
   * @param $data
   * 2019/5/11 15:20
   */
  public function addSignone($entityId, $data) {

    //判断实体下 除了自己以外不能包含同样名称的input_name

    $where = [
      getWhereCondition('entity_id', $entityId),
      getWhereCondition('input_name', $data['input_name'])
    ];

    $hasCount = $this->entitycolumnModel->getCount($where);

    if ($hasCount > 0)
      showApiException('entityColumn存在同名input_name属性', StatusCode::ENTITY_COLUMN_SAME_NAME);

    $result = $this->entitycolumnModel->addSignone($entityId, $data);

    return $result;

  }


  /**
   * 分组更新数据
   * @param int $entityId <require|number> 实体id不能为空|实体id必须是数字
   * @param int $id <require|number> id
   * @return array mixed 返回用户数据
   */

  public function update($entityId, $id, $data) {
    $result = $this->entitycolumnModel->updateColumn($entityId, $id, $data);
    $result && $data['id'] = $id;
    return $result ? $this->show($data) : $this->show([]);
  }

  /**
   * 根据实体id 获取元素
   * @method getviewlist
   * @param int $id <require|number> 实体id不能为空|实体id必须是数字
   * @return array
   * @throws InvalideException
   * 2019/5/11 6:14
   */
  public function getviewlist($id) {

    //根据实体id获取到所有的元素
    $fields = $this->field;
    unset($fields['id']);
    $entityList = $this->getList($id, $fields)['result'];
    //无值
    if (!$entityList)
      return $this->show([]);

    $entityList = $this->entitycolumnModel->dataCombine($entityList);

    $entityList = $this->optionToData($entityList);

    //根据sort 进行排序
    return $this->show(arrayOrderby($entityList, 'sort_id', SORT_ASC));
  }

  public function optionToData($entityList) {

    if (!$entityList)
      return $entityList;

    foreach ($entityList as $key => &$val) {
      if (in_array($val['input_type'], ['select', 'radio', 'checkbox'])) {
        $data = $this->automaticService->getOptionsByItem($val);
        $val['optionsList'] = $data;
      }
    }
    unset($val);
    return $entityList;
  }


  /**
   * @method getTableField
   * @param int $entityId <require|number> 实体id不能为空|实体id必须是数字
   * 2019/7/21 12:44
   */
  public function getTableField($entityId) {
    $result = $this->entitycolumnModel->getTableField($entityId);
    return $this->show($result);
  }

  /**
   * @method sortColumn
   * @param $entityId <require|number> 实体id不能为空|实体id必须是数字
   * @param int $id <require|number> 实体属性id不能为空|实体属性id必须是数字
   * @param $sortId <require|number> 排序sort_id不能为空|排序sort_id必须是数字
   * @return array
   * 2019/7/22 7:52
   */
  public function sortColumn($entityId, $id, $sortId) {
    $data = ['sort_id' => $sortId];
    $result = $this->entitycolumnModel->update($id, $data);

    return $result ? $this->show(['affected_rows' => $result]) : $this->show([]);
  }



}