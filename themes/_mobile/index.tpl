<div class="main">

<h1 id="title">{$site_title}</h1>

{if $page == 0}
<div id="article">
<h2>お知らせ</h2>
<div id="article_list">
{$article_data}
</div>
</div>
<hr/>
{/if}


{if $page != 1}{$page}ページ目{/if}
<div>
[0]<a href="./" accesskey="0">トップ</a><br>
{if $page > 1}
[1]<a href="./?p={$page-1}" accesskey="1">前</a>
{/if}
[2]<a href="./?p={$page}&{$random}" accesskey="2">再読込</a>
{if $page < $page_num}
[3]<a href="./?p={$page+1}" accesskey="3">次</a>
{/if}
</div><br>

<div class="contents">

{$streamer_online_data}
{$streamer_offline_data}

</div>

<div>
[0]<a href="./" accesskey="0">トップ</a><br>
{if $page > 1}
[1]<a href="./?p={$page-1}" accesskey="1">前</a>
{/if}
[2]<a href="./?p={$page}&{$random}" accesskey="2">再読込</a>
{if $page < $page_num}
[3]<a href="./?p={$page+1}" accesskey="3">次</a>
{/if}
</div>
{if $page != 1}{$page}ページ目{/if}
<br>
{if $page == 1}
<div class="link">
{$link_data}
</div>
{/if}

</div>
