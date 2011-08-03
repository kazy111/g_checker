<script type="text/javascript">
function enc(str)
{
  return Base64.toBase64(RawDeflate.deflate(EscapeUnicode(str)));
}
function openMultiview()
{
  var items = document.getElementsByName('view');
  var t = new Array();
  for(var i in items){
    if(items[i].checked) t.push(items[i].value);
  }
  hash = enc( ('['+t.join(',')+']').replace(/\'/g, '"') );
  window.open('http://kazy111.info/multiview/#'+hash);
}

</script>

<div class="main">

<h1 id="title">獄チェ</h1>

{include file="$file_path/themes/default/header_text.tpl"}

<div id="article">
<h2>お知らせ <a href="info.php" class="navi-triangle">&#x25B6;</a></h2>
<div id="article_list">
{$article_data}
</div>
</div>

<div class="option">
<div>ソート順: {$sort_data}</div>
<div>テーマ: {$theme_data}</div>
</div>

<br />

<div class="contents">

<div id="notice"></div>

<div class="list">
<table class="list-table">
<tbody>
<tr>
<th></th>
<th>Live</th>
<th>Name</th>
<th>Time</th>
<th>Topic</th>
<th>Chat</th>
<th>Persons</th>
<th>Archive</th>
<th>Wiki</th>
</tr>
{$streamer_online_data}
</tbody>
</table>
<input type="button" onclick="openMultiview()" value="多窓で開く">
</div>


<div class="old">
{$streamer_offline_data}
<div>


</div>


</div>
<br/>
{include file="$file_path/themes/default/footer_text.tpl"}

<script type="text/javascript" src="./js/base64.js"></script>
<script type="text/javascript" src="./js/rawdeflate.js"></script>
<script type="text/javascript" src="./js/ecl.js"></script>
