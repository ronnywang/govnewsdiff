#!/usr/bin/env php
<?php

$path = dirname(__DIR__);

include($path . '/init.inc.php');
Pix_Table::$_save_memory = true;

foreach (array('News', 'NewsInfo', 'KeyValue') as $table) {
    $t = Pix_Table::getTable($table);
    $t->createTable();

    $fp = gzopen(__DIR__ . "/dump/{$table}.jsonld.gz", "r");
    $columns = json_decode(fgets($fp));
    $inserting = array();
    while ($rows = json_decode(fgets($fp))) {
        $inserting[] = $rows;
        if (count($inserting) >= 100) {
            $t->bulkInsert($columns, $inserting);
            $inserting = array();
        }
    }
    if (count($inserting)) {
        $t->bulkInsert($columns, $inserting);
        $inserting = array();
    }
}
