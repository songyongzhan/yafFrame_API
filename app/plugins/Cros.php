<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/19
 * Time: 11:26
 * Email: 574482856@qq.com
 */

class CrosPlugin extends Yaf_Plugin_Abstract {

  public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {

    if (isAjax()) {
      debugMessage('执行跨域请求放行:' . isAjax());
      header('Access-Control-Allow-Origin: *');
      header('Access-Control-Allow-Methods: GET, POST');
      header('Access-Control-Allow-Headers: X-Requested-With,Uni-Source, X-Access-Token');
      if (isOptions()) {
        header('Access-Control-Max-Age: 86400');
        die;
      }

    }


  }

}