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
* @package module_install
*/
class ucp_characters_info
{
	function module()
	{
		return array(
			'filename'	=> 'ucp_characters',
			'title'		=> 'UCP_CHARACTERS',
			'version'	=> '0.0.1',
			'modes'		=> array(
				'index'			=> array('title' => 'UCP_CHARACTERS_INDEX', 'auth' => 'acl_u_characters', 'cat' => array('UCP_CHARACTERS')),
				'character'			=> array('title' => 'UCP_CHARACTERS_NEW', 'auth' => 'acl_u_characters', 'cat' => array('UCP_CHARACTERS'))
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}

?>