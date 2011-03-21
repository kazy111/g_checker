<?php

interface IDataManager {
  // index list
  function get_index_datas();
  function get_streamer_info($streamer_id);

  // status update
  function update_chat($cid, $member, $topic);
  function update_program($pid, $live, $viewer, $change_flag, $thumb);
  
  function increment_offline_count($pid);
  
  function sanitize($str);
  
  // maintenance
  function get_streamer($streamer_id);
  function get_program($program_id);
  function get_chat($chat_id);
  function get_article($article_id);

  function get_streamers();
  function get_programs();
  function get_chats();
  function get_articles($pagesize = NULL, $page = 0);
  function get_histories($streamer_id);

  function set_streamer($data);
  function set_program($data);
  function set_chat($data);
  function set_article($data);

  function delete_streamer($streamer_id);
  function delete_program($program_id);
  function delete_chat($chat_id);
  function delete_article($article_id);

  // tag maintenance
  function get_tag($id);
  function set_tag($data);
  
  function initialize_db();
  function delete_db();
  function register_onece($name, $room, $chat_type, $ust_id, $jus_id, $ust_no, $desc);
  function query($sql);
}

?>
