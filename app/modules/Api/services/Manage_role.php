<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2019/01/14
 * Time: 22:17
 * Email: 574482856@qq.com
 */

defined('APP_PATH') OR exit('No direct script access allowed');

/**
 * 获取用户组列表
 * Class ManageroleService
 */
class Manage_roleService extends BaseService {

  protected $field = ['role_id', 'manage_id'];

  /**
   * 获取列表
   * @param int $manage_id <require|number> 用户id不能为空
   * @return array
   */
  public function getList($manage_id) {

    $where = [
      getWhereCondition('manage_id', $manage_id)
    ];

    $result = $this->manage_roleModel->getList($where, $this->field);

    return $result ? $this->show(['list' => $result]) : $this->show([]);
  }

}