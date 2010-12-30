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
