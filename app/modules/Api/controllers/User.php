<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/18
 * Time: 9:44
 * Email: songyongzhan@qianbao.com
 */

class UserController extends BaseController {



  public function indexAction(){

    getInstance();
    exit;
    echo ' modules Api index 方法';


    $db=Yaf_Registry::get('db');
    $data=$db->get('goods_category',10);
    P($data);

    P($this->getResponse());


//    $this->UserService->index();



    //$userModel=new UserModel();
    //
    //(new UserService())->index();
    //
    //P($userModel->index());
    //
    //P();


  }



}