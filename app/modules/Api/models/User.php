<?php

/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/18
 * Time: 14:22
 * Email: songyongzhan@qianbao.com
 */
class UserModel extends BaseModel {

  protected function _Encrypt($data) {
    return Rsa::Encrypt($data, RESTHUB_SERVER_PUBLIC);
  }

  protected function _Decrypt($data) {
    return Rsa::Decrypt($data, RESTHUB_CLIENT_PRIVKEY);
  }

  protected function init() {

  }

  public function add() {

  }

  public function del() {

  }


  public function fetchBefore($url, $data) {

    $this->setRequestHeader([
      'Content-Type' => 'application/json',
      'Uni-Source' => 'bank/rhczbank',
      'source' => 'PHP',
      'mplatform' => 'bank',
      'bplatform' => 'rhczbank',
      'platform' => 'rhczbank',
      'version' => '2018-08-09',
      'token' => '',
    ]);

    return jsonencode(['content' => $this->_Encrypt($data ? jsonencode($data) : '')]);

  }

  protected function fetchAfter($url, $data) {
    if (!empty($data['result']) && is_string($data['result'])) {
      if ($data['result'] = $this->_Decrypt($data['result'])) {
        $data['result'] = jsondecode($data['result']);
      } else {
        show_error('decrypt error.');
      }
    }

    return $data;
  }


  protected function fetchFinish($data) {
    if (!is_array($data)) {
      show_error('接口返回结果错误');
    }

    switch ($data['status']) {
      case API_SUCCESS: //成功
        break;
      case '55107651': //登录失败

        break;
      case '60000056': //登录次数过多
        showJsonMsg(API_FAILURE, '请一小时后再登录。');

        break;
      case '60000055': //未设置交易密码

        break;

      case '57001651'://ERROR_LOGIN请重新登录
        break;

      default:
        //默认业务处理
    }

    return $data;
  }

  public function queryBidList($pageNo = 1, $pageSize = 10) {

    $param = [
      'pageNo' => $pageNo,
      'pageSize' => $pageSize,
    ];

    $result = $this->fetchPost('/bank_rhcz/v1/pub/api/queryBidList', $param);

    return $result;


  }

  public function index($array) {

    return array_map(function ($val) {
      return '每一个值都经过了UserModel进行处理: ' . $val;
    }, $array);


  }

}