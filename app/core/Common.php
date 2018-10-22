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
   * @return mixed
   */
  function app() {
    return Yaf_Application::app();
  }
}

if (!function_exists('getDispatcher')) {
  /**
   * 获取转发对象
   * @return mixed
   */
  function getDispatcher() {
    return Yaf_Dispatcher::getInstance();
  }
}


if (!function_exists('getRequest')) {
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
      $_instance = new $className(getRequest(), isCli() ? new Yaf_Response_Cli() : new Yaf_Response_Http(), Yaf_Registry::get('viewTemplate'));
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