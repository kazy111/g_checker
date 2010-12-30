function is_mobile () {
  var useragents = [
    'iPhone',         // Apple iPhone
    'iPod',           // Apple iPod touch
    'Android',        // 1.5+ Android
    'dream',          // Pre 1.5 Android
    'CUPCAKE',        // 1.5+ Android
    'blackberry9500', // Storm
    'blackberry9530', // Storm
    'blackberry9520', // Storm v2
    'blackberry9550', // Storm v2
    'blackberry9800', // Torch
    'webOS',          // Palm Pre Experimental
    'incognito',      // Other iPhone browser
    'webmate'         // Other iPhone browser
  ];
  var pattern = new RegExp(useragents.join('|'), 'i');
  return pattern.test(navigator.userAgent);
}
