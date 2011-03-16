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

// トップページヘッダに表示する説明
$header_description = '<a href="http://www.gokusotsu.com/top/?cat=3">獄卒ch</a>関係な人の配信をチェック/視聴するページ ／ 獄卒チェッカー通知Twitter→<a href="http://twitter.com/g_checker">&psi;</a>';
// フッタに表示する説明
$footer_description = '意見、報告、要望は<a href="contact.php">問合せフォーム</a>か<a href="http://twitter.com/kazy111">じゃわてぃー</a>まで';

// indexで表示する、offlineの数 (onlineに加えて表示する数)
$list_extra_number = 5;

// 記事一覧等で1ページに表示する数
$page_size = 10;

// indexで常に一番上に表示する IDs
$ontop = array();
$ontop[] = 2;
$ontop[] = 60;
$ontop[] = 61;


/* twitter 投稿設定 */
// http://dev.twitter.com/apps あたりでアプリ作成して取得のこと
// Consumer key
$consumer_key = '';
// Consumer Secret
$consumer_secret = '';
$oauth_access_token = '';
$oauth_access_token_secret = '';

// 配信開始時につぶやくか
$tweet_start = FALSE;
// 配信終了時につぶやくか
$tweet_end   = FALSE;
// つぶやくときの末尾
$tweet_footer = ' #g_checker';


// timezone
date_default_timezone_set('Asia/Tokyo');

// 獄卒ch専用の処理を行うか
$gokusotsu = TRUE;

// デバッグフラグ
$debug = TRUE;

?>