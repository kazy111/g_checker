
<div class="main">

<h1 id="title">ゼットンチェッカー</h1>

<summary>
<div id="summary">
ゼットンな人の配信をチェック/視聴するページ ／ ゼットンチェッカー通知Twitter→<a href="http://twitter.com/g_checker">&psi;</a>
</div>
</summary>

<div id="article">
<h2>お知らせ <a href="info.php" class="navi-triangle">&#x25B6;</a></h2>
<div id="article_list">
{$article_data}
</div>
</div>


<div id="menu">

<div class="option">
<div>ソート順: {$sort_data}</div>
<div>テーマ: {$theme_data}</div>
</div>
</div>

<br />

<div class="contents">

<div id="notice"></div>

<table class="list-table">
<tbody id="list-table">
<tr>
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
</div>

<input id="continue" type="button" value="全て表示" onclick="var n=document.getElementById('list-table').childNodes; for(var i in n){ try{ n[i].className = n[i].className.replace('old',''); }catch(e){ ; } }" />

{include file="$file_path/themes/default/footer_text.tpl"}

</div>
