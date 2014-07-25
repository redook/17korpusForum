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
	'UCP_CHARACTERS'	=> 'Postacie',
	'CHARACTERS'	=> 'Postacie',
	'UCP_CHARACTERS_INDEX'	=> 'Postacie',
	'UCP_CHARACTERS_NEW'	=> 'Nowa postać',
	'UCP_CHARACTERS_CHARACTER_ADDED'	=> 'Postać została dodana',
	'UCP_CHARACTERS_CHARACTER_EDITED'	=> 'Postać została zmieniona',
	'RETURN_CHARACTERS_LIST'	=> '%sPowrót do listy postaci%s',
	'UCP_CHARACTERS_TITLE_INDEX'	=> 'Lista postaci',
	'UCP_CHARACTERS_TITLE_NEW'	=> 'Postać',
	'CHARACTER_DELETED' => 'Postać została usunięta',
	'DELETE_CHARACTER' => 'Usuń postać',
	'DELETE_CHARACTER_CONFIRM' => 'Czy na pewno chcesz usunąć tą postać?',
	'UCP_CHARACTER_NAME'	=> 'Imię postaci',
));

?>