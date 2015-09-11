<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ; ?>
<?php echo JHtml::_('tabs.start', 'mijovideos', array('useCookie'=>0)); ?>

<?php echo JHtml::_('tabs.panel', JText::_('COM_MIJOVIDEOS_SHARE_THIS_VIDEO'), 'sl_share_this'); ?>
    <!-- AddThis Button BEGIN -->
    <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
        <a class="addthis_button_facebook"></a>
        <a class="addthis_button_twitter"></a>
        <a class="addthis_button_google_plusone_share"></a>
        <a class="addthis_button_blogger"></a>
        <a class="addthis_button_odnoklassniki_ru"></a>
        <a class="addthis_button_vk"></a>
        <a class="addthis_button_tumblr"></a>
        <a class="addthis_button_reddit"></a>
        <a class="addthis_button_linkedin"></a>
        <a class="addthis_button_compact"></a>
    </div>
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js"></script>
    <!-- AddThis Button END -->

<?php echo JHtml::_('tabs.panel', JText::_('COM_MIJOVIDEOS_EMBED'), 'sl_embed'); ?>
    <textarea class="mijovideos_embed">
        <?php echo "<iframe width=\"600\" height=\"360\" src=\"".JUri::root().JRoute::_("index.php?option=com_mijovideos&view=video&video_id=".$this->item->id.$this->Itemid."&layout=player&tmpl=component")."\" frameborder=\"0\" allowfullscreen></iframe>"; ?>
    </textarea>

<?php echo JHtml::_('tabs.end'); ?>