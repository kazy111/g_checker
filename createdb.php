<?php

include 'config.php';

function db_initialize($db)
{
  $db->query('CREATE TABLE streamer_table ('
             .'id SERIAL NOT NULL,'
             .'name VARCHAR(255),'
             .'description TEXT,'
             .'twitter VARCHAR(255),'
             .'url VARCHAR(255),'
             .'wiki INT,'
             .'PRIMARY KEY (id))');

  $db->query('CREATE TABLE program_table ('
             .'id SERIAL,'
             .'type SMALLINT,'
             .'ch_name VARCHAR(255),'
             .'optional_id VARCHAR(255),'
             .'thumbnail TEXT,'
             .'live BOOLEAN,'
             .'start_time TIMESTAMP,'
             .'end_time TIMESTAMP,'
             .'viewer INT,'
             .'streamer_id INT REFERENCES streamer_table (id),'
             .'chat_id INT REFERENCES chat_table (id),'
             .'live_count INT DEFAULT 0,'
             .'PRIMARY KEY (id))');
  
  $db->query('CREATE TABLE chat_table ('
             .'id SERIAL,'
             .'type SMALLINT,'
             .'topic VARCHAR(512),'
             .'room VARCHAR(255),'
             .'member INT,'
             .'PRIMARY KEY(id))');
  
  $db->query('CREATE TABLE history_table ('
             .'id SERIAL REFERENCES program_table (id),'
             .'program_id INT,'
             .'start_time TIMESTAMP,'
             .'end_time TIMESTAMP,'
             .'PRIMARY KEY(id))');
  
  $db->query('CREATE TABLE article_table ('
             .'id SERIAL,'
             .'title TEXT,'
             .'body TEXT,'
             .'created TIMESTAMP,'
             .'priority SMALLINT,'
             .'PRIMARY KEY(id))');
}

db_initialize($db);

?>