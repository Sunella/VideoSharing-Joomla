<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die;
?>

<div class="mijovideos_box">
    <div class="mijovideos_box_heading">
        <?php if (($this->params->get('show_page_heading', '0') == '1')) {
            $page_title = $this->params->get('page_heading', '');
            if (empty($page_title)) {
                $page_title = $this->params->get('page_title', '');
            }
            ?>
                <h1 class="mijovideos_box_h1"><?php echo $page_title; ?></h1>
            <?php
        }
        ?>
    </div>

    <div class="mijovideos_box_content">
        <div class="mijovideos_box_content_99">
            <?php MijoVideos::get('utility')->renderModules('mijovideos_top'); ?>
        </div>
        <div class="mijovideos_box_content_99">
            <div class="mijovideos_box_content_49">
                <?php MijoVideos::get('utility')->renderModules('mijovideos_left'); ?>
            </div>
            <div class="mijovideos_box_content_49">
                <?php MijoVideos::get('utility')->renderModules('mijovideos_right'); ?>
            </div>
        </div>
        <div class="mijovideos_box_content_99">
            <?php MijoVideos::get('utility')->renderModules('mijovideos_bottom'); ?>
        </div>
    </div>

    <div class="clr"></div>
</div>