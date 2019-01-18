<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/19
 * Time: 11:26
 * Email: 574482856@qq.com
 */

class DecrytPlugin extends Yaf_Plugin_Abstract {

  public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {


    $config = [
      'a' => ['AESDecrypt', [COOKIE_KEY, TRUE]],
      'r' => ['Rsa::Decrypt', [JSPHP_PWD_PRIVKEY, TRUE]],
    ];

    $encrytType = isset($_GET[PREFIX . 'encryt']) ? $_GET[PREFIX . 'encryt'] : '';

    if ($encrytType && isset($config[$encrytType]) && count($config[$encrytType]) === 2) {
      debugMessage('开始自动解密...  类型为:' . $config[$encrytType][0]);
      try {
        foreach ($_GET as $key => &$value) {
          $value && $value = call_user_func_array($config[$encrytType][0], array_merge([$value], $config[$encrytType][1]));
        }

        foreach ($_POST as $key => &$value) {
          $value && $value = call_user_func_array($config[$encrytType][0], array_merge([$value], $config[$encrytType][1]));
        }

      } catch (Exception $e) {

        debugMessage('统一解密异常. [' . $key . ']');

        $value = FALSE; //解密出错
      }

    }


  }

}