<?php


function get_key($array, $key){
  return (array_key_exists($key, $array) ? $array[$key] : '');
}

function number_trim($str)
{
  preg_match('/^([0-9]+).*$/', $str, $match);
  return $match[1];
}

function validate_num($str)
{
  return preg_match('/^[0-9]+$/',$str);
}

// TODO set encoding by config
function sanitize_html($str)
{
  return htmlentities($str, ENT_QUOTES, 'UTF-8');
}

function sanitize_array($arr)
{
  foreach($arr as $k => $v){
    $arr[$k] = sanitize_html($v);
  }
  return $arr;
}

function is_old_mobile () {
  $useragents = array(
    'DoCoMo',         // docomo
    'J-Phone',        //
    'Vodafone',       //
    'SoftBank',       // softbank
    'UP.Browser',
    'KDDI'
  );
  $pattern = '/^('.implode('|', $useragents).')/';
  return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
}

function is_mobile () {
  $useragents = array(
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
    );
  $pattern = '/'.implode('|', $useragents).'/i';
  return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
}

function mb_trim( $string ) 
{ 
  $string = preg_replace( "/(^\s+)|(\s+$)/us", "", $string );
  return $string;
} 

function format_tag($str){
  $arr = explode(',', $str);
  $result = '';
  $cutlen = 20;
  foreach($arr as $v){
    if($v != NULL && $v != ''){
      //if(mb_strlen($v, 'UTF-8') > $cutlen)
      //  $v = mb_substr($v, 0, $cutlen-1, 'UTF-8').'c';
      $result .= '<span class="tag" rel="tag">'.mb_trim($v).'</span>';
    }
  }
  return $result;
}

?>
