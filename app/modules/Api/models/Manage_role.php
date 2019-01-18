<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/12/28
 * Time: 11:40
 * Email: 574482856@qq.com
 *
 * 管理权限分组 模型
 */
defined('APP_PATH') OR exit('No direct script access allowed');

class Manage_roleModel extends BaseModel {

  /**
   * 获取分组中的权限
   * @param $manage_id
   */
  public function getRoleGroupAccess($manage_id, $useType, $field = []) {
    //实现联表查询

    if (!$this->realDelete)
      $this->_db->join('role_access ra', 'ra.role_id=mr.role_id and ra.status>-1', 'left');
    else
      $this->_db->join('role_access ra', 'ra.role_id=mr.role_id', 'left');

    $this->_db->join('menu m', 'm.id=ra.menu_id', 'left');

    if ($useType == 0) {
      $this->_db->where('m.type_id', '1');
      $this->_db->where('m.status', '1');
    } else
      $this->_db->where('m.status', '-1', '>');

    $this->_db->where('mr.manage_id', $manage_id);
    $field = array_map(function ($val) {

      if($val==='id')
        return 'DISTINCT m.id';
      else
        return 'm.' . $val;

    }, $field);

    $result = $this->_db->get('manage_role mr', NULL, $field);
    $this->_logSql();

    return $result;
  }

}