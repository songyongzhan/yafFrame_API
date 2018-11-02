<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/23
 * Time: 10:25
 * Email: songyongzhan@qianbao.com
 */

class GoodsController extends ApiBaseController {


  public function indexAction() {

    //$rec= new Reflec($this);

    //return $this->UserService->index('', 'jjjj', 2, '登录成功');

    return $this->UserService->edit(11,[]);

  }

  public function testAction(){

    echo PHP_INT_SIZE;

    exit;
  }


}