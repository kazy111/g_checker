<?php

/* DB設定 */
// DBの種類 (0: MySQL, 1: PostgreSQL)
$db_type   = 1;
// ホスト名
$db_host   = 'localhost';
// ポート (NULLでデフォルト)
$db_port   = NULL;
// DB名
$db_name   = 'gokusotsu';
// ユーザ名
$db_user   = 'gokusotsu';
// パスワード
$db_passwd = 'Hise78-F';


/* サイト設定 */
// サイトタイトル
$site_title = '獄卒チェッカー';
// サイトトップのURL
$site_url = 'http://kazy111.info/checker';
// サーバ上での設置場所
$file_path = '/usr/home/kazy/public_html/checker/';
// メールフォームの送り先メールアドレス
$admin_mail = 'kazy@kazy111.info';


// indexで表示する、offlineの数 (onlineに加えて表示する数)
$list_extra_number = 5;

// 記事一覧等で1ページに表示する数
$page_size = 10;

// indexで常に一番上に表示する IDs
$ontop = array();
$ontop[] = 2;
$ontop[] = 60;
$ontop[] = 61;
// timezone
date_default_timezone_set('Asia/Tokyo');


/* twitter 投稿設定 */
// 配信開始時につぶやくか
$tweet_start = TRUE;
// 配信終了時につぶやくか
$tweet_end   = TRUE;

// 獄卒ch専用の処理を行うか
$gokusotsu = TRUE;

// デバッグフラグ
$debug = TRUE;

?>