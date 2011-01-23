<?php
include_once 'IDataManager.php';
include_once 'MySQLDB.php';

class MySQLDataManager implements IDataManager {
  private $db;

  function __construct($db_host, $db_name, $db_user, $db_passwd){
    $this->db = new MySQLDB($db_host, $db_name, $db_user, $db_passwd);
  }

  // raw data for index page
  function get_index_datas(){
    $sql = 'select s.id as sid, p.id as pid, c.id as cid, live, start_time, end_time, topic, optional_id, wiki, twitter, '
        .' thumbnail, member, viewer, name, ch_name, p.type as type, c.type as ctype, c.id as cid, room, thumbnail'
        .' from streamer_table as s, program_table as p, chat_table as c '
        .' where s.id = p.streamer_id '
        .' and c.id = p.chat_id order by sid, pid;';
    $result = $this->db->query($sql);
    $list = array();
    while($arr = $this->db->fetch($result)){
      if(!$list[$arr['sid']]) $list[$arr['sid']] = array();
      $list[$arr['sid']][] = $arr;
    }
    
    return $list;
  }

  // use in view.php
  function get_streamer_info($streamer_id){
    $sql = 'select live, name, description, p.id as pid, c.id as cid, ch_name, optional_id, room, c.type as ctype, p.type as ptype '
      .' from streamer_table as s, program_table as p, chat_table c '
      .' where s.id = '.$streamer_id.' and s.id = p.streamer_id and c.id = p.chat_id';

    $result = $this->db->query($sql);
    $list = array();
    while($arr = $this->db->fetch($result)){
      $list[] = $arr;
    }
    return $list;
  }

  function get_streamer($streamer_id){
    return $this->db->query_ex('select id, name, description, twitter, url, wiki from streamer_table where id = '.$streamer_id.'');
  }
  
  function get_channel($channel_id){
    return $this->db->query_ex('select type, ch_name, optional_id, streamer_id, chat_id from program_table where id = '.$channel_id);
  }
  
  function get_chat($chat_id){
    return $this->db->query_ex('select type, room from chat_table where id = '.$chat_id);
  }
  
  function get_article($article_id){
    return $this->db->query_ex('select title, body, created, priority from article_table where id = '.$article_id);
  }
  
  function get_streamers(){
    $sql = 'select id, name from streamer_table order by id';
    $result = $this->db->query($sql);
    $list = array();
    while($arr = $this->db->fetch($result)){
      $list[] = $arr;
    }
    return $list;
  }
  function get_channels(){
    $sql = 'select id, ch_name, type, streamer_id, chat_id from program_table order by id';
    $result = $this->db->query($sql);
    $list = array();
    while($arr = $this->db->fetch($result)){
      $list[] = $arr;
    }
    return $list;
  }
  function get_chats(){
    $sql = 'select id, type, room from chat_table order by id';
    $result = $this->db->query($sql);
    $list = array();
    while($arr = $this->db->fetch($result)){
      $list[] = $arr;
    }
    return $list;
  }

  function get_articles($pagesize, $page){
    $sql = 'select id, title, body, priority, created from article_table '
      .' order by priority desc, created desc limit '.$pagesize.' offset '.($pagesize * $page).';';
    $result = $this->db->query($sql);
    $list = array();
    while(($arr = $this->db->fetch($result)) != NULL ){
      $list[] = $arr;
    }
    return $list;
  }
  
  function get_histories($streamer_id){
    $result = $this->db->query('select h.start_time as stime, h.end_time as etime from program_table as p, history_table as h '
                         .' where p.id = h.program_id and p.streamer_id = '.$streamer_id.' order by stime desc limit 100 ');
    
    $list = array();
    while($arr = $this->db->fetch($result)){
      $list[] = $arr;
    }
    return $list;
  }

  function set_streamer($id, $data){
    if(is_null($data['id']) || $data['id'] == '' || !is_numeric($data['id'])){
      // create
      $this->db->query('insert into streamer_table (name, description, twitter, url, wiki) values (\''
                       .$data['name'].'\', \''.$data['description'].'\', \''
                       .$data['twitter'].'\', \''.$data['url'].'\', '.$data['wiki'].')');
    } else {
      // update
      $this->db->query('update streamer_table set name = \''
                       .$data['name'].'\', description = \''.$data['description']
                       .'\', twitter = \''.$data['twitter'].'\', url = \''
                       .$data['url'].'\', wiki = '.($data['wiki']&&$data['wiki']!='' ?$data['wiki']:'NULL' ).' where id='.$data['id']);
    }
  }
  function set_channel($id, $data){
    if(is_null($data['id']) || $data['id'] == '' || !is_numeric($data['id'])){
      // create
      $this->db->query('insert into program_table (type, ch_name, optional_id, streamer_id, chat_id, viewer) values ('
                 .$data['type'].', \''.$data['ch_name'].'\', \''
                 .$data['optional_id'].'\', '.$data['streamer_id'].', '.$data['chat_id'].', 0)');
    } else {
      // update
      $this->db->query('update program_table set type = '.$data['type'].', ch_name = \''
                 .$data['ch_name'].'\', optional_id = \''.$data['optional_id']
                 .'\', streamer_id = '.$data['streamer_id']
                 .', chat_id = '.$data['chat_id']
                 .' where id='.$data['id']);
    }
  }
  
