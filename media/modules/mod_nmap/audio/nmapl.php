<?php
/**
 * Pro Magic Audio Player
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
 *@author ProJoom
 *@copyright (C) 2008 - 2009 ProJoom
 *@link http://www.projoom.com/extensions/pro-magic-audio-player.html Official website
 **/
header("content-type: text/xml");
require_once (dirname(__file__) . "/getid/getid3.php");
error_reporting(E_ALL ^ E_NOTICE);
$receive = $_GET['s'];
if(empty($receive)) {
    die('Hello, how are you?');
}
$key = "xCv";
function nmapp_decode($string, $key) {
    $key = sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
    for ($i = 0; $i < $strLen; $i += 2) {
        $ordStr = hexdec(base_convert(strrev(substr($string, $i, 2)), 36, 16));
        if($j == $keyLen) {
            $j = 0;
        }
        $ordKey = ord(substr($key, $j, 1));
        $j++;
        $hash .= chr($ordStr - $ordKey);
    }
    return $hash;
}
function nmapp_getfiles($path, $recurse = false, $fullpath = false) {
    $arr = array();
    if(!is_dir($path)) {
        exit;
    }
    $handle = opendir($path);
    while (($file = readdir($handle)) !== false) {
        if(($file != '.') && ($file != '..')) {
            $dir = $path . '/' . $file;
            $isDir = is_dir($dir);
            if($isDir) {
                if($recurse) {
                    $arr2 = nmapp_getfiles($dir, $recurse, $fullpath);

                    $arr = array_merge($arr, $arr2);
                }
            } else {
                if(preg_match("/.mp3/i", $file)) {
                    if($fullpath) {
                        $arr[] = $path . '/' . $file;
                    } else {
                        $arr[] = $file;
                    }
                }
            }
        }
    }
    closedir($handle);

    asort($arr);
    return $arr;
}
$pset = nmapp_decode($receive, $key);
$combined = explode("|", $pset);
$ntyp = $combined[0];
$nwidth = $combined[1];
$nplay = $combined[2];
$nstyle = $combined[3];
$ndir = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . $combined[4];
$ndir = str_replace('modules/mod_nmap/audio/nmapl.php', '', $ndir);
$ndir2 = dirname(__file__) . $combined[4];
$ndir2 = str_replace('modules/mod_nmap/audio', '', $ndir2);
$ndir2 = str_replace('modules\mod_nmap\audio', '', $ndir2);
$ntrim = $combined[6];
$nrep = $combined[7];
$nshuffle = $combined[8];
$nmacomp = $combined[9];
$nmalsthgt = $combined[10];
$nmascrltit = $combined[11];
$nmapvolhgt = $combined[12];
$nmapsbarwdt = $combined[13];
$nmapflyout = $combined[14];
$disptime = $combined[15];
$nheight = $combined[16];
$parts = explode("*", $combined[5]);
$nrfile = $parts[0];
$nint = $parts[1];
$nstartvol = $parts[2];
$nformat = $parts[3];
$nrecursivity = $parts[4];
if($nrecursivity == 1) {
    $recursivity = true;
    $fullpath = true;
} else {
    $recursivity = false;
    $fullpath = false;
}
$nmapid3 = $parts[5];
$pbgc1 = $combined[17];
$pbgc2 = $combined[18];
$pbga = $combined[19];
$pbgc3 = $combined[20];
$pbgsc1 = $combined[21];
$pbgsc2 = $combined[22];
$pbgsa = $combined[23];
$pbgsc3 = $combined[24];
$bbgc1 = $combined[25];
$bbgc2 = $combined[26];
$bbga = $combined[27];
$bbgc3 = $combined[28];
$bisc1 = $combined[29];
$bisc2 = $combined[30];
$bisa = $combined[31];
$bisc3 = $combined[32];
$bbghc1 = $combined[33];
$bbghc2 = $combined[34];
$bbgha = $combined[35];
$bbghc3 = $combined[36];
$bic1 = $combined[37];
$bia = $combined[38];
$bich = $combined[39];
$bicha = $combined[40];
$globaltextc = $combined[41];
$sbgc1 = $combined[42];
$sbgc2 = $combined[43];
$sbga = $combined[44];
$sfc1 = $combined[45];
$svc2 = $combined[46];
$sfa = $combined[47];
$sspc1 = $combined[48];
$sspc2 = $combined[49];
$sspa = $combined[50];
$sbc1 = $combined[51];
$sbc2 = $combined[52];
$sbc3 = $combined[53];
$vbgc1 = $combined[54];
$vbgc2 = $combined[55];
$vbga = $combined[56];
$vbgc3 = $combined[57];
$vsbgc1 = $combined[58];
$vsbgc2 = $combined[59];
$vsfc1 = $combined[60];
$vsfc2 = $combined[61];
$vsbc1 = $combined[62];
$vsbc2 = $combined[63];
$lbgc1 = $combined[64];
$lbgc2 = $combined[65];
$lbga = $combined[66];
$lbgc3 = $combined[67];
$lsbc1 = $combined[68];
$lsba = $combined[69];
$lsbgc1 = $combined[70];
$lsbga = $combined[71];
$libg = $combined[72];
$libga = $combined[73];
$libgac = $combined[74];
$libgaa = $combined[75];
$roundness = $combined[76];
$nmapflyout = ($nmapflyout == "t") ? "top" : "bottom";
if(empty($nmapsbarwdt)) {
    $plsbwdt = '5';
} else {
    $plsbwdt = $nmapsbarwdt;
}
if(empty($nmalsthgt)) {
    $lstheight = '82';
} else {
    $lstheight = $nmalsthgt;
}
if(empty($nmascrltit)) {
    $titlescroll = '5';
} else {
    $titlescroll = $nmascrltit;
}
if(empty($nmapvolhgt)) {
    $volheight = '40';
} else {
    $volheight = $nmapvolhgt;
}
$omit_chars = $ntrim;
$nmaprep = "false";
$show_titles = true;
$nmaplay = "false";
$nmapcompact = "false";
$nmapdisptime = "false";
if($nstyle == '5') {
    $show_titles = false;
}
if($nplay == '1') {
    $nmaplay = "true";
}
if($nrep == '1') {
    $nmaprep = "true";
}
if($nmacomp == '0') {
    $nmapcompact = "true";
}
if($disptime == '1') {
    $nmapdisptime = "true";
}
$playery = '0';
if($nmapflyout == 'top') {
    $playery = $nheight - 40;
}
if(is_dir($ndir2)) {
    $dir_mode = "true";
} elseif (is_file($ndir2) && substr($ndir2, -3) == "mp3") {
    $dir_mode = "false";
    $nmapcompact = "0";
}
if($nstyle == 'a') {
    $playlist = '<?xml version="1.0" encoding="utf-8"?>
<music
playerMode="' . $ntyp . '"
displayTimeInfo="' . $nmapdisptime . '"
playerWidth="' . $nwidth . '"
playerHeight="40"
playerX="0"
playerY="' . $playery . '"
flyOut="' . $nmapflyout . '"
autoStart="' . $nmaplay . '"
buttonSize="20"
buttonPadding="3"
buttonY="5"
playInSequence="' . $nmaprep . '"
playerVolume="' . $nstartvol . '"
embedFonts="false"
playerBg="282828,000000,100,5,171717,100,1"
playerBgShine="FFFFFF,FFFFFF,10,5,333333,0,1"
buttonBg="444444,000000,100,7,000000,100,1"
buttonInnerStroke="363636,000000,100,7,333333,100,1"
buttonBgHover="0D97F2,075d99,100,7,064B79,100,1"
buttonIcon="888888,100"
buttonIconHover="FFFFFF,100"
displaySongText="Verdana,A4A4A4,100,10,false"
displaySongTimeTotal="Arial,A4A4A4,100,10,false"
displaySongTimeElapsed="Arial,FFFFFF,100,10,true"
displaySongTextY="2"
displaySongTimeY="2"
pauseDisplayFor="2"
songTitleScrollTime="' . $titlescroll . '"
sliderHeight="5"
sliderY="21"
sliderButtonWidth="10"
sliderButtonHeight="10"
sliderBg="000000,333333,100,3,0,0,0"
sliderFill="0D97F2,075d99,100,3,0,0,0"
sliderStramProgress="333333,444444,100,3,0,0,0"
sliderButton="FFFFFF,666666,100,10,333333,100,1"
volumeHeight="' . $nmapvolhgt . '"
volumeBg="222222,000000,100,9,333333,100,1"
volumeSliderBg="333333,444444,100,3,0,0,0"
volumeSliderFill="0D97F2,075d99,100,3,0,0,0"
volumeText="Arial,FFFFFF,100,10,false"
volumeSliderButton="FFFFFF,666666,100,9,333333,100,1"
listOpen="' . $nmapcompact . '"
listHeight="' . $lstheight . '"
listPadding="10"
listScrollbarWidth="' . $plsbwdt . '"
listBg="222222,000000,100,15,333333,100,1"
listScrollbar="666666,100"
listScrollbarBg="000000,100"
listItemHeight="20"
listItemBg="333333,100"
listItemBgActive="075d99,100"
listTitleText="Verdana,999999,100,10,false"
listTitleTextActive="Verdana,FFFFFF,100,10,false"
listTitleScrollTime="' . $titlescroll . '" >' . "\n";
} elseif ($nstyle == 'b') {
    $playlist = '<?xml version="1.0" encoding="utf-8"?>
<music
playerMode="' . $ntyp . '"
displayTimeInfo="' . $nmapdisptime . '"
playerWidth="' . $nwidth . '"
playerHeight="40"
playerX="0"
playerY="' . $playery . '"
flyOut="' . $nmapflyout . '"
autoStart="' . $nmaplay . '"
buttonSize="20"
buttonPadding="3"
buttonY="5"
playInSequence="' . $nmaprep . '"
playerVolume="' . $nstartvol . '"
embedFonts="false"
playerBg="1278DE,1278DE,100,6,999999,100,1"
playerBgShine="ffffff,ffffff,100,12,ffffff,0,1"
buttonBg="ffffff,1278DE,100,6,0022FF,60,1"
buttonInnerStroke="1,0,0,6,FFFFFF,100,1"
buttonBgHover="ffffff,021BBF,100,6,fff315,100,1"
buttonIcon="FFFFFF,100"
buttonIconHover="FFFFFF,100"
displaySongText="Verdana,000000,100,10,false"
displaySongTimeTotal="Arial,00083D,100,10,false"
displaySongTimeElapsed="Arial,00083D,100,10,true"
displaySongTextY="2"
displaySongTimeY="2"
pauseDisplayFor="2"
songTitleScrollTime="' . $titlescroll . '"
sliderHeight="5"
sliderY="21"
sliderButtonWidth="10"
sliderButtonHeight="10"
sliderBg="ffffff,ffffff,100,5,919191,0,1"
sliderFill="0022FF,0022FF,100,5,919191,100,1"
sliderStramProgress="1278DE,1278DE,100,8,333333,0,1"
sliderButton="000000,919191,100,8,333333,70,1"
volumeHeight="' . $nmapvolhgt . '"
volumeBg="1278DE,1278DE,100,9,333333,100,1"
volumeSliderBg="ffffff,FFFFFF,100,3,0,0,0"
volumeSliderFill="0022FF,0022FF,100,3,0,0,0"
volumeText="Arial,FFFFFF,100,10,false"
volumeSliderButton="000000,919191,100,9,333333,100,1"
listOpen="' . $nmapcompact . '"
listHeight="' . $lstheight . '"
listPadding="10"
listScrollbarWidth="' . $plsbwdt . '"
listBg="1278DE,1578DE,100,15,1278DE,100,1"
listScrollbar="0058A6,100"
listScrollbarBg="ffffff,100"
listItemHeight="20"
listItemBg="FFFFFF,70"
listItemBgActive="57AEFA,100"
listTitleText="Verdana,000000,100,10,false"
listTitleTextActive="Verdana,ffffff,100,10,false"
listTitleScrollTime="' . $titlescroll . '" >' . "\n";
} elseif ($nstyle == 'c') {
    $playlist = '<?xml version="1.0" encoding="utf-8"?>
<music
playerMode="' . $ntyp . '"
displayTimeInfo="' . $nmapdisptime . '"
playerWidth="' . $nwidth . '"
playerHeight="40"
playerX="0"
playerY="' . $playery . '"
flyOut="' . $nmapflyout . '"
autoStart="' . $nmaplay . '"
buttonSize="20"
buttonPadding="3"
buttonY="5"
playInSequence="' . $nmaprep . '"
playerVolume="' . $nstartvol . '"
embedFonts="false"
playerBg="DE0004,DE0004,100,6,ffffff,100,1"
playerBgShine="ffffff,ffffff,100,12,ffffff,0,1"
buttonBg="ffffff,DE0004,100,6,DE0004,60,1"
buttonInnerStroke="1,0,0,6,FFFFFF,100,1"
buttonBgHover="ffffff,750002,100,6,DE0004,100,1"
buttonIcon="FFFFFF,100"
buttonIconHover="FFFFFF,100"
displaySongText="Verdana,000000,100,10,false"
displaySongTimeTotal="Arial,00083D,100,10,false"
displaySongTimeElapsed="Arial,00083D,100,10,true"
displaySongTextY="2"
displaySongTimeY="2"
pauseDisplayFor="2"
songTitleScrollTime="' . $titlescroll . '"
sliderHeight="5"
sliderY="21"
sliderButtonWidth="10"
sliderButtonHeight="10"
sliderBg="ffffff,ffffff,100,5,919191,0,1"
sliderFill="9C0003,9C0003,100,9,9C0003,100,1"
sliderStramProgress="1278DE,1278DE,100,8,333333,0,1"
sliderButton="000000,919191,100,8,333333,70,1"
volumeHeight="' . $nmapvolhgt . '"
volumeBg="DE0004,DE0004,100,9,333333,100,1"
volumeSliderBg="ffffff,FFFFFF,100,3,0,0,0"
volumeSliderFill="690204,690204,100,3,0,0,0"
volumeText="Arial,FFFFFF,100,10,false"
volumeSliderButton="000000,919191,100,9,333333,100,1"
listOpen="' . $nmapcompact . '"
listHeight="' . $lstheight . '"
listPadding="10"
listScrollbarWidth="' . $plsbwdt . '"
listBg="DE0004,FF7578,100,15,ffffff,100,1"
listScrollbar="690204,100"
listScrollbarBg="ffffff,100"
listItemHeight="20"
listItemBg="FFFFFF,70"
listItemBgActive="DE0004,100"
listTitleText="Verdana,000000,100,10,false"
listTitleTextActive="Verdana,ffffff,100,10,false"
listTitleScrollTime="' . $titlescroll . '" >' . "\n";
} elseif ($nstyle == 'd') {
    $playlist = '<?xml version="1.0" encoding="utf-8"?>
<music
playerMode="' . $ntyp . '"
displayTimeInfo="' . $nmapdisptime . '"
playerWidth="' . $nwidth . '"
playerHeight="40"
playerX="0"
playerY="' . $playery . '"
flyOut="' . $nmapflyout . '"
autoStart="' . $nmaplay . '"
buttonSize="20"
buttonPadding="3"
buttonY="5"
playInSequence="' . $nmaprep . '"
playerVolume="' . $nstartvol . '"
embedFonts="false"
playerBg="282828,000000,100,0,333333,100,1"
playerBgShine="FFFFFF,FFFFFF,0,0,333333,0,1"
buttonBg="222222,000000,100,0,000000,100,1"
buttonInnerStroke="363636,000000,100,0,333333,100,1"
buttonBgHover="0D97F2,075d99,100,0,064B79,100,1"
buttonIcon="888888,100"
buttonIconHover="FFFFFF,100"
displaySongText="Verdana,FFFFFF,100,10,false"
displaySongTimeTotal="Arial,FFFFFF,100,10,false"
displaySongTimeElapsed="Arial,FFFFFF,100,10,true"
displaySongTextY="2"
displaySongTimeY="2"
pauseDisplayFor="2"
songTitleScrollTime="' . $titlescroll . '"
sliderHeight="5"
sliderY="21"
sliderButtonWidth="10"
sliderButtonHeight="10"
sliderBg="000000,333333,100,0,0,0,0"
sliderFill="0D97F2,075d99,100,0,0,0,0"
sliderStramProgress="333333,444444,100,0,0,0,0"
sliderButton="FFFFFF,666666,0,0,333333,0,1"
volumeHeight="' . $nmapvolhgt . '"
volumeBg="222222,000000,100,0,333333,100,1"
volumeSliderBg="333333,444444,100,0,0,0,0"
volumeSliderFill="0D97F2,075d99,100,0,0,0,0"
volumeText="Arial,FFFFFF,100,10,false"
volumeSliderButton="FFFFFF,666666,0,9,0,0,1"
listOpen="' . $nmapcompact . '"
listHeight="' . $lstheight . '"
listPadding="10"
listScrollbarWidth="' . $plsbwdt . '"	
listBg="222222,000000,100,0,333333,100,1"
listScrollbar="666666,100"
listScrollbarBg="000000,100"
listItemHeight="20"
listItemBg="333333,100"
listItemBgActive="075d99,100"
listTitleText="Verdana,999999,100,10,false"
listTitleTextActive="Verdana,FFFFFF,100,10,false"
listTitleScrollTime="' . $titlescroll . '" >' . "\n";
} elseif ($nstyle == 'e') {
    $playlist = '<?xml version="1.0" encoding="utf-8"?>
<music
playerMode="' . $ntyp . '"
displayTimeInfo="' . $nmapdisptime . '"
playerWidth="' . $nwidth . '"
playerHeight="40"
playerX="0"
playerY="' . $playery . '"
flyOut="' . $nmapflyout . '"
autoStart="' . $nmaplay . '"
buttonSize="20"
buttonPadding="2"
buttonY="5"
playInSequence="' . $nmaprep . '"
playerVolume="' . $nstartvol . '"
embedFonts="false"
playerBg="83abc0,495e6c,100,25,899aa5,100,1"
playerBgShine="FFFFFF,FFFFFF,0,25,333333,0,1"
buttonBg="b1e0f8,407194,100,30,28546b,100,1"
buttonInnerStroke="363636,000000,100,30,b1e0f8,100,1"
buttonBgHover="407194,b1e0f8,100,30,b1e0f8,100,1"
buttonIcon="FFFFFF,100"
buttonIconHover="FFFFFF,100"	
displaySongText="Century Gothic,FFFFFF,100,13,false"
displaySongTimeTotal="Century Gothic,FFFFFF,100,10,false"
displaySongTimeElapsed="Century Gothic,FFFFFF,100,15,false"	
displaySongTextY="0"
displaySongTimeY="0"
pauseDisplayFor="2"
songTitleScrollTime="' . $titlescroll . '"
sliderHeight="5"
sliderY="21"
sliderButtonWidth="10"
sliderButtonHeight="10"
sliderBg="000000,333333,10,10,0,0,0"
sliderFill="FFFFFF,9fabb4,100,10,0,0,0"
sliderStramProgress="495e6c,83abc0,100,10,0,0,0"
sliderButton="b1e0f8,407194,100,10,b1e0f8,100,1"
volumeHeight="' . $nmapvolhgt . '"
volumeBg="83abc0,495e6c,100,30,899aa5,100,1"
volumeSliderBg="495e6c,83abc0,100,10,0,0,0"
volumeSliderFill="FFFFFF,9fabb4,100,10,0,0,0"
volumeText="Arial,FFFFFF,100,10,false"
volumeSliderButton="b1e0f8,407194,100,10,b1e0f8,100,1"
listOpen="' . $nmapcompact . '"
listHeight="' . $lstheight . '"
listPadding="10"
listScrollbarWidth="' . $plsbwdt . '"
listBg="83abc0,577181,100,30,899aa5,100,1"
listScrollbar="def1ff,100"
listScrollbarBg="000000,10"
listItemHeight="20"
listItemBg="333333,10"
listItemBgActive="83abc0,100"
listTitleText="Verdana,83abc0,100,10,false"
listTitleTextActive="Verdana,FFFFFF,100,10,false"
listTitleScrollTime="' . $titlescroll . '" >' . "\n";
} elseif ($nstyle == 'f') {
    $playlist = '<?xml version="1.0" encoding="utf-8"?>
<music
playerMode="' . $ntyp . '"
displayTimeInfo="' . $nmapdisptime . '"
playerWidth="' . $nwidth . '"
playerHeight="40"
playerX="0"
playerY="' . $playery . '"
flyOut="' . $nmapflyout . '"
autoStart="' . $nmaplay . '"
buttonSize="20"
buttonPadding="3"
buttonY="5"
playInSequence="' . $nmaprep . '"
playerVolume="' . $nstartvol . '"
embedFonts="false"
playerBg="BD0675,DE0789,100,3,FC47C7,100,0"
playerBgShine="BD0675,DE0789,100,3,333333,0,1"
buttonBg="EAE6D4,CFC69E,100,3,919191,10,0"
buttonInnerStroke="0,0,0,3,EAE6D4,100,1"
buttonBgHover="D6EA15,ADC411,100,3,EAE6D4,100,1"
buttonIcon="82B013,100"
buttonIconHover="FFFFFF,100"
displaySongText="Verdana,ffffff,100,10,true"
displaySongTimeTotal="Arial,CFC69E,100,10,false"
displaySongTimeElapsed="Arial,333333,100,10,true"
displaySongTextY="2"
displaySongTimeY="2"
pauseDisplayFor="2"
songTitleScrollTime="' . $titlescroll . '"
sliderHeight="5"
sliderY="21"
sliderButtonWidth="10"
sliderButtonHeight="10"
sliderBg="FFFFFF,FFFFFF,90,3,666666,0,1"
sliderFill="D6EA15,ADC411,100,5,919191,100,1"
sliderStramProgress="919191,FFFFFF,5,5,666666,0,0"
sliderButton="D6EA15,D6EA15,80,6,333333,50,0"
volumeHeight="' . $nmapvolhgt . '"
volumeBg="FFFFFF,dddddd,100,9,333333,100,1"
volumeSliderBg="919191,FFFFFF,100,3,0,0,0"
volumeSliderFill="ADC411,D6EA15,100,3,0,0,0"
volumeText="Arial,000000,100,10,false"
volumeSliderButton="FFFFFF,666666,100,9,333333,100,1"
listOpen="' . $nmapcompact . '"
listHeight="' . $lstheight . '"
listPadding="10"
listScrollbarWidth="' . $plsbwdt . '"
listBg="DE0789,BD0675,100,3,cccccc,100,0"
listScrollbar="ABEC13,100"
listScrollbarBg="ffffff,100"
listItemHeight="20"
listItemBg="FFFFFF,70"
listItemBgActive="C0C0C0,100"
listTitleText="Verdana,ffffff,100,10,false"
listTitleTextActive="Verdana,ffffff,100,10,true"
listTitleScrollTime="' . $titlescroll . '" >' . "\n";
} elseif ($nstyle == 'g') {
    $playlist = '<?xml version="1.0" encoding="utf-8"?>
<music
playerMode="' . $ntyp . '"
displayTimeInfo="' . $nmapdisptime . '"
playerWidth="' . $nwidth . '"
playerHeight="32"
playerX="0"
playerY="' . $playery . '"
flyOut="' . $nmapflyout . '"
autoStart="' . $nmaplay . '"
buttonSize="20"
buttonPadding="3"
buttonY="5"
playInSequence="' . $nmaprep . '"
playerVolume="' . $nstartvol . '"
embedFonts="false"
playerBg="555555,555555,100,0,555555,100,1"
playerBgShine="FFFFFF,FFFFFF,0,5,333333,0,0"
buttonBg="000000,000000,20,0,888888,0,1"
buttonInnerStroke="363636,000000,100,7,333333,0,1"
buttonBgHover="000000,000000,80,0,000000,100,1"
buttonIcon="FFFFFF,100"
buttonIconHover="FFFFFF,100"
displaySongText="Verdana,FFFFFF,100,10,false"
displaySongTimeTotal="Arial,666666,100,10,false"
displaySongTimeElapsed="Arial,888888,100,10,false"
displaySongTextY="6"
displaySongTimeY="6"
pauseDisplayFor="2"
songTitleScrollTime="' . $titlescroll . '"
sliderHeight="5"
sliderY="21"
sliderButtonWidth="10"
sliderButtonHeight="10"
sliderBg="000000,333333,100,3,0,0,0"
sliderFill="0D97F2,075d99,100,3,0,0,0"
sliderStramProgress="333333,444444,100,3,0,0,0"
sliderButton="FFFFFF,666666,100,10,333333,100,1"
volumeHeight="' . $nmapvolhgt . '"
volumeBg="555555,555555,100,9,555555,100,1"
volumeSliderBg="333333,444444,100,2,0,0,0"
volumeSliderFill="0D97F2,075d99,100,2,0,0,0"
volumeText="Arial,FFFFFF,100,10,false"
volumeSliderButton="FFFFFF,666666,100,9,333333,100,1"
listOpen="' . $nmapcompact . '"
listHeight="' . $lstheight . '"
listPadding="10"
listScrollbarWidth="' . $plsbwdt . '"
listBg="222222,000000,60,0,333333,0,0"
listScrollbar="000000,20"
listScrollbarBg="000000,10"
listItemHeight="20"
listItemBg="333333,0"
listItemBgActive="075d99,0"
listTitleText="Verdana,999999,100,10,false"
listTitleTextActive="Verdana,FFFFFF,100,10,false"
listTitleScrollTime="' . $titlescroll . '" >' . "\n";
} elseif ($nstyle == 'x') {
    $playlist = '<?xml version="1.0" encoding="utf-8"?>
<music
playerMode="' . $ntyp . '"
displayTimeInfo="' . $nmapdisptime . '"
playerWidth="' . $nwidth . '"
playerHeight="40"
playerX="0"
playerY="' . $playery . '"
flyOut="' . $nmapflyout . '"
autoStart="' . $nmaplay . '"
buttonSize="20"
buttonPadding="3"
buttonY="5"
playInSequence="' . $nmaprep . '"
playerVolume="' . $nstartvol . '"
embedFonts="false"
playerBg="' . $pbgc1 . ',' . $pbgc2 . ',' . $pbga . ',' . $roundness . ',' . $pbgc3 . ',100,0"
playerBgShine="' . $pbgsc1 . ',' . $pbgsc2 . ',' . $pbgsa . ',' . $roundness . ',' . $pbgsc3 . ',0,0"
buttonBg="' . $bbgc1 . ',' . $bbgc2 . ',' . $bbga . ',' . $roundness . ',' . $bbgc3 . ',0,1"
buttonInnerStroke="' . $bisc1 . ',' . $bisc2 . ',' . $bisa . ',' . $roundness . ',' . $bisc3 . ',0,1"
buttonBgHover="' . $bbghc1 . ',' . $bbghc2 . ',' . $bbgha . ',' . $roundness . ',' . $bbghc3 . ',0,1"
buttonIcon="' . $bic1 . ',' . $bia . '"
buttonIconHover="' . $bich . ',' . $bicha . '"
displaySongText="Verdana,' . $globaltextc . ',100,10,false"
displaySongTimeTotal="Arial,' . $globaltextc . ',100,10,false"
displaySongTimeElapsed="Arial,' . $globaltextc . ',100,10,true"
displaySongTextY="2"
displaySongTimeY="2"
pauseDisplayFor="2"
songTitleScrollTime="' . $titlescroll . '"
sliderHeight="5"
sliderY="20"
sliderButtonWidth="9"
sliderButtonHeight="9"
sliderBg="' . $sbgc1 . ',' . $sbgc2 . ',' . $sbga . ',0,0,0,0"
sliderFill="' . $sfc1 . ',' . $svc2 . ',' . $sfa . ',0,0,0,0"
sliderStramProgress="' . $sspc1 . ',' . $sspc2 . ',' . $sspa . ',0,0,0,0"
sliderButton="' . $sbc1 . ',' . $sbc1 . ',' . $sbga . ',9,' . $sbc1 . ',0,1"
volumeHeight="' . $nmapvolhgt . '"
volumeBg="' . $vbgc1 . ',' . $vbgc2 . ',' . $vbga . ',' . $roundness . ',' . $vbgc3 . ',100,1"
volumeSliderBg="' . $vsbgc1 . ',' . $vsbgc2 . ',100,0,0,0,0"
volumeSliderFill="' . $vsfc1 . ',' . $vsfc2 . ',100,0,0,0,0"
volumeText="Arial,' . $globaltextc . ',100,10,false"
volumeSliderButton="' . $sbc1 . ',' . $sbc1 . ',' . $sbga . ',9,' . $sbc1 . ',0,1"
listOpen="' . $nmapcompact . '"
listHeight="' . $lstheight . '"
listPadding="10"
listScrollbarWidth="' . $plsbwdt . '"	
listBg="' . $lbgc1 . ',' . $lbgc2 . ',' . $lbga . ',' . $roundness . ',' . $lbgc3 . ',0,0"
listScrollbar="' . $lsbc1 . ',' . $lsba . '"
listScrollbarBg="' . $lsbgc1 . ',' . $lsbga . '"
listItemHeight="20"
listItemBg="' . $libg . ',' . $libga . '"
listItemBgActive="' . $libgac . ',' . $libgaa . '"
listTitleText="Verdana,999999,100,10,false"
listTitleTextActive="Verdana,' . $globaltextc . ',100,10,false"
listTitleScrollTime="' . $titlescroll . '" >' . "\n";
}
$playlist .= '<group>' . "\n";


