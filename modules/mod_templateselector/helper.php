<?php
/**
 * 			Mod Template Selector Helper
 * @version	 	1.8.0
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

class modTemplateSelector
{
	public static function getLists($params)
	{
		$app = JFactory::getApplication();

		$lists = array();

		if($app->isAdmin()){
			return $lists;
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->qn('id'). ' AS value');
		$query->select($db->qn('title'). ' AS text');
		$query->select($db->qn('template'));
		$query->from($db->qn('#__template_styles'));
		$query->where($db->qn('client_id'). ' = 0');
		$query->order($db->qn('id'). ' ASC');

		$db->setQuery($query);
		$rows = $db->loadObjectList();

		if(count($rows) < 1){
			return $lists;
		}

		$selected = JRequest::getVar('jTemplateSelector', '', 'cookie', 'int');
		$style_id = $app->input->getInt('templatedirectory', $selected);

		if($selected != $style_id){
			setcookie('jTemplateSelector', $style_id
					, time()+intval($params->get('duration', 365))*86400);
			$app->redirect(JURI::current());
		}

		if($selected < 1){
			$selected = self::getCurrentTemplate();
		}

		$listSelected = $params->get('templates', array());
		if(!is_array($listSelected)){
			$listSelected = array($listSelected);
		}

		JArrayHelper::toInteger($listSelected);
		$listSelected = array_unique($listSelected);
		if(count($listSelected) == 1 && $listSelected[0] == 0){
			$listSelected = array();
		}

		$options = array();
		$templates = array();

		$listLayout = (strpos($params->get('layout', 'default'), 'list') != false);

		foreach($rows as $row)  {
			if(in_array($row->value, $listSelected) || count($listSelected) < 1){
				if($listLayout){
					$row->current = false;
					if($row->value == $selected){
						$row->current = true;
					}
					$templates[] = $row;
					continue;
				}
				//remove
//				$row->text = JText::_(str_replace(array(' '), '_', $row->text));
				$templates[$row->value] = $row->template;
				$options[] = $row;
			}
		}

		$jsoptions = array();

		JHtml::_('behavior.framework');
		JHtml::_('script', 'templateselector.js', 'modules/mod_templateselector/assets/');

		if($listLayout){
			$jsoptions['duration'] = intval($params->get('duration', 365));
			$jsoptions = json_encode($jsoptions);

			$javascript = "window.addEvent('domready', function(){var jTSelector = new jTemplateSelector($jsoptions);});";
			JFactory::getDocument()->addScriptDeclaration($javascript);
			return $templates;
		}

		$template = $app->getTemplate();
		$lists['current_image'] = JURI::base(true). '/templates/'. $template. '/template_thumbnail.png';

		$jsoptions['templates'] = $templates;
		$jsoptions['base'] = JURI::base(true). '/templates/';
		$jsoptions['duration'] = intval($params->get('duration', 365));
		$jsoptions['selected'] = $selected;
		$jsoptions = json_encode($jsoptions);

		$javascript = "window.addEvent('domready', function(){new jTemplateSelector($jsoptions);});";

		JFactory::getDocument()->addScriptDeclaration($javascript);

		$lists['list'] = JHTML::_('select.genericlist', $options, 'tmpldirectory', array(
			'id' => 'jTmplDirectories',
			'list.attr' => 'class="inputbox"',
			'list.select' => $selected)
		);

		$lists['selected'] = $selected;

		return $lists;
	}

	public static function getCurrentTemplate()
	{
		$app = JFactory::getApplication();
		$menus = $app->getMenu('site');
		$menu = $menus->getActive();
		if($menu){
			$template_style_id = (int)$menu->params->get('template_style_id');
			if($template_style_id > 0){
				return $template_style_id;
			}
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->qn('id'));
		$query->from($db->qn('#__template_styles'));
		$query->where($db->qn('client_id'). ' = 0');
		$query->where($db->qn('home'). ' = 1');
		$db->setQuery( $query );
		return intval( $db->loadResult() );
	}
}
