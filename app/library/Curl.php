<?php

class Curl {

  const JSON_ARRAY = 1;
  const JSON_OBJECT = 0;

  private $_options = [
    CURLOPT_TIMEOUT => 45,
    CURLOPT_CONNECTTIMEOUT => 3,
    //CURLOPT_CONNECTTIMEOUT_MS => 300,
    CURLOPT_NOSIGNAL => 1, //libcurl 7.28.1
    //CURLOPT_HEADER => 1,
    CURLOPT_ENCODING => '', //CURLOPT_ACCEPT_ENCODING
    CURLOPT_FAILONERROR => ENVIRONMENT === 'development' ? 0 : 1, // >= 400
    CURLOPT_RETURNTRANSFER => 1,
    //CURLOPT_AUTOREFERER => 1,
    CURLOPT_MAXREDIRS => 8,
    CURLOPT_FOLLOWLOCATION => 1, //open_basedir
    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4, //IPv4
  ];
  //
  private $_curl;
  private $_error;
  private $_code;
  private $_reqHeaders              = []; //request
  private $_resHeaders              = ''; //response
  private $_response;
  private $_cookies;
  private $response_header_continue = FALSE;

  public static function isLoadExt() {
    return extension_loaded('curl');
  }

  public static function newSession() {
    return new static;
  }

  public function __construct(array $defaultOptions = []) {
    $this->_curl = curl_init();

    $this->setOption(CURLOPT_HEADERFUNCTION, [$this, 'addResponseHeaderLine']);
    empty($defaultOptions) || $this->_options = $defaultOptions;
  }

  public function __destruct() {
    curl_close($this->_curl);
  }

  public function getOption($key) {
    return isset($this->_options[$key]) ? $this->_options[$key] : NULL;
  }

  public function setOption($key, $value) {
    $this->_options[$key] = $value;
  }

  public function setCookie($key, $val) {
    $this->_cookies[$key] = $val;
    $this->setOption(CURLOPT_COOKIE, http_build_query($this->_cookies, '', '; '));
  }

  public function setOptions(array $data) {
    $this->_options = array_replace($this->_options, $data);
  }

  public function addResponseHeaderLine($curl, $header_line) {
    $trimmed_header = trim($header_line, "\r\n");

    if ($trimmed_header === "") {
      $this->response_header_continue = FALSE;
    } else if (strtolower($trimmed_header) === 'http/1.1 100 continue') {
      $this->response_header_continue = TRUE;
    } else if (!$this->response_header_continue) {
      $this->response_headers[] = $trimmed_header;
    }

    return strlen($header_line);
  }

  public function getResponseHeaders($headerKey = NULL) {
    $headers = [];
    $headerKey = strtolower($headerKey);
    if (isset($this->response_headers)) {
      foreach ($this->response_headers as $header) {
        $parts = explode(":", $header, 2);

        $key = isset($parts[0]) ? $parts[0] : NULL;
        $value = isset($parts[1]) ? $parts[1] : NULL;

        $headers[trim(strtolower($key))] = trim($value);
      }
    }

    if ($headerKey) {
      return isset($headers[$headerKey]) ? $headers[$headerKey] : FALSE;
    }

    return $headers;
  }

  public function setHeader(array $data) {
    $this->_reqHeaders = array_replace($this->_reqHeaders, $data);

    foreach ($this->_reqHeaders as $key => $value) {
      if ($value !== NULL) {
        $header[] = is_integer($key) ? $value : $key . ': ' . $value;
      }
    }
    $this->_options[CURLOPT_HTTPHEADER] = $header; //CURLOPT_COOKIE
  }

  public function setProxy($url, $port = 8080) {
    $this->setOptions([CURLOPT_PROXY => $url, CURLOPT_PROXYPORT => $port]);
  }

  /**
   * 发送 GET 请求并返回解析后的 JSON 内容
   *
   * @param string $url
   * @param string|array $data
   * @param integer $return
   * @return mixed
   */
  public function getJson($url, $data = '', $return = self::JSON_ARRAY) {
    $content = json_decode($this->get($url, $data), $return, 512, JSON_BIGINT_AS_STRING);

    return $content ?: FALSE;
  }

  /**
   * 发送 GET 请求
   *
   * @param string $url
   * @param string|array $data
   * @return boolean|string
   */
  public function get($url, $data = '') {
    if ($data) {
      $url .= strpos($url, '?') === FALSE ? '?' : '&';
      $url .= is_array($data) ? http_build_query($data) : $data;
    }

    return $this->request([CURLOPT_URL => $url, CURLOPT_CUSTOMREQUEST => 'GET']);
  }

  /**
   * 发送 POST 请求并返回解析后的 JSON 内容
   *
   * @param string $url
   * @param array|string $data
   * @param boolean $multipart 强制使用 form-data 编码 ($data 必须为数组)
   * @param integer $return
   * @return mixed
   */
  public function postJson($url, $data = '', $multipart = FALSE, $return = self::JSON_ARRAY) {
    $content = json_decode($this->post($url, $data, $multipart), $return, 512, JSON_BIGINT_AS_STRING);

    return $content ?: FALSE;
  }

