<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die;

$editor = JFactory::getEditor();
?>
<form action="<?php echo MijoVideos::get('utility')->getActiveUrl(); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form form-horizontal">
    <?php if (MijoVideos::isDashboard()) { ?>
        <div style="float: left; width: 99%; margin-left: 10px;">
            <button class="btn btn-success" onclick="Joomla.submitbutton('apply')"><span class="icon-apply icon-white"></span> <?php echo JText::_('COM_MIJOVIDEOS_SAVE'); ?></button>
            <button class="btn" onclick="Joomla.submitbutton('save')"><span class="icon-save"></span> <?php echo JText::_('COM_MIJOVIDEOS_SAVE_CLOSE'); ?></button>
            <?php if ($this->acl->canCreate()) { ?>
            <button class="btn" onclick="Joomla.submitbutton('save2new')"><span class="icon-save-new"></span> <?php echo JText::_('COM_MIJOVIDEOS_SAVE_NEW'); ?></button>
            <?php } ?>
            <button class="btn" onclick="Joomla.submitbutton('cancel')"><span class="icon-cancel"></span> <?php echo JText::_('COM_MIJOVIDEOS_CANCEL'); ?></button>
        </div>
        <br/>
        <br/>
    <?php } ?>

    <?php echo JHtml::_('tabs.start', 'mijovideos', array('useCookie'=>1)); ?>

        <!-- Details -->
        <?php echo JHtml::_('tabs.panel', JText::_('COM_MIJOVIDEOS_DETAILS'), 'details'); ?>
        <table class="admintable" width="100%">
            <tr>
                <td class="key2">
                    <?php echo JText::_( 'COM_MIJOVIDEOS_TITLE') ; ?>
                </td>
                <td class="value2">
                    <input type="text" name="title" value="<?php echo $this->item->title; ?>" class="inputbox required" style="font-size: 1.364em; width: 50%;" size="65" aria-required="true" required="required" aria-invalid="false"/>
                </td>
            </tr>

            <tr>
                <td class="key2">
                    <?php echo JText::_('COM_MIJOVIDEOS_ALIAS'); ?>
                </td>
                <td class="value2">
                    <input class="text_area" type="text" name="alias" id="alias" size="45" maxlength="250" value="<?php echo $this->item->alias;?>" />
                </td>
            </tr>
            <tr>
                <td class="key2">
                    <?php echo JText::_( 'COM_MIJOVIDEOS_DESCRIPTION'); ?>
                </td>
                <td class="value2">
                    <?php echo $editor->display('description', $this->item->description , '100%', '250', '90', '6'); ?>
                </td>
            </tr>
        </table>

        <!-- Publishing -->
        <<?php echo JHtml::_('tabs.panel', JText::_('COM_MIJOVIDEOS_PUBLISHING_OPTIONS'), 'publishing'); ?>
        <table class="admintable" width="100%">
            <?php if ($this->acl->canEditState()) { ?>
            <tr>
                <td class="key2">
                    <?php echo JText::_('COM_MIJOVIDEOS_PUBLISHED'); ?>
                </td>
                <td class="value2">
                    <?php echo $this->lists['published']; ?>
                </td>
            </tr>
            <?php } else { ?>
                <input type="hidden" name="published" value="<?php echo $this->item->published; ?>" />
            <?php } ?>

            <tr>
                <td class="key2">
                    <span><?php echo JText::_('COM_MIJOVIDEOS_ACCESS'); ?></span>
                </td>
                <td class="value2">
                    <?php echo $this->lists['access']; ?>
                </td>
            </tr>

            <tr>
                <td class="key2">
                    <?php echo JText::_('COM_MIJOVIDEOS_LANGUAGE'); ?>
                </td>
                <td class="value2">
                    <?php echo $this->lists['language']; ?>
                </td>
            </tr>

            <tr>
                <td class="key2">
                    <?php echo JText::_('COM_MIJOVIDEOS_ASSOCIATION'); ?>
                </td>
                <td class="value2">
                    <?php echo $this->lists['association']; ?>
                </td>
            </tr>
        </table>

    <?php echo JHtml::_('tabs.end'); ?>

    <div class="clearfix"></div>

	<input type="hidden" name="option" value="com_mijovideos" />
    <input type="hidden" name="view" value="reasons" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
	<?php echo JHtml::_( 'form.token' ); ?>

    <?php if (MijoVideos::isDashboard()) { ?>
    <input type="hidden" name="dashboard" value="1" />
    <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
    <?php } ?>

	<script type="text/javascript">
		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				Joomla.submitform(pressbutton);
				return;
			} else {
				//Should have some validations rule here
				//Check something here
				if (form.title.value == '') {
					alert("<?php echo JText::_( 'COM_MIJOVIDEOS_PLEASE_ENTER_TITLE'); ?>");
					form.title.focus();
					return ;
				}
				Joomla.submitform(pressbutton);
			}
		}
	</script>
</form>