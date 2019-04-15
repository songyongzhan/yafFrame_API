<?php
/**
 * 功能概述:
 * 时间: 17-6-7 下午5:07
 * 文件名：Redis.class.php
 * PhpStorm
 * 开发者：宋永占 (一切缘于你)
 *     QQ：574482856
 *    邮箱：574482856@qq.com
 */

class MyRedis {
  private static $_instance;
  protected      $redis; // redis对象
  protected      $ip     = '127.0.0.1'; // redis服务器ip地址
  protected      $port   = '6379'; // redis服务器端口
  protected      $passwd = ''; // redis密码
  protected      $prefix = "";
  protected      $expire = 0; //如果没有指定过期时间，0分钟  不过期

  public static function getInstance($config = array()) {
    if (!self::$_instance instanceof self) {
      self::$_instance = new self($config);
    }
    return self::$_instance;
  }

  public function __construct($config = array()) {
    $this->redis = new \Redis();
    empty($config) or $this->connect($config);
    return $this->redis;
  }

  // 连接redis服务器
  public function connect($config = array()) {
    if (!empty($config)) {
      $this->ip = $config['ip'];
      $this->port = $config['port'];
      if (isset($config['passwd']) && $config['passwd'] != '') {
        $this->passwd = $config['passwd'];
      }
      if (isset($config['expire'])) {
        $this->expire = $config['expire'];
      }
    }
    $state = $this->redis->connect($this->ip, $this->port);
    if ($state == FALSE) {
      die('redis connect failure');
    }
    if (!is_null($this->passwd)) {
      $this->redis->auth($this->passwd);
    }
    if (isset($config['prefix'])) {
      $this->prefix = $config['prefix'];
    }
    //设置字段前缀
    $this->redis->setOption(\Redis::OPT_PREFIX, $this->prefix);

  }

  public function setOption($type, $setVal) {
    $this->redis->setOption($type, $setVal);
    /*  * <pre>
   * $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_NONE);        // don't serialize data
   * $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);         // use built-in serialize/unserialize
   * $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_IGBINARY);    // use igBinary serialize/unserialize
   * $redis->setOption(Redis::OPT_PREFIX, 'myAppName:');                      // use custom prefix on all keys
    */
  }


  public function getKeys($key) {
    return $this->redis->keys($key);
  }


  public function setEx($key, $val, $expire = 0) {
    if ($expire === 0 || !is_int($expire))
      $expire = $this->expire;

    return $this->redis->setex($key, $expire, $val);
  }

  // 设置一条String
  public function setStr($key, $text, $timeout = TRUE) {
    $this->redis->set($key, $text);
    if ($timeout) {
      $this->setExpire($key);
    }
  }

  // 获取一条String
  public function getStr($key) {
    $text = $this->redis->get($key);
    return empty($text) ? NULL : $text;
  }

  // 删除一条String
  public function del($key) {
    $this->redis->del($key);
  }

  /**
   * 判断key是否存在
   * @param $key 判断哪个key
   * @return bool 返回真假
   */
  public function exists($key) {
    return $this->redis->exists($key);
  }

  /**
   * 返回存在多少个key
   * @return int 返回key的个数
   */
  public function dbsize() {
    return $this->redis->dbSize();
  }

  /**
   * 选择哪个数据库
   * @param int $num 数字
   */
  public function select($num = 0) {
    $this->redis->select($num);
  }


  /**
   * @param $key
   * @param int $timeout
   * 设置这个key的保存的时间
   */
  public function expire($key, $timeout = 30000) {
    $this->redis->setTimeout($key, $timeout);
  }


  /**
   * 清空当前数据库
   */
  public function flushdb() {
    $this->redis->flushdb();
  }

  /**
   * 清空redis所有的数据
   */
  public function flushall() {
    $this->redis->flushall();
  }

  //自动加 1
  public function incr($key) {
    $this->redis->incr($key);
  }

  //自动加上默认的数  $this->incrByFloat($score,10.3);
  public function incrByFloat($key, $increment) {
    $this->redis->incrByFloat($key, $increment);
  }

  //自动加上默认的整数  $this->incrBy("score",3);
  public function incrBy($key, $num = 1) {
    $this->redis->incrBy($key, $num);
  }

  //自动减去 1
  public function decr($key) {
    $this->redis->decr($key);
  }

  //自动加上默认的数  $this->decrBy($score,10.3);
  public function decrBy($key, $increment) {
    $this->redis->decrBy($key, $increment);
  }

  /**
   * 添加进list
   * @param $key
   * @param $_data
   */
  public function lpush($key, $_data, $timeout = TRUE) {
    $this->redis->lPush($key, $_data);
    if ($timeout) {
      $this->setExpire($key);
    }
  }

  /**
   * 显示列表中的数据
   * @param $key
   * @param int $start
   * @param int $end
   * @return array
   *
   * $this->lrange($key,0,0)  获取一个元素
   *$this->lrange($key,0,1)  获取两个元素 从0开始取 取到1   2个数
   *
   *
   */
  public function lrange($key, $start = 0, $end = 50) {
    return $this->redis->lRange($key, $start, $end);
  }

