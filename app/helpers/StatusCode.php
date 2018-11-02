<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/17
 * Time: 14:21
 * Email: songyongzhan@qianbao.com
 */


class StatusCode {

  const MESSAGE_CODE = 2000;


  /**
   * 如果信息码存在，返回信息，如果不存在返回空
   * @param $code
   * @return mixed|string
   */
  public static function get_code_message($code) {

    $data = [
      2000 => '信息码错误'
    ];

    return isset($data[$code]) ? $data[$code] : '';
  }


}