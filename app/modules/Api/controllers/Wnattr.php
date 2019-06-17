<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2019/4/30
 * Time: 20:57
 * Email: 574482856@qq.com
 */


defined('APP_PATH') OR exit('No direct script access allowed');

class WnattrController extends ApiBaseController {

  public function getListAction() {
    //如果传递了page_size 就分页
    $title = $this->_post('title', '');

    $rules = [
      ['condition' => 'like',
        'key_field' => ['title'],
        'db_field' => ['title']
      ]
    ];
    $data = ['title' => $title];

    $where = $this->where($rules, array_filter($data, 'filter_empty_callback'));

    $result = $this->wnattrService->getList($where);
    return $result;
  }


  public function addAction() {
    $data = $this->getDatas();
    $result = $this->wnattrService->add($data);
    return $result;
  }


  public function updateAction() {
    $id = $this->_post('id');
    $data = $this->getDatas();
    $result = $this->wnattrService->update($id, $data);
    return $result;
  }


  public function getOneAction() {
    $id = $this->_post('id');
    $result = $this->wnattrService->getOne($id);
    return $result;
  }


  public function deleteAction() {
    $id = $this->_post('id');
    $result = $this->wnattrService->delete($id);
    return $result;
  }

  public function getNavlistAction() {
    $result = $this->wnattrService->getNavlist();
    return $result;
  }

  public function getAttrListSelectAction(){
    $result=$this->wnattrService->getAttrListSelect();
    return $result;

  }

  private function getDatas() {
    return [
      'title' => $this->_post('title'),
      'pid' => $this->_post('pid'),
      'thumb' => $this->_post('thumb',''),
      'descript' => $this->_post('descript',''),
      'tubiao' => $this->_post('tubiao', ''),
      'keywords' => $this->_post('keywords', ''),
      't_index' => $this->_post('t_index', ''),
      't_list' => $this->_post('t_list', ''),
      't_listimg' => $this->_post('t_listimg', ''),
      't_body' => $this->_post('t_body', ''),
      't_article' => $this->_post('t_article', ''),
      'template_index' => $this->_post('template_index', 't_index'),
      'body' => $this->_post('body', ''),
      'limitpage' => $this->_post('limitpage', 10),
      'sort_id' => $this->_post('sort_id', 0)
    ];
  }


}