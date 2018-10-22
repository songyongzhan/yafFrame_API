<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/19
 * Time: 15:09
 * Email: songyongzhan@qianbao.com
 */


/**
 * 公共Log 日志
 *
 * 请不要修改
 *
 * Class CommonLogPlugin
 */
class CommonLogPlugin extends Yaf_Plugin_Abstract {

  public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
    if (!Yaf_Registry::has('log') && import(APP_PATH . DS . 'app/core/Log.php'))
      Yaf_Registry::set('log', new Log());
  }

}
