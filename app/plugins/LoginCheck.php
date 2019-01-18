<?php

/**
 * @name SamplePlugin
 * @desc Yaf定义了如下的6个Hook,插件之间的执行顺序是先进先Call
 * @see http://www.php.net/manual/en/class.yaf-plugin-abstract.php
 * @author root
 *
 *
 *
 *
 */


/**
 *
 * LoginCheck只有设置了无登录白名单，那么在menu设置了白名单才能访问。
 *
 * LoginCheck没有通过白名单验证，直接提醒用户登录了。
 *
 * 如果menu中没有设置白名单，那么会验证当前用户是否有权限访问。
 *
 * Class LoginCheckPlugin
 */
class LoginCheckPlugin extends Yaf_Plugin_Abstract {
  public function routerShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {

    if (!IS_CHECK_TOKEN) return;

    if (($router = _parseCurrentUri()) && strtolower($router['module']) === "api") {

      //不需要验证的接口
      $whiteList = [
        'Manage' => ['logout', 'getClientIp', 'checkToken', 'getcode', 'checkcode', 'login', 'searchData', 'aa'],
        'Dictionaries' => '*',
        'Exportdata' => ['createCsv'],
        'Menu' => ['getAppointMenuList'],
        'Roleaccess' => ['checkUrl'],
      ];

      $whiteList = array_change_value_case_recursive($whiteList);
      $whiteList = array_change_key_case_recursive($whiteList);

      $controller = strtolower($router['controller']); //控制器
      $action = strtolower($router['action']); //方法

      if ((isset($whiteList[$controller]) && $whiteList[$controller] === '*') || (isset($whiteList[$controller]) && in_array($action, $whiteList[$controller]))) {
        return TRUE;
      }

      if ($tokenData = get_client_token_data()) {
        $result = getInstance()->manageService->check_token(get_client_token_data());

        //$result['result']['success'] = FALSE;

        if (!$result['result']['success'])
          showApiException('token已超时,请重新登录', StatusCode::TOKEN_TIMEOUT_EXPIRE);
      } else
        showApiException('请登录', StatusCode::PLEASE_LOGIN);
    }

  }
}