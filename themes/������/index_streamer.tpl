<tr class="streamer {$live}">
  <td>
    <input type="checkbox" name="view" value="{$multi_data}">
  </td>
  <td class="live">●</td>
  <td class="name"><span><a href="view.php?id={$sid}" target="_blank">{$name}</a></span></td>
  <td class="time"><span class="time_diff">{$diff}</span> <span class="time_since">{$time}</span></td>
  <td class="topic"><span>{$topic}</span></td>
  <td class="chat"><span>{$chat}</span></td>
  <td class="viewer"><span>{$viewer}/{$member}</span></td>
  <td class="program">
{foreach from=$program_raw item=p}
  {if $p[0] == 0}
   <a href="http://lonsdaleite.jp/uarchives/?channel={$p[2]}">Ust</a>
  {else}{if $p[0] == 1}
   <a target="_blank" href="http://lonsdaleite.jp/jarchives/?channel={$p[1]}">Jus</a>
  {/if}{/if}
{/foreach}
  </td>
  <td class="wiki">{if $wiki != ''}<a href="http://www21.atwiki.jp/tenga18/pages/{$wiki}.html">■</a>{/if}
  </td>
</tr>