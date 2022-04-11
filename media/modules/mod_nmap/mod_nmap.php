<?php
/**
 *Pro Magic Audio Player
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
defined('_JEXEC') or die('Access denied!');
$sfxclass = $params->get('moduleclass_sfx');
$vscript = $params->get('vscript');
$nofl = $params->get('nofl');
$nmapalign = $params->get('nmapalign');
$nmapwidth = $params->get('nmapwidth');
$nmapheight = $params->get('nmapheight');
$nmaptyp = $params->get('nmaptyp');
$nmapstyle = $params->get('nmapstyle');
$nmapdir = $params->get('nmapdir');
$nmaptrim = $params->get('nmaptrim');
$nmaplay = $params->get('nmaplay');
$nmaprepeat = $params->get('nmaprepeat');
$nmapshuffle = $params->get('nmapshuffle');
$nmacomp = $params->get('nmacomp');
$nmalsthgt = $params->get('nmalsthgt');
$nmascrltit = $params->get('nmascrltit');
$nmapvolhgt = $params->get('nmapvolhgt');
$nmapplbwdt = $params->get('nmapplbwidth');
$nmapflyout = $params->get('nmapflyout');
$nmapdisptime = $params->get('nmapdisptime');
$nmapwintype = $params->get('nmapwintype');
$nmapvol = $params->get('nmapvol');
$defbg = $params->get('defbg');
$nmapbut = $params->get('nmapbut');
$nmaptitle = $params->get('nmaptitle');
$nmapwcor = $params->get('nmapwcor');
$nmapformat = $params->get('nmapformat');
$nmaprecurse = $params->get('nmaprecurse');
$nmapid3 = $params->get('nmapid3');
$RURL = JURI::base();
if (empty ($nofl)){ 
$nofl = 'Please update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash Player</a> to view content.';
}
$nmaprfile = $params->get('nmaprfile');
if (!empty($nmaprfile)){
	$nmaprfile = $RURL . $nmaprfile;
} else {
	$nmaprfile = "";
}
$debug_key = "";
$debug_key = JRequest::getVar('pj', '', 'get');
$nmapint = $params->get('nmapint');
$pbgc1 = $params->get('pbgc1');
$pbgc2 = $params->get('pbgc2');
$pbga = $params->get('pbga');
$pbgc3 = $params->get('pbgc3');
$pbgsc1 = $params->get('pbgsc1');
$pbgsc2 = $params->get('pbgsc2');
$pbgsa = $params->get('pbgsa');
$pbgsc3 = $params->get('pbgsc3');
$bbgc1 = $params->get('bbgc1');
$bbgc2 = $params->get('bbgc2');
$bbga = $params->get('bbga');
$bbgc3 = $params->get('bbgc3');
$bisc1 = $params->get('bisc1');
$bisc2 = $params->get('bisc2');
$bisa = $params->get('bisa');
$bisc3 = $params->get('bisc3');
$bbghc1 = $params->get('bbghc1');
$bbghc2 = $params->get('bbghc2');
$bbgha = $params->get('bbgha');
$bbghc3 = $params->get('bbghc3');
$bic1 = $params->get('bic1');
$bia = $params->get('bia');
$bich = $params->get('bich');
$bicha = $params->get('bicha');
$globaltextc = $params->get('globaltextc');
$sbgc1 = $params->get('sbgc1');
$sbgc2 = $params->get('sbgc2');
$sbga = $params->get('sbga');
$sfc1 = $params->get('sfc1');
$svc2 = $params->get('svc2');
$sfa = $params->get('sfa');
$sspc1 = $params->get('sspc1');
$sspc2 = $params->get('sspc2');
$sspa = $params->get('sspa');
$sbc1 = $params->get('sbc1');
$sbc2 = "";
$sbc3 = "";
$vbgc1 = $params->get('vbgc1');
$vbgc2 = $params->get('vbgc2');
$vbga = $params->get('vbga');
$vbgc3 = $params->get('vbgc3');
$vsbgc1 = $params->get('vsbgc1');
$vsbgc2 = $params->get('vsbgc2');
$vsfc1 = $params->get('vsfc1');
$vsfc2 = $params->get('vsfc2');
$vsbc1 = "";
$vsbc2 = "";
$lbgc1 = $params->get('lbgc1');
$lbgc2 = $params->get('lbgc2');
$lbga = $params->get('lbga');
$lbgc3 = $params->get('lbgc3');
$lsbc1 = $params->get('lsbc1');
$lsba = $params->get('lsba');
$lsbgc1 = $params->get('lsbgc1');
$lsbga = $params->get('lsbga');
$libg = $params->get('libg');
$libga = $params->get('libga');
$libgac = $params->get('libgac');
$libgaa = $params->get('libgaa');
$roundness = $params->get('roundness');
$nmapsetperms = $params->get('nmapsetperms');
$key = "xCv";
$nmapid = rand(1, 99);
global $mainframe;
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.path');
$perm_mainfolder = substr(decoct(fileperms(JPATH_BASE . '/modules/mod_nmap')), 2);
$perm_nmapl = substr(decoct(fileperms(JPATH_BASE . '/modules/mod_nmap/audio/nmapl.php')), 3);
$perm_popup = substr(decoct(fileperms(JPATH_BASE . '/modules/mod_nmap/audio/popup.php')), 3);
$set_perm_nmapl = "";
$set_perm_popup = "";
if($nmapsetperms == 1) {
    $plfile = JPATH_BASE . "/modules/mod_nmap/audio/nmapl.php";
    if(!JPath::setPermissions($plfile)) {
        $set_perm_nmapl = "FAILED";
    } else {
        $set_perm_nmapl = "SUCCESS";
    }
    $popupfile = JPATH_BASE . "/modules/mod_nmap/audio/popup.php";
    if(!JPath::setPermissions($popupfile)) {
        $set_perm_popup = "FAILED";
    } else {
        $set_perm_popup = "SUCCESS";
    }
}
//
if($debug_key == "pmap") {
    if($perm_mainfolder < 755) {
        $prm_mf = '<span style="color: red">0' . $perm_mainfolder . '</span>';
    } else {
        $prm_mf = '<span style="color: green">0' . $perm_mainfolder . '</span>';
    }
    if($perm_nmapl < 444) {
        $prm_nmapl = '<span style="color: red">0' . $perm_nmapl . '</span>';
    } else {
        $prm_nmapl = '<span style="color: green">0' . $perm_nmapl . '</span>';
    }
    if($perm_popup < 444) {
        $prm_popup = '<span style="color: red">0' . $perm_popup . '</span>';
    } else {
        $prm_popup = '<span style="color: green">0' . $perm_popup . '</span>';
    }
    $f_em = "";
    if(!empty($set_perm_nmapl)) {
        if($set_perm_nmapl == "SUCCESS") {
            $f_em .= '<strong>File "nmapl.php" permissions have been changed successfully to 644.</strong><br/>';
        } else {
            $f_em .= '<strong>File "nmapl.php" permissions could not be changed. Please change them manually.</strong><br/>';
        }
    }
    if(!empty($set_perm_popup)) {
        if($set_perm_popup == "SUCCESS") {
            $f_em .= '<strong>File "popup.php" permissions have been changed successfully to 644.</strong><br/>';
        } else {
            $f_em .= '<strong>File "popup.php" permissions could not be changed. Please change them manually.</strong><br/>';
        }
    }

    $document = &JFactory::getDocument();
    $dbgcss = "body > div#pjdbg {position: fixed;}
#pjdbg {
position:fixed;

background-color: #E2E2E2;
border: 1px solid black;
padding: 3px;
line-height: 16px;
}
#pjdbg img {padding-bottom: 5px;}
/* fix IE6 */
#pjdbg{
 position:fixed;
 _position:absolute;
 _top:expression(eval(document.body.scrollTop));
}";
    $document->addStyleDeclaration($dbgcss);
    printf('<div id="pjdbg"><br/><center><span style="font-size: 22px; font-weight: bold;">Pro Magic Audio Player - Debug Panel</span><br/><hr/><strong>Module Folder Permission: ' . $prm_mf . '<br/>Playlist File Permission: ' . $prm_nmapl . '<br/>Popup File Permission: '. $prm_popup . '<br/>' . $f_em .
        '</strong></center></div>');
    return;
}
$RURL = JURI::base();
$document = &JFactory::getDocument();
//head start
$compat = '';
switch ($vscript){
	case 'mod1':
		$jsswf_url = $RURL . "modules/mod_nmap/js/swfobject.js";
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
$nmapdira = $nmapdir;
$nmapdirb = '0';
//build variables
$combined = $nmaptyp . "|" . $nmapwidth . "|" . $nmaplay . "|" . $nmapstyle .
	"|" . $nmapdira . "|" . $nmaprfile."*". $nmapint . "*" . $nmapvol. "*" . $nmapformat . "*" . $nmaprecurse . "*" . $nmapid3."|" . $nmaptrim . "|" . $nmaprepeat . "|" .
	$nmapshuffle . "|" . $nmacomp . "|" . $nmalsthgt . "|" . $nmascrltit . "|" . $nmapvolhgt .
	"|" . $nmapplbwdt . "|" . $nmapflyout . "|" . $nmapdisptime . "|" . $nmapheight;
if ($nmapstyle == "x"){
$combined = $combined  . "|" . $pbgc1 . "|" . $pbgc2 . "|" . $pbga . "|" . $pbgc3 . "|" . $pbgsc1 . "|" . $pbgsc2 . "|" . $pbgsa . "|" . $pbgsc3 . "|" . $bbgc1 . "|" . $bbgc2 . "|" . $bbga . "|" . $bbgc3 . "|" . $bisc1 . "|" . $bisc2 . "|" . $bisa . "|" . $bisc3 . "|" . $bbghc1 . "|" . $bbghc2 . "|" . $bbgha . "|" . $bbghc3 . "|" . $bic1 . "|" . $bia . "|" . $bich . "|" . $bicha . "|" . $globaltextc . "|" . $sbgc1 . "|" . $sbgc2 . "|" . $sbga . "|" . $sfc1 . "|" . $svc2 . "|" . $sfa . "|" . $sspc1 . "|" . $sspc2 . "|" . $sspa . "|" . $sbc1 . "|" . $sbc2 . "|" . $sbc3 . "|" . $vbgc1 . "|" . $vbgc2 . "|" . $vbga . "|" . $vbgc3 . "|" . $vsbgc1 . "|" . $vsbgc2 . "|" . $vsfc1 . "|" . $vsfc2 . "|" . $vsbc1 . "|" . $vsbc2 . "|" . $lbgc1 . "|" . $lbgc2 . "|" . $lbga . "|" . $lbgc3 . "|" . $lsbc1 . "|" . $lsba . "|" . $lsbgc1 . "|" . $lsbga . "|" . $libg . "|" . $libga . "|" . $libgac . "|" . $libgaa . "|" . $roundness . "|" . $nmapid;
}else{
	$combined = $combined . "|" . $nmapid;
}
//encode
$combined = str_replace('#', '', $combined);
$defbg = str_replace('#', '', $defbg);
$string = $combined;
$j = ''; $hash = '';
$key = sha1($key);
$strLen = strlen($string);
$keyLen = strlen($key);
for ($i = 0; $i < $strLen; $i++)
{
	$ordStr = ord(substr($string, $i, 1));
	if ($j == $keyLen)
	{
		$j = 0;
	}
	$ordKey = ord(substr($key, $j, 1));
	$j++;
	$hash .= strrev(base_convert(dechex($ordStr + $ordKey), 16, 36));
}
//end encode
$send = $hash;
$xmlurl = $send;
$playerurl = $RURL . "modules/mod_nmap/audio/nmaplayer.swf?id=$nmapid";
if ($nmapwintype == "normal") {
	if ($compat == 'yes') {
		$nmapsound = "<div class=\"$sfxclass\" align=\"$nmapalign\"><object style=\"outline:none;\" type=\"application/x-shockwave-flash\" id=\"nmap_$nmapid\" data=\"$playerurl\" width=\"$nmapwidth\" height=\"$nmapheight\">
		<param name=\"movie\" value=\"$playerurl\" />
		<param name=\"base\" value=\"$RURL\" />
		<param name=\"wmode\" value=\"transparent\" />
		<param name=\"flashvars\" value=\"uid=$xmlurl\" />
		</object></div>";
	} else {
		$nmapsound = "<div class=\"$sfxclass\" align=\"$nmapalign\" id=\"nmap_$nmapid\">Please update your <a href=\"http://get.adobe.com/flashplayer/\" target=\"_blank\">Flash Player</a> to view content.</div>
		<script type=\"text/javascript\">
		var flashvars = { uid: \"$xmlurl\", align: \"$nmapalign\", showVersionInfo: \"false\"};
		var params = { base: \"$RURL\", wmode: \"transparent\" };
		var attributes = { style: \"outline:none;\" };
		swfobject.embedSWF(\"$playerurl\", \"nmap_$nmapid\", \"$nmapwidth\", \"$nmapheight\", \"9.0.0\", \"\", flashvars, params, attributes);
		</script>";
	}
} else if ($nmapwintype == "popup"){
    if (empty($nmapptitle)) {
        $nmapptitle = 'Pro Magic Audio Player';
    }
    $url = strtr(base64_encode(addslashes(gzcompress(serialize($RURL),9))), '+/=', '-_,');
    $popurl = $RURL . "modules/mod_nmap/audio/popup.php?uid=".$send."&amp;url=".$url."&amp;plw=".$nmapwidth."&amp;plh=".$nmapheight."&amp;bgc=".$defbg."&amp;tit=".$nmaptitle;
    $buturl = $RURL . "modules/mod_nmap/audio/$nmapbut";
    $popwidth = $nmapwidth + $nmapwcor;
    $popheight = $nmapheight + $nmapwcor;
    $nmapsound = "<div align=\"center\"><a href=\"JavaScript:void(0);\" onclick=\"nmapopup=window.open('$popurl','nmapopup','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=$popwidth,height=$popheight,left=50,top=50'); return false;\"><img style=\"outline:none;\" src=\"$buturl\" border=\"0\" alt=\"Click\"/></a></div>";
}
echo $nmapsound;
?>