<?php

class helperCsv implements Iterator {

  private $_handle    = NULL;
  private $_lines     = NULL;
  private $_delimiter = NULL;
  private $_index     = NULL; //行号
  private $_headers   = NULL; //字段
  private $_current   = NULL;
  private $_lockFile  = NULL; //锁定文件
  private $_filter    = [];

  /**
   *
   * @param string $filename 文件名
   * @param integer $lines 获取行数
   * @param boolean $header 是否有表头
   * @param string $delimiter 分隔符
   * @throws Exception
   */
  public function __construct($filename, $lines = NULL, $header = TRUE, $delimiter = ',') { //SplFileObject
    //setlocale(LC_ALL, 'zh_CN.UTF-8');
    if (PHP_OS === 'Darwin') {
      $original = ini_get('auto_detect_line_endings');
      $original || ini_set('auto_detect_line_endings', TRUE);
    } else $original = TRUE;

    $this->_lockFile = ini_get('session.save_path') . DS . $filename . '.lock';

    if (file_exists($this->_lockFile)) throw new Exception('The file "' . $filename . '" to locked.');

    if (!$this->_handle = fopen($filename, 'r')) throw new Exception('The file "' . $filename . '" cannot be read.');
    $original || ini_set('auto_detect_line_endings', $original);

    file_put_contents($this->_lockFile, 'locked');

    $this->_lines = $lines;
    $this->_delimiter = $delimiter;
    $header || $this->_headers = FALSE;
  }

  private function readline() {
    $result = str_getcsv($this->_replace(fgets($this->_handle)));

    return isset($result[0]) ? $result : NULL;
  }

  private function _replace($str) {
    foreach ($this->_filter as $filter) {
      $str = str_replace($filter['find'], $filter['replace'], $str);
    }
    return $str;
  }

  /**
   * 添加字符特殊字符过滤
   * @param $find
   * @param $replace
   */
  public function addFilter($find, $replace) {
    array_push($this->_filter, [
      'find' => $find,
      'replace' => $replace
    ]);
    return $this;
  }

  public function rewind() {
    rewind($this->_handle);

    $this->_index = 0;
    if ($this->_headers !== FALSE) $this->_headers = $this->readline();
  }

  public function current() {
    $this->_current = $this->readline();
    $this->_index++;

    return $this->_headers ? array_combine($this->_headers, $this->_current) : $this->_current;
  }

  public function key() {
    return $this->_index;
  }

  public function next() {
    return !feof($this->_handle);
  }

  public function valid() {
    if ($this->_lines > 0 && $this->_lines <= $this->_index) return FALSE;
    elseif (!$this->next()) {
      fclose($this->_handle);

      @unlink($this->_lockFile);
      return FALSE;
    }

    return TRUE;
  }

}
