<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted access');
?>

<td valign="top" width="58%">
    <table>
        <tr>
            <td>
                <div id="mijovideos_cpanel" width="30%">
                    <?php
                    $option = JRequest::getWord('option');

                    if (MijoVideos::isDashboard()) {
                        $link = 'administrator/index.php?option=com_mijovideos';
                        $this->quickIconButton($link, 'icon-48-mijovideos-config.png', JText::_('COM_MIJOVIDEOS_CPANEL_CONFIGURATION'), false, 0, 0, true);
                    }
                    // @TODO : Add this line when release wordpress
                    // else if (MijoVideos::get('utility')->is30() or MFactory::isW()) {
                    else if (MijoVideos::get('utility')->is30()) {
                        $uri = (string) JUri::getInstance();
                        $return = urlencode(base64_encode($uri));

                        $link = 'index.php?option=com_config&view=component&component=com_mijovideos&path=&amp;return='.$return;
                        $this->quickIconButton($link, 'icon-48-mijovideos-config.png', JText::_('COM_MIJOVIDEOS_CPANEL_CONFIGURATION'));
                    }
                    else {
                        $link = 'index.php?option=com_config&view=component&component=com_mijovideos&path=&tmpl=component';
                        $this->quickIconButton($link, 'icon-48-mijovideos-config.png', JText::_('COM_MIJOVIDEOS_CPANEL_CONFIGURATION'), true, 875, 550);
                    }

                    $link = MijoVideos::get('utility')->route('index.php?option='.$option.'&amp;view=fields');
                    $this->quickIconButton($link, 'icon-48-mijovideos-fields.png', JText::_('COM_MIJOVIDEOS_CPANEL_FIELDS'));

                    $link = MijoVideos::get('utility')->route('index.php?option='.$option.'&view=restoremigrate');
                    $this->quickIconButton($link, 'icon-48-mijovideos-restore.png', JText::_('COM_MIJOVIDEOS_CPANEL_RESTORE'));

                    $link = MijoVideos::get('utility')->route('index.php?option='.$option.'&amp;view=upgrade');
                    $this->quickIconButton($link, 'icon-48-mijovideos-upgrade.png', JText::_('COM_MIJOVIDEOS_CPANEL_UPGRADE'));
                    ?>

                    <br /><br /><br /><br /><br /><br /><br /><?php if (!MijoVideos::is30()) { ?><br /><br /><?php } ?>

                    <?php

                    $link = MijoVideos::get('utility')->route('index.php?option='.$option.'&amp;view=categories');
                    $this->quickIconButton($link, 'icon-48-mijovideos-categories.png', JText::_('COM_MIJOVIDEOS_CPANEL_CATEGORIES'));

                    $link = MijoVideos::get('utility')->route('index.php?option='.$option.'&amp;view=channels');
                    $this->quickIconButton($link, 'icon-48-mijovideos-channels.png', JText::_('COM_MIJOVIDEOS_CPANEL_CHANNELS'));

                    $link = MijoVideos::get('utility')->route('index.php?option='.$option.'&amp;view=playlists');
                    $this->quickIconButton($link, 'icon-48-mijovideos-playlists.png', JText::_('COM_MIJOVIDEOS_CPANEL_PLAYLISTS'));

                    $link = MijoVideos::get('utility')->route('index.php?option='.$option.'&amp;view=videos');
                    $this->quickIconButton($link, 'icon-48-mijovideos-videos.png', JText::_('COM_MIJOVIDEOS_CPANEL_VIDEOS'));

                    ?>

                    <br /><br /><br /><br /><br /><br /><br /><?php if (!MijoVideos::is30()) { ?><br /><br /><?php } ?>

                    <?php

                    $link = MijoVideos::get('utility')->route('index.php?option='.$option.'&amp;view=subscriptions');
                    $this->quickIconButton($link, 'icon-48-mijovideos-subscriptions.png', JText::_('COM_MIJOVIDEOS_CPANEL_SUBSCRIPTIONS'));

                    $link = MijoVideos::get('utility')->route('index.php?option='.$option.'&amp;view=reports');
                    $this->quickIconButton($link, 'icon-48-mijovideos-reports.png', JText::_('COM_MIJOVIDEOS_CPANEL_REPORTS'));

                    $link = MijoVideos::get('utility')->route('index.php?option='.$option.'&amp;view=files');
                    $this->quickIconButton($link, 'icon-48-mijovideos-files.png', JText::_('COM_MIJOVIDEOS_CPANEL_FILES'));

                    $link = MijoVideos::get('utility')->route('index.php?option='.$option.'&amp;view=processes');
                    $this->quickIconButton($link, 'icon-48-mijovideos-processes.png', JText::_('COM_MIJOVIDEOS_CPANEL_PROCESSES'));

                    ?>

                    <br /><br /><br /><br /><br /><br /><br /><?php if (!MijoVideos::is30()) { ?><br /><br /><?php } ?>

                    <?php
                    $link = MijoVideos::get('utility')->route('index.php?option='.$option.'&amp;view=support&amp;task=support');
                    $this->quickIconButton($link, 'icon-48-mijovideos-support.png', JText::_('COM_MIJOVIDEOS_CPANEL_SUPPORT'), true, 650, 420);

                    $link = MijoVideos::get('utility')->route('index.php?option='.$option.'&amp;view=support&amp;task=translators');
                    $this->quickIconButton($link, 'icon-48-mijovideos-translators.png', JText::_('COM_MIJOVIDEOS_CPANEL_TRANSLATORS'), true);

                    $link = 'http://mijosoft.com/joomla-extensions/mijovideos/changelog?tmpl=component';
                    $this->quickIconButton($link, 'icon-48-mijovideos-changelog.png', JText::_('COM_MIJOVIDEOS_CPANEL_CHANGELOG'), true);

                    $link = 'http://mijosoft.com';
                    $this->quickIconButton($link, 'icon-48-mijosoft.png', 'Mijosoft.com', false, 0, 0, true);
                    ?>
                </div>
            </td>
        </tr>
    </table>
