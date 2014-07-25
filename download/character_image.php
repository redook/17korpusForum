<?php
/**
*
* @package phpBB3
* @version $Id$
* @copyright (c) 2005 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);


// Thank you sun.
if (isset($_SERVER['CONTENT_TYPE']))
{
	if ($_SERVER['CONTENT_TYPE'] === 'application/x-java-archive')
	{
		exit;
	}
}
else if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Java') !== false)
{
	exit;
}

if (isset($_GET['character_id']))
{

	require($phpbb_root_path . 'includes/startup.' . $phpEx);
	require($phpbb_root_path . 'config.' . $phpEx);

	if (!defined('PHPBB_INSTALLED') || empty($dbms) || empty($acm_type))
	{
		exit;
	}

	require($phpbb_root_path . 'includes/acm/acm_' . $acm_type . '.' . $phpEx);
	require($phpbb_root_path . 'includes/cache.' . $phpEx);
	require($phpbb_root_path . 'includes/db/' . $dbms . '.' . $phpEx);
	require($phpbb_root_path . 'includes/constants.' . $phpEx);
	require($phpbb_root_path . 'includes/functions.' . $phpEx);

	$db = new $sql_db();
	$cache = new cache();

	// Connect to DB
	if (!@$db->sql_connect($dbhost, $dbuser, $dbpasswd, $dbname, $dbport, false, false))
	{
		exit;
	}
	unset($dbpasswd);

	// worst-case default
	$browser = (!empty($_SERVER['HTTP_USER_AGENT'])) ? htmlspecialchars((string) $_SERVER['HTTP_USER_AGENT']) : 'msie 6.0';

	$config = $cache->obtain_config();
	$character_id = intval(request_var('character_id', ''));
	
	$sql = 'SELECT character_id, image 
				FROM ' . CHARACTERS_TABLE .'
				WHERE character_id = '.$character_id;
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	$filename = $row['image'];
	
	$exit = false;

	// '==' is not a bug - . as the first char is as bad as no dot at all
	if (strpos($filename, '.') == false)
	{
		send_status_line(403, 'Forbidden');
		$exit = true;
	}

	$ext		= substr(strrchr($filename, '.'), 1);

	if (!$exit && !in_array($ext, array('png', 'gif', 'jpg', 'jpeg')))
	{	
		
		// no way such an avatar could exist. They are not following the rules, stop the show.
		send_status_line(403, 'Forbidden');
		$exit = true;
	}


	if (!$exit)
	{
		if (!$filename)
		{
			// no way such an avatar could exist. They are not following the rules, stop the show.
			send_status_line(403, 'Forbidden');
		}
		else
		{
	
			send_image_to_browser( $filename, $browser);
		}
	}
	file_gc();
}


/**
* A simplified function to deliver avatars
* The argument needs to be checked before calling this function.
*/
function send_image_to_browser($file, $browser)
{
	global $config, $phpbb_root_path;

	$image_dir = 'images/characters';

	// Adjust image_dir path (no trailing slash)
	if (substr($image_dir, -1, 1) == '/' || substr($image_dir, -1, 1) == '\\')
	{
		$image_dir = substr($image_dir, 0, -1) . '/';
	}
	$image_dir = str_replace(array('../', '..\\', './', '.\\'), '', $image_dir);

	if ($image_dir && ($image_dir[0] == '/' || $image_dir[0] == '\\'))
	{
		$image_dir = '';
	}
	$file_path = $phpbb_root_path . $image_dir . '/' . $file;
	
	$stamp = filemtime($file_path);
	
	set_modified_headers($stamp, $browser);

	if ((@file_exists($file_path) && @is_readable($file_path)) && !headers_sent())
	{
		header('Pragma: public');

		$image_data = @getimagesize($file_path);
		header('Content-Type: ' . image_type_to_mime_type($image_data[2]));
			
		if ((strpos(strtolower($browser), 'msie') !== false) && !phpbb_is_greater_ie_version($browser, 7))
		{
			header('Content-Disposition: attachment; ' . header_filename($file));

			if (strpos(strtolower($browser), 'msie 6.0') !== false)
			{
				header('Expires: -1');
			}
			else
			{
				header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000));
			}
		}
		else
		{
			header('Content-Disposition: inline; ' . header_filename($file));
			header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000));
		}

		$size = @filesize($file_path);
		if ($size)
		{
			header("Content-Length: $size");
		}

		if (@readfile($file_path) == false)
		{
			$fp = @fopen($file_path, 'rb');

			if ($fp !== false)
			{
				while (!feof($fp))
				{
					echo fread($fp, 8192);
				}
				fclose($fp);
			}
		}

		flush();
	}
	else
	{
		send_status_line(404, 'Not Found');
	}
}




/**
* Get a browser friendly UTF-8 encoded filename
*/
function header_filename($file)
{
	$user_agent = (!empty($_SERVER['HTTP_USER_AGENT'])) ? htmlspecialchars((string) $_SERVER['HTTP_USER_AGENT']) : '';

	// There be dragons here.
	// Not many follows the RFC...
	if (strpos($user_agent, 'MSIE') !== false || strpos($user_agent, 'Safari') !== false || strpos($user_agent, 'Konqueror') !== false)
	{
		return "filename=" . rawurlencode($file);
	}

	// follow the RFC for extended filename for the rest
	return "filename*=UTF-8''" . rawurlencode($file);
}


/**
* Check if the browser has the file already and set the appropriate headers-
* @returns false if a resend is in order.
*/
function set_modified_headers($stamp, $browser)
{

	// let's see if we have to send the file at all
	$last_load 	=  isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? strtotime(trim($_SERVER['HTTP_IF_MODIFIED_SINCE'])) : false;
		
	if (strpos(strtolower($browser), 'msie 6.0') === false && !phpbb_is_greater_ie_version($browser, 7))
	{
		if ($last_load !== false && $last_load >= $stamp)
		{
			send_status_line(304, 'Not Modified');
			// seems that we need those too ... browsers
			header('Pragma: public');
			header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000));
			return true;
		}
		else
		{
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $stamp) . ' GMT');
		}
	}

	return false;
}

function file_gc()
{
	global $cache, $db;
	if (!empty($cache))
	{
		$cache->unload();
	}
	$db->sql_close();
	exit;
}

/**
* Check if the browser is internet explorer version 7+
*
* @param string $user_agent	User agent HTTP header
* @param int $version IE version to check against
*
* @return bool true if internet explorer version is greater than $version
*/
function phpbb_is_greater_ie_version($user_agent, $version)
{
	if (preg_match('/msie (\d+)/', strtolower($user_agent), $matches))
	{
		$ie_version = (int) $matches[1];
		return ($ie_version > $version);
	}
	else
	{
		return false;
	}
}

?>