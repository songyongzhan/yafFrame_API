<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/23
 * Time: 13:41
 * Email: songyongzhan@qianbao.com
 */

class ProxyModel {

  public static  $_object          = [];
  private        $_rule            = [];//验证规则
  private        $_instance        = NULL;
  private        $_classname       = NULL;
  private        $_cachePath       = APP_PATH . DS . 'data/cache/validate';
  private static $_validateContent = NULL;
  const IGNORE = ['BaseModel'];

  public function __construct($obj, $name = NULL) {
    $this->_classname = is_null($name) ? get_class($obj) : $name;
    $this->_instance = $obj;
    if (!is_dir($this->_cachePath))
      mkdir($this->_cachePath, 0755, TRUE);
  }


  public final function __get($name) {
    if (!isset($this->_instance->$name))
      throw new Exceptions('Undefined property:' . $name . ' not found exists.');

    return $this->_instance->$name;
  }

  public final function __set($name, $value) {
    $this->_classname = is_null($name) ? get_class($value) : $name;
    $this->_instance = $value;
  }

  public function __call($method, $params) {

    if ($method[0] !== '_' && method_exists($this->_instance, $method)) {
      ENVIRONMENT === 'devlop' && logMessage('debug', '自动验证:' . $this->_classname . '->' . $method . '() 参数:' . jsonencode($params));
      $reflection = new Reflec($this->_instance);
      $validateFile = $this->_cachePath . DS . 'form_' . $this->_classname . '.' . Tools_Config::getConfig('application.ext');

      if (file_exists($validateFile) && (filemtime($validateFile) > $reflection->getFileTime())) {
        is_null(self::$_validateContent) && self::$_validateContent = require_once $validateFile;
        $this->_rule = self::$_validateContent;
      } else {
        $this->_rule = $this->_makeFile($reflection, $validateFile);
      }

      if (!is_array($rules = $this->_rule))
        throw new Exceptions('$this->_rule is not Array.', 500);

      //如果最后一个参数是数组，则和其他参数合并在一起
      if (isset($rules['rules'][$method]) && ($methodRules = $rules['rules'][$method])) {

        $data = $this->_combineParam($rules['params'][$method], $params);
        if (TRUE !== ($result = validate($methodRules, $data, $rules['msg'][$method]))) {
          showApiException($result['errMsg']);
        }
        /* P($methodRules);
        $msg = $rules['msg'][$method];
        $validate = new Validate($methodRules, $msg);
        $result = $validate->check($this->_combineParam($rules['params'][$method], $params));
        P($msg);
        P($this->_combineParam($rules['params'][$method], $params));
        P($result, 'var_dump');
        P($validate->getError());*/

      }
    }
    return call_user_func_array([$this->_instance, $method], $params);
  }


  /**
   *
   * 创建表单文件
   *
   * @param $reflection
   * @param $validateFile
   * @return array
   *
   * $rule = [
   * 'name'  => 'require|max:25',
   * 'age'   => 'number|between:1,120',
   * 'email' => 'email',
   * ];
   *
   * $msg = [
   * 'name.require' => '名称必须',
   * 'name.max'     => '名称最多不能超过25个字符',
   * 'age.number'   => '年龄必须是数字',
   * 'age.between'  => '年龄只能在1-120之间',
   * 'email'        => '邮箱格式错误',
   * ];
   *
   * $data = [
   * 'name'  => 'thinkphp',
   * 'age'   => 10,
   * 'email' => 'thinkphp@qq.com',
   * ];
   *
   * $validate = new Validate($rule, $msg);
   * $result   = $validate->check($data);
   */

  private function _makeFile($reflection, $validateFile) {
    $config = [];
    $config['rules'] = [];
    $config['params'] = [];

    foreach ($reflection->getAllComment('/^\s+\*\s@param\s+(\w+)\s+\$(\w+)\s+<([^>]+)>\s?([^\s\*]*)/im'
      , self::IGNORE) as $key => $item) {
      for ($i = 0; $i < count($item[0]); $i++) {

        $config['rules'][$key][$item[2][$i]] = $item[3][$i];

        if (strpos($item[3][$i], '|') && strpos($item[4][$i], '|')) {
          $moreRules = explode('|', $item[3][$i]);
          $moreMessage = explode('|', $item[4][$i]);
          for ($j = 0; $j < count($moreRules); $j++) {
            $ruleName = strpos($moreRules[$j], ':') ? (explode(':', $moreRules[$j]))[0] : $moreRules[$j];
            $config['msg'][$key][$item[2][$i] . '.' . $ruleName] = $moreMessage[$j];
          }
        } else {
          $config['msg'][$key][$item[2][$i] . '.' . $item[3][$i]] = $item[4][$i];
        }
      }
      $config['params'][$key] = $reflection->getMethodParams($key, TRUE);
    }
    $config['file'] = $reflection->getFileName();

    file_put_contents($validateFile, '<?php return ' . var_export($config, TRUE) . ';', LOCK_EX);
    return $config;
  }

  /**
   * 暴力合并形参与实参 (实参最后值为数组时,附加上)
   * @param array $params 函数形参
   * @param array $values 调用实参
   *
   *
   * @desc
   * 第一个参数 为注释代码中获取到的变量名数组集合
   * 第二个参数 为此次请求传递来的数据集合
   *
   * 这里有一个foreach循环 主要目的是 把 第一个数组和第二个数组 数据做合并
   * 合并成一个新数组 类似
   *
   * $data=[
   *  'user'=>'james',
   *  'age'=>14
   * ];
   *
   * 如果第二个数组中最后一个元素为数组，则 直接和前面数组进行合并
   *
   * 最后返回
   *
   * @return array
   */
  private function _combineParam(array $params, array $values) {
    $i = 0;
    $result = [];
    foreach ($params as $key => $item) {
      if (is_numeric($key)) { //无默认值
        if (!array_key_exists($i, $values)) {
          showException('Parameter declaration or configuration error.');
        }

        $key = $item;
        $value = $values[$i];
      } else $value = array_key_exists($i, $values) ? $values[$i] : $item;

      $i++;
      $result[$key] = $value;
    }

    is_array($value = end($values)) && $result += $value;

    return $result;
  }

  //返回当前对象
  public function getInstance() {
    return $this->_instance;
  }


}