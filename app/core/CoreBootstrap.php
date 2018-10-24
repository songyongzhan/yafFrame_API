<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/24
 * Time: 15:34
 * Email: songyongzhan@qianbao.com
 */

/**
 * 核心，不要修改这里文件
 * Class BaseBootstrap
 */
class CoreBootstrap extends Yaf_Bootstrap_Abstract {

  public function _initConfig() {

    $arrConfig = Yaf_Application::app()->getConfig();
    Yaf_Registry::set('config', $arrConfig);

    //register initconfig
    $initConfig = new Yaf_Config_Simple(require_once APP_CONFIG_PATH . DS . 'initConfig.php');
    Yaf_Registry::set('initConfig', $initConfig);
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
    $plugins = $initConfig->get('plugins')->toArray();
    foreach ($plugins as $field => $class) {
      $class = ucfirst($class);
      $file = APP_PATH . DS . 'app/plugins/' . $class . '.' . Tools_Config::getConfig('application.ext');
      if (!file_exists($file)) continue;
      $class = strpos($class, 'Plugin') ? $class : $class .= 'Plugin';
      $dispatcher->registerPlugin(new $class());
    }
  }


}