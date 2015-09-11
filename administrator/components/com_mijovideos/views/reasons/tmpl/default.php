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
        <button class="btn" onclick="window.top.location = '<?php echo MijoVideos::get('utility')->route('index.php?option=com_mijovideos&view=reports'); ?>';return false;"><span class="icon-warning"></span> <?php echo JText::_('COM_MIJOVIDEOS_CPANEL_REPORTS'); ?></button>
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
                <button onclick="document.getElementById('search').value='';this.form.submit();" class="btn"><?php echo JText::_( 'Reset' ); ?></button>
            </td>

            <td class="miwi_filter">
                <?php echo $this->lists['filter_published']; ?>

                <select name="filter_access" class="inputbox" onchange="this.form.submit()">
                    <option value=""><?php echo JText::_('JOPTION_SELECT_ACCESS');?></option>
                    <?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->filter_access);?>
                </select>

                <select name="filter_language" class="inputbox" onchange="this.form.submit()">
                    <option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE');?></option>
                    <?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->filter_language);?>
                </select>
            </td>
        </tr>
    </table>
    <div id="editcell">
        <table class="adminlist table table-striped">
            <thead>
                <tr>
                    <th width="5">
                        <?php echo JText::_( '#' ); ?>
                    </th>
                    <th width="20">
                        <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                    </th>
                    <th class="title" style="text-align: left;">
                        <?php echo JHtml::_('grid.sort',  JText::_('COM_MIJOVIDEOS_TITLE'), 'rs.title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
                    </th>
                    <th width="10%" style="text-align: center;">
                        <?php echo JHtml::_('grid.sort', JText::_( 'COM_MIJOVIDEOS_PUBLISHED'), 'rs.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
                    </th>
                    <th width="20%" style="text-align: center;">
                        <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'rs.access', $this->lists['order_Dir'], $this->lists['order']); ?>
                    </th>
                    <th width="15%" style="text-align: center;">
                        <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'rs.language', $this->lists['order_Dir'], $this->lists['order']); ?>
                    </th>
                    <th width="5%" style="text-align: center;">
                        <?php echo JHtml::_('grid.sort',  JText::_( 'COM_MIJOVIDEOS_ID'), 'rs.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
                    </th>
                </tr>
            </thead>

            <tbody>
            <?php
            $k = 0;
            $n = count($this->items);
            for ($i=0; $i < $n; $i++) {
                $row = &$this->items[$i];

                $link = MijoVideos::get('utility')->route('index.php?option=com_mijovideos&view=reasons&task=edit&cid[]='.$row->id);

                $checked = JHtml::_('grid.id', $i, $row->id);

                $published = $this->getIcon($i, $task = $row->published == '0' ? 'publish' : 'unpublish', $row->published ? 'publish_y.png' : 'publish_x.png', true);
                ?>
                <tr class="<?php echo "row$k"; ?>">
                    <td>
                        <?php echo $this->pagination->getRowOffset( $i ); ?>
                    </td>
                    <td>
                        <?php echo $checked; ?>
                    </td>
                    <td>
                        <?php if (MijoVideos::get('acl')->canEdit()) { ?>
                        <a href="<?php echo $link; ?>">
                            <?php echo $row->title; ?>
                        </a>
                        <?php } else { ?>
                        <?php echo $row->title; ?>
                        <?php } ?>
                    </td>
                    <td class="text_center">
                        <?php echo $published; ?>
                    </td>
                    <td class="center">
                        <?php echo $this->levels[$row->access]->title; ?>
                    </td>
                    <td class="center nowrap">
                        <?php if ($row->language == '*') { ?>
                        <?php echo JText::alt('JALL', 'language'); ?>
                        <?php } else { ?>
                        <?php echo isset($this->langs[$row->language]->title) ? $this->escape($this->langs[$row->language]->title) : JText::_('JUNDEFINED'); ?>
                        <?php } ?>
                    </td>
                    <td class="text_center">
                        <?php echo $row->id; ?>
                    </td>
                </tr>
                <?php
                $k = 1 - $k;
            }
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="11">
                        <?php echo $this->pagination->getListFooter(); ?>
                    </td>
                </tr>
            </tfoot>
        </table>
	</div>

	<input type="hidden" name="option" value="com_mijovideos" />
	<input type="hidden" name="view" value="reasons" />
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