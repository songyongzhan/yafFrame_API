<?php

/**
 * @name SamplePlugin
 * @desc Yaf定义了如下的6个Hook,插件之间的执行顺序是先进先Call
 * @see http://www.php.net/manual/en/class.yaf-plugin-abstract.php
 * @author root
 */
class MenuCheckPlugin extends Yaf_Plugin_Abstract {
  public function routerShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {

    if (!IS_CHECK_MENU) return;

    if ($router = _parseCurrentUri()) {

      //不需要验证的接口
      $whiteList = [
        'Manage' => ['logout', 'checkToken', 'login', 'password', 'getClientIp', 'searchData', 'getUserInfo', 'password', 'checkCode', 'getCode', 'aa'],
        'Dictionaries' => '*',
        'Exportdata' => ['createCsv'],
        'Menu' => ['getAppointMenuList', 'getList'],
        'Reportlist' => ['getListByreport'],
        'Roleaccess' => ['checkUrl'],
        //'Menu' => ['getOne']
      ];

      $whiteList = array_change_value_case_recursive($whiteList);
      $whiteList = array_change_key_case_recursive($whiteList);

      $controller = strtolower($router['controller']); //控制器
      $action = strtolower($router['action']); //方法

      if ((isset($whiteList[$controller]) && $whiteList[$controller] === '*') || (isset($whiteList[$controller]) && in_array($action, $whiteList[$controller]))) {
        return TRUE;
      }

      $obj = getInstance();
      // isadmin=1 是超级管理员，无需验证
      if ($obj->tokenService->isadmin != 1) {
        $checkResult = $obj->roleaccessService->checkUrl($controller . '/' . $action);
        if ($checkResult['result']['success'] === 0)
          showApiException('您没有权限访问此栏目', StatusCode::URL_MENU_DENIED);
      }
      //验证是否有权限访问


    }

  }
}
