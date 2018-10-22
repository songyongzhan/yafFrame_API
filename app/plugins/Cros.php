<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/19
 * Time: 11:26
 * Email: songyongzhan@qianbao.com
 */

class CrosPlugin extends Yaf_Plugin_Abstract {

  public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {

    if (isAjax()) {
      $header = [
        'Access-Control-Allow-Methods' => 'GET,POST',
        'Access-Control-Allow-Headers' => ' X-Requested-With, Uni-Source, X-Access-Token',
      ];
      $response->setAllHeaders($header);

      if (isOptions()) {
        $response->setHeader('Access-Control-Max-Age', 86400);
        die;
      }

    }


  }

}