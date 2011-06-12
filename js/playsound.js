var arr = new Array('welcome', 'theme', 'theme2', 'y1', 'y2', 'y3', 'g1');


var playsound = ReadCookie('playsound');

if (document.createElement && playsound != 'false') window.onload = function() {
  var ele = document.createElement('div');
  var id = '_playsound';
  ele.id = id;
  document.body.appendChild(ele);
  swfobject.embedSWF('js/playmp3.swf?url=media/'+arr[Math.floor(Math.random()*arr.length)]+'.mp3', id, 1, 1, '8.0.0');

  //var play = document.getElementById('_playsound');
  //play.playFile('media/welcome.mp3')
}
