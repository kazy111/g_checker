<tr class="streamer {$live}">
  <td class="name time"><span class="popup"><a href="view.php?id={$sid}" target="_blank">ゼットン{if $live_raw && $thumbnail != ''}<img class="thumb" src="{$thumbnail}" height="240" />{/if}</a></span>
<span class="tags">{$tag}</span>
<br /><span class="time_diff">{$diff}</span> <span class="time_since">{$time}</span></td>
  <td class="topic"><span>{$topic}</span></td>
  <td class="viewer"><span>{$viewer}/{$member}</span></td>
</tr>