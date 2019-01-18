<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/12/28
 * Time: 11:40
 * Email: 574482856@qq.com
 *
 * 栏目管理 模型
 */
defined('APP_PATH') OR exit('No direct script access allowed');

class MenuModel extends BaseModel {

  /**
   * 批量更新sort排序id
   * @param $data
   * @return bool
   */
  public function batchSort($data) {
    $this->startTransaction();
    foreach ($data as $key => $val) {
      if (!$this->update($val['id'], ['sort_id' => $val['sort_id']]))
        return $this->rollback();
    }
    return $this->commit();
  }


}