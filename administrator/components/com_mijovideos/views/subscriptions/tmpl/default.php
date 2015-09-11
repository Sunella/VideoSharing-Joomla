<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

$item_id = '';
$Itemid = MijoVideos::getInput()->getInt('Itemid', 0);
if (!empty($Itemid)) {
    $item_id = '&Itemid='.$Itemid;
}

JHtml::_('behavior.tooltip');
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
            </td>
            <?php if ($this->acl->canAdmin()) { ?>
            <td class="miwi_filter">
                <?php echo $this->lists['filter_user']; ?>
            </td>
            <?php } ?>
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
                        <?php echo JText::_('COM_MIJOVIDEOS_TITLE'); ?>
                    </th>

                    <th width="10%" style="text-align: center;">
                        <?php echo JHtml::_('grid.sort', JText::_('JGLOBAL_USERNAME'), 'u.username', $this->lists['order_Dir'], $this->lists['order']); ?>
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

                    $link = MijoVideos::get('utility')->route('index.php?option=com_mijovideos&view=channels&task=edit&cid[]='.$row->channel_id);
                    $checked = JHtml::_('grid.id', $i, $row->id);

                    ?>
                    <tr class="<?php echo "row$k"; ?>">
                        <td style="text-align: center;">
                            <?php echo $this->pagination->getRowOffset($i); ?>
                        </td>
                        <td style="text-align: center;">
                            <?php echo $checked; ?>
                        </td>

                        <td class="text_left">
                            <a href="<?php echo $link; ?>">
                                <?php echo $row->title; ?>
                            </a>
                        </td>

                        <td class="text_center">
                          <?php if (!MijoVideos::isDashboard()) { ?>
                          <a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id='.$row->user_id); ?>">
                              <?php echo $row->username; ?>
                          </a>
                          <?php } else { ?>
                              <?php echo $row->username; ?>
                          <?php } ?>
                      </td>
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
	<input type="hidden" name="view" value="subscriptions" />
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