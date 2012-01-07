<?php
include_once 'IDataManager.php';
include_once 'PostgreSQLDB.php';

class PostgreSQLDataManager implements IDataManager {
  private $db;

  function __construct($db_host, $db_name, $db_user, $db_passwd){
    $this->db = new PostgreSQLDB($db_host, $db_name, $db_user, $db_passwd);
  }

  function sanitize($str){
    return $this->db->sanitize($str);
  }

  function query($sql){
    return $this->db->query($sql);
  }

  // raw data for index page
  function get_index_datas(){
    $sql = 'select s.id as sid, p.id as pid, c.id as cid, live, start_time, end_time, topic, optional_id, description, wiki, twitter, url, tag, '
        .' thumbnail, member, viewer, name, ch_name, p.type as type, c.type as ctype, c.id as cid, room, thumbnail, offline_count, '
        .' p.title'
        .' from streamer_table as s, program_table as p, chat_table as c '
        .' where s.id = p.streamer_id and s.enable=1'
        .' and c.id = p.chat_id order by sid, pid;';
    $result = $this->db->query($sql);
    $list = array();
    while($arr = $this->db->fetch($result)){
      if(!array_key_exists($arr['sid'], $list)) $list[$arr['sid']] = array();
      $list[$arr['sid']][] = $arr;
    }
    
    return $list;
  }

  // use in view.php
  function get_streamer_info($streamer_id){
    $sql = 'select live, name, description, tag, url, wiki, p.id as pid, c.id as cid, ch_name, optional_id, room, c.type as ctype, p.type as ptype '
          .' from streamer_table as s, program_table as p, chat_table c '
          .' where s.id = '.$streamer_id.' and s.id = p.streamer_id and c.id = p.chat_id and s.enable=1';

    $result = $this->db->query($sql);
    $list = array();
    while($arr = $this->db->fetch($result)){
      $list[] = $arr;
    }
    return $list;
  }
  
  function update_chat($cid, $member, $topic){
    $sql = 'update chat_table set member='.$member
          .' , topic=\''.$topic.'\' where id='.$cid;
    $ret = $this->db->query($sql);
  }
  
  function update_program($pid, $live, $viewer, $change_flag, $thumb, $title=null){
    $sql_live = $live ? 'TRUE' : 'FALSE';
    // construct time SQL
    $sql_time = '';
    if($change_flag){
      $now = date('Y-m-d H:i:s');
      if($live){
        $sql_time = ', start_time = \''.$now.'\' ';
      }else{
        $sql_time = ', end_time = \''.$now.'\' ';
        // TODO add history
      }
    }
    $sql = 'update program_table set live = '.$sql_live.' , viewer = '.$viewer
          . $sql_time.', thumbnail = \''. $thumb .'\', offline_count = 0 '
            . ($title != null ? ' ,title = \''. $title .'\' ' : '')
          . ' where id = '.$pid;
    $this->db->query($sql);
  }
  
  function increment_offline_count($pid){
    $sql = 'update program_table set offline_count = offline_count + 1 where id = '.$pid;
    $this->db->query($sql);
  }

  function add_history($pid, $start_time, $end_time) {
    try{
      $sql = 'insert into  history_table (program_id, start_time, end_time) '
        .' values ('.$pid.', \''.$start_time.'\', \''.$end_time.'\')';
      log_print($sql);
      $this->db->query($sql);
    } catch (Exception $e) {
      print("例外キャッチ：". $e->getMessage(). "\n");
    }
  }
  
  function get_streamer($streamer_id){
    return $this->db->query_ex('select id, name, description, twitter, url, wiki, tag from streamer_table where id = '.$streamer_id.'');
  }
  
  function get_program($program_id){
    return $this->db->query_ex('select type, ch_name, optional_id, streamer_id, chat_id from program_table where id = '.$program_id);
  }
  
  function get_chat($chat_id){
    return $this->db->query_ex('select type, room from chat_table where id = '.$chat_id);
  }
  
  function get_article($article_id){
    return $this->db->query_ex('select title, body, created, priority from article_table where id = '.$article_id);
  }
  
  function get_legend($id){
    return $this->db->query_ex('select body, created from legend_table where id = '.$id);
  }
  function get_random_legend(){
    return $this->db->query_ex('select body, created from legend_table order by random() limit 1');
  }


  function get_streamers(){
    $sql = 'select id, name, description, twitter, url, wiki, tag from streamer_table order by id';
    $result = $this->db->query($sql);
    $list = array();
    while($arr = $this->db->fetch($result)){
      $list[] = $arr;
    }
    return $list;
  }
  
