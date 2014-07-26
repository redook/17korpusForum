<?php
/**
*
* acp_styles [Polski]
*
* @package language
* @copyright (c) 2014 17 Karny Korpus Floty
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
	'UCP_CHARACTERS'	=> 'Characters',
	'UCP_CHARACTERS_INDEX'	=> 'Characters',
	'UCP_CHARACTERS_NEW'	=> 'New character',
	'UCP_CHARACTERS_TITLE_CHARACTER'	=> 'Character',
	'UCP_CHARACTERS_CHARACTER_ADDED'	=> 'The character has been added',
	'UCP_CHARACTERS_CHARACTER_EDITED'	=> 'The character details have been modified',
	'RETURN_CHARACTERS_LIST'	=> '%sBack to characters list%s',
	'UCP_CHARACTERS_TITLE_INDEX'	=> 'Characters list',
	'UCP_CHARACTERS_TITLE_NEW'	=> 'Character',
	'CHARACTER_DELETED' => 'The character has been removed',
	'DELETE_CHARACTER' => 'Remove character',
	'DELETE_CHARACTER_CONFIRM' => 'Are you sure you want to remove this character?',
	'UCP_CHARACTER_NAME'	=> 'Character name',
	'UCP_CHARACTERS_MAIN_PAGE_NAME'	=> 'Your nick for the guild webpage',
	'UCP_CHARACTERS_SHOW_ME_ON_MAIN_PAGE' => 'Display me on the guild webpage',
	'UCP_CHARACTERS_SHOW_CHARACTER_ON_MAIN_PAGE' => 'Display this character on the guild webpage',
	'CHARACTERS'	=> 'Characters',
	'CHARACTERS_CHARACTER'	=> 'Character',
	'CHARACTERS_ROLE'	=> 'Role',
	'CHARACTERS_CLASS' => 'Class',
	'CHARACTERS_LEVEL' => 'Level',
	'CHARACTERS_SPECIES' => 'Species',
	'CHARACTERS_NO_CHARACTERS' => 'No characters',
	'CHARACTERS_SCREEN'	=> 'Screen',
	'CHARACTERS_CURRENT_SCREEN'	=> 'Current screen',
	'CHARACTERS_IMAGE'	=> 'Image',

));

?>