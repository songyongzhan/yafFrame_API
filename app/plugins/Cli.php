<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/19
 * Time: 11:26
 * Email: 574482856@qq.com
 */

class CliPlugin extends Yaf_Plugin_Abstract {

  public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {


    if (isCli()) {
      global $argc, $argv;


      if ($argc > 1) {

        $requstUri = $argv [1];

        $_SERVER['m_requesturi']=$requstUri;

        $request->setRequestUri($requstUri);

        $requstUri = str_replace('request_uri=', '', $requstUri);
        $requestUriArr = explode('/', trim($requstUri, '/'));

        if (count($requestUriArr) < 3) {
          printf("==================\n必须指定模块名\n==================");
        }


        $moduleName = $requestUriArr[0];
        $controllerClass = $requestUriArr[1];
        $actionName = $requestUriArr[2];


        $module = strtolower($moduleName);
        $modules = Yaf_Application::app()->getModules();


        if (in_array(ucfirst($module), $modules)) {
          $request->setModuleName($module);
        }


        $request->setActionName($actionName);

        $request->setControllerName(ucfirst(strtolower($controllerClass)));


        $params = array_slice($argv, 2);

        //如果不是成对出现，则剔除最后一个
        if (count($params) % 2 > 0)
          array_pop($params);

        for ($i = 0; $i < count($params); $i = $i + 2) {
          $request->setParam($params[$i], $params[$i + 1]);
        }

      }
    }


  }

}
