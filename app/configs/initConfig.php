<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/19
 * Time: 10:45
 * Email: 574482856@qq.com
 */

/**
 * 项目启动配置文件
 *
 */
return [

  /**
   * $key 这个值随便起，个人知道怎么回事就行，没有别的什么要求
   */
  'plugins' => [
    //routerStartup
    'CommonLog' => 'CommonLog', //公共Log 日志
    'CommonException' => 'CommonException', //公共exception
    'InitException' => 'InitException',
    'CrosPlugin' => 'Cros',
    'cli' => 'Cli',
    'decryt' => 'Decryt', //自动解密
    //routerDispatch
    'ApiDispatch' => 'ApiDispatch',

    //只是针对于二维数组
    //只有是在api模块下才执行 如果像上面单独的写法，则全部注册
    //这种带数组的写法，系统会检测是否这个模块，验证成功后，注册
    'Api' => [
      //'LoginCheck' => 'LoginCheck',
      //'MenuCheck' => 'MenuCheck', //暂时关闭
    ],


  ],
  //扩展从数据库
  //'db' => [
  //  'slave1' => [
  //    'host' => '172.28.66.198',
  //    'username' => 'song',
  //    'password' => '123',
  //    'db' => 'test',
  //    'port' => 3306,
  //    'prefix' => '',
  //    'charset' => 'utf8',
  //    'enable' => TRUE
  //  ]
  //]


];
