<?php
class Page{
  
  var $title;
  var $header;
  var $pname;
  var $data = array();
  var $theme = 'default';
  var $theme_dir = './themes/';
  var $theme_url = './themes/';
  var $rel_dir = '.';

  function Page($reldir = '.')
  {
    $this->header = array();
    $this->theme_dir = $GLOBALS['file_path'].'/themes/';
    $this->theme_url = $reldir.'/themes/';
    $this->rel_dir = $reldir;
  }
  function set_title($t)
  {
    $this->title = $t;
  }
  function add_header($h)
  {
    $this->header[] = $h;
  }
  function get_header()
  {
    return $this->header;
  }
  function set_relative_dir_to_top($reldir){
    $this->theme_url = $reldir.'/themes/';
    $this->rel_dir = $reldir;
  }

  function set($pname, $data)
  {
    $this->pname = $pname;
    $this->data = $data;
  }

  function get_css(){
    if( file_exists($this->theme_dir.$this->theme.'/theme.css') )
      $tpl = $this->theme_url.$this->theme.'/theme.css';
    else
      $tpl = $this->theme_url.'default/theme.css';
    return $tpl;
  }
  
  function get_template($page){
    if( file_exists($this->theme_dir.$this->theme.'/'.$page.'.tpl') )
      $tpl = $this->theme_dir.$this->theme.'/'.$page.'.tpl';
    else
      $tpl = $this->theme_dir.'default/'.$page.'.tpl';

    return $tpl;
  }
  function get_safe_contents($dwoo, $tpl, $data){
    $tplfile = $this->get_template($tpl);
    $hash = hash('md5', $tplfile);
    return $dwoo->get(new Dwoo_Template_SafeFile($tplfile, 0, $hash, $hash), $data);
  }
  function get_themes(){
    $themes = array();
    if( is_dir($this->theme_dir) ){
      if( $dh = opendir($this->theme_dir) ){
        readdir($dh); readdir($dh);
        while(($file = readdir($dh)) !== false){
          if(filetype($this->theme_dir.$file) == 'dir'
             && strpos($file, '_') !== 0)
            $themes[] = $file;
        }
        closedir($dh);
      }
    }
    sort($themes);
    return $themes;
  }

  function get($extra = '')
  {
    global $site_title;
    if(!$this->pname) return '';
    //header('Content-Type: text/html; charset=utf8');
    $dwoo = new Dwoo();
    $data = new Dwoo_Data();
    $this->header[] = '<link rel="stylesheet" type="text/css" href="'.$this->get_css().'" />';
    $this->header[] = '<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />';
    $data->assign('site_url', $GLOBALS['site_url']);
    $data->assign('file_path', $GLOBALS['file_path']);
    $data->assign('site_title', $GLOBALS['site_title']);
    $data->assign('relative_dir_to_top', $this->rel_dir);
    $data->assign('header_description', $GLOBALS['header_description']);
    $data->assign('footer_description', $GLOBALS['footer_description']);
    $data->assign('curr_time', time());
    $data->assign('site_title', ($this->title?$this->title.' - ':'').$site_title);
    $data->assign('additional_header', implode("\n", $this->header));
    $ret = '';
    
    $ret .= $dwoo->get(new Dwoo_Template_File($this->get_template(is_mobile()?$extra.'header':'header')), $data);
    //$ret .= $this->get_safe_contents($dwoo, is_mobile()?'header_mobile':'header', $data);

    if($this->data){
      if(is_array($this->data)){
        $this->data['site_url'] = $GLOBALS['site_url'];
        $this->data['file_path'] = $GLOBALS['file_path'];
        $this->data['site_title'] = $GLOBALS['site_title'];
        $this->data['relative_dir_to_top'] = $this->rel_dir;
        $this->data['header_description'] = $GLOBALS['header_description'];
        $this->data['footer_description'] = $GLOBALS['footer_description'];
      }else{
        $this->data->assign('site_url', $GLOBALS['site_url']);
        $this->data->assign('file_path', $GLOBALS['file_path']);
        $this->data->assign('site_title', $GLOBALS['site_title']);
        $this->data->assign('relative_dir_to_top', $this->rel_dir);
        $this->data->assign('header_description', $GLOBALS['header_description']);
        $this->data->assign('footer_description', $GLOBALS['footer_description']);
      }
    }
    $ret .= $dwoo->get(new Dwoo_Template_File($this->get_template($this->pname)), $this->data);
    $ret .= $dwoo->get(new Dwoo_Template_File($this->get_template('footer')), $data);
    //$ret .= $this->get_safe_contents($dwoo, $this->pname, $this->data);
    //$ret .= $this->get_safe_contents($dwoo, 'footer', $data);
    
    return $ret;
  }
  function get_once($page, $data)
  {
    $dwoo = new Dwoo();

    if($data){
      if(is_array($data)){
        $data['site_url'] = $GLOBALS['site_url'];
        $data['file_path'] = $GLOBALS['file_path'];
        $data['site_title'] = $GLOBALS['site_title'];
        $data['relative_dir_to_top'] = $this->rel_dir;
        $data['header_description'] = $GLOBALS['header_description'];
        $data['footer_description'] = $GLOBALS['footer_description'];
      }else{
        $data->assign('site_url', $GLOBALS['site_url']);
        $data->assign('file_path', $GLOBALS['file_path']);
        $data->assign('site_title', $GLOBALS['site_title']);
        $data->assign('relative_dir_to_top', $this->rel_dir);
        $data->assign('header_description', $GLOBALS['header_description']);
        $data->assign('footer_description', $GLOBALS['footer_description']);
      }
    }
    return $dwoo->get(new Dwoo_Template_File($this->get_template($page)), $data);
    //return $this->get_safe_contents($dwoo, $page, $data);
  }
  function output($extra = '')
  {
    print $this->get($extra);
  }
}
?>