<?php

/**
 * @name SampleModel
 * @desc sample数据获取类, 可以访问数据库，文件，其它系统等
 * @author root
 */
class SampleModel extends BaseModel {

  public function init() {
    echo $this->_get('username');
  }

  public function selectSample() {


  }

  public function insertSample($arrInfo) {
    return TRUE;
  }
}
