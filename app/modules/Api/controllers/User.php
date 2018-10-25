<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/18
 * Time: 9:44
 * Email: songyongzhan@qianbao.com
 */

class UserController extends ApiBaseController {

  public function bbAction() {
    $data = $this->UserService->bb(1);
    return $data;
  }

  public function indexAction() {

    $data = $this->UserService->index('james', '123', '2', 'ff');

    P($data);

    exit;

  }


}