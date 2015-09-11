<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');

$field		= JRequest::getCmd('field');
$function	= 'jSelectChannel_'.$field;
//$listOrder	= $this->escape($this->state->get('list.ordering'));
//$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo JRoute::_('index.php?option=com_mijovideos&view=channels&layout=modal&tmpl=component&groups='.JRequest::getVar('groups', '', 'default', 'BASE64').'&excluded='.JRequest::getVar('excluded', '', 'default', 'BASE64'));?>" method="post" name="adminForm" id="adminForm">
	<fieldset class="filter">
		<div class="left">
			<label for="filter_search"><?php echo JText::_('JSEARCH_FILTER'); ?></label>
            <input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area search-query" onchange="document.adminForm.submit();" />
            <button onclick="this.form.submit();" class="btn"><?php echo JText::_( 'Go' ); ?></button>
            <button onclick="document.getElementById('search').value='';this.form.submit();" class="btn"><?php echo JText::_('Reset'); ?></button>
			<button type="button" onclick="if (window.parent) window.parent.<?php echo $this->escape($function);?>('', '<?php echo JText::_('JLIB_FORM_SELECT_USER') ?>');"><?php echo JText::_('JOPTION_NO_USER')?></button>
		</div>
	</fieldset>

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
                    <?php echo JHtml::_('grid.sort', JText::_('COM_MIJOVIDEOS_TITLE'), 'c.title', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>


                <th width="10%" style="text-align: center;">
                    <?php echo JHtml::_('grid.sort', JText::_('JGLOBAL_USERNAME'), 'u.username', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>


                <th width="5%" style="text-align: center;">
                    <?php echo JHtml::_('grid.sort', JText::_('JSTATUS'), 'c.published', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>

                <th width="5%" style="text-align: center;">
                    <?php echo JHtml::_('grid.sort', JText::_('COM_MIJOVIDEOS_FEATURE'), 'c.featured', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>

                <th width="10%" style="text-align: center;">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'c.access', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>


                <th width="3%" style="text-align: center;">
                    <?php echo JHtml::_('grid.sort', 'JGLOBAL_HITS', 'c.hits', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>

                <th width="3%">
                    <?php echo JHtml::_('grid.sort', JText::_('COM_MIJOVIDEOS_ID'), 'r.id', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (isset($this->cChannels)){
                $k = 0;
                $n = count($this->cChannels);
                for ($i=0; $i < $n; $i++) {
                    $row = $this->cChannels[$i];

                    $link = JRoute::_('index.php?option=com_mijovideos&view=channels&task=edit&cid[]='.$row->id);

                    $checked = JHtml::_('grid.id', $i, $row->id );



                        $img = $row->published ? 'tick.png' : 'publish_x.png';
                        $alt = $row->published ? JText::_('JPUBLISHED') : JText::_('JUNPUBLISHED');
                        $published = JHtml::_('image', 'admin/' . $img, $alt, null, true);

                        $img = $row->featured ? 'featured.png' : 'disabled.png';
                        $featured = JHtml::_('image', 'admin/' . $img, '', null, true);


                    ?>
                    <tr class="<?php echo "row$k"; ?>">
                        <td style="text-align: center;">
                            <?php echo $this->pagination->getRowOffset($i); ?>
                        </td>
                        <td style="text-align: center;">
                            <?php echo $checked; ?>
                        </td>

                        <td class="text_left">
                            <a class="pointer" onclick="if (window.parent) window.parent.<?php echo $this->escape($function);?>('<?php echo $row->id; ?>', '<?php echo $this->escape(addslashes($row->title)); ?>');">
                                <?php echo $row->title; ?>
                            </a>
                        </td>

                        <td class="text_center">
                            <a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id='.$row->user_id); ?>" target="_top">
                                <?php echo $row->username; ?>
                            </a>
                        </td>

                        <td class="text_center">
                            <?php echo $published; ?>
                        </td>

                        <td class="text_center">
                            <?php echo $featured; ?>
                        </td>

                        <td class="text_center">
                            <?php echo $this->levels[$row->access]->title; ?>
                        </td>

                        <td class="text_center">
                            <?php echo $row->hits; ?>
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
    <input type="hidden" name="view" value="channels" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
    <?php echo JHtml::_('form.token'); ?>

    <script type="text/javascript">
        function submitbutton(pressbutton) {
            if (pressbutton == 'add_registrant') {
                var form = document.adminForm;
                if (form.video_id.value == 0) {
                    alert("<?php echo JText::_('COM_MIJOVIDEOS_CHOOSE_VIDEO_TO_ADD'); ?>");
                    form.video_id.focus();
                    return
                }
            }
            submitform(pressbutton);
        }
    </script>
</form>
