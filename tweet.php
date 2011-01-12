<?php
// require twitterOAuth lib
require_once('classes/twitteroauth/twitteroauth.php');

function tweet($str)
{
  /* Consumer key from twitter */
  $consumer_key = 'pgVGp7hqF997D3Ur2wqWQ';
  /* Consumer Secret from twitter */
  $consumer_secret = 'kWQoNNJkCHBMN9QhzapfVRlk0PkgrDKHCM55GN63E';
  
  $oauth_access_token = '215249406-We3GO1GUsZFaqFQoO4uQrDkhv4a29DWkGELgvssx';
  $oauth_access_token_secret = 'gKxMtx4DR2GnEGu2MB9wK9abe7XbYmvWEHXW1WcfY0';
  
  /* Create TwitterOAuth with app key/secret and user access key/secret */
  $to = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_access_token, $oauth_access_token_secret);
  /* Run request on twitter API as user. */
  //$content = $to->OAuthRequest('https://twitter.com/account/verify_credentials.xml', array(), 'GET');
  print("\nTweet: ".$str." #g_checker\n");
  $content = $to->OAuthRequest('https://twitter.com/statuses/update.xml',
                               'POST', array('status' => $str));
  //$content = $to->OAuthRequest('https://twitter.com/statuses/replies.xml', array(), 'POST');
  print($content);
}

?>