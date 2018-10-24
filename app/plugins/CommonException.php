<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/19
 * Time: 15:09
 * Email: songyongzhan@qianbao.com
 */


/**
 * 公共exception
 *
 * 请不要修改
 *
 * Class CommonExceptionPlugin
 */
class CommonExceptionPlugin extends Yaf_Plugin_Abstract {

  public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {

    set_error_handler('_error_handler');
    set_exception_handler('_exception_handler');
    register_shutdown_function('_shutdown_handler');

    //供 公共方法获取exceptions对象使用
    if (!Yaf_Registry::has('exceptions') && import(APP_PATH . DS . 'app/core/Exceptions.php'))
      Yaf_Registry::set('exceptions', new Exceptions());

  }

}
