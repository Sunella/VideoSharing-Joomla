<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

JHtml::_('behavior.modal', 'a.reasons_message');
?>
<form action="<?php echo MijoVideos::get('utility')->getActiveUrl(); ?>" method="post" name="adminForm" id="adminForm">
    <?php if (MijoVideos::isDashboard()) { ?>
    <div style="float: left; width: 99%;">
        <button class="btn" onclick="window.top.location = '<?php echo MijoVideos::get('utility')->route('index.php?option=com_mijovideos&view=reasons'); ?>';return false;"><span class="icon-checkbox-partial"></span> <?php echo JText::_('COM_MIJOVIDEOS_REASONS'); ?></button>
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
                <?php echo $this->lists['filter_reason']; ?>
                <?php echo $this->lists['filter_type']; ?>

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
                <th class="title" width="7%">
                    <?php echo JText::_( 'COM_MIJOVIDEOS_CHANNEL'); ?>
                </th>
                <th width="20%">
                    <?php echo JHtml::_('grid.sort', JText::_( 'COM_MIJOVIDEOS_ITEM_TITLE'), 'v.item_title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
                </th>
                <th width="5%">
                    <?php echo JHtml::_('grid.sort', JText::_( 'COM_MIJOVIDEOS_TYPE'), 'r.item_type', $this->lists['order_Dir'], $this->lists['order'] ); ?>
                </th>
                <th class="title" width="10%">
                    <?php echo JHtml::_('grid.sort',  JText::_( 'COM_MIJOVIDEOS_DATE'), 'r.created', $this->lists['order_Dir'], $this->lists['order'] ); ?>
                </th>
                <th width="5%">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'r.lang_code', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th width="1%">
                    <?php echo JHtml::_('grid.sort',  JText::_( 'COM_MIJOVIDEOS_ID'), 'r.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php
        $k = 0;
        $n = count($this->items);
        for ($i=0; $i < $n; $i++) {
            $row = &$this->items[$i];

            $link = JRoute::_('index.php?option=com_mijovideos&view=reasons&task=edit&cid='.$row->reason_id);
            $message_link = JRoute::_('index.php?option=com_mijovideos&view=reports&layout=details&tmpl=component&cid='.$row->id);
            $channel_id = MijoVideos::get('channels')->getDefaultChannel()->id;
            $channel_link = JRoute::_('index.php?option=com_mijovideos&view=channels&channel_id='.$channel_id);

            if ($row->item_type == 'videos') {
                $context = 'video';
                $item_editlink = MijoVideos::get('utility')->route('index.php?option=com_mijovideos&view=videos&task=edit&cid[]='.$row->item_id);
            } else {
                $context = 'channel';
                $item_editlink = MijoVideos::get('utility')->route('index.php?option=com_mijovideos&view=channels&task=edit&cid[]='.$row->item_id);
            }

            $item_viewlink = JUri::root().'index.php?option=com_mijovideos&view='.$context.'&'.$context.'_id='.$row->item_id;


            $checked = JHtml::_('grid.id', $i, $row->id);

            ?>
            <tr class="<?php echo "row$k"; ?>">
                <td>
                    <?php echo $this->pagination->getRowOffset( $i ); ?>
                </td>
                <td>
                    <?php echo $checked; ?>
                </td>
                <td>
                    <a href="<?php echo $link; ?>">
                        <?php echo $row->title; ?>
                    </a>
                    <a class="reasons_message" style="font-size: 11px;" href="<?php echo $message_link; ?>" rel="{handler: 'iframe', size: {x: 400, y: 200}}">
                        [<?php echo JText::_('COM_MIJOVIDEOS_MESSAGE'); ?>]
                    </a>
                </td>
                <td>
                    <?php if ($this->acl->canEdit()) { ?>
                        <a href="<?php echo $channel_link; ?>">
                            <?php echo $row->channel_title; ?>
                        </a>
                    <?php } else { ?>
                        <?php echo $row->channel_title; ?>
                    <?php } ?>
                </td>
                <td>
                    <?php echo $row->item_title; ?>
                    <?php if ($this->acl->canEdit()) { ?>
                        <a style="font-size: 11px;" href="<?php echo $item_editlink; ?>">
                            [<?php echo JText::_('COM_MIJOVIDEOS_EDIT'); ?>]
                        </a>
                    <?php } ?>
                    <a style="font-size: 11px;" href="<?php echo $item_viewlink; ?>">
                        [<?php echo JText::_('COM_MIJOVIDEOS_VIEW'); ?>]
                    </a>
                </td>
                <td>
                    <?php echo ucfirst($row->item_type); ?>
                </td>
                <td>
                    <?php echo JHtml::_('date', $row->created, JText::_('DATE_FORMAT_LC4')); ?>
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
	<input type="hidden" name="view" value="reports" />
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