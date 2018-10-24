<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/23
 * Time: 10:35
 * Email: songyongzhan@qianbao.com
 */

class  ApiException extends Exceptions {


  /**
   * @var int|null|string 业务状态码标识
   */
  private $_httpCode = NULL;

  /**
   * 自定义接口异常类
   * api_Exception constructor.
   * @param string $message 错误信息
   * @param int $code 业务错误提示码
   * @param int $_httpCode http响应码
   * @param Throwable|NULL $previous
   */
  public function __construct($message = '', $code = 0, $httpCode = 200, Throwable $previous = NULL) {
    parent::__construct($message, $code, $previous);
    $this->_httpCode = $httpCode;
  }

  /**
   * 获取httpcode码
   * @return int|null|string
   */
  public function getHttpCode() {
    return $this->_httpCode;
  }


}