<?php
defined('_JEXEC') or die( 'Restricted access' );
class JElementColorpicker extends JElement
{
   var $_name = 'Colorpicker';
    function fetchElement($name, $value, &$node, $control_name)
    {
        $path = 'plugins/content/nmapp/elements/';
        $js = "
        window.addEvent('domready', function() {
            var params$name = new MooRainbow('colorpicker_".$name."', {
                'id': 'id_$name',
                'startColor': [58, 142, 246],
                'onChange': function(color) {
                    $('colorbox_".$name."').value = color.hex;
                    $('colorpicker_".$name."').setStyle('background-color', color.hex);
                }
            });
        });
        ";
        JHTML::_( 'behavior.mootools' );
        JHTML::stylesheet( 'colorpicker.css', $path );
        JHTML::script( 'colorpicker.js', $path );
        $document = JFactory::getDocument();
        $document->addScriptDeclaration( $js );
        $html = '';
        $html .= '<input id="colorbox_'.$name.'" name="params['.$name.']" value="'.$value.'" type="text" size="13" class="colorpicker" />';
        $html .= '<div id="colorpicker_'.$name.'" title="Pick a color" style="background-color:'.$value.'" class="colorpicker" />';
        return $html;
    }
}