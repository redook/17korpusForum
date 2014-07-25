<?php
/**
*
* @package phpBB3
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

class characters_const
{

	public static $classes = array(
			'commando' => 'Commando',
			'gunslinger' => 'Gunslinger',
			'guardian' => 'Jedi Guardian',
			'sage' => 'Jedi Sage',
			'sentinel' => 'Jedi Sentinel',
			'shadow' => 'Jedi Shadow',
			'scoundrel' => 'Scoundrel',
			'vanguard' => 'Vanguard',
			
			'mercenary' => 'Mercenary',
			'operative' => 'Operative',
			'powertech' => 'Powertech',
			'assasin' => 'Sith Assasin',
			'juggernaut' => 'Sith Juggernaut',
			'marauder' => 'Sith Marauder',
			'sorcerer' => 'Sith Sorcerer',
			'sniper' => 'Sniper',
			
			'disabled' => '---',
			
			'consular' => 'Jedi Consular',
			'knight' => 'Jedi Knight',
			'smuggler' => 'Smuggler',
			'trooper' => 'Trooper',

			'bountyhunter' => 'Bounty Hunter',
			'agent' => 'Imperial Agent',
			'inquisitor' => 'Sith Inquisitor',
			'warrior' => 'Sith Warrior',
		);
	
	public static $classesRep = array(
			'commando' => 'Commando',
			'gunslinger' => 'Gunslinger',
			'guardian' => 'Jedi Guardian',
			'sage' => 'Jedi Sage',
			'sentinel' => 'Jedi Sentinel',
			'shadow' => 'Jedi Shadow',
			'scoundrel' => 'Scoundrel',
			'vanguard' => 'Vanguard',
		);
		
	public static $classesRepBasic = array(
			'consular' => 'Jedi Consular',
			'knight' => 'Jedi Knight',
			'smuggler' => 'Smuggler',
			'trooper' => 'Trooper',
		);
		
	public static $classesImp = array(
			'mercenary' => 'Mercenary',
			'operative' => 'Operative',
			'powertech' => 'Powertech',
			'assasin' => 'Sith Assasin',
			'juggernaut' => 'Sith Juggernaut',
			'marauder' => 'Sith Marauder',
			'sorcerer' => 'Sith Sorcerer',
			'sniper' => 'Sniper',
		);
		
	public static $classesImpBasic = array(
			'bountyhunter' => 'Bounty Hunter',
			'agent' => 'Imperial Agent',
			'inquisitor' => 'Sith Inquisitor',
			'warrior' => 'Sith Warrior',
		);

	public static function classes(){
		return self::$classesRep + self::$classesImp + array('disabled' => '---') + self::$classesRepBasic + self::$classesImpBasic;
	}
		
	public static $roles = array(
			'tank' => 'Tank',
			'heal' => 'Healer',
			'dps' => 'DPS',
		);
	
	public static $levels = array(
			'30' => '1-30',
			'49' => '31-49',
			'54' => '50-54',
			'55' => '55',
	);
	
	public static $species = array(
			'chiss'  => 'Chiss',
			'cyborg' => 'Cyborg',
			'human' => 'Human',
			'miraluka' => 'Miraluka',
			'mirialan' => 'Mirialan',
			'rattataki' => 'Rattataki',
			'sith' => 'Sith Pureblood',
			'twilek ' => 'Twi\'lek ',
			'zabrak' => 'Zabrak',
			'cathar' => 'Cathar',
	);

}
?>