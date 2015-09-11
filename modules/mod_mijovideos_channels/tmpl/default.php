<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted access');

if (count($rows)) {
?>
		<?php
			foreach ($rows as $row) {
                $Itemid = MijoVideos::get('router')->getItemid(array('view' => 'channel', 'channel_id' => $row->id), null, true);

	    		$link = JRoute::_('index.php?option=com_mijovideos&view=channel&channel_id='.$row->id . $Itemid);?>
				<div class="mijovideos-modules-module">
                    <div>
                        <a href="<?php echo $link; ?>">
                            <img class="mijovideos-module-thumbs" style="width: <?php echo $width; ?>px; height: <?php echo $height; ?>px" src="<?php echo $utility->getThumbPath($row->id, 'channels', $row->thumb); ?>"  alt="<?php echo $row->title; ?>" />
                        </a>
                    </div>
                    <div>
                        <p class="mijovideos-module-text-class" >
                            <a href="<?php echo $link; ?>">
                                <?php echo htmlspecialchars(JHtmlString::truncate($row->title, $config->get('title_truncation'), false, false)); ?>
                            </a>
                        </p>
                        <span class="mijovideos-modules-span" ><?php echo MijoVideos::get('model')->getSubscriberCount($row->id) . ' ' . JText::_('COM_MIJOVIDEOS_SUBSCRIBERS'); ?></span>
                    </div>
				</div>
	  <?php } ?>
<?php
}