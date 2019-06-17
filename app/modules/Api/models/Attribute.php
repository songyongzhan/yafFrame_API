<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2019/5/6
 * Time: 22:32
 * Email: 574482856@qq.com
 */

defined('APP_PATH') OR exit('No direct script access allowed');

class AttributeModel extends BaseModel {

  public function _init() {
    parent::_init(); // TODO: Change the autogenerated stub
  }

  /**
   * 根据多个ids 获取属性
   * @method getAttributeListByIds
   * @param $attributeIds
   * 2019/5/10 23:36
   */
  public function getAttributeListByIds($attributeIds) {

    //可以考虑缓存、redis、目前采用数据库查询

    $where = [
      getWhereCondition('id', $attributeIds, 'in')
    ];

    $result = $this->getList($where, ['id', 'input_label', 'validate_message', ',default_value', 'input_width', 'column_type', 'column_type', 'column_value', 'notnull', 'column_default', 'commenttxt', 'placeholder']);

    return $result;
  }
}





