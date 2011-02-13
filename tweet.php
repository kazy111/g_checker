<?php
// require twitterOAuth lib
require_once('classes/twitteroauth/twitteroauth.php');
include_once('config.php');

function tweet($str)
{
  /* Create TwitterOAuth with app key/secret and user access key/secret */
  $to = new TwitterOAuth($GLOBALS['consumer_key'], $$GLOBALS['consumer_secret'], $$GLOBALS['oauth_access_token'], $$GLOBALS['oauth_access_token_secret']);
  /* Run request on twitter API as user. */
  //$content = $to->OAuthRequest('https://twitter.com/account/verify_credentials.xml', array(), 'GET');
  print("\nTweet: ".$str.$$GLOBALS['tweet_footer']."\n");
  $content = $to->OAuthRequest('https://twitter.com/statuses/update.xml',
                               'POST', array('status' => $str));
  //$content = $to->OAuthRequest('https://twitter.com/statuses/replies.xml', array(), 'POST');
  print($content);
}

?>