<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/19
 * Time: 15:09
 * Email: songyongzhan@qianbao.com
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

   /*   P('=========InitException==========');
      var_dump($exception);
      P('=========InitException==========');*/

      //var_dump($exception);
      $old_exception && $old_exception($exception);

    });

  }

}
