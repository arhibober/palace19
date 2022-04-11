/*******************************************************************************
 * Script : Template Selector for mootools : 1.8.0
 * @package		Template Selector
 * @copyright		Copyright (C) 2007-2012 Yoshiki Kozaki(www.joomler.net) All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 * @author		Yoshiki Kozaki(www.joomler.net) info@joomler.net
 * @link 			http://www.joomler.net/
 ******************************************************************************/

var jTemplateSelector=new Class({Implements:Options,options:{templates:{},reset:"jTemplateReset",change:"jTemplateChange",rolling:"jTemplateRolling",directory:"jTemplateDirectory",list:"jTmplDirectories",msg:"tpscurimg",selected:null,base:null,duration:365},selector:null,key:"jTemplateSelector",initialize:function(e){this.setOptions(e);if(this.options.templates.length<1)return;if($(this.options.list)){this.selector=$(this.options.list);this.selector.addEvent("change",function(){this.onChange()}.bind(this));this.selector.set("value",this.options.selected)}if($(this.options.reset))$(this.options.reset).addEvent("click",function(e){if(e)e.stop();$(this.options.directory).set("value","");Cookie.dispose(this.key);window.location.reload()}.bind(this));if($(this.options.rolling))$(this.options.rolling).addEvent("click",function(e){if(e)e.stop();var t=false,n=this.options.selected;this.selector.getElements("option").each(function(e,r){if(e.get("value")==n){t=r+1}});if(t==this.selector.options.length)t=0;this.selector.options.selectedIndex=t;this.onChange();if($(this.options.change))$(this.options.change).fireEvent("click")}.bind(this));if($(this.options.change))$(this.options.change).addEvent("click",function(e){if(e)e.stop();Cookie.write(this.key,$(this.options.directory).get("value"),{duration:this.options.duration});window.location.reload()}.bind(this))},onChange:function(){var e=this.selector.get("value");if(typeof this.options.templates[e]=="undefined")return;var t=$(this.options.msg);if(t){t.src=this.options.base+this.options.templates[e]+"/template_thumbnail.png"}$(this.options.directory).set("value",e)}})
