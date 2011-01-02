<div class="main">

<div id="title">
<img src="themes/玲/title_amako1.png">
<img src="themes/玲/title_400.jpg" width="400" height="109">
<img src="themes/玲/title_amako2.png"></div>


{include file="$file_path/themes/default/header_text.tpl"}

<div id="article">
<h2>お知らせ</h2>
<div id="article_list">
{$article_data}
</div>
</div>


<div id="menu"></div>

<div class="option">
<div>ソート順: {$sort_data}</div>
<div>テーマ: {$theme_data}</div>
</div>

<br />

<div class="contents">

<div id="notice"></div>

 <ul id="tab">
  <li class="selected"><a href="#checker">チェッカー</a></li>
  <li><a href="#intro">はじめに</a></li>
  <li><a href="#bbs">BBS</a></li>
  <li><a href="#wiki">配信者Wiki</a></li>
  <li><a href="#gwiki">ゲーム関連Wiki</a></li>
  <li><a href="#broadcast">配信したい方</a></li>
  <li><a href="#contact">問合せ</a></li>
 </ul>


<div id="intro" style="display: none" class="tab_content">
<iframe src="http://www.gokusotsu.com/top/?cat=3" width="100%" height="500px" ></iframe>
</div>
<div id="bbs" style="display: none" class="tab_content">
<iframe src="http://yy.atbbs.jp/tenga18/" width="100%" height="500px" ></iframe>
</div>
<div id="wiki" style="display: none" class="tab_content">
<iframe src="http://www21.atwiki.jp/tenga18/" width="100%" height="500px" ></iframe>
</div>
<div id="gwiki" style="display: none" class="tab_content">
<iframe src="http://www36.atwiki.jp/tengame/" width="100%" height="500px" ></iframe>
</div>
<div id="broadcast" style="display: none" class="tab_content">
<iframe src="http://www.gokusotsu.com/top/?cat=5" width="100%" height="500px" ></iframe>
</div>
<div id="contact" style="display: none" class="tab_content">
<blockquote>
Twitterのじゃわてぃーまで気軽にDMを飛ばして下さい。
もしくはkazy@kazy111.infoまでメールを下さい。
</blockquote>
</div>

<div id="checker">

<table class="list-table">
<tbody id="list-table">
<tr>
<th>Name</th>
<th>Time</th>
<th>Topic</th>
<th>View/Chat</th>
<th>Archives</th>
<th>Chat</th>
<th>Log</th>
</tr>
{$streamer_online_data}
{$streamer_offline_data}
</tbody>
</table>

<input id="continue" type="button" value="全て表示" onclick="var n=document.getElementById('list-table').childNodes; for(var i in n){ try{ n[i].className = n[i].className.replace('old',''); }catch(e){ ; } }" />

</div>


</div>


</div>

{include file="$file_path/themes/default/footer_text.tpl"}

