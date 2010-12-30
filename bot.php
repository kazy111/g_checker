<?php

/**
 * Simple PHP IRC Bot
 *
 * PHP Version 5
 *
 * LICENSE: This source file is subject to Creative Commons Attribution
 * 3.0 License that is available through the world-wide-web at the following URI:
 * http://creativecommons.org/licenses/by/3.0/.  Basically you are free to adapt 
 * and use this script commercially/non-commercially. My only requirement is that
 * you keep this header as an attribution to my work. Enjoy! 
 *
 * @category   Chat Room Scipt
 * @package    Simple PHP IRC Bot 
 * @author     Super3boy <admin@wildphp.com>
 * @copyright  2010, The Nystic Network
 * @license    http://creativecommons.org/licenses/by/3.0/
 * @link       http://wildphp.com (Visit for updated versions and more free scripts!)
 * @version    1.0.0 (Last updated 03-20-2010)
 *
 */

//So the bot doesnt stop.
//set_time_limit(0);
ini_set('display_errors', 'off');



/*
//Set your connection data.
$config = array( 
        'server' => 'example.com', 
        'port'   => 6667, 
        'channel' => '#channel',
        'name'   => 'real name', 
        'nick'   => 'user', 
        'pass'   => 'pass',
);
*/

class IRCBot {

        //This is going to hold our TCP/IP connection
        var $socket;

        //This is going to hold all of the messages both server and client
        var $ex = array();
        var $info = array();
        /*
        
         Construct item, opens the server connection, logs the bot in
         @param array

        */

        function __construct($config)

        {
                $this->socket = fsockopen($config['server'], $config['port']);
                $this->login($config);
                $this->main($config);
        }



        /*

         Logs the bot in on the server
         @param array

        */

        function login($config)
        {
                $this->send_data('USER', $config['nick'].' wildphp.com '.$config['nick'].' :'.$config['name']);
                $this->send_data('NICK', $config['nick']);
		$this->join_channel($config['channel']);
        }



        /*

         This is the workhorse function, grabs the data from the server and displays on the browser

        */

        function main($config)
        {             
                $data = fgets($this->socket, 256);
                
                //echo nl2br($data);

                flush();

                $this->ex = explode(' ', $data);

                if($this->ex[0] == 'PING')
                {
                        $this->send_data('PONG', $this->ex[1]); //Plays ping-pong with the server to stay connected.
                }

                //$command = str_replace(array(chr(10), chr(13)), '', $this->ex[3]);
          switch($this->ex[1]){
          case '322':
            //print 'ch: '.$this->ex[3].' mem: '.$this->ex[4].' topic: '.$this->ex[6];
            $this->info[$this->ex[3]] = array($this->ex[4], $this->ex[6]);
            break;
          case '323':
            return;
          case '376'
            $this->send_data('LIST');
            break;
          }
          

          $this->main($config);
        }


        function send_data($cmd, $msg = null) //displays stuff to the broswer and sends data to the server.
        {
                if($msg == null)
                {
                        fputs($this->socket, $cmd."\r\n");
                        echo '<strong>'.$cmd.'</strong><br />';
                } else {

                        fputs($this->socket, $cmd.' '.$msg."\r\n");
                        echo '<strong>'.$cmd.' '.$msg.'</strong><br />';
                }

        }


        function join_channel($channel) //Joins a channel, used in the join function.
        {

                if(is_array($channel))
                {
                        foreach($channel as $chan)
                        {
                                $this->send_data('JOIN', $chan);
                        }

                } else {
                        $this->send_data('JOIN', $channel);
                }
        }
}


?>
