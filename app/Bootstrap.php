<?php

/**
 * @name Bootstrap
 * @author root
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf_Bootstrap_Abstract {

  public function _initConfig() {

    $arrConfig = Yaf_Application::app()->getConfig();
    Yaf_Registry::set('config', $arrConfig);

    //register initconfig
    $initConfig = new Yaf_Config_Simple(require_once APP_CONFIG_PATH . DS . 'initConfig.php');
    Yaf_Registry::set('initConfig', $initConfig);
  }

  /* public function _initError(Yaf_Dispatcher $dispatcher){
     $dispatcher->setErrorHandler(
       function($err_code,$err_message,$err_file,$err_line,$err_context){
         P('--------------');
         P($err_message);

         P($err_code);
         P($err_file);
         P($err_line);
         //P($err_context);

         P('--------------');

       },-1);
   }*/

  public function _initTwig(Yaf_Dispatcher $dispatcher) {
    if (!TWIG_INIT_FLAG) return FALSE;
    $view = new Twig(APP_PATH . "/static/views", Yaf_Registry::get('config')->get('twig')->toArray());
    $dispatcher->setView($view);
    Yaf_Registry::set('viewTemplate', $view);
  }

  /**
   * 关闭自动渲染
   * @desc 如果需要开启，注释掉此方法即可 也可以使用 $dispatcher->enableView()
   * @param Yaf_Dispatcher $dispatcher
   */
  public function _initDisabledAutoView(Yaf_Dispatcher $dispatcher) {
    $dispatcher->disableView();
  }


  public function _initDatabase(Yaf_Dispatcher $dispatcher) {
    if (!DB_INIT_FLAG) return FALSE;
    $dbconfig = Tools_Config::getConfig('db.mysql');
    Yaf_Registry::set('db', new MysqliDb($dbconfig));
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


  public function _initRoute(Yaf_Dispatcher $dispatcher) {
    //在这里注册自己的路由协议,默认使用简单路由
    $routerConfig = new Yaf_Config_Simple(require_once APP_CONFIG_PATH . DS . 'routerConfig.php');
    //var_dump($routerConfig->toArray());
  }


  public function _initView(Yaf_Dispatcher $dispatcher) {
    //在这里注册自己的view控制器，例如smarty,firekylin
  }

}
