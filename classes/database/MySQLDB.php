<?php

class MySQLDB {
 
  private $connect;
  private $result;
  private $Server;
  private $DbName;
  private $User;
  private $Password;

  function __construct(
    $Server='localhost',
    $DbName='',
    $User='',
    $Password='' ) {
    $this->Server = $Server;
    $this->DbName = $DbName;
    $this->User = $User;
    $this->Password = $Password;
    if ( !extension_loaded( "mysql" ) ) {
      dl("php_mysql.dll");
    }
    $this->open();
  }
  function open( ) {
    $this->connect = mysql_connect($this->Server,
                                   $this->User,
                                   $this->Password);
    if(!mysql_select_db($this->DBName)){
      // error
    }
    //$this->query_ex( "SET CLIENT_ENCODING TO 'SJIS'" );
  }
 
  function close( ) {
    mysql_close( $this->connect );
  }
 
  function query( $sql ) {
    $ret = mysql_query( $this->connect, $sql );
    return $ret;
  }

  function fetch( $result ) {
    return mysql_fetch_assoc( $result );
  }

  function free_result( $result ){
    mysql_free_result($result);
  }
  
  function execute( $SqlExec ) {
    $ret = mysql_query( $this->connect, $SqlExec );
    return $ret;
  }
  function begin( ) {
    mysql_query( $this->connect, 'start transaction' );
  }
  function commit( ) {
    mysql_query( $this->connect, 'commit' );
  }
  function rollback( ) {
    mysql_query( $this->connect, 'rollback' );
  }
  function sanitize($text){
    return mysql_escape_string($text);
  }

  function version( ) {
    $field = $this->query_ex( "SHOW SERVER_VERSION" );
    return $field["server_version"];
  }
  
  function query_ex( $sql = '' ) {
    $ret = NULL;
    if ( $sql != '' ) {
      $result = $this->query( $sql );
      if ( $result ) {
        $ret = $this->fetch ( $result );
      }
      $this->free_result($result);
    }
    return $ret;
  }
}
?>