  function set_chat($id, $data){
    if(is_null($data['id']) || $data['id'] == '' || !is_numeric($data['id'])){
      // create
      $this->db->query('insert into chat_table (type, room, member) values ('
                       .$data['type'].', \''.$data['room'].'\', 0)');
    } else {
      // update
      $this->db->query('update chat_table set type = '.$data['type'].', room = \''
                       .$data['room'].'\' where id='.$data['id']);
    }
  }
  
  function set_article($id, $data){
    if(is_null($data['id']) || $data['id'] == '' || !is_numeric($data['id'])){
      // create
      $now = date('Y-m-d H:i:s');
      $this->db->query('insert into article_table (title, body, priority, created) values (\''
                       .$data['title'].'\', \''.$data['body'].'\', '.$data['priority'].', \''.$now.'\')');
    } else {
      // update
      $this->db->query('update article_table set title = \''.$data['title'].'\', body = \''
                       .$data['body'].'\', priority = '.$data['priority'].' where id='.$data['id']);
    }
  }

  function delete_streamer($streamer_id){
    // start transaction
    $this->db->begin();

    try{
      $sql = 'select c.id as id '
          .' from streamer_table as s, program_table as p, chat_table as c '
          .' where s.id = '.$id.' and  s.id = p.streamer_id and c.id = p.chat_id';
      $result = $this->db->query($sql);
      while($arr = $this->db->fetch($result)){
        $this->delete_chat($arr['id']);
      }

      $sql = 'select p.id as id '
          .' from streamer_table as s, program_table as p '
          .' where s.id = '.$id.' and  s.id = p.streamer_id';
      $result = $this->db->query($sql);
      while($arr = $this->db->fetch($result)){
        $this->delete_channel($arr['id']);
      }

      $sql = 'select id from streamer_table where id = '.$id;
      $result = $this->db->query($sql);
      while($arr = $this->db->fetch($result)){
        $this->db->query('delete from streamer_table where id = '.$arr['id']);
      }
      $this->db->commit();
    }catch(Exception $e){
      $this->db->rollback();
    }
  }
  
  function delete_channel($channel_id){
    $this->db->query('delete from program_table where id = '.$channel_id);
  }
  function delete_chat($chat_id){
    $this->db->query('delete from chat_table where id = '.$chat_id);
  }
  function delete_article($article_id){
    $this->db->query('delete from article_table where id = '.$article_id);
  }

  function is_using_chat($chat_id){
    $sql = 'select p.id as id '
      .' from program_table as p, chat_table as c '
      .' where c.id = '.$chat_id.' and c.id = p.chat_id';
    $result = $this->db->query($sql);

    $flag = FALSE;
    while($arr = $this->db->fetch($result)){
      $flag = TRUE;
    }
    unset($result);
    return $flag;
  }

  function initialize_db() {
    $this->db->query('CREATE TABLE streamer_table ('
                     .'id INT NOT NULL AUTO_INCREMENT,'
                     .'name VARCHAR(255),'
                     .'description TEXT,'
                     .'twitter VARCHAR(255),'
                     .'url VARCHAR(255),'
                     .'wiki INT,'
                     .'PRIMARY KEY (id))');

    $this->db->query('CREATE TABLE program_table ('
                     .'id INT NOT NULL AUTO_INCREMENT,'
                     .'type SMALLINT,'
                     .'ch_name VARCHAR(255),'
                     .'optional_id VARCHAR(255),'
                     .'thumbnail TEXT,'
                     .'live BOOLEAN,'
                     .'start_time TIMESTAMP,'
                     .'end_time TIMESTAMP,'
                     .'viewer INT,'
                     .'streamer_id INT,'
                     .'chat_id INT,'
                     .'live_count INT DEFAULT 0,'
                     .'PRIMARY KEY (id))');
    
    $this->db->query('CREATE TABLE chat_table ('
                     .'id INT NOT NULL AUTO_INCREMENT,'
                     .'type SMALLINT,'
                     .'topic VARCHAR(512),'
                     .'room VARCHAR(255),'
                     .'member INT,'
                     .'PRIMARY KEY(id))');
    
    $this->db->query('CREATE TABLE history_table ('
                     .'id INT NOT NULL AUTO_INCREMENT,'
                     .'program_id INT NOT NULL,'
                     .'start_time TIMESTAMP,'
                     .'end_time TIMESTAMP,'
                     .'PRIMARY KEY(id))');
    
    $this->db->query('CREATE TABLE article_table ('
                     .'id INT NOT NULL AUTO_INCREMENT,'
                     .'title TEXT,'
                     .'body TEXT,'
                     .'created TIMESTAMP,'
                     .'priority SMALLINT,'
                     .'PRIMARY KEY(id))');
  }
}

?>
