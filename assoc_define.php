<?php



$service_assoc = array(0 => 'ustream',
                       1 => 'justin');

$chat_assoc = array(0 => 'c.ustream.tv',
                       1 => 'irc.mibbit.com');

$sort_assoc = array('random' => 'ランダム',
                    'viewer' => '視聴者数',
                    'time' => '配信時間',
                    'name' => '名前'
                    );

function assoc2select($assoc, $id, $selected)
{
  $html = '<select id="'.$id.'" name="'.$id.'">';
  foreach($assoc as $k => $v){
    if($selected && $selected == $k)
      $html .= '<option value="'.$k.'" selected="selected">'.$v.'</option>';
    else
      $html .= '<option value="'.$k.'">'.$v.'</option>';
  }
  $html .= '</select>';
  return $html;
}

?>
