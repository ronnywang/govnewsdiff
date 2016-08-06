<?php

class Crawler_EyNews
{
    public static function crawl($insert_limit)
    {
        for ($page = 63; $page <= 449; $page ++) {
            error_log($page);
            $url = "http://www.ey.gov.tw/Ey_News.aspx?n=DC478855B8ECCFBC&page={$page}&PageSize=20";
            $content = Crawler::getBody($url);
            preg_match_all('#"(Video_Content|News_Content2)\.aspx?[^"]*"#', $content, $matches);
            $urls = array_map(function($u) { return 'http://www.ey.gov.tw/' . htmlspecialchars_decode(trim($u, '"')); }, $matches[0]);
            foreach ($urls as $url) {
                News::addNews($url, 1);
            }
        }

        $insert = $update = 0;
        foreach ($matches[0] as $link) {
            $url = Crawler::standardURL('http://www.appledaily.com.tw' . $link);
            $update ++;
            if ($insert_limit <= $insert) {
                break;
            }
        }

        return array($update, $insert);
    }

    public static function parse($body, $url)
    {
        $doc = new DOMDocument;
        @$doc->loadHTML($body);

        if (strpos($url, 'Video_Content.aspx')) { // 影音新聞
            $ret = new StdClass;
            // 標題在 <h4>
            $h4_doms = $doc->getElementsByTagName('h4');
            if ($h4_doms->length != 1) {
                throw new Exception("{$url} 應該要只有一個 <h4>");
            }
            $ret->title = $h4_doms->item(0)->nodeValue;

            // 全文在 div.mv_view_box
            $box_doms = Crawler::getDomByNameAndClass($doc, 'div', 'mv_view_box');
            if (!$box_doms) {
                throw new Exception("{$url} 應該要只有一個 div.mv_view_box");
            }
            $ret->body = trim(Crawler::getTextFromDom($box_doms));
            return $ret;
        } elseif (strpos($url, 'News_Content2.aspx')) {
            $ret = new StdClass;
            // 標題在 div.data_midlle_news_box01 下的 dt
            $box_dom = Crawler::getDomByNameAndClass($doc, 'div', 'data_midlle_news_box01');
            if (!$box_dom) {
                throw new Exception("{$url} 應該要有一個 div.data_midlle_news_box01");
            }
            if ($box_dom->getElementsByTagName('dt')->length != 1) {
                throw new Exception("{$url} 應該要有一個 div.data_midlle_news_box01 dt");
            }
            $ret->title = $box_dom->getElementsByTagName('dt')->item(0)->nodeValue;

            // 內文在 div.data_midlle_news_box02 但也要把  div.data_midlle_news_box01 的 dd 列進來
            if ($box_dom->getElementsByTagName('dd')->length != 1) {
                throw new Exception("{$url} 應該要有一個 div.data_midlle_news_box01 dd");
            }
            $ret->body = $box_dom->getElementsByTagName('dd')->item(0)->nodeValue;
            $box_dom = Crawler::getDomByNameAndClass($doc, 'div', 'data_midlle_news_box02');
            if (!$box_dom) {
                throw new Exception("{$url} 應該要有一個 div.data_midlle_news_box02");
            }
            $ret->body .= "\n" . trim(Crawler::getTextFromDom($box_dom));
            return $ret;
        } else {
            throw new Exception("未知的種類: {$url}");
        }

        throw new Exception("未知的種類: {$url}");
    }


}
