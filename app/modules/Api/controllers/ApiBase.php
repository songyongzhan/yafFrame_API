<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/23
 * Time: 9:38
 * Email: songyongzhan@qianbao.com
 */

class ApiBaseController extends BaseController {

  /**
   * API 统一入口
   * @param $method
   * @param array $params
   * @throws Exception
   */
  public function _remapAction() {
    try {
      $parseUri = $this->_parseUri();
      $controller = getInstance($parseUri['controller'], $parseUri['module']);
      $data = call_user_func_array([$controller, $parseUri['action'] . 'Action'], $this->getRequest()->getParams());
      $this->showJson($data['result'], $data['code'], $data['msg']);
    } catch (Exception $e) {
      $this->showJson([], API_FAILURE, $e->getMessage());
      //showApiException($e->getMessage(), $e->getCode());
    }
  }


  private function showJson($result, $code = API_SUCCESS, $msg = NULL) {
    $data = [
      'status' => empty($code) ? API_SUCCESS : $code,
      'message' => empty($msg) ? '' : $msg,
      'result' => $result ?: []
    ];
    //header('Content-Type: application/json; charset=utf-8');
    header('Content-Type:text/plain; charset=utf-8');
    echo jsonencode($data);
  }

  /**
   * 获取当前uri中控制器和方法
   * @return array
   * @throws Exceptions
   */
  private function _parseUri() {
    $uri = $this->getRequest()->getRequestUri();
    $data = array_slice(explode('/', trim($uri, '/')), 0, 3);
    if (count($data) !== 3) throw new Exceptions(' Parameter passing error. Please check if there is a module name.', 500);
    return array_combine(['module', 'controller', 'action'], array_map(function ($val) { return ucfirst($val); }, $data));
  }


}
