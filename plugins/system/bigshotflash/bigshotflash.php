<?php
######################################################################
# BIGSHOT Flash			          	          	          	         #
# Copyright (C) 2012 by BIGSHOT  	   	   	   	   	   	   	   	   	 #
# Homepage   : www.thinkBIGSHOT.com		   	   	   	   	   	   		 #
# Author     : Jeff Henry	    		   	   	   	   	   	   	   	 #
# Email      : JeffH@thinkBIGSHOT.com 	   	   	   	   	   	   	     #
# Version    : 1.0.2                       	   	    	   	   		 #
# License    : http://www.gnu.org/copyleft/gpl.html GNU/GPL          #
######################################################################

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.plugin.plugin');
jimport( 'joomla.html.parameter');

class plgSystemBigshotflash extends JPlugin
{
	function plgSystemBigshotflash(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->_plugin = JPluginHelper::getPlugin('system', 'bigshotflash');
		$this->_params = new JParameter($this->_plugin->params);
	}
	
	function url_validate($link)
	{        
		$url_parts = @parse_url($link);
		if (empty($url_parts["host"])) return false;
		$documentpath = $url_parts["path"];
		if (!empty($url_parts["query"])) $documentpath .= "?".$url_parts["query"];
		$host = $url_parts["host"];
		$port = $url_parts["port"];
		if (empty($port)) $port = "80";
		$socket = @fsockopen( $host, $port, $errno, $errstr, 30 );
		if (!$socket) return false;
		else
		{
			fwrite ($socket, "HEAD ".$documentpath." HTTP/1.0\r\nHost: $host\r\n\r\n");
			$http_response = fgets( $socket, 22 );
            if (strstr($http_response, "200 OK"))
			{
				return true;
				fclose($socket);
			}
			else return false;
		}
	}
 
	function onAfterRender()
	{
		$app = &JFactory::getApplication();
		if ($app->isAdmin()) return true;
		$body = $tmp = JResponse::getBody();
		$tLength = strlen(($tag = 'Flash'));
		$parameters = Array('id', 'movie', 'width', 'height', 'quality', 'bgcolor', 'play', 'loop', 'wmode', 'allowFullScreen', 'scale', 'menu', 'base');
		while (($tStart = strpos($tmp, '{'.$tag.'=')) !== false)
		{
			$tmp = substr($tmp, $tStart + $tLength + 2);
			$tEnd = strpos($tmp, '}');
			$tNext = strpos($tmp, '{');
			if ($tEnd !== false && ($tEnd < $tNext || $tNext === false))
			{
				$input = explode('|', substr("$tmp", 0, $tEnd));
				$data = array('movie'=>(substr($input[0], 0, 7) !== 'http://' && substr($input[0], 0, 8) !== 'https://' ? JURI::root() : '').$input[0]);
				if (!$this->url_validate($data['movie']))
					$object = "<span style='color: #B00'>Flash File <b>".$data['movie']."</b> is not found</span>";
				else
				{
					foreach ($input as $foo=>$pair) if ($foo)
					{
						list($key, $value) = explode('=', $pair);
						$data[$key] = $value;
					}
					foreach ($parameters as $parameter)
						if (!array_key_exists($parameter, $data))
							$data[$parameter] = $this->params->get($parameter);
					$object = "
<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0' width='".$data['width']."' height='".$data['height']."' id='".$data['id']."'>
	<param name='movie' value='".$data['movie']."'>
	<param name='quality' value='".$data['quality']."' />
	<param name='bgcolor' value='".$data['bgcolor']."' />
	<param name='play' value='".$data['play']."' />
	<param name='loop' value='".$data['loop']."' />
	<param name='scale' value='".$data['scale']."' />
	<param name='menu' value='".$data['menu']."' />
	<param name='base' value='".$data['base']."' />
	<param name='wmode' value='".$data['wmode']."' />
	<param name='allowFullScreen' value='".$data['allowFullScreen']."' />
	<!--[if !IE]>-->
	<object type='application/x-shockwave-flash' data='".$data['movie']."' width='".$data['width']."' height='".$data['height']."'>
		<param name='movie' value='".$data['movie']."'>
		<param name='quality' value='".$data['quality']."' />
		<param name='bgcolor' value='".$data['bgcolor']."' />
		<param name='play' value='".$data['play']."' />
		<param name='loop' value='".$data['loop']."' />
		<param name='scale' value='".$data['scale']."' />
		<param name='menu' value='".$data['menu']."' />
		<param name='base' value='".$data['base']."' />
		<param name='wmode' value='".$data['wmode']."' />
		<param name='allowFullScreen' value='".$data['allowFullScreen']."' />
	<!--<![endif]-->
		<a href='http://www.adobe.com/go/getflash'>
			<img src='http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif' alt='Get Adobe Flash player' />
		</a>
	<!--[if !IE]>-->
	</object>
	<!--<![endif]-->
</object>
";
				}
				$body = substr($body, 0, $tStart).$object.substr($body, $tStart + $tLength + $tEnd + 3);
			}
			else $body = substr($body, 0, $tStart)."<span style='color: #B00'>Please close <b>&#125;</b> on a Flash Plugin call</span><br />".substr($body, $tStart + $tLength + 2);
			$tmp = $body;
		}
		JResponse::setBody($body);
		return true;
	}
}
