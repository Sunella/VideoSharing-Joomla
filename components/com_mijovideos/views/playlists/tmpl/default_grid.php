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
    $n = count($this->items);
    for ($i = 0; $i < $n; $i++) {
        $item = $this->items[$i];

        if (empty($item->videos)) {
            continue;
        } else {
            $video_id = $item->videos[0]->video_id;
        }

        if(!empty($item->thumb)) {
            $thumb = $utility->getThumbPath($item->id, 'playlists', $item->thumb);
        } else {
            $thumb = $utility->getThumbPath($video_id, 'videos', $item->videos[0]->thumb);
        }
		$url = JRoute::_('index.php?option=com_mijovideos&view=video&video_id='.$video_id.'&playlist_id='.$item->id.$this->Itemid);
		$channel_url = JRoute::_('index.php?option=com_mijovideos&view=channel&channel_id='.$item->channel_id.$this->Itemid); ?>
        <div class="mijovideos_column<?php echo $this->config->get('items_per_column'); ?>">
            <div class="videos-grid-item">
                <div class="videos-aspect<?php echo $this->config->get('thumb_aspect'); ?>"></div>
                <a href="<?php echo $url; ?>">
                    <img class="videos-items-grid-thumb" src="<?php echo $thumb; ?>" alt="<?php echo $item->title; ?>"/>
                </a>
            </div>
            <div class="playlists-items-grid-box-content">
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
            </div>
        </div>
    <?php } ?>
<?php } ?>