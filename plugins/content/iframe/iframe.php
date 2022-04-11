<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

$mainframe->registerEvent( 'onPrepareContent', 'plgContentIframe' );

/**
* Plugin that loads module positions within content
*/
function plgContentIframe( &$row, &$params, $page=0 )
{

	if ( JString::strpos( $row->text, 'iframe' ) === false ) return true;		# если плагин не вызвается в коде сваливаем нафиг


	$plugin =& JPluginHelper::getPlugin('content', 'iframe');
	$pluginParams = new JParameter( $plugin->params );

	// expression to search for
	$regex = '/{iframe\s*.*?}/i';
	if ($pluginParams->get( 'clean', 0 )) {
		$row->text = preg_replace( $regex, '', $row->text );
		return true;
	}	
	else {
		$AllowedID = $pluginParams->get( 'allowedID');
		if ($AllowedID) {
			$arrayAllowedID = explode(',', $AllowedID );
			foreach ($arrayAllowedID as &$id) $id = trim($id);

			if (!in_array($row->id, $arrayAllowedID)) {
				$row->text = preg_replace( $regex, '', $row->text );
				return true;
			}
			else {
				// find all instances of plugin and put in $matches
				preg_match_all( $regex, $row->text, $matches );

				foreach ($matches[0] as $load)
				{
					$modules	= iframe_frame($load);		#возвращает контент
					$row->text 	= str_replace($load, $modules, $row->text );
				}
			}
		}
		else $row->text = preg_replace( $regex, '', $row->text );
	}
	return true;
}

function iframe_frame($load)	
{

	if (preg_match('/(iframe|\|)\s*source\s*=/i', $load))
	{
		$regex = "/[A-Za-z]+\s*=\s*['".'"]?[^|}]+["'."']?/i";
		preg_match_all( $regex, $load, $matches );
		
		$atributes = '';
		foreach($matches[0] as $v) 	
		{
			$atributes .= trim(substr($v,0,strpos($v,'='))).'="';
			$atributes .= preg_replace('/^["'."'](.*)['".'"]$/','$1',trim(substr($v,strpos($v,'=')+1))).'" ';
		} 
		$atributes = str_replace('source=', 'src=', $atributes);
		$return	= '<iframe '.$atributes.'></iframe>'; 
	}	
	else $return = '';
return $return;

}
