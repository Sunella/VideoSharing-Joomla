<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;
$url = JRoute::_('index.php?option=com_mijovideos&view=channel&channel_id='.$this-> item->id.$this->Itemid);
$utility = MijoVideos::get('utility');
?>
<?php if (($this->params->get('show_page_heading', '0') == '1')) { ?>
    <?php $page_title = $this->params->get('page_title', ''); ?>
    <?php if (!empty($this->item->title)) { ?>
        <h1><?php echo $this->item->title;?></h1>
    <?php } else if (!empty($page_title)) { ?>
        <h1><?php echo $page_title; ?></h1>
    <?php } ?>
<?php } ?>
<div id="notification"></div>
<div class="mijovideos_box">
    <div class="mijovideos_box_heading">
        <h1 class="mijovideos_box_h1"><?php echo $this->item->title; ?></h1>
    </div>

    <div class="mijovideos_box_content">
        <!-- content -->
        <?php if ($this->item->banner and file_exists(JPATH_ROOT.'/media/com_mijovideos/images/channels/' . $this->item->id . '/banner/thumb/' . $this->item->banner)) {
            $background_image = "url(media/com_mijovideos/images/channels/" . $this->item->id . "/banner/thumb/" .$this->item->banner.") no-repeat;";
        } else {
            $background_image = "";
        } ?>
        <div class="banner_image" style="background: <?php echo $background_image; ?>; background-repeat: round;">
            <a href="<?php echo $url; ?>">
                <img class="channel-items-list-thumb" src="<?php echo $utility->getThumbPath($this->item->id, 'channels', $this->item->thumb); ?>" title="<?php echo $this->item->title; ?>" alt="<?php echo $this->item->title; ?>"/>
            </a>
        </div>
        <div class="mijovideos_channel_header">
            <div class="mijovideos_channel_title">
                <h1><a href="<?php echo JRoute::_('index.php?option=com_mijovideos&view=channel&channel_id='.$this->item->id.$this->Itemid); ?>"><?php echo $this->item->title; ?></a></h1>
            </div>
            <?php if (MijoVideos::get('channels')->getDefaultChannel()->id == $this->item->id or $this->item->share_others) { ?>
                <span class="mijovideos_upload">
                    <a href="<?php echo MijoVideos::get('utility')->route(JUri::base() . 'index.php?option=com_mijovideos&view=upload&channel_id='.$this->item->id.'&dashboard=1')?>" class="<?php echo MijoVideos::getButtonClass(); ?>">
                        <?php echo JText::_('COM_MIJOVIDEOS_UPLOAD'); ?>
                    </a>
                </span>
            <?php } ?>
            <?php if($this->config->get('subscriptions')) { ?>
                <div class="mijovideos_subscribe" id="<?php echo $this->item->id ?>">
                    <?php if (is_null($this->checksubscription)) { ?>
                        <a class="<?php echo MijoVideos::getButtonClass(); ?> subscribe" id="subscribe_button">
                            <?php echo JText::_('COM_MIJOVIDEOS_SUBSCRIBE'); ?>
                        </a>
                        <a class="<?php echo MijoVideos::getButtonClass(); ?> subscribed" style="display:none" id="unsubscribe_button"><?php echo JText::_('COM_MIJOVIDEOS_SUBSCRIBED'); ?></a>
                    <?php } else { ?>
                        <a class="<?php echo MijoVideos::getButtonClass(); ?> subscribe" style="display:none" id="subscribe_button">
                            <?php echo JText::_('COM_MIJOVIDEOS_SUBSCRIBE'); ?>
                        </a>
                        <a class="<?php echo MijoVideos::getButtonClass(); ?> subscribed" id="unsubscribe_button"><?php echo JText::_('COM_MIJOVIDEOS_SUBSCRIBED'); ?></a>
                    <?php } ?>
                    <div class="mijovideos_subscribe_count" id="subs_count<?php echo $this->item->id ?>"><span class="subs_count"><?php echo number_format($this->item->subs); ?></span></div>
                    <div class="subs_nub"><s></s><i></i></div>
                </div>
            <?php } ?>
        </div>
        <form method="post" name="adminForm" id="adminForm" action="<?php echo JRoute::_('index.php?option=com_mijovideos&channel_id='.$this->item->id.'&view=channel'.$this->Itemid); ?>">
            <div class="mijovideos_searchbox">
                <input type="text" name="mijovideos_search" id="mijovideos_search" placeholder="Search..." value="<?php echo empty($this->lists['search']) ? "" : $this->lists['search']; ?>" onchange="document.adminForm.submit();" />
            </div>
            <?php echo JHtml::_('tabs.start', 'left', array('useCookie'=>1)); ?>
            <?php echo JHtml::_('tabs.panel', JText::_('COM_MIJOVIDEOS_VIDEOS'), 'sl_videos'); ?>

            <div class="mijovideos_subheader">
                <div class="mijovideos_filter_videos"><?php echo $this->lists['filter_videos']; ?></div>
                <div class="mijovideos_filter_videos"><?php echo $this->lists['order']; ?></div>
                <div class="mijovideos_flow_select">
                    <?php  $grid = $list = '';
                    if(strpos($this->display, 'grid') !== false) {
                        $grid = 'active';
                    } else {
                        $list = 'active';
                    } ?>
                    <a class="<?php echo MijoVideos::getButtonClass(); ?> <?php echo $grid; ?>" href="<?php echo JRoute::_('index.php?option=com_mijovideos&view=channel&channel_id='.$this->item->id.'&display=grid'.$this->Itemid); ?>" title="<?php echo JText::_('COM_MIJOVIDEOS_GRID'); ?>"><?php echo JText::_('COM_MIJOVIDEOS_GRID'); ?></a>
                    <a class="<?php echo MijoVideos::getButtonClass(); ?> <?php echo $list; ?>" href="<?php echo JRoute::_('index.php?option=com_mijovideos&view=channel&channel_id='.$this->item->id.'&display=list'.$this->Itemid); ?>" title="<?php echo JText::_('COM_MIJOVIDEOS_LIST'); ?>"><?php echo JText::_('COM_MIJOVIDEOS_LIST'); ?></a>
                </div>
            </div>

            <input type="hidden" name="option" value="com_mijovideos" />
            <input type="hidden" name="view" value="channel" />
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="channel_id" value="<?php echo $this->item->id; ?>" />
            <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
            <?php echo JHtml::_('form.token'); ?>
            <div class="clr"></div>
            <div id="channel_items">
                <?php echo $this->loadTemplate($this->display); ?>
            </div>
            <?php if($this->total_items > $this->config->get('videos_per_page')) { ?>
                <div class="video_more">
                    <button type="button" id="loadmore" class="video_more_buttons"><?php echo JText::_('COM_MIJOVIDEOS_LOAD_MORE'); ?></button>
                    <input id="pages" type="hidden" value="<?php echo $this->total_pages; ?>">
                </div>
            <?php } ?>
        </form>
        <?php if ($this->config->get('comments') != "0") { ?>
            <?php echo JHtml::_('tabs.panel', JText::_('COM_MIJOVIDEOS_COMMENTS'), 'sl_comments'); ?>
            <?php MijoVideos::get('utility')->trigger('getComments', array($this->item->id, $this->item->title)); ?>
        <?php } ?>
        <?php echo JHtml::_('tabs.panel', JText::_('COM_MIJOVIDEOS_DESCRIPTION'), 'sl_description'); ?>
        <div class="mijovideos_description"><?php echo  $this->item->description; ?></div>
        <div class="mijovideos_description_stats">
            <?php if($this->config->get('custom_fields')) { ?>
                <div class="mijovideos_custom_fields">
                    <?php
                    if (!empty($this->fields)) {
                        foreach ($this->fields as $field) { ?>
                            <div class="title">
                                <?php echo $field->title; ?>
                            </div>
                            <div class="content">
                                <?php echo str_replace('***', ', ', $field->field_value); ?>
                            </div>
                            <br />
                        <?php }
                    } ?>
                </div>
            <?php } ?>
            <?php if($this->config->get('subscriptions')) { ?>
                <div class="title"><?php echo number_format($this->item->subs); ?></div> <?php echo $this->item->subs > 1 ? JText::_('COM_MIJOVIDEOS_SUBSCRIBERS') : JText::_('COM_MIJOVIDEOS_SUBSCRIBER'); ?><br>
            <?php } ?>
            <div class="title"><?php echo number_format($this->item->hits); ?></div> <?php echo $this->item->hits > 1 ? JText::_('COM_MIJOVIDEOS_VIEW') : JText::_('COM_MIJOVIDEOS_VIEWS'); ?><br>
            <div class="content"><?php echo JText::_('COM_MIJOVIDEOS_JOINED'); ?> <?php echo JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC4')); ?></div>
        </div>
        <!-- content // -->
    </div>
</div>
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
                    } else {
                        jQuery('#'+id+' #subscribe_button').hide();
                        jQuery('#'+id+' #unsubscribe_button').show();
                        subs.children().text(parseInt(count) + 1);
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
<script>
    jQuery(function(){
        var page = 1;
        var pages = jQuery("#pages").val(); //TOTAL NUMBER OF PAGES

        //WHEN THE 'LOAD MORE' BUTTON IS PRESSED
        jQuery("#loadmore").live("click", function(){
            var next = page+=1;

            jQuery.ajax({
                url: 'index.php?option=com_mijovideos&view=channel&format=raw&channel_id=<?php echo $this->item->id; ?>',
                type: 'post',
                data: 'page='+next,
                dataType: 'json',
                success: function(json) {
                    if(next==pages){
                        jQuery("#loadmore").remove();
                    }
                    jQuery("#channel_items").append(json);
                }
            });
        });
    });
</script>