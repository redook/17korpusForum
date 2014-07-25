<?php
/**
*
* @package ucp
* @version $Id$
* @copyright (c) 2014 17 Karny Korpus Floty
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}



/**
* ucp_characters
* UCP Characters
* @package ucp
*/
class ucp_characters
{

	var $u_action;
	var $new_config;
	

	function main($id, $mode)
	{
		global $db, $user, $auth, $template;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
		include($phpbb_root_path . 'includes/constants_characters.' . $phpEx);
		
		$submit = (!empty($_POST['submit'])) ? true : false;
		switch($mode)
		{
			case 'character':
				$edit = (request_var('edit', '')) ? true : false;
				$delete = (!empty($_POST['delete'])) ? true : false;
				$klass = null;
				$data = array(
						'name'			=> '',
						'class'			=> '',
						'role'			=> '',
						'level'			=> '',
						'species'			=> '',
						'display_on_main_page' => 0,
					);
				if($edit || $delete)
					$characterId = intval($_GET['character_id']);
					
				if($delete) {
					$this->deleteChatacter($characterId);
					return;
				}
					
				if($edit && !$submit)
				{
					$sql = 'SELECT character_id, name, role, class, level, species, image, display_on_main_page 
						FROM ' . CHARACTERS_TABLE .'
						WHERE user_id = ' .$user->data['user_id'].' AND character_id = '.$characterId;
					$result = $db->sql_query($sql);
					$row = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);
					$data = $this->copyNotNulls($data, $row);
				}
				$data = array(
					'name'			=> request_var('name', $data['name']),
					'class'			=> $this->nullIfEmpty(request_var('class', $data['class'])),
					'role'			=> request_var('role', $data['role']),
					'level'			=> $this->nullIfEmpty(request_var('level', $data['level'])),
					'species'			=> $this->nullIfEmpty(request_var('species', $data['species'])),
					'display_on_main_page' => request_var('display_on_main_page', $data['display_on_main_page']),
					'image' => $data['image'],
				);

				if($submit) {
					$validate_array = array(
						'name'			=> array('string', false, 3, 30),
						'class'			=> array('string', true, 1, 15),
						'role'			=> array('string', true, 1, 15),
						'species'			=> array('string', true, 1, 15),
						'level'		=> array('num', true, 1, 55),
						'display_on_main_page'		=> array('num', true, 0, 1)
					);
					$error = array();
					$error = validate_data($data, $validate_array);
					
					$uploadData = $this->character_upload($data, $error);
					$image = $uploadData[0];

					if (!sizeof($error))
					{
						$sql_ary = array(
							'name'		=> $data['name'],
							'class'		=> $data['class'],
							'role'		=> $data['role'],
							'level'		=> $data['level'],
							'species'		=> $data['species'],
							'user_id'	=> $user->data['user_id'],
							'display_on_main_page' => $data['display_on_main_page']?$data['display_on_main_page']:0,
						);
						if($image) {
							$sql_ary['image'] = $image;
						}

						if($characterId){
							$sql = 'UPDATE ' . CHARACTERS_TABLE .' SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
								WHERE user_id = ' . $user->data['user_id'] . ' AND character_id = ' .$characterId;
						} else {
							$sql = 'INSERT INTO ' . CHARACTERS_TABLE . $db->sql_build_array('INSERT', $sql_ary);
						}
						$db->sql_query($sql);
						$meta_info = append_sid("{$phpbb_root_path}ucp.$phpEx", "i=characters&amp;mode=index");
						//meta_refresh(3, $meta_info);
						if($characterId){
							$message = $user->lang['UCP_CHARACTERS_CHARACTER_EDITED'].'<br /><br />' . sprintf($user->lang['RETURN_CHARACTERS_LIST'], '<a href="' . $meta_info . '">', '</a>');
						} else {
							$message = $user->lang['UCP_CHARACTERS_CHARACTER_ADDED'].'<br /><br />' . sprintf($user->lang['RETURN_CHARACTERS_LIST'], '<a href="' . $meta_info . '">', '</a>');
						}
						trigger_error($message);
					} else {
						$error = preg_replace('#^([A-Z_]+)$#e', "(!empty(\$user->lang['\\1'])) ? \$user->lang['\\1'] : '\\1'", $error);
					}
				}

				$template->assign_vars(array(
					'ERROR'			=> (sizeof($error)) ? implode('<br />', $error) : '',
					'S_FORM_ENCTYPE'	=> ' enctype="multipart/form-data"',
					'S_CHARACTER_NAME'	=> $data['name'],
					'S_DISPLAY_ON_MAIN_PAGE'	=> $data['display_on_main_page'],
					'S_CHARACTER_IMAGE'	=> $data['image'],
					'S_CHARACTER_ROLE'	=> $data['role'],
					'S_CHARACTER_ID'	=> $characterId,
					'S_CLASS_OPTIONS'	=> $this->htmlSelectOptions(characters_const::$classes, $data['class']),
					'S_LEVEL_OPTIONS'	=> $this->htmlSelectOptions(characters_const::$levels, $data['level']),
					'S_SPECIES_OPTIONS'	=> $this->htmlSelectOptions(characters_const::$species, $data['species']),
					
					'L_CHARACTER_NAME_EXPLAIN'		=> sprintf($user->lang[$config['allow_name_chars'] . '_EXPLAIN'], 3, 30),
				));
				$this->page_title = 'UCP_CHARACTERS';
				$this->tpl_name = 'ucp_characters_edit';
				

				break;
			case 'index':
				
				$sql = 'SELECT main_page_name, display_on_main_page 
					FROM ' . USER_CHARACTERS_TABLE .'
					WHERE user_id = ' .$user->data['user_id'];

						
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);
				if($row) {
					$template->assign_vars(array(
						//'ERROR'			=> (sizeof($error)) ? implode('<br />', $error) : '',
						'S_MAIN_PAGE_NAME'	=> $row['main_page_name'],
						'S_DISPLAY_ON_MAIN_PAGE'	=> $row['display_on_main_page'],
						'L_CHARACTER_NAME_EXPLAIN'		=> sprintf($user->lang[$config['allow_name_chars'] . '_EXPLAIN'], 3, 30),
					));
				}
				
