<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/17
 * Time: 14:08
 * Email: songyongzhan@qianbao.com
 */

class BaseModel extends CoreModel {

  use TraitCommon;

  private   $_db;
  protected $table;
  protected $id = 'id'; //表主键


  public static $header = [

  ];

  public function _init() {
    $this->_db = Yaf_Registry::has('db') ? Yaf_Registry::get('db') : NULL;
    $this->table = strtolower(get_class($this));
  }


  public function add($data, $table = NULL) {

  }


  public function update($where, $data) {

  }

  public function getOne() {

  }

  public function getList() {

  }

  public function getListPage() {

  }


  public function query($data, $table = '', $where = '', $type = 'select') {

    //$data 可以是数组
    //$data 如果是字符串，则认为是sql文件，直接直接
  }

  
}