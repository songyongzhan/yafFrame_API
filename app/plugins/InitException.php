<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/19
 * Time: 15:09
 * Email: 574482856@qq.com
 */


/**
 *
 * 备注说明
 *
 * 这是项目中使用的exception  与公共无关
 *
 * Class InitExceptionPlugin
 */
class InitExceptionPlugin extends Yaf_Plugin_Abstract {

  public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
    $old_exception = set_exception_handler(NULL);

    set_exception_handler(function ($exception) use ($old_exception) {

      //处理我们的异常信息 在这里可以根据环境替换StatusCode

      $old_exception && $old_exception($exception);

    });

  }

}
