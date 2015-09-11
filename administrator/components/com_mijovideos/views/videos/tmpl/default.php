<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

JHtml::_('behavior.modal');
$utility = MijoVideos::get('utility');
?>
<form action="<?php echo MijoVideos::get('utility')->getActiveUrl(); ?>" method="post" name="adminForm" id="adminForm">
    <?php if (MijoVideos::isDashboard()) { ?>
    <div style="float: left; width: 99%;">
        <?php if ($this->acl->canCreate()) { ?>
        <button class="btn btn-success" onclick="window.location = '<?php echo $utility->route('index.php?option=com_mijovideos&view=upload'); ?>';return false;"><span class="icon-new icon-white"></span> <?php echo JText::_('COM_MIJOVIDEOS_NEW'); ?></button>
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
                <button onclick="document.getElementById('search').value='';this.form.submit();" class="btn"><?php echo JText::_( 'Reset' ); ?></button>
            </td>
            <td class="miwi_filter">
                <?php if ($this->acl->canAdmin()) { ?>
                <?php echo $this->lists['filter_category']; ?>
                <?php } ?>
                <?php //echo $this->lists['filter_channel']; ?>
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
                <th width="5px">
                    <?php echo JText::_('#'); ?>
                </th>
                <th width="20px">
                    <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                </th>
                <th width="80px" style="text-align: center;">
                    <?php echo JText::_('COM_MIJOVIDEOS_THUMB'); ?>
                </th>
                <th class="title" style="text-align: left;">
                    <?php echo JHtml::_('grid.sort',  JText::_('COM_MIJOVIDEOS_TITLE'), 'title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
                </th>
                <?php if ($this->acl->canAdmin()) { ?>
                <th width="18%" style="text-align: left;">
                    <?php echo JText::_('COM_MIJOVIDEOS_CATEGORY'); ?>
                </th>
                <?php } ?>
                <th width="7%">
                    <?php echo JText::_('COM_MIJOVIDEOS_CHANNEL'); ?>
                </th>
                <th width="5%">
                    <?php echo JHtml::_('grid.sort', JText::_('COM_MIJOVIDEOS_PUBLISHED'), 'v.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
                </th>
                <?php if ($this->acl->canAdmin()) { ?>
                <th width="6%">
                    <?php echo JHtml::_('grid.sort', JText::_('COM_MIJOVIDEOS_FEATURE'), 'v.featured', $this->lists['order_Dir'], $this->lists['order'] ); ?>
                </th>
                <?php } ?>
                <th width="10%" style="text-align: center;">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'v.access', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th width="90px" style="text-align: center;">
                    <?php echo JHtml::_('grid.sort',  JText::_('COM_MIJOVIDEOS_DATE_CREATED'), 'v.created', $this->lists['order_Dir'], $this->lists['order'] ); ?>
                </th>
                <?php if ($this->acl->canAdmin()) { ?>
                <th width="5%">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'v.language', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <?php } ?>
                <?php if ($this->acl->canAdmin()) { ?>
                <th width="5px">
                    <?php echo JHtml::_('grid.sort',  JText::_('COM_MIJOVIDEOS_ID'), 'v.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
                </th>
                <?php } ?>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="11">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
        <?php
        $k = 0;
        $n = count($this->items);
        $config = MijoVideos::getConfig();
        for ($i=0; $i < $n; $i++) {
            $row = $this->items[$i];

            $link = $utility->route('index.php?option=com_mijovideos&view=videos&task=edit&cid[]='.$row->id);
            $channel_link = $utility->route('index.php?option=com_mijovideos&view=channels&task=edit&cid[]='.$row->channel_id);

            $checked = JHtml::_('grid.id', $i, $row->id);

            $published = $this->getIcon($i, $task = $row->published == '0' ? 'publish' : 'unpublish', $row->published ? 'publish_y.png' : 'publish_x.png', true);
            $featured = $this->getIcon($i, $task = $row->featured == '0' ? 'feature' : 'unfeature', $row->featured ? 'featured.png' : 'disabled.png', true);
            ?>
            <tr class="<?php echo "row$k"; ?>">
                <td style="vertical-align: middle">
                    <?php echo $this->pagination->getRowOffset($i); ?>
                </td>
                <td style="vertical-align: middle">
                    <?php echo $checked; ?>
                </td>
                <td style="vertical-align: middle">
                        <a href="<?php echo $utility->getThumbPath($row->id, 'videos', $row->thumb); ?>" class="modal"><img src="<?php echo $utility->getThumbPath($row->id, 'videos', $row->thumb); ?>" class="img_preview_list" /></a>
                </td>
                <td style="vertical-align: middle">
                    <?php if ($this->acl->canEditOwn($row->user_id)) { ?>
                        <a href="<?php echo $link; ?>">
                            <?php echo $row->title; ?>
                        </a>
                    <?php } else { ?>
                        <?php echo $row->title; ?>
                    <?php } ?>
                </td>
                <?php if ($this->acl->canAdmin()) { ?>
                <td style="vertical-align: middle">
                    <?php echo $row->categories; ?>
                </td>
                <?php } ?>
                <td style="vertical-align: middle">
                    <?php if ($this->acl->canEditOwn($row->user_id)) { ?>
                        <a href="<?php echo $channel_link; ?>">
                            <?php echo $row->channel_title; ?>
                        </a>
                    <?php } else { ?>
                        <?php echo $row->channel_title; ?>
                    <?php } ?>
                </td>
                <td class="text_center" style="vertical-align: middle">
                    <?php echo $published; ?>
                </td>
                <?php if ($this->acl->canAdmin()) { ?>
                <td class="text_center" style="vertical-align: middle">
                    <?php echo $featured; ?>
                </td>
                <?php } ?>
                <td class="center" style="vertical-align: middle">
                    <?php echo $this->levels[$row->access]->title; ?>
                </td>
                <td style="text-align: center; vertical-align: middle;">
                    <?php echo JHtml::_('date', $row->created, JText::_('DATE_FORMAT_LC4')); ?>
                </td>
                <?php if ($this->acl->canAdmin()) { ?>
                <td class="center nowrap" style="vertical-align: middle">
                    <?php if ($row->language == '*') { ?>
                    <?php echo JText::alt('JALL', 'language'); ?>
                    <?php } else { ?>
                    <?php echo isset($this->langs[$row->language]->title) ? $this->escape($this->langs[$row->language]->title) : JText::_('JUNDEFINED'); ?>
                    <?php } ?>
                </td>
                <?php } ?>
                <?php if ($this->acl->canAdmin()) { ?>
                <td class="text_center" style="vertical-align: middle">
                    <?php echo $row->id; ?>
                </td>
                <?php } ?>
            </tr>
            <?php
            $k = 1 - $k;
        }
        ?>
        </tbody>
        </table>
	</div>

	<input type="hidden" name="option" value="com_mijovideos" />
	<input type="hidden" name="view" value="videos" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />

    <?php if (MijoVideos::isDashboard()) { ?>
    <input type="hidden" name="dashboard" value="1" />
    <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
    <?php } ?>

    <?php echo JHtml::_( 'form.token' ); ?>
</form>