  function get_programs(){
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

  function get_articles($pagesize = NULL, $page = 0){
    $sql = 'select id, title, body, priority, created from article_table '
          .' order by priority desc, created desc';
    if($pagesize)
      $sql .= ' limit '.$pagesize.' offset '.($pagesize * $page).';';
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

  function get_legends($pagesize = NULL, $page = 0){
    $sql = 'select id, body, created from legend_table '
          .' order by created desc';
    if($pagesize)
      $sql .= ' limit '.$pagesize.' offset '.($pagesize * $page).';';
    $result = $this->db->query($sql);
    $list = array();
    while(($arr = $this->db->fetch($result)) != NULL ){
      $list[] = $arr;
    }
    return $list;
  }

  function set_streamer($data){
    if(is_null($data['id']) || $data['id'] == '' || !is_numeric($data['id'])){
      // create
      $this->db->query('insert into streamer_table (name, description, twitter, url, wiki, tag, enable) values (\''
                       .$data['name'].'\', \''.$data['description'].'\', \''
                       .$data['twitter'].'\', \''.$data['url'].'\', '.($data['wiki']&&$data['wiki']!='' ?$data['wiki']:'NULL' )
                       .', \''.$data['tag'].'\', 1)');
    } else {
      // update
      $this->db->query('update streamer_table set name = \''
                       .$data['name'].'\', description = \''.$data['description']
                       .'\', twitter = \''.$data['twitter'].'\', url = \''
                       .$data['url'].'\', wiki = '.($data['wiki']&&$data['wiki']!='' ?$data['wiki']:'NULL' )
                       .', tag = \''.$data['tag'].'\''
                       .' where id='.$data['id']);
    }
  }
  function set_program($data){
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
  
  function set_chat($data){
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

  function set_article($data){
    $now = date('Y-m-d H:i:s');
    if(is_null($data['id']) || $data['id'] == '' || !is_numeric($data['id'])){
      // create
      $this->db->query('insert into article_table (title, body, priority, created) values (\''
                       .$data['title'].'\', \''.$data['body'].'\', '.$data['priority'].', \''.$now.'\')');
    } else {
      // update
      if(array_key_exists('update_time', $data)){
        $this->db->query('update article_table set title = \''.$data['title'].'\', body = \''
                         .$data['body'].'\', priority = '.$data['priority'].', created = \''.$now.'\''
                         .' where id='.$data['id']);
      } else {
        $this->db->query('update article_table set title = \''.$data['title'].'\', body = \''
                         .$data['body'].'\', priority = '.$data['priority'].' where id='.$data['id']);
      }
    }
  }

  function set_legend($data){
    $now = date('Y-m-d H:i:s');
    if(is_null($data['id']) || $data['id'] == '' || !is_numeric($data['id'])){
      // create
      $this->db->query('insert into legend_table (body, created) values (\''
                       .$data['body'].'\', \''.$now.'\')');
    } else {
      // update
      $this->db->query('update legend_table set body = \''.$data['body'].'\' where id='.$data['id']);
    }
  }

  function delete_streamer($streamer_id){
    // start transaction
    $this->db->begin();

    try{
      $sql = 'select c.id as cid, p.id as pid '
          .' from streamer_table as s, program_table as p, chat_table as c '
          .' where s.id = '.$streamer_id.' and  s.id = p.streamer_id and c.id = p.chat_id';
      $result = $this->db->query($sql);
      while($arr = $this->db->fetch($result)){
        $tmp = $this->db->query_ex('select id from program_table where chat_id = '.$arr['cid'].' and id <> '.$arr['pid']);
        // if unused chat, then delete
        if(is_null($tmp)  || !is_numeric($tmp['id']))
          $this->delete_chat($arr['cid']);
      }

      $sql = 'select p.id as id '
          .' from streamer_table as s, program_table as p '
          .' where s.id = '.$streamer_id.' and  s.id = p.streamer_id';
      $result = $this->db->query($sql);
      while($arr = $this->db->fetch($result)){
        $this->delete_program($arr['id']);
      }

      $sql = 'select id from streamer_table where id = '.$streamer_id;
      $result = $this->db->query($sql);
      while($arr = $this->db->fetch($result)){
        $this->db->query('delete from streamer_table where id = '.$arr['id']);
      }
      $this->db->commit();
    } catch (Exception $e) {
      $this->db->rollback();
    }
  }
  
  function delete_program($program_id){
    $this->db->query('delete from program_table where id = '.$program_id);
  }
  function delete_chat($chat_id){
    $this->db->query('delete from chat_table where id = '.$chat_id);
  }
  function delete_article($article_id){
    $this->db->query('delete from article_table where id = '.$article_id);
  }
  function delete_legend($id){
    $this->db->query('delete from legend_table where id = '.$id);
  }
  
  // tag maintenance
  function get_tag($id){
    return $this->db->query_ex('select tag from streamer_table where id = '.$id);
  }
  function set_tag($data){
    if(is_null($data['id']) || $data['id'] == '' || !is_numeric($data['id'])){
      // err
      return FALSE;
    } else {
      // update
      $this->db->query('update streamer_table set tag = \''.$data['tag']
                       .'\' where id='.$data['id']);
      return TRUE;
    }
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
  
  function try_query($sql){
    if( $this->db->query($sql) ){
      print '<b>success:</b> '.$sql."<br>\n";
    }else{
      print '<span style="color: red;"><b>fail:</b> '.$sql."</span><br>\n";
    }
  }
  
  function initialize_db() {
    $this->try_query('CREATE TABLE streamer_table ('
                     .'id SERIAL NOT NULL,'
                     .'name VARCHAR(255),'
                     .'description TEXT,'
                     .'twitter VARCHAR(255),'
                     .'url VARCHAR(255),'
                     .'wiki INT,'
                     .'tag TEXT DEFAULT \'\','
                     .'enable SMALLINT DEFAULT 1,'
                     .'PRIMARY KEY (id))');

    $this->try_query('CREATE TABLE program_table ('
                     .'id SERIAL NOT NULL,'
                     .'type SMALLINT,'
                     .'ch_name VARCHAR(255),'
                     .'optional_id VARCHAR(255),'
                     .'title VARCHAR(255) DEFAULT \'\','
                     .'thumbnail TEXT,'
                     .'live BOOLEAN,'
                     .'start_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,'
                     .'end_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,'
                     .'viewer INT,'
                     .'streamer_id INT,'
                     .'chat_id INT,'
                     .'offline_count INT DEFAULT 0,'
                     .'PRIMARY KEY (id))');
    
    $this->try_query('CREATE TABLE chat_table ('
                     .'id SERIAL NOT NULL,'
                     .'type SMALLINT,'
                     .'topic VARCHAR(512),'
                     .'room VARCHAR(255),'
                     .'member INT,'
                     .'PRIMARY KEY(id))');
    
    $this->try_query('CREATE TABLE history_table ('
                     .'id SERIAL NOT NULL,'
                     .'program_id INT,'
                     .'start_time TIMESTAMP,'
                     .'end_time TIMESTAMP,'
                     .'PRIMARY KEY(id))');
    
    $this->try_query('CREATE TABLE article_table ('
                     .'id SERIAL NOT NULL,'
                     .'title TEXT,'
                     .'body TEXT,'
                     .'created TIMESTAMP,'
                     .'priority SMALLINT,'
                     .'PRIMARY KEY(id))');
    
    $this->try_query('CREATE TABLE legend_table ('
                     .'id SERIAL NOT NULL,'
                     .'body TEXT,'
                     .'created TIMESTAMP,'
                     .'PRIMARY KEY(id))');
  }
  
  function delete_db(){
    $this->try_query('drop table streamer_table;');
    $this->try_query('drop table program_table;');
    $this->try_query('drop table chat_table;');
    $this->try_query('drop table history_table;');
    $this->try_query('drop table article_table;');
    $this->try_query('drop table legend_table;');
  }
  
  function register_onece($name, $room, $chat_type, $ust_id, $jus_id, $ust_no, $desc){
    $this->db->begin();

    try{
      // PostgreSQL
      $sql = 'select nextval(\'streamer_table_id_seq\')';
      $arr = $this->db->query_ex($sql);
      $sid = $arr['nextval']; // TODO => create get_sequence_id?
      // -- PostgreSQL
      $this->db->query('insert into streamer_table (id, name, description, enable) values '
                 .'('.$sid.', \''.$name.'\', \''.$desc.'\', 1)');
      
      // PostgreSQL
      $tmp = $this->db->query_ex('select id from chat_table where type='
                                 .$chat_type.' and room=\''.$room.'\'');
      if(is_null($tmp) || !is_numeric($tmp['id'])){
        $arr = $this->db->query_ex('select nextval(\'chat_table_id_seq\')');
        $cid = $arr['nextval']; // TODO => create get_sequence_id?
        // -- PostgreSQL
        $this->db->query('insert into chat_table (id, room, type) values '
                         .'('.$cid.', \''.$room.'\', '.$chat_type.')');
      }else{
        $cid = $tmp['id'];
      }
      
      if($ust_id){
        $this->db->query('insert into program_table (streamer_id, chat_id, type, ch_name, optional_id)'
                         .' values ('.$sid.', '.$cid.', 0, \''.$ust_id.'\',\''.$ust_no.'\')');
      }
      if($jus_id){
        $this->db->query('insert into program_table (streamer_id, chat_id, type, ch_name)'
                         .' values ('.$sid.', '.$cid.', 1, \''.$jus_id.'\')');
      }
      
      $this->db->commit();
    }catch(Exception $e){
      $this->db->rollback();
    }
    
  }
}

?>
