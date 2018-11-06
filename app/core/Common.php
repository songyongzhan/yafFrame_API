<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/17
 * Time: 14:21
 * Email: songyongzhan@qianbao.com
 */

if (!function_exists('P')) {
  function P($arr, $fun = 'print_r', $fontsize = 20, $color = 'blue') {
    echo '<pre style="color:' . $color . ';font-size:' . $fontsize . 'px;">';
    $fun($arr);
    echo '</pre>';
  }
}

if (!function_exists('Pv')) {
  function Pv($arr, $fun = 'var_dump', $fontsize = 20, $color = 'blue') {
    P($arr, $fun, $fontsize, $color);
  }
}

if (!function_exists('import')) {
  /**
   * 引入类
   * 要求必须是绝对路径
   * @param $file
   */
  function import($file) {
    return file_exists($file) && Yaf_Loader::import($file);
  }
}

if (!function_exists('app')) {
  /**
   * 获取application对象
   * @return Yaf_Application
   */
  function app() {
    return Yaf_Application::app();
  }
}

if (!function_exists('getDispatcher')) {
  /**
   *
   * @return Yaf_Dispatcher
   */
  function getDispatcher() {
    return Yaf_Dispatcher::getInstance();
  }
}


if (!function_exists('getRequest')) {
  /**
   * @return Yaf_Request_Abstract
   */
  function getRequest() {
    return Yaf_Dispatcher::getInstance()->getRequest();
  }
}

if (!function_exists('logMessage')) {
  function logMessage($level, $message) {
    if (!Yaf_Registry::has('log'))
      throw new Exceptions('No log log class was found. Please check if it is registered.', 500);

    Yaf_Registry::get('log')->write_log($level, $message);
  }
}

if (!function_exists('debugMessage')) {
  function debugMessage($message) {
    logMessage('debug', $message);
  }
}

if (!function_exists('getInstance')) {
  /**
   * 获取当前运行controller 实例
   * @param null $controller
   * @param null $moduleName
   * @return null
   * @throws Exceptions
   */
  function getInstance($controller = NULL, $moduleName = NULL) {
    static $_instance = NULL;
    if (is_null($_instance)) {

      $defaultController = Tools_Config::getConfig('application.dispatcher.defaultController') ?: 'Index';
      $controllerName = is_null($controller) ? getRequest()->getControllerName() ?: ucfirst($defaultController) : ucfirst($controller);
      $modules = Tools_Config::getConfig('application.modules');
      $defaultModule = Tools_Config::getConfig('application.dispatcher.defaultModule');
      $moduleName = is_null($moduleName) ? getRequest()->getModuleName() ?: $defaultModule : ucfirst($moduleName);
      $controllerPath = APP_PATH . DS . 'app/controllers';

      if (strpos($modules, ',') && (strtolower($moduleName) !== strtolower($defaultModule)))
        $controllerPath = APP_PATH . DS . 'app/modules/' . ucfirst($moduleName) . '/controllers';

      $file = $controllerPath . DS . $controllerName . '.' . Tools_Config::getConfig('application.ext');

      if (file_exists($file))
        import($file);
      else throw new Exceptions($file . ' file not exists', 500);

      $className = $controllerName . 'Controller';
      $_instance = new $className(getRequest(), isCli() ? new Yaf_Response_Cli() : new Yaf_Response_Http(), Yaf_Registry::has('viewTemplate') ? Yaf_Registry::get('viewTemplate') : new Yaf_View_Simple(TEMPLATE_DIR));
    }
    return $_instance;
  }
}

if (!function_exists('getTime')) {
  /**
   * 获取当前项目运行的环境
   * @return String
   */
  function getTime() {
    return date('Y-m-d H:i:s');
  }
}

if (!function_exists('getEnviron')) {
  /**
   * 获取当前项目运行的环境
   * @return String
   */
  function getEnviron() {
    return Yaf_Application::app()->environ();
  }
}


if (!function_exists('getUri')) {
  /**
   * 获取当前url的路径
   * @return String
   */
  function getUri() {
    return Yaf_Dispatcher::getInstance()->getRequest()->getRequestUri();
  }
}

if (!function_exists('isCli')) {
  function isCli() {
    return getRequest()->isCli();
  }
}

