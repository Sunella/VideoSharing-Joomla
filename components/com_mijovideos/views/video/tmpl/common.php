<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die ('Restricted access');
$utility = MijoVideos::get('utility');
$thumb_size = $utility->getThumbSize($this->config->get('thumb_size'));
?>
<div class="mijovideos_box">
	<div class="mijovideos_box_heading">
		<h3 class="mijovideos_box_h3">
            <a href="<?php echo $url; ?>" title="<?php echo $item->title; ?>">
                <?php echo $this->escape(JHtmlString::truncate($item->title, $this->config->get('title_truncation'), false, false)); ?>
            </a>
        </h3>
	</div>
    <div class="mijovideos_box_content">
        <div class="videos-items-list-box">
            <div class="videos-list-item" style="width: <?php echo $thumb_size; ?>px">
                <div class="videos-aspect<?php echo $this->config->get('thumb_aspect'); ?>"></div>
                <a href="<?php echo $url; ?>">
                    <img class="videos-items-grid-thumb" src="<?php echo $utility->getThumbPath($item->id, 'videos', $item->thumb); ?>" title="<?php echo $item->title; ?>" alt="<?php echo $item->title; ?>"/>
                </a>
            </div>
            <div class="videos-items-list-box-content">
                <div class="videos-meta">
                    <div class="mijovideos-meta-info">
                        <div class="videos-view">
                            <span class="value"><?php echo number_format($item->hits); ?></span>
                            <span class="key"><?php echo JText::_('COM_MIJOVIDEOS_VIEWS'); ?></span>
                        </div>
                        <div class="date-created">
                            <span class="key"><?php echo JText::_('COM_MIJOVIDEOS_DATE_CREATED'); ?></span>
                            <span class="value"><?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="videos-description">
                    <?php if (!empty($item->introtext)) { ?>
                        <?php echo $this->escape(JHtmlString::truncate($item->introtext, $this->config->get('desc_truncation'), false, false)); ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
        var box_width = document.getElementById("mijovideos_docs").offsetWidth;
        var thumb_size = <?php echo $thumb_size; ?>;
        var thumb_percent = Math.round((thumb_size/box_width)*100);
        var desc_percent = 100 - thumb_percent - 3;
        jQuery('div[class^="videos-items-list-box-content"]').css('width', desc_percent+'%');
        jQuery('div[class^="videos-list-item"]').css('width', thumb_percent+'%');
    });
</script>