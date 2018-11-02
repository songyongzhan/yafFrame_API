<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/17
 * Time: 14:11
 * Email: songyongzhan@qianbao.com
 */
require_once APP_PATH . DIRECTORY_SEPARATOR . 'app/configs/api.php';
/*foreach (glob(__DIR__ . '/*.php') as $file) {
  if ($file != __FILE__)
    require_once $file;
}*/

$autoload = [];
$autoload['helper'] = array(
  'helper_funs' => 'funs',
  'StatusCode' => 'StatusCode'
);

$autoload['_autoload'] = array(
  'TraitCommon' => APP_PATH . '/app/core/TraitCommon.php',
  'CoreController' => APP_PATH . '/app/core/CoreController.php',
  'CoreModel' => APP_PATH . '/app/core/CoreModel.php',
  'CoreService' => APP_PATH . '/app/core/CoreService.php',
  'BaseController' => APP_PATH . '/app/core/BaseController.php',
  'BaseModel' => APP_PATH . '/app/core/BaseModel.php',
  'BaseService' => APP_PATH . '/app/core/BaseService.php',
  'ProxyModel' => APP_PATH . '/app/core/ProxyModel.php',
  'CoreBootstrap' => APP_PATH . '/app/core/CoreBootstrap.php',
);

spl_autoload_register(function ($class) use ($autoload) {
  if (isset($autoload['_autoload'][$class]) && is_file($file = $autoload['_autoload'][$class])) {
    Yaf_Loader::import($file);
    return TRUE;
  }

  class_exists('Tools_Request') || Yaf_Loader::import(APP_PATH . '/app/library/Tools/Request.php');
  class_exists('Tools_Config') || Yaf_Loader::import(APP_PATH . '/app/library/Tools/Config.php');

  if (Tools_Config::getConfig('app.modules.load') == TRUE) {   //自动加载service和model
    checkInclude($class);
  }

}, TRUE, FALSE);

//自动加载helpers 文件中指定文件
foreach ($autoload['helper'] as $filename) {
  if (is_file($file = APP_PATH . '/app/helpers/' . $filename . '.php')) {
    Yaf_Loader::import($file);
  }
}


