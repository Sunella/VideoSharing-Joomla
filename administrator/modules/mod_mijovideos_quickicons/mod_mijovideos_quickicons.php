<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_ADMINISTRATOR.'/components/com_mijovideos/library/mijovideos.php');

JFactory::getDocument()->addStyleSheet(JUri::root() . 'administrator/components/com_mijovideos/assets/css/mijovideos.css');
if (MijoVideos::is30()) {
    JFactory::getDocument()->addStyleSheet(JUri::root() . 'administrator/components/com_mijovideos/assets/css/joomla3.css');
} else{
    JFactory::getDocument()->addStyleSheet(JUri::root() . 'administrator/components/com_mijovideos/assets/css/joomla2.css');
}


function getMijovideosIcon($link, $image, $text) {
    $lang = JFactory::getLanguage();
    ?>
<div class="icon-wrapper" style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
    <div class="icon">
        <a href="<?php echo $link; ?>">
            <img src="<?php echo JUri::root(true); ?>/administrator/components/com_mijovideos/assets/images/<?php echo $image; ?>" alt="<?php echo $text; ?>" />
            <span><?php echo $text; ?></span>
        </a>
    </div>
</div>
<?php
}
?>

<div id="mijovideos_cpanel">
    <?php

    if ($params->get('mijovideos_fields', '0') == '1') {
        $link = 'index.php?option=com_mijovideos&view=fields';
        getMijovideosIcon($link, 'icon-48-mijovideos-videos.png', JText::_('MOD_MIJOVIDEOS_QUICKICONS_FIELDS'));
    }
    if ($params->get('mijovideos_categories', '1') == '1') {
        $link = 'index.php?option=com_mijovideos&view=categories';
        getMijovideosIcon($link, 'icon-48-mijovideos-categories.png', JText::_('MOD_MIJOVIDEOS_QUICKICONS_CATEGORIES'));
    }
    if ($params->get('mijovideos_channels', '1') == '1') {
        $link = 'index.php?option=com_mijovideos&view=channels';
        getMijovideosIcon($link, 'icon-48-mijovideos-channels.png', JText::_('MOD_MIJOVIDEOS_QUICKICONS_CHANNELS'));
    }
    if ($params->get('mijovideos_playlists', '1') == '1') {
        $link = 'index.php?option=com_mijovideos&view=playlists';
        getMijovideosIcon($link, 'icon-48-mijovideos-playlists.png', JText::_('MOD_MIJOVIDEOS_QUICKICONS_PLAYLISTS'));
    }
    if ($params->get('mijovideos_videos', '1') == '1') {
        $link = 'index.php?option=com_mijovideos&view=videos';
        getMijovideosIcon($link, 'icon-48-mijovideos-videos.png', JText::_('MOD_MIJOVIDEOS_QUICKICONS_VIDEOS'));
    }

    ?>
</div>