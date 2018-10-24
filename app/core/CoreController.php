<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/17
 * Time: 14:08
 * Email: songyongzhan@qianbao.com
 */

class CoreController extends Yaf_Controller_Abstract {

  /**
   * 获取项目运行最后的错误信息
   * @return array
   */
  public function getError() {
    return [
      'error_meg' => Yaf_Application::app()->getLastErrorMsg(),
      'error_no' => Yaf_Application::app()->getLastErrorNo(),
    ];
  }

  /**
   * 调用不存在的方法时，调用
   * @param $name
   * @param $arguments
   */
  public function __call($name, $arguments) {

  }


}