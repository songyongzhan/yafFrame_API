<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/17
 * Time: 14:21
 * Email: songyongzhan@qianbao.com
 */

function P($arr, $fontsize = 20, $fun = 'print_r') {
  echo '<pre style="color:blue;font-size:' . $fontsize . 'px;">';
  $fun($arr);
  echo '</pre>';
}


/**
 * 引入类
 * 要求必须是绝对路径
 * @param $file
 */
function import($file) {
  file_exists($file) && Yaf_Loader::import($file);
}

/**
 * 获取application对象
 * @return mixed
 */
function app() {
  return Yaf_Application::app();
}

/**
 * 获取转发对象
 * @return mixed
 */
function getDispatcher() {
  return app()->getDispatcher();
}

function getRequest() {
  return getDispatcher()->getRequest();
}

/**
 * 判断输入的string是否为非空字符串
 * @param string $input
 * @return boolean
 */
function isStr($input) {
  return is_string($input) && isset($input[0]);
}

function isAjax() {
  return app()->getRequest()->isXmlHttpRequest();
}

/**
 * 判断输入的string是否为正整数 (不支持科学计数法)
 * @param string|integer $input
 * @param integer $min 最小值
 * @param integer $max 最大值
 * @return boolean
 */
function isUInt($input, $min = NULL, $max = NULL) {
  $result = ctype_digit(strval($input)); //PHP_INT_MAX
  $result && isset($min) && $result = $input >= $min;
  $result && isset($max) && $result = $input <= $max;
  return $result;
}


function isIE() { //MSIE 10.0;
  return stripos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE;
}

/**
 * 多模块下引入自动引入文件
 *
 * @param $class
 */
function checkInclude($class) {
  $result = FALSE;
  $moduleName = Tools_Request::getModuleName();
  if (strtolower($moduleName) !== 'index') {   //只是加载models 和services
    $loadtype = NULL; //判断加载类型
    if (strpos($class, 'Model'))
      $loadtype = 'model';
    elseif (strpos($class, 'Service'))
      $loadtype = 'service';
    if (!is_null($loadtype)) {
      $file = APPLICATION_PATH . DS . 'app/modules/' . ucfirst($moduleName) . '/' . $loadtype . 's/' . str_replace(ucfirst($loadtype), '', $class) . '.' . Tools_Config::getConfig('application.ext');
      file_exists($file) && $result = TRUE && require_once $file;
      return $result;
    }
  }
}