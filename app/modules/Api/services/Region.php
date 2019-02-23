<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2019/1/29
 * Time: 16:17
 * Email: 574482856@qq.com
 */

defined('APP_PATH') OR exit('No direct script access allowed');

/**
 * 关联城市列表 操作服务类
 * Class RegionService
 */
class RegionService extends BaseService {

  protected $field = ['region_id as id', 'parent_id', 'region_name as text', 'region_type'];

  /**
   * 获取列表
   * @param array $where
   * @param $field
   * @return mixed
   */
  public function getList(array $where) {
    $result = $this->regionModel->getList($where, ['region_id as id', 'region_name as text']);

    return $this->show($result);
  }


  /**
   * 根据ids获取所有城市
   * @param mixed $ids <require> 城市id不能为空
   */
  public function getListById($ids) {
    $where = [
      getWhereCondition('region_id', is_array($ids) ? $ids : explode(',', trim($ids, ',')), 'in')
    ];
    $result = $this->regionModel->getList($where, ['region_id as id', 'region_name as text']);
    return $this->show($result);
  }

}