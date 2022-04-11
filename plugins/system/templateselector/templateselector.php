<?php
/**
 * 			plg System Template Selector
 * @version	 	1.8.0
 * @package		Template Selector
 * @copyright		Copyright (C) 2007 - 2012 Yoshiki Kozaki All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 * @author		Yoshiki Kozaki info@joomler.net
 * @link 			http://www.joomler.net/
 */

/**
* @package		Joomla
* @copyright		Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.plugin.plugin' );

class  plgSystemTemplateSelector extends JPlugin
{
	public function onAfterInitialise()
	{
		$app = JFactory::getApplication();

		if($app->isAdmin()){
			return;
		}

		$cookieValue = JRequest::getVar('jTemplateSelector', '', 'cookie', 'int');

		$template_style_id = $app->input->getInt('templatedirectory', $cookieValue);

		if($template_style_id < 1){
			return;
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->qn('template'));
		$query->select($db->qn('params'));
		$query->from($db->qn('#__template_styles'));
		$query->where($db->qn('client_id'). ' = 0');
		$query->where($db->qn('id'). ' = '. (int)$template_style_id);
		$query->order($db->qn('id'));
		$db->setQuery( $query );
		$row = $db->loadObject();
		if(!$row || empty($row->template)){
			return;
		}

		class_exists('modTemplateSelector')
			or require(JPATH_SITE.DS.'modules'.DS.'mod_templateselector'.DS.'helper.php');
		$current = modTemplateSelector::getCurrentTemplate();

		if($current != $template_style_id && is_dir(JPATH_THEMES. DS. $row->template)){
			$app->setTemplate($row->template, (new JRegistry($row->params)));
		}
	}
}