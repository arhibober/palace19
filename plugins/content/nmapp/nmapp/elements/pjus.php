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
defined('_JEXEC') or die('Restricted access');
class JElementpjus extends JElement {
    var $_name = 'pjus';
    function fetchElement($name, $value, &$node, $control_name) {
        global $mainframe;
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.path');
        jimport('joomla.filesystem.archive');
        @ini_set('default_socket_timeout', 20);
        @set_time_limit(20);
        $config = new JSimpleXML;
        $config->loadFile(JPATH_ROOT . "/plugins/content/nmapp.xml");
        if (@$config->document->files[0]) {
            $config->document->removeChild($config->document->files[0]);
            $save_file = $config->document->toString();
            JFile::write(JPATH_ROOT . "/plugins/content/nmapp.xml", '<?xml version="1.0" encoding="UTF-8" ?>'.$save_file);
            $save_file = @file_get_contents(JPATH_ROOT . "/plugins/content/nmapp.xml");
            $save_file = str_replace("creationdate", "creationDate", $save_file);
            $save_file = str_replace("authoremail", "authorEmail", $save_file);
            $save_file = str_replace("authorurl", "authorUrl", $save_file);
            JFile::write(JPATH_ROOT . "/plugins/content/nmapp.xml", $save_file);
        }
        $tmp1 = explode(":", $value);
        $tmp2 = explode(".", $tmp1[1]);
        $ename = "pjus_" . $tmp1[0];
        $actvers = $tmp1[1];
        $mod_name = '';
        if (isset($_COOKIE[$ename]))
		{
			$html = '<span style="color: #666666;font-weight: bold;">' . $actvers . '&nbsp;</span>';
			return $html;
		}
        $modver = array("A" => $tmp2[0], "B" => $tmp2[1], "C" => $tmp2[2]);
        if (false == ($xmlserv = @file_get_contents("http://www.projoom.com/versions/pjv.xml"))) {
            $html = '<div style="color: #666666;font-weight: bold;">' . $actvers .
                ' (local)</div>';
            return $html;
        }
        $xmlobj = new JSimpleXML;
        $xmlobj->loadString($xmlserv);
        foreach ($xmlobj->document->children() as $program) {
            if ($program->name[0]->data() == $tmp1[0]) {
                $verserv = $program->version[0]->data();
            }
        }
        $verarray = explode(".", $verserv);
        $critical = ($verarray[0] == $modver["A"]) ? false : true;
        $higher = ($verarray[1] == $modver["B"]) ? false : true;
        $normal = ($verarray[2] == $modver["C"]) ? false : true;
        if ($critical) {
            if (!isset($_COOKIE[$ename . '_m'])) {
                $js = "window.addEvent('domready', function(){
    		var modal = new AscModal('<div align=\"center\">There is a new version on the server.<div>Please update.</div></div>', 'n');
    		modal.show();
    		$('ckupd').addEvent('click', function(){
    		window.open ('http://www.projoom.com/knowledgebase/article/how_to_upgrade.html');
    		});
    		});";
            } else {
                $js = "window.addEvent('domready', function(){
    		$('ckupd').addEvent('click', function(){
    		window.open ('http://www.projoom.com/knowledgebase/article/how_to_upgrade.html');
    		});});";
            }
            $path = 'plugins/content/nmapp/elements/';
            JHTML::_('behavior.mootools');
            JHTML::stylesheet('pjus.css', $path);
            JHTML::script('pjus.js', $path);
            $document = JFactory::getDocument();
            $document->addScriptDeclaration($js);
            $html = '<span style="color: #FF0000;font-weight: bold;">' . $verserv .
                '&nbsp;(major update)&nbsp;</span>
       		<input type="button" id="ckupd" value="Update Now">';
            setcookie($ename . "_m", "1", time() + (3600 * 24));
            return $html;
        } else
            if ($higher) {
                if (!isset($_COOKIE[$ename . '_m'])) {
                    $js = "window.addEvent('domready', function(){
    		var modal = new AscModal('<div align=\"center\">There is a new (recommended) version on the server.<div>Please update.</div></div>', 'n');
    		modal.show();
    		$('ckupd').addEvent('click', function(){
    		window.open ('http://www.projoom.com/knowledgebase/article/how_to_upgrade.html');
    		});
    		});";
                } else {
                    $js = "window.addEvent('domready', function(){
    		$('ckupd').addEvent('click', function(){
    		window.open ('http://www.projoom.com/knowledgebase/article/how_to_upgrade.html');
    		});});";
                }
                $path = 'plugins/content/nmapp/elements/';
                JHTML::_('behavior.mootools');
                JHTML::stylesheet('pjus.css', $path);
                JHTML::script('pjus.js', $path);
                $document = JFactory::getDocument();
                $document->addScriptDeclaration($js);
                $html = '<span style="color: #079CFF;font-weight: bold;">' . $verserv .
                    '&nbsp;</span>
       		<input type="button" id="ckupd" value="Update Now">';
                setcookie($ename . "_m", "1", time() + (3600 * 24));
                return $html;
            } else
                if ($normal) {
                    if (!isset($_COOKIE[$ename . '_m'])) {
                        $js = "window.addEvent('domready', function(){
    		var modal = new AscModal('<div align=\"center\">There is a new (minor) version on the server.<div>Update if needed.</div></div>', 'n');
    		modal.show();
    		$('ckupd').addEvent('click', function(){
    		window.open ('http://www.projoom.com/knowledgebase/article/how_to_upgrade.html');
    		});
    		});";
                    } else {
                        $js = "window.addEvent('domready', function(){
    		$('ckupd').addEvent('click', function(){
    		window.open ('http://www.projoom.com/knowledgebase/article/how_to_upgrade.html');
    		});});";
                    }
                    $path = 'plugins/content/nmapp/elements/';
                    JHTML::_('behavior.mootools');
                    JHTML::stylesheet('pjus.css', $path);
                    JHTML::script('pjus.js', $path);
                    $document = JFactory::getDocument();
                    $document->addScriptDeclaration($js);
                    $html = '<span style="color: #50AA1D;font-weight: bold;">' . $verserv .
                        '&nbsp;</span>
       		<input type="button" id="ckupd" value="Update Now">';
                    setcookie($ename . "_m", "1", time() + (3600 * 24));
                    return $html;
                } else {
                    $html = '<div style="color: #666666;font-weight: bold;">' . $actvers . '</div>';
                    setcookie($ename, "checked", time() + (3600 * 27 * 7));
                    return $html;
                }
    }
}
?>