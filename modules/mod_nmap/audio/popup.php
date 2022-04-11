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
error_reporting(E_ALL ^ E_NOTICE);
$location = unserialize(gzuncompress(stripslashes(base64_decode(strtr($_GET['url'], '-_,', '+/=')))));
$pwidth = $_GET['plw'];
$pheight = $_GET['plh'];
$pagetit = $_GET['tit'];
$send = $_GET['uid'];
$bgcol = $_GET['bgc'];
if (empty($bgcol))
{
	$bgcol = "FFFFFF";
}
$player = $location.'modules/mod_nmap/audio/nmaplayer.swf';
$playlist = $send;
echo "<html>
<head>
  <title>$pagetit</title>
</head>
<body bgcolor=\"#$bgcol\">
<div align=\"center\"><object style=\"outline:none;\" type=\"application/x-shockwave-flash\" id=\"nmap_popup\" data=\"$player\" width=\"$pwidth\" height=\"$pheight\">
	<param name=\"movie\" value=\"$player\" />
	<param name=\"base\" value=\"$location\" />
	<param name=\"wmode\" value=\"transparent\" />
	<param name=\"flashvars\" value=\"uid=$playlist\" />
	</object>
</div>
</body>
</html>";
?>