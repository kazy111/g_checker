<?php

interface IDataManager {
  public function get_index_datas();

  public function get_streamer_info($streamer_id);
  
  function get_streamer($streamer_id);
  function get_program($program_id);
  function get_chat($chat_id);
  function get_article($article_id);
  
  function get_streamers();
  function get_programs();
  function get_chats();
  function get_articles($pagesize, $page);
  function get_histories($streamer_id);

  function set_streamer($id, $data);
  function set_program($id, $data);
  function set_chat($id, $data);
  function set_article($id, $data);

  function delete_streamer($streamer_id);
  function delete_program($program_id);
  function delete_chat($chat_id);
  function delete_article($article_id);

  function initialize_db();
}

?>
