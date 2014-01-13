<?php

interface DatabaseInterface {

	static public function getConnection();
	static public function getLine($table, $where = array());
  static public function countLines($table, $where = array());
	static public function getLines($table, $where = array(), $order = '');
	static public function insertLineWithKey($table, $key, $data);
	static public function getMaxFieldValue($table, $fieldname);
	static public function insertLine($table, $data);
	static public function update($table, $data, $where);
	static public function getFields($table, $fieldnames, $where = array());
	static public function cleanUpByForeignKey($tableToCleanUp, $cleanUpconditionColumn, $partentTable, $partentConditionColumn);

}

?>