if (!function_exists('isGet')) {
  function isGet() {
    return Yaf_Application::app()->getDispatcher()->getRequest()->isGet();
  }
}

if (!function_exists('isPost')) {
  function isPost() {
    return Yaf_Application::app()->getDispatcher()->getRequest()->isPost();
  }
}

if (!function_exists('isOptions')) {
  function isOptions() {
    return Yaf_Application::app()->getDispatcher()->getRequest()->isOptions();
  }
}

if (!function_exists('isIE')) {
  /**
   * 是否被路由 跳转过来的
   * @return bool
   */
  function isRouted() {
    return Yaf_Application::app()->getDispatcher()->getRequest()->isRouted();
  }
}


if (!function_exists('isStr')) {
  /**
   * 判断输入的string是否为非空字符串
   * @param string $input
   * @return boolean
   */
  function isStr($input) {
    return is_string($input) && isset($input[0]);
  }
}

if (!function_exists('isAjax')) {
  function isAjax() {
    return getRequest()->isXmlHttpRequest();
  }
}
if (!function_exists('isUInt')) {
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
}

if (!function_exists('isIE')) {
  function isIE() { //MSIE 10.0;
    return stripos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE;
  }
}

if (!function_exists('checkInclude')) {
  /**
   * 多模块下引入自动引入文件
   *
   * @param $class
   */
  function checkInclude($class) {
    $result = FALSE;
    $moduleName = Tools_Request::getModuleName();
    if (strtolower($moduleName) !== strtolower(Tools_Config::getConfig('application.dispatcher.defaultModule'))) {   //只是加载models 和services
      $loadtype = NULL; //判断加载类型
      if (strpos($class, 'Model'))
        $loadtype = 'model';
      elseif (strpos($class, 'Service'))
        $loadtype = 'service';
      if (!is_null($loadtype)) {
        $file = APP_PATH . DS . 'app/modules/' . ucfirst($moduleName) . '/' . $loadtype . 's/' . str_replace(ucfirst($loadtype), '', $class) . '.' . Tools_Config::getConfig('application.ext');
        file_exists($file) && $result = TRUE && require_once $file;
        return $result;
      }
    }
  }
}

if (!function_exists('_exception_handler')) {
  /**
   * Exception Handler
   *
   * @param  Exception $exception
   * @return  void
   */
  function _exception_handler($exception) {
    $_error = $_error = Yaf_Registry::get('exceptions');
    $_error->log_exception('error', 'Exception: ' . $exception->getMessage());

    isCli() OR set_status_header(500);

    //P($exception);
    // Should we display the error?
    if (str_ireplace(array('off', 'none', 'no', 'false', 'null'), '', ini_get('display_errors'))) {

      $_error->show_exception($exception);
    }

    exit(1); // EXIT_ERROR
  }
}


if (!function_exists('_error_handler')) {
  /**
   * Error Handler
   *
   * @param  int $severity
   * @param  string $message
   * @param  string $filepath
   * @param  int $line
   * @return  void
   */
  function _error_handler($severity, $message, $filepath, $line) {
    $is_error = (((E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR | E_USER_ERROR) & $severity) === $severity);
    if ($is_error) {
      set_status_header(500);
    }
    if (($severity & error_reporting()) !== $severity) {
      return;
    }
    $_error = Yaf_Registry::get('exceptions');
    $_error->log_exception($severity, $message, $filepath, $line);

    // Should we display the error?
    if (str_ireplace(array('off', 'none', 'no', 'false', 'null'), '', ini_get('display_errors'))) {
      $message = sprintf('servir:%s msssage: %s filepath:%s line %s', $severity, $message, $filepath, $line);
      $_error->show_error($severity, $message);
    }

    if ($is_error) {
      exit(1); // EXIT_ERROR
    }
  }
}

