<?php
/**
 * 			Mod Template Selector
 * @version	 	1.2.3
 * @package		Template Selector
 * @copyright		Copyright (C) 2007-2012 Yoshiki Kozaki(www.joomler.net) All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 * @author		Yoshiki Kozaki(www.joomler.net) info@joomler.net
 * @link 			http://www.joomler.net/
 */

/**
* @package		Joomla
* @copyright		Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).DS.'helper.php');
$lists = modTemplateSelector::getLists($params);

if(count($lists) < 1){
	return;
}

$moduleclass_sfx = $params->get('moduleclass_sfx');

require(JModuleHelper::getLayoutPath('mod_templateselector', $params->get('layout', 'default')));