  /**
   * 截取字符
   * @param $key
   * @param int $start
   * @param int $end
   *
   */
  public function ltrim($key, $start = 0, $end = 50) {
    $this->redis->lTrim($key, $start, $end);
  }

  /**
   * 返回列表 key中元素的个数
   * @param $key
   * @return int
   */
  public function llen($key) {
    return $this->redis->lLen($key);
  }

  /**
   * 删除 此列表中最后一项
   * @param $key
   */
  public function rpop($key) {
    return $this->redis->rPop($key);
  }


  //无序集合 唯一  确定
  //添加一个value
  public function sAdd($key, $val, $timeout = TRUE) {
    $this->redis->sAdd($key, $val);
    if ($timeout) {
      $this->setExpire($key);
    }
  }

  //显示列表 结合所有的列表内容
  public function sList($key) {
    return $this->redis->sMembers($key);
  }

  //删除 $val
  public function sRem($key, $val) {
    $this->redis->sRem($key, $val);
  }

  //把$val 从 seckey移动到dstkey
  public function sMove($seckey, $dstkey, $val) {
    return $this->redis->sMove($seckey, $dstkey, $val);
  }

  //看看key中是否含有$vaL这个元素，如果存在返回true 否则返回false
  public function sContains($key, $val) {
    return $this->redis->sContains($key, $val);
  }

  //返回key中有多少个元素
  public function sCard($key) {
    return $this->redis->sCard($key);
  }

  //求交集
  public function sInter($key1, $key2) {
    return $this->redis->sInter($key1, $key2);
  }

  //求并集
  public function sUnion($key1, $key2) {
    return $this->redis->sUnion($key1, $key2);
  }

  //求差集
  public function sDiff($key1, $key2) {
    return $this->redis->sDiff($key1, $key2);
  }

  //判断集合中是否含有某个值
  public function sismember($key, $val) {
    return $this->redis->sIsMember($key, $val);
  }

  //hash
  //设置一个 hash的一个key
  public function hSet($key, $_index, $_data, $timeout = TRUE) {
    $this->redis->hSet($key, $_index, $_data);
    if ($timeout) {
      $this->setExpire($key);
    }
  }

  public function hmSet($key, $_data, $timeout = TRUE) {

    $this->redis->hMset($key, $_data);
    if ($timeout) {
      $this->setExpire($key);
    }

  }

  //得到一个hash的值
  public function hGet($key, $_index) {
    return $this->redis->hGet($key, $_index);
  }

  //返回hansh的个数
  public function hLength($key) {
    return $this->redis->hLen($key);
  }

  //返回这个hash中所有的键名
  public function hKeys($key) {
    return $this->redis->hKeys($key);
  }

  //返回这个hash中所有的键名
  public function hVals($key) {
    return $this->redis->hVals($key);
  }

  //返回这个hash中所有的数据
  public function hGetAll($key) {
    return $this->redis->hGetAll($key);
  }

  //查看这个键是否存在
  public function hExists($key, $_index) {
    return $this->redis->hExists($key, $_index);
  }

  //删除下标为t的
  public function hDel($key, $t) {
    return $this->redis->hDel($key, $t);
  }

  //设置每一个key的过期时间
  private function setExpire($key) {
    if ($this->expire > 0) {
      $this->redis->setTimeout($key, $this->expire);
    }
  }


  //添加元素
  public function zadd($key, $score, $val, $timeout = TRUE) {
    $this->redis->zAdd($key, $score, $val);
    if ($timeout) {
      $this->setExpire($key);
    }
  }

  //返回集合元素的个数
  public function zcard($key) {
    return $this->redis->zCard($key);
  }

  //返回[min, max]区间内元素数量
  public function zcount($key, $min, $max) {
    return $this->redis->zCount($key, $min, $max);
  }

  //按照score来返回这个 分数期间的元素
  public function zRangeByScore($key, $start = 0, $end = 0, $withscores = FALSE) {
    if ($end == 0) {
      $end = $this->zcard($key);
    }
    if ($withscores) {
      return $this->redis->zRangeByScore($key, $start, $end, array('withscores' => TRUE));
    } else {
      return $this->redis->zRangeByScore($key, $start, $end);
    }
  }

  public function zList($key, $start = 0, $end = 0) {
    if ($end == 0) {
      $end = $this->zcard($key);
    }
    return $this->redis->zRange($key, $start, $end);
  }

  public function zList2($key, $start = 0, $end = 0) {
    return $this->redis->zRange($key, $start, $end);
  }

  public function zllist($key, $start = 0, $end = 0) {
    if ($end == 0) {
      $end = $this->zcard($key);
    }
    return $this->redis->zRange($key, $start, $end, array('WITHSCORES' => TRUE));
  }

  //获取数组中的索引
  public function zRank($key, $member) {
    return $this->redis->zRank($key, $member);
  }

  //删除按照索引的值，区间的元素
  public function zRemRangeByRank($key, $min, $max) {
    return $this->redis->zRemRangeByRank($key, $min, $max);
  }

  //得到这个元素的score
  public function zScore($key, $member) {
    return $this->redis->zScore($key, $member);
  }

  //按照分数去删除
  public function zRemRangeByScore($key, $min, $max) {
    return $this->redis->zRemRangeByScore($key, $min, $max);
  }

}

?>
