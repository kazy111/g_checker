<tr class="streamer {$live}">
  <td class="name"><span class="popup"><a href="view.php?id={$sid}" target="_blank">{$name}{if $live_raw && $thumbnail != ''}<img class="thumb" src="{$thumbnail}" height="240" />{/if}</a></span>
    <span class="tags">{$tag}</span></td>
  <td class="time"><span class="time_diff">{$diff}</span> <span class="time_since">{$time}</span></td>
  <td class="topic"><span>{$topic}</span></td>
  <td class="viewer"><span>{$viewer}/{$member}</span></td>
  <td class="program"><span>
{foreach from=$program_raw item=p}
  {if $p[6] != ''}
   <a target="_blank" href="{$p[6]}">{$p[5]}</a>
  {/if}
{/foreach}
  </span></td>
  <td class="chat"><span>{$chat}</span></td>
  <td class="history"><a href="history.php?id={$sid}">&hearts;</a></td>
</tr>