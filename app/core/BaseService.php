<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/23
 * Time: 10:29
 * Email: 574482856@qq.com
 */

defined('APP_PATH') OR exit('No direct script access allowed');

class BaseService extends CoreService {

  private static $_object = [];

  /**
   * 自动初始化
   */
  public function _init() {

  }

  /**
   * 统一返回格式化数据
   * @param int $code 业务响应码
   * @param array $result 返回结果值
   * @param string $msg 返回结果信息
   * @param boolean $isEncrypt 是否加密
   * @return array
   */
  public function show($result, $code = API_SUCCESS, $msg = '', $isEncrypt = FALSE) {
    if ($isEncrypt) {
      //进行加密

    }
    if ($result && !is_array($result))
      $result = [$result];

    if ($code != API_SUCCESS && empty($msg))
      $msg = StatusCode::get_code_message($code);

    return [
      'code' => $code,
      'msg' => $msg,
      'result' => $result
    ];
  }


  public final function __set($name, $value) {
    (self::$_object)[$name] = $value;
  }

  /**
   * 自动声明变量
   * @param $name
   * @return mixed|null
   */
  public final function __get($name) {
    $value = NULL;
    if (in_array($name, self::$_object) && is_callable((self::$_object)[$name]))
      $value = (self::$_object)[$name]();
    else if (in_array($name, self::$_object))
      $value = (self::$_object)[$name];
    else if (strpos($name, 'Model') || strpos($name, 'Service')) {
      $nameClass = ucfirst($name);
      if (class_exists($nameClass)) {
        (strtolower(getRequest()->getModuleName()) != strtolower(Tools_Config::getConfig('application.dispatcher.defaultModule'))) && checkInclude($nameClass);
        $value = new ProxyModel(new $nameClass());
        $this->$name = $value;
      } else if (strpos($name, 'Model')) { //若调用model不存在，就new BaseModel并重新设置table
        if ((strtolower(getRequest()->getModuleName()) != strtolower(Tools_Config::getConfig('application.dispatcher.defaultModule')))) {
          $baseModel = new BaseModel();
          $baseModel->setTable(strtolower(substr($name, 0, -5)));
          $value = new ProxyModel($baseModel);
          $this->$name = $value;
        }
      }
    }
    return $value;
  }
}