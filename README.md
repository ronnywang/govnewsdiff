GovNewsDiff
===========
本專案程式修改自 https://github.com/ronnywang/newsdiff 專案， [newsdiff](http://newsdiff.g0v.ronny.tw) 是針對台灣媒體追蹤修改記錄，以便備份媒體對新聞的刪除修改，而 GovNewsDiff 是為響應 g0v-hackath20n 的提案 [政權轉移／政策改變後被消失的那些資料](https://g0v.hackpad.com/--qMqNgMJFxDu) ，將針對新聞媒體的 newsdiff 改成針對政府公告的 govnewsdiff ，追蹤各政府網站的公告區塊並將公告記錄下來，以便在未來公告因特定原因被刪除時，仍能找回原先的公告。

專案成果在 http://govnewsdiff.g0v.ronny.tw/

如何參與此專案
==============
由於政府網站有數千個，難以一次性的全部記錄下來，因此 govnewsdiff 專案希望透過大家分工合作，一起來撰寫針對各政府網站的爬蟲，然後透過 govnewsdiff 統一抓取並記錄下來並定期產出打包檔，以供後續追蹤或是分析使用。
[TODO]這邊要加上如何增加新的爬蟲的說明文件

環境需求
========
1. PHP 5.4 以上版本
2. 資料庫可用 SQLite, MySQL, PostgreSQL
3. PHP curl, dom

初始環境建立
============
1. git clone https://github.com/ronnywang/govnewsdiff # 或者 fork 之後改用自己的 url
2. cd govnewsdiff/
3. cp webdata/config-sample.php webdata/config.php # 使用預設的設定檔，資料庫使用 SQLite
4. cd webdata/scripts/
4. mkdir dump/
5. wget -O dump/downloadgovnewsdiff.tar http://ronnywang-public.s3-website-ap-northeast-1.amazonaws.com/opendata/govnewsdiff/govnewsdiff.tar # 下載 govnewsdiff 打包檔
6. tar xvf dump/downloadgovnewsdiff.tar dump/ # 解壓縮打包檔
7. php import.php # 將打包檔匯入
8. cd ../../ # 回到根目錄
9. php -S 0:8080 index.php # 打開瀏覽器，瀏覽 http://localhost:8080/ 即可看到結果

專案授權
========
專案內程式碼以 BSD License 公開
* [pixframework](http://framework.pixnet.net) 使用 [BSD License](http://framework.pixnet.net/license/)
