<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/22
 * Time: 9:50
 * Email: songyongzhan@qianbao.com
 */

class Log {
  private        $_enabled   = TRUE;
  private static $messages   = [];
  protected      $file_name  = NULL;
  protected      $file_path  = NULL;
  protected      $log_expire = NULL;
  protected      $file_ext   = NULL;

  public function getEnabled() {
    return $this->_enabled;
  }

  public function setEnabled($enabled) {
    $this->_enabled = $enabled;
  }

  public function write_log($level, $message) {
    if ($this->_enabled !== TRUE) return FALSE;
    self::$messages[] = $this->_formatLine($level, getTime(), is_array($message) ? serialize($message) : $message);
  }

  public function write_file($message) {
    $this->check_file();
    if ($result = file_put_contents($this->log_file, $message, FILE_APPEND | LOCK_EX))
      return $result;
  }

  /**
   *
   * 检测日志文件是否存在，如果不存在就创建
   *
   * 如果文件存在，则看看日期间隔是否到期，如果到期 重命名文件，再次创建一个日志文件。
   *
   *
   */
  protected function check_file() {
    //判断文件是否存在，如果不存在直接创建
    $file = $this->log_file;
    $expire = Tools_Config::getConfig('log.log_expire');

    $pattern = '#(^[1-9]+[0-9]?)([d|h|s|i|m]+)#';
    $diff = 0;
    if (preg_match($pattern, $expire)) {
      preg_match_all($pattern, $expire, $all);

      $howLong = $all[1][0];
      switch ($all[2][0]) {
        case 'h': //时
          $diff = $howLong * 3600;
          break;
        case 'd': //天
          $diff = $howLong * 24 * 3600;
          break;
        case 'i': //分
          $diff = $howLong * 60;
          break;
        case 'm': //月
          $diff = $howLong * 24 * 3600 * 30;
          break;
        default: //秒
          $diff = $howLong;
      }
    }
    $backFile = $this->file_path . DS . date('Y_m_d_H_i_s') . '.' . $this->file_ext;

    if (file_exists($file) && ($diff > 0 && (time() - filectime($file) > $diff)))
      rename($file, $backFile);

    if (!file_exists($file)) {
      $message = "<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>\n\n";
      if (!is_writable(dirname($file)))
        throw new Exceptions(dirname($file) . ' Directory not to write', 500);
      file_put_contents($file, $message, LOCK_EX);
      chmod($file, 0644);
    }
  }

  private function _formatLine($level, $date, $message) {
    return $level . ' - ' . $date . ' --> ' . $message . "\n";
  }


  public function __construct() {
    //CONFIGPATH
    $this->file_name = Tools_Config::getConfig('log.file_name');
    $this->file_path = Tools_Config::getConfig('log.file_path');
    $this->log_expire = Tools_Config::getConfig('log.log_expire');
    $this->file_ext = Tools_Config::getConfig('log.file_type') ? Tools_Config::getConfig('log.file_type') : Tools_Config::getConfig('application.ext');
    $this->log_file = $this->file_path . DS . $this->file_name . '.' . $this->file_ext;

    if (!is_dir($this->file_path))
      mkdir($this->file_path, 0755, TRUE);
  }

  public function __destruct() {
    if (!empty(self::$messages)) {
      $this->write_log('debug',
        __METHOD__ . PHP_EOL . '页面执行 ' . number_format(microtime(TRUE) - $_SERVER['REQUEST_TIME_FLOAT'], 3)
        . ' sec [' . $_SERVER['REQUEST_URI'] . ']');

      $this->write_file(PHP_EOL . '<log>' . PHP_EOL . implode('', self::$messages) . '</log>' . PHP_EOL);
    }
  }


}