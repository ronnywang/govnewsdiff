<?php

include(__DIR__ . '/../init.inc.php');
Pix_Table::$_save_memory = true;

$sources = News::getSources();

$start = microtime(true);
$insert_count = 0;
$max_insert = 500;
$keys = array_keys($sources);
// 隨機跑新聞來源，避免在前面的總是優先被跑到
shuffle($keys);

foreach ($keys as $id) {
    $class = $sources[$id][1];
    if ($max_insert - $insert_count <= 0) {
        break;
    }
    // 超過 8 分鐘就不要跑了...以免累積太多 job
    if (microtime(true) - $start > 8 * 60) {
        error_log("excess 8 minutes");
        break;
    }

    try {
        list($update, $insert) = call_user_func(array($class, 'crawl'), $max_insert - $insert_count);
    } catch (Exception $e) {
        error_log("$class failed: " . $e->getMessage());
        continue;
    }
    if ($update) {
        KeyValue::set('source_update-' . $id, time());
    }
    if ($insert) {
        $insert_count += $insert;
        KeyValue::set('source_insert-' . $id, time());
    }
}

error_log('Insert_count: ' . $insert_count);
