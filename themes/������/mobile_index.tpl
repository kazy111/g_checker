<div class="main">

<h1 id="title">{$site_title}</h1>

{include file="$file_path/themes/default/header_text.tpl"}

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
<th></th>
<th>Name/Time</th>
<th>Topic</th>
<th>View/Chat</th>
</tr>
{$streamer_online_data}
</tbody>
</table>

{$streamer_offline_data}

</div>


</div>
