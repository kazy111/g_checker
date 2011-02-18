<!DOCTYPE html>
<html lang="ja" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$site_title}</title>
<link rel="shortcut icon" href="http://kazy111.info/checker/favicon.ico" />
<link rel="stylesheet" type="text/css" href="http://kazy111.info/checker/css/checker.css" />
<script type="text/javascript" src="http://kazy111.info/checker/js/jquery-1.4.2.min.js"></script>
<meta http-equiv="content-script-type" content="text/javascript" /> 
<script type="text/javascript" src="http://kazy111.info/checker/js/swfobject.js"></script> 
<script type="text/javascript" src="http://kazy111.info/checker/js/nicoirc20100905.js"></script> 

<script type="text/javascript">

var p_data = {$p_data};
var cur_pid;
var mboxid = 'movie';
var mode = 'direct';


function select(pid)
{
  var ust, jus, t = p_data[pid];
  var width = $(window).width();
  var height = $(window).height()-16;
  if(cur_pid != pid){
    switch(t.type){
      case 0: ust = t.opt_id; jus = ''; break;
      case 1: ust = ''; jus = t.ch_id; break;
    }
    showMovieMode(mboxid, ust, jus, width, height, mode, 'high');
    //cur_pid = pid;
  }
}

function add_select()
{
  var s = document.getElementById('select');
  for(var i in p_data){
    var n = document.createElement('span');
    var type = p_data[i].type == 0 ? 'UST' : 'JUS';
    n.innerHTML = '<a href="javascript:select('+p_data[i].id+')">'+type+': '+p_data[i].ch_id+'</a> ';
    s.appendChild(n);
  }
}

$(document).ready(function() {
  var footmargin = 16;
  function resizeContainer(e) {
    var containerWidth = $(window).width();
    var containerHeight = $(window).height() - footmargin;
    //var containerHeight = document.documentElement.scrollHeight - footmargin;
    (function(jq) {
      for(var i = 0; i < jq.length; i++) {
        jq[i].css("width", containerWidth);
        jq[i].css("height", containerHeight);
        jq[i].css("overflow-y", "hidden");
        jq[i].css("overflow-x", "hidden");
      }
    })(new Array($("#mmovie"),$('#emovie'),$("#movie")));
  }
  $(window).bind("resize", resizeContainer);
  resizeContainer(null);
  resizeContainer(null);
});

</script>
<div id="view" style="width:100%;height:100%;"></div>
<div id="movie" style="width:100%;height:100%;padding:0;margin:0"></div>

<div id="select" style="margin: 0">画面切り替え: </div>

<script type="text/javascript">
add_select();
select({$first_id});
</script>

</body>
</html>