</td>

<td valign="top" width="42%" style="padding: <?php echo ($this->_mainframe->isAdmin() ? '7' : '0'); ?>px 0 0 5px">
    <?php echo JHtml::_('sliders.start', 'mijovideos'); ?>
    <?php echo JHtml::_('sliders.panel', JText::_('COM_MIJOVIDEOS_CPANEL_WELLCOME'), 'welcome'); ?>
    <table class="adminlist">
        <tr>
            <td valign="top" width="50%" align="center">
                <table class="adminlist table table-striped">
                    <?php
                        $rowspan = 5;
                        if (empty($this->info['pid'])) {
                            $rowspan = 6;
                        }
                    ?>
                    <tr height="70">
                        <td width="%25">
                            <?php
                                $icon = 'help';
                                if ($this->info['version_enabled'] == 0) {
                                    $icon = 'noinfo';
                                } elseif ($this->info['version_status'] == 0) {
                                    $icon = 'latest';
                                }

                                $img_path = JUri::root().'/administrator/components/com_mijovideos/assets/images/icon-48-v-'.$icon.'.png';
                            ?>

                            <img src="<?php echo $img_path; ?>" />
                        </td>
                        <td width="%35">
                            <?php
                                if ($this->info['version_enabled'] == 0) {
                                    echo '<b>'.JText::_('COM_MIJOVIDEOS_CPANEL_VERSION_CHECKER_DISABLED_1').'</b>';
                                } elseif ($this->info['version_status'] == 0) {
                                    echo '<b><font color="green">'.JText::_('COM_MIJOVIDEOS_CPANEL_LATEST_VERSION_INSTALLED').'</font></b>';
                                } elseif($this->info['version_status'] == -1) {
                                    echo '<b><font color="red">'.JText::_('COM_MIJOVIDEOS_CPANEL_OLD_VERSION').'</font></b>';
                                } else {
                                    echo '<b><font color="orange">'.JText::_('COM_MIJOVIDEOS_CPANEL_NEWER_VERSION').'</font></b>';
                                }
                            ?>
                        </td>
                        <td align="center" style="vertical-align: middle;" rowspan="<?php echo $rowspan; ?>">
                            <a href="http://www.mijosoft.com/joomla-extensions/mijovideos" target="_blank">
                            <img src="<?php echo JUri::root(true); ?>/administrator/components/com_mijovideos/assets/images/logo.png" width="140" height="140" style="display: block; margin: auto;" alt="MijoVideos" title="MijoVideos" align="middle" border="0">
                            </a>
                        </td>
                    </tr>
                    <?php if (empty($this->info['pid'])) { ?>
                    <tr height="40">
                        <td>
                            <?php echo '<b><font color="red">'.JText::_('COM_MIJOVIDEOS_PERSONAL_ID').'</font></b>';?>
                        </td>
                        <td>
                            <input type="text" name="pid" id="pid" class="inputbox" size="18" style="width: 140px;" />
                            &nbsp;
                            <input type="button" class="btn btn-danger" <?php if (MijoVideos::is30()) { ?>style="margin-bottom: 10px;"<?php } ?> onclick="javascript: submitbutton('savePersonalID')" value="<?php echo JText::_('Save'); ?>" />
                        </td>
                    </tr>
                    <?php } ?>
                    <tr height="40">
                        <td>
                            <?php
                                if($this->info['version_status'] == 0 || $this->info['version_enabled'] == 0) {
                                    echo JText::_('COM_MIJOVIDEOS_CPANEL_LATEST_VERSION');
                                } elseif($this->info['version_status'] == -1) {
                                    echo '<b><font color="red">'.JText::_('COM_MIJOVIDEOS_CPANEL_LATEST_VERSION').'</font></b>';
                                } else {
                                    echo '<b><font color="orange">'.JText::_('COM_MIJOVIDEOS_CPANEL_LATEST_VERSION').'</font></b>';
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                if ($this->info['version_enabled'] == 0) {
                                    echo JText::_('COM_MIJOVIDEOS_CPANEL_VERSION_CHECKER_DISABLED_2');
                                } elseif($this->info['version_status'] == 0) {
                                    echo $this->info['version_latest'];
                                } elseif($this->info['version_status'] == -1) {
                                    // Version output in red
                                    echo '<b><font color="red">'.$this->info['version_latest'].'</font></b>&nbsp;&nbsp;&nbsp;&nbsp;';
                                    ?>
                                    <input type="button" class="btn btn-danger" class="button hasTip" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_UPGRADE'); ?>" onclick="upgrade();" />
                                    <?php
                                } else {
                                    echo '<b><font color="orange">'.$this->info['version_latest'].'</font></b>';
                                }
                            ?>
                        </td>
                    </tr>
                    <tr height="40">
                        <td>
                            <?php echo JText::_('COM_MIJOVIDEOS_CPANEL_INSTALLED_VERSION'); ?>
                        </td>
                        <td>
                            <?php
                                if ($this->info['version_enabled'] == 0) {
                                    echo JText::_('COM_MIJOVIDEOS_CPANEL_VERSION_CHECKER_DISABLED_2');
                                } else {
                                    echo $this->info['version_installed'];
                                }
                            ?>
                        </td>
                    </tr>
                    <tr height="40">
                        <td>
                            <?php echo JText::_('COM_MIJOVIDEOS_CPANEL_COPYRIGHT'); ?>
                        </td>
                        <td>
                            <a href="http://mijosoft.com" target="_blank"><?php echo MijoVideos::get('utility')->getXmlText(JPATH_MIJOVIDEOS_ADMIN.'/mijovideos.xml', 'copyright'); ?></a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <?php echo JHtml::_('sliders.panel', JText::_('COM_MIJOVIDEOS_CPANEL_SERVER'), 'server_status'); ?>
    <table class="adminlist table table-striped">
    <?php
        foreach ($this->info['server'] as $server) {
            $color = ($server['value'] == JText::_('JNO')) ? '#FF0000' : '#339900';
        ?>
        <tr>
            <td width="40%">
                <?php echo $server['name']; ?>
            </td>
            <td width="60%">
                <strong style="color:<?php echo $color; ?>"><?php echo $server['value']; ?></strong>
            </td>
        </tr>
    <?php } ?>
    </table>

    <?php echo JHtml::_('sliders.panel', JText::_('COM_MIJOVIDEOS_CPANEL_STATISTICS'), 'stats'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td width="40%">
                <?php echo JText::_('COM_MIJOVIDEOS_CPANEL_CATEGORIES'); ?>
            </td>
            <td width="60%">
                <b><?php echo $this->stats['categories']; ?></b>
            </td>
        </tr>
        <tr>
            <td width="40%">
                <?php echo JText::_('COM_MIJOVIDEOS_CPANEL_CHANNELS'); ?>
            </td>
            <td width="60%">
                <b><?php echo $this->stats['channels'];?></b>
            </td>
        </tr>
        <tr>
            <td width="40%">
                <?php echo JText::_('COM_MIJOVIDEOS_CPANEL_PLAYLISTS'); ?>
            </td>
            <td width="60%">
                <b><?php echo $this->stats['playlists'];?></b>
            </td>
        </tr>
        <tr>
            <td width="40%">
                <?php echo JText::_('COM_MIJOVIDEOS_CPANEL_VIDEOS'); ?>
            </td>
            <td width="60%">
                <b><?php echo $this->stats['videos'];?></b>
            </td>
        </tr>
    </table>

    <?php echo JHtml::_('sliders.end'); ?>
</td>