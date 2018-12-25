<?php return array (
  'rules' => 
  array (
    'index' => 
    array (
      'user' => 'require',
      'password' => 'require',
      'type' => 'require|lt:25',
      'message' => 'require',
    ),
    'edit' => 
    array (
      'id' => 'require|integer',
    ),
  ),
  'params' => 
  array (
    'index' => 
    array (
      0 => 'user',
      1 => 'password',
      2 => 'type',
      3 => 'message',
    ),
    'edit' => 
    array (
      0 => 'id',
      1 => 'data',
    ),
  ),
  'msg' => 
  array (
    'index' => 
    array (
      'user.require' => '',
      'password.require' => '',
      'type.require|lt:25' => '',
      'message.require' => '',
    ),
    'edit' => 
    array (
      'id.require|integer' => '',
    ),
  ),
  'file' => '/usr/local/nginx/html/api/app/modules/Api/services/User.php',
);