if (!function_exists('set_status_header')) {
  /**
   * Set HTTP Status Header
   *
   * @param  int  the status code
   * @param  string
   * @return  void
   */
  function set_status_header($code = 200, $text = '') {

    if (isCli()) {
      return;
    }

    if (empty($code) OR !is_numeric($code)) {
      show_error('Status codes must be numeric', 500);
    }

    if (empty($text)) {
      is_int($code) OR $code = (int)$code;
      $stati = array(
        100 => 'Continue',
        101 => 'Switching Protocols',

        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',

        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',

        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        422 => 'Unprocessable Entity',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',

        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        511 => 'Network Authentication Required',
      );

      if (isset($stati[$code])) {
        $text = $stati[$code];
      } else {
        show_error('No status text available. Please check your status code number or supply your own message text.', 500);
      }
    }

    if (strpos(PHP_SAPI, 'cgi') === 0) {
      header('Status: ' . $code . ' ' . $text, TRUE);
    } else {
      $server_protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
      if (!headers_sent())
        header($server_protocol . ' ' . $code . ' ' . $text, TRUE, $code);
    }
  }
}

if (!function_exists('show_error')) {
  /**
   * Error Handler
   * @param  string
   * @param  int
   * @param  string
   * @return  void
   */
  function show_error($message, $status_code = 500, $heading = 'An Error Was Encountered') {
    isAjax() && showJsonMsg($status_code, $message);
    $status_code = abs($status_code);
    if ($status_code < 100) {
      $exit_status = $status_code + 9; // 9 is EXIT__AUTO_MIN
      if ($exit_status > 125) // 125 is EXIT__AUTO_MAX
      {
        $exit_status = 1; // EXIT_ERROR
      }

      $status_code = 500;
    } else {
      $exit_status = 1; // EXIT_ERROR
    }

    $_error = Yaf_Registry::get('exceptions');
    echo $_error->show_error($heading, $message, 'error_general', $status_code);
    exit($exit_status);
  }
}

if (!function_exists('_shutdown_handler')) {
  /**
   * Shutdown Handler
   * @return  void
   */
  function _shutdown_handler() {
    $last_error = error_get_last();
    if (isset($last_error) &&
      ($last_error['type'] & (E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING))) {
      _error_handler($last_error['type'], $last_error['message'], $last_error['file'], $last_error['line']);
    }
  }
}

if (!function_exists('cutstr')) {
  /**
   * [cutstr 汉字切割]
   * @param  [string] $string [需要切割的字符串]
   * @param  [string] $length [显示的长度]
   * @param  string $dot [切割后面显示的字符]
   * @return [string]         [切割后的字符串]
   */
  function cutstr($string, $length, $dot = '...') {
    if (strlen($string) <= $length) {
      return $string;
    }
    $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
    $strcut = '';
    $n = $tn = $noc = 0;
    while ($n < strlen($string)) {
      $t = ord($string[$n]);
      if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
        $tn = 1;
        $n++;
        $noc++;
      } elseif (194 <= $t && $t <= 223) {
        $tn = 2;
        $n += 2;
        $noc += 2;
      } elseif (224 <= $t && $t < 239) {
        $tn = 3;
        $n += 3;
        $noc += 2;
      } elseif (240 <= $t && $t <= 247) {
        $tn = 4;
        $n += 4;
        $noc += 2;
      } elseif (248 <= $t && $t <= 251) {
        $tn = 5;
        $n += 5;
        $noc += 2;
      } elseif ($t == 252 || $t == 253) {
        $tn = 6;
        $n += 6;
        $noc += 2;
      } else {
        $n++;
      }
      if ($noc >= $length) {
        break;
      }
    }
    if ($noc > $length) {
      $n -= $tn;
    }
    $strcut = substr($string, 0, $n);
    $strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
    return $strcut . $dot;
  }
}

if (!function_exists('getPassedHours')) {
  /**
   * [getPassedHours 某时间戳到现在所经过的时间]
   * @param  [int] $distence [时间戳]
   * @return [string]           [秒/分钟/小时]
   */
  function getPassedHours($distence) {
    $passed = "";
    switch ($distence) {
      case ($distence < 60):
        {
          $passed = $distence . "秒";
          break;
        }
      case ($distence > 60 && $distence < 60 * 60):
        {
          $passed = intval($distence / 60) . "分钟";
          break;
        }
      case ($distence > 60 * 60):
        {
          $passed = sprintf("%.1f", $distence / (60 * 60)) . "小时";
          break;
        }
    }

    return $passed;
  }
}


if (!function_exists('wtrim')) {
  /**
   * 增加了全角转半角的trim
   *
   * @param  string $str 原字符串
   * @return  string  $str    转换后的字符串
   */
  function wtrim($str) {
    return trim(sbc2abc($str));
  }
}


