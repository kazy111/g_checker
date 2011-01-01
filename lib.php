<?php

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

?>
