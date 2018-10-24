<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/19
 * Time: 10:45
 * Email: songyongzhan@qianbao.com
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
    'crosPlugin' => 'Cros',
    'CommonException' => 'CommonException', //公共exception
    'CommonLog' => 'CommonLog', //公共Log 日志
    'InitException' => 'InitException',
    'ApiDispatch' => 'ApiDispatch',
  ]


];