<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

$editor = JFactory::getEditor();
$utility = MijoVideos::get('utility');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			Joomla.submitform( pressbutton );
			return;				
		} else {
			<?php echo $editor->save('introtext'); ?>
			Joomla.submitform( pressbutton );
		}
	}
</script>

<form action="<?php echo MijoVideos::get('utility')->getActiveUrl(); ?>" method="post" name="adminForm" enctype="multipart/form-data" id="adminForm">
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
                    <?php echo JText::_('COM_MIJOVIDEOS_TITLE'); ?>
                </td>
                <td class="value2">
                    <input class="text_area inputbox required" type="text" name="title" id="title" style="font-size: 1.364em; width: 50%;" size="65" maxlength="250" value="<?php echo $this->item->title;?>" />
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
                    <?php echo JText::_('COM_MIJOVIDEOS_PARENT'); ?>
                </td>
                <td class="value2">
                    <?php echo $this->lists['parent']; ?>
                </td>
            </tr>
			<tr>
                <td class="key2"><?php echo JText::_('COM_MIJOVIDEOS_PICTURE'); ?></td>
                <td class="value2">
                    <input type="file" class="inputbox" name="thumb_image" size="32" />
                    <?php if ($this->item->thumb) { ?>
                        <a href="<?php echo $utility->getThumbPath($this->item->id, 'categories', $this->item->thumb); ?>" class="modal"><img src="<?php echo $utility->getThumbPath($this->item->id, 'categories', $this->item->thumb); ?>" class="img_preview" /></a>
                        <input type="checkbox" name="del_thumb" value="1" /><?php echo JText::_('COM_MIJOVIDEOS_DELETE_CURRENT'); ?>
                    <?php } ?>
                </td>
            </tr>
            
            <tr>
                <td class="key2">
                    <?php echo JText::_( 'COM_MIJOVIDEOS_DESCRIPTION'); ?>
                </td>
                <td class="value2">
                	<?php 
		            # Description
		            $pageBreak = "<hr id=\"system-readmore\">";
		            
		            $fulltextLen = strlen($this->item->fulltext);
		            
		            if ($fulltextLen > 0){
		            	$content = "{$this->item->introtext}{$pageBreak}{$this->item->fulltext}";
		            } else {
		            	$content = "{$this->item->introtext}";
		            }
		            
                    echo $editor->display( 'introtext',  $content , '100%', '250', '75', '10') ; ?>
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

            <tr>
                <td class="key2">
                    <?php echo JText::_('COM_MIJOVIDEOS_ACCESS_LEVEL'); ?>
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
                    <?php echo $this->lists['language'] ; ?>
                </td>
            </tr>
        </table>

        <!-- Meta Settings -->
        <?php echo JHtml::_('tabs.panel', JText::_('COM_MIJOVIDEOS_META_OPTIONS'), 'publishing'); ?>
        <table class="admintable" width="100%">
            <tr>
                <td class="key2">
                    <?php echo JText::_('COM_MIJOVIDEOS_META_DESC'); ?>
                </td>
                <td class="value2">
                    <textarea name="meta_desc" id="meta_desc" cols="40" rows="3" class="" aria-invalid="false"><?php echo $this->item->meta_desc;?></textarea>
                </td>
            </tr>
            <tr>
                <td class="key2">
                    <?php echo JText::_('COM_MIJOVIDEOS_META_KEYWORDS'); ?>
                </td>
                <td class="value2">
                    <textarea name="meta_key" id="meta_key" cols="40" rows="3" class="" aria-invalid="false"><?php echo $this->item->meta_key;?></textarea>
                </td>
            </tr>
            <tr>
                <td class="key2">
                    <?php echo JText::_('COM_MIJOVIDEOS_META_AUTHOR'); ?>
                </td>
                <td class="value2">
                    <input class="text_area" type="text" name="meta_author" id="meta_author" size="40" maxlength="250" value="<?php echo $this->item->meta_author;?>" />
                </td>
            </tr>
        </table>

    <?php echo JHtml::_('tabs.end'); ?>

    <div class="clearfix"></div>

	<input type="hidden" name="option" value="com_mijovideos" />
	<input type="hidden" name="view" value="categories" />
    <input type="hidden" name="task" value="" />
	<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />

    <?php if (MijoVideos::isDashboard()) { ?>
    <input type="hidden" name="dashboard" value="1" />
    <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
    <?php } ?>

    <?php echo JHtml::_('form.token'); ?>
</form>