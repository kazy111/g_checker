<tr class="streamer {$live}">
  <td>
    <input type="checkbox" name="view" value="{$multi_data}">
  </td>
  <td class="name"><span class="popup"><a href="view.php?id={$sid}" target="_blank">{$name}
{if $live_raw}<img class="thumb" src="{$thumbnail}" width="320" height="240" />{/if}</a></span></td>
  <td class="time"><span class="time_diff">{$diff}</span> <span class="time_since">{$time}</span></td>
  <td class="topic"><span>{$topic}</span></td>
  <td class="viewer"><span>{$viewer}/{$member}</span></td>
  <td class="program"><span>
{foreach from=$program_raw item=p}
  {if $p[0] == 0}
   <a target="_blank" href="http://lonsdaleite.jp/uarchives/?channel={$p[2]}">Ust</a>
  {else}
   <a target="_blank" href="http://lonsdaleite.jp/jarchives/?channel={$p[1]}">Jus</a>
  {/if}
{/foreach}
  </span></td>
  <td class="chat"><span>{$chat}</span></td>
  <td class="history"><a href="history.php?id={$sid}">&hearts;</a></td>
</tr>