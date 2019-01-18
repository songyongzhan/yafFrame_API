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
 * 强制模板渲染
 *
 * 控制器不存在也能强制显示出来
 *
 * 这是项目中使用的exception  与公共无关
 *
 * Class InitExceptionPlugin
 */
class TransparentPlugin extends Yaf_Plugin_Abstract {

  public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
    $old_exception = set_exception_handler(NULL);

    set_exception_handler(function ($exception) use ($old_exception) {

      //处理我们的异常信息 在这里可以根据环境替换StatusCode

      $old_exception && $old_exception($exception);

    });


    /**
     * $errcontext 错误上下文
     *
     */
    $old_error_handler = set_error_handler(NULL);
    set_error_handler(function ($errno, $errmessage, $filepath, $line, $errcontext) use ($old_error_handler) {

      if (stristr($errmessage, 'No such file or directory') && strtolower(Tools_Request::getModuleName()) === 'index') {
        $controller = getInstance('manage');
        $data = _parseCurrentUri();
        debugMessage('强制模板输出: 控制器不存在，通过模拟，渲染指定页面');
        debugMessage($errmessage);
        $controller->_display($data['controller'] . '/' . $data['action']);

      } else
        $old_error_handler && $old_error_handler($errno, $errmessage, $filepath, $line);

    });


  }

}
