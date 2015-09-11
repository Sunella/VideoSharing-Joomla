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
    <?php if($module->position == 'mijovideos_top' and $params->get('position') == 0) { ?>
        <div class="mijovideos_player">
            <?php echo MijoVideos::get('videos')->getPlayer($rows[0]);
            unset($rows[0]); ?>
        </div>
        <div class="mijovideos-top-module-wrap">
            <?php foreach ($rows as $row) {
                $Itemid = MijoVideos::get('router')->getItemid(array('view' => 'video', 'video_id' => $row->id), null, true);
                $link = JRoute::_('index.php?option=com_mijovideos&view=video&video_id='.$row->id . $Itemid);?>
                <div class="mijovideos-top-module">
                    <a href="<?php echo $link; ?>">
                        <img style="width: <?php echo $width; ?>px; height: <?php echo $height; ?>px;" src="<?php echo $utility->getThumbPath($row->id, 'videos', $row->thumb); ?>" title="<?php echo $row->title; ?>"" alt="<?php echo $row->title; ?>"/>
                    </a>
                </div>
            <?php } ?>
        </div>
    <?php } else { ?>
        <?php foreach ($rows as $row) {
            $Itemid = MijoVideos::get('router')->getItemid(array('view' => 'video', 'video_id' => $row->id), null, true);
            $link = JRoute::_('index.php?option=com_mijovideos&view=video&video_id='.$row->id . $Itemid);?>
            <div class="mijovideos-modules-module">
                <a href="<?php echo $link; ?>">
                    <img class="mijovideos-module-thumbs" style="width: <?php echo $width; ?>px; height: <?php echo $height; ?>px" src="<?php echo $utility->getThumbPath($row->id, 'videos', $row->thumb); ?>" title="<?php echo $row->title; ?>" alt="<?php echo $row->title; ?>"/>
                    <h3 class="mijovideos_box_h3">
                        <a href="<?php echo $link; ?>" title="<?php echo $row->title; ?>">
                            <?php echo htmlspecialchars(JHtmlString::truncate($row->title, $config->get('title_truncation'), false, false)); ?>
                        </a>
                    </h3>
                    <div class="videos-meta">
                        <div class="mijovideos-meta-info">
                            <div class="videos-view">
                            <span class="value"><?php echo number_format($row->hits); ?><span>
                            <span class="key"><?php echo JText::_('COM_MIJOVIDEOS_VIEWS'); ?><span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        <?php } ?>
    <?php } ?>
<?php } ?>