<script type="text/javascript">

var chdata = new Array();
var chnames = new Array();
function dbadd(name)
{
  ch = chdata[name];
  setChannel(ch.name, ch.data.chat,
             (ch.data.ustch?ch.data.ustch:''), (ch.data.jstch?ch.data.jstch:''),
             (ch.data.ustid?ch.data.ustid:''), (ch.data.comment?ch.data.comment:''));
}

function compare_name(a, b)
{
  return (a.label > b.label) ? 1 : -1;
}

function cbinfo(info)
{
  for(var i = 0; i < info.length; i++){
    if(info[i]){
    chnames.push({ label: info[i].name, value: info[i].name + '|' + (info[i].data.yomigana ? info[i].data.yomigana:'') });
    chdata[info[i].name] = info[i];
    }
  }

  var tarr = new Array();
  var tstr = { };
  for(var i = 0; i < chnames.length; i++){
    var v = chnames[i].label;
    if(!(v in tstr)){
      tstr[v] = true;
      tarr.push(chnames[i]);
    }
  }
  chnames = tarr;
  chnames.sort(compare_name);

  $(function() {
    var availableTags = 
    $("#dbname").autocomplete({
      source: chnames,
      select: function(event, ui){
        gevent = event;
        var n = (event.srcElement ? event.srcElement.innerText : event.originalTarget.innerHTML);
        dbadd(n);
      }
    });
  });
}


function setChannel(name, room, ustch, jusch, ust_id, desc)
{

  $('input#name')[0].value = name;
  $('input#room')[0].value = room;
  $('input#ust_id')[0].value = ustch;
  $('input#jus_id')[0].value = jusch;
  $('input#ust_no')[0].value = ust_id;
  $('input#desc')[0].value = desc;
}

function addScript(url)
{
	var obj = document.createElement("script");
	obj.src = url;
	obj.type= 'text/javascript';
	obj.charset='utf-8';
	document.getElementsByTagName("head")[0].appendChild(obj)
}

addScript("http://wedata.net/databases/USW/items.json?callback=cbinfo");
</script>

<span class="message">{$message}</span>

<fieldset><legend>DBからチャンネル追加</legend>
<input type="text" id="dbname" value="" />
DBの編集は<a href="http://wedata.net/databases/USW/items">こちら</a>から、<a href="http://www.openid.ne.jp/">OpenID</a>があれば誰でもできます
</fieldset>

<form method="POST">
Name: <input type="text" name="name" id="name" /><br />
Chat type: {$ctype_html} <br />
Chat room: <input type="text" name="room" id="room" /><br />
Ustream Ch: <input type="text" name="ust_id" id="ust_id" /><br />
Justin Ch: <input type="text" name="jus_id" id="jus_id" /><br />
Ustream id: <input type="text" name="ust_no" id="ust_no" /><br />
Description: <input type="text" name="desc" id="desc" /><br />
<input type="hidden" name="mode" id="mode" value="1" />
<input type="submit" value="submit" />
</form>

