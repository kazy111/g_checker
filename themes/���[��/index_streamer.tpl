<div class="streamer {$live} {if $even}even{else}odd{/if}">
<table>
<tbody>
<tr>
 <td>
  <a href="view.php?id={$sid}" target="_blank">
  <span class="st_name">{$name}</span></a> <span class="tags">{$tag}</span></td>
 <td>{if $live_raw}<span class="st_live">配信中</a>{/if}</td></tr>
<tr>
 <td>
  <a href="view.php?id={$sid}" target="_blank">
    <img class="thumbnail" src="{if $live_raw && $thumbnail != ''}{$thumbnail}{else}./themes/しーも/offline.png{/if}" width="160" height="120" />
  </a>
 </td>
 <td width="100%"><span class="st_topic">Topic: {$topic}</span><hr> {if $live_raw}{$diff}{else}{$time}{/if}

  <div>{$viewer}/{$member}</div>
  <span>{$chat}</span>
  {if $twitter!=''}<span><a href="http://twitter.com/{$twitter}">Tw</a></span>{/if}
  {if $wiki!=''}<span><a href="{$wiki_url}{$wiki}.html">Wiki</a></span>{/if}
 </td>
</tr>
</tbody>
</table>
</div>
