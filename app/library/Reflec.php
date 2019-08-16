<?php

class Reflec {

  public  $class     = NULL;
  private $_instance = NULL;

  public function __construct($class) {
    $this->class = is_object($class) ? get_class($class) : $class;
    $this->_instance = new ReflectionClass($class);
  }

  public function getFileName() {
    return $this->_instance->getFileName();
  }

  public function getFileTime() {
    return filemtime($this->getFileName());
  }

  public function getClassComment() {
    return $this->_instance->getDocComment();
  }

  /**
   * 获取指定方法的注解
   * @param string $methodname 方法名
   * @return string|false
   * @throws ReflectionException
   */
  public function getMethodComment($methodname) {
    $method = $this->_instance->getMethod($methodname); //ReflectionMethod

    return $method->getDocComment();
  }

  public function getMethodParams($methodname, $isDefaultValue = FALSE) {
    $params = $this->_instance->getMethod($methodname)->getParameters();

    $result = [];
    foreach ($params as $param) { //ReflectionParameter
      $paramName = $param->getName();
      if ($isDefaultValue && $param->isDefaultValueAvailable()) { //isOptional
        $result[$paramName] = $param->getDefaultValue();
      } else $result[] = $paramName;
    }

    return $result;
  }

  public function isMethod($methodname) {
    return $this->_instance->hasMethod($methodname);
  }


  public function methodIsPublic($method) {

  }

  /**
   * @method getMethod
   * @param $name
   * 2019/8/11 18:53
   */
  public function getMethod($name) {
    $methods = array_change_value_case($this->getMethods());
    if (in_array(strtolower($name), $methods)) {
      return $this->_instance->getMethod($name);
    } else {
      return FALSE;
    }
  }

  /**
   * 得到类所有的方法
   * @method getMethods
   * @return ReflectionMethod[]
   * 2019/8/11 18:51
   */
  public function getMethods() {
    $methods = $this->_instance->getMethods();

    $data = [];
    foreach ($methods as $ref) {
      $data[] = $ref->name;
    }
    return $data;
  }

  public function getAllComment($regular, array $ignore = []) {
    foreach ($this->_instance->getMethods() as $method) {
      if ($ignore && in_array($method->class, $ignore)) continue; //getDeclaringClass

      if (!$method->isPublic() && !$method->isStatic()) continue;

      if (!$comment = $method->getDocComment()) continue;

      if (preg_match_all($regular, $comment, $result)) {
        yield $method->getName() => $result;
      }
    }
  }

}
