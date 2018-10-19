<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/18
 * Time: 10:52
 * Email: songyongzhan@qianbao.com
 */

class Tools_Request {

  private static $_request = NULL;

  public static function getRequest() {
    if (self::$_request === NULL) {
      self::$_request = Yaf_Application::app()->getDispatcher()->getRequest();
    }
    return self::$_request;
  }

  public static function getModuleName() {
    //getModuleName()
    return self::getRequest()->getModuleName();
  }


}