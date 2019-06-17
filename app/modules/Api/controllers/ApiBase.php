<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/23
 * Time: 9:38
 * Email: 574482856@qq.com
 */

defined('APP_PATH') OR exit('No direct script access allowed');

class ApiBaseController extends BaseController {

  /**
   * API 统一入口
   * @param $method
   * @param array $params
   * @throws Exception
   */
  public function _remapAction() {
    try {
      $parseUri = $this->_parseUri();
      $controller = getInstance($parseUri['controller'], $parseUri['module']);
      $data = call_user_func_array([$controller, $parseUri['action'] . 'Action'], $this->getRequest()->getParams());
      $this->showJson($data['result'], $data['code'], $data['msg']);
    } catch (Exception $e) {

      //var_dump($e->getMessage());exit;
      //$this->showJson([], API_FAILURE, $e->getMessage());
      show_error($e->getMessage(), $e->getCode());
      //showApiException($e->getMessage(), $e->getCode());
    }
  }


  private function showJson($result, $code = API_SUCCESS, $msg = NULL) {
    $data = [
      'status' => empty($code) ? API_SUCCESS : $code,
      'message' => empty($msg) ? '' : $msg,
      'result' => $result ?: []
    ];
    //header('Content-Type: application/json; charset=utf-8');
    header('Content-Type:text/plain; charset=utf-8');
    echo jsonencode($data);
    exit;
  }

  /**
   *
   * @param $rules
   * @param null $data
   * @return array|bool|mixed
   */
  public function where($rules, $data = NULL) {
    if (!is_array($rules) || !$data) {
      return [];
    }

    $where = [];

    foreach ($rules as $key => $val) {
      $condition_type = $val['condition'];
      switch ($condition_type) {
        case 'not null':
          $where = $this->_null_condition($val, $where, 'not null', $data);
          break;
        case 'null':
          $where = $this->_null_condition($val, $where, 'null', $data);
          break;
        case 'in':
          $where = $this->_in_condition($val, $where, 'in', $data);
          break;
        case 'not in':
          $where = $this->_in_condition($val, $where, 'not in', $data);
          break;
        case 'like':
          $where = $this->_like_condition($val, $where, 'like', $data);
          break;
        case 'after like':
          $where = $this->_like_condition($val, $where, 'after like', $data);
          break;
        case 'before like':
          $where = $this->_like_condition($val, $where, 'before like', $data);
          break;
        case 'between':
          if (count($val['key_field']) < 2) {
            break;
          }
          //就循环2次
          for ($f_key = 0; $f_key < 2; $f_key++) {
            $f_filed = $val['key_field'][$f_key];
            if (!isset($data[$f_filed])) break;
            $key_value = isset($data[$f_filed]) ? $data[$f_filed] : '';
            if (isset($val['db_field'][$f_key])) {
              $_db_fields = $val['db_field'][$f_key];
              $condition = $f_key == 0 ? '>=' : '<=';
              $where[] = [
                'field' => trim($_db_fields),
                'val' => trim($key_value),
                'operator' => $condition
              ];
            }
          }
          break;
        default :
          foreach ($val['key_field'] as $f_key => $f_filed) {
            if (!isset($data[$f_filed])) continue;
            $where[] = [
              'field' => trim($val['db_field'][$f_key]),
              'val' => isset($data[$f_filed]) ? trim($data[$f_filed]) : '',
              'operator' => $condition_type
            ];
          }
          break;
      }
    }
    return $where;
  }


  /**
   * 处理like 或 not like
   * @param array $val
   * @param array $where 条件
   * @param string $condition_type 处理类型
   * @return mixed
   */
  private function _like_condition($val, $where, $condition_type, $data) {
    foreach ($val['key_field'] as $f_key => $f_filed) {
      if (!isset($data[$f_filed])) continue;
      $key_value = isset($data[$f_filed]) ? $data[$f_filed] : '';
      if (isset($val['db_field'][$f_key])) {
        $_db_fields = $val['db_field'][$f_key];
        switch ($condition_type) {
          case 'like':
            $where[] = [
              'field' => $_db_fields,
              'val' => '%' . trim($key_value) . '%',
              'operator' => 'like',
              'condition' => 'AND'
            ];
            break;
          case 'after like':
            $where[] = [
              'field' => $_db_fields,
              'val' => trim($key_value) . '%',
              'operator' => 'like',
              'condition' => 'AND'
            ];

            break;
          case 'before like':
            $where[] = [
              'field' => $_db_fields,
              'val' => '%' . trim($key_value),
              'operator' => 'like',
              'condition' => 'AND'
            ];
            break;
        }
      }
    }
    return $where;
  }

  /**
   * 处理null或 not null
   * @param array $val
   * @param array $where 条件
   * @param string $condition_type 处理类型
   * @return mixed
   */
  private function _null_condition($val, $where, $condition_type, $data) {
    foreach ($val['key_field'] as $f_key => $f_filed) {
      if (isset($val['db_field'][$f_key])) {
        $_db_fields = $val['db_field'][$f_key];
        if (isset($data[$_db_fields])) {
          if ($condition_type == 'null')
            $operator = 'IS';
          else $operator = 'IS NOT';

          $where[] = [
            'field' => $_db_fields,
            'val' => NULL,
            'operator' => $operator,
            'condition' => 'AND'
          ];

        }
      }
    }
    return $where;
  }

  /**
   * 处理in 或not in
   * @param array $val
   * @param array $where 条件
   * @param string $condition_type 处理类型
   * @return mixed
   */
  private function _in_condition($val, $where, $condition_type, $data) {

    foreach ($val['key_field'] as $f_key => $f_filed) {
      if (!isset($data[$f_filed])) continue;

      if (isset($data[$f_filed]) && is_string($data[$f_filed]))
        $data[$f_filed] = explode(',', trim($data[$f_filed], ','));

      $where[] = [
        'field' => trim($val['db_field'][$f_key]),
        'val' => isset($data[$f_filed]) ? $data[$f_filed] : '',
        'operator' => $condition_type,
        'condition' => 'AND'
      ];
    }

    return $where;
  }

  /**
   * 获取当前uri中控制器和方法
   * @return array
   * @throws Exceptions
   */
  private function _parseUri() {
    return _parseCurrentUri();
  }

  /**
   * 过滤掉空字符
   * @method filterData
   * @param $data
   * @return array
   * 2019/5/10 6:55
   */
  protected function filterData($data) {
    return array_filter($data, 'filter_empty_callback');
  }


}
