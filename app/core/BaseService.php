<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/23
 * Time: 10:29
 * Email: songyongzhan@qianbao.com
 */

class BaseService extends CoreService {

  /**
   * 统一返回格式化数据
   * @param int $code 业务响应码
   * @param array $result 返回结果值
   * @param string $msg 返回结果信息
   * @param boolean $isEncrypt 是否加密
   * @return array
   */
  public function show(array $result, $code = API_SUCCESS, $msg = '', $isEncrypt = FALSE) {
    if ($isEncrypt) {
      //进行加密

    }
    return [
      'code' => $code,
      'msg' => $msg,
      'result' => $result
    ];
  }
}