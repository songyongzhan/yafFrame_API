<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/22
 * Time: 10:39
 * Email: songyongzhan@qianbao.com
 */

class UserController extends BaseController {

  public function indexAction() {
    echo 'User Index';
  }


  public function testAction() {

    $arr = [5, 1, 7, 15, 0, 4, 6, 10, 14, 88];

    //$arr = [0, 1, 2, 3, 4, 5, 6, 7, 8, 99, 9, 10, 11, 12, 90, 13, 14, 15, 16, 17, 100, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 40, 50, 60];

    $bitMap = new Bitmap();

    $bitdata = $bitMap->createBitMap($arr)->getBitmap();

    $ishave = $bitMap->checkExists(88);

    printf('数组中%s是否含有88 ::::: %s<br>', var_export($arr, TRUE), is_null($ishave) ? '无' : '有');

    P($bitdata);

    printf('数组中%s是否含有77 ::::: %s<br>', var_export($arr, TRUE), is_null($bitMap->checkExists(77)) ? '无' : '有');
    $bitMap->addData(77);
    printf('添加一个77<br>');
    printf('数组中%s是否含有77 ::::: %s<br>', var_export($arr, TRUE), is_null($bitMap->checkExists(77)) ? '无' : '有');

    printf('删除88<br>');
    $bitMap->delData(88);
    printf('数组中%s是否含有88 ::::: %s<br>', var_export($arr, TRUE), is_null($bitMap->checkExists(88)) ? '无' : '有');


    printf('获取原始数组<br>');

    P($bitMap->getData());


    //排序功能
    //$sortData = Bitmap::bitSort($arr);
    //P($sortData);

  }

}