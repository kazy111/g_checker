<?php

/* DB設定 */
// DBの種類 (0: MySQL, 1: PostgreSQL)
$db_type   = 0;
// ホスト名
$db_host   = 'localhost';
// ポート (NULLでデフォルト)
$db_port   = NULL;
// DB名
$db_name   = 'gokusotsu';
// ユーザ名
$db_user   = 'gokusotsu';
// パスワード
$db_passwd = '';


/* サイト設定 */
// サイトタイトル
$site_title = '獄卒チェッカー';
// サイトトップのURL
$site_url = 'http://kazy111.info/checker';
// サーバ上での設置場所
$file_path = '/usr/home/kazy/public_html/checker/';
// メールフォームの送り先メールアドレス
$admin_mail = 'kazy@kazy111.info';
// サイトキーワード (検索エンジン用)
$keywords = 'ゲーム配信';

// トップページヘッダに表示する説明
$header_description = '<a href="http://www.gokusotsu.com/top/?cat=3">獄卒ch</a>関係な人の配信をチェック/視聴するページ ／ 獄卒チェッカー通知Twitter→<a href="http://twitter.com/g_checker">&psi;</a>';
// フッタに表示する説明
$footer_description = '意見、報告、要望は<a href="contact.php">問合せフォーム</a>か<a href="http://twitter.com/kazy111">じゃわてぃー</a>まで';
// 最初に表示するテーマ
$default_theme='default';
// トップページでのデフォルトのソート順 viewer, name, time, random
$default_sort='random';

// indexで表示する、offlineの数 (onlineに加えて表示する数)
$list_extra_number = 5;

// 記事一覧等で1ページに表示する数
$page_size = 10;

// indexで常に一番上に表示する IDs
$ontop = array();
$ontop[] = 2;
$ontop[] = 60;
$ontop[] = 61;

// 限定キーワード (|区切)
//   これがトピック、タイトルに含まれていると、indexで上位配置、配信通知する対象にする)
$limit_keywords = 'アイマス|アイドルマスター|ライブフォーユー|L4U|Ｌ４Ｕ|ステージフォーユー|S4U|Ｓ４Ｕ|グラビアフォーユー|G4U|Ｇ４Ｕ|プロデュース|写真撮影';


// wikiリンク用URL (このURL + wikiの値.html にリンクされます)
$wiki_url = '';

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


/* ニコ生設定 */
// ログインID (配信チェックに必須)
$nico_loginid = '';
// ログインパスワード (配信チェックに必須)
$nico_loginpw = '';


// timezone
date_default_timezone_set('Asia/Tokyo');

// 獄卒ch専用の処理を行うか
$gokusotsu = TRUE;

// デバッグフラグ
$debug = TRUE;

?>