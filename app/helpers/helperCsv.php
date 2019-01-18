<?php

class helperCsv implements Iterator {

  private $_handle = NULL;
  private $_lines = NULL;
  private $_delimiter = NULL;
  private $_index = NULL; //行号
  private $_headers = NULL; //字段
  private $_current = NULL;

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
    }
    else $original = TRUE;

    if (!$this->_handle = fopen($filename, 'r')) throw new Exception('The file "' . $filename . '" cannot be read.');
    $original || ini_set('auto_detect_line_endings', $original);

    $this->_lines = $lines;
    $this->_delimiter = $delimiter;
    $header || $this->_headers = FALSE;
  }

  private function readline() {
    return fgetcsv($this->_handle, 1024);
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

      return FALSE;
    }

    return TRUE;
  }

}
