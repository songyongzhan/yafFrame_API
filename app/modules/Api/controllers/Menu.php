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

class MenuController extends ApiBaseController {


  /**
   * 获取栏目列表
   * @name 列表
   * @param int user_type 用途 如果是1 显示栏目列表用于修改 ,不传值 或传0 用于左侧栏目显示
   * @return mixed
   */
  public function getListAction() {

    $where = [];
    $useType = $this->_post('use_type', 0);
    $result = $this->menuService->getList($where, $useType);
    return $result;
  }

  /**
   * 栏目添加
   * @param string $title <POST> 栏目标题
   * @param int $pid <POST> 父级id
   * @param string $url <POST> 栏目url地址
   * @param int $platform_id <POST> 平台id
   * @param string $ext <POST> 扩展信息
   * @param string $relation_url <POST> 扩展url增加额外权限
   * @param int $type_id <POST> 栏目类型 默认 栏目1 方法名2
   * @return mixed 返回栏目信息
   */
  public function addAction() {
    $data = [
      'title' => $this->_post('title'),
      'pid' => $this->_post('pid', ''),
      'url' => $this->_post('url'),
      'ext' => $this->_post('ext', ''),
      'relation_url' => $this->_post('relation_url', ''),
      'type_id' => $this->_post('type_id', 1),
      'sort_id' => $this->_post('sort_id', 999),
      'status' => $this->_post('status')
    ];
    $result = $this->menuService->add($data);
    return $result;
  }

  /**
   * 栏目修改
   * @param string $title <POST> 栏目标题
   * @param int $pid <POST> 父级id
   * @param string $url <POST> 栏目url地址
   * @param int $platform_id <POST> 平台id
   * @param string $ext <POST> 扩展信息
   * @param string $relation_url <POST> 扩展url增加额外权限
   * @param int $type_id <POST> 栏目类型 默认 栏目1 方法名2
   * @return mixed 返回栏目信息
   */
  public function updateAction() {
    $id = $this->_post('id');
    $data = [
      'title' => $this->_post('title'),
      'pid' => $this->_post('pid'),
      'url' => $this->_post('url'),
      'ext' => $this->_post('ext'),
      'relation_url' => $this->_post('relation_url'),
      'type_id' => $this->_post('type_id'),
      'sort_id' => $this->_post('sort_id', 0),
      'status' => $this->_post('status')
    ];
    $result = $this->menuService->update($id, $data);
    return $result;
  }

  /**
   * 获取单个栏目
   * @name 获取栏目
   * @param int $id <POST> 栏目id
   * @return mixed 返回栏目信息
   */
  public function getOneAction() {
    $id = $this->_post('id');
    $result = $this->menuService->getOne($id);
    return $result;
  }

  /**
   * 获取指定的栏目菜单
   * @param string $menu_ids <POST> 栏目ids
   * @param int $platform_id <POST> 平台id
   * @return mixed
   */
  public function getAppointMenuListAction() {
    $menu_ids = $this->_post('menu_ids');
    $platform_id = $this->_post('platform_id');
    $result = $this->menuService->getAppointMenuList($menu_ids, $platform_id);
    return $result;
  }

  /**
   * 删除栏目
   * @name 删除
   * @param int $id <POST> 栏目id
   * @return mixed
   */
  public function deleteAction() {
    $id = $this->_post('id');
    $result = $this->menuService->delete($id);
    return $result;
  }

  /**
   * 批量排序
   * @name 批量排序
   * @param string $sortStr <POST> 更新数据
   * @return mixed
   */
  public function batchSortAction() {
    $sortStr = $this->_post('sort_str');
    $result = $this->menuService->batchSort($sortStr);
    return $result;
  }


}