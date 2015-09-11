<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ; ?>
<?php if (count($this->playlistitems)) { ?>
    <div class="playlist_notification"></div>
    <h4><?php echo JText::_('COM_MIJOVIDEOS_ADD_TO_PLAYLIST'); ?></h4>
    <div class="mijovideos_playlist_addto_filter">
        <input type="checkbox" class="mijovideos_checkbox" name="add_to_top" />&nbsp;<span><?php echo JText::_('COM_MIJOVIDEOS_ADD_TO_TOP'); ?></span>
        <?php echo $this->lists['playlist_order']; ?>
    </div>
    <div class="mijovideos_playlist_items">
        <?php foreach ($this->playlistitems as $item) { ?>
            <?php $playlist_videos = array();
            foreach ($item->videos as $video) {
                $playlist_videos[] = $video->video_id;
            } ?>
            <li class="mijovideos_playlist_item">
                <a class="playlist_item" id="playlist_item<?php echo $item->id; ?>">
                    <img src="components/com_mijovideos/assets/images/tick.png" style="<?php if (!in_array($this->item->id, $playlist_videos)) { ?>visibility: hidden<?php } ?>"/>
                    <span class="mijovideos_playlist_title" id="<?php if ($item->type == 1) echo "type1"; ?>"><?php echo $item->title; ?>&nbsp;(<span id="total_videos"><?php echo $item->total; ?></span>)</span>
                    <span class="mijovideos_playlist_access"><?php echo $this->levels[$item->access]->title; ?></span>
                    <span class="mijovideos_playlist_created"><?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?></span>
                </a>
            </li>
        <?php } ?>
    </div>
<?php } ?>
<form method="post" name="adminForm" id="adminForm" action="<?php echo JRoute::_('index.php?option=com_mijovideos&task=save'); ?>">
    <div class="mijovideos_create_playlist">
        <input type="text" class="mijovideos_playlist_name" value="" name="playlist_title" placeholder="<?php echo JText::_('COM_MIJOVIDEOS_ENTER_PLAYLIST_NAME'); ?>" />
        <div class="mijovideos_playlist_actions">
            <?php echo $this->lists['access']; ?>
            <a class="<?php echo MijoVideos::getButtonClass(); ?>" id="create_playlist"><?php echo JText::_('COM_MIJOVIDEOS_CREATE_PLAYLIST'); ?></a>
        </div>
    </div>
    <?php echo JHtml::_('form.token'); ?>
</form>
<script type="text/javascript"><!--
    function ajaxOrder() {
        var filter_order = jQuery('#playlist_order').find(':selected').val();
        switch (filter_order) {
            case  "title_az":
                filter_order_Dir = "ASC"
                break;
            case "title_za":
                filter_order_Dir = "DESC"
                break;
            default :
                filter_order_Dir = "DESC"
                break;
        }
        jQuery.ajax({
            url: 'index.php?option=com_mijovideos&view=video&task=ajaxOrder&format=raw&video_id=<?php echo $this->item->id; ?>',
            type: 'post',
            data: {filter_videos: "playlists", filter_order: filter_order, filter_order_Dir: filter_order_Dir},
            dataType: 'json',
            success: function(json) {
                if (json['html']) {
                    jQuery('.mijovideos_playlist_items').html(json['html']);
                }
                if (json['redirect']) {
                    location = json['redirect'];
                }
            }
        });
    }
    //--></script>