if($dir_mode == "true") {
    $get_files = nmapp_getfiles($ndir2, $recursivity, $fullpath);
    $xml_string = array();
    foreach ($get_files as $entry) {
        $sortarray[] = $entry;
        $file_parts = pathinfo($entry);
        $file_dir = $file_parts["dirname"];
        if($recursivity) {
            $file_dir = str_replace($ndir2, "", $file_dir);
        } else {
            $file_dir = str_replace(".", "", $file_dir);
        }
        $file = $file_parts["basename"];
        if($nformat == "1") {
            $file = iconv("windows-1252", "utf-8", $file);
        }
        $tmp_str = '<song file="' . $ndir . $file_dir . '/' . $file . '"';
        if($show_titles) {
            if($nmapid3 == "1") {
                $getID3 = new getID3;
                $ThisFileInfo = $getID3->analyze($ndir2 . $file_dir . "/" . $file);
                getid3_lib::CopyTagsToComments($ThisFileInfo);
                $artist = "";
                $title = "";
                if(isset($ThisFileInfo['comments']['title'][0])) {
                    $title = $ThisFileInfo['comments']['title'][0];
                }
                if(isset($ThisFileInfo['comments']['artist'][0])) {
                    $artist = $ThisFileInfo['comments']['artist'][0];
                }
                if(!empty($artist) && !empty($title)) {
                    $file_title = $artist . " - " . $title;
                } else {
                    $file_title = substr($file, $omit_chars, strpos($file, ".") - $omit_chars);
                }
                $tmp_str .= ' title="' . $file_title . '"';
            } else {
                $file_title = substr($file, $omit_chars, strpos($file, ".") - $omit_chars);
                $tmp_str .= ' title="' . $file_title . '"';
            }
        }
        $tmp_str .= " />";
        array_push($xml_string, $tmp_str);
    }
} elseif ($dir_mode == "false") {
    $xml_string = array();
    $file_object = pathinfo($ndir2);
    $file = $file_object["basename"];
    $ndir2 = $file_object["dirname"];
    $nshuffle = "0";
    if($nformat == "1") {
        $file = iconv("windows-1252", "utf-8", $file);
    }
    $tmp_str = '<song file="' . $ndir . '"';
    if($show_titles) {
    	if($nmapid3 == "1") {
                $getID3 = new getID3;
                $ThisFileInfo = $getID3->analyze($ndir2 . "/" . $file);
                getid3_lib::CopyTagsToComments($ThisFileInfo);
                $artist = "";
                $title = "";
                if(isset($ThisFileInfo['comments']['title'][0])) {
                    $title = $ThisFileInfo['comments']['title'][0];
                }
                if(isset($ThisFileInfo['comments']['artist'][0])) {
                    $artist = $ThisFileInfo['comments']['artist'][0];
                }
                if(!empty($artist) && !empty($title)) {
                    $file_title = $artist . " - " . $title;
                } else {
                    $file_title = substr($file, $omit_chars, strpos($file, ".") - $omit_chars);
                }
                $tmp_str .= ' title="' . $file_title . '"';
            } else {
                $file_title = substr($file, $omit_chars, strpos($file, ".") - $omit_chars);
                $tmp_str .= ' title="' . $file_title . '"';
            }
    }
    $tmp_str .= " />";
    array_push($xml_string, $tmp_str);
} else {
    $playlist .= "<song file='null' title='Error! Incorrect path!'/>\n</group>\n</music>\n";
    echo $playlist;
    exit;
}

