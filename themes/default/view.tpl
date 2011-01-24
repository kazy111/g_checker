<script type="text/javascript">

var c_data = {$c_data};
var p_data = {$p_data};
var cur_pid;
var cur_cid;
var mboxid = 'movie';
var cboxid = 'chat';
var width, height;
var mode = 'normal';
var enable_chat = 1;

width = ReadCookie('w');  if(width == '')  width = 500;
height = ReadCookie('h'); if(height == '') height = 400;
enable_chat = ReadCookie('enable_chat');
if(enable_chat == '')
   enable_chat = 1;
else
  enable_chat = Number(enable_chat);

function WriteCookie(key, value, days) {
     var str = key + "=" + escape(value) + ";";
     if (days != 0) {
          var dt = new Date();
          dt.setDate(dt.getDate() + days);
          str += "expires=" + dt.toGMTString() + ";";
     }
     document.cookie = str;
}
function ReadCookie(key) {
     var sCookie = document.cookie;
     var aData = sCookie.split(";");
     var oExp = new RegExp(" ", "g");
     key = key.replace(oExp, "");

     var i = 0;
     while (aData[i]) {
          var aWord = aData[i].split("=");
          aWord[0] = aWord[0].replace(oExp, "");
          if (key == aWord[0]) return unescape(aWord[1]);
          if (++i >= aData.length) break;
     }
     return "";
}


function select(pid)
{
  var ust, jus, t = p_data[pid];
  if(cur_pid != pid){
    switch(t.type){
      case 0: ust = t.opt_id; jus = ''; break;
      case 1: ust = ''; jus = t.ch_id; break;
    }
    showMovieMode(mboxid, ust, jus, width, height, mode, 'high');
    //cur_pid = pid;
  }
  if(enable_chat && cur_cid != t.cid){
    var c = c_data[t.cid];
    var _w = width*0.75; if(_w<320)_w = 320;
    switch(c.type){
      case 0: loadWebChatUstream(cboxid, c.opt_id, c.room, _w, height); break;
      case 1: loadWebChatMibbit(cboxid, c.room, _w, height); break;
    }
  }
  cur_cid = t.cid;
}
function toggleChat()
{
  var n = document.getElementById(cboxid);
  if(enable_chat){
    n.innerHTML = '';
    enable_chat = 0;
  }else{
    var c = c_data[cur_cid];
    var _w = width*0.75; if(_w<320)_w = 320;
    switch(c.type){
      case 0: loadWebChatUstream(cboxid, c.opt_id, c.room, _w, height); break;
      case 1: loadWebChatMibbit(cboxid, c.room, _w, height); break;
    }
    enable_chat = 1;
  }
  WriteCookie('enable_chat', enable_chat, 90);
}

function mibbitChat()
{
  var c = c_data[cur_cid];
  loadWebChatMibbit(cboxid, c.room, width*0.75, height);
}

function resize(w, h)
{
  width = w; height = h;
  var t = document.getElementById('movie').childNodes[0];
  if(t){
    t.width = w; t.height = h;
    t = t.childNodes[0];
    if(t && (t.tagName == 'EMBED' || t.tagName == 'OBJECT')){ t.width = w; t.height = h; }
  }
  t = document.getElementById('chat');
  if(t){
    t.style.top = '-'+h;
    t = t.childNodes[0];
    var _w = width*0.75; if(_w<320)_w = 320;
    if(t){ t.width = _w; t.height = h;  }
  }
  WriteCookie('w', w, 90);
  WriteCookie('h', h, 90);
  
}

function add_select()
{
  var s = document.getElementById('select');
  var l = document.getElementById('link');
  for(var i in p_data){
    var n = document.createElement('span');
    var type = p_data[i].type == 0 ? 'UST' : 'JUS';
    n.innerHTML = '<a href="javascript:select('+p_data[i].id+')">'+type+': '+p_data[i].ch_id+'</a> ';
    s.appendChild(n);


    var n = document.createElement('span');
    if( p_data[i].type == 0 )
      n.innerHTML = '<a href="http://www.ustream.tv/channel/'+p_data[i].ch_id+'">Ust: '+p_data[i].ch_id+'</a> ';
    else
      n.innerHTML = '<a href="http://www.justin.tv/'+p_data[i].ch_id+'">Jus: '+p_data[i].ch_id+'</a> ';
    l.appendChild(n);
  }
}
function WriteCookie(key, value, days) {
     var str = key + "=" + escape(value) + ";";         // 書き出す値１ : key=value
     if (days != 0) {                                                 /* 日数 0 の時は省略 */
          var dt = new Date();                                   // 現在の日時
          dt.setDate(dt.getDate() + days);                   // days日後の日時
          str += "expires=" + dt.toGMTString() + ";"; // 書き出す値２ : 有効期限
     }
     document.cookie = str;                                   // Cookie に書き出し
}
function ReadCookie(key) {
     var sCookie = document.cookie;    // Cookie文字列
     var aData = sCookie.split(";");       // ";"で区切って"キー=値"の配列にする
     var oExp = new RegExp(" ", "g");   // すべての半角スペースを表す正規表現
     key = key.replace(oExp, "");          // 引数keyから半角スペースを除去

     var i = 0;
     while (aData[i]) {                           /* 語句ごとの処理 : マッチする要素を探す */
          var aWord = aData[i].split("=");                         // さらに"="で区切る
          aWord[0] = aWord[0].replace(oExp, "");              // 半角スペース除去
          if (key == aWord[0]) return unescape(aWord[1]); // マッチしたら値を返す
          if (++i >= aData.length) break;                          // 要素数を超えたら抜ける
     }
     return "";                                   // 見つからない時は空文字を返す
}
</script>

<div id="view">
<a href="http://kazy111.info/checker/">獄卒チェッカー</a> &gt;&gt; 
<span id="name">{$name}</span>
<span id="description">{$description}</span>

<div class="navigation" id="select">チャット: <a href="javascript:toggleChat(cboxid)">■</a>
&nbsp;<a href="javascript:mibbitChat(cboxid)">(退避用)</a>
 &nbsp;画面切り替え: </div>

<div class="no-wrap">
<div id="movie">
</div>

<div id="chat">
</div>

<br />

<div class="navigation" id="resize">
  サイズ変更: 
<a href="javascript:resize(375,300)">x0.75</a>
　<a href="javascript:resize(500,400)">x1</a>
　<a href="javascript:resize(625,500)">x1.25</a>
　<a href="javascript:resize(750,600)">x1.5</a>
</div>
</div>

<div class="navigation" id="link">
  本家: 
</div>


<div class="navigation">
  テーマ変更: {$theme_data}</div>

<script type="text/javascript">

add_select();
select({$first_id});
</script>

</div>
</body>
</html>