if (!function_exists('sbc2abc')) {
  /**
   * 全角转半角
   *
   * @param  string $str 原字符串
   * @return  string  $str    转换后的字符串
   */
  function sbc2abc($str) {
    $f = array('　', '０', '１', '２', '３', '４', '５', '６', '７', '８', '９', 'ａ', 'ｂ', 'ｃ', 'ｄ', 'ｅ', 'ｆ', 'ｇ', 'ｈ', 'ｉ', 'ｊ', 'ｋ', 'ｌ', 'ｍ', 'ｎ', 'ｏ', 'ｐ', 'ｑ', 'ｒ', 'ｓ', 'ｔ', 'ｕ', 'ｖ', 'ｗ', 'ｘ', 'ｙ', 'ｚ', 'Ａ', 'Ｂ', 'Ｃ', 'Ｄ', 'Ｅ', 'Ｆ', 'Ｇ', 'Ｈ', 'Ｉ', 'Ｊ', 'Ｋ', 'Ｌ', 'Ｍ', 'Ｎ', 'Ｏ', 'Ｐ', 'Ｑ', 'Ｒ', 'Ｓ', 'Ｔ', 'Ｕ', 'Ｖ', 'Ｗ', 'Ｘ', 'Ｙ', 'Ｚ', '．', '－', '＿', '＠');
    $t = array(' ', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '.', '-', '_', '@');
    $str = str_replace($f, $t, $str);
    return $str;
  }
}


/**
 * 转换为 JSON 字符串
 * @param mixed $data 数据
 * @param boolen $forceObject 强制将索引或空数组转换为对象
 * @return mixed
 */
function jsonencode($data, $forceObject = FALSE) {
  $option = (PHP_VERSION >= '5.4.0' ? JSON_UNESCAPED_UNICODE : 0) | ($forceObject ? JSON_FORCE_OBJECT : 0);
  $option = $option | (PHP_VERSION >= '5.5.0' ? JSON_PARTIAL_OUTPUT_ON_ERROR : 0);

  return is_array($data) || $forceObject ? json_encode($data, $option) : $data;
}

function jsondecode($data, $forceObject = FALSE) {
  $result = json_decode($data, !$forceObject, 512, JSON_BIGINT_AS_STRING);

  if ($forceObject)
    is_object($result) || $result = FALSE;
  else
    is_array($result) || $result = FALSE;

  return $result;
}

/**
 * 抛出异常 API异常
 * @param string 出错提示
 * @param string 异常对象名
 * @throws Exception
 */
function showApiException($message = API_FAILURE_MSG, $code = API_FAILURE, $httpcode = 200, $exception = 'ApiException') {
  throw new $exception($message, $code, $httpcode);
}


if (!function_exists('validate')) {
  function validate($rules, $data, $msg = []) {
    empty($rules) && show_error('Validation rule array cannot be empty.');
    empty($data) && show_error('Validation data can not be empty.');
    strlen(implode('', array_values($msg))) === 0 && $msg = [];
    $validate = Validate::make($rules, $msg);
    if ($validate->check($data)) {
      return TRUE;
    } else
      return ['result' => FALSE, 'errMsg' => $validate->getError()];
  }
}

if (!function_exists('array_change_value_case')) {
  function array_change_value_case($input, $case = CASE_LOWER) {
    $aRet = array();

    if (!is_array($input)) {
      return $aRet;
    }

    foreach ($input as $key => $value) {
      if (is_array($value)) {
        $aRet[$key] = array_change_value_case($value, $case);
        continue;
      }

      $aRet[$key] = ($case == CASE_UPPER ? strtoupper($value) : strtolower($value));
    }

    return $aRet;
  }
}

