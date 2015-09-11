<?php
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'components/com_mijovideos/assets/css/perfect-scrollbar.css');
$document->addScript(JUri::root() . 'components/com_mijovideos/assets/js/jquery.mousewheel.js');
$document->addScript(JUri::root() . 'components/com_mijovideos/assets/js/perfect-scrollbar.js');
?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#mijovideos_video_player_playlist').perfectScrollbar({suppressScrollX: true});
        var offsetTop = jQuery('.mijovideos_playing')[0].offsetTop;
        document.getElementById('mijovideos_video_player_playlist').scrollTo(0,offsetTop);
        document.getElementById("mijovideos_video_player_playlist").style.height = document.getElementById("mijovideos_video_player_playlist").style.height - 30;
    });
</script>
<div id="mijovideos_video_player_playlist" class="mijovideos_video_player_playlist">
    <ul id="mijovideos_playlist">
    <?php
    $count = count($this->playlistvideos);
    foreach($this->playlistvideos as $key => $playlistvideo) {
        $playing = ($this->item->id == $playlistvideo->video_id) ? true : false;
        if($playing) {

            if ( $key+1 == $count ) {
                $forward_url  = $_url = JRoute::_('index.php?option=com_mijovideos&view=video&playlist_id='.$playlistvideo->playlist_id.'&video_id='.$this->playlistvideos[0]->video_id.$this->Itemid);
            }
            else {
                $forward_url  = $_url = JRoute::_('index.php?option=com_mijovideos&view=video&playlist_id='.$playlistvideo->playlist_id.'&video_id='.$this->playlistvideos[$key+1]->video_id.$this->Itemid);
            }

            if( $key == 0 ) {
                $backward_url = $_url = JRoute::_('index.php?option=com_mijovideos&view=video&playlist_id='.$playlistvideo->playlist_id.'&video_id='.$this->playlistvideos[$count-1]->video_id.$this->Itemid);
            }
            else {
                $backward_url = $_url = JRoute::_('index.php?option=com_mijovideos&view=video&playlist_id='.$playlistvideo->playlist_id.'&video_id='.$this->playlistvideos[$key-1]->video_id.$this->Itemid);
            }
        }

        $_url = JRoute::_('index.php?option=com_mijovideos&view=video&playlist_id='.$playlistvideo->playlist_id.'&video_id='.$playlistvideo->video_id.$this->Itemid); ?>

            <li <?php if($playing) echo 'class = "mijovideos_playing"'  ?>>
                <a href="<?php echo $_url; ?>">
                  <div><?php if(!$playing) echo ($key+1); else echo '▶'; ?></div>
                  <img class="video_thump" src="<?php echo $playlistvideo->thumb; ?>">
                  <span><?php echo $playlistvideo->title;  ?></span></a>

            </li>
    <?php } ?>
        <li class="mijovideos_li_bottom"></li>
    </ul>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
        var myPlayer = videojs("plg_videojs_1");

        myPlayer.ready(function(){
            this.play();

            this.on("ended", function(){
                window.location = "<?php echo $forward_url; ?>";
            });
        });
    });
</script>

<div id="mijovideos-control-bar" class="mijovideos-control-bar">
    <a href="<?php echo $forward_url; ?>"><div class="mijovideos_forward"></div></a>
    <a href="<?php echo $backward_url; ?>"><div class="mijovideos_backward"></div></a>
    <span>Playlist : <?php echo substr($playlistvideo->title,0, 25);  if(strlen($playlistvideo->title) > 25) echo '...';?></span>
</div>