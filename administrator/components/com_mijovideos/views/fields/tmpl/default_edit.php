<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

?>
<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			Joomla.submitform( pressbutton );
			return;				
		} else {
			//Should validate the information here
			if (form.name.value == "") {
				alert("<?php echo JText::_('COM_MIJOVIDEOS_ENTER_FIELD_NAME'); ?>");
				form.name.focus();
				return ;
			}
			if (form.title.value == "") {
				alert("<?php echo JText::_("COM_MIJOVIDEOS_ENTER_FIELD_TITLE"); ?>");
				form.title.focus();
				return ; 
			}
			if (form.field_type.value == -1) {
				alert("<?php echo JText::_("COM_MIJOVIDEOS_CHOOSE_FIELD_TYPE") ; ?>");
				return ; 
			}			
			Joomla.submitform( pressbutton );
		}
	}

    function checkFieldName() {
        var form = document.adminForm;
        var name = form.name.value;
        var oldValue = name;

        name = name.replace('mijo_','');
        name = name.replace(/[^a-zA-Z0-9_]*/ig, '');
        form.name.value='mijo_' + name;
    }
</script>

<form action="<?php echo MijoVideos::get('utility')->getActiveUrl(); ?>" method="post" name="adminForm" id="adminForm">
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
        <table class="admintable">
            <tr>
                <td class="key2">
                    <span class="editlinktip hasTip" title="<?php echo JText::_('COM_MIJOVIDEOS_NAME'); ?>::<?php echo JText::_('COM_MIJOVIDEOS_FIELD_NAME_REQUIREMENT'); ?>"><?php echo JText::_('COM_MIJOVIDEOS_NAME'); ?></span>
                </td>
                <td class="value2">
                    <input class="text_area" type="text" name="name" id="name" size="50" maxlength="250" value="<?php echo $this->item->name;?>" onchange="checkFieldName();" />
                </td>
            </tr>

            <tr>
                <td class="key2">
                    <?php echo  JText::_('COM_MIJOVIDEOS_TITLE'); ?>
                </td>
                <td class="value2">
                    <input class="text_area" type="text" name="title" id="title" size="50" maxlength="250" value="<?php echo $this->item->title;?>" />
                </td>
            </tr>

            <tr>
                <td class="key2">
                    <?php echo JText::_('COM_MIJOVIDEOS_FIELD_TYPE'); ?>
                </td>
                <td class="value2">
                    <?php echo $this->lists['field_type']; ?>
                </td>
            </tr>

            <tr>
                <td class="key2">
                    <?php echo  JText::_('COM_MIJOVIDEOS_DESCRIPTION'); ?>
                </td>
                <td class="value2">
                    <textarea rows="5" cols="50" name="description"><?php echo $this->item->description;?></textarea>
                </td>
            </tr>

            <tr>
                <td class="key2">
                    <span class="editlinktip hasTip" title="<?php echo JText::_('COM_MIJOVIDEOS_VALUES'); ?>::<?php echo JText::_('COM_MIJOVIDEOS_EACH_ITEM_LINE'); ?>"><?php echo JText::_('COM_MIJOVIDEOS_VALUES'); ?></span>
                </td>
                <td class="value2">
                    <textarea rows="5" cols="50" name="values"><?php echo $this->item->values; ?></textarea>
                </td>
            </tr>
            <tr>
                <td class="key2">
                    <span class="editlinktip hasTip" title="<?php echo JText::_('COM_MIJOVIDEOS_DEFAULT_VALUES'); ?>::<?php echo JText::_('COM_MIJOVIDEOS_EACH_ITEM_LINE'); ?>"><?php echo JText::_('COM_MIJOVIDEOS_DEFAULT_VALUES'); ?></span>
                </td>
                <td class="value2">
                    <textarea rows="5" cols="50" name="default_values"><?php echo $this->item->default_values; ?></textarea>
                </td>
            </tr>

            <?php
                if ($this->config->get('cb_integration')) {
                ?>
                    <tr>
                        <td class="key2">
                            <span class="editlinktip hasTip" title="<?php echo JText::_('COM_MIJOVIDEOS_FIELD_MAPPING'); ?>::<?php echo JText::_('COM_MIJOVIDEOS_FIELD_MAPPING_EXPLAIN'); ?>"><?php echo JText::_('COM_MIJOVIDEOS_FIELD_MAPPING'); ?></span>
                        </td>
                        <td class="value2">
                            <?php echo $this->lists['field_mapping']; ?>
                        </td>
                    </tr>
                <?php
                }
            ?>
        </table>

        <!-- Style -->
    <?php echo JHtml::_('tabs.panel', JText::_('COM_MIJOVIDEOS_STYLE'), 'style'); ?>
        <table class="admintable" width="100%">
            <tr>
                <td class="key2">
                    <?php echo  JText::_( 'COM_MIJOVIDEOS_ROWS'); ?>
                </td>
                <td class="value2">
                    <input class="text_area" type="text" name="rows" id="rows" size="10" maxlength="250" value="<?php echo $this->item->rows;?>" />
                </td>
            </tr>
            <tr>
                <td class="key2">
                    <?php echo  JText::_( 'COM_MIJOVIDEOS_COLS'); ?>
                </td>
                <td class="value2">
                    <input class="text_area" type="text" name="cols" id="cols" size="10" maxlength="250" value="<?php echo $this->item->cols;?>" />
                </td>
            </tr>
            <tr>
                <td class="key2">
                    <?php echo  JText::_( 'COM_MIJOVIDEOS_SIZE'); ?>
                </td>
                <td class="value2">
                    <input class="text_area" type="text" name="size" id="size" size="10" maxlength="250" value="<?php echo $this->item->size;?>" />
                </td>
            </tr>
            <tr>
                <td class="key2">
                    <?php echo  JText::_('COM_MIJOVIDEOS_CSS_CLASS'); ?>
                </td>
                <td class="value2">
                    <input class="text_area" type="text" name="css_class" id="css_class" size="10" maxlength="250" value="<?php echo $this->item->css_class;?>" />
                </td>
            </tr>
        </table>

        <!-- Publishing Options -->
        <?php echo JHtml::_('tabs.panel', JText::_('COM_MIJOVIDEOS_PUBLISHING_OPTIONS'), 'publishing'); ?>
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
            <!-- <tr>
                <td class="key2">
                    <?php echo JText::_('COM_MIJOVIDEOS_ACCESS_LEVEL'); ?>
                </td>
                <td class="value2">
                    <?php echo $this->lists['access']; ?>
                </td>
            </tr> -->
            <tr>
                <td class="key2">
                    <?php echo JText::_('COM_MIJOVIDEOS_LANGUAGE'); ?>
                </td>
                <td class="value2">
                    <?php echo $this->lists['language'] ; ?>
                </td>
            </tr>
        </table>

    <?php echo JHtml::_('tabs.end'); ?>

    <div class="clearfix"></div>

    <input type="hidden" name="option" value="com_mijovideos" />
    <input type="hidden" name="view" value="fields" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />

    <?php if (MijoVideos::isDashboard()) { ?>
    <input type="hidden" name="dashboard" value="1" />
    <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
    <?php } ?>

    <?php echo JHtml::_('form.token'); ?>
</form>