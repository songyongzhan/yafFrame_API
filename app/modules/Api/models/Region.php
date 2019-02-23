<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/12/28
 * Time: 11:40
 * Email: 574482856@qq.com
 *
 * 城市列表 模型
 */
defined('APP_PATH') OR exit('No direct script access allowed');

class RegionModel extends BaseModel {
  
  //不支持createtime和updatetime
  protected $autoaddtime = FALSE;

  //设置删除为 真实删除  我们默认是不支持删除功能的
  protected $realDelete = TRUE;

  protected $id = 'region_id';

}