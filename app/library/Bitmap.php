<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/11/2
 * Time: 16:00
 * Email: songyongzhan@qianbao.com
 */


class Bitmap {

  public static $_instance    = NULL;
  public        $int_bit_size = NULL;
  private       $_bitmap      = [];

  public function __construct() {
    $this->int_bit_size = PHP_INT_SIZE * 8 - 1;
  }


  /**
   * 根据数组创建一个bitmap
   * @param $data
   * @param int $int
   */
  public function createBitMap(array $data, $int = 10) {
    //创建一个指定长度以0填充的数组
    $bitmap = array_fill(0, $int, 0);
    foreach ($data as $item) {
      //字节位置
      $bytePos = $item / $this->int_bit_size;
      $bitPos = $item % $this->int_bit_size;
      $position = 1 << $bitPos;
      isset($bitmap[$bytePos]) || $bitmap[$bytePos] = 0;
      $bitmap[$bytePos] = $bitmap[$bytePos] | $position;
    }
    $this->_bitmap = $bitmap;
    return $this;
  }


  /**
   * 从小到大排序数组
   * @param array $data
   * @return array
   */
  public static function bitSort(array $data) {
    return (new self())->createBitMap($data)->getData();
  }

  /**
   * 获取bitmap的原始数据
   * @param $bitmap
   * @return array
   */
  public function getData(array $bitmap = NULL) {
    $int_bit_size = $this->int_bit_size;
    $result = array();
    $bitmap = is_null($bitmap) ? $this->_bitmap : $bitmap;
    foreach ($bitmap as $key => $value) {
      for ($i = 0; $i < $int_bit_size; $i++) {
        $temp = 1 << $i;
        $flag = $temp & $value;
        if ($flag) {
          $result[] = $key * $int_bit_size + $i;
        }
      }
    }
    return $result;
  }

  /**
   * 判断bitmap中是否存在这个数据
   * @param $val
   * @return null
   */
  public function checkExists($val) {
    if (!is_int($val)) return NULL;
    $int_bit_size = $this->int_bit_size;
    $key = floor($val / $int_bit_size);
    $result = NULL;
    if (isset(($this->_bitmap)[$key])) {
      for ($b = 0; $b < $int_bit_size; $b++) {
        $temp = 1 << $b;
        $flag = $temp & ($this->_bitmap)[$key];
        if ($flag && ($val == (($key * $int_bit_size) + $b))) {
          $result = $val;
          break;
        }
      }
    }
    return $result;
  }

  /**
   * 在bitmap中添加数据
   * @param $val
   * @return $this
   */
  public function addData($val) {
    if (!is_int($val)) return $this;
    $int_bit_size = $this->int_bit_size;
    $bitmap = $this->_bitmap;
    $bytePos = floor($val / $int_bit_size);
    isset($bitmap[$bytePos]) || $bitmap[$bytePos] = 0;
    $bitPos = $val % $int_bit_size;
    $position = 1 << $bitPos;
    $bitmap[$bytePos] = $bitmap[$bytePos] | $position;
    $this->_bitmap = $bitmap;
    return $this;
  }

  /**
   * 在bitmap中删除一个数据
   * @param $val
   * @return $this
   */
  public function delData($val) {
    if (!is_int($val)) return $this;
    if ($this->checkExists($val)) {
      $int_bit_size = $this->int_bit_size;
      $key = floor($val / $int_bit_size);
      $bitPos = $val % $int_bit_size;
      $position = 1 << $bitPos;
      $new = $this->_bitmap[$key] & ~$position;
      $this->_bitmap[$key] = $new;
    }
    return $this;
  }

  /**
   * 获取bitmap的值
   * @return array
   */
  public function getBitmap() {
    return $this->_bitmap;
  }

  public function __get($name) {
    return $this->$name;
  }

  public function __set($name, $value) {
    $this->$name = $value;
  }


}