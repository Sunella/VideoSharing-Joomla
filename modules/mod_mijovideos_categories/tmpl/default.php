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
                $Itemid = MijoVideos::get('router')->getItemid(array('view' => 'category', 'category_id' => $row->id), null, true);

	    		$link = JRoute::_('index.php?option=com_mijovideos&view=category&category_id='.$row->id . $Itemid);?>
            <div class="mijovideos-modules-module">
                <?php if ($thumb) { ?>
                    <div>
                        <a href="<?php echo $link; ?>">
                            <img class="mijovideos-module-thumbs" style="width: <?php echo $width; ?>px; height: <?php echo $height; ?>px" src="<?php echo $utility->getThumbPath($row->id, 'categories', $row->thumb); ?>"  alt="<?php echo $row->title; ?>" />
                        </a>
                    </div>
                <?php } ?>
                <div>
                    <div class="mijovideos-module-text-class">
                        <a href="<?php echo $link; ?>">
                            <?php echo htmlspecialchars(JHtmlString::truncate($row->title, $config->get('title_truncation'), false, false)); ?>
                        </a>
                        (<?php echo MijoVideos::get('model')->getVideosCategoriesCount($row->id) . ' ' . JText::_('COM_MIJOVIDEOS_VIDEO_LIST'); ?>)
                    </div>
                </div>
            </div>
	  <?php } ?>
<?php
}