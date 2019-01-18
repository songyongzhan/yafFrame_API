<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/24
 * Time: 15:34
 * Email: 574482856@qq.com
 */

/**
 * 核心，不要修改这里文件
 * Class BaseBootstrap
 */

defined('APP_PATH') OR exit('No direct script access allowed');

class CoreBootstrap extends Yaf_Bootstrap_Abstract {

  public function _initConfig() {

    $arrConfig = Yaf_Application::app()->getConfig();
    Yaf_Registry::set('config', $arrConfig);

    //register initconfig
    Yaf_Registry::has('initConfig') || $this->initConfig();
  }

  /**
   * 关闭自动渲染
   * @desc 如果需要开启，注释掉此方法即可 也可以使用 $dispatcher->enableView()
   * @param Yaf_Dispatcher $dispatcher
   */
  public function _initDisabledAutoView(Yaf_Dispatcher $dispatcher) {
    $dispatcher->disableView();
  }

  public function _initPlugin(Yaf_Dispatcher $dispatcher) {
    //注册插件
    $initConfig = Yaf_Registry::get('initConfig');
    if (!$initConfig->get('plugins')) return;
    $plugins = $initConfig->get('plugins')->toArray();

    foreach ($plugins as $field => $class) {
      //针对模块进行注册插件
      if (is_array($class)) {


        if (!_parseCurrentUri())
          continue;

        //如果不是这个模块下的插件，则不要进行加载
        if (strtolower($field) !== strtolower(_parseCurrentUri()['module']))
          continue;

        foreach ($class as $k => $sun) {
          $class = ucfirst($sun);
          $file = APP_PATH . DS . 'app/plugins/' . $class . '.' . Tools_Config::getConfig('application.ext');
          if (!file_exists($file)) continue;
          $sun = strpos($sun, 'Plugin') ? $class : $class .= 'Plugin';
          $dispatcher->registerPlugin(new $sun());
        }

      } else {
        $class = ucfirst($class);
        $file = APP_PATH . DS . 'app/plugins/' . $class . '.' . Tools_Config::getConfig('application.ext');
        if (!file_exists($file)) continue;
        $class = strpos($class, 'Plugin') ? $class : $class .= 'Plugin';
        $dispatcher->registerPlugin(new $class());
      }

    }
  }

  /**
   * 不会被系统直接调用
   */
  protected function initConfig() {
    $initConfig = new Yaf_Config_Simple(require_once APP_CONFIG_PATH . DS . 'initConfig.php');
    Yaf_Registry::set('initConfig', $initConfig);
  }

}
