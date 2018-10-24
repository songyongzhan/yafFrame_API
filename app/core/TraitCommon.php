<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/24
 * Time: 17:59
 * Email: songyongzhan@qianbao.com
 */

trait TraitCommon {

  private static $_session = [];

  public function _get($name, $default = NULL, $callback = 'isStr') {
    return $this->_getParam(getRequest()->get($name), $default, $callback);
  }

  public function _post($name, $default = NULL, $callback = 'isStr') {
    return $this->_getParam(getRequest()->getPost($name), $default, $callback);
  }

  public function _server($name = '', $default = NULL, $callback = 'isStr') {
    return $this->_getParam(getRequest()->getServer(empty($name) ? '' : strtoupper($name)), $default, $callback);
  }

  /**
   * 获取此请求所有的参数
   * @return array
   */
  public function getParams() {
    return getRequest()->getParams();
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

  private function _getParam($value, $default, $callback) {
    empty($value) && $value = $default;
    if ($value === NULL) return $value;
    if (isStr($callback)) {
      return function_exists($callback) && $callback === 'isStr' ? trim($value) : (function_exists($callback) ? $callback($value) : $value);
    } else if (is_callable($callback))
      return $callback($value);
  }
}