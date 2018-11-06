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


  public function _getCookie($name, $prefix = '', $path = '/', $domain = NULL) {
    if ($prefix === '' && ($configPrefix = Tools_Config::getConfig('cookie.prefix')))
      $configPrefix && $prefix = $configPrefix;

    if (isset($_COOKIE[$prefix . $name]) && ($value = AESDecrypt($_COOKIE[$prefix . $name], COOKIE_KEY))) {
      return jsondecode($value) ?: $value;
    }
    return '';
  }

  public function _setCookie($name, $value, $expire = 0, $domain = NULL, $path = '/', $prefix = '', $secure = FALSE, $httponly = TRUE) {
    if (is_array($name)) {
      // always leave 'name' in last place, as the loop will break otherwise, due to $$item
      foreach (array('value', 'expire', 'domain', 'path', 'prefix', 'secure', 'httponly', 'name') as $item) {
        if (isset($name[$item])) {
          $$item = $name[$item];
        }
      }
    }

    if ($prefix === '' && ($configPrefix = Tools_Config::getConfig('cookie.prefix')))
      $configPrefix && $prefix = $configPrefix;

    if (is_null($domain) && ($configDomain = Tools_Config::getConfig('cookie.domain')))
      $configDomain && $domain = $configDomain;

    if (is_null($path) && ($configPath = Tools_Config::getConfig('cookie.path')))
      $configPath && $path = $configPath;

    if (($configSecure = Tools_Config::getConfig('cookie.secure')))
      $secure = $configSecure;

    if (($configHttponly = Tools_Config::getConfig('cookie.httponly')))
      $httponly = $configHttponly;

    if (!is_numeric($expire))
      $expire = time() - 86500;
    else
      $expire = ($expire > 0) ? time() + $expire : 0;

    if ($value = jsonencode($value))
      $value = AESEncrypt($value, COOKIE_KEY);

    setcookie($prefix . $name, $value, $expire, $path, $domain, $secure, $httponly);
  }

  public function _delCookie($name, $path = '/', $prefix = '', $domain = NULL) {
    if (is_null($domain) && ($configDomain = Tools_Config::getConfig('cookie.domain')))
      $configDomain && $domain = $configDomain;

    if ($prefix === '' && ($configPrefix = Tools_Config::getConfig('cookie.prefix')))
      $configPrefix && $prefix = $configPrefix;

    setcookie($name, '', time() - 1, $path);
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