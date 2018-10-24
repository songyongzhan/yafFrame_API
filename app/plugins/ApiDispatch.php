<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/23
 * Time: 9:38
 * Email: songyongzhan@qianbao.com
 */

/**
 * 封装API 统一入口文件
 *
 * Class ApiPlugin
 *
 */
class ApiDispatchPlugin extends Yaf_Plugin_Abstract {

  public function preDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {

    //如果是api模块，跳转到 ApiBase 的_remap方法 封装统一入口
    $apiInterceptor = Tools_Config::getConfig('api.interceptor');
    $apiInterceptor = array_change_value_case(explode(',', $apiInterceptor), CASE_LOWER);
    if (in_array(strtolower($request->module), $apiInterceptor)) {
      $request->setControllerName('ApiBase');
      $request->setActionName('_remap');
    }
  }


}
