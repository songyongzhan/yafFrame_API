<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/12/28
 * Time: 11:40
 * Email: 574482856@qq.com
 *
 * 权限分组管理 模型
 */
defined('APP_PATH') OR exit('No direct script access allowed');

class RoleModel extends BaseModel {

  /**
   * 重写父类删除方法
   * @param int $id
   * @return bool|void
   */
  public function roleDelete($id) {

    //首先判断此组下是否有用户，如果存在，就不能删除
    $count = $this->manage_roleModel->getCount([
      ['field' => 'role_id', 'val' => $id]
    ]);

    //如果count大于0 则需要删除此分组下的用户然后再来删除
    if ($count > 0)
      return ['type' => $count];
    else
      return $this->delete($id);
  }

}