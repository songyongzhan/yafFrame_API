<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2019/5/07
 * Time: 20:57
 * Email: 574482856@qq.com
 */


defined('APP_PATH') OR exit('No direct script access allowed');

class AttributeController extends ApiBaseController {

  public function getListAction() {
    //如果传递了page_size 就分页
    $page_size = $this->_post('page_size', PAGESIZE);
    $page_num = $this->_post('page_num', 1);
    $title = $this->_post('title', '');
    $input_label = $this->_post('input_label', '');
    $rules = [
      ['condition' => 'like',
        'key_field' => ['title', 'input_label'],
        'db_field' => ['title', 'input_label']
      ]
    ];
    $data = ['title' => $title, 'input_label' => $input_label];

    $where = $this->where($rules, $this->filterData($data));

    $result = $this->attributeService->getListPage($where, $page_num, $page_size);
    return $result;
  }

  public function addAction() {
    $data = $this->getData();
    $result = $this->attributeService->add($data);
    return $result;
  }


  public function updateAction() {
    $id = $this->_post('id');
    $data = $this->getData();
    $result = $this->attributeService->update($id, $data);
    return $result;
  }


  public function getOneAction() {
    $id = $this->_post('id');
    $result = $this->attributeService->getOne($id);
    return $result;
  }

  public function deleteAction() {
    $id = $this->_post('id');
    $result = $this->attributeService->delete($id);
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
      'input_type' => $this->_post('input_type'),
      'input_label' => $this->_post('input_label'),
      'input_name' => $this->_post('input_name'),
      'validate_type' => $this->_post('validate_type', ''),
      'validate_message' => $this->_post('validate_message', ''),
      'default_value' => $this->_post('default_value', ''),
      'input_width' => $this->_post('input_width', 0),
      'options_multi_type' => $this->_post('options_multi_type', 1),
      'options' => $this->_post('options', ''),
      'placeholder' => $this->_post('placeholder', ''),
      'column_name' => $this->_post('column_name'),
      'column_type' => $this->_post('column_type'),
      'column_value' => $this->_post('column_value', ''),
      'notnull' => $this->_post('notnull'),
      'column_default' => $this->_post('column_default'),
      'commenttxt' => $this->_post('commenttxt'),
      'status' => $this->_post('status'),
      'sort_id' => $this->_post('sort_id', 0)
    ];

  }

}