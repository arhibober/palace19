<?php
/**
* @version		$Id: mod_wimpy.php 10381 2008-06-01 03:35:53Z pasamio $
* @package		Joomla
* @copyright	Copyright (C) 2006 - 2008 Plaino. All rights reserved.
* @license		GNU/GPL
* Wimpy module is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See mod_wimpy.xml for copyright notices and details.
**/
/////////////////////////////
//
//
// Wimpy MP3 Player Joomla Module
// © 2007 Plaino
// v 2.2
// Available at www.wimpyplayer.com
//
//
/////////////////////////////

// no direct access
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

global $mainframe;
$wimpyHTML = $mainframe->getBasePath( 0, true )."wimpy/myWimpy.html";

function get_data($url) {
  $ch = curl_init();
  $timeout = 5;
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}


function _iscurlinstalled() {
	if  (in_array  ('curl', @get_loaded_extensions())) {
		return true;
	}
	else{
		return false;
	}
}



$data = @file_get_contents($wimpyHTML);

if(!$data || $data == ""){
	if(@_iscurlinstalled()){
		$data = @get_data($wimpyHTML);
	}
}


if($data && $data != ""){
//if($data = @file_get_contents($wimpyHTML)){
	$AwimpyA = explode("<!-- START WIMPY PLAYER CODE -->", $data);
	$AwimpyB = explode("<!-- END WIMPY PLAYER CODE -->", $AwimpyA[1]);
	$wimpyCode = "\n\n<!-- START WIMPY PLAYER CODE -->\n\n".$AwimpyB[0]."\n\n<!-- END WIMPY PLAYER CODE -->\n\n";
	$content = $wimpyCode;
} else {
	$content = '<p><b>Could not locate: '.$wimpyHTML.'</b></p>';
	$content .= '<p>Be sure to use the Customizer tool at wimpyplayer.com and ';
	$content .= 'upload the resulting "myWimpy.html" file to a folder ';
	$content .= 'named "wimpy" in your Joomla installation directory. ';
	$content .= 'Also, the HTML code that the Customizer tool outputs must ';
	$content .= 'not be altered, the file must be named "myWimpy.html" (case sensitive), ';
	$content .= 'and the file must be located here: ' . $wimpyHTML . '</p>';
}

 ?>