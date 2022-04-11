<?php
/**
 * @package Module Random Article for Joomla! 2.5+
 * @version $Id: mod_random-article.php 49 2012-12-07 22:16:24Z artur.ze.alves@gmail.com $
 * @author Artur Alves
 * @copyright (C) 2012 - Artur Alves
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
 
// no direct access
defined('_JEXEC') or die('Restricted access');
 
// Fix for Joomla 3
if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);

require_once(dirname(__FILE__).DS.'helper.php');

$language = JFactory::getLanguage();
$language->load('mod_random-article');

if($params->get('logfile'))
	modRandomArticleHelper::logThis(1, print_r($params, true));

$addCurrentID = false;
if($params->get('itemid'))
	$addCurrentID = true;

$articles = modRandomArticleHelper::getArticles($params);
if($articles > 0) { 
	$i = 0;
	foreach($articles as $article) {
		$urls[$i] = modRandomArticleHelper::getUrl($article, $addCurrentID);
		
		if($params->get('logfile')) {
			modRandomArticleHelper::logThis(2, print_r($article, true));
			modRandomArticleHelper::logThis(3, print_r($urls[$i], true));
		}
		
		$i++;
	}
}

require(JModuleHelper::getLayoutPath('mod_random-article'));
?>