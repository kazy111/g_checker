<div class="main">

<h1 id="title">{$site_title}</h1>

{include file="$file_path/themes/default/header_text.tpl"}

<div class="option">
<div>ソート順: {$sort_data}</div>
<div>テーマ: {$theme_data}</div>
</div>

<br />

<div class="contents">

<div class="online">
{$streamer_online_data}
</div>

<br />

<div class="offline">
{$streamer_offline_data}
</div>


{include file="$file_path/themes/default/footer_text.tpl"}

</div>
