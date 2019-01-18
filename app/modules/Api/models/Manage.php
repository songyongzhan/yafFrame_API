<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/12/28
 * Time: 11:45
 * Email: 574482856@qq.com
 *
 * 后台用户管理 模型
 */
defined('APP_PATH') OR exit('No direct script access allowed');

class ManageModel extends BaseModel {

  /**
   * 普通用户登录
   * @param $username
   * @param $password
   * @param $platform_id
   * @return array|bool code 1 登录成功   0 密码错误  -1 用户不存在
   */
  public function login($username, $password, $field = ['*']) {
    $login = $this->getOne(
      [
        getWhereCondition('username', $username),
        getWhereCondition('password', $password)
      ]
      , $field);

    if ($login) {
      if ($login['password'] == $password) {
        unset($login['password']);
        $login['login'] = TRUE; //登录成功
        $login['status'] = 1; //登录成功
        return $login;
      } else {
        return ['login' => FALSE, 'status' => 0]; //密码输入错误
      }
    } else {
      return ['login' => FALSE, 'status' => -1]; //用户不存在
    }
  }

  /**
   * 根据用户ID得到验证token的数据
   */
  public function check_token($manage_id) {
    $result = $this->getOne([getWhereCondition('id', $manage_id)], ['id', 'token', 'timeout']);
    return $result;
  }

  /**
   * 更新token时间
   * @param $manage_id
   * @param $timeout
   * @return int
   */
  public function update_token_timeout($manage_id, $timeout) {
    return $this->update($manage_id, ['timeout' => $timeout]);
  }


  /**
   * 返回登录失败信息
   * @param $status
   * @return array
   */
  private function _returnLoginStatus($status) {
    return ['login' => FALSE, 'code' => $status];
  }


}