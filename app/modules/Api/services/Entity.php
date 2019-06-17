<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2019/5/6
 * Time: 22:34
 * Email: 574482856@qq.com
 */

defined('APP_PATH') OR exit('No direct script access allowed');


class EntityService extends BaseService {

  protected $field = ['id', 'title', 'descript', 'table_name', 'table_engine', 'listorder', 'commenttxt', 'status', 'sort_id', 'updatetime', 'createtime'];

  public function getListPage(array $where, $page_num, $page_size) {
    $result = $this->entityModel->getListPage($where, $this->field, $page_num, $page_size);
    return $this->show($result);
  }

  /**
   * @method add
   *
   * @param string $title <require> 标题不能为空
   * @param string $table_name <require|alphaDash> 表名不能为空|表名只能是字母数字下划线
   * @param string $descript <require> 描述不能为空
   * @return array
   * 2019/5/19 16:47
   */
  public function add($data) {
    $lastInsertId = $this->entityModel->insert($data);
    if ($lastInsertId) {
      $data['id'] = $lastInsertId;
      return $this->show($data);
    } else {
      return $this->show([], StatusCode::INSERT_FAILURE);
    }
  }

  /**
   * 删除
   * @param int $id <require|number> id
   */
  public function delete($id) {
    $result = $this->entityModel->delete($id);
    return $result > 0 ? $this->show(['row' => $result, 'id' => $id]) : $this->show([], StatusCode::DATA_NOT_EXISTS);
  }

  /**
   * 获取单个信息
   * @param int $id <require|number> id不能为空|id必须是数字
   * @param string $fileds
   * @return mixed
   */
  public function getOne($id, $fileds = '*') {
    if ($fileds == '*')
      $fileds = array_merge($this->field, ['ext', 'listcolumn']);

    $result = $this->entityModel->getOne($id, $fileds);
    return $result ? $this->show($result) : $this->show([], StatusCode::DATA_NOT_EXISTS);
  }

  /**
   * 分组更新数据
   * @param int $id <require|number> id不能为空|id必须是数字
   * @param string $title <require> 名称
   * @param int $status <require|number> 状态
   * @return array mixed 返回用户数据
   */

  public function update($id, $data) {
    $result = $this->entityModel->update($id, $data);
    if ($result) {
      $data['id'] = $id;
    }
    return $result ? $this->show($data) : $this->show([]);
  }

  /**
   * 更新实体列表显示的字段类型
   * @method updateListColumn
   * @param int $id <require|number> id
   * @param string $listcolumn <require> 后台列表显示字段不能为空
   * @return array
   * @throws Exception
   * 2019/5/12 17:55
   */
  public function updateListColumn($id, $listcolumn) {

    if (!$listcolumn)
      showApiException('后台列表显示字段不能为空');

    $listcolumn = explode(',', trim($listcolumn, ','));

    return $this->update($id, ['listcolumn' => $listcolumn]);
  }

}