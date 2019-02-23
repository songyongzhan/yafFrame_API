<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/18
 * Time: 15:17
 * Email: 574482856@qq.com
 */

defined('APP_PATH') OR exit('No direct script access allowed');

class ManageService extends BaseService {

  /**
   *
   * 这是另外一种写法
   *
   * @param string $user <require>
   * @param string $password <require>
   * @param int $type <require|lt:25>
   * @param string $message <require>
   * @return mixed
   */
  public function index($user, $password, $type, $message) {

  }

  const FIELD = ['id', 'username', 'fullname', 'mobile', 'email', 'timeout', 'status', 'department', 'ext', 'login_date', 'last_logintime', 'remarks', 'updatetime', 'createtime'];

  /**
   * 用户添加
   * @param string $username <require> 用户名
   * @param string $password <require> 密码
   * @param string $re_password <require|confirm:password> 确认密码|两次密码输入不一致
   * @param string $fullname <require> 姓名
   * @param string $email <email> 邮箱格式不正确
   * @param string $mobile <mobile> 手机格式不正确
   * @return true|false 返回添加结果
   */
  public function add($data) {
    //判断此平台下是否存在这个账号，如果存在直接返回
    $hasManage = $this->manageModel->getList([
      ['field' => 'username', 'val' => $data['username']]
    ], ['id']);


    if ($hasManage) showApiException('此用户已存在，不能重复添加', StatusCode::SAME_USERNAME_ERROR);
    $data['password'] = password_encrypt($data['password']);
    unset($data['re_password']);
    $lastInsertId = $this->manageModel->insert($data);
    if ($lastInsertId) {
      unset($data['password']);
      $data['id'] = $lastInsertId;
      return $this->show($data);
    } else {
      return $this->show([], StatusCode::INSERT_FAILURE);
    }
  }

  /**
   * 用户更新数据
   * @param int $id <require|number> 用户ID
   * @param string $fullname <require> 姓名
   * @param string $email <email> 邮箱
   * @param string $mobile <mobile> 手机格式不正确
   * @param array $data 更新到数据库的数据
   * @return array mixed 返回用户数据
   * @throws Exception
   */
  public function update($id, $data) {
    if (!$data)
      showApiException('请求参数错误', StatusCode::PARAMS_ERROR);

    if (isset($data['password']) && $data['password'] != '') {
      if ($data['password'] !== $data['re_password'])
        showApiException('两次输入密码不一致', StatusCode::INCONSISTENT_PASSWORD);
      $data['password'] = password_encrypt($data['password']);
    } else if (isset($data['password']))
      unset($data['password']);

    if (isset($data['re_password']))
      unset($data['re_password']);

    $result = $this->manageModel->update($id, $data);
    $result && $data['id'] = $id;
    return $result ? $this->show($data) : $this->show([]);
  }

  /**
   * 获取用户列表
   * @param array $where 搜索条件
   * @param int $pageNo 页码
   * @param int $pageSize 每页显示条数
   * @return array
   */
  public function getList($where = [], $page_num, $page_size) {
    $result = $this->manageModel->getListPage($where, self::FIELD, $page_num, $page_size);
    return $result ? $this->show($result) : $this->show([]);
  }

  /**
   * 获取一个用户
   * @param int $id <require|number> 用户ID
   * @param string $fileds 获取字段名称||array
   * @return array
   */
  public function getOne($id) {
    $result = $this->manageModel->getOne($id, self::FIELD);
    unset($result['password']);
    return $result ? $this->show($result) : $this->show([], StatusCode::DATA_NOT_EXISTS);
  }

