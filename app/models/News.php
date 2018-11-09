<?php

/**
 * @name SampleModel
 * @desc sample数据获取类, 可以访问数据库，文件，其它系统等
 * @author root
 */
class NewsModel extends BaseModel {

  protected function _init() {
    parent::_init();
    //$this->_host = 'http://g.yt99.com';
    //$this->_host = 'http://k.yt99.com';
    $this->_host = 'http://r.yt99.com';

  }

  public function fetchBefore($url, $data) {

    //$this->setRequestOptions(CURLOPT_COOKIE,'acw_tc=dede581e15416489660366317ece9fedad784adaaeedf1582f420846de');
    $this->setRequestHeader([
      //"accept" => "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
      //"accept-encoding" => "gzip, deflate, br",
      //"accept-language" => "zh-CN,zh;q=0.9",
      //"cache-control" => "no-cache",
      //"pragma" => "no-cache",
      //"upgrade-insecure-requests" => "1",
      //"user-agent" => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.81 Safari/537.36"


      //'Accept' => '*/*',
      //'Accept-Encoding' => 'gzip, deflate, br',
      //'Accept-Language' => 'zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
      //'Cache-Control' => 'no-cache',
      //'Connection' => 'keep-alive',
      ////dede581e15416473174475151e24260ab39cf7b6aa640683df4bbd6621
      //'Cookie' => 'acw_tc=dede581e154164731744751…60ab39cf7b6aa640683df4bbd6621',
      //'Host' => 'k.yt99.com',
      //'Pragma' => 'no-cache',
      //'Referer' => 'https://ab.weitiexiu.com/index…l&k=8cmDeLQG5xs5HdI&s=2&date=1',
      //'TE'=>'Trailers',
      //"user-agent" => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.81 Safari/537.36"


      'Accept' => 'audio/webm,audio/ogg,audio/wav…q=0.7,video/*;q=0.6,*/*;q=0.5',
      'Accept-Language' => 'zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
      'Cache-Control' => 'no-cache',
      'Connection' => 'keep-alive',
      'Host' => 'r.yt99.com',
      'Pragma' => 'no-cache',
      'Range' => 'bytes=0-',
      'Referer' => 'https://ab.weitiexiu.com/index…l&k=8cmDeLQG5xs5HdI&s=2&date=1',
      "user-agent" => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.81 Safari/537.36"


    ]);


    return $data;

  }


  protected function fetchFinish($data) {

    return $data;
  }

  public function geturl($imageurl, $param = []) {
    $result = $this->send($imageurl, $param);
    echo file_put_contents(APP_PATH . DS . 'a.m4a', $result);

  }


}
