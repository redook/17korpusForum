<?php
	if (empty($lang) || !is_array($lang))
	{
		$lang = array();
	}

	//$lang['permission_cat']['characters'] = 'SWTOR Characters MOD';

	$lang = array_merge($lang, array(
		'acl_u_characters'    => array('lang' => 'Access to SWTOR Characters MOD', 'cat' => 'profile'),
	));
?>