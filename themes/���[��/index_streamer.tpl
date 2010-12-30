<div class="streamer {$live} {if $even}even{else}odd{/if}">
<table>
<tbody>
<tr>
 <td>
  <a href="view.php?id={$sid}" target="_blank">
  <span class="st_name">{$name}</span></a></td>
 <td>{if $live_raw}<span class="st_live">配信中</a>{/if}</td></tr>
<tr>
 <td>
  <a href="view.php?id={$sid}" target="_blank">
    <img class="thumbnail" src="{if $live_raw}{$thumbnail}{else}./themes/しーも/offline.png{/if}" width="160" height="120" />
  </a>
 </td>
 <td width="100%"><span class="st_topic">Topic: {$topic}</span><hr>{$time_diff}
  <div>{$viewer}/{$member}</div>
  <span>{$chat}</span>
  {if $twitter!=''}<span><a href="http://twitter.com/{$twitter}">Tw</a></span>{/if}
  {if $wiki!=''}<span><a href="http://www21.atwiki.jp/tenga18/{$wiki}.html">Wiki</a></span>{/if}
 </td>
</tr>
</tbody>
</table>
</div>
