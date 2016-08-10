<?php

class Crawler_MOEA
{
    public static function crawl($insert_limit)
    {
        $insert = $update = 0;
        for ($page = 1; $page <= 1; $page ++) {
            $url = "http://www.moea.gov.tw/MNS/populace/news/News.aspx?kind=2&menu_id=41";
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_POSTFIELDS, 'ctl00$holderContent$grdNews$ctl13$uctlPages$dltPage$ctl02$btnPage=' . $page);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $content = curl_exec($curl);
            preg_match_all('#"\.\./\.\.(/populace/news/News.aspx\?kind=[0-9]*&amp;menu_id=[0-9]*&amp;news_id=[0-9]*)"#', $content, $matches);
            $urls = array_map(function($u) { return 'http://www.moea.gov.tw/MNS' . htmlspecialchars_decode(trim($u, '"')); }, $matches[1]);
            foreach ($urls as $url) {
                $update ++;
                $insert += News::addNews($url, 2);
                if ($insert_limit <= $insert) {
                    break 2;
                }
            }
        }

        return array($update, $insert);
    }

    public static function parse($body, $url)
    {
        if (strpos($body, '您要查看的網頁可能已被刪除、名稱已被更改，或者暫時不可用。')) {
            $ret = new STdClass;
            $ret->title = 404;
            $ret->body = 404;
            return $ret;
        }

        $doc = new DOMDocument;
        @$doc->loadHTML($body);

        $ret = new StdClass;
        // 標題在 div.divTitle
        $dom = Crawler::getDomByNameAndClass($doc, 'div', 'divTitle');
        if (!$dom) {
            throw new Exception("{$url} 應該要只有一個 div.divTitle");
        }
        $ret->title = trim($dom->nodeValue);
        // 內文在 #divNewsDetail
        $dom = $doc->getElementById('divNewsDetail');
        if (!$dom) {
            throw new Exception("{$url} 應該要只有一個 #divNewsDetail");
        }
        $ret->body = trim(Crawler::getTextFromDom($dom));
        return $ret;
    }


}