<script type="text/javascript"><!--
    jQuery('#create_playlist').click(function() {
        var token = jQuery("#adminForm input[type='hidden']")[0].name;
        var tokenval = jQuery("#adminForm input[type='hidden']")[0].value;
        var playlist_title = jQuery('.mijovideos_playlist_name').val();
        var access = jQuery('#playlist_access').find(':selected').val();
        jQuery.ajax({
            url: 'index.php?option=com_mijovideos&view=playlists&task=save&format=raw',
            type: 'post',
            data: 'title='+playlist_title+'&access='+access+'&'+token+'='+tokenval,
            dataType: 'json',
            success: function(json) {
                if (json['success']) {
                    html  = '<li class="mijovideos_playlist_item">';
                    html += '   <a class="playlist_item" id="playlist_item' + json['id'] + '">';
                    html += '       <img src="components/com_mijovideos/assets/images/tick.png" style="visibility: hidden"/>';
                    html += '       <span class="mijovideos_playlist_title">' + playlist_title + '&nbsp;(<span id="total_videos">0</span>)</span>';
                    html += '       <span class="mijovideos_playlist_access">' + jQuery('#playlist_access').find(':selected').text() + '</span>';
                    html += '       <span class="mijovideos_playlist_created"><?php echo JHtml::_('date', '', JText::_('DATE_FORMAT_LC4')); ?></span>';
                    html += '   </a>';
                    html += '</li>';
                    jQuery('.mijovideos_playlist_items').append(html);
                    var id = json['id'];
                    var ordering = jQuery('.mijovideos_checkbox:checked').val();
                    var total = jQuery('#playlist_item'+id).find('.mijovideos_playlist_title').find('#total_videos').text();
                    jQuery.ajax({
                        url: 'index.php?option=com_mijovideos&view=playlists&task=addVideoToPlaylist&format=raw',
                        type: 'post',
                        data: {playlist_id: id, video_id: <?php echo $this->item->id; ?>, ordering: ordering},
                        dataType: 'json',
                        success: function(json) {
                            if (json['success']) {
                                jQuery('#playlist_item'+id).find("img").css('visibility', 'visible');
                                jQuery('#playlist_item'+id).find('.mijovideos_playlist_title').find('#total_videos').text(parseInt(total) + 1);
                            }
                            if (json['redirect']) {
                                location = json['redirect'];
                            }
                            if (json['error']) {
                                jQuery('.playlist_notification').html('<div class="mijovideos_warning" style="display: none;">' + json['error'] + '</div>');
                                jQuery('.mijovideos_warning').fadeIn('slow');
                                jQuery('.mijovideos_warning').delay(5000).fadeOut('slow');
                            }
                        }

                    });
                }
                if (json['redirect']) {
                    location = json['redirect'];
                }
                if (json['error']) {
                    jQuery('#notification').html('<div class="mijovideos_warning" style="display: none;">' + json['error'] + '</div>');
                    jQuery('.mijovideos_warning').fadeIn('slow');
                    jQuery('.mijovideos_warning').delay(5000).fadeOut('slow');
                }
            }
        });
    });
    //--></script>
<script type="text/javascript"><!--
    jQuery('.playlist_item').on('click', function() {
        var id = this.id.replace("playlist_item", "");
        var ordering = jQuery('.mijovideos_checkbox:checked').val();
        var type = jQuery(this).find('.mijovideos_playlist_title').attr('id');
        var total = jQuery(this).find('.mijovideos_playlist_title').find('#total_videos').text();
        if (jQuery(this).children('img')[0].style['visibility'] !== 'hidden') {
            jQuery.ajax({
                url: 'index.php?option=com_mijovideos&view=playlists&task=removeVideoFromPlaylist&format=raw',
                type: 'post',
                data: {playlist_id: id, video_id: <?php echo $this->item->id; ?>, type : type},
                dataType: 'json',
                success: function(json) {
                    if (json['success']) {
                        jQuery('#playlist_item'+id).find("img").css('visibility', 'hidden');
                        jQuery('#playlist_item'+id).find('.mijovideos_playlist_title').find('#total_videos').text(parseInt(total) - 1);
                        jQuery('.playlist_notification').html('<div class="mijovideos_success" style="display: none;">' + json['success'] + '</div>');
                        jQuery('.mijovideos_success').fadeIn('slow');
                        jQuery('.mijovideos_success').delay(5000).fadeOut('slow');
                    }
                    if (json['redirect']) {
                        location = json['redirect'];
                    }
                    if (json['error']) {
                        jQuery('.playlist_notification').html('<div class="mijovideos_warning" style="display: none;">' + json['error'] + '</div>');
                        jQuery('.mijovideos_warning').fadeIn('slow');
                        jQuery('.mijovideos_warning').delay(5000).fadeOut('slow');
                    }
                }

            });
        } else {
            jQuery.ajax({
                url: 'index.php?option=com_mijovideos&view=playlists&task=addVideoToPlaylist&format=raw',
                type: 'post',
                data: {playlist_id: id, video_id: <?php echo $this->item->id; ?>, ordering : ordering, type : type},
                dataType: 'json',
                success: function(json) {
                    if (json['success']) {
                        jQuery('#playlist_item'+id).find("img").css('visibility', 'visible');
                        jQuery('#playlist_item'+id).find('.mijovideos_playlist_title').find('#total_videos').text(parseInt(total) + 1);
                        jQuery('.playlist_notification').html('<div class="mijovideos_success" style="display: none;">' + json['success'] + '</div>');
                        jQuery('.mijovideos_success').fadeIn('slow');
                        jQuery('.mijovideos_success').delay(5000).fadeOut('slow');
                    }
                    if (json['redirect']) {
                        location = json['redirect'];
                    }
                    if (json['error']) {
                        jQuery('.playlist_notification').html('<div class="mijovideos_warning" style="display: none;">' + json['error'] + '</div>');
                        jQuery('.mijovideos_warning').fadeIn('slow');
                        jQuery('.mijovideos_warning').delay(5000).fadeOut('slow');
                    }
                }

            });
        }

    });
    //--></script>