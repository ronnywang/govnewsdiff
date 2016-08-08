<?php

include(__DIR__ . '/../init.inc.php');
Pix_Table::addStaticResultSetHelper('Pix_Array_Volume');

mkdir(__DIR__ . '/dump/');

foreach (array('News', 'NewsInfo', 'NewsRaw', 'KeyValue') as $table) {
    $fp = null;
    error_log($table);
    foreach (Pix_Table::getTable($table)->search(1)->volumemode(100) as $row) {
        if (is_null($fp)) {
            $fp = gzopen(__DIR__ . '/dump/' . $table . '.jsonld.gz', 'w');
            fputs($fp, json_encode(array_keys($row->toArray())) . "\n");
        }
        fputs($fp, json_encode(array_values($row->toArray()), JSON_UNESCAPED_UNICODE) . "\n");
    }
    fclose($fp);
}
