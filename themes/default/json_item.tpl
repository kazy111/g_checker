{
"name":"{$name}",
"id":{$sid},
"live":"{$live}",
"thumbnail":"{$thumbnail}",
"start":{$start_raw},
"end":{$end_raw},
"diff":"{$diff}",
"time":"{$time}",
"topic":"{$topic}",
"viewer":{$viewer},
"member":{$member},
"chat":"{$chat}",
"wiki":"{$wiki}",
"twitter":"{$twitter}",
"archives":[
{foreach from=$program_raw item=p}
  {if $p[0] == 0}
    { "type": {$p[0]}, "url": "http://lonsdaleite.jp/uarchives/?channel={$p[2]}" },
  {else}
    { "type": {$p[0]}, "url": "http://lonsdaleite.jp/jarchives/?channel={$p[1]}" },
  {/if}
{/foreach}
    { "type": -1, "url": "" }
]
}