<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

if (count($this->items)) {
    $utility = MijoVideos::get('utility');
    $thumb_size = $utility->getThumbSize($this->config->get('thumb_size'));
    $k = 0;
    $n = count($this->items);
    for ($i = 0; $i < $n; $i++) {
        $item = $this->items[$i];
        if (empty($item->videos)) continue;
        $this->Itemid = MijoVideos::get('router')->getItemid(array('view' => 'playlist', 'playlist_id' => $item->id), null, true);
        $playlist_url = JRoute::_('index.php?option=com_mijovideos&view=playlist&playlist_id='.$item->id.$this->Itemid);
        $channel_url = JRoute::_('index.php?option=com_mijovideos&view=channel&channel_id='.$item->channel_id.$this->Itemid);
        $video_id = empty($item->videos) ? '' : $item->videos[0]->video_id;
        if(!empty($item->thumb)) {
            $thumb = $utility->getThumbPath($item->id, 'playlists', $item->thumb);
        } else {
            $thumb = $utility->getThumbPath($item->videos[0]->video_id, 'videos', $item->videos[0]->thumb);
        }
		$url = JRoute::_('index.php?option=com_mijovideos&view=video&video_id='.$video_id.'&playlist_id='.$item->id.$this->Itemid); ?>
        <div class="videos-items-list-box">
            <div class="playlists-list-item" style="width: <?php echo $thumb_size; ?>px">
                <div class="videos-aspect<?php echo $this->config->get('thumb_aspect'); ?>"></div>
                <a href="<?php echo $url; ?>">
                    <img class="videos-items-grid-thumb" src="<?php echo $thumb; ?>" alt="<?php echo $item->thumb; ?>"/>
                </a>
            </div>
            <div class="playlists-items-list-box-content">
                <h3 class="mijovideos_box_h3">
                    <a href="<?php echo $url; ?>" title="<?php echo $item->title; ?>">
                        <?php echo $this->escape(JHtmlString::truncate($item->title, $this->config->get('title_truncation'), false, false)); ?>
                    </a>
                </h3>
                <div class="playlists-meta">
                    <div class="mijovideos-meta-info">
                        <div class="created_by">
							<span class="key"><?php echo JText::_('COM_MIJOVIDEOS_CREATED_BY'); ?></span>
							<span class="value">
                                <a href="<?php echo $channel_url; ?>" title="<?php echo $item->channel_title; ?>">
                                    <?php echo $this->escape(JHtmlString::truncate($item->channel_title, $this->config->get('title_truncation'), false, false)); ?>
                                </a>
                            </span>
                        </div>
                        <div class="date-created">
                            <span class="key"><?php echo JText::_('COM_MIJOVIDEOS_DATE_CREATED'); ?></span>
                            <span class="value"><?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="playlists-items">
                    <?php $j = 0;
                    foreach($item->videos as $video) { $j++; ?>
                           <div class="playlists-item">
                               <a href="<?php echo JRoute::_('index.php?option=com_mijovideos&view=video&video_id='.$video->video_id.$this->Itemid); ?>">
                                   <?php echo $this->escape(JHtmlString::truncate($video->title, $this->config->get('title_truncation'), false, false)); ?>
                               </a>
                               <span class="mijovideos-duration"><?php echo $utility->secondsToTime($video->duration); ?></span>
                               <?php if ($j == 2) break; ?>
                           </div>
                    <?php } ?>
                </div>
                <div class="playlists-meta">
                    <div class="mijovideos-meta-info">
                        <a class="date-created" href="<?php echo $playlist_url; ?>"><?php echo JTEXT::_('COM_MIJOVIDEOS_VIEW_PLAYLIST'); ?>&nbsp;&nbsp;(<?php echo isset($item->total) ? $item->total : '0' ; ?>&nbsp;<?php echo JTEXT::_('COM_MIJOVIDEOS_VIDEOS')?>)</a>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        var box_width = document.getElementById("adminForm").offsetWidth;
        var thumb_size = <?php echo $thumb_size; ?>;
        var thumb_percent = Math.round((thumb_size/box_width)*100);
        var desc_percent = 100 - thumb_percent - 3;
        jQuery('div[class^="playlists-items-list-box-content"]').css('width', desc_percent+'%');
        jQuery('div[class^="playlists-list-item"]').css('width', thumb_percent+'%');
    });
</script>