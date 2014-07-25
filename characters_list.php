<?php
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

$sql = 'SELECT c.user_id, c.character_id, uc.main_page_name, c.character_id, c.name, c.image
			FROM ' . CHARACTERS_TABLE .' c, ' .USER_CHARACTERS_TABLE. ' uc
			WHERE c.user_id =  uc.user_id AND uc.display_on_main_page = 1 AND c.display_on_main_page = 1
			ORDER BY uc.main_page_name, c.character_id';

			
$result = $db->sql_query($sql);

$characters = array();
while ($row = $db->sql_fetchrow($result)) {
	//echo $row['main_page_name'] .' - '. $row['character_id'] . ' - ' . $row['name'] . '<br/>';
	$characters[$row['user_id']]['characters'][] = $row;
	$characters[$row['user_id']]['main_page_name'] = $row['main_page_name'];
}
$db->sql_freeresult($result);

header('Content-Type: application/json');

echo '{ "people" : [';
$first = 1;
foreach($characters as $player) {
	if($first){
		$first = 0;
	} else {
		echo ',';
	}
	echo '{';
	echo '"name" : "'.$player['main_page_name'].'",';
	echo '"characters" : [ ';
	$firstChar = 1;
	foreach($player['characters'] as $character){
		if($firstChar){
			$firstChar = 0;
		} else {
			echo ',';
		}
		echo '{';
		echo '"name" : "'.$character['name'].'",';
			echo '"id" : "'.$character['character_id'].'"';
		if($character['image']) {
			echo ', "img" : "'.$character['image'].'"';
		}
		echo '}';
	}
	echo '] ';
	echo '}';
}

echo ']}';

define('IN_PHPBB', false);
?>