<?php

class PostgreSQLDB {
 
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
    if ( !extension_loaded( "pgsql" ) ) {
      dl("php_pgsql.dll");
    }
    $this->open();
  }
  function open( ) {
    $this->connect = pg_connect(
      'host='.$this->Server .
      ' port=5432' .
      ' dbname='.$this->DbName .
      ' user='.$this->User .
      ' password='.$this->Password
      );
    //$this->QueryEx( "SET CLIENT_ENCODING TO 'SJIS'" );
  }
 
  function close( ) {
    pg_close( $this->connect );
  }
 
  function query( $SqlQuery ) {
    $ret = pg_query( $this->connect, $SqlQuery );
    return $ret;
  }

  function fetch( $result ) {
    if( $result )
      return pg_fetch_assoc( $result );
    else
      return NULL;
  }

  function free_result( $result ){
    if( $result )
      pg_free_result($result);
  }
  
  function execute( $SqlExec ) {
    $ret = pg_query( $this->connect, $SqlExec );
    return $ret;
  }
  function begin( ) {
    pg_query( $this->connect, 'begin' );
  }
  function commit( ) {
    pg_query( $this->connect, 'commit' );
  }
  function rollback( ) {
    pg_query( $this->connect, 'rollback' );
  }
  function sanitize($text){
    return pg_escape_string($text);
  }

  function version( ) {
    $field = $this->query_ex( "SHOW SERVER_VERSION" );
    return $field["server_version"];
  }
  
  function query_ex( $SqlQuery='' ) {
    $ret = NULL;
    if ( $SqlQuery != '' ) {
      $result = $this->query( $SqlQuery );
      if ( $result ) {
        $ret = $this->fetch ( $result );
      }
      $this->free_result($result);
    }
    return $ret;
  }
}
?>