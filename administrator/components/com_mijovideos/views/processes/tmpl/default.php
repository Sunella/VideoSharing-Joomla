<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

$ordering = ($this->lists['order'] == 'p.ordering');

$item_id = '';
$Itemid = MijoVideos::getInput()->getInt('Itemid', 0);
if (!empty($Itemid)) {
    $item_id = '&Itemid='.$Itemid;
}
?>
<form action="<?php echo MijoVideos::get('utility')->getActiveUrl(); ?>" method="post" name="adminForm" id="adminForm">
    <?php if (MijoVideos::isDashboard()) { ?>
    <div style="float: left; width: 99%;">
        <?php if ($this->acl->canCreate()) { ?>
        <button class="btn btn-success" ><span class="icon-new icon-white"></span> <?php echo JText::_('COM_MIJOVIDEOS_UPLOAD'); ?></button>
        <?php } ?>
        <?php if ($this->acl->canEdit()) { ?>
        <button class="btn" onclick="submitAdminForm('process');return false;"><span class="icon-play-2"></span> <?php echo JText::_('COM_MIJOVIDEOS_PROCESS'); ?></button>
        <button class="btn" onclick="submitAdminForm('processall');return false;"><span class="icon-play"></span> <?php echo JText::_('COM_MIJOVIDEOS_PROCESS_ALL'); ?></button>
        <?php } ?>
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
            <?php echo $this->lists['filter_status']; ?>
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

                <th width="5%" style="text-align: left;">
                    <?php echo JHtml::_('grid.sort', JText::_('COM_MIJOVIDEOS_VIDEO_ID'), 'v.id', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>

                <th width="83%" style="text-align: left;">
                    <?php echo JHtml::_('grid.sort', JText::_('COM_MIJOVIDEOS_TITLE'), 'pt.title', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>

                <th width="5%" style="text-align: center;">
                    <?php echo JHtml::_('grid.sort', JText::_('JSTATUS'), 'p.status', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>

                <th width="3%" style="text-align: center;">
                    <?php echo JHtml::_('grid.sort', JText::_('COM_MIJOVIDEOS_ID'), 'p.id', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php
        if (isset($this->items)){
        $k = 0;
        $n = count($this->items);
        for ($i=0; $i < $n; $i++) {
            $row = $this->items[$i];

            $video_link = MijoVideos::get('utility')->route('index.php?option=com_mijovideos&view=videos&task=edit&cid[]='.$row->video_id);

            $checked = JHtml::_('grid.id', $i, $row->id );

			switch ($row->status) {
                case 1:
                    $status = JText::_('COM_MIJOVIDEOS_SUCCESSFUL');
                    break;
                case 2:
                    $status = JText::_('COM_MIJOVIDEOS_FAILED');
                    break;
                case 3:
                    $status = JText::_('COM_MIJOVIDEOS_PROCESSING');
                    break;
                default: // 0
                    $status = JText::_('COM_MIJOVIDEOS_QUEUED');
                    break;
            }

            ?>
            <tr class="<?php echo "row$k"; ?>">
                <td style="text-align: center;">
                    <?php echo $this->pagination->getRowOffset($i); ?>
                </td>
                <td style="text-align: center;">
                    <?php echo $checked; ?>
                </td>

                <td class="text_center">
                    <?php if (MijoVideos::get('acl')->canEdit()) { ?>
                        <a href="<?php echo $video_link; ?>">
                            <?php echo $row->video_id; ?>
                        </a>
                    <?php } else { ?>
                        <?php echo $row->video_id; ?>
                    <?php } ?>
                </td>

                <td class="text_left">
                    <?php echo $row->title; ?>
                </td>
				
                <td class="text_center">
                    <?php echo $status; ?>
                </td>

                <td class="text_center">
                    <?php echo $row->id; ?>
                </td>

            </tr>
            <?php
            $k = 1 - $k;
        } } ?>
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
	<input type="hidden" name="view" value="processes" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />

    <?php if (MijoVideos::isDashboard()) { ?>
    <input type="hidden" name="dashboard" value="1" />
    <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
        <?php } ?>

    <?php echo JHtml::_('form.token'); ?>
</form>