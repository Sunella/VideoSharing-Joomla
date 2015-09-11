<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted access');

?>
<fieldset class="adminform" style="background-color: #FFFFFF;">
    <legend><?php echo JText::_('COM_MIJOVIDEOS_RESTOREMIGRATE_BACKUPRESTORE'); ?></legend>
    <table class="noshow" width="100%">
        <tr style="vertical-align: top;">
            <td width="50%">
                <form enctype="multipart/form-data" action="index.php?option=com_mijovideos&amp;view=restoremigrate" method="post" name="adminForm">
                    <fieldset class="adminform" <?php echo MijoVideos::is30() ? '' : 'style="background-color: #F4F4F4;"'; ?>>
                        <legend><?php echo JText::_('COM_MIJOVIDEOS_RESTOREMIGRATE_MIJOVIDEOS_BACKUP'); ?></legend>
                        <table class="adminform">
                            <tr>
                                <td>
                                    <input class="button btn btn-success" type="submit" name="backup_categories" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_CATEGORIES'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-success" type="submit" name="backup_channels" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_CHANNELS'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-success" type="submit" name="backup_playlists" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_PLAYLISTS'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-success" type="submit" name="backup_playlistvideos" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_PLAYLISTS')." ".JText::_('COM_MIJOVIDEOS_VIDEOS'); ?>" />
                                    &nbsp;
                                    <input class="button btn btn-success" type="submit" name="backup_videos" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_VIDEOS'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-success" type="submit" name="backup_videoscategories" value="<?php echo JText::_('COM_MIJOVIDEOS_VIDEOS')." ".JText::_('COM_MIJOVIDEOS_CPANEL_CATEGORIES'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-success" type="submit" name="backup_subscriptions" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_SUBSCRIPTIONS'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-success" type="submit" name="backup_likes" value="<?php echo JText::_('COM_MIJOVIDEOS_LIKES'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-success" type="submit" name="backup_reports" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_REPORTS'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-success" type="submit" name="backup_files" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_FILES'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-success" type="submit" name="backup_reasons" value="<?php echo JText::_('COM_MIJOVIDEOS_REASONS'); ?>" style="margin-bottom: 3px;"/>
                                </td>
                            </tr>
                        </table>
                    </fieldset>

                    <input type="hidden" name="option" value="com_mijovideos" />
                    <input type="hidden" name="view" value="restoremigrate" />
                    <input type="hidden" name="task" value="backup" />

                    <?php if (MijoVideos::isDashboard()) { ?>
                    <input type="hidden" name="dashboard" value="1" />
                    <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
                    <?php } ?>

                    <?php echo JHtml::_('form.token'); ?>
                </form>
            </td>
            <td width="50%">
                <form enctype="multipart/form-data" action="index.php?option=com_mijovideos&amp;view=restoremigrate" method="post" name="adminForm">
                    <fieldset class="adminform" <?php echo MijoVideos::is30() ? '' : 'style="background-color: #F4F4F4;"'; ?>>
                        <legend><?php echo JText::_('COM_MIJOVIDEOS_RESTOREMIGRATE_MIJOVIDEOS_RESTORE'); ?></legend>
                        <table class="adminform">
                            <tr>
                                <td width="120">
                                    <?php echo MijoVideos::is30() ? '' : '<label for="install_package">'; ?><?php echo JText::_('COM_MIJOVIDEOS_COMMON_SELECT_FILE'); ?>:<?php echo MijoVideos::is30() ? '' : '</label>'; ?>&nbsp;&nbsp;&nbsp;<input class="input_box" id="file_restore" name="file_restore" type="file" size="30" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input class="button btn btn-danger" type="submit" name="restore_categories" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_CATEGORIES'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-danger" type="submit" name="restore_channels" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_CHANNELS'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-danger" type="submit" name="restore_playlists" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_PLAYLISTS'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-danger" type="submit" name="restore_playlistvideos" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_PLAYLISTS')." ".JText::_('COM_MIJOVIDEOS_VIDEOS'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-danger" type="submit" name="restore_videos" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_VIDEOS'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-danger" type="submit" name="restore_videoscategories" value="<?php echo JText::_('COM_MIJOVIDEOS_VIDEOS')." ".JText::_('COM_MIJOVIDEOS_CPANEL_CATEGORIES'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-danger" type="submit" name="restore_subscriptions" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_SUBSCRIPTIONS'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-danger" type="submit" name="restore_likes" value="<?php echo JText::_('COM_MIJOVIDEOS_LIKES'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-danger" type="submit" name="restore_reports" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_REPORTS'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-danger" type="submit" name="restore_files" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_FILES'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-danger" type="submit" name="restore_reasons" value="<?php echo JText::_('COM_MIJOVIDEOS_REASONS'); ?>" style="margin-bottom: 3px;"/>
                                </td>
                            </tr>
                        </table>
                    </fieldset>

                    <input type="hidden" name="option" value="com_mijovideos" />
                    <input type="hidden" name="view" value="restoremigrate" />
                    <input type="hidden" name="task" value="restore" />

                    <?php if (MijoVideos::isDashboard()) { ?>
                    <input type="hidden" name="dashboard" value="1" />
                    <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
                    <?php } ?>

                    <?php echo JHtml::_('form.token'); ?>
                </form>
            </td>
        </tr>
    </table>
</fieldset>

<fieldset class="adminform" style="background-color: #FFFFFF;">
    <legend><?php echo JText::_('COM_MIJOVIDEOS_RESTOREMIGRATE_MIGRATE'); ?></legend>
    <table class="noshow">
        <tr style="vertical-align: top;">
            <td width="400">
                <form enctype="multipart/form-data" action="index.php?option=com_mijovideos&amp;view=restoremigrate" method="post" name="adminForm">
                    <fieldset class="adminform" <?php echo MijoVideos::is30() ? '' : 'style="background-color: #F4F4F4;"'; ?>>
                        <legend><?php echo JText::_('hwdMediaShare'); ?></legend>
                        <table class="adminform">
                            <tr>
                                <td width="120">
                                    <input class="button btn btn-primary" type="submit" name="migrate_HwdMediaShareCats" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_CATEGORIES'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-primary" type="submit" name="migrate_HwdMediaShareChannels" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_CHANNELS'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-primary" type="submit" name="migrate_HwdMediaSharePlaylists" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_PLAYLISTS'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-primary" type="submit" name="migrate_HwdMediaShareVideos" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_VIDEOS'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-primary" type="submit" name="migrate_HwdMediaShareLikes" value="<?php echo JText::_('COM_MIJOVIDEOS_LIKES'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-primary" type="submit" name="migrate_HwdMediaShareReports" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_REPORTS'); ?>" style="margin-bottom: 3px;"/>
                                </td>
                            </tr>
                        </table>
                    </fieldset>

                    <input type="hidden" name="option" value="com_mijovideos" />
                    <input type="hidden" name="view" value="restoremigrate" />
                    <input type="hidden" name="task" value="migrate" />

                    <?php if (MijoVideos::isDashboard()) { ?>
                    <input type="hidden" name="dashboard" value="1" />
                    <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
                    <?php } ?>

                    <?php echo JHtml::_('form.token'); ?>
                </form>
            </td>

            <td width="270">
                <form enctype="multipart/form-data" action="index.php?option=com_mijovideos&amp;view=restoremigrate" method="post" name="adminForm">
                    <fieldset class="adminform" <?php echo MijoVideos::is30() ? '' : 'style="background-color: #F4F4F4;"'; ?>>
                        <legend><?php echo JText::_('Contus HDVideoShare'); ?></legend>
                        <table class="adminform">
                            <tr>
                                <td width="120">
                                    <input class="button btn btn-primary" type="submit" name="migrate_ContusHDVideoShareCats" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_CATEGORIES'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-primary" type="submit" name="migrate_ContusHDVideoShareVideos" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_VIDEOS'); ?>" style="margin-bottom: 3px;"/>
                                </td>
                            </tr>
                        </table>
                    </fieldset>

                    <input type="hidden" name="option" value="com_mijovideos" />
                    <input type="hidden" name="view" value="restoremigrate" />
                    <input type="hidden" name="task" value="migrate" />

                    <?php if (MijoVideos::isDashboard()) { ?>
                    <input type="hidden" name="dashboard" value="1" />
                    <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
                    <?php } ?>

                    <?php echo JHtml::_('form.token'); ?>
                </form>
            </td>

            <td width="270">
                <form enctype="multipart/form-data" action="index.php?option=com_mijovideos&amp;view=restoremigrate" method="post" name="adminForm">
                    <fieldset class="adminform" <?php echo MijoVideos::is30() ? '' : 'style="background-color: #F4F4F4;"'; ?>>
                        <legend><?php echo JText::_('Jom Webplayer'); ?></legend>
                        <table class="adminform">
                            <tr>
                                <td width="120">
                                    <input class="button btn btn-primary" type="submit" name="migrate_JomWebplayerCats" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_CATEGORIES'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-primary" type="submit" name="migrate_JomWebplayerVideos" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_VIDEOS'); ?>" style="margin-bottom: 3px;"/>
                                </td>
                            </tr>
                        </table>
                    </fieldset>

                    <input type="hidden" name="option" value="com_mijovideos" />
                    <input type="hidden" name="view" value="restoremigrate" />
                    <input type="hidden" name="task" value="migrate" />

                    <?php if (MijoVideos::isDashboard()) { ?>
                    <input type="hidden" name="dashboard" value="1" />
                    <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
                    <?php } ?>

                    <?php echo JHtml::_('form.token'); ?>
                </form>
            </td>

            <td width="270">
                <form enctype="multipart/form-data" action="index.php?option=com_mijovideos&amp;view=restoremigrate" method="post" name="adminForm">
                    <fieldset class="adminform" <?php echo MijoVideos::is30() ? '' : 'style="background-color: #F4F4F4;"'; ?>>
                        <legend><?php echo JText::_('HD Webplayer'); ?></legend>
                        <table class="adminform">
                            <tr>
                                <td width="120">
                                    <input class="button btn btn-primary" type="submit" name="migrate_HdwPlayerCats" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_CATEGORIES'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-primary" type="submit" name="migrate_HdwPlayerVideos" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_VIDEOS'); ?>" style="margin-bottom: 3px;"/>
                                </td>
                            </tr>
                        </table>
                    </fieldset>

                    <input type="hidden" name="option" value="com_mijovideos" />
                    <input type="hidden" name="view" value="restoremigrate" />
                    <input type="hidden" name="task" value="migrate" />

                    <?php if (MijoVideos::isDashboard()) { ?>
                    <input type="hidden" name="dashboard" value="1" />
                    <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
                    <?php } ?>

                    <?php echo JHtml::_('form.token'); ?>
                </form>
            </td>

            <td width="270">
                <form enctype="multipart/form-data" action="index.php?option=com_mijovideos&amp;view=restoremigrate" method="post" name="adminForm">
                    <fieldset class="adminform" <?php echo MijoVideos::is30() ? '' : 'style="background-color: #F4F4F4;"'; ?>>
                        <legend><?php echo JText::_('AllVideoShare'); ?></legend>
                        <table class="adminform">
                            <tr>
                                <td width="120">
                                    <input class="button btn btn-primary" type="submit" name="migrate_AllVideoShareCats" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_CATEGORIES'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-primary" type="submit" name="migrate_AllVideoShareVideos" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_VIDEOS'); ?>" style="margin-bottom: 3px;"/>
                                </td>
                            </tr>
                        </table>
                    </fieldset>

                    <input type="hidden" name="option" value="com_mijovideos" />
                    <input type="hidden" name="view" value="restoremigrate" />
                    <input type="hidden" name="task" value="migrate" />

                    <?php if (MijoVideos::isDashboard()) { ?>
                    <input type="hidden" name="dashboard" value="1" />
                    <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
                    <?php } ?>

                    <?php echo JHtml::_('form.token'); ?>
                </form>
            </td>

            <td width="270">
                <form enctype="multipart/form-data" action="index.php?option=com_mijovideos&amp;view=restoremigrate" method="post" name="adminForm">
                    <fieldset class="adminform" <?php echo MijoVideos::is30() ? '' : 'style="background-color: #F4F4F4;"'; ?>>
                        <legend><?php echo JText::_('XMovie'); ?></legend>
                        <table class="adminform">
                            <tr>
                                <td width="120">
                                    <input class="button btn btn-primary" type="submit" name="migrate_XMoviesCats" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_CATEGORIES'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-primary" type="submit" name="migrate_XMoviesVideos" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_VIDEOS'); ?>" style="margin-bottom: 3px;"/>
                                </td>
                            </tr>
                        </table>
                    </fieldset>

                    <input type="hidden" name="option" value="com_mijovideos" />
                    <input type="hidden" name="view" value="restoremigrate" />
                    <input type="hidden" name="task" value="migrate" />

                    <?php if (MijoVideos::isDashboard()) { ?>
                    <input type="hidden" name="dashboard" value="1" />
                    <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
                    <?php } ?>

                    <?php echo JHtml::_('form.token'); ?>
                </form>
            </td>

            <td width="270">
                <form enctype="multipart/form-data" action="index.php?option=com_mijovideos&amp;view=restoremigrate" method="post" name="adminForm">
                    <fieldset class="adminform" <?php echo MijoVideos::is30() ? '' : 'style="background-color: #F4F4F4;"'; ?>>
                        <legend><?php echo JText::_('VideoFlow'); ?></legend>
                        <table class="adminform">
                            <tr>
                                <td width="120">
                                    <input class="button btn btn-primary" type="submit" name="migrate_VideoFlowCats" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_CATEGORIES'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-primary" type="submit" name="migrate_VideoFlowVideos" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_VIDEOS'); ?>" style="margin-bottom: 3px;"/>
                                </td>
                            </tr>
                        </table>
                    </fieldset>

                    <input type="hidden" name="option" value="com_mijovideos" />
                    <input type="hidden" name="view" value="restoremigrate" />
                    <input type="hidden" name="task" value="migrate" />

                    <?php if (MijoVideos::isDashboard()) { ?>
                    <input type="hidden" name="dashboard" value="1" />
                    <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
                    <?php } ?>

                    <?php echo JHtml::_('form.token'); ?>
                </form>
            </td>

            <td width="270">
                <form enctype="multipart/form-data" action="index.php?option=com_mijovideos&amp;view=restoremigrate" method="post" name="adminForm">
                    <fieldset class="adminform" <?php echo MijoVideos::is30() ? '' : 'style="background-color: #F4F4F4;"'; ?>>
                        <legend><?php echo JText::_('HD FLV Player'); ?></legend>
                        <table class="adminform">
                            <tr>
                                <td width="120">
                                    <input class="button btn btn-primary" type="submit" name="migrate_HdFlvPlayerCats" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_CATEGORIES'); ?>" style="margin-bottom: 3px;"/>
                                    &nbsp;
                                    <input class="button btn btn-primary" type="submit" name="migrate_HdFlvPlayerVideos" value="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_VIDEOS'); ?>" style="margin-bottom: 3px;"/>
                                </td>
                            </tr>
                        </table>
                    </fieldset>

                    <input type="hidden" name="option" value="com_mijovideos" />
                    <input type="hidden" name="view" value="restoremigrate" />
                    <input type="hidden" name="task" value="migrate" />

                    <?php if (MijoVideos::isDashboard()) { ?>
                    <input type="hidden" name="dashboard" value="1" />
                    <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
                    <?php } ?>

                    <?php echo JHtml::_('form.token'); ?>
                </form>
            </td>
        </tr>
    </table>
</fieldset>