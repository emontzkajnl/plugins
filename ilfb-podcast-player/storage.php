<?php
/*
Plugin Name: ILFB Podcast Player
Plugin URI: http://jnlcom.com
Description: Adds a widget for an HTML5 player and playlist
Version: 1.1
Author: JCI Developers
Author URI: http://jnlcom.com
License: GPL2
*/

class ILFBPodcastPlayer extends WP_Widget
{
  function ILFBPodcastPlayer()
  {
    $widget_ops = array('classname' => 'ILFBPodcastPlayer', 'description' => 'Displays a Podcast Player' );
    $this->WP_Widget('ILFBPodcastPlayer', 'ILFB Podcast Player', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
?>

  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 ?>

<?php
    // PLAYER
			$htmlaudio = "";
			$htmlplaylst = "";
			$url = "http://www.stretchinternet.com/rfd/podcasts/feed.php";
			$xml = simplexml_load_file($url);
			$listnum = 0;
			for ($i = 0; $i < 15; $i++) {
				$title = $xml->channel->item[$i]->title;
				$filename = $xml->channel->item[$i]->guid;
				$episode = "-c.mp3";
				$towncountry = strpos($filename, $episode);
					if ($towncountry === FALSE) {
					} else {
					$toptitle = $xml->channel->item[0]->title;
					$listnum++ ;
						$htmlplaylist .= "<div class='playlist-song' id='song-". $listnum ."'><div class='amplitude-play-playlist' amplitude-song-id='". $listnum ."'> $title  </div></div>";
						$htmlaudio .= "<audio id='". $listnum ."' amplitude-audio-type='song' amplitude-title='". $title ."' amplitude-visual-element-id='song-". $listnum ."' ><source src='" . $filename . "' type='audio/mpeg'></audio>";
					}	
			}
?>
	<script type="text/javascript" src="/wp-content/plugins/ilfb-podcast-player/js/amplitude.js"></script>
	<link rel="stylesheet" type="text/css" href="/wp-content/plugins/ilfb-podcast-player/css/playlist-styles.css"/>
	<div id="player">
		<img src="/wp-content/plugins/ilfb-podcast-player/images/ilfb-town-country-podcast-header.png">
		<div id="player-top">
			<div id="amplitude-play-pause" class="amplitude-paused"></div>
			<div id="track-info-container">
				<span id="amplitude-now-playing-title">Select an episode below</span>
			</div>
	        <div id="amplitude-song-slider"><div id="amplitude-track-progress"></div></div>
			<div id="time-info-container">
	            <span id="amplitude-current-time">0:00</span> / <span id="amplitude-audio-duration">0:00</span>
	        </div>
		</div>
		<div id="player-playlist">
		<?php echo $htmlplaylist; ?>
	    </div>
		<div id="player-bottom">
			<a href="" class="button"  onclick="window.open('http://client.stretchinternet.com/client/rfd.portal#','',' scrollbars=no,menubar=no,width=500, resizable=yes,toolbar=no,location=no,status=no');" >Click to listen live | 2:30pm CT | M-Th</a>
			Subscribe to this podcast:<a href="itpc://www.stretchinternet.com/rfd/podcasts/feed.php"><img src="/wp-content/plugins/ilfb-podcast-player/images/ilfbitunesicon.png" rel="bookmark" title="Subscribe via iTunes"></a><a href="http://www.stretchinternet.com/rfd/podcasts/feed.php"><img src="/wp-content/plugins/ilfb-podcast-player/images/ilfbrssicon.png" rel="bookmark" title="Subscribe via RSS"></a>
	    </div>
	</div>

		<?php
			echo '<div id="amplitude-playlist">' . $htmlaudio . '</div>';	

    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("ILFBPodcastPlayer");') );?>