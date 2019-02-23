<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/18
 * Time: 15:17
 * Email: 574482856@qq.com
 */

defined('APP_PATH') OR exit('No direct script access allowed');

class LogsService extends BaseService {

  const FIELD = ['id', 'controller', 'method', 'ip', 'exe_type', 'manage_id', 'createtime'];



  /**
   * 获取日志记录
   * @param array $where 搜索条件
   * @param $page_num 页码
   * @param $page_size 每页显示条数
   * @return mixed
   */
  public function getList($where, $page_num, $page_size) {
    //$where = platform_where($where, 'log.');
    $result = $this->logsModel->getLogsPage($where, self::FIELD, $page_num, $page_size);
    if ($result) {
      foreach ($result['list'] as &$val) {
        if ($val['ip'] != '') $val['ip'] = long2ip($val['ip']);
      }
    }
    return $this->show($result);
  }

  /**
   * 获取单条记录
   * @param int $id <required|numeric> id
   * @param string $field
   * @return mixed
   */
  public function getOne($id, $field = '*') {
    if ($field === '*') {
      $field = self::FIELD;
      $field = array_merge($field, ['detail', 'exe_sql']);
    }
    $result = $this->logsModel->getOne($id, $field);
    if (isset($result['ip']) && $result['ip']) $result['ip'] = long2ip($result['ip']);
    if (isset($result['detail']) && $result['detail']) $result['detail'] = jsondecode($result['detail']);
    return $result ? $this->show($result) : $this->show([]);
  }
}