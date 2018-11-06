<?php

/**
 * 自定义bootstrap
 *
 * 可以增加_init 开始的方法，系统会自动执行
 *
 *
 * _init
 * Class Bootstrap
 */
class Bootstrap extends CoreBootstrap {


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

  /**
   * 启动数据库
   * @param Yaf_Dispatcher $dispatcher
   * @return bool
   */
  public function _initDatabase(Yaf_Dispatcher $dispatcher) {
    if (!DB_INIT_FLAG) return FALSE;
    $dbconfig = Tools_Config::getConfig('db.mysql');
    $db = new MysqliDb($dbconfig);
    //$db->setQueryOption([MYSQLI_OPT_INT_AND_FLOAT_NATIVE => TRUE]);
    Yaf_Registry::set('db', $db);
  }

  public function _initTwig(Yaf_Dispatcher $dispatcher) {
    if (!TWIG_INIT_FLAG) return FALSE;
    $view = new Twig(APP_PATH . "/static/views", Tools_Config::getConfig('twig'));
    $dispatcher->setView($view);
    Yaf_Registry::set('viewTemplate', $view);
  }

  /*public function _initRoute(Yaf_Dispatcher $dispatcher) {
    //在这里注册自己的路由协议,默认使用简单路由
    new Yaf_Config_Simple(require_once APP_CONFIG_PATH . DS . 'routerConfig.php');
  }*/


  /*  public function _initView(Yaf_Dispatcher $dispatcher) {
      //在这里注册自己的view控制器，例如smarty,firekylin

    }*/

}
