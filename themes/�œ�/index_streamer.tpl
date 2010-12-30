<div class="streamer {$live}">
  {if $live_raw}
  <a href="view.php?id={$sid}" target="_blank">
    <img class="thumbnail" src="{$thumbnail}" width="320" height="240" />
  </a>
  <div class="time"><span class="time_diff">{$diff}</span> <span class="time_since">{$time}</span></div>
  {else}
  <a href="view.php?id={$sid}" target="_blank">●</a>
  {/if}
</div>
