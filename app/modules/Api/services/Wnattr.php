<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2019/4/30
 * Time: 20:58
 * Email: 574482856@qq.com
 */

defined('APP_PATH') OR exit('No direct script access allowed');


/**
 * Class NavService
 * 2019/4/30 21:05
 */
class WnattrService extends BaseService {

  protected $field = ['id', 'title', 'pid', 'thumb', 'descript', 'tubiao', 'keywords', 't_index', 't_list', 't_listimg', 't_body', 't_article', 'template_index', 'limitpage'
    , 'status', 'updatetime', 'createtime'];

  /**
   * 获取列表
   * @param array $where
   * @param $field
   * @return mixed
   */
  public function getListPage(array $where, $page_num, $page_size) {
    $result = $this->wnattrModel->getListPage($where, $this->field, $page_num, $page_size);
    return $this->show($result);
  }

  /**
   * @method add
   * @param $title
   * @param $pid
   * @param $data
   * @return array
   * 2019/5/12 19:58
   */
  public function add($title, $pid, $data) {

    $lastInsertId = $this->wnattrModel->insert($data);
    if ($lastInsertId) {
      $data['id'] = $lastInsertId;
      return $this->show($data);
    } else {
      return $this->show([], StatusCode::INSERT_FAILURE);
    }

  }

  /**
   * 删除一个
   * @param int $id <require|number> id
   */
  public function delete($id) {

    //如果有子类，不能删除，需要自行处理
    $where = [
      getWhereCondition('pid', $id)
    ];
    $count = $this->wnattrModel->getCount($where);
    if ($count > 0)
      showApiException('此栏目下有子栏目，请处理后再删除');

    $result = $this->wnattrModel->delete($id);
    return $result > 0 ? $this->show(['row' => $result, 'id' => $id]) : $this->show([], StatusCode::DATA_NOT_EXISTS);
  }

  /**
   * 获取单个信息
   * @param int $id <require|number> id
   * @param string $fileds
   * @return mixed
   */
  public function getOne($id, $fileds = '*') {
    if ($fileds == '*')
      $this->field;

    $result = $this->wnattrModel->getOne($id, array_merge($fileds, ['body']));
    return $result ? $this->show($result) : $this->show([], StatusCode::DATA_NOT_EXISTS);
  }

  /**
   * 分组更新数据
   * @param int $id <require|number> id
   * @param string $title <require> 名称
   * @return array mixed 返回用户数据
   */

  public function update($id, $title, $pid, $data) {

    $result = $this->wnattrModel->update($id, $data);
    if ($result) {
      $data['id'] = $id;
    }
    return $result ? $this->show($data) : $this->show([]);
  }

  /**
   *
   * @method getNavlist
   * @param array $where
   * @return array
   * @throws InvalideException
   * 2019/5/14 8:38
   */
  public function getNavlist($where = []) {

    $field = $this->field;
    unset($field['updatetime'], $field['createtime'], $field['keywords'], $field['descript']);

    $result = $this->wnattrModel->getList($where, $field);

    $result = menu_group_list(menu_sort_by_sort_id($result));

    return $this->show($result);

  }

  /**
   * 用于显示下拉框
   * @method getAttrListSelect
   * @return array
   * @throws InvalideException
   * 2019/5/14 8:38
   */
  public function getAttrListSelect() {
    //用于权限判断，如果超级管理员，显示全部，
    //如果不是超级管理员，则根据权限，显示栏目菜单
    $field = $this->field;
    unset($field['updatetime'], $field['createtime'], $field['keywords'], $field['descript']);

    $where = [];
    $result = $this->wnattrModel->getList($where, $field);
    $result = menu_sort(menu_sort_by_sort_id($result));
    return $this->show($result);
  }

}