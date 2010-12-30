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

<h1 id="title">{$site_title}</h1>

<summary>
<a href="http://www.gokusotsu.com/top/?cat=3">獄卒ch</a>関係な人の配信をチェック/視聴するページ
 ／ 獄卒チェッカー通知Twitter→<a href="http://twitter.com/g_checker">&psi;</a>
</summary>

<div id="menu"></div>

<div id="article">
<h2>お知らせ</h2>
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
<form id="form">

<table class="list-table">
<tbody id="list-table">
<tr>
<th></th>
<th>Name</th>
<th>Time</th>
<th>Topic</th>
<th>View/Chat</th>
<th>Archives</th>
<th>Chat</th>
<th>Log</th>
</tr>
{$streamer_online_data}
{$streamer_offline_data}
</tbody>
</table>


<input type="button" onclick="openMultiview()" value="多窓で開く">
</form>
</div>


<input id="continue" type="button" value="全て表示" onclick="var n=document.getElementById('list-table').childNodes; for(var i in n){ try{ n[i].className = n[i].className.replace('old',''); }catch(e){ ; } }" />


</div>

<script type="text/javascript" src="./js/base64.js"></script>
<script type="text/javascript" src="./js/rawdeflate.js"></script>
<script type="text/javascript" src="./js/ecl.js"></script>
