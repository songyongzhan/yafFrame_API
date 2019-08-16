<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2019/5/15
 * Time: 23:22
 * Email: 574482856@qq.com
 */


defined('APP_PATH') OR exit('No direct script access allowed');

class AutomaticController extends ApiBaseController {

  /**
   * 搜索判断类型
   * @var array
   */
  protected $strType = ['varchar', 'char', 'text'];


  /**
   * 可以接受任何方法 支持这种方式
   * @method __call
   * @param $name
   * @param $arguments
   * @return array
   * 2019/8/7 23:49
   */
  public function __call($name, $arguments) {

    //$name xxxAction


    // 这是调用service的方法，暂时先注释掉
    //$name = str_replace('Action', '', $name);
    //return $this->automaticService->_call($name, $arguments, getFilterPost());


    //改成更新新的方法，调用控制的方法
    $entityId = $this->_post('entity_id');
    //首先判断是否包含有自己的controller和方法，如果存在，就执行自己的，不存在，则执行 automatic的方法
    $result = $this->runSelfMethod(__FUNCTION__, $entityId);
    if ($result) {
      return $result;
    }

    showApiException('调用的' . $name . '方法不存在');

  }

  /**
   *
   * @method entityColumnAction
   * 2019/7/28 13:54
   */
  public function entityColumnAction() {

    return $this->getViewColumn();
  }

  /**
   * @method getListAction
   * @return array
   * @throws InvalideException
   * 2019/8/11 10:19
   */
  public function getListAction() {

    $entityId = $this->_post('entity_id');
    //首先判断是否包含有自己的controller和方法，如果存在，就执行自己的，不存在，则执行 automatic的方法
    $result = $this->runSelfMethod(__FUNCTION__, $entityId);
    if ($result) {
      return $result;
    }

    //如果传递了page_size 就分页
    $page_size = $this->_post('page_size', PAGESIZE);
    $page_num = $this->_post('page_num', 1);


    $entityInfo = $this->entityService->getOne($entityId);
    if (!isset($entityInfo['result'])) {
      debugMessage('entityInfo 数据错误');
      return $this->baseService->show([]);
    }
    $entityInfo = $entityInfo['result'];
    $fields = $entityInfo['listcolumn'];
    $rules = $data = [];

    //组装搜索条件
    if ($entityInfo['searchcolumn']) {
      $column = $this->attributeService->getAttrbuteByColumn($entityInfo['searchcolumn'], $entityId);
      if (isset($column['result'])) {
        $column = $column['result'];
        foreach ($column as $key => $val) {
          $data[$val['input_name']] = $this->_post($val['input_name'], '');
          $rules[] = [
            'condition' => in_array($val['column_type'], $this->strType) ? 'like' : '=',
            'key_field' => [$val['input_name']],
            'db_field' => [$val['input_name']]
          ];
        }
      }
    }
    $where = $this->where($rules, $this->filterData($data));
    $result = $this->automaticService->getListPage($where, $page_num, $page_size, $this->_post('entity_id'), $fields);

    return $result;
  }

  public function addAction() {
    $entityId = $this->_post('entity_id');
    //首先判断是否包含有自己的controller和方法，如果存在，就执行自己的，不存在，则执行 automatic的方法
    $result = $this->runSelfMethod(__FUNCTION__, $entityId);
    if ($result) {
      return $result;
    }
    $data = $this->getData();
    $result = $this->automaticService->add($data, $this->_post('entity_id'));
    return $result;
  }


  public function updateAction() {

    $entityId = $this->_post('entity_id');
    //首先判断是否包含有自己的controller和方法，如果存在，就执行自己的，不存在，则执行 automatic的方法
    $result = $this->runSelfMethod(__FUNCTION__, $entityId);
    if ($result) {
      return $result;
    }


    $id = $this->_post('id');
    $data = $this->getData();
    $result = $this->automaticService->update($id, $data, $this->_post('entity_id'));
    return $result;
  }


  public function getOneAction() {

    $entityId = $this->_post('entity_id');
    //首先判断是否包含有自己的controller和方法，如果存在，就执行自己的，不存在，则执行 automatic的方法
    $result = $this->runSelfMethod(__FUNCTION__, $entityId);
    if ($result) {
      return $result;
    }

    $id = $this->_post('id');
    $fields = $this->_post('field', '*');
    $result = $this->automaticService->getOne($id, $this->_post('entity_id'), $fields);
    return $result;
  }

  public function deleteAction() {
    $entityId = $this->_post('entity_id');
    //首先判断是否包含有自己的controller和方法，如果存在，就执行自己的，不存在，则执行 automatic的方法
    $result = $this->runSelfMethod(__FUNCTION__, $entityId);
    if ($result) {
      return $result;
    }

    $id = $this->_post('id');
    $result = $this->automaticService->delete($id, $this->_post('entity_id'));
    return $result;
  }


  /**
   * 判断是否执行自己实体内的方法
   * @method isRunSelfMethod
   * @param $method
   * @param $entityId
   * 2019/8/12 0:38
   */
  private function runSelfMethod($method, $entityId) {

    $entitySelfController = $this->automaticService->havSelfControllerMethod($method, $entityId);
    if ($entitySelfController) {
      $className = $entitySelfController['className'];
      $methodName = $entitySelfController['method'];
      $data = getInstance($className)->$methodName();
      return $data;
    }
    return FALSE;
  }

  /**
   * 获取参数
   * @method getParam
   * @return array
   * 2019/5/10 6:49
   */
  private function getData() {
    $entityId = $this->_post('entity_id');
    $list = $this->entitycolumnService->getviewlist($entityId);
    $data = [];
    foreach ($list['result'] as $key => $val) {
      $data[$val['input_name']] = $this->_post($val['input_name'], '');
    }
    return $data;
  }


  /**
   * 用于显示列表页
   * @method getViewColumn
   * @return array
   * @throws InvalideException
   * 2019/5/15 23:36
   */
  private function getViewColumn() {

    $entityId = $this->_post('entity_id');
    return $this->entitycolumnService->getviewlist($entityId);
  }


}