if(!empty($nint) && !empty($nrfile)) {
    $extra_title = pathinfo($nrfile);
    $extra_title = $extra_title["basename"];
    $extra_title = substr($extra_title, $omit_chars, strpos($extra_title, ".") - $omit_chars);
}
if(empty($xml_string)) {
    $playlist .= "<song file='null' title='Error! No audio files found!'/>\n</group>\n</music>\n";
    echo $playlist;
    exit;
}
if($nshuffle == '0') {
    sort($xml_string);
} elseif ($nshuffle == '1') {
    shuffle($xml_string);
} elseif ($nshuffle == '2') {
    rsort($xml_string);
} elseif ($nshuffle == '3') {
    unset($final_array);
    unset($timearray);
    foreach ($sortarray as $fmtime) {
        $timearray[] = filemtime($fmtime);
    }
    for ($d = 0; $d < count($xml_string); $d++) {
        $final_array[$timearray[$d]] = $xml_string[$d];
    }
    krsort($final_array);
    unset($xml_string);
    foreach ($final_array as $value) {
        $xml_string[] = $value;
    }
} elseif ($nshuffle == '4') {
    unset($final_array);
    unset($timearray);
    foreach ($sortarray as $fmtime) {
        $timearray[] = filemtime($fmtime);
    }
    for ($d = 0; $d < count($xml_string); $d++) {
        $final_array[$timearray[$d]] = $xml_string[$d];
    }
    ksort($final_array);
    unset($xml_string);
    foreach ($final_array as $value) {
        $xml_string[] = $value;
    }
}
for ($i = 0; $i < count($xml_string); $i++) {
    if(!empty($nint) && !empty($nrfile)) {
        if(($i % $nint) == 0) {
            if($i != 0) {
                $playlist .= "<song file='" . $nrfile . "' title='" . $extra_title . "'/>";
            }
        }
    }
    $playlist .= $xml_string[$i] . "\n";
}
$playlist .= '</group>' . "\n";
$playlist .= '</music>' . "\n";
echo $playlist;
?>