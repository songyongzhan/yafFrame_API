<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/17
 * Time: 14:08
 * Email: songyongzhan@qianbao.com
 */

class BaseModel {

  private $_db = NULL;

  public final function __construct() {
    $this->_db = Yaf_Registry::get('db');
    $this->init();
  }

  /**
   * 构造方法 PS：仿照构造方法
   */
  public function init() {

  }



  public function getdb() {
    return $this->_db;
  }


}