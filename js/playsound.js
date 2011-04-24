
if (document.createElement) window.onload = function() {
  var ele = document.createElement('div');
  var id = '_playsound';
  ele.id = id;
  document.body.appendChild(ele);
  swfobject.embedSWF('js/playmp3.swf?url=media/welcome.mp3', id, 1, 1, '8.0.0');

  //var play = document.getElementById('_playsound');
  //play.playFile('media/welcome.mp3')
}
