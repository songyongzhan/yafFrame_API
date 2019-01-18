<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/18
 * Time: 15:17
 * Email: 574482856@qq.com
 */

defined('APP_PATH') OR exit('No direct script access allowed');

class RoleaccessService extends BaseService {


  /**
   * 更新用户群组权限
   * @param int $manage_id <require|number> 用户id
   * @param string $role_ids <require> 权限信息
   */
  public function updateManageRole($manage_id, $role_ids) {
    $role_ids = explode(',', trim($role_ids, ','));
    $result = $this->roleaccessModel->updateManageRole($manage_id, $role_ids);
    return $result ? $this->show($result) : $this->show([]);
  }

  /**
   * 更新分组权限
   * @param $role_id <require|number> 用户id
   * @param $menu_ids 栏目权限
   */
  public function updateRoleAccess($role_id, $menu_ids) {
    $menu_ids = explode(',', trim($menu_ids, ','));
    $result = $this->roleaccessModel->updateRoleAccess($role_id, $menu_ids);
    return $result ? $this->show($result) : $this->show([]);
  }

  /**
   *
   * 返回当前用户可以访问的url
   * @return array
   */
  public function getRoleMenuApi() {
    $roleResult = $this->menuService->getList([], 1);
    $roleResult = $roleResult['result'];

    $checkUrlData = [];
    foreach ($roleResult as $val) {

      if ($val['relation_url'] != '')
        $checkUrlData[] = $val['relation_url'];

      if ($val['type_id'] == 2) {
        if ($val['url'] != '')
          $checkUrlData[] = $val['url'];
      }
    }
    $checkUrlData = array_change_value_case($checkUrlData);

    return $this->show(['menu_urls' => $checkUrlData]);
  }

  /**
   *  验证当前用户是否有权限登录
   * @param string $url <require> 验证地址传递为空
   * @return array
   */
  public function checkUrl($url) {

    $roleResult = $this->menuService->getList([], 1);
    $roleResult = $roleResult['result'];

    $checkUrlData = [];
    foreach ($roleResult as $val) {

      if ($val['relation_url'] != '')
        $checkUrlData[] = $val['relation_url'];

      if ($val['type_id'] == 2) {
        if ($val['url'] != '')
          $checkUrlData[] = $val['url'];
      }
    }

    $checkUrlData = array_change_value_case($checkUrlData);

    $url = strtolower($url);
    $is_have = in_array($url, $checkUrlData);

    return $is_have ? $this->show(['success' => 1]) : $this->show(['success' => 0]);
  }

  /**
   * 获取当前用户可以访问的栏目和方法
   *
   * 此方法一般用于权限设置
   *
   * @return array|bool
   * @throws InvalideException
   */
  private function getCurrentManageRoleMenu() {
    if ($this->tokenService->isadmin) {
      $where = [
        getWhereCondition('status', 1)
      ];
      $menuList = $this->menuModel->getList($where, ['id', 'title', 'pid', 'sort_id']);
    } else {
      //不是超级管理员 获取栏目
      $menuList = $this->menuService->getManageRole($this->tokenService->manage_id, 1);
    }
    $menuList = menu_group_list(sort_by_sort_id($menuList, 'asc'));
    return $menuList;
  }


  /**
   * 获取分组的权限
   * @param int $id <require|number> id不能为空|id不是数字
   */
  public function getRoleAccess($id) {
    $menuList = $this->getCurrentManageRoleMenu();
    $roleMenu = $this->roleaccessModel->getList([
      getWhereCondition('role_id', $id)
    ], 'menu_id');
    $roleMenuIds = $roleMenu ? array_column($roleMenu, 'menu_id') : [];

    return $this->show(['menu_list' => $menuList, 'role_ids' => implode(',', $roleMenuIds), 'id' => $id]);
  }

  /**
   * 根据用户id获取此用户的权限id
   * @param int $manage_id <require> 用户id不能为空
   */
  public function getManageRole($manage_id) {

    $menuList = $this->getCurrentManageRoleMenu();
    //得到当前用户的权限
    $manageMenu = $this->menuService->getManageRole($manage_id, 1);
    $menuIds = $manageMenu ? array_column($manageMenu, 'id') : [];

    return $this->show(['menu_list' => $menuList, 'role_ids' => implode(',', $menuIds)]);
  }


}