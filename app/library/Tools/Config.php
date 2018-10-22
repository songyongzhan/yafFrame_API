<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/18
 * Time: 10:28
 * Email: songyongzhan@qianbao.com
 */

class Tools_Config {

  /**
   * 获取配置文件配置
   * @param null $node
   * @param string $path
   * @return string
   */
  public static function getConfig($node = NULL, $path = CONFIGPATH) {
    static $config;
    $nodeVal = '';
    if (!$config) {
      $config = new Yaf_Config_Ini(CONFIGPATH, ini_get('yaf.environ'));
    }
    if ($node !== NULL && !empty($node)) {
      if ($nodeVal = $config->get($node)) {
        is_object($nodeVal) && $nodeVal = $nodeVal->toArray();
      }
    }
    return $nodeVal;
  }


}