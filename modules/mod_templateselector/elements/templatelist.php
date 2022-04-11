<?php
/**
 * @version		$Id: Templatelist.php 12345 2011-01-28 00:00:00Z kozaki $
 * @package		Template Selector
 * @subpackage	Parameter
 * @copyright		Copyright (C) 2007 - 2011 Joomler!.net. All rights reserved.
 * @license		GNU General Public License version 2 or later
 */

// No direct access.
defined('JPATH_BASE') or die;

/**
 * Renders a filelist element
 *
 * @package		Joomla.Framework
 * @subpackage	Parameter
 * @since		1.5
 */

class JFormFieldTemplatelist extends JFormField
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	protected $type = 'Templatelist';

	protected function getInput()
	{
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

		$this->multiple = true;

		return JHtml::_('select.genericlist', $rows, $this->getName($this->fieldname),
			array(
				'id' => $this->id,
				'list.attr' => 'class="inputbox" multiple="multiple" size="'. count($rows). '"',
				'list.select' => $this->value
			)
		);
	}
}