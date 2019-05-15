<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2019/5/6
 * Time: 22:34
 * Email: 574482856@qq.com
 */

defined('APP_PATH') OR exit('No direct script access allowed');


class AttributeService extends BaseService {

  protected $field = ['id', 'title', 'status', 'sort_id', 'updatetime', 'createtime'];


  public function getListPage(array $where, $page_num, $page_size) {
    $result = $this->attributeModel->getListPage($where, $this->field, $page_num, $page_size);
    return $this->show($result);
  }

  /**
   * @method add
   * @param string $title <require> 属性标签不能为空
   * @param string $input_type <require> 元素类型不能为空
   * @param string $input_label <require> 标签名不能为空
   * @param string $input_name <require> 元素名称不能为空
   * @param string $input_width <number> 元素宽度必须是数字
   * @param string $column_type <require> 字段类型不能为空
   * @return array
   * 2019/5/10 7:03
   * @throws Exception
   */
  public function add($data) {

    $this->_check($data);

    $lastInsertId = $this->attributeModel->insert($data);
    if ($lastInsertId) {
      $data['id'] = $lastInsertId;
      return $this->show($data);
    } else {
      return $this->show([], StatusCode::INSERT_FAILURE);
    }
  }

  /**
   * @method delete
   * @param int $id <require|number> id不能为空|id必须是数字
   * @return array
   * @throws InvalideException
   * 2019/5/10 7:09
   */
  public function delete($id) {
    $where = [
      getWhereCondition('attribute_id', $id)
    ];

    if ($this->entitycolumnModel->getCount($where) > 0)
      showApiException('此项已在使用，请删除关联后再删除', StatusCode::ATTRIBUTE_USED);

    $result = $this->attributeModel->delete($id);
    return $result > 0 ? $this->show(['row' => $result, 'id' => $id]) : $this->show([], StatusCode::DATA_NOT_EXISTS);
  }

  /**
   * @method getOne
   * @param int $id <require|number> id不能为空|id必须是数字
   * @param string $fileds
   * @return array
   * 2019/5/10 7:08
   */
  public function getOne($id, $fileds = '*') {
    if ($fileds == '*')
      $fileds = $this->field;

    $result = $this->attributeModel->getOne($id, $fileds);
    return $result ? $this->show($result) : $this->show([], StatusCode::DATA_NOT_EXISTS);
  }

  /**
   * @method add
   * @param int $id <require|number> id不能为空|id必须是数字
   * @param string $title <require> 属性标签不能为空
   * @param string $input_type <require> 元素类型不能为空
   * @param string $input_label <require> 标签名不能为空
   * @param string $input_name <require> 元素名称不能为空
   * @param string $input_name <require> 元素名称不能为空
   * @param string $input_width <number> 元素宽度必须是数字
   * @param string $column_type <require> 字段类型不能为空
   * @return array
   * 2019/5/10 7:03
   */
  public function update($id, $data) {
    $this->_check($data, 'update');
    $result = $this->attributeModel->update($id, $data);
    if ($result)
      $data['id'] = $id;

    return $result ? $this->show($data) : $this->show([]);
  }


  /**
   * 添加、修改属性判断 目的 不要出现重复的input_name 和 title
   * @method _check
   * @param $data
   * @param string $method update方法时，增加过滤条件
   * @throws InvalideException
   * 2019/5/11 6:42
   */
  private function _check($data, $method = 'add') {

    //添加 判断 title 和 input_name 不能重名
    $titleWhere = [getWhereCondition('title', $data['title'])];
    $method == 'update' && $titleWhere[] = getWhereCondition('id', $data['id'], '!=');
    $titleCount = $this->attributeModel->getCount();
    if ($titleCount > 0)
      showApiException('此名称已经存在，请更改', StatusCode::ATTRIBUTE_TITLE_EXISTS);

    $inputNameWhere = [getWhereCondition('input_name', $data['input_name'])];
    $method == 'update' && $inputNameWhere[] = getWhereCondition('id', $data['id'], '!=');

    $inputNameCount = $this->attributeModel->getCount();
    if ($inputNameCount > 0)
      showApiException('此名称已经存在，请更改', StatusCode::ATTRIBUTE_INPUTNAME_EXISTS);

  }


}