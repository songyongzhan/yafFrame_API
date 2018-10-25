<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/18
 * Time: 15:17
 * Email: songyongzhan@qianbao.com
 */

class UserService extends BaseService {


  public $name = 'UserServiceName';

  /**
   * @param string $user <require>
   * @param string $password <require>
   * @param int $type <require|lt:25>
   * @param string $message <require>
   * @return mixed
   */
  public function index($user, $password, $type, $message) {


    $data = $this->UserModel->index([
      'user'=>$user,
      'password'=>$password,
      'type'=>$type,
      'message'=>$message,
    ]);

    return $this->show($data);

  }

  /**
   * @param array $data 需要添加的数据
   */
  public function add($data) {

  }


  /**
   * @param int $id <require|integer>
   * @param $data
   */
  public function edit($id, $data) {

    return $this->show(['age' => 14, 'user' => 'bbb']);
  }

  public function bb($int) {
    $data=$this->UserModel->queryBidList($int);

    return $this->show($data);

  }


}