				if($submit) {
					$data = array(
						'main_page_name' => '',
						'display_on_main_page' => '0',
					);
					$data = $this->copyNotNulls($data, $row);
					$data = array(
						'main_page_name' => request_var('main_page_name', $data['main_page_name']),
						'display_on_main_page' => request_var('display_on_main_page', $data['display_on_main_page']),
					);
					if($row) {
						
						$sql = 'UPDATE ' . USER_CHARACTERS_TABLE .' SET ' . $db->sql_build_array('UPDATE', $data) . '
								WHERE user_id = ' . $user->data['user_id'];
						
					} else {
						$data['user_id'] = $user->data['user_id'];
						$sql = 'INSERT INTO ' . USER_CHARACTERS_TABLE . $db->sql_build_array('INSERT', $data);
					}

					$db->sql_query($sql);
					$meta_info = append_sid("{$phpbb_root_path}ucp.$phpEx", "i=characters&amp;mode=index");
					$message = 'Zmieniono dane<br /><br />' . sprintf($user->lang['RETURN_CHARACTERS_LIST'], '<a href="' . $meta_info . '">', '</a>');
					trigger_error($message);
				}
				
				
				$sql = 'SELECT character_id, name, role, class, level, species, display_on_main_page 
					FROM ' . CHARACTERS_TABLE .'
					WHERE user_id = ' .$user->data['user_id'];

						
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result)) {
					// Send vars to template
					$characterEditUrl = append_sid("{$phpbb_root_path}ucp.$phpEx", "i=characters&amp;mode=character&amp;edit=1&amp;character_id=".$row['character_id']);
					$template->assign_block_vars('characterrow', array(
						'CHARACTER_ID'		=> $row['character_id'],
						'CHARACTER_NAME'		=> $row['name'],
						'CHARACTER_ROLE'		=> characters_const::$roles[$row['role']],
						'CHARACTER_ROLE_IMG'		=> $row['role'],
						'CHARACTER_CLASS'		=> characters_const::$classes[$row['class']],
						'CHARACTER_CLASS_IMG'		=> $row['class'],
						'CHARACTER_LEVEL'		=> characters_const::$levels[$row['level']],
						'CHARACTER_SPECIES'		=> characters_const::$species[$row['species']],
						'CHARACTER_SPECIES_IMG'		=> $row['species'],
						'CHARACTER_DISPLAY'		=> $row['display'],
						'CHARACTER_EDIT_URL'		=> $characterEditUrl,
						)
						
					);
				}
				$db->sql_freeresult($result);
				$this->page_title = 'UCP_CHARACTERS';
				$this->tpl_name = 'ucp_characters';
				

				break;
		}
		$template->assign_vars(array(
			'L_TITLE'	=> $user->lang['UCP_CHARACTERS_TITLE_' . strtoupper($mode)],
		));

	}
	
	function htmlSelectOptions($optionsArray, $selectedOption)
	{
		$selected = ($selectedOption!='') ? '' : 'selected="selected"';
		$html = '<option value="" '.$selected.'>-</option>';
		foreach ($optionsArray as $key => $displayName)
		{
			$disabled = $key == 'disabled' ? ' disabled="true"' : '';
			$selected = ($selectedOption == $key) ? 'selected="selected"' : '';
			$html = $html.'<option value="'.$key.'" '.$selected.$disabled.'>'.$displayName.'</option>';
		}
		return $html;
	}
	
	function roleOptions($role)
	{
		$html = '';
		foreach (characters_const::$roles as $key => $displayName)
		{
			$selected = ($role == $key) ? 'checked="checked"' : '';
			$html = $html.'<input type="radio" class="radio" name="role" value="'.$key.'" '.$selected.' /> '.$displayName.'&nbsp;&nbsp;';
		}
		return $html;
	}
	
	function nullIfEmpty($str) {
		return $str==''?null:$str;
	}
	
	function copyNotNulls($dest, $src) {
		foreach ($src as $key => $val)
		{
			if($val) {
				$dest[$key] = $val;
			}
		}
		return $dest;
	}
	
	function deleteChatacter($characterId) {
		global $db, $user;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
		// Do we need to confirm ?
		if (confirm_box(true))
		{
			$sql = 'DELETE FROM ' . CHARACTERS_TABLE . ' WHERE character_id = '.$characterId.
				' AND user_id = '.$user->data['user_id'];
			$db->sql_query($sql);
			$meta_info = append_sid("{$phpbb_root_path}ucp.$phpEx", "i=characters&amp;mode=index");
			meta_refresh(3, $meta_info);
			$message = $user->lang['CHARACTER_DELETED'].'<br /><br />' . sprintf($user->lang['RETURN_CHARACTERS_LIST'], '<a href="' . $meta_info . '">', '</a>');
			trigger_error($message);
		}
		else
		{
			$s_hidden_fields = array(
				'character_id'			=> $characterId,
				'i'	=> 'characters',
				'mode'	=> 'character',
				'delete'	=> '1'
			);
			confirm_box(false, 'DELETE_CHARACTER', build_hidden_fields($s_hidden_fields));
		}
	}
	
	function character_upload($data, &$error)
	{
		global $phpbb_root_path, $config, $db, $user, $phpEx;

		// Init upload class
		include_once($phpbb_root_path . 'includes/functions_upload.' . $phpEx);
		$upload = new fileupload('AVATAR_', array('jpg', 'jpeg', 'gif', 'png'), 1048576, 10, 10, 1200, 900, (isset($config['mime_triggers']) ? explode('|', $config['mime_triggers']) : false));

		if (!empty($_FILES['uploadfile']['name']))
		{
			$file = $upload->form_upload('uploadfile');
		}
		else
		{
			return;
		//	$file = $upload->remote_upload($data['uploadurl']);
		}

		$prefix = utf8_clean_string($data['name']).'_';
		$file->clean_filename('avatar', $prefix, $user->data['user_id']);

		$destination = 'images/characters';

		// Adjust destination path (no trailing slash)
		if (substr($destination, -1, 1) == '/' || substr($destination, -1, 1) == '\\')
		{
			$destination = substr($destination, 0, -1);
		}

		$destination = str_replace(array('../', '..\\', './', '.\\'), '', $destination);
		if ($destination && ($destination[0] == '/' || $destination[0] == "\\"))
		{
			$destination = '';
		}

		// Move file and overwrite any existing image
		$file->move_file($destination, true);

		if (sizeof($file->error))
		{
			$file->remove();
			$error = array_merge($error, $file->error);
		}

		return array($file->get('realname'), $file->get('width'), $file->get('height'));
	}

}
?>