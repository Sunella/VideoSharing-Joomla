<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

if (count($this->items)) {
    foreach ($this->checksubscription AS $checksubscription) {
        $subs[] = $checksubscription['item_id'];
    }
    $utility = MijoVideos::get('utility');
    $n = count($this->items);
    for ($i = 0; $i < $n; $i++) {
        $item = $this->items[$i];
        if (empty($item->videos)) {
            continue;
        }
        $url = JRoute::_('index.php?option=com_mijovideos&view=channel&channel_id='.$item->id.$this->Itemid); ?>
        <div class="mijovideos_channels_grid">
            <a href="<?php echo $url; ?>">
                <img class="mijovideos_channels_box_content_img2" src="<?php echo $utility->getThumbPath($item->id, 'channels', $item->thumb); ?>" title="<?php echo $item->title; ?>" alt="<?php echo $item->title; ?>"/>
            </a>
            <div class="mijovideos_channels_box_content">
                <div class="mijovideos_channels_box_content_header">
                    <h3 class="mijovideos_box_h3">
                        <a href="<?php echo $url; ?>" title="<?php echo $item->title; ?>">
                            <?php echo $this->escape(JHtmlString::truncate($item->title, $this->config->get('title_truncation'), false, false)); ?>
                        </a>
                    </h3>
                    <?php if($this->config->get('subscriptions')) { ?>
                        <div class="mijovideos_subscribe" id="<?php echo $item->id; ?>">
                            <?php if (isset($subs)) { ?>
                                <?php if (in_array($item->id, $subs)) { ?>
                                    <a class="<?php echo MijoVideos::getButtonClass(); ?> subscribe" style="display:none" id="subscribe_button">
                                        <?php echo JText::_('COM_MIJOVIDEOS_SUBSCRIBE'); ?>
                                    </a>
                                    <div class="mijovideos_subscribe_count" style="display:none" id="subs_count<?php echo $item->id ?>"><span class="subs_count"><?php echo is_null($item->subs) ? '0' : number_format($item->subs); ?></span></div>
                                    <div style="visibility:hidden" class="subs_nub"><s></s><i></i></div>
                                    <a class="<?php echo MijoVideos::getButtonClass(); ?> subscribed" id="unsubscribe_button"><?php echo JText::_('COM_MIJOVIDEOS_SUBSCRIBED'); ?></a>
                                <?php } else { ?>
                                    <a class="<?php echo MijoVideos::getButtonClass(); ?> subscribe" id="subscribe_button">
                                        <?php echo JText::_('COM_MIJOVIDEOS_SUBSCRIBE'); ?>
                                    </a>
                                    <div class="mijovideos_subscribe_count" id="subs_count<?php echo $item->id ?>"><span class="subs_count"><?php echo is_null($item->subs) ? '0' : number_format($item->subs); ?></span></div>
                                    <div class="subs_nub"><s></s><i></i></div>
                                    <a class="<?php echo MijoVideos::getButtonClass(); ?> subscribed" style="display:none" id="unsubscribe_button"><?php echo JText::_('COM_MIJOVIDEOS_SUBSCRIBED'); ?></a>
                                <?php } ?>
                            <?php } else { ?>
                                <a class="<?php echo MijoVideos::getButtonClass(); ?> subscribe" id="subscribe_button">
                                    <?php echo JText::_('COM_MIJOVIDEOS_SUBSCRIBE'); ?>
                                </a>
                                <div class="mijovideos_subscribe_count" id="subs_count<?php echo $item->id ?>"><span class="subs_count"><?php echo isset($item->subs) ? number_format($item->subs) : '0'; ?></span></div>
                                <div class="subs_nub"><s></s><i></i></div>
                                <a class="<?php echo MijoVideos::getButtonClass(); ?> subscribed" style="display:none" id="unsubscribe_button"><?php echo JText::_('COM_MIJOVIDEOS_SUBSCRIBED'); ?></a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
                <?php echo $this->escape(JHtmlString::truncate($item->introtext, $this->config->get('desc_truncation'), false, false)); ?>
                <div class="mijovideos_preview_videos">
                    <div class="mijovideos_preview_videos_title"><?php echo JText::_('COM_MIJOVIDEOS_PREVIEW_VIDEOS');?></div>
                    <?php foreach ($item->videos as $video) { ?>
                        <?php $url = JRoute::_('index.php?option=com_mijovideos&view=video&video_id='.$video->id.$this->Itemid); ?>
                        <div class="mijovideos_preview">
                            <a href="<?php echo $url; ?>">
                                <img class="mijovideos_thumb_100" src="<?php echo $utility->getThumbPath($video->id, 'videos', $video->thumb); ?>" title="<?php echo $video->title; ?>" alt="<?php echo $video->title; ?>"/>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>

<script type="text/javascript"><!--
    jQuery('#subscribe_button, #unsubscribe_button').click(function() {
        var clicked_button = jQuery(this).attr('id').replace("_button","");
        var id = jQuery(this).parent().attr("id");
        jQuery.ajax({
            url: 'index.php?option=com_mijovideos&view=channels&task=subscribeToItem&format=raw',
            type: 'post',
            data: {type: clicked_button , id: id},
            dataType: 'json',
            success: function(json) {
                if (json['success']) {
                    var subs = jQuery('#subs_count'+id);
                    var count = subs.children().text();
                    if(clicked_button == "unsubscribe") {
                        jQuery('#'+id+' #unsubscribe_button').hide();
                        jQuery('#'+id+' #subscribe_button').show();
                        subs.children().text(parseInt(count) - 1);
                        subs.show();
                        subs.next().css('visibility', 'visible');
                    } else {
                        jQuery('#'+id+' #subscribe_button').hide();
                        jQuery('#'+id+' #unsubscribe_button').show();
                        subs.children().text(parseInt(count) + 1);
                        subs.hide();
                        subs.next().css('visibility', 'hidden');
                    }
                }
                if (json['redirect']) {
                    location = json['redirect'];
                }
                if (json['error']) {
                    jQuery('#notification').html('<div class="mijovideos_warning" style="display: none;">' + json['error'] + '</div>');
                    jQuery('.mijovideos_warning').fadeIn('slow');
                    jQuery('.mijovideos_box, body').animate({ scrollTop: 0 }, 'slow');
                    jQuery('.mijovideos_warning').delay(5000).fadeOut('slow');
                }
            }
        });
    });
    jQuery(document).ready(function() {
        jQuery('.subscribed').hover(
            function() {
                jQuery(this).text("<?php echo JText::_('COM_MIJOVIDEOS_UNSUBSCRIBE'); ?>");
            }, function() {
                jQuery(this).text("<?php echo JText::_('COM_MIJOVIDEOS_SUBSCRIBED'); ?>");
            }
        );
    });
    //--></script>