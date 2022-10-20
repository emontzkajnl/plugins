<?php 
/**
 * ILFB Podcast Player Block
 */

 

$id = 'podcast-player-' . $block['id'];

$className = 'podcast-player';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 

?>
<style>
    /* Player Styles */
#player{
    width: 300px;
    margin: auto;
    box-shadow: 1px 5px 5px #888888;
}
#player-top{
    padding: 10px 0px 10px 0px;
    height: initial;
    background-color: white;
}
#player-art{
    padding-left: 5px;
    padding-top: 5px;
    background-color: white;
}
#slash{
    color: #3b3b3b;
    font-size: 12px;
    font-weight: bold;
    text-shadow: 1px 1px #ffffff;
    font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
    margin-top: 20px;
}
#track-info-container{
    float: left;
    font-size: 10px;
    width: initial;
    overflow: hidden;
    margin-left: 10px;
}
#time-info-container{
    float: right;
    font-size: 10px;
    line-height: 19px;
    margin-top:1px;
}
#player-bottom{
    background-color: white;
    height: 56px;
    text-align:center;
    vertical-align:middle;
    font-family: "museo-sans", helvetica, sans-serif !important;
	color:#0eaaa5;
	padding:10px 0px;
}
#player-bottom img {
    vertical-align:middle;
    margin-left:8px;
}
#player-bottom a.button {
	color:#0eaaa5;
	display:block;
	border:#0eaaa5 1px solid;
	padding:6px 4px;
	width:initial;
	max-width:250px !important;
	margin:0px auto 10px;
	border-radius: 5px;
}
.control{
    width: 70px;
    height: 35px;
    float: left;
    font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
    color: #3b3b3b;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 12px;
    text-align: center;
    padding: 5px;
    line-height: 35px;
    cursor: pointer;
}
.control:nth-child(2){
    border-left: 2px solid #ccc;
    border-right: 2px solid #ccc;
}
.control:nth-child(3){
    border-right: 2px solid #ccc;
}
#shuffle-on-image{
    display: none;
}
#player-playlist{
    width: 100%;
    background-color: #f8f8f8;
    display: block;
    color: #111;
    font-family: "museo-sans", helvetica, sans-serif !important;
    font-size: 11px;
    overflow: hidden;
}
.playlist-song{
    clear: both;
    float: left;
    margin-bottom: 3px;
    width: 100%;
}
.playlist-song:hover{
    background-color: #0eaaa5;
    cursor:pointer;
    color:#000;
}
.ILFBPodcastPlayer .sidebar-widget-header {
	display:none;
}
.playlist-song-album-art{
    float: left;
}
.playlist-song-album-art img{
    width: 50px;
    height: 50px;
}
.playlist-song-information{
    float: left;
    margin-left: 10px;
}
/* Amplitude Element Styles */
#amplitude-play-pause{
    width: 36px;
    height: 36px;
    cursor: pointer;
    float: left;
}
.amplitude-paused{
    background-image: url('../images/yellow-play.png');
    background-repeat: no-repeat;
    background-size:36px 36px;
}
.amplitude-playing{
    background-image: url('../images/yellow-pause.png');
    background-repeat: no-repeat;
    background-size:36px 36px;
}
#amplitude-now-playing-artist{
    font-family: "museo-sans", helvetica, sans-serif !important;
    font-size: 14px;
}
#amplitude-now-playing-title{
    color: #3b3b3b;
    font-size: 12px;
    font-weight: bold;
    text-shadow: 1px 1px #ffffff;
    font-family: "museo-sans", helvetica, sans-serif !important;
    margin-top: 20px;
}
#amplitude-current-time{
    font-family: "museo-sans", helvetica, sans-serif !important;
}
#amplitude-audio-duration{
    font-family: "museo-sans", helvetica, sans-serif !important;
}
#amplitude-song-slider{
    display: inline-block;
    height: 10px;
    border-radius: 5px;
    width: 150px;
    background-color: rgba(237,237,237,.8);
    margin-left: 10px;
    margin-top: 5px;
    clear:left;
}
#amplitude-track-progress{
    background-color: #9bb665;
    height: 10px;
    border-radius: 5px;
    width: 0px;
}
#amplitude-album-art{
    width: 324px;
    height: 324px;
}
.amplitude-album-art-image{
    width: 324px;
    height: 324px;
}
.amplitude-play-playlist{
    float: left;
    cursor: pointer;
    margin-top: 8px;
    margin-left: 16px;
    padding-left: 24px;
    width: 100%;
    height: 24px;
    background-image: url('../images/yellow-play-small.png');
    background-repeat: no-repeat;
}
.amplitude-now-playing{
    background-color: #eee;
}
</style>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<div class="podcast-player__left">
    <h3 class="podcast-player__title">List To Our Partners Podcast</h3>
    <p class="podcast-player__description">Farm, Family and food-related places, events and issues</p>
    <button><a href="" class="podcast-player__button">Latest Episode</a></button>
</div>
<div class="podcast-player__right">
<?php
    $htmlaudio = "";
    $htmlplaylst = "";
    $url = "http://www.stretchinternet.com/rfd/podcasts/feed.php";
    
    if (simplexml_load_file($url) !== false):
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
    } ?>
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
endif;
?>
    
</div>
</div>