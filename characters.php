<?php
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);


print_r($user);

if($user->data['is_registered']) {
	$sql = 'SELECT character_id, name
			FROM ' . CHARACTERS_TABLE .'
			WHERE user_id = ' .$user->data['user_id'];

			
	$result = $db->sql_query($sql);

	echo '<br/>';
	while ($row = $db->sql_fetchrow($result)) {
		echo $row['character_id'] . ' - ' . $row['name'] . '<br/>';
	}
	$db->sql_freeresult($result);
} else {
	echo 'N/A';
}
?>
