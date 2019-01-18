<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/18
 * Time: 15:17
 * Email: 574482856@qq.com
 */

defined('APP_PATH') OR exit('No direct script access allowed');

class RoleService extends BaseService {

  /**
   * 获取权限列表 分页
   * @param array $where
   * @param int $page_num <number>
   * @param int $page_size <number>
   */
  public function getListPage(array $where, $field = '*', $page_num, $page_size) {
    $result = $this->roleModel->getListPage($where, $field, $page_num, $page_size);
    return $this->show($result);
  }

  /**
   * 获取栏目列表
   * @param array $where
   * @param $field
   * @return mixed
   */
  public function getList($where, $field = '*') {
    $result = $this->roleModel->getList($where, $field);
    return $this->show($result);
  }

  /**
   * 添加权限
   * @param string $title <require> 分组名称
   * @return mixed 返回最后插入的id
   */
  public function add($title, $remarks) {
    $data = [
      'title' => $title,
      'remarks' => $remarks
    ];
    $lastInsertId = $this->roleModel->insert($data);
    if ($lastInsertId) {
      $data['id'] = $lastInsertId;
      return $this->show($data);
    } else {
      return $this->show([], StatusCode::INSERT_FAILURE);
    }

  }

  /**
   * 删除一个角色
   * @param int $id <require|number> id
   */
  public function delete($id) {
    $result = $this->roleModel->roleDelete($id);
    if (isset($result['type'])) {
      showApiException('请先删除此分组下的用户，再删除分组', StatusCode::HAS_MANAGE);
    } else {
      return $result > 0 ? $this->show(['row' => $result, 'id' => $id]) : $this->show([], StatusCode::DATA_NOT_EXISTS);
    }
  }

  /**
   * 获取单个信息
   * @param int $id <require|number> id不能为空|id不是数字
   * @param string $fileds
   * @return mixed
   */
  public function getOne($id, $fileds = '*') {
    $result = $this->roleModel->getOne($id, $fileds);
    return $result ? $this->show($result) : $this->show([], StatusCode::DATA_NOT_EXISTS);
  }

  /**
   * 分组更新数据
   * @param int $id <require|number> id不能为空|id不是数字
   * @param string $title <require> 名称
   * @return array mixed 返回用户数据
   */

  public function update($id, $title, $remarks) {
    $data = [
      'title' => $title,
      'remarks' => $remarks
    ];
    $result = $this->roleModel->update($id, $data);
    if ($result) {
      $data['id'] = $id;
    }
    return $result ? $this->show($data) : $this->show([]);
  }



}