  /**
   * 用户登录
   * @param string $username <require> 用户名
   * @param string $password <require> 密码
   * @param string $code <require> 验证码
   * @return array mixed 返回用户信息
   * @throws Exception
   */
  public function login($username, $password, $code) {
    if ($code != 0)
      $this->checkCode(getClientIP(), $code);

    $password = password_encrypt($password);
    $loginField = array_merge(['password', 'isadmin'], self::FIELD);
    unset($loginField['timeout'], $loginField['ext'], $loginField['createtime'], $loginField['updatetime']);
    $result = $this->manageModel->login($username, $password, $loginField);
    if ($result['login']) {

      if (!$result['isadmin']) {
        //判断时间区间
        list($startDate, $endDate) = explode('至', $result['login_date']);
        //如果不在这个范围内，则提醒用户登录时间过期
        if (!(time() > strtotime(trim($startDate)) && time() < strtotime(trim($endDate)))) {
          showApiException('登录时间过期或未开通', StatusCode::LOGINDATEEXPIRE);
        }
      }

      $token = create_token($result['id']);
      $timeout = time() + TOKEN_EXPIRE_LONG;
      //更新token
      $this->manageModel->update($result['id'], ['token' => $token, 'timeout' => $timeout, 'last_logintime' => time()]);
      $token_data = [
        'remote_ip' => getClientIP(),
        'src_token' => $token,
        'manage_id' => $result['id'],
        'isadmin' => $result['isadmin'],
        'username' => $result['username']
      ];
      $result['token'] = AESEncrypt(jsonencode($token_data), COOKIE_KEY, TRUE);
      unset($result['login']);
      return $this->show($result);
    } else if ($result['status'] === -1)
      return $this->show([], StatusCode::USER_NOT_EXISTS);
    else if ($result['status'] === 0)
      return $this->show($result, StatusCode::USER_AUTHENTICATION_FAILURE);
  }

  /**
   * 验证token是否通过
   * @return bool
   */
  public function check_token($tokenData) {
    $result = $this->manageModel->check_token($tokenData['manage_id']);
    $flag = FALSE;
    if ($result) {
      //判断token是否过期  token是否一致
      if ($result['token'] == $tokenData['src_token'] && time() < $result['timeout']) {
        $timeout = time() + TOKEN_EXPIRE_LONG;
        $this->_updateTokenTimeout($result['id'], $timeout);
        $flag = TRUE;
        //$data['token'] = $tokenData['src_token'];
        //$data['timeout'] = $timeout;
        $data['manage_id'] = $tokenData['manage_id'];
      }
    }
    $data['success'] = $flag;
    return $this->show($data);
  }


  /**
   * 使用token换取用户数据
   * @return mixed
   */
  public function getUserInfo() {
    $result = $this->manageModel->getOne($this->tokenService->manage_id, self::FIELD);
    return $this->show($result);
  }

  /**
   * 用户退出
   * @return mixed
   */
  public function logout() {
    $this->_updateTokenTimeout($this->tokenService->manage_id, time() - 1);
    return $this->show([], API_SUCCESS, '用户退出成功');
  }

  /**
   * 删除用户数据
   * @param int $id <require> 删除id不能为空
   * @return mixed
   */
  public function delete($id) {
    $result = $this->manageModel->delete($id);
    return $result ? $this->show(['row' => $result, 'id' => $id]) : $this->show([]);
  }

  /**
   * 更新用户的过期时间
   * @param int $manage_id <require|number> 更新用户的ID
   * @param int $timeout <require|number> 更新过期时间戳
   * @return true|false
   */
  private function _updateTokenTimeout($manage_id, $timeout = NULL) {
    $timeout || $timeout = time();
    return $this->manageModel->update_token_timeout($manage_id, $timeout);
  }


  /**
   * 显示两个ip 一个是getClentIp 另一个是服务器的ip
   */
  public function getClientIp() {
    $data = ['client_ip' => ip_long(getClientIP()), 'server_ip' => ip_long($_SERVER['SERVER_ADDR'])];
    return $this->show($data);
  }

  /**
   * 修改用户密码
   * @param int $id <require|number> 用户id
   * @param string $oldPassword <require> 旧密码
   * @param string $newPassword <require> 新密码
   * @param string $rePassword <require|matches[newPassword]> 确认密码
   */
  public function password($id, $oldPassword, $newPassword, $rePassword) {
    $manage = $this->manageModel->getOne($id, 'id,username,password');

    if (!$manage)
      showApiException('用户不存在', StatusCode::USER_NOT_EXISTS);

    if ($manage['password'] != password_encrypt($oldPassword))
      showApiException('旧密码输入错误', StatusCode::PASSWORD_ERROR);

    $result = $this->manageModel->update($id, ['password' => password_encrypt($newPassword), 'last_logintime' => time()]);
    $result && $data['id'] = $id;
    return $result ? $this->show($data) : $this->show([]);
  }

