<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2019/5/07
 * Time: 20:57
 * Email: 574482856@qq.com
 */


defined('APP_PATH') OR exit('No direct script access allowed');

class EntityController extends ApiBaseController {

  public function getListAction() {
    //如果传递了page_size 就分页
    $page_size = $this->_post('page_size', PAGESIZE);
    $page_num = $this->_post('page_num', 1);
    $title = $this->_post('title', '');
    $rules = [
      ['condition' => 'like',
        'key_field' => ['title'],
        'db_field' => ['title']
      ]
    ];
    $data = ['title' => $title];

    $where = $this->where($rules, $this->filterData($data));

    $result = $this->entityService->getListPage($where, $page_num, $page_size);
    return $result;
  }


  public function addAction() {
    $data = $this->getData();
    $result = $this->entityService->add($data);
    return $result;
  }


  public function updateAction() {
    $id = $this->_post('id');
    $data = $this->getData();
    $result = $this->entityService->update($id, $data);
    return $result;
  }


  public function getOneAction() {
    $id = $this->_post('id');
    $result = $this->entityService->getOne($id);
    return $result;
  }

  public function deleteAction() {
    $id = $this->_post('id');
    $result = $this->entityService->delete($id);
    return $result;
  }

  /**
   * @method getlistcolumnAction
   * @return array
   * 2019/5/10 22:47
   */
  public function getlistcolumnAction() {
    $id = $this->_post('id');
    $result = $this->entitycolumnService->getList($id);
    return $result;
  }

  /**
   * 批量添加实体元素
   * @method addcolumnAction
   * @return array
   * 2019/5/10 22:43
   */
  public function addcolumnAction() {

    $entityId = $this->_post('entity_id');
    $columnData = $this->_post('column_info');
    $result = $this->entitycolumnService->add($entityId, $columnData);
    return $result;
  }

  /**
   * 更新实体属性提示相关信息
   * @method updatecolumnAction
   * @return array
   * 2019/5/10 22:38
   */
  public function updatecolumnAction() {

    $id = $this->_post('id');
    $entityId = $this->_post('entity_id');

    //修改实体属性，不能修改input_name 也就是字段名
    $data = [
      'input_label' => $this->_post('input_label'),
      //'input_name' => $this->_post('input_name'),
      'default_value' => $this->_post('default_value'),
      'input_width' => $this->_post('input_width'),
      'validate_message' => $this->_post('validate_message'),
      'sort_id' => $this->_post('sort_id')
    ];

    $result = $this->entitycolumnService->update($entityId, $id, $data);

    return $result;
  }

  /**
   * 删除实体中的一个元素
   * @method deletecolumnAction
   * @return array
   * 2019/5/10 22:40
   */
  public function deletecolumnAction() {
    $id = $this->_post('id');
    return $this->entitycolumnService->delete($id);
  }

  /**
   * 根据实体id 获取元素 用于渲染模板的列表
   * @method getviewlistAction
   * @return mixed
   * 2019/5/10 22:54
   */
  public function getviewlistAction() {
    $id = $this->_post('id');
    $result = $this->entitycolumnService->getviewlist($id);
    return $result;
  }

  /**
   * @method updatelistcolumnAction
   * @return mixed
   * 2019/5/12 17:52
   */
  public function updatelistcolumnAction(){
    $id=$this->_post('id');
    $listcolumn=$this->_post('listcolumn');
    $result=$this->entityService->updateListColumn($id,$listcolumn);
    return $result;
  }

  /**
   * 获取参数
   * @method getParam
   * @return array
   * 2019/5/10 6:49
   */
  private function getData() {
    return [
      'title' => $this->_post('title'),
      'descript' => $this->_post('descript'),
      'table_engine' => $this->_post('table_engine'),
      'ext' => $this->_post('ext'),
      'table_name' => $this->_post('table_name'),
      'listcolumn' => $this->_post('listcolumn', ''), //得到字段
      'listorder' => $this->_post('listorder', 'id desc'),
      'commenttxt' => $this->_post('commenttxt'),
      'status' => $this->_post('status'),
      'sort_id' => $this->_post('sort_id',0)
    ];
  }

}