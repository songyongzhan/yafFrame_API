<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/17
 * Time: 14:21
 * Email: 574482856@qq.com
 */


class StatusCode {


  const USER_NOT_EXISTS = 42001409; //您没有访问权限  用户不存在
  const PASSWORD_CANNOT_EMPTY = 42002409; //密码不能为空
  const USERNAME_CANNOT_EMPTY = 42003409; //用户名不能为空
  const REGISTER_FAILURE = 42004409; //注册失败
  const USERNAME_OR_PASSWORD_INVALID = 42005409; //用户名或密码错误
  const USER_IS_DENIED = 42006409; //用户被禁用
  //token相关
  const TOKEN_IS_EMPTY = 42301409; //TOKEN 为空
  const TOKEN_TIMEOUT_EXPIRE = 42302409; //token 过期  登录超时
  const TOKEN_ERROR = 42303409; //token解析错误
  const REMOTEIP_CHANGED = 42202409; //网络环境发生变化
  const DATA_NOT_EXISTS = API_SUCCESS; //数据不存在
  const NO_DATA_CHANGE = 42402409; //没有数据被改变
  const PARAMS_ERROR = 42000409; //请求参数异常
  const URL_MENU_DENIED = 42016409; //您没有权限访问此栏目
  const INSERT_FAILURE = 43501409; //数据插入失败

  const FILE_NOT_EXISTS = 55801409; //目录或文件不存在
  const INTERVACE_ACCESS_ERROR = 55802409; //接口目录异常

  const MESSAGE_CODE = 42007409; //信息码错误
  const ENDIP_LT_STARTIP = 42009409;
  const USER_AUTHENTICATION_FAILURE = 42010409;//用户认证失败
  const SELF_EDIT_SELF = 42011409;//自己不能修改自己
  const SAME_USERNAME_ERROR = 42012409;//此用户已存在，不能重复添加
  const DIRECTORY_CANNOT_BE_WRITTEN = 42014409;
  const STATUS_CACHE_CREATE_FAILURE = 42015409;//状态码缓存文件生成失败
  const UNAUTHORIZED_ACCESS = 42803409;//您无权访问此方法
  const INCONSISTENT_PASSWORD = 42019409;//两次输入密码不一致
  const PASSWORD_ERROR = 42020409; //密码输入错误
  const CANNOT_EDIT_LDAP_PASSWORD = 42021409;//系统不能修改域名密码
  const PLEASE_LOGIN = 42022409;//请登录
  const CODE_ERROR = 42023409;//验证码输入错误
  const RULE_NOT_ARRAY = 42024409;//验证规则不是数组
  const REPORTLIST_NOT_EXISTS = 42025409; //reportlist data 不存在


  /**
   * 如果信息码存在，返回信息，如果不存在返回空
   * @param $code
   * @return mixed|string
   */
  public static function get_code_message($code) {

    $data = [
      42000409 => '请求参数异常',
      42001409 => '您没有访问权限 用户不存在',
      42002409 => '密码不能为空',
      42003409 => '用户名不能为空',
      42004409 => '注册失败',
      42005409 => '用户名或密码错误',
      42006409 => '用户被禁用',
      42007409 => '信息码填写错误',
      42009409 => '结束ip不能小于起始ip',
      42010409 => '用户认证失败',
      42011409 => '自己不能修改自己权限',
      42012409 => '此用户已存在，不能重复添加',
      42014409 => '目录不可写',
      42015409 => '状态码缓存文件生成失败',
      42016409 => '您没有权限访问此栏目',
      42019409 => '密码与确认密码不一致',
      42020409 => '旧密码不正确',
      42021409 => '系统不能修改域控密码',
      42202409 => '网络环境发生变化',
      42022409 => '请登录',
      42301409 => 'USERTOKEN 不存在',
      42302409 => '登录超时，请重新登录',
      42303409 => 'token错误',
      42402409 => '没有数据被改变',
      42803409 => '您无权访问此方法',
      43501409 => '数据添加失败',
      55801405 => '目录或文件不存在',
      55802405 => '接口目录异常',
      42023409 => '验证码输入错误',
      42024409 => '验证规则不是数组',
      42025409 => 'reportlist data 不存在'
    ];

    return isset($data[$code]) ? $data[$code] : '';
  }


}