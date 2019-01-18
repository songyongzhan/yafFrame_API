<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/12/28
 * Time: 11:45
 * Email: 574482856@qq.com
 *
 */

defined('APP_PATH') OR exit('No direct script access allowed');

class RoleaccessController extends ApiBaseController {

  /**
   * 验证一个url是否可以访问  传递栏目 id  或 url 任意一项都可以
   * @name 验证url 权限验证
   * @param string $url <POST> 需要验证的url
   * @return mixed
   */
  public function checkUrlAction() {
    $url = $this->_post('url');
    $result = $this->roleaccessService->checkUrl($url);
    return $result;
  }

  /**
   * 获取当前用户可以访问的url
   * @return mixed
   */
  public function getRoleMenuUrlsAction(){
    $result=$this->roleaccessService->getRoleMenuUrls();
    return $result;
  }

  /**
   * 更新用户权限所属分组
   * @name 更新用户属组
   * @param int $id <POST> 用户id
   * @param string $role_ids <POST> 管理权限
   * @return mixed
   */
  public function updateManageRoleAction() {
    $id = $this->_post('manage_id');
    $role_ids = $this->_post('role_ids');
    $result = $this->roleaccessService->updateManageRole($id, $role_ids);
    return $result;
  }

  /**
   * 获取当前用户拥有的权限
   * @name 获取当前用户权限
   * @param int $id <POST> 用户id
   */
  public function getManageRoleAction() {
    $manage_id = $this->_post('manage_id');
    $result = $this->manage_roleService->getList($manage_id);
    return $result;
  }

  /**
   * 获取当前用户所有的权限
   * @return array
   */
  public function getManageAccessAction() {
    $manage_id = $this->_post('manage_id');
    $result = $this->roleaccessService->getManageRole($manage_id);
    return $result;
  }

  /**
   * 获取单个组的权限
   *
   * @return array
   */
  public function getRoleAccessAction() {
    $id = $this->_post('id');
    $result = $this->roleaccessService->getRoleAccess($id);
    return $result;
  }

  /**
   * 权限更新
   * @name 更新组权限
   * @param string $menu_ids <POST> 更新的栏目id  多个请使用，进行分隔
   * @param int $role_id <POST> 分组id
   * @return mixed 成功或false
   */
  public function updateRoleAccessAction() {
    $role_id = $this->_post('role_id');
    $menu_ids = $this->_post('menu_ids');
    $result = $this->roleaccessService->updateRoleAccess($role_id, $menu_ids);
    return $result;
  }

}