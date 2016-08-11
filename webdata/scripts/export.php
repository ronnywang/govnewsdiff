<?php

include(__DIR__ . '/../init.inc.php');
Pix_Table::addStaticResultSetHelper('Pix_Array_Volume');

$sources = array();
foreach (array('News', 'NewsInfo', 'KeyValue') as $table) {
    $fp = null;
    error_log($table);
    foreach (Pix_Table::getTable($table)->search(1)->volumemode(1000) as $row) {
        if ($table == 'News') {
            $sources[$row->id] = $row->source;
        }
        if (is_null($fp)) {
            $fp = gzopen(__DIR__ . '/dump/' . $table . '.jsonld.gz', 'w');
            fputs($fp, json_encode(array_keys($row->toArray())) . "\n");
        }
        fputs($fp, json_encode(array_values($row->toArray()), JSON_UNESCAPED_UNICODE) . "\n");
    }
    fclose($fp);
}


$source_fp = array();
foreach (NewsRaw::search(1)->volumemode(100) as $row) {
    $source = $sources[$row->news_id];
    if (!array_key_exists($source, $source_fp)) {
        $fp = gzopen(__DIR__ . '/dump/NewsRaw-' . $source. '.jsonld.gz', 'w');
        fputs($fp, json_encode(array_keys($row->toArray())) . "\n");
        $source_fp[$source] = $fp;
    } else {
        $fp = $source_fp[$source];
    }
    fputs($fp, json_encode(array_values($row->toArray()), JSON_UNESCAPED_UNICODE) . "\n");
}
array_map('fclose', $source_fp);
