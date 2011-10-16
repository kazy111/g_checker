<script type="text/javascript">

var c_data = {$c_data};
var p_data = {$p_data};
var cur_pid;
var cur_cid;
var mboxid = 'movie';
var cboxid = 'chat';
var width, height;
var mode = 'direct';
var enable_chat = 1;
var min_chat_width = 380;
var layout = parseInt(ReadCookie('l'));  if(layout == '')  layout = 0;

width = ReadCookie('w');  if(width == '')  width = 512;
height = ReadCookie('h'); if(height == '') height = 385;
// ustream bar size = 25
size = [ [375,295,'x0.75' ], [512,385,'x1.00'], [640,505,'x1.25'], [720,565,'x1.50'], [840,655,'x1.75'], [1024,745,'x2.00'],
         [384,241,'x0.75 wide' ], [512,313,'x1.00 wide'], [640,385,'x1.25 wide'], [768,457,'x1.50 wide'], [896,529,'x1.75 wide'], [1024,601,'x2.00 wide'],
         [480, 82, '17:2']];

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
  var id, t = p_data[pid];
  //if(cur_pid != pid){
  if(true){
    switch(t.type){
      case 0: id = t.opt_id; break;
      case 1: id = t.ch_id; break;
      case 2: id = t.ch_id; break;
      case 5: id = t.opt_id; break;
    }
    $('.sw').removeClass('sw-select');
    $('#sw-'+pid).addClass('sw-select');
    showMovieMode(mboxid, t.type, id, width, height, mode, 'high');
    cur_pid = pid;
  }
  if(enable_chat && cur_cid != t.cid){
    var c = c_data[t.cid];
    var _w = getChatWidth(), _h = getChatHeight();

    switch(c.type){
      case 0: loadWebChatUstream(cboxid, c.opt_id, c.room, _w, _h); break;
      case 1: loadWebChatMibbit(cboxid, c.room, _w, _h); break;
      case 2: socialStream(); break;
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
    var _w = getChatWidth(), _h = getChatHeight();
    switch(c.type){
      case 0: loadWebChatUstream(cboxid, c.opt_id, c.room, _w, _h); break;
      case 1: loadWebChatMibbit(cboxid, c.room, _w, _h); break;
      case 2: socialStream(); break;
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

function socialStream()
{
  var p = p_data[cur_pid];
  if(!p.opt_id){
    for(var i in p_data){
      if(p_data[i].opt_id){
        p = p_data[i];
        break;
      }
    }
  }
  if(p.opt_id) loadSocialStream(cboxid, p.opt_id, width*0.75, height);
}

function resize_select(str)
{
  var x = str.split(',');
  resize(x[0], x[1]);
}


function getChatWidth() {
  var _w;
  switch(layout) {
    case 1:
      _w = width;  break;
    default:
      _w = height*0.75;
      if(_w < min_chat_width)_w = min_chat_width;
  }
  return _w;
}
function getChatHeight() {
  var _h;
  switch(layout) {
    case 1:
      _h = height*0.7;  break;
    default:
      _h = height;
  }
  return _h;
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
    if( (t.tagName != 'EMBED' && t.tagName != 'OBJECT')){ t = t.childNodes[0]; }

    t.width = getChatWidth(); t.height = getChatHeight();
  }
  WriteCookie('w', w, 90);
  WriteCookie('h', h, 90);
  
}

function add_select()
{
  var s = document.getElementById('select');
  var l = document.getElementById('link');
  for(var i in p_data){

    // ニコ生、Twitcastingは画面切り替えしない
    if(p_data[i].type != 3 && p_data[i].type != 4){

      var n = document.createElement('span');
      n.id = 'sw-'+p_data[i].id;
      n.className = 'sw';
      n.innerHTML = '<a href="javascript:select('+p_data[i].id+')">'+p_data[i].typename+': '+p_data[i].ch_id+'</a> ';
      s.appendChild(n);
    }

    // add link
    var n = document.createElement('span');
    n.innerHTML = p_data[i].org;
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
function setLayout(layout) {
  switch(layout) {
    case 1:
      $('#wrapper').css('white-space', 'wrap');
      $('#chat').css('clear', 'both');
      break;
    default:
      $('#wrapper').css('white-space', 'nowrap');
      $('#chat').css('clear', 'none');
      break;
  }
}
function toggleLayout() {
  layout ^= 1;
  setLayout(layout);
  resize(width, height);
  WriteCookie('l', layout, 90);
}
</script>

<div id="view">
<a href="{$relative_dir_to_top}/">{$site_title}</a> &gt;&gt; 
<span id="name">{if $url != ''}<a href="{$url}" target="_blank">{$name}</a>{else}{if $wiki}<a href="{$wiki_url}{$wiki}.html" target="_blank">{$name}</a>{else}{$name}{/if}{/if}</span>
<span id="description">{$description}</span>

<div class="navigation" id="select">
 <strong>チャット:</strong> <a href="javascript:toggleChat(cboxid)">■</a>
 <a href="javascript:toggleLayout()">縦横</a>
 &nbsp;<a href="javascript:mibbitChat(cboxid)">(退避用)</a>
 &nbsp;<a href="javascript:socialStream(cboxid)">(Twitter)</a>
 &nbsp;&nbsp;<strong>画面切り替え:</strong>
 <a onclick="javascript:window.open('view_pop.php?id={$id}', null, 'width=512,height=385,menubar=no,toolbar=no,resizable=yes');">Pop</a>
</div>

<div class="tags">
 {$tag}
 <a onclick="javascript:window.open('edit_tag.php?id={$id}', null, 'width=512,height=150,menubar=no,toolbar=no,resizable=yes');">タグ編集</a>
</div>

<div id="wrapper">
<div id="movie">
</div>

<div id="chat">
</div>

<br />

<div class="navigation" id="resize">
  サイズ変更:
<select id="resize_select" onchange="resize_select(this.options[this.selectedIndex].value)">
</select>

</div>

<div class="navigation" id="link">
  本家: 
</div>


<div class="navigation">
  テーマ変更: {$theme_data}</div>

<script type="text/javascript">

var sel  = document.getElementById('resize_select');
for(var i = 0; i < size.length; i++){
  var o = document.createElement('option');
  var s = size[i];
  o.value = s[0]+','+s[1];
  o.label = s[2];
  o.innerHTML = s[2];
  if(width == s[0] && height == s[1]) o.selected = 'selected';
  sel.appendChild(o);
}

add_select();
setLayout(layout);
select({$first_id});
</script>

</div>
