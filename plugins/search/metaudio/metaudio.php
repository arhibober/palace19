<?php
/**
* @file
* @brief    metaudio audio and music library search plug-in
* @author   Levente Hunyadi
* @version  0.8.0
* @remarks  Copyright (C) 2010-2011 Levente Hunyadi
* @remarks  Licensed under GNU/GPLv3, see http://www.gnu.org/licenses/gpl-3.0.html
* @see      http://hunyadi.info.hu/projects/metaudio
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.plugin.plugin');

/**
 * metaudio search plugin.
 */
class metaudioSearch {
	function __construct($params) {
		$this->limit = $params->get('search_limit', 50);
	}

	/**
	* Metadata search method.
	* The SQL must return the following fields that are used in a common display
	* routine: href, title, section, created, text, browsernav
	* @param string Target search string
	* @param string mathcing option, exact|any|all
	* @param string ordering option, newest|oldest|popular|alpha|category
	* @param mixed An array if the search it to be restricted to areas, null if search all
	*/
	function onContentSearch($text, $phrase = '', $ordering = '', $areas = null) {
		// skip if not searching inside audio metadata
		if (is_array($areas)) {
			if (!array_intersect($areas, array_keys(self::onContentSearchAreas()))) {
				return array();
			}
		}
		
		// skip if search phrase is empty
		$text = trim($text);
		if ($text == '') {
			return array();
		}

		// build SQL WHERE clause
		switch ($phrase) {
			case 'all':
			case 'any':
				$text = preg_replace('#\s+#', ' ', $text);  // collapse multiple spaces
				$words = explode(' ', $text);
				$whereFile = self::getSearchWhereClause($words, 'file.filename', $phrase);
				$whereData = self::getSearchWhereClause($words, 'data.textvalue', $phrase);
				break;
			case 'exact':
			default:
				$whereFile = self::getSearchWhereClause($text, 'file.filename');
				$whereData = self::getSearchWhereClause($text, 'data.textvalue');
		}

		// build SQL ORDER BY clause
		$orderby = '';
		switch ($ordering) {
			case 'oldest':
				$orderby = 'file.filetime ASC';
				break;
			case 'newest':
				$orderby = 'file.filetime DESC';
				break;
			case 'category':
				$orderby = 'folder.folderpath, artist';
				break;
			case 'alpha':
			case 'popular':  // ignored
			default:
				$orderby = 'artist, title, file.filename';
				break;
		}

		// build database query
		$query = "
			SELECT
				folder.folderpath,
				file.filename,
				file.filetime,
				( SELECT data.textvalue FROM #__metaudio_data AS data INNER JOIN #__metaudio_property AS prop ON data.propertyid = prop.propertyid WHERE data.fileid = file.fileid AND prop.propertyname = 'Artist' ) AS artist,
				( SELECT data.textvalue FROM #__metaudio_data AS data INNER JOIN #__metaudio_property AS prop ON data.propertyid = prop.propertyid WHERE data.fileid = file.fileid AND prop.propertyname = 'Title' ) AS title,
				( SELECT data.textvalue FROM #__metaudio_data AS data INNER JOIN #__metaudio_property AS prop ON data.propertyid = prop.propertyid WHERE data.fileid = file.fileid AND prop.propertyname = 'Description' ) AS description,
				( SELECT data.textvalue FROM #__metaudio_data AS data INNER JOIN #__metaudio_property AS prop ON data.propertyid = prop.propertyid WHERE data.fileid = file.fileid AND prop.propertyname = 'Comment' ) AS comment
			FROM #__metaudio_file AS file INNER JOIN #__metaudio_folder AS folder ON file.folderid = folder.folderid
			WHERE {$whereFile} OR EXISTS (
				SELECT *
				FROM #__metaudio_data AS data INNER JOIN #__metaudio_property AS prop ON data.propertyid = prop.propertyid
				WHERE data.fileid = file.fileid AND {$whereData}
			)
			ORDER BY {$orderby}
		";
		$db = JFactory::getDbo();
		$db->setQuery($query, 0, $this->limit);
		$rows = $db->loadAssocList();

		// fetch database results
		$results = array();
		if ($rows) {
			foreach($rows as $row) {
				// set audio file artist and/or title
				if (isset($row['artist']) && isset($row['title'])) {
					$title = $row['artist'].': '.$row['title'];
				} elseif (isset($row['title'])) {
					$title = $row['title'];
				} else {
					$title = $row['filename'];
				}

				// set item details
				if (isset($row['description']) && isset($row['comment'])) {
					$description = $row['description']."\n".$row['comment'];
				} elseif (isset($row['description'])) {
					$description = $row['description'];
				} elseif (isset($row['comment'])) {
					$description = $row['comment'];
				} else {
					$description = '';
				}

				$results[] = (object) array(
					'href'        => 'index.php?option=com_metaudio&controller=recording&f='.$row['folderpath'].'/'.$row['filename'],
					'title'       => htmlspecialchars($title),
					'section'     => 'metaudio',
					'created'     => $row['filetime'],
					'text'        => nl2br(htmlspecialchars($description)),
					'browsernav'  => '1'
				);
			}
		}
		return $results;
	}

	/**
	 * @return {array} An array of search areas.
	 */
	static function& onContentSearchAreas() {
		static $areas = array(
			'metaudio' => 'metaudio'
		);
		return $areas;
	}

	/**
	* Builds an SQL WHERE clause from components.
	* @param {array} $words A list of words used to build the clause.
	* @param {string} $column The database column to compare against.
	* @param {string} $phrase 'all', 'any' or empty.
	*/
	private static function getSearchWhereClause($words, $column, $phrase = '') {
		if (!is_array($words)) {
			$words = array($words);
		}

		$db = JFactory::getDbo();
		$parts = array();
		foreach ($words as $word) {
			$parts[] = $column.' LIKE '.$db->quote('%'.$db->escape($word, true).'%', false);
		}
		return '(' . implode(') ' . ($phrase == 'all' ? 'AND' : 'OR') . ' (' , $parts) . ')';
	}
}

if (version_compare(JVERSION, '1.6') >= 0) {  // Joomla 1.6.x
	class plgSearchMetAudio extends JPlugin {
		private $adaptee;

		function __construct( &$subject, $config ) {
			parent::__construct( $subject, $config );
			$this->adaptee = new metaudioSearch($this->params);
		}

		function onContentSearch($text, $phrase='', $ordering='', $areas=null) {
			return $this->adaptee->onContentSearch($text, $phrase, $ordering, $areas);
		}

		function onContentSearchAreas() {
			return $this->adaptee->onContentSearchAreas();
		}
	}
} else {  // Joomla 1.5.x
	JPlugin::loadLanguage('plg_search_metaudio');

	function plgSearchMetaudio($text, $phrase='', $ordering='', $areas=null) {
		$plugin =& JPluginHelper::getPlugin('search', 'metaudio');
		$params = new JParameter($plugin->params);
		$search = new metaudioSearch($params);
		return $search->onContentSearch($text, $phrase, $ordering, $areas);
	}

	function& plgSearchMetaudioAreas() {
		return metaudioSearch::onContentSearchAreas();
	}

	$mainframe->registerEvent('onSearch', 'plgSearchMetaudio');
	$mainframe->registerEvent('onSearchAreas', 'plgSearchMetaudioAreas');
}
