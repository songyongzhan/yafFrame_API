<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/17
 * Time: 14:11
 * Email: songyongzhan@qianbao.com
 */

require_once APPLICATION_PATH . '/app/configs/api.php';

$autoload = [];
$autoload['helper'] = array('funs');

$autoload['_autoload'] = array(
  'BaseController' => APPLICATION_PATH . '/app/core/BaseController.php',
  'BaseModel' => APPLICATION_PATH . '/app/core/BaseModel.php',
);

spl_autoload_register(function ($class) use ($autoload) {
  if (isset($autoload['_autoload'][$class]) && is_file($file = $autoload['_autoload'][$class])) {
    Yaf_Loader::import($file);
    return TRUE;
  }

  class_exists('Tools_Request') || Yaf_Loader::import(APPLICATION_PATH . '/app/library/Tools/Request.php');
  class_exists('Tools_Config') || Yaf_Loader::import(APPLICATION_PATH . '/app/library/Tools/Config.php');

  if (Tools_Config::getConfig('app.modules.load') == TRUE) {   //自动加载service和model
    checkInclude($class);
  }

}, TRUE, FALSE);

//自动加载helpers 文件中指定文件
foreach ($autoload['helper'] as $filename) {
  if (is_file($file = APPLICATION_PATH . '/app/helpers/' . $filename . '.php')) {
    Yaf_Loader::import($file);
  }
}


