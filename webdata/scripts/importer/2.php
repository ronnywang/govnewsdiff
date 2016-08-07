<?php

// 把經濟部重大政策匯入工具

include(__DIR__ . '/../../init.inc.php');

for ($id = 55459; $id >= 0; $id --) {
    $url = 'http://www.moea.gov.tw/MNS/populace/news/News.aspx?kind=2&menu_id=41&news_id=' . $id;
    $content = file_get_contents($url);
    if (strpos($content, '您要查看的網頁可能已被刪除、名稱已被更改，或者暫時不可用')) {
        continue;
    }
    error_log($id);
    News::addNews($url, 2);
}
