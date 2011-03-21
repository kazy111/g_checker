<!DOCTYPE html>
<html lang="ja" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$site_title}</title>
<link rel="shortcut icon" href="http://kazy111.info/checker/favicon.ico" />
<link rel="stylesheet" type="text/css" href="http://kazy111.info/checker/css/checker.css" />
<meta http-equiv="content-script-type" content="text/javascript" />

<body>

<h2>タグ編集</h2>


<div class="message"><strong>{$message}</strong></div>

<div class="main">
, (半角カンマ)区切りで入力して下さい
<form method="POST">
  <input type="hidden" name="mode" value="1" />
  <input type="hidden" name="id" value="{$id}" />
  <input type="edit" name="tag" value="{$tag}" style="width: 30em" /><br />
  <input type="submit" value="登録" />
</form>

</div>

</body>
</html>
