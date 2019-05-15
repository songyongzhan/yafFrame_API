<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/17
 * Time: 14:11
 * Email: 574482856@qq.com
 */

date_default_timezone_set('PRC');
require_once APP_PATH . DIRECTORY_SEPARATOR . 'app/configs/api.php';
/*foreach (glob(__DIR__ . '/*.php') as $file) {
  if ($file != __FILE__)
    require_once $file;
}*/

$autoload = [];

//=================核心加载 勿动 开始==============================
$autoload['_autoload'] = [
  'TraitCommon' => APP_PATH . '/app/core/TraitCommon.php',
  'CoreController' => APP_PATH . '/app/core/CoreController.php',
  'Model' => APP_PATH . '/app/core/Model.php',
  'CoreModel' => APP_PATH . '/app/core/CoreModel.php',
  'CoreService' => APP_PATH . '/app/core/CoreService.php',
  'BaseController' => APP_PATH . '/app/core/BaseController.php',
  'BaseModel' => APP_PATH . '/app/core/BaseModel.php',
  'BaseService' => APP_PATH . '/app/core/BaseService.php',
  'ProxyModel' => APP_PATH . '/app/core/ProxyModel.php',
  'CoreBootstrap' => APP_PATH . '/app/core/CoreBootstrap.php',
];

$autoload['helper'] = [
  'helper_funs' => 'funs',
  'StatusCode' => 'StatusCode'
];
//=================核心加载 勿动 结束==============================


//=================业务加载项==============================
$autoload['business_autoload'] = [

];

$autoload['business_helper'] = [
  'RpcTrait' => 'RpcTrait'
];

//=================业务加载项==============================


$autoload['_autoload'] = $autoload['_autoload'] + $autoload['business_autoload'];
$autoload['helper'] = $autoload['helper'] + $autoload['business_helper'];

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

if (isset($_SERVER['REQUEST_URI'])) {
  $pattern = '/.*\.(ico|jpg|png|gif|css|js)/i';
  if (preg_match($pattern, $_SERVER['REQUEST_URI'])) {
    $server_protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
    header('status:404');
    header($server_protocol . " 404 No Found.");

    exit;
  }
}

//自动加载helpers 文件中指定文件
foreach ($autoload['helper'] as $filename) {
  if (is_file($file = APP_PATH . '/app/helpers/' . $filename . '.php')) {
    Yaf_Loader::import($file);
  }
}


