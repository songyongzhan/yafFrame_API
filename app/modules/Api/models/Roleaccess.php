<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/12/29
 * Time: 11:40
 * Email: 574482856@qq.com
 *
 * 权限管理 模型
 */
defined('APP_PATH') OR exit('No direct script access allowed');

class RoleaccessModel extends BaseModel {


  /**
   * 数据表名称
   * @var string
   */
  protected $table = 'role_access';

  /**
   * 更新用户角色
   * @param $manage_id
   * @param $role_ids
   * @return array|bool
   * @throws InvalideException
   */
  public function updateManageRole($manage_id, $role_ids) {
    $this->startTransaction();


    $hasCount = $this->getCount([getWhereCondition('manage_id', $manage_id)], 'manage_role');
    $delCount = 0;
    if ($hasCount) {
      $delCount = $this->delete([getWhereCondition('manage_id', $manage_id)], 'manage_role');

      if (!$delCount)
        return $this->rollback();
    }

    $data = [];
    foreach ($role_ids as $val) {
      $data[] = $this->autoAddtimeData([
        'manage_id' => $manage_id,
        'role_id' => $val,
        'status' => 1
      ]);
    }
    $result = $this->inserMulti($data, 'manage_role');
    if (!$result)
      return $this->rollback();

    $this->commit();

    return ['delCount' => $delCount, 'insert' => $result];
  }

  /**
   * 更新分组中的权限
   * @param $role_id
   * @param $role_ids
   */
  public function updateRoleAccess($role_id, $menu_ids) {

    $this->startTransaction();

    $hasCount = $this->getCount([getWhereCondition('role_id', $role_id)]);
    $delCount = 0;
    if ($hasCount) {

      $delCount = $this->delete([getWhereCondition('role_id', $role_id)]);

      if (!$delCount)
        return $this->rollback();
    }


    $data = [];
    foreach ($menu_ids as $val) {
      $data[] = $this->autoAddtimeData([
        'menu_id' => $val,
        'role_id' => $role_id,
        'status' => 1
      ], 'insert', TRUE);
    }
    $result = $this->inserMulti($data);
    if (!$result)
      return $this->rollback();

    $this->commit();

    return ['delCount' => $delCount, 'insert' => $result];
  }


}