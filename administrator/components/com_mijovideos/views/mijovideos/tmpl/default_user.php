<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted access');

$channel = MijoVideos::get('channels')->getDefaultChannel();
$thumb = MijoVideos::get('utility')->getThumbPath($channel->id, 'channels', $channel->thumb);
$config = MijoVideos::getConfig();
?>

<td valign="top" width="58%">
    <table>
        <tr>
            <td>
                <div id="mijovideos_cpanel" width="30%">
                    <?php
                    $option = JRequest::getWord('option');

                    $link = MijoVideos::get('utility')->route('index.php?option='.$option.'&amp;view=channels');
                    $this->quickIconButton($link, 'icon-48-mijovideos-channels.png', JText::_('COM_MIJOVIDEOS_CPANEL_CHANNELS'));

                    if($config->get('playlists')) {
                        $link = MijoVideos::get('utility')->route('index.php?option='.$option.'&amp;view=playlists');
                        $this->quickIconButton($link, 'icon-48-mijovideos-playlists.png', JText::_('COM_MIJOVIDEOS_CPANEL_PLAYLISTS'));
                    }

                    $link = MijoVideos::get('utility')->route('index.php?option='.$option.'&amp;view=videos');
                    $this->quickIconButton($link, 'icon-48-mijovideos-videos.png', JText::_('COM_MIJOVIDEOS_CPANEL_VIDEOS'));

                    if($config->get('subscriptions')) {
                        $link = MijoVideos::get('utility')->route('index.php?option='.$option.'&amp;view=subscriptions');
                        $this->quickIconButton($link, 'icon-48-mijovideos-subscriptions.png', JText::_('COM_MIJOVIDEOS_CPANEL_SUBSCRIPTIONS'));
                    }
                    ?>
                </div>
            </td>
        </tr>
    </table>
</td>

<td valign="top" width="42%" style="padding: 15px 0 0 5px">
    <?php echo JHtml::_('sliders.start', 'mijovideos'); ?>
    <?php echo JHtml::_('sliders.panel', JText::_('COM_MIJOVIDEOS_WELLCOME') . ' ' . $channel->title, 'welcome'); ?>
    <table class="adminlist">
        <tr>
            <td valign="top" width="50%" align="center">
                <table class="adminlist table table-striped">
                    <tr height="40">
                        <td width="%25">
                            <?php echo JText::_('COM_MIJOVIDEOS_CPANEL_CHANNELS'); ?>
                        </td>
                        <td width="%35">
                            <b><?php echo $this->stats['channels'];?></b>
                        </td>
                        <td align="center" style="vertical-align: middle;" rowspan="4">
                            <img src="<?php echo $thumb; ?>" width="140" height="140" style="display: block; margin: auto;" alt="<?php echo $channel->title; ?>" title="<?php echo $channel->title; ?>" align="middle" border="0">
                        </td>
                    </tr>
                    <?php if($config->get('playlists')) { ?>
                        <tr height="40">
                            <td>
                                <?php echo JText::_('COM_MIJOVIDEOS_CPANEL_PLAYLISTS'); ?>
                            </td>
                            <td>
                                <b><?php echo $this->stats['playlists'];?></b>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr height="40">
                        <td>
                            <?php echo JText::_('COM_MIJOVIDEOS_CPANEL_VIDEOS'); ?>
                        </td>
                        <td>
                            <b><?php echo $this->stats['videos'];?></b>
                        </td>
                    </tr>
                    <?php if($config->get('subscriptions')) { ?>
                        <tr height="40">
                            <td>
                                <?php echo JText::_('COM_MIJOVIDEOS_CPANEL_SUBSCRIPTIONS'); ?>
                            </td>
                            <td>
                                <b><?php echo $this->stats['subscriptions'];?></b>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </td>
        </tr>
    </table>

    <?php echo JHtml::_('sliders.end'); ?>
</td>