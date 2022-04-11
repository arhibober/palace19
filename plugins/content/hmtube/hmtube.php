<?php

/*
 * @package Joomla 1.6
 * @copyright Copyright (C) 2011 hypermodern.org. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 *
 * @plugin HM Tube
 * @copyright Copyright (C) Lander Compton www.hypermoern.org
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgContenthmtube extends JPlugin {
	
	
	public function onContentPrepare($context, &$article, &$params, $limitstart) { 
				
		// get the paramters article and context				
		$this->hmtubetube_content( $article, $params, $context);
		$document = &JFactory::getDocument();

		//add the jwplayer script to the webpage header
		$document->addScript( '/plugins/content/hmtube/js/jwplayer.js' );

	} // end function
	
	
	public function hmoverrides($overridesraw) {
		
		// Declare the variables global in case an override is needed.
		global $hmHD, $hmwidth, $hmheght, $hmcontrolbar, $hmskin, $hmcontrolbarhide, $hmstretching, $hmautostart, $hmnoparse;
		
		// Find the [hmyt] tag parameters then put them into array $overrides/
		$regex2 = '/\S*\s?=\s?\"\S*\"/i';
		preg_match_all ( $regex2, $overridesraw, $overrides );
		
		
		unset($keyvalues);
		$keyvalues = array();
		
		// Process the override parameters and put thier values in their respective variables.
		for($x=0; $x<count($overrides[0]); $x++) {
			
			// If there are any spaces or quotes, remove them.
			$overrides[0][$x] = str_replace(' ', '',$overrides[0][$x]);
			$overrides[0][$x] = str_replace("\"", '',$overrides[0][$x]);
			$split = '/=/';
		
			// Split the parameters by =, and place them into key/value pairs.	
			$keyvalue = preg_split($split, $overrides[0][$x]);
			array_push($keyvalues, $keyvalue);
			
			if ($keyvalue[0] == 'hd') 				{ $hmHD = $keyvalue[1];}
			if ($keyvalue[0] == 'height') 			{ $hmheight = $keyvalue[1];}
			if ($keyvalue[0] == 'width') 			{ $hmwidth = $keyvalue[1];}
			if ($keyvalue[0] == 'controlbar') 		{ $hmcontrolbar = $keyvalue[1];}
			
			if ($keyvalue[0] == 'skin') 			{ $hmskin = $keyvalue[1];}
			if ($keyvalue[0] == 'controlbarhide') 	{ $hmcontrolbarhide = $keyvalue[1];}
			if ($keyvalue[0] == 'autostart') 		{ $hmautostart = $keyvalue[1];}
			if ($keyvalue[0] == 'stretching') 		{ $hmstretching = $keyvalue[1];}
			if ($keyvalue[0] == 'noparse') 			{ $hmnoparse = $keyvalue[1];}
			
			
		} // end for loop
		//print_r($keyvalues); echo "<br>";
		//echo "$hmHD $hmwidth $hmheight";
		
	return;
		
	} // end function
	
	
	public function hmtubetube_content( &$article, &$params, $context = 'com_content.article') {
		
		//devlare globals so the other functions can override
		global $hmHD, $hmwidth, $hmheght, $hmcontrolbar, $hmskin, $hmcontrolbarhide, $hmstretching, $hmautostart, $hmnoparse;
		

		
		//turn HD on or off
		if ($hmHD == 'true') {$hmHD = 'hd-1';}
			
		else {$hmHD = "";}
		
		// example match: [hmyt]http://www.youtube.com/watch?v=Out4wtHLFBE[/hmyt]
		$regex =	'/(\[hmyt(.*)\](http:\/\/www.youtube.com\/watch\?v=)([\S]{11})\[\/hmyt\])/i';
		
		// Search out all tags and put them into array $matcheshm.
		preg_match_all( $regex, $article->text, $matcheshm );
		
		// Counter used to incrementally change div id mediaspace. This allows the program
		// to have more than one video per article. Two of the same youtube videos still cannot be used in the same article.
		$mcnt = 0;
		
		//process the matches
		for($x=0; $x<count($matcheshm[0]); $x++) {
				
			$hmwidth = $this->params->get( 'hmwidth',425);
			$hmheight = $this->params->get( 'hmheight',375);
			$hmskin = $this->params->get( 'hmskin','slim');
			$hmcontrolbar = $this->params->get( 'hmcontrolbar','bottom');
			$hmcontrolbarhide = $this->params->get( 'hmcontrolbarhide','false');
			$hmautostart = $this->params->get( 'hmautostart','false');
			$hmstretching = $this->params->get( 'stretching','uniform');
			$hmHD = $this->params->get( 'hmHD','true');
			$hmnoparse = '';
			$vid= $matcheshm[4][$x];
			
			// Ff the hmyt tag has args to override, then go to function hmoverrides for processing
			if ($matcheshm[2][$x] != '') {
					$overrides=$this->hmoverrides($matcheshm[2][$x]);
				
			}
		// The below comment is a test for the overrides.
		//echo "<br>$hmHD $hmwidth $hmheight $hmskin $hmcontrolbar $hmstretching $hmautostart";
		
			// the embed code for the jw player
			
			if ($hmnoparse == "true") {
				
				// $replace = $matcheshm[0][$x];
				//   noparse\s?=\s?\"\S*\"
				
				$hmargs = $matcheshm[2][$x];
				$noparseremove = '/noparse\s?=\s?\"\S*\"/i';
				$hmargs = preg_replace('/noparse\s?=\s?\"\S*\"/', '', $hmargs);
				$replace = '[hmyt'  . $hmargs  . ']' . $matcheshm[3][$x] . $matcheshm[4][$x] . '[/hmyt]';
				
			}
			else {
			$replace = "<div id='mediaspace". $mcnt . "'>This text will be replaced</div>
		  	<script type='text/javascript'>
				jwplayer('mediaspace" . $mcnt . "').setup({
			  'flashplayer': 			'/plugins/content/hmtube/js/player.swf',
			  'file': 					'http://www.youtube.com/watch?v=$vid',
			  'controlbar': 			'" . $hmcontrolbar . "',
			  'controlbar.idlehide': 	'" . $hmcontrolbarhide . "',
			  'width': 					'" . $hmwidth . "',
			  'height': 				'" . $hmheight . "',
			  'skin': 					'/plugins/content/hmtube/skin/" . $hmskin . ".zip', 
			  'autostart':				'" . $hmautostart ."',
			  'stretching': 			'" . $hmstretching . "',
			  'plugins': 				'" . $hmHD . "',
			});
		  </script>
	";
			}
				
		

				// Execute and replace the [hmyt] tags and their contents.
				$article->text = str_replace($matcheshm[1][$x], $replace, $article->text);
				$mcnt = $mcnt + 1;
				
		} // end for loop
	} // end function hmtubetube_content
} // end class
?>