<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/17
 * Time: 14:08
 * Email: songyongzhan@qianbao.com
 */

class BaseController extends Yaf_Controller_Abstract {

  public static  $_object  = [];
  private static $_session = [];

  /**
   * 构造方法 PS：仿照构造方法
   */
  public function init() {
    Yaf_Loader::import("Smarty/Smarty.class.php");
  }


  public function _get($name, $default = NULL, $callback = 'isStr') {
    return $this->_getParam($this->getRequest()->get($name), $default, $callback);
  }

  public function _post($name, $default = NULL, $callback = 'isStr') {
    return $this->_getParam($this->getRequest()->getPost($name), $default, $callback);
  }

  public function _server($name = '', $default = NULL, $callback = 'isStr') {
    return $this->_getParam($this->getRequest()->getServer(empty($name) ? '' : strtoupper($name)), $default, $callback);
  }

  /**
   * 获取此请求所有的参数
   * @return array
   */
  public function getParams() {
    return $this->getRequest()->getParams();
  }


  public function _getCookie($key, $domain = NULL, $path = '/') {

  }

  public function _setCookie($key, $value, $expire = 0, $domain = NULL, $path = '/') {

  }

  public function _delCookie($name) {

  }

  public function _setSession(String $key, $value = NULL) {
    $this->_initSession();
    (self::$_session)->set($key, $value);
  }

  public function _getSession(String $key) {
    $this->_initSession();
    if (!$this->_hasSession($key)) return FALSE;
    return (self::$_session)->get($key);
  }

  public function _hasSession(String $key) {
    $this->_initSession();
    return (self::$_session)->has($key);
  }

  public function _delSession($key = []) {
    $this->_initSession();
    if (!$key) return;
    if (isStr($key))
      (self::$_session)->del($key);
    elseif (is_array($key)) {
      foreach ($key as $sessionKey) {
        (self::$_session)->del($sessionKey);
      }
    }
  }

  public function _initSession() {
    if (!self::$_session) {
      self::$_session = Yaf_Session::getInstance();
      (self::$_session)->start();
    }
  }


  public function assign($key, $val) {
    $this->getView()->assign($key, $val);
  }


  private function _getParam($value, $default, $callback) {
    empty($value) && $value = $default;
    if ($value === NULL) return $value;
    if (isStr($callback)) {
      return function_exists($callback) && $callback === 'isStr' ? trim($value) : (function_exists($callback) ? $callback($value) : $value);
    } else if (is_callable($callback))
      return $callback($value);
  }

  public final function __set($name, $value) {
    (self::$_object)[$name] = $value;
  }

  public final function __get($name) {
    $value = NULL;
    if (in_array($name, self::$_object) && is_callable((self::$_object)[$name]))
      $value = (self::$_object)[$name]();
    else if (in_array($name, self::$_object))
      $value = (self::$_object)[$name];
    else if (strpos($name, 'Model') || strpos($name, 'Service')) {
      $nameClass = ucfirst($name);
      if (class_exists($nameClass) && checkInclude($nameClass) && ($value = new $nameClass()))
        $this->$name = $value;
    }
    return $value;

  }

  /**
   * 获取项目运行最后的错误信息
   * @return array
   */
  public function getError() {
    return [
      'error_meg' => Yaf_Application::app()->getLastErrorMsg(),
      'error_no' => Yaf_Application::app()->getLastErrorNo(),
    ];
  }

  /**
   * 调用不存在的方法时，调用
   * @param $name
   * @param $arguments
   */
  public function __call($name, $arguments) {

  }


}