  /**
   * 发送 POST 请求
   *
   * @param string $url
   * @param array|string $data
   * @param boolean $multipart 强制使用 form-data 编码 ($data 必须为数组)
   * @return boolean|string
   */
  public function post($url, $data = '', $multipart = FALSE) {
    if (is_array($data)) { //application/x-www-form-urlencoded  multipart/form-data
      $this->_options[CURLOPT_POSTFIELDS] = $this->isMultiPart($data, $multipart) ?
        $data : http_build_query($data);

      is_array($this->_options[CURLOPT_POSTFIELDS])
      && $this->setHeader(['Content-Type' => 'multipart/form-data']);
    } else $this->_options[CURLOPT_POSTFIELDS] = $data;

    return $this->request([CURLOPT_URL => $url, CURLOPT_CUSTOMREQUEST => 'POST']);
  }

  /**
   * 发送 cURL 请求
   *
   * @param array $options
   * @return boolean|string
   */
  private function request(array $options = []) {
    $this->_code = 0;
    $this->_error = '';
    $this->setOptions($options);
    curl_setopt_array($this->_curl, $this->_options);

    $this->_response = curl_exec($this->_curl);
    if ($this->_response === FALSE) {
      $this->_error = curl_error($this->_curl) . ' [' . curl_errno($this->_curl) . ']';
    }

    $headers = curl_getinfo($this->_curl); //CURLINFO_HEADER_SIZE

    isset($headers['http_code']) && $this->_code = $headers['http_code'];

    if (isset($this->_options[CURLOPT_HEADER]) && $this->_options[CURLOPT_HEADER] == 1) {
      $this->_resHeaders = substr($this->_response, 0, $headers['header_size']);
      $this->_response = substr($this->_response, $headers['header_size']);
    }

    return $this->_response;
  }

  protected function isMultiPart(array &$data, $multipart = FALSE) {
    $result = $multipart;
    if (class_exists('CURLFile')) { //PHP5.6
      foreach ($data as &$item) {
        if (isset($item[0])) { //索引数组或字符串
          if (is_array($item)) { //基本文件服务  Seaweed-FS
            $filepath = $item[0]; //上传文件
            isset($item[1]) && $filename = $item[1]; //目标位置
          } else $filepath = $item;

          if ($filepath[0] === '@') { //上传文件
            if ($filepath = realpath(ltrim($filepath, '@'))) {
              $item = new CURLFile($filepath);
              isset($filename) && $item->setPostFilename($filename);

              unset($filename);
              $result = TRUE;

              continue;
            } else throw new InvalidArgumentException('file is not a valid.');
          }
        }

        is_array($item) && $item = json_encode($item, JSON_UNESCAPED_UNICODE);
      }
    }

    return $result;
  }

  private function gzipDecode($data) { //RFC 1952
    return strlen($data) < 18 || strcmp(substr($data, 0, 2), "\x1f\x8b") ? $data : gzdecode($data);
  }

  public function getCode() {
    return $this->_code;
  }

  public function getResonse() {
    return $this->_response ?: '';
  }

  public function getError() {
    return $this->_error;
  }

  public function hasError() {
    return $this->_error !== NULL;
  }

  public function getLogs() {
    $url = $this->getOption(CURLOPT_URL);
    $data = $this->getOption(CURLOPT_POSTFIELDS);
    $response = $this->_response;

    $log[] = __METHOD__ . ' [' . date('Y-m-d H:i:s') . ']';
    $log[] = '请求方式 ' . (isAjax() ? 'Ajax ' : '') . $_SERVER['REQUEST_METHOD'] . ' [' . ENVIRONMENT . ']';
    $log[] = '访问链接 ' . $_SERVER['REQUEST_URI'] . ' [' . $_SERVER['REMOTE_ADDR'] . ']';

    //if (ENVIRONMENT !== 'production') $log[] = '调用文件 ' . getCallerFromTrace();

    $log[] = '接口地址 ' . $url . (ENVIRONMENT === 'production' && !isLocalhost() ? '' :
        ' [' . gethostbyname(parse_url($url, PHP_URL_HOST)) . ']');
    $log[] = '浏览器头 ' . $_SERVER['HTTP_USER_AGENT'];
    $log[] = '头部参数 ' . json_encode($this->getOption(CURLOPT_HTTPHEADER) ?: [], JSON_UNESCAPED_UNICODE);
    $log[] = '请求参数 ' . $data . ' [' . $this->getOption(CURLOPT_CUSTOMREQUEST) . ']';
    $log[] = '接口返回 ' . $response;

    if (!$response) {
      $log[] = '';
      $log[] = '返回错误 ' . ($this->getError() ?: '未知错误 [' . $this->getCode() . ']');
      $log[] = '原始返回 ' . $this->getResonse();
    }

    return implode(PHP_EOL, $log);
  }

}
