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
    //把配置保存起来
    $arrConfig = Yaf_Application::app()->getConfig();
    Yaf_Registry::set('config', $arrConfig);
  }

  public function _initTwig(Yaf_Dispatcher $dispatcher) {
    if (!TWIG_INIT_FLAG) return FALSE;
    $view = new TwigAdapter(APPLICATION_PATH . "/static/views", Yaf_Registry::get('config')->get('twig')->toArray());
    Yaf_Dispatcher::getInstance()->setView($view);
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
    $dbconfig = Tools_Config::getConfig('db.mysql');
    Yaf_Registry::set('db', new MysqliDb($dbconfig));
  }

  public function _initPlugin(Yaf_Dispatcher $dispatcher) {
    //注册一个插件
    $objSamplePlugin = new SamplePlugin();
    $dispatcher->registerPlugin($objSamplePlugin);
  }

  public function _initRoute(Yaf_Dispatcher $dispatcher) {
    //在这里注册自己的路由协议,默认使用简单路由
  }


  public function _initView(Yaf_Dispatcher $dispatcher) {
    //在这里注册自己的view控制器，例如smarty,firekylin
  }

}
