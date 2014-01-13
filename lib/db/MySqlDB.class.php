<?php

class MySqlDB implements DatabaseInterface {
  /* hold the connection */

  static private $db;

  static public function getConnection() {
    if (!isset(self::$db)) {
      self::$db = @MYSQL_CONNECT(DB_HOST, DB_USER, DB_PASS) or die(ERROR_DB_CONNECTION_FAILED);
      @MYSQL_SELECT_DB(DB_NAME, self::$db);
    }
    return self::$db;
  }

  static private function __getWhereStatement($where) {
    if (empty($where)) {
      return '';
    } else {
      $wherestr = array();
      foreach (array_keys($where) as $key) {
        $key = escapeStr($key);
        $value = escapeStr($where[$key]);
        $wherestr[] = " `$key` = '$value' ";
      }
      $statement = ' WHERE ' . join(' AND ', $wherestr) . ' ';
      return $statement;
    }
  }

  /**
   * Gets row from database
   *
   * @param string $table
   * @param array $where
   * @return array - result row as array
   */
  static public function getLine($table, $where = array()) {
    $wherestr = self::__getWhereStatement($where);
    $query = "SELECT * FROM `$table` $wherestr LIMIT 1";
    $result = mysql_query($query, self::getConnection()) or die($query . "  " . mysql_error());
    $data = mysql_fetch_array($result, MYSQL_ASSOC);
    foreach ($data as $key => $value) {
      $data[$key] = stripcslashes($value);
    }
    return $data;
  }

  /**
   * Counts the lines that match the where clause
   * @param type $table the table
   * @param type $where the where clause
   * @return type the amount of lines that matches the where clause
   */
  static public function countLines($table, $where = array()) {
    $wherestr = self::__getWhereStatement($where);
    $query = "SELECT COUNT(*) AS res FROM `$table` $wherestr LIMIT 1";
    $result = mysql_query($query, self::getConnection()) or die($query . "  " . mysql_error());
    return mysql_result($result, 0, 0);
  }

