<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/19
 * Time: 16:09
 * Email: songyongzhan@qianbao.com
 */

/**
 * 系统错误日志系统，请不要修改
 * Class Exceptions
 */
class Exceptions extends Yaf_Exception {

  public function __construct($message = '', $code = 0, Throwable $previous = NULL) {

    $this->errorTemplatePath = ERROR_TEMPLATE_PATH;
    $this->errorFileName = ERROR_FILENAME;
    $this->exceptionFileName = EXCEPTION_FILENAME;
    parent::__construct($message, $code, $previous);
  }


  public function show_exception($exception, $template = NULL) {
    is_null($template) || $this->exceptionFileName = $template;
    
    $message = $exception->getMessage();
    if (empty($message)) {
      $message = '(null)';
    }

    ob_start();
    $file = $this->errorTemplatePath . DS . trim($this->exceptionFileName, '/');
    file_exists($file) && require_once $file;
    $buffer = ob_get_contents();
    ob_end_clean();
    echo $buffer;
  }

  public function log_exception($type, $message = NULL, $file = NULL, $line = NULL) {
    is_null($message) && $message = $this->getMessage();
    is_null($file) && $file = $this->getFile();
    is_null($line) && $line = $this->getLine();

    debugMessage(sprintf('异常信息:%s in %s line %s', $message, $file, $line));
  }

  public function log_error($severity, $message = NULL, $file = NULL, $line = NULL) {
    is_null($message) && $message = $this->getMessage();
    is_null($file) && $file = $this->getFile();
    is_null($line) && $line = $this->getLine();
    debugMessage(sprintf('severity:%s 错误信息:%s in %s line %s', $severity, $message, $file, $line));
  }

  public function show_error($exception, $message, $template = NULL, $status_code = 500) {
    is_null($template) || $this->errorFileName = $template;

    if (isCli()) {
      $message = "\t" . (is_array($message) ? implode("\n\t", $message) : $message);
      echo $message;
      exit;
    }

    set_status_header($status_code);
    $message = '<p>' . (is_array($message) ? implode('</p><p>', $message) : $message) . '</p>';
    ob_start();
    $file = $this->errorTemplatePath . DS . trim($this->errorFileName, '/');
    file_exists($file) && require_once $file;
    $buffer = ob_get_contents();
    ob_end_clean();
    echo $buffer;
    exit(9);
  }

}


class InvalideException extends Exceptions{
  
}


