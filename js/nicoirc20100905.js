var server, port, ch, jus, ust;

function getQuery()
{
	var q = new Array();
	if(location.search){
		var query = location.search;
		query = query.substring(1,query.length);
		var querys = new Array();
		querys = query.split("&");
		for(i=0;i<querys.length;i++){
			var pram = new Array();
			pram = querys[i].split("=");
			var name = pram[0];
			var value = pram[1];
			q[name] = value;
		}
	}
	return q;
}

function showMovieMode(boxid, ust, jus, w, h, mode, quality)
{
  var box = document.getElementById(boxid);
  if(ust && ust!=''){
	var url = 'http://www.ustream.tv/flash/live/1/' + ust;

	box.innerHTML = '<object id="m'+boxid+'" width="'+w+'" height="'+h
	  + '" type="application/x-shockwave-flash" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">'
	  + '<embed id="e'+boxid+'" flashvars="autoplay=true&amp;brand=embed&amp;cid='+ust+'&amp;locale=ja_JP"'
	  + ' width="'+w+'" height="'+h+'" allowfullscreen="true" allowscriptaccess="always" wmode="'+mode+'"'
	  + ' quality="'+quality+'" src="http://www.ustream.tv/flash/live/1/'+ust+'?v3=1" hasPriority="true" type="application/x-shockwave-flash" />'
	  + '<param name="movie" value="' + url
	  + '"/><param name="flashvars" value="viewcount=true&amp;autoplay='
	  + 'true&amp;brand=embed&amp;locale=ja_JP"/>'
	  + '<param name="wmode" value="'+ mode +'"/>'
	  + '<param name="quality" value="'+quality+'"/>'
	  + '<param name="allowFullScreen" value="true"/>'
	  + '<param name="allowscriptaccess" value="always"/>'
	  + '<param name="hasPriority" value="true"/>'
	  + '</object>';

  }else{
    var Safari = /a/.__proto__=='//';
    box.innerHTML = '<div id="jtv_embed_holder_'+jus+'">'+
	'<object type="application/x-shockwave-flash" height="'+h+'" width="'+w+'"'
	    + 'id="m'+boxid+'" data="http://www.justin.tv/widgets/live_embed_player.swf?channel='
	    + jus + (false? '&amp;consumer_key=hQ3kBxqRXXBZJlPmu03JnA' : '') + '" bgcolor="#000000">'
	    + '<param name="allowFullScreen" value="true" />'
// if Safari, then allowScriptAccess
//	    + (Safari ? '<param name="allowScriptAccess" value="always" />' : '')
	    + '<param name="allowScriptAccess" value="always" />'
	    + '<param name="allownetworking" value="all" />'
	    + '<param name="wmode" value="'+ mode +'"/>'
	    + '<param name="quality" value="'+quality+'"/>'
	    + '<param name="movie" value="http://www.justin.tv/widgets/live_embed_player.swf" />'
	    + '<param name="hasPriority" value="true"/>'
	    + '<param name="flashvars" value="channel='+ jus +'&amp;auto_play='
	    + 'true&amp;start_volume=25'+  (false? '&amp;consumer_key=hQ3kBxqRXXBZJlPmu03JnA' : '') +'" />'
	    + '</object>'
	    + '</div>';

  }
}

function loadChat(boxid, ch, w, h)
{
  //alert(ch);
  var so = new SWFObject("chatview.swf?0.013", "streaming", w, h, "8", "#000000");
  so.addParam("flashvars", 's='+server+'&amp;p='+port+'&amp;ch=#'+ch + (prefix?'&amp;pre='+prefix:''));

  so.addParam("quality", "high");
  so.addParam("wmode", "transparent");
  so.write(boxid);
  var tmp = document.getElementById(boxid).childNodes[0];
  tmp.style.position = 'absolute';
}

function loadWebChatUstream(boxid, ustid, ch, w, h)
{
  var so = new SWFObject("http://www.ustream.tv/flash/irc.swf", "streaming", w, h, "8", "#000000");
  so.addParam("flashvars", 'channelId='+ustid+'&amp;brandId=1&amp;channel='+ch+'&amp;server=chat1.ustream.tv&amp;locale=ja_JP');
  so.addParam("quality", "high");
  //so.addParam("wmode", "normal");
  so.addParam("allowFullScreen", "true");
  so.addParam("allowScriptAccess", "always");
  so.write(boxid);
}
function loadWebChatMibbit(boxid, ch, w, h)
{
  ch = encodeURIComponent(ch);
  document.getElementById(boxid).innerHTML = '<iframe width="'+w+'" height="'+h
    +'" scrolling="No" style="border:0" src="http://widget.mibbit.com/?settings=c7b9cac76249db973f5bc61336ff8c65&amp;channel='+ch+'&amp;noServerTab=false&amp;nick=justin-%3F%3F%3F%3F%3F&amp;autoConnect=true&amp;delay=2"></iframe>'
}



function unloadChat(boxid)
{
  document.getElementById(boxid).innerHTML = '';
}