if (!function_exists('getClientIP')) {
  function getClientIP() { //HTTP_X_FORWARDED_FOR
    foreach (['HTTP_CLIENT_IP', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'] as $value) {
      if (!empty($_SERVER[$value])) return $_SERVER[$value];
    }
  }
}


if (!function_exists('isLocalhost')) {
  function isLocalhost() {
    return strpos($_SERVER['HTTP_HOST'], '127.0.0.1') === 0;
  }
}


function getCallerFromTrace() {
  //产生一条回溯跟踪 参数：忽略args的索引，包括所有的function/method参数，以节省内存
  $traces = array_reverse(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
  //$traces = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

  $apiInterceptor = array_change_value_case(explode(',', Tools_Config::getConfig('api.interceptor')), CASE_LOWER);
  $moduleName = Tools_Request::getModuleName();
  if (strtolower($moduleName) !== strtolower(Tools_Config::getConfig('application.dispatcher.defaultModule')) && in_array(strtolower($moduleName), $apiInterceptor)) {
    $uri = _parseCurrentUri();
    $file = MODULES_PATH . DS . $moduleName . DS . 'controllers/' . $uri['controller'] . '.' . Tools_Config::getConfig('application.ext');
  } else {
    $file = APP_PATH . DS . 'app/controllers/' . (getRequest()->getControllerName()) . '.' . Tools_Config::getConfig('application.ext');
  }
  $index = NULL;
  foreach ($traces as $key => $val) {
    if (isset($val['file']) && ($val['file'] == $file)) {
      $index = $key;
      break;
    }
  }
  $result = is_null($index) ? NULL : isset($traces[$index]) ? $traces[$index] : NULL;

  if (!$result) return '';
  $callerFile = str_replace(APP_PATH, '', $result['file']);
  return sprintf(' %s line [%d]', $callerFile, $result['line']);
}


if (!function_exists('_parseCurrentUri')) {
  function _parseCurrentUri() {
    $uri = getRequest()->getRequestUri();
    $data = array_slice(explode('/', trim($uri, '/')), 0, 3);
    if (count($data) !== 3) throw new Exceptions(' Parameter passing error. Please check if there is a module name.', 500);
    return array_combine(['module', 'controller', 'action'], array_map(function ($val) { return ucfirst($val); }, $data));
  }
}


/**
 * 输出提示信息JSON
 * @param mixed $code 状态码
 * @param string $msg 提示信息
 * @param string $url 跳转链接
 */
function showJsonMsg($code, $msg = NULL, $url = '') {
  header('Content-Type: text/plain; charset=utf-8'); //application/json  text/plain IE8-
  die(jsonencode(_getJson($code, $msg, $url)));
}

function _getJson($code, $msg, $url) {
  if (is_array($code)) {
    $msg = $code['message'];
    $code = $code['status'];
  }
  $result['code'] = $code;
  $result['message'] = $msg;
  isset($url) && $result['url'] = strval($url);
  return $result;
}


/**
 * Convert PHP tags to entities
 *
 * @param  string
 * @return  string
 */
function encode_php_tags($str) {
  return str_replace(array('<?', '?>'), array('&lt;?', '?&gt;'), $str);
}


function base64encode($data, $urlsafe = FALSE) {
  $data = base64_encode($data);

  return $urlsafe ? strtr($data, '+/', '-_') : $data;
}

function base64decode($data, $urlsafe = FALSE) {
  return base64_decode($urlsafe ? strtr($data, '-_', '+/') : $data);
}

function AESEncrypt($data, $key, $urlsafe = FALSE) { //openssl_get_cipher_methods
  if ($data && $key) {
    $iv = openssl_random_pseudo_bytes(16);
    $data = base64encode($iv . openssl_encrypt($data, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv), $urlsafe);
  }

  return $data;
}

function AESDecrypt($data, $key, $urlsafe = FALSE) {
  if (strlen($data) >= 16 + 16 && $key) {
    $data = base64decode($data, $urlsafe);
    $data = openssl_decrypt(substr($data, 16), 'aes-128-cbc', $key, OPENSSL_RAW_DATA, substr($data, 0, 16));
  }

  return $data;
}

function DESEncrypt($data, $key, $urlsafe = FALSE) {
  if ($data && $key) {
    $data = openssl_encrypt($data, 'des-ecb', $key);

    $urlsafe && $data = strtr($data, '+/', '-_');
  }

  return $data;
}

function DESDecrypt($data, $key, $urlsafe = FALSE) {
  if ($data && $key) {
    $urlsafe && $data = strtr($data, '-_', '+/');

    $data = openssl_decrypt($data, 'des-ecb', $key);
  }

  return $data;
}