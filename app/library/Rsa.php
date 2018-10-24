<?php

class Rsa {

  private $_pubkey;
  private $_privkey;
  //
  private static $_instance;

  private function __construct() {
    //
  }

  final public static function getInstance($pubkey = '', $privkey = '') {
    isset(self::$_instance) || self::$_instance = new self();

    self::$_instance->setKey($pubkey, $privkey);

    return self::$_instance;
  }

  public function setKey($pubkey, $privkey) {
    //openssl_pkey_get_private('file://private_key.pem');
    $pubkey && $this->_pubkey = "-----BEGIN PUBLIC KEY-----\n" . chunk_split($pubkey, 64) . "-----END PUBLIC KEY-----";
    $privkey && $this->_privkey = "-----BEGIN RSA PRIVATE KEY-----\n" . chunk_split($privkey, 64) . "-----END RSA PRIVATE KEY-----";
  }

  private function _encrypt($data) {
    $result = '';
    if ($data) {
      foreach (str_split($data, 117) as $chunk) { //RSA_PKCS1_PADDING  1024/8-11 => 117    RSA_PKCS1_OAEP_PADDING (41)
        if (!openssl_public_encrypt($chunk, $encrypted, $this->_pubkey, OPENSSL_PKCS1_PADDING))
          throw new Exception('Unable to encrypt data.');

        $result .= $encrypted;
      }
    }

    return $result;
  }

  private function _decrypt($data) {
    $result = '';
    if ($data) {
      foreach (str_split($data, 128) as $chunk) {
        if (!openssl_private_decrypt($chunk, $decrypted, $this->_privkey))
            throw new Exception('Unable to decrypt data.');

        $result .= $decrypted;
      }
    }

    return $result;
  }

  public static function Encrypt($data, $pubkey = '') {
    $_self = self::getInstance($pubkey, '');

    return base64_encode($_self->_encrypt($data));
  }

  public static function Decrypt($data, $privkey = '') {
    $_self = self::getInstance('', $privkey);

    return $_self->_decrypt(base64_decode($data));
  }

  public static function hexEncrypt($data, $pubkey = '') {
    $_self = self::getInstance($pubkey, '');

    return bin2hex($_self->_encrypt($data));
  }

  public static function hexDecrypt($data, $privkey = '') {
    $_self = self::getInstance('', $privkey);

    return $_self->_decrypt(hex2bin($data)); //pack('H*', $data)
  }

}
