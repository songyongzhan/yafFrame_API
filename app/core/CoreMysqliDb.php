<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/11/23
 * Time: 14:47
 * Email: songyongzhan@qianbao.com
 */

class CoreMysqliDb extends MysqliDb {


  public function rawQuery($query, $bindParams = NULL) {
    $params = array(''); // Create the empty 0 index
    $this->_query = $query;
    $stmt = $this->_prepareQuery();

    if (is_array($bindParams) === TRUE && count($bindParams) > 0) {
      foreach ($bindParams as $prop => $val) {
        $params[0] .= $this->_determineType($val);
        array_push($params, $bindParams[$prop]);
      }

      call_user_func_array(array($stmt, 'bind_param'), $this->refValues($params));
    }

    $stmt->execute();
    $this->count = $stmt->affected_rows;
    $this->_stmtError = $stmt->error;
    $this->_stmtErrno = $stmt->errno;
    $this->_lastQuery = $this->replacePlaceHolders($this->_query, $params);
    $res = $this->_dynamicBindResults($stmt);
    $this->reset();

    return $res;
  }


}