
{if $live_raw}<font color="red">Live!</font>{/if}
<font color="blue"><b>{$name}</b></font><br>
<span class="time_since">{$time}</span>
<br>
<small>{$topic}</small>
<br>
<span>{$viewer}/{$member}</span>
{if $live_raw && $thumbnail != ''}<br><a class="thumb" href="{$thumbnail}">サムネイル</a>{/if}
<hr>
