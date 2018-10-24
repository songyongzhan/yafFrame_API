<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/17
 * Time: 14:08
 * Email: songyongzhan@qianbao.com
 */

class BaseModel extends CoreModel {

  use TraitCommon;

  public function getdb() {
    return $this->_db;
  }

}