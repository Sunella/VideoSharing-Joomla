<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

$ordering = ($this->lists['order'] == 'p.ordering');

?>

<form action="<?php echo MijoVideos::get('utility')->getActiveUrl(); ?>" method="post" name="adminForm" id="adminForm">
    <?php if (MijoVideos::isDashboard()) { ?>
    <div style="float: left; width: 99%;">
        <?php if ($this->acl->canCreate()) { ?>
        <button class="btn btn-success" onclick="submitAdminForm('add');return false;"><span class="icon-new icon-white"></span> <?php echo JText::_('COM_MIJOVIDEOS_NEW'); ?></button>
        <?php } ?>
        <?php if ($this->acl->canEdit()) { ?>
        <button class="btn" onclick="submitAdminForm('edit');return false;"><span class="icon-edit"></span> <?php echo JText::_('COM_MIJOVIDEOS_EDIT'); ?></button>
        <?php } ?>
        <?php if ($this->acl->canEditState()) { ?>
        <button class="btn" onclick="submitAdminForm('publish');return false;"><span class="icon-publish"></span> <?php echo JText::_('COM_MIJOVIDEOS_PUBLISH'); ?></button>
        <button class="btn" onclick="submitAdminForm('unpublish');return false;"><span class="icon-unpublish"></span> <?php echo JText::_('COM_MIJOVIDEOS_UNPUBLISH'); ?></button>
        <?php } ?>
        <?php if ($this->acl->canCreate()) { ?>
        <button class="btn" onclick="submitAdminForm('copy');return false;"><span class="icon-copy"></span> <?php echo JText::_('COM_MIJOVIDEOS_COPY'); ?></button>
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
            <?php echo $this->lists['filter_published']; ?>

            <select name="filter_access" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_SELECT_ACCESS');?></option>
                <?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->filter_access);?>
            </select>

            <?php if ($this->acl->canAdmin()) { ?>
            <select name="filter_language" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE');?></option>
                <?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->filter_language);?>
            </select>
            <?php } ?>
        </td>
    </tr>
    </table>
<div id="editcell">
	<table class="adminlist table table-striped">
        <thead>
            <tr>
                <th width="5px" style="text-align: center;">
                    <?php echo JText::_('#'); ?>
                </th>

                <th width="20px" style="text-align: center;">
                    <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                </th>

                <th style="text-align: left;">
                    <?php echo JHtml::_('grid.sort', JText::_('COM_MIJOVIDEOS_TITLE'), 'p.title', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>

                <th class="title" width="7%">
                    <?php echo JText::_( 'COM_MIJOVIDEOS_CHANNEL'); ?>
                </th>

                <th width="5%" style="text-align: center;">
                    <?php echo JHtml::_('grid.sort', JText::_('JSTATUS'), 'p.published', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>

                <?php if ($this->acl->canAdmin()) { ?>
                <th width="5%" style="text-align: center;">
                    <?php echo JHtml::_('grid.sort', JText::_('JFEATURED'), 'p.featured', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <?php } ?>
				
				<th width="<?php echo MijoVideos::is30() ? '12' : '8'; ?>%" style="text-align: right;">
					<?php echo JHtml::_('grid.sort', JText::_('JGRID_HEADING_ORDERING'), 'p.ordering', $this->lists['order_Dir'], $this->lists['order']); ?>
					<?php if ($ordering) { ?>
					<?php echo JHtml::_('grid.order', $this->cPlaylists, 'filesave.png', 'saveOrder'); ?>
					<?php } ?>
				</th>

                <th width="10%" style="text-align: center;">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'p.access', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>

                <?php if ($this->acl->canAdmin()) { ?>
				<th width="5%" style="text-align: center;">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'p.language', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
                <?php } ?>

                <th width="3%" style="text-align: center;">
                    <?php echo JHtml::_('grid.sort', 'JGLOBAL_HITS', 'p.hits', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>

                <?php if ($this->acl->canAdmin()) { ?>
                <th width="5px">
                    <?php echo JHtml::_('grid.sort', JText::_('COM_MIJOVIDEOS_ID'), 'p.id', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
        <?php
        if (isset($this->cPlaylists)){
        $k = 0;
        $n = count($this->cPlaylists);
        for ($i=0; $i < $n; $i++) {
            $row = $this->cPlaylists[$i];

            $link = MijoVideos::get('utility')->route('index.php?option=com_mijovideos&view=playlists&task=edit&cid[]='.$row->id);
            $channel_link = MijoVideos::get('utility')->route('index.php?option=com_mijovideos&view=channels&task=edit&cid[]='.$row->channel_id);

            $checked = JHtml::_('grid.id', $i, $row->id );

            $published = $this->getIcon($i, $task = $row->published == '0' ? 'publish' : 'unpublish', $row->published ? 'publish_y.png' : 'publish_x.png', true);
            $featured = $this->getIcon($i, $task = $row->featured == '0' ? 'feature' : 'unfeature', $row->featured ? 'featured.png' : 'disabled.png', true);

            ?>
            <tr class="<?php echo "row$k"; ?>">
                <td style="text-align: center;">
                    <?php echo $this->pagination->getRowOffset($i); ?>
                </td>
                <td style="text-align: center;">
                    <?php echo $checked; ?>
                </td>

                <td class="text_left">
                    <?php if ($this->acl->canEditOwn($row->user_id)) { ?>
                        <a href="<?php echo $link; ?>">
                            <?php echo $row->title; ?>
                        </a>
                    <?php } else { ?>
                        <?php echo $row->title; ?>
                    <?php } ?>
                </td>

                <td class="text_left">
                    <?php if ($this->acl->canEditOwn($row->user_id)) { ?>
                        <a href="<?php echo $channel_link; ?>">
                            <?php echo $row->channel_title; ?>
                        </a>
                    <?php } else { ?>
                        <?php echo $row->channel_title; ?>
                    <?php } ?>
                </td>
				
                <td class="text_center">
                    <?php echo $published; ?>
                </td>

                <?php if ($this->acl->canAdmin()) { ?>
                <td class="text_center">
                    <?php echo $featured; ?>
                </td>
                <?php } ?>
				
				<td class="ordering" style="text-align: right;">
					<?php if ($ordering) { ?>
					<span><?php echo $this->pagination->orderUpIcon($i, ($row->user_id==0 || $row->user_id == @$this->cPlaylists[$i-1]->user_id),'orderup', 'Move Up', $ordering ); ?></span>
					<span><?php echo $this->pagination->orderDownIcon($i, $n, ($row->user_id ==0 || $row->user_id == @$this->cPlaylists[$i+1]->user_id), 'orderdown', 'Move Down', $ordering ); ?></span>
					<?php } ?>
					<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>				
					<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" style="text-align: center; width: 30px;" <?php echo $disabled; ?> />
				</td>

                <td class="text_center">
                    <?php echo $this->levels[$row->access]->title; ?>
                </td>

                <?php if ($this->acl->canAdmin()) { ?>
				<td style="text-align: center;">
					<?php if ($row->language == '*') { ?>
					<?php echo JText::alt('JALL', 'language'); ?>
					<?php } else { ?>
					<?php echo isset($this->langs[$row->language]->title) ? $this->escape($this->langs[$row->language]->title) : JText::_('JUNDEFINED'); ?>
					<?php } ?>
				</td>
                <?php } ?>

                <td class="text_center">
                    <?php echo $row->hits; ?>
                </td>

                <?php if ($this->acl->canAdmin()) { ?>
                <td class="text_center">
                    <?php echo $row->id; ?>
                </td>
                <?php } ?>

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
	<input type="hidden" name="view" value="playlists" />
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