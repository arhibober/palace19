<?php
/**
 * @version		1.0
 * @Project		SCM Music Player
 * @author 		Omn Hamilton, smotip.com Development Team
 * @package		
 * @copyright	Copyright (C) 2012 smotip.com. All rights reserved.
 * @license 	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin');

class plgSystemscmmusicplayer extends JPlugin
{
	function plgSystemscmmusicplayer(&$subject, $config)
	{
		parent::__construct($subject, $config);
		
 
   
	}
	
	function onAfterRender()
	{
		
		$skin = $this->params->get('skin'); 
		$autostart = $this->params->get('autostart'); 
		$shuffle = $this->params->get('shuffle'); 
		$volume = $this->params->get('volume'); 
		$playlist = $this->params->get('playlist'); 
		$placement = $this->params->get('placement'); 
		$showplaylist = $this->params->get('showplaylist'); 
		$authorLink = $this->params->get('authorLink'); 
		$app = JFactory::getApplication();

		if($skin == '' || $app->isAdmin() === true  || strpos($_SERVER["PHP_SELF"], "index.php") === false)
		{
			return;
		}

		$buffer = JResponse::getBody();

		if ($authorLink == "yes") {
			$plugin_scm_music_player_javascript = "<!--SCM Music Player by Adrian C Shum - http://scmplayer.net--> \n";
			$plugin_scm_music_player_javascript .= "<script type=\"text/javascript\" src=\"http://scmplayer.net/script.js\" ></script> \n";
			$plugin_scm_music_player_javascript .= "<script type=\"text/javascript\">   \n";
			$plugin_scm_music_player_javascript .= "SCMMusicPlayer.init(\"{'skin':'$skin','playback':{'autostart':'$autostart','shuffle':'$shuffle','volume':'$volume'},'playlist':'$playlist','placement':'$placement','showplaylist':'$showplaylist'}\");   \n";
			$plugin_scm_music_player_javascript .= "</script>  \n";
			$plugin_scm_music_player_javascript .= "<!--End of SCM Music Player script-->";
			$plugin_scm_music_player_javascript .= '<div align="right"><a style="text-decoration:none" href="http://www.smotip.com" title="This SCM Music Player for Joomla Plugin is authored by smotip.com" target="_blank">+</a></div>'. "\n";			
		} else {
			$plugin_scm_music_player_javascript = "<!--SCM Music Player by Adrian C Shum - http://scmplayer.net--> \n";
			$plugin_scm_music_player_javascript .= "<script type=\"text/javascript\" src=\"http://scmplayer.net/script.js\" ></script> \n";
			$plugin_scm_music_player_javascript .= "<script type=\"text/javascript\">   \n";
			$plugin_scm_music_player_javascript .= "SCMMusicPlayer.init(\"{'skin':'$skin','playback':{'autostart':'$autostart','shuffle':'$shuffle','volume':'$volume'},'playlist':'$playlist','placement':'$placement','showplaylist':'$showplaylist'}\");   \n";
			$plugin_scm_music_player_javascript .= "</script>  \n";
			$plugin_scm_music_player_javascript .= "<!--End of SCM Music Player script-->";
		}
		$pos = strrpos($buffer, "</body>");
		
		
		if($pos > 0)
		{
			$buffer = substr($buffer, 0, $pos).$plugin_scm_music_player_javascript.substr($buffer, $pos);

			JResponse::setBody($buffer);
		}
		
		return true;
	}
}
?>
