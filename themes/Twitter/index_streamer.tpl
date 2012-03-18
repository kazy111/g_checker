<div class="streamer {$live}">
  <span class="tweet-img">
  <a href="view.php?id={$sid}" target="_blank">
    {if $live_raw && $thumbnail != ''}
    <img class="thumb" src="{$thumbnail}" width="80" />
    {else}
      {if $twitter != ''}
    <img class="thumb" src="http://gadgtwit.appspot.com/twicon/{$twitter}/bigger" width="73" height="73" />
    <span class="thumb_offline">offline</span>
      {else}
    <img class="thumb" src="./themes/Twitter/offline.png" width="80" />
      {/if}
    {/if}
  </a>
  </span>

  <span class="tweet-body">
  <span class="name">
    <a href="view.php?id={$sid}" target="_blank">
   {$name}</a></span>
  <span class="topic">{$topic}</span>

  <span class="meta">
  <span class="time">
    <a href="history.php?id={$sid}">
    <span class="time_diff">{$diff}</span> <span class="time_since">{$time}</span>
    </a>
  </span>

  <span class="viewer"><span>{$viewer} viewer / {$member} chat</span></span>
  </span><!-- end meta -->

  <span class="tags">{$tag}</span>

  <span class="tweet-menu">
  {if $url != ''}URL:
  <a target="_blank" href="{$url}">&psi;</a> / 
  {/if}
  <span class="program">Archive:
{foreach from=$program_raw item=p}
  {if $p[6] != ''}
   <a target="_blank" href="{$p[6]}">{$p[5]}</a>
  {/if}
{/foreach}
  </span> / 
  <span class="chat">IRC: {$chat}</span>
  </span>

  </span>
</div>