  /**
   *  修改用户权限
   * @param int $id <require|number> 用户id
   * @param string $role_access <require> 权限id
   * @return array
   */
  public function updateManageAccess($id, $role_access) {
    if (is_array($role_access))
      $role_access = implode(',', $role_access);

    $data = ['role_access' => $role_access];
    $result = $this->manageModel->update($id, $data);

    return $result ? $this->show($data) : $this->show([]);
  }

  /**
   * 获取验证码
   * @param string $ip <require> ip
   * @return array
   */
  public function getCode($ip) {
    $code = getRandomStr(4);
    $this->redisModel->redis->setStr('code_ip' . ip_long($ip), $code, TRUE);
    return $this->show(['code' => $code]);
  }

  /**
   * 验证输入的验证码是否成功
   * @param string $ip <require> ip
   * @param string $code <require> 验证码
   * @return array
   * @throws Exception
   */
  public function checkCode($ip, $code) {
    $result = $this->redisModel->redis->getStr('code_ip' . ip_long($ip));
    if (!$result || (strtolower($result) != strtolower($code)))
      showApiException('验证码错误请重新输入', StatusCode::CODE_ERROR);

    return $this->show(['success' => 1]);
  }

  /**
   * 综合返回 搜索需要使用的数据
   */
  public function searchData($type = '') {
    $data = [];
    $default = $data_type = ['ciq', 'country', 'trade', 'transport', 'made', 'transport_mode', 'transaction_mode', 'goods_code', 'shipper', 'specification'];

    if ($type !== "" && in_array($type, $default))
      $data_type = [$type];
    else if ($type !== "" && strpos(',', $type)) {
      $temp = explode(',');
      $data_type = [];
      foreach ($temp as $val) {
        if (in_array($val, $default))
          $data_type[] = $val;
      }
    }


    foreach ($data_type as $val) {
      switch ($val) {
        case 'ciq':
          $ciq = $this->redisModel->redis->hGetAll('ciq');
          foreach ($ciq as $key => $val) {
            $data['export_ciq'][] = [
              'id' => $key,
              'text' => $val
            ];
          }
          break;
        case 'country':
          $country = $this->redisModel->redis->hGetAll('country');

          foreach ($country as $key => $val) {
            $data['dist_country'][] = [
              'id' => $key,
              'text' => $val
            ];
          }
          break;

        case 'trade':
          $trade = $this->redisModel->redis->hGetAll('trade'); //贸易方式

          foreach ($trade as $key => $val) {
            $data['trade_mode'][] = [
              'id' => $key,
              'text' => $val
            ];
          }
          break;

        case 'made':
          $made = $this->redisModel->redis->hGetAll('made'); //原产地
          foreach ($made as $key => $val) {
            $data['madein'][] = [
              'id' => $key,
              'text' => $val
            ];
          }
          break;

        case 'transport_mode':
          $transport = $this->redisModel->redis->hGetAll('transport'); //运输方式

          foreach ($transport as $key => $val) {
            $data['transport_mode'][] = [
              'id' => $key,
              'text' => $val
            ];
          }
          break;

        case 'transaction_mode':
          $transaction_mode = $this->exportdataModel->getViewData('transaction_mode_view');//交易方式
          foreach ($transaction_mode as $key => $val) {
            $data['transaction_mode'][] = [
              'id' => $val['transaction_mode'],
              'text' => $val['transaction_mode']
            ];
          }
          break;

        case 'shipper':
          $shipper = $this->exportdataModel->getViewData('shipper_view'); //货主单位
          foreach ($shipper as $key => $val) {
            $data['shipper'][] = [
              'id' => $val['shipper'],
              'text' => $val['shipper']
            ];
          }
          break;

        case 'specification':
          $specification = $this->exportdataModel->getViewData('specification_view');//规格
          foreach ($specification as $key => $val) {
            $data['specification'][] = [
              'id' => $val['specification'],
              'text' => $val['specification']
            ];
          }
          break;
        case 'goods_code':
          $specification = $this->exportdataModel->getViewData('goods_code_view');//规格
          foreach ($specification as $key => $val) {
            $data['goods_code'][] = [
              'id' => $val['goods_code'],
              'text' => $val['goods_code']
            ];
          }
          break;
      }
    }
    return $this->show($data);
  }

}