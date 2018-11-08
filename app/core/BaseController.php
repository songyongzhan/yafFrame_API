<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/17
 * Time: 14:08
 * Email: songyongzhan@qianbao.com
 */

class BaseController extends CoreController {

  use TraitCommon;

  private static $_object = [];


  /**
   * 构造方法 PS：仿照构造方法
   */
  public function init() {

  }


  public function _render($templateFile) {
    return $this->getView()->render($templateFile);
  }

  public function _display($templateFile) {
    $this->getView()->display($templateFile);
  }

  public function assign($key, $val) {
    $this->getView()->assign($key, $val);
  }


  public final function __set($name, $value) {
    (self::$_object)[$name] = $value;
  }

  /**
   * 自动声明变量
   * @param $name
   * @return mixed|null
   */
  public final function __get($name) {
    $value = NULL;
    if (key_exists($name, self::$_object) && is_callable((self::$_object)[$name]))
      $value = (self::$_object)[$name]();
    else if (key_exists($name, self::$_object))
      $value = (self::$_object)[$name];
    else if (strpos($name, 'Model') || strpos($name, 'Service')) {
      $nameClass = ucfirst($name);
      if (class_exists($nameClass)) {
        (strtolower(getRequest()->getModuleName()) != strtolower(Tools_Config::getConfig('application.dispatcher.defaultModule'))) && checkInclude($nameClass);
        $value = new ProxyModel(new $nameClass());
        $this->$name = $value;
      }
    }
    return $value;
  }


}