<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;
?>

<form action="<?php echo MijoVideos::get('utility')->getActiveUrl(); ?>" method="post" name="adminForm" id="adminForm">
    <?php if (MijoVideos::isDashboard()) { ?>
    <div style="float: left; width: 99%;">
        <?php if ($this->acl->canDelete()) { ?>
        <button class="btn" onclick="submitAdminForm('delete');return false;"><span class="icon-delete"></span> <?php echo JText::_('COM_MIJOVIDEOS_DELETE'); ?></button>
        <?php } ?>
    </div>
    <br/>
    <br/>
    <?php } ?>
    <table width="100%">
    <tr>
        <td class="miwi_search">
            <?php echo JText::_( 'Filter' ); ?>:
            <input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area search-query" onchange="document.adminForm.submit();" />
            <button onclick="this.form.submit();" class="btn"><?php echo JText::_( 'Go' ); ?></button>
            <button onclick="document.getElementById('search').value='';this.form.submit();" class="btn"><?php echo JText::_('Reset'); ?></button>
        </td >
        <td class="miwi_filter">
            <?php echo $this->lists['filter_published']; ?>
        </td>
    </tr>
    </table>
<div id="editcell">
	<table class="adminlist table table-striped">
        <thead>
            <tr>
                <th width="5" style="text-align: center;">
                    <?php echo JText::_('#'); ?>
                </th>

                <th width="20" style="text-align: center;">
                    <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                </th>

                <th style="text-align: left;">
                    <?php echo JHtml::_('grid.sort', JText::_('COM_MIJOVIDEOS_VIDEO'), 'f.video_id', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>

                <th style="text-align: left;">
                    <?php echo JHtml::_('grid.sort', JText::_('COM_MIJOVIDEOS_FILES_TYPE'), 'f.process_type', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>

                <?php if (!MijoVideos::isDashboard()) { ?>
                <th style="text-align: left;">
                    <?php echo JText::_('COM_MIJOVIDEOS_FILES_PATH'); ?>
                </th>
                <?php } ?>

                <th width="5%" style="text-align: center;">
                    <?php echo JHtml::_('grid.sort', JText::_('JSTATUS'), 'f.published', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>

                <th width="5%" style="text-align: center;">
                    <?php echo JHtml::_('grid.sort', JText::_('COM_MIJOVIDEOS_FILES_EXTENSION'), 'f.ext', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>

                <th width="10%" style="text-align: center;">
                    <?php echo JHtml::_('grid.sort', JText::_('COM_MIJOVIDEOS_FILES_SIZE'), 'f.size', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>

                <th style="text-align: center;">
                    <?php echo JText::_('COM_MIJOVIDEOS_FILES_DOWNLOAD'); ?>
                </th>

                <?php if (!MijoVideos::isDashboard()) { ?>
                <th width="3%">
                    <?php echo JHtml::_('grid.sort', JText::_('COM_MIJOVIDEOS_ID'), 'f.id', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
        <?php
        if (!empty($this->items)){
            $k = 0;
            $n = count($this->items);

            for ($i=0; $i < $n; $i++) {
                $row = $this->items[$i];

                $video_link = MijoVideos::get('utility')->route('index.php?option=com_mijovideos&view=videos&task=edit&cid[]='.$row->video_id);

                $p_type = MijoVideos::get('processes')->getTypeTitle($row->process_type);

                $item = MijoVideos::getTable('MijovideosVideos');
                $item->load($row->video_id);
                if ($row->process_type == 100) { // HTML5 formats
                    if ($row->ext == 'jpg') {
                        $file_path = str_replace(JUri::root(), '', '/'.MijoVideos::get('utility')->getThumbPath($row->video_id, 'videos', $row->source));
                        $p_type = 'Thumbnail';
                    } elseif ($row->ext == 'mp4' or $row->ext == 'webm' or $row->ext == 'ogg' or $row->ext == 'ogv') {
                        $file_path = MijoVideos::get('utility')->getVideoFilePath($item->id, 'orig', $item->source);
                        $default_size = MijoVideos::get('utility')->getVideoSize(JPATH_ROOT . $file_path);
                        $file_path = MijoVideos::get('utility')->getVideoFilePath($row->video_id, $default_size, $row->source);
                        $p_type .= " (".$default_size."p)";
                    }
                } elseif ($row->process_type == 200) { // Original File
                    $file_path = MijoVideos::get('utility')->getVideoFilePath($item->id, 'orig', $item->source);
                    $p_type = 'Original';
                } else {
                    $p_size = MijoVideos::get('processes')->getTypeSize($row->process_type);
                    if ($row->process_type < 7) {
                        $file_path = str_replace(JUri::root(), '', '/'.MijoVideos::get('utility')->getThumbPath($row->video_id, 'videos', $row->source, $p_size));
                        $row->ext = 'jpg';
                    } else {
                        $file_path = MijoVideos::get('utility')->getVideoFilePath($row->video_id, $p_size, $row->source);
                    }
                }

                $published = $this->getIcon($i, $task = $row->published == '0' ? 'publish' : 'unpublish', $row->published ? 'publish_y.png' : 'publish_x.png', true);

                $checked = JHtml::_('grid.id', $i, $row->id);

                ?>
                <tr class="<?php echo "row$k"; ?>">
                    <td style="text-align: center;">
                        <?php echo $this->pagination->getRowOffset($i); ?>
                    </td>
                    <td style="text-align: center;">
                        <?php echo $checked; ?>
                    </td>

                    <td style="text-align: left;">
                        <?php if ($this->acl->canEditOwn($row->user_id)) { ?>
                            <a href="<?php echo $video_link; ?>">
                                <?php echo $row->video_title; ?>
                            </a>
                        <?php } else { ?>
                            <?php echo $row->video_title; ?>
                        <?php } ?>
                    </td>

                    <td style="text-align: left;">
                        <?php echo $p_type; ?>
                    </td>

                    <?php if (!MijoVideos::isDashboard()) { ?>
                    <td style="text-align: left;">
                        <?php echo $file_path; ?>
                    </td>
                    <?php } ?>

                    <td class="text_center">
                        <?php echo $published; ?>
                    </td>

                    <td style="text-align: center;">
                        <?php echo $row->ext; ?>
                    </td>

                    <td style="text-align: center;">
                        <?php echo MijoVideos::get('utility')->getFilesizeFromNumber($row->file_size); ?>
                    </td>

                    <td style="text-align: center;">
                        <a href="<?php echo JUri::root().$file_path; ?>">
                            <?php echo JText::_('COM_MIJOVIDEOS_FILES_DOWNLOAD'); ?>
                        </a>
                    </td>

                    <?php if (!MijoVideos::isDashboard()) { ?>
                    <td class="text_center">
                        <?php echo $row->id; ?>
                    </td>
                    <?php } ?>

                </tr>
                <?php
                $k = 1 - $k;
            }
        }
        ?>
        </tbody>
        <tfoot>
       		<tr>
       			<td colspan="30">
       				<?php echo $this->pagination->getListFooter(); ?>
       			</td>
       		</tr>
       	</tfoot>
	</table>
	</div>
	
	<input type="hidden" name="option" value="com_mijovideos" />
	<input type="hidden" name="view" value="files" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />

    <?php if (MijoVideos::isDashboard()) { ?>
    <input type="hidden" name="dashboard" value="1" />
    <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
        <?php } ?>

    <?php echo JHtml::_('form.token'); ?>
</form>