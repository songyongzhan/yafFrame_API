<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/17
 * Time: 14:08
 * Email: songyongzhan@qianbao.com
 */

class CoreModel {

  private $_db = NULL;

  protected $_host      = NULL;
  private   $_curl      = NULL;
  private   $_multipart = FALSE;


  public final function __construct() {
    logMessage('info', 'CoreModel class Initialized.');

    $this->_db = Yaf_Registry::has('db') ? Yaf_Registry::get('db') : NULL;
    $this->init();
  }

  /**
   * 构造方法 PS：仿照构造方法
   */
  public function init() {




  }


  private function _initCurl($url) {
    isset($this->_curl) || $this->_curl = new helperCurl();

    if (substr($url, 0, 6) === 'https:') {
      $this->_curl->setOptions([CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_SSL_VERIFYHOST => 0]);
    }

    $this->_curl->setHeader([
      'Content-Type' => NULL,
      'Client-Ip' => getClientIP(),
      'QBENV' => isset($_SERVER['HTTP_ENV']) ? $_SERVER['HTTP_ENV'] : NULL,
    ]);
  }

  /**
   * 请求前处理数据（加密或组装数据）
   * @param string $url
   * @param array|string $data
   * @return string|array
   */
  protected function fetchBefore($url, $data) {
    return $data;
  }

  /**
   * 请求成功后处理数据（处理解密数据，禁止在此处判断数据状态）
   * @param string $url
   * @param array|boolean $data
   * @return mixed
   */
  protected function fetchAfter($url, $data) {
    return $data;
  }

  /**
   * 请求完成后处理数据（判断数据状态或拼接数据）
   * @param array|boolean $data
   * @return mixed
   */
  protected function fetchFinish($data) {
    return $data;
  }

}