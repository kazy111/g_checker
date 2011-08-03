<div class="main">

<h1 id="title">{$site_title}</h1>

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

<br>

<div class="contents">

<div id="notice"></div>
<hr>
<div id="online">
{$streamer_online_data}
</div>

<div id="offline">
{$streamer_offline_data}
</div>

</div>
<br>
<input id="continue" type="button" value="全て表示" onclick="var n=document.getElementById('offline').childNodes; for(var i in n){ try{ n[i].className = n[i].className.replace('old',''); }catch(e){ ; } }" />


{include file="$file_path/themes/default/footer_text.tpl"}

</div>
