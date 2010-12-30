<div class="main">

<h1 id="title">GOKUSOTSU CHECKER</h1>

<summary>
<a href="http://www.gokusotsu.com/top/?cat=3">獄卒ch</a>関係な人の配信をチェック/視聴するページ
 ／ 獄卒チェッカー通知Twitter→<a href="http://twitter.com/g_checker">&psi;</a>
</summary>

<div id="menu"></div>
<div class="option">
<div>ソート順: {$sort_data}</div>
<div>テーマ: {$theme_data}</div>
</div>

<br />

<div class="contents">

<table class="list-table">
<tbody id="list-table">
<tr>
<th>Name/Time</th>
<th>Topic</th>
<th>View/Chat</th>
</tr>
{$streamer_online_data}
{$streamer_offline_data}
</tbody>
</table>
<input id="continue" type="button" value="全て表示" onclick="var n=document.getElementById('list-table').childNodes; for(var i in n){ try{ n[i].className = n[i].className.replace('old',''); }catch(e){ ; } }" />

</div>
<div class="link">
{$link_data}
</div>


</div>
