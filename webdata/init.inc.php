<?php

error_reporting(E_ALL ^ E_STRICT ^ E_NOTICE);

include(__DIR__ . '/stdlibs/pixframework/Pix/Loader.php');
set_include_path(__DIR__ . '/stdlibs/pixframework/'
    . PATH_SEPARATOR . __DIR__ . '/models'
    . PATH_SEPARATOR . __DIR__ . '/stdlibs/Dropbox-master/'
);
require_once(__DIR__ . '/stdlibs/diff_match_patch-php-master/diff_match_patch.php');

Pix_Loader::registerAutoLoad();

if (file_exists(__DIR__ . '/config.php')) {
    include(__DIR__ . '/config.php');
}
// TODO: 之後要搭配 geoip
date_default_timezone_set('Asia/Taipei');
mb_internal_encoding("UTF-8");

if (!getenv('DATABASE_URL')) {
    die('need DATABASE_URL');
}
Pix_Table_Db::addDbFromURI(getenv('DATABASE_URL'));
