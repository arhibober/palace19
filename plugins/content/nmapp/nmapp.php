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
defined('_JEXEC') or die('Restricted access');
error_reporting(E_ALL ^ E_NOTICE);
jimport('joomla.event.plugin');
$mainframe->registerEvent('onPrepareContent', 'nmapopup');
function nmapopup(&$row, &$params, $page = 0) {
    $regex = "#{nmap}(.*?){/nmap}#s";
    $plugin = &JPluginHelper::getPlugin('content', 'nmapp');
    $pluginParams = new JParameter($plugin->params);
    if(!$pluginParams->get('enabled', 1)) {
        $row->text = preg_replace($regex, '', $row->text);
        return true;
    }
    $row->text = preg_replace_callback($regex, 'nmapopup_replacer', $row->text);
    return true;
}
function nmapopup_replacer(&$matches) {
    $RURL = JURI::base();
    $plugin = &JPluginHelper::getPlugin('content', 'nmapp');
    $pluginParams = new JParameter($plugin->params);
    //Params
    $nmapalign = $pluginParams->get('nmapalign', '');
    $nmaptyp = $pluginParams->get('nmaptyp', '');
    $nmapstyle = $pluginParams->get('nmapstyle', '');
    $nmaptrim = $pluginParams->get('nmaptrim', '');
    $nmaplay = $pluginParams->get('nmaplay', '');
    $nmaprepeat = $pluginParams->get('nmaprepeat', '');
    $nmapshuffle = $pluginParams->get('nmapshuffle', '');
    $nmacomp = $pluginParams->get('nmacomp', '');
    $nmascrltit = $pluginParams->get('nmascrltit', '');
    $nmapvolhgt = $pluginParams->get('nmapvolhgt', '');
    $nmapplbwdt = $pluginParams->get('nmapplbwidth', '');
    $nmapflyout = $pluginParams->get('nmapflyout', '');
    $nmapdisptime = $pluginParams->get('nmapdisptime', '');
    $nmapint = $pluginParams->get('nmapint');
    $nmaprfile = $pluginParams->get('nmaprfile');
    $nmapvol = $pluginParams->get('nmapvol');
    $nmapformat = $pluginParams->get('nmapformat');
    $nmaprecurse = $pluginParams->get('nmaprecurse');
    $nmapsetperms = $pluginParams->get('nmapsetperms');
    $nmapid3 = $pluginParams->get('nmapid3');
    if(!empty($nmaprfile)) {
        $nmaprfile = $RURL . $nmaprfile;
    } else {
        $nmaprfile = "";
    }
    //skins
    $debug_key = "";
    $debug_key = JRequest::getVar('pj', '', 'get');
    $pbgc1 = $pluginParams->get('pbgc1');
    $pbgc2 = $pluginParams->get('pbgc2');
    $pbga = $pluginParams->get('pbga');
    $pbgc3 = $pluginParams->get('pbgc3');
    $pbgsc1 = $pluginParams->get('pbgsc1');
    $pbgsc2 = $pluginParams->get('pbgsc2');
    $pbgsa = $pluginParams->get('pbgsa');
    $pbgsc3 = $pluginParams->get('pbgsc3');
    $bbgc1 = $pluginParams->get('bbgc1');
    $bbgc2 = $pluginParams->get('bbgc2');
    $bbga = $pluginParams->get('bbga');
    $bbgc3 = $pluginParams->get('bbgc3');
    $bisc1 = $pluginParams->get('bisc1');
    $bisc2 = $pluginParams->get('bisc2');
    $bisa = $pluginParams->get('bisa');
    $bisc3 = $pluginParams->get('bisc3');
    $bbghc1 = $pluginParams->get('bbghc1');
    $bbghc2 = $pluginParams->get('bbghc2');
    $bbgha = $pluginParams->get('bbgha');
    $bbghc3 = $pluginParams->get('bbghc3');
    $bic1 = $pluginParams->get('bic1');
    $bia = $pluginParams->get('bia');
    $bich = $pluginParams->get('bich');
    $bicha = $pluginParams->get('bicha');
    $globaltextc = $pluginParams->get('globaltextc');
    $sbgc1 = $pluginParams->get('sbgc1');
    $sbgc2 = $pluginParams->get('sbgc2');
    $sbga = $pluginParams->get('sbga');
    $sfc1 = $pluginParams->get('sfc1');
    $svc2 = $pluginParams->get('svc2');
    $sfa = $pluginParams->get('sfa');
    $sspc1 = $pluginParams->get('sspc1');
    $sspc2 = $pluginParams->get('sspc2');
    $sspa = $pluginParams->get('sspa');
    $sbc1 = $pluginParams->get('sbc1');
    $sbc2 = "";
    $sbc3 = "";
    $vbgc1 = $pluginParams->get('vbgc1');
    $vbgc2 = $pluginParams->get('vbgc2');
    $vbga = $pluginParams->get('vbga');
    $vbgc3 = $pluginParams->get('vbgc3');
    $vsbgc1 = $pluginParams->get('vsbgc1');
    $vsbgc2 = $pluginParams->get('vsbgc2');
    $vsfc1 = $pluginParams->get('vsfc1');
    $vsfc2 = $pluginParams->get('vsfc2');
    $vsbc1 = "";
    $vsbc2 = "";
    $lbgc1 = $pluginParams->get('lbgc1');
    $lbgc2 = $pluginParams->get('lbgc2');
    $lbga = $pluginParams->get('lbga');
    $lbgc3 = $pluginParams->get('lbgc3');
    $lsbc1 = $pluginParams->get('lsbc1');
    $lsba = $pluginParams->get('lsba');
    $lsbgc1 = $pluginParams->get('lsbgc1');
    $lsbga = $pluginParams->get('lsbga');
    $libg = $pluginParams->get('libg');
    $libga = $pluginParams->get('libga');
    $libgac = $pluginParams->get('libgac');
    $libgaa = $pluginParams->get('libgaa');
    $roundness = $pluginParams->get('roundness');
    $nmapwcor = $pluginParams->get('nmapwcor');
    //check for playlist permission
    $perm_mainfolder = substr(decoct(fileperms(JPATH_ROOT . '/plugins/content/nmapp')), 2);
    $perm_popup = substr(decoct(fileperms(JPATH_ROOT . '/plugins/content/nmapp/popup.php')), 3);
    $set_perm_nmapl = "";
    $set_perm_popup = "";
    if($nmapsetperms == 1) {
        $popupfile = JPATH_ROOT . "/plugins/content/nmapp/popup.php";
        if(!JPath::setPermissions($popupfile)) {
            $set_perm_popup = "FAILED";
        } else {
            $set_perm_popup = "SUCCESS";
        }
    }
    //
    if($debug_key == "pmapp") {
        if($perm_mainfolder < 755) {
            $prm_mf = '<span style="color: red">0' . $perm_mainfolder . '</span>';
        } else {
            $prm_mf = '<span style="color: green">0' . $perm_mainfolder . '</span>';
        }
        if($perm_popup < 644) {
            $prm_popup = '<span style="color: red">0' . $perm_popup . '</span>';
        } else {
            $prm_popup = '<span style="color: green">0' . $perm_popup . '</span>';
        }
        $f_em = "";
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
        printf('<div id="pjdbg"><br/><center><span style="font-size: 24px; font-weight: bold;">Pro Magic Audio Player - Debug Panel</span><br/><hr/><strong>Plugin Folder Permission: ' . $prm_mf . '<br/>Popup File Permission: ' . $prm_popup .
            '<br/>' . $f_em . '</strong></center></div>');
        return;
    }
    //
    $thisParams = explode("|", $matches[1]);
    if(sizeof($thisParams) < 5)
        return "<center>Not enough parameters! <br />Please use this code : {nmap}type|width|height|directory|button|title|style{/nmap} <br />Note : The title is optional</center>";
    $nmapptype = str_replace(' ', '', $thisParams[0]);
    $nmapwidth = str_replace(' ', '', $thisParams[1]);
    $nmapheight = str_replace(' ', '', $thisParams[2]);
    $nmapdir = $thisParams[3];
    $nmapbut = $thisParams[4];
    $nmaptitle = $thisParams[5];
    $nmappstyle = str_replace(' ', '', $thisParams[6]);
    if(!empty($nmappstyle)) {
        $nmapstyle = $nmappstyle;
    }
    $bgcol = $thisParams[7];
    if(!empty($bgcol)) {
        $defbg = str_replace('#', '', $bgcol);
    }
    $nmalsthgt = $nmapheight - 40;
    $nmapas = $thisParams[8];
    if(!empty($nmapas)) {
        $nmaplay = $nmapas;
    }
    $key = "xCv";
    $nmapid = rand(1, 99);
    $RURL = JURI::base();
    $nmapdira = $nmapdir;
    $nmapdirb = '0';
    $combined = $nmaptyp . "|" . $nmapwidth . "|" . $nmaplay . "|" . $nmapstyle . "|" . $nmapdira . "|" . $nmaprfile . "*" . $nmapint . "*" . $nmapvol . "*" . $nmapformat . "*" . $nmaprecurse . "*" . $nmapid3 . "|" . $nmaptrim . "|" . $nmaprepeat . "|" . $nmapshuffle . "|" . $nmacomp . "|" . $nmalsthgt . "|" . $nmascrltit .
        "|" . $nmapvolhgt . "|" . $nmapplbwdt . "|" . $nmapflyout . "|" . $nmapdisptime . "|" . $nmapheight;
    if($nmapstyle == "x") {
        $combined = $combined . "|" . $pbgc1 . "|" . $pbgc2 . "|" . $pbga . "|" . $pbgc3 . "|" . $pbgsc1 . "|" . $pbgsc2 . "|" . $pbgsa . "|" . $pbgsc3 . "|" . $bbgc1 . "|" . $bbgc2 . "|" . $bbga . "|" . $bbgc3 . "|" . $bisc1 . "|" . $bisc2 . "|" . $bisa . "|" . $bisc3 . "|" . $bbghc1 . "|" . $bbghc2 . "|" .
            $bbgha . "|" . $bbghc3 . "|" . $bic1 . "|" . $bia . "|" . $bich . "|" . $bicha . "|" . $globaltextc . "|" . $sbgc1 . "|" . $sbgc2 . "|" . $sbga . "|" . $sfc1 . "|" . $svc2 . "|" . $sfa . "|" . $sspc1 . "|" . $sspc2 . "|" . $sspa . "|" . $sbc1 . "|" . $sbc2 . "|" . $sbc3 . "|" . $vbgc1 . "|" . $vbgc2 .
            "|" . $vbga . "|" . $vbgc3 . "|" . $vsbgc1 . "|" . $vsbgc2 . "|" . $vsfc1 . "|" . $vsfc2 . "|" . $vsbc1 . "|" . $vsbc2 . "|" . $lbgc1 . "|" . $lbgc2 . "|" . $lbga . "|" . $lbgc3 . "|" . $lsbc1 . "|" . $lsba . "|" . $lsbgc1 . "|" . $lsbga . "|" . $libg . "|" . $libga . "|" . $libgac . "|" . $libgaa .
            "|" . $roundness . "|" . $nmapid;
    } else {
        $combined = $combined . "|" . $nmapid;
    }
    $combined = str_replace('#', '', $combined);
    $send = nmapp_encode($combined, $key);
    //
    if($nmapptype == 'popup') {
        if(empty($nmapptitle)) {
            $nmapptitle = 'Pro Magic Audio Player';
        }
        $url = strtr(base64_encode(addslashes(gzcompress(serialize($RURL), 9))), '+/=', '-_,');
        $popurl = $RURL . "plugins/content/nmapp/popup.php?cfg=" . $send . "&url=" . $url . "&plw=" . $nmapwidth . "&plh=" . $nmapheight . "&bgc=" . $defbg . "&tit=" . $nmaptitle;
        $buturl = $RURL . "plugins/content/nmapp/" . $nmapbut . ".png";
        $popwidth = $nmapwidth + $nmapwcor;
        $popheight = $nmapheight + $nmapwcor;
        $text = "<div align=\"center\"><a href=\"JavaScript:void(0);\" onclick=\"nmapopup=window.open('$popurl','nmapopup','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=$popwidth,height=$popheight,left=50,top=50'); return false;\"><img style=\"outline:none;\" src=\"$buturl\" border=\"0\" alt=\"Click\"/></a></div>";
        return $text;
    }
    if($nmapptype == 'normal') {
        $xmlurl = $send;
        $playerurl = $RURL . "modules/mod_nmap/audio/nmaplayer.swf?id=$nmapid";
        $text = "<div align=\"$nmapalign\"><object style=\"outline:none;\" type=\"application/x-shockwave-flash\" id=\"nmap_$nmapid\" data=\"$playerurl\" width=\"$nmapwidth\" height=\"$nmapheight\">
	<param name=\"movie\" value=\"$playerurl\" />
	<param name=\"base\" value=\"$RURL\" />
	<param name=\"wmode\" value=\"transparent\" />
	<param name=\"flashvars\" value=\"uid=$xmlurl\" />
	</object></div>";
        return $text;
    }
}
function nmapp_encode($string, $key) {
    $j = '';
    $hash = '';
    $key = sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
    for ($i = 0; $i < $strLen; $i++) {
        $ordStr = ord(substr($string, $i, 1));
        if($j == $keyLen) {
            $j = 0;
        }
        $ordKey = ord(substr($key, $j, 1));
        $j++;
        $hash .= strrev(base_convert(dechex($ordStr + $ordKey), 16, 36));
    }
    return $hash;
}
?>