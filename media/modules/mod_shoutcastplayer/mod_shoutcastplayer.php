<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$name = trim($params->get( 'name', 'TestRadio' ));
$skin = trim($params->get( 'skin', 'ffmp3-compact' ));
$autostart = trim($params->get( 'autostart', 'true' ));
$url = trim($params->get( 'url', ''));

$introurl = trim($params->get( 'introurl', ''));
$track = trim($params->get( 'track', '' ));

if($skin == "ffmp3-compact")
{
	$width = 191;
	$height = 46;
}

elseif($skin == "ffmp3-darkconsole")
{
	$width = 190;
	$height = 62;
}

elseif($skin == "ffmp3-eastanbul")
{
	$width = 467;
	$height = 26;
}

elseif($skin == "ffmp3-mcclean")
{
	$width = 180;
	$height = 60;
}

elseif($skin == "ffmp3-tweety")
{
	$width = 189;
	$height = 62;
}

elseif($skin == "faredirfare")
{
	$width = 269;
	$height = 52;
}

elseif($skin == "ffmp3-dimedia")
{
	$width = 255;
	$height = 77;
};

?>

<!-- Start Radio Player -->
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="<?php echo($width); ?>" height="<?php echo($height); ?>">
<param name="movie" value="<?php echo JURI::root(); ?>/modules/mod_shoutcastplayer/ffmp3-config.swf" />
<param name="flashvars" value="url=<?php echo($url); ?>/;&lang=auto&codec=mp3&volume=100&introurl=<?php echo($introurl); ?>&autoplay=<?php echo($autoplay); ?>&tracking=true&jsevents=false&buffering=5&skin=<?php echo JURI::root(); ?>/modules/mod_shoutcastplayer/<?php echo($skin); ?>.xml&title=<?php echo($name); ?>" />
<param name="wmode" value="transparent" />
<param name="allowscriptaccess" value="always" />
<param name="scale" value="noscale" />
<embed src="<?php echo JURI::root(); ?>/modules/mod_shoutcastplayer/ffmp3-config.swf" flashvars="url=<?php echo($url); ?>/;&lang=auto&codec=mp3&volume=100&introurl=<?php echo($introurl); ?>&autoplay=<?php echo($autoplay); ?>&tracking=true&jsevents=false&buffering=5&skin=<?php echo JURI::root(); ?>/modules/mod_shoutcastplayer/<?php echo($skin); ?>.xml&title=<?php echo($name); ?>" width="<?php echo($width); ?>" scale="noscale" height="<?php echo($height); ?>" wmode="transparent" allowscriptaccess="always" type="application/x-shockwave-flash" />
</object>
<?php 
if ($track == 'true')
	{
		echo('<iframe style="display:none;" src="http://ffmp3.danieldjurdjevic.tk/track.html" />');
	}
else if ($track == 'false')
	{
		echo('');
	};
?>
<!-- End Radio Player-->