  /**
   * Gets rows from database
   *
   * @param string $table
   * @param array $where
   * @param string $order - fieldname to order by
   * @return array - result row as array
   */
  static public function getLines($table, $where = array(), $order = '') {
    $wherestr = self::__getWhereStatement($where);
    if ($order != '') {
      $orderBy = "ORDER BY $order";
    }
    $query = "SELECT * FROM `$table` $wherestr $orderBy";
    $result = mysql_query($query, self::getConnection()) or die($query . "  " . mysql_error());
    $data = array();
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      foreach ($row as $key => $value) {
        $row[$key] = stripcslashes($value);
      }
      $data[] = $row;
    }
    return $data;
  }

  static public function getQuery($query) {
    $result = mysql_query($query, DB::getConnection());
    $data = array();
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      foreach ($row as $key => $value) {
        $row[$key] = stripcslashes($value);
      }
      $data[] = $row;
    }
    return $data;
  }

  /**
   * Inserts data from array into table and returns the id of the new row
   *
   * @param string $table
   * @param string $data
   * @return int/string - id of new tuple
   */
  static public function insertLine($table, $data) {
    if (!empty($data)) {
      $db = self::getConnection();
      foreach (array_keys($data) as $key) {
        $data[$key] = escapeStr($data[$key]);
      }
      $query = "INSERT INTO `$table` (`" . join('`, `', array_keys($data)) . "`) VALUES('" . join("', '", $data) . "')";
      mysql_query($query, $db) or die(mysql_error());
      $id = mysql_insert_id($db) or die(mysql_error());
      return $id;
    }
    return false;
  }

  /**
   * Inserts data from array into table using attribute $key as auto_incrementing key
   * @param string $table
   * @param string $key
   * @param array $data
   * @return int/string - id of new tuple
   */
  static public function insertLineWithKey($table, $key, $data) {
    $id = self::getMaxFieldValue($table, $key) + 1;
    $data[$key] = $id;
    return self::insertLine($table, $data);
  }

  /**
   * Returns maximum value for attribut
   * @param string $table
   * @param string $fieldname
   * @return int/string - maximum value of attribut
   */
  static public function getMaxFieldValue($table, $fieldname) {
    $db = self::getConnection();
    $query = "SELECT MAX($fieldname) AS maxFieldValueResult FROM $table";
    $result = mysql_query($query, $db) or die(mysql_error());
    $resultArray = mysql_fetch_array($result, MYSQL_ASSOC);
    return $resultArray['maxFieldValueResult'];
  }

  /**
   * Removes all rows from table, which match conditions in where-array
   *
   * @param string $table
   * @param array $where
   */
  static public function removeLine($table, $where = array()) {
    $wherestr = self::__getWhereStatement($where);
    $query = "DELETE FROM `$table` $wherestr ";
    mysql_query($query, self::getConnection()) or die($query . "  " . mysql_error());
  }

  /**
   * Update all matching rows in table
   *
   * @param string $table
   * @param array $data
   * @param array $where
   */
  static public function update($table, $data, $where) {
    $whereStatement = self::__getWhereStatement($where);
    $updateStr = array();
    foreach (array_keys($data) as $key) {
      $value = escapeStr($data[$key]);
      $updateStr[] = "`$key` = '$value'";
    }
    $updateStatement = join(', ', $updateStr);
    $query = "UPDATE $table SET $updateStatement $whereStatement";
    mysql_query($query, self::getConnection());
  }

  /**
   * Get all matching field(s) given by $fieldnames
   * @param string $table
   * @param string/array $fieldnames
   * @param array $where
   */
  static public function getFields($table, $fieldnames, $where = array()) {
    $whereStr = self::__getWhereStatement($where);
    if (is_array($fieldnames)) {
      $fields = '`' . implode('`, `', $fieldnames) . '`';
    } else {
      $fields = `$fieldnames`;
    }
    $query = "SELECT $fields FROM `$table` $whereStr";
    $result = mysql_query($query, self::getConnection()) or die($query);
    $data = array();
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      foreach ($row as $key => $value) {
        $row[$key] = stripcslashes($value);
      }
      $data[] = $row;
    }
    return $data;
  }

  /**
   * Checks if a table exists or not
   * @param string $table - name of the table
   * @return boolean
   */
  static public function hasTable($table) {
    $query = "SHOW TABLES LIKE '$table';";
    $result = mysql_query($query, self::getConnection()) or die($query . "  " . mysql_error());
    if (mysql_fetch_row($result)) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Checks if a table with column exists or not
   * @param string $table - name of the table
   * @param string $column - name of the column
   * @return boolean
   */
  static public function hasColumn($table, $column) {
    $query = "SHOW COLUMNS FROM $table";
    $result = mysql_query($query, self::getConnection()) or die($query . "  " . mysql_error());
    while ($data = mysql_fetch_row($result)) {
      if (stristr($data[0], $column)) {
        return true;
      }
    }
    return false;
  }

  /**
   * Get rows which matches the where statement and joins automatically
   * @param string $table
   * @param array $where
   * @param string $order
   * @return array
   */
  static public function getJoin($table, $where = array(), $order = '') {
    $whereStr = self::__getWhereStatement($where);
    if ($order != '') {
      $orderStr = "ORDER BY $order";
    }
    $columnNames = self::getLine($table);
    $joinTables = array();
    foreach (array_keys($columnNames) as $column) {
      if (stristr($column, '_FKTBL_') !== FALSE) {
        $joinTables[$column] = substr($column, strpos($column, '_FKTBL_') + 7);
      }
    }
    $joinStr = "";
    foreach (array_keys($joinTables) as $tblKey) {
      $tbl = $joinTables[$tblKey];
      $columnname = substr($tbl, strpos($tbl, '_') + 1);
      $joinStr .= " JOIN $tbl ON $tbl.id_$columnname = $table.$tblKey ";
    }
    $query = "SELECT * FROM $table $joinStr $whereStr $orderStr";
    $result = mysql_query($query, self::getConnection()) or die($query . "  " . mysql_error());
    $data = mysql_fetch_array($result, MYSQL_ASSOC);
    return $data;
  }

  static public function createDump($filename = '') {
    if ('' == $filename) {
      $filename = date('Y-m-d', time()) . '-DB-BACKUP.sql';
    }
    if (stristr($filename, '.sql') === FALSE) {
      $filename = $filename . '.sql';
    }
    system('mysqldump -u ' . DB_USER . ' -p ' . DB_PASS . ' ' . DB_NAME . ' > ' . BACKUP_DIR . $filename);
  }

  static public function restoreDumb($filename = '') {
    if (stristr($filename, '.sql') === FALSE) {
      $filename = $filename . '.sql';
    }
    if ('' != DB_PASS) {
      $password = ' -p ' . DB_PASS;
    }
    system('mysql -u ' . DB_USER . $password . ' ' . DB_NAME . ' < ' . BACKUP_DIR . $filename);
  }

  /**
   * Removes all rows from $tableToCleanUp with a value for $conditionColumn that does not exist in the $conditionColumn of the $partentTable
   * @param $tableToCleanUp
   * @param $cleanUpconditionColumn
   * @param $partenTable
   * @param $partentConditionColumn
   */
  static public function cleanUpByForeignKey($tableToCleanUp, $cleanUpconditionColumn, $partentTable, $partentConditionColumn) {
    $query = "DELETE FROM $tableToCleanUp WHERE $cleanUpconditionColumn NOT IN (SELECT $partentConditionColumn FROM $partentTable)";
    mysql_query($query, self::getConnection()) or die($query . "  " . mysql_error());
  }

}

?>