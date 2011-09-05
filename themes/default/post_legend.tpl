<!DOCTYPE html>
<html lang="ja" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$site_title}</title>
<link rel="shortcut icon" href="{$relative_dir_to_top}/favicon.ico" />
<link rel="stylesheet" type="text/css" href="{$relative_dir_to_top}/css/checker.css" />
<meta http-equiv="content-script-type" content="text/javascript" />

<body>

<h2>伝説投稿</h2>


<div class="message"><strong>{$message}</strong></div>

<div class="main">
何らかの伝説をどうぞ <small>(例：ハマーのスイングでハリケーンが起きたことは有名)</small>
<form method="POST">
  <input type="hidden" name="mode" value="1" />
  <input type="edit" name="body" style="width: 35em" /><br />
  <input type="submit" value="投稿" />
</form>

</div>

</body>
</html>