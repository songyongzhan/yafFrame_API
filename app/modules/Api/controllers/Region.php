<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2019/1/29
 * Time: 16:17
 * Email: 574482856@qq.com
 */


defined('APP_PATH') OR exit('No direct script access allowed');

class RegionController extends ApiBaseController {

  /**
   * 获取城市列表
   * @return mixed
   */
  public function getListAction() {

    $parent_id = $this->_post('parent_id', ''); //一般使用这个即可，每次都通过数据库去查询
    $region_type = $this->_post('region_type', '');

    $rules = [
      ['condition' => '=',
        'key_field' => ['parent_id', 'region_type'],
        'db_field' => ['parent_id', 'region_type']
      ]
    ];

    $data = ['parent_id' => $parent_id, 'region_type' => $region_type];
    $where = $this->where($rules, array_filter($data, 'filter_empty_callback'));
    $result = $this->regionService->getList($where);
    return $result;
  }







}