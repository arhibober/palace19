<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_BASE . DS . 'components' . DS . 'com_jsmusic' . DS . 'functions.php');
require_once( JPATH_BASE . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');

jimport('joomla.html.pagination');

class plgCommunityMusic extends CApplications
{
	var $name		= 'Music';
	var $_name		= 'Music';
	var $_user		= null;
	var $pagination	= null;
	
    function plgCommunityMusic(& $subject, $config)
    {
		$this->_user	=& CFactory::getActiveProfile();
		$this->_my		=& CFactory::getUser();

		$db	= JFactory::getDBO();
		$query = 'SELECT params FROM #__plugins WHERE element="music" AND folder="community"';
		$db->setQuery($query);
		$params	= new JParameter($db->loadResult());
		$this->params =& $params;
		
		parent::__construct($subject, $config);
    }
    
		function _getSongs()
		{
			$db		=& JFactory::getDBO();
			$sort	= $this->params->get('sort', 'DESC');
			$limit	= $this->params->get('count', '5');
	
			$query	= 'SELECT * FROM ' . $db->nameQuote( '#__jsmusic' ) . ' '
					. 'WHERE ' . $db->nameQuote( 'userid' ) . '=' . $db->Quote( $this->_user->id ) . ' '
					. 'ORDER BY ' . $db->nameQuote( 'date' ) . ' ' . $sort . ' ' 
					. 'LIMIT 0,' . $limit;
			$db->setQuery( $query );
	
			$result = $db->loadObjectList();
	
			return $result;
		}    
    
	function onProfileDisplay()
	{
		JPlugin::loadLanguage( 'plg_jomsocialmusic', JPATH_ADMINISTRATOR );
		
		$document		=& JFactory::getDocument();
		$document->addStyleSheet( JURI::root() . 'plugins/community/music/style.css' );
		$document->addStyleSheet( JURI::root() . 'components/com_jsmusic/css/app-player.css' );
		$document->addScript( JURI::root() . 'components/com_jsmusic/js/jquery-1.3.2.min.js' );
		$document->addScript( JURI::root() . 'components/com_jsmusic/js/jquery.colorbox.js' );
		$document->addScript( JURI::root() . 'components/com_jsmusic/js/jquery.jplayer.min.js' );
		$songs	= $this->_getSongs();
		$userid = JRequest::getVar('userid','','REQUEST');
		$this->loadUserParams();	
				
		CFactory::load( 'helpers' , 'user' );
		ob_start();
	
		$volume 	= $this->userparams->get('volume', '');
		$autoplay_setting 	= $this->userparams->get('autoplay', '');
if ($autoplay_setting == 1) {
  $autoplay = 'true';
  } else {
  $autoplay = 'false';
  }
	?>
<script type= "text/javascript">
  $(document).ready(function(){
    $(".playlist_app").colorbox({width:"550px", height:"500px", inline:true, href:"#playlist_box"});
  });  
</script>	
<script type= "text/javascript">
  $(document).ready(function(){
  var rand_play = 0; 
  var repeat = 0; 
	var playItem = 0;
	var myPlayList = [
        <?php
	$userid = $this->_user->id;
	$db		=& JFactory::getDBO();        
  $query = "SELECT * "
    .' FROM #__jsmusic'
    .' WHERE `userid`='.$userid.' ORDER BY date DESC';
  $db->setQuery($query);
  $song_list = $db->loadObjectList();	
        $i=0;	
    foreach ($song_list as $song_item) {               
              $song_url=$song_item->url;
              $song_artist=stripslashes($song_item->artist);
              $song_title=stripslashes($song_item->title);
              $song_id=$song_item->id;
              $song_hits=$song_item->hits;
              $song_name= $song_artist. ' - ' .$song_title;
              $link= JRoute::_('index.php?option=com_jsmusic&view=song&id=' . $song_id);
        echo '{name:"'.$song_name.' <div style=float:right;margin-right:25px;><a href='.$link.'><small>Go to song</small></a></div>",name_up:"'.$song_name.'",mp3:"'.$song_url.'"},';
              }; 
          $i++; 
        
        ?>
];

	var jpPlayTime = $("#jplayer_play_time");
	var jpTotalTime = $("#jplayer_total_time");
	var jpTotalTime1 = $("#jplayer_total_time1");
 
	$("#jquery_jplayer").jPlayer({
		ready: function() {
			displayPlayList();
			playListInit(<?php echo $autoplay; ?>); // Parameter is a boolean for autoplay.
		},
		volume: <?php echo $volume; ?>,
		swfPath: "<?php echo JURI::base();?>/components/com_jsmusic/js/"
	})
	.jPlayer("onProgressChange", function(loadPercent, playedPercentRelative, playedPercentAbsolute, playedTime, totalTime) {
		jpPlayTime.text($.jPlayer.convertTime(playedTime));
		jpTotalTime.text($.jPlayer.convertTime(totalTime));
		jpTotalTime1.text($.jPlayer.convertTime(totalTime));
	})
	.jPlayer("onSoundComplete", function() {
    if(repeat > 0){ 
        playListChange( playItem ); 
    }else{ 
        playListNext(); 
    } 
	});
$("#jp-shuffle-off").hide(); 
$("#jp-repeat-off").hide(); 
$("#jp-repeat-on").click(function() { 
        if(repeat < 1){ 
        show_repeat_off_Btn(); 
        repeat ++; 
        } 
}); 

$("#jp-repeat-off").click(function() { 
        if(repeat == 1){ 
        show_repeat_on_Btn(); 
        repeat --; 
        } 
}); 

$("#jp-shuffle-on").click(function() { 
        if(rand_play < 1){ 
        show_Shuffling_off_Btn(); 
        rand_play ++; 
        } 
}); 

$("#jp-shuffle-off").click(function() { 
        if(rand_play == 1){ 
        show_Shuffling_on_Btn(); 
        rand_play --; 
        } 
}); 	
	$("#jplayer_previous").click( function() {
		playListPrev();
		return false;
	});
	$("#jplayer_next").click( function() {
		playListNext();
		return false;
	});
	function displayPlayList() {
		for (i=0; i < myPlayList.length; i++) {
			$("#jplayer_playlist ul").append("<li id='jplayer_playlist_item_"+i+"'>"+ myPlayList[i].name +"</li>");
			$("#jplayer_playlist_item_"+i).data( "index", i ).click( function() {
				var index = $(this).data("index");
				if (playItem != index) {
					playListChange( index );
				} else {
					$("#jquery_jplayer").jPlayer("play");
				}
			});
		}
	}
	function playListInit(autoplay) {
		if(autoplay) {
			playListChange( playItem );
		} else {
			playListConfig( playItem );
		}
	}
	function playListConfig( index ) {
		$("#jplayer_playlist_item_"+playItem).removeClass("jplayer_playlist_current_app");
		$("#jplayer_playlist_item_"+index).addClass("jplayer_playlist_current_app");
		playItem = index;
		$("#jplayer_title_track").text(myPlayList[playItem].name_up);
		$("#jplayer_title_track1").text(myPlayList[playItem].name_up);
		$("#jquery_jplayer").jPlayer("setFile", myPlayList[playItem].mp3, myPlayList[playItem].ogg);
	}
function show_repeat_off_Btn() 
        { 
        $("#jp-repeat-on").fadeOut(function(){ 
        $("#jp-repeat-off").fadeIn(); 
        }); 
        } 
function show_repeat_on_Btn() 
        { 
        $("#jp-repeat-off").fadeOut(function(){ 
        $("#jp-repeat-on").fadeIn(); 
        }); 
        }
function show_Shuffling_off_Btn() 
        { 
        $("#jp-shuffle-on").fadeOut(function(){ 
        $("#jp-shuffle-off").fadeIn(); 
        }); 
        } 
function show_Shuffling_on_Btn() 
        { 
        $("#jp-shuffle-off").fadeOut(function(){ 
        $("#jp-shuffle-on").fadeIn(); 
        }); 
        } 	
	function playListChange( index ) {
		playListConfig( index );
		$("#jquery_jplayer").jPlayer("play");
	}
function playListNext() { 
        if(rand_play == 1){ //if random play 
        var rand = Math.floor(Math.random() * myPlayList.length); 
                if(myPlayList.length != 1){ //not only 1 track in the playlist 
                        if(playItem == rand){ //played track = new random track 
                        var fix_rand = Math.floor(Math.random() * myPlayList.length); 
                        playListChange( fix_rand ); //play another random track 
                        }else{ 
                        playListChange( rand ); 
                        } 
                }else{ 
                playListChange( rand ); 
                } 
        }else{ 
        var index = (playItem+1 < myPlayList.length) ? playItem+1 : 0; 
        playListChange( index ); 
        } 
}// end of playListNext() 
function playListPrev() { 
        if(rand_play == 1){ //if random play 
        var rand = Math.floor(Math.random() * myPlayList.length); 
                if(myPlayList.length != 1){//not only 1 track in the playlist 
                        if(playItem == rand){//played track = new random track 
                        var fix_rand = Math.floor(Math.random() * myPlayList.length); 
                        playListChange( fix_rand );//play another random track 
                        }else{ 
                        playListChange( rand ); 
                        } 
                }else{ 
                playListChange( rand ); 
                } 
        }else{ 
        var index = (playItem-1 >= 0) ? playItem-1 : myPlayList.length-1; 
        playListChange( index ); 
        } 
}// end of playListPrev() 
});
</script>

    <?php
	if($songs)
	{
		$i = 1;
		{
	?>
			<div id="jquery_jplayer"></div> 
 
			<div class="jp-playlist-player_app"> 
				<div class="jp-interface_app"> 
					<ul class="jp-controls_app"> 
						<li class="playlist_app">playlist</li> 
						<li id="jplayer_play" class="jp-play_app">play</li> 
						<li id="jplayer_pause" class="jp-pause_app">pause</li> 
						<li id="jplayer_stop" class="jp-stop_app">stop</li> 
						<span id="repeat-shuffle_app">
            <span id="jp-repeat-on"><img src="<?php echo JURI::base();?>/components/com_jsmusic/images/repeat_off_app.png" title="Turn repeat on"></span>
            <span id="jp-repeat-off"><img src="<?php echo JURI::base();?>/components/com_jsmusic/images/repeat_on_app.png" title="Turn repeat off"></span> 
            <span id="jp-shuffle-on"><img src="<?php echo JURI::base();?>/components/com_jsmusic/images/shuffle_off_app.png" title="Turn shuffle on"></span>
            <span id="jp-shuffle-off"><img src="<?php echo JURI::base();?>/components/com_jsmusic/images/shuffle_on_app.png" title="Turn shuffle off"></span> 
            </span>            
						<li id="jplayer_volume_min" class="jp-volume-min_app">min volume</li> 
            <div id="jplayer_volume_bar" class="jp-volume-bar_app"> 
              <div id="jplayer_volume_bar_value" class="jp-volume-bar-value_app"></div> 
            </div>
						<li id="jplayer_volume_max" class="jp-volume-max_app">max volume</li> 
						<li id="jplayer_previous" class="jp-previous_app">previous</li> 
						<li id="jplayer_next" class="jp-next_app">next</li> 
					</ul> 
					<div class="jp-progress_app"> 
						<div id="jplayer_load_bar" class="jp-load-bar_app"> 
							<div id="jplayer_play_bar" class="jp-play-bar_app"></div> 
						</div> 
					</div> 
					<div id="jplayer_play_time" class="jp-play-time_app"></div>
					<div id="jplayer_total_time" class="jp-total-time_app"></div> 
				</div> 
			</div> 

		<div style='display:none'>
			<div id='playlist_box' style='padding:10px; background:#fff;'>		
				<div id="jplayer_playlist" class="jp-playlist_app"> 
				<ul> 
					<!-- The function displayPlayList() uses this unordered list --> 
					<li></li> 
				</ul> 
			</div> 
      </div>
		</div>

<table class="table1">
	<thead>
		<tr>
			<th scope="col" abbr="Artist">Artist</th>
			<th scope="col" abbr="Title">Title</th>
			<th scope="col" abbr="Genre">Genre</th>
			<th scope="col" abbr="Added">Added</th>
		</tr>
	</thead>
	<tbody>
					<?php foreach( $songs as $song ): 
					$date = $song->date;
					$session_time= strtotime("$date");
			$db		=& JFactory::getDBO();					
			$query	= 'SELECT * FROM ' . $db->nameQuote( '#__jsmusic_genres' )
					. 'WHERE ' . $db->nameQuote( 'id' ) . '=' . $db->Quote( $song->genreid );
			$db->setQuery( $query );
			$row = $db->loadObject();					
			$genre = $row->name;		
			$query	= 'SELECT * FROM ' . $db->nameQuote( '#__jsmusic_artists' )
					. 'WHERE ' . $db->nameQuote( 'name' ) . '=' . $db->Quote( $song->artist );
			$db->setQuery( $query );
			$row = $db->loadObject();					
			$artist_id = $row->id;							
					?>
		<tr>
			<td width="30%"><a href="<?php echo JRoute::_('index.php?option=com_jsmusic&view=artist&id=' . $artist_id); ?>"><?php echo $song->artist; ?></a></td>
			<td width="30%"><a href="<?php echo JRoute::_('index.php?option=com_jsmusic&view=song&id=' . $song->id); ?>"><?php echo $song->title; ?></a></td>
			<td width="20%"><a href="<?php echo JRoute::_('index.php?option=com_jsmusic&view=genre&id=' . $song->genreid); ?>"><?php echo $genre; ?></a></td>
			<td width="20%"><?php echo time_stamp($session_time); ?></td>
		</tr>						
						<div style="clear: both;"></div>
					<?php endforeach; ?>

	<?php
			$i++;
		}
?>		
	</tbody>
</table>			
<?php } else { 
  $add_song_link = JRoute::_('index.php?option=com_jsmusic&view=song&layout=form');
?>
<table class="table1">
	<tbody>
    <tr>
<?php if($this->_my->id == $this->_user->id) { ?> 
			<td width="100%">No songs added. <a href="<?php echo $add_song_link; ?>">Add songs now!</a></td>
<?php } else { ?>			
			<td width="100%">No songs added.</td>
<?php } ?>			
    </tr>
	</tbody>
</table>		
<?php } ?>	
	<div style="clear: both;"></div>
	<?php
		$content	= ob_get_contents();
		@ob_end_clean();
		
		return $content;
	}
}