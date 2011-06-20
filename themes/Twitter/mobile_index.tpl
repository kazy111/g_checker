<div class="main">

<div id="title"><img src="./themes/Twitter/title.gif" alt="{$site_title}"></div>




<div class="content-arrow"></div>


<div class="contents">

{include file="$file_path/themes/default/header_text.tpl"}

<div id="menu">

<div class="option">
<div>ソート順: {$sort_data}</div>
<div>テーマ: {$theme_data}</div>
</div>
</div>

<br />

<div id="article">
<div id="article_list">
{$article_data}
</div>
</div>

<div id="tweets">
<div id="notice"></div>

{$streamer_online_data}
{$streamer_offline_data}
</div>

<input id="continue" type="button" value="全て表示" onclick="var n=document.getElementById('tweets').childNodes; for(var i in n){ try{ n[i].className = n[i].className.replace('old',''); }catch(e){ ; } }" />

{include file="$file_path/themes/default/footer_text.tpl"}
</div>


</div>

