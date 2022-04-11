<?php /**  * @copyright	Copyright (C) 2012 ThemeGoat.com - All Rights Reserved. **/
defined( '_JEXEC' ) or die( 'Restricted access' );
define( 'YOURBASEPATH', dirname(__FILE__) );
if ((strstr($_SERVER['HTTP_USER_AGENT'], 'IE')) && (!strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE 9')) && (!strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE 10')) && (!strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE 11')) && (!strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE 12')))
{
	echo "Извините, данным браузером сайт не поддерживается.";
	return;
}
?>
<?php include (YOURBASEPATH.DS . "/modules/req_parameters.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<?php if ($this->params->get( 'jchecker' )) : ?>  
<script type="text/javascript">if (typeof jQuery == 'undefined') { document.write(unescape("%3Cscript src='<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/modules/jquery171.js' type='text/javascript'%3E%3C/script%3E")); } </script>
<script type="text/javascript">jQuery.noConflict();</script>
<?php endif; ?>
<?php require(YOURBASEPATH . DS . "functions.php"); ?>
<?php require(YOURBASEPATH . DS . "/modules/req_css.php"); ?>
<?php if ($this->params->get( 'jcopyright' )) : ?> <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/modules/jcopyright.js"></script><?php endif; ?>
<?php if ($this->params->get( 'jtabs' )) : ?> <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/modules/jtabs.js"></script><?php endif; ?>
<?php if ($this->params->get( 'jscroll' )) : ?> <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/modules/jscroll.js"></script><?php endif; ?>
</head>

<body class="background">
<div id="main">
<div id="header-w">
    	<div id="header">
		<div class="topmenu">
		<div class="topleft"></div><div class="topright">
			<jdoc:include type="modules" name="position-1" style="none" />
		</div><div class="topright2">
<!-- Social Buttons -->
<?php if ($this->params->get( 'socialbuttons' )) : ?><?php include "modules/socialbuttons.php"; ?><?php endif; ?>
<!-- END-->			
		</div>
		</div>
        	<?php if ($this->countModules('logo')) : ?>
                <div class="logo">
                	<jdoc:include type="modules" name="logo" style="none" />
                </div>
            <?php else : ?>        
            	<a href="<?php echo $this->baseurl ?>/">
			<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/logo.png" border="0" class="logo">
			</a>
            <?php endif; ?>
			
			<?php if ($this->countModules('logo_slogan')) : ?>
                <div class="logo_slogan">
                	<jdoc:include type="modules" name="logo_slogan" style="none" />
                </div>
            <?php else : ?>        
            	<a href="<?php echo $this->baseurl ?>/">
			<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/logo_DS_50_w4.png" border="0" class="logo_slogan">
			</a>
            <?php endif; ?>
			
		<div class="slogan"><?php if ($this->params->get( 'slogandisable' )) : ?><?php echo ($slogan); ?><?php endif; ?></div>
            <?php if ($this->countModules('top')) : ?> 
                <div class="top">
                    <jdoc:include type="modules" name="top" style="none"/>
                </div>
            <?php endif; ?>                         
	</div> 
</div>

<div class="shadow">
<div id="wrapper">
        	<div id="navr">
			
			<div class="searchbutton"><jdoc:include type="modules" name="position-0" style="none" /></div>
		<div id="navl">
		<div id="nav">
				<div id="nav-left"><jdoc:include type="modules" name="menuload" style="none" /></div>
	<div id="nav-right">
	<?php include "html/com_content/archive/component.php"; ?>
	<?php if ($this->countModules('breadcrumb')) : ?>
        	<jdoc:include type="modules" name="breadcrumb"  style="none"/>
        <?php endif; ?>
	<div class="clearpad"></div>
	<div id="message">
	    <jdoc:include type="message" />
	</div>    
            <?php if($this->countModules('left')) : ?>
<div id="leftbar-w">
    <div id="sidebar">
        <jdoc:include type="modules" name="left" style="jaw" /></div>	
</div>
    <?php endif; ?>
    <?php if($this->countModules('left') xor $this->countModules('right')) $maincol_sufix = '_md';
	  elseif(!$this->countModules('left') and !$this->countModules('right'))$maincol_sufix = '_bg';
	  else $maincol_sufix = ''; ?>	

<div id="centercontent<?php echo $maincol_sufix; ?>">
<div id="centercontent1">
</div>
<!-- Slideshow -->
<?php $menu = JSite::getMenu(); ?>
<?php $lang = JFactory::getLanguage(); ?>
<?php if ($menu->getActive() == $menu->getDefault($lang->getTag())) { ?>
<?php if ($this->params->get( 'slidedisable' )) : ?>   <?php include "modules/slideshow.php"; ?><div class="slideshadow"> <!-- menushadow --></div><?php endif; ?>

<?php } ?>		
<!-- END Slideshow -->
<div class="clearpad"><jdoc:include type="component" /> </div></div>	
    <?php if($this->countModules('right') and JRequest::getCmd('layout') != 'form') : ?>
	
<div id="rightbar-w">
<!-- Tabs -->
<?php if ($this->params->get( 'jtabs' ) && ($menu->getActive() == $menu->getDefault($lang->getTag()))) : ?><?php include "modules/jtabs-content.php"; ?><?php endif; ?>
<!-- END Tabs -->
    <div id="sidebar">
         <jdoc:include type="modules" name="right" style="jaw" />
    </div>
	<!-- <?php if ($this->params->get( 'googletranslate' )) : ?>  <?php include "modules/googletranslate.php"; ?><?php endif; ?>-->
    </div>
    <?php endif; ?>
<div class="clr"></div>
<div class="slideshadow"> <!-- menushadow --></div>
        </div>   		
        </div>     
		<div id="user-bottom">
<div class="user1"><jdoc:include type="modules" name="user1" style="xhtml" /></div>
<div class="user2" style="position: relative; top: -45px;"><p style="text-align: center;"><!--LiveInternet counter--><script type="text/javascript"><!--
document.write("<a href='http://www.liveinternet.ru/stat/localhost/palace18/hours.html' "+
"target=_blank><img src='//counter.yadro.ru/hit?t11.11;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random()+
"' alt='' title='LiveInternet: показано число просмотров за 24"+
" часа, посетителей за 24 часа и за сегодня' "+
"border='0' width='88' height='31'><\/a>")
//--></script><!--/LiveInternet-->
<!-- I.UA counter --><a href="http://catalog.i.ua/stat/174290/" target="_blank" onclick="this.href='http://catalog.i.ua/stat/174290/';" title="Rated by I.UA">
<script type="text/javascript" language="javascript"><!--
iS='<img src="'+(window.location.protocol=='https:'?'https':'http')+
'://r.i.ua/s?u174290&p4&n'+Math.random();
iD=document;if(!iD.cookie)iD.cookie="b=b; path=/";if(iD.cookie)iS+='&c1';
iS+='&d'+(screen.colorDepth?screen.colorDepth:screen.pixelDepth)
+"&w"+screen.width+'&h'+screen.height;
iT=iR=iD.referrer.replace(iP=/^[a-z]*:\/\//,'');iH=window.location.href.replace(iP,'');
((iI=iT.indexOf('/'))!=-1)?(iT=iT.substring(0,iI)):(iI=iT.length);
if(iT!=iH.substring(0,iI))iS+='&f'+escape(iR);
iS+='&r'+escape(iH);
iD.write(iS+'" border="0" width="88" height="31" />');
//--></script></a><!-- End of I.UA counter -->
<jdoc:include type="modules" name="user2" style="xhtml" /></p></div>
<div class="user3"><jdoc:include type="modules" name="user3" style="xhtml" /></div>
</div>
<!--- To Top -->
<div style="display:none;" class="nav_up" id="nav_up"></div>
<!-- End -->
<div id="bottom">
	
	<div class="tg" style="color: #C0C0C0 !important;">
	        <p style="color: #333">© Дворец студентов НТУ "ХПИ", 2013<br/>
				<?php
$option = JRequest::getString('option');if($option == 'com_content'){  $cid = JRequest::getInt('catid');  $id = JRequest::getInt('id');}
if ($id != 33)
	echo "<a href='http://www.createwordpressthemes.com' title='visit us'>Create Wordpress Themes</a><br/><span style='color: #C0C0C0 !important;'>Designer by <a href='mailto://arhibober@gmail.com'>Arhibober</a></span>";
				?>
            <jdoc:include type="modules" name="copyright"/>  
			<?php $menu = JSite::getMenu(); ?><?php $lang = JFactory::getLanguage(); ?><?php if ($menu->getActive() == $menu->getDefault($lang->getTag())) { ?>
				<a href="http://www.createwordpressthemes.com" title="visit us">Create Wordpress Themes</a><br/><span style="color: #C0C0C0 !important;">Designer by <a href="mailto://arhibober@gmail.com">Arhibober</a></span><?php } ?>
			<?php if ($this->params->get( 'footerdisable' )) : ?><?php echo ($footertext); ?><?php endif; ?>
			<?php if ($this->params->get( 'jcopyright' )) : ?><div class="panel">Copyright 2012</div><p class="flip">&copy;</p><?php endif; ?>
	</div>
</div></div>

</div>
<div class="back-bottom"><!--shadow top--> </div>
</div>
</div>
</body>
</html>