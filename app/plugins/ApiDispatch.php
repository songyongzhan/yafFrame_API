<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/23
 * Time: 9:38
 * Email: 574482856@qq.com
 */

/**
 * 封装API 统一入口文件
 *
 * Class ApiPlugin
 *
 */
class ApiDispatchPlugin extends Yaf_Plugin_Abstract {

  public function preDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
    debugMessage('客户传递参数:' . jsonencode(filterDataToLogFile(getUserData())));
    //如果是api模块，跳转到 ApiBase 的_remap方法 封装统一入口
    $apiInterceptor = Tools_Config::getConfig('api.interceptor');
    $apiInterceptor = array_change_value_case_recursive(explode(',', $apiInterceptor), CASE_LOWER);
    $module = strtolower($request->module);

    //var_dump($request);
    //var_dump($apiInterceptor);
    //var_dump($request->getModuleName());
    //var_dump($request->getRequestUri());
    //
    //exit;

    if ($module === 'common')
      showApiException('Common模块禁止访问', StatusCode::COMMOM_MODULE_DENINE);

    if (in_array($module, $apiInterceptor)) {
      $request->setControllerName('ApiBase');
      $request->setActionName('_remap');
    }
  }


}
