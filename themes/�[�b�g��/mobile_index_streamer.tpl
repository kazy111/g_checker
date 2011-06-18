<tr class="streamer {$live}">
  <td class="name time"><span class="popup"><a href="view.php?id={$sid}" target="_blank">ゼットン{if $live_raw}<img class="thumb" src="{$thumbnail}" width="320" height="240" />{/if}</a></span>
<span class="tags">{$tag}</span>
<br /><span class="time_diff">{$diff}</span> <span class="time_since">{$time}</span>
<!--{foreach from=$program_raw item=p}
  {if $p[0] == 0}
   <a href="ust://{$p[2]}">Ust</a>
  {else}
   <a href="jtv://{$p[1]}">Jus</a>
  {/if}
{/foreach}-->
</td>
  <td class="topic"><span>{$topic}</span></td>
  <td class="viewer"><span>{$viewer}/{$member}</span></td>
</tr>