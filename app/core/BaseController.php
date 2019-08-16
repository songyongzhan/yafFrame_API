<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/17
 * Time: 14:08
 * Email: 574482856@qq.com
 */

defined('APP_PATH') OR exit('No direct script access allowed');

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

  public function _display($templateFile = '') {
    if ($templateFile) {
      if (!strpos($templateFile, '.html'))
        $templateFile .= '.html';
      if ($templateFile[0] !== '/')
        $templateFile = '/' . $templateFile;
    } else
      $templateFile = sprintf('/%s/%s.html', getRequest()->getControllerName(), getRequest()->getActionName());

    $this->getView()->display(strtolower($templateFile));
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
      } else if (strpos($name, 'Model')) { //若调用model不存在，就new BaseModel并重新设置table
        if ((strtolower(getRequest()->getModuleName()) != strtolower(Tools_Config::getConfig('application.dispatcher.defaultModule')))) {
          $baseModel = new BaseModel();
          $baseModel->setTable(strtolower(substr($name, 0, -5)));
          $value = new ProxyModel($baseModel);
          $this->$name = $value;
        }
      }
    }
    return $value;
  }


}
