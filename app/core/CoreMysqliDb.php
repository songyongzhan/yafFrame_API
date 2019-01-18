<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/11/23
 * Time: 14:47
 * Email: 574482856@qq.com
 */


/**
 * 帮助文档 https://packagist.org/packages/joshcam/mysqli-database-class
 * Class CoreMysqliDb
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


  /**
   * 复写删除，返回受影响的条数
   * @param string $tableName
   * @param null $numRows
   * @return bool|int|void
   */
  public function delete($tableName, $numRows = NULL) {
    if ($this->isSubQuery) {
      return;
    }

    $table = self::$prefix . $tableName;

    if (count($this->_join)) {
      $this->_query = "DELETE " . preg_replace('/.* (.*)/', '$1', $table) . " FROM " . $table;
    } else {
      $this->_query = "DELETE FROM " . $table;
    }

    $stmt = $this->_buildQuery($numRows);
    $stmt->execute();
    $this->_stmtError = $stmt->error;
    $this->_stmtErrno = $stmt->errno;
    $this->reset();

    $this->count = $stmt->affected_rows;

    return $this->count;  //	affected_rows returns 0 if nothing matched where statement, or required updating, -1 if error
  }


  public function getTableScnema($tableName, $field = '*') {

    if (is_array($field))
      $field = implode(',', $field);

    if (strpos($tableName, '.') === FALSE) {
      $tableName = self::$prefix . $tableName;
    }

    $this->_query = 'SELECT ' . implode(' ', $this->_queryOptions) . ' ' .
      $field . " from information_schema . columns where TABLE_NAME = '".$tableName."' ";
    $stmt = $this->_buildQuery(NULL);

    $stmt->execute();
    $this->_stmtError = $stmt->error;
    $this->_stmtErrno = $stmt->errno;
    $res = $this->_dynamicBindResults($stmt);
    $this->reset();

    return $res;
  }


  public function getWhere() {
    return $this->_where;
  }


}