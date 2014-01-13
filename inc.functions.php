<?php

function __autoload($classname) {
  global $CLASSPATH;
  foreach ($CLASSPATH as $path) {
    $file = ROOT . $path . $classname . '.class.php';
    if (file_exists($file)) {
      require_once($file);
    }
  }
}

function debug($msg) {
  $debugInformation = debug_backtrace();
  foreach ($debugInformation as $info) {
    print $info['file'] . ':' . $info['line'] . '<BR/>';
  }
  print "<PRE>" . print_r($msg, true) . "</PRE>";
}

function str_contains($str, $subStr) {
  return (strpos($str, $subStr) !== false);
}

function st2date($timestamp) {
  return date('Y-m-d H:i:s', $timestamp);
}

function timestamp2Date($timestamp) {
  return st2date($timestamp);
}

function object2array($object) {
  return @json_decode(@json_encode($object), 1);
}

function escapeStr($str) {
  if (!mb_check_encoding($str, 'UTF-8') || !($str === mb_convert_encoding(mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32'))) {
    $str = mb_convert_encoding($str, 'UTF-8');
  }
  $str = mysql_real_escape_string($str, DB::getConnection());
  return $str;
}

function smarty_prefilter_enable_utf8_encoding($tpl_source, &$smarty) {
  $data .= '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
  $data .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "xhtml1-transitional.dtd">' . "\n";
  return $data . $tpl_source;
}

?>