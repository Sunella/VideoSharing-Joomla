<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

$format = 'Y-m-d' ;
$editor = JFactory::getEditor();

if (version_compare(JVERSION, '1.6.0', 'ge')) {
    $format = 'Y-m-d';
    $param = null ;
}
else {
    $format = '%Y-%m-%d';
    $param = 0 ;
}
$config = MijoVideos::getConfig();
$utility = MijoVideos::get('utility');
?>
<style>
    .calendar { vertical-align: bottom; }
    .form-horizontal .controls { margin-left: 5px !important; }
</style>

<script type="text/javascript">
    jQuery(function() {
        jQuery("#auto_video").autocomplete({
            source: function( request, response ) {
                var query = document.getElementById("auto_video").value;
                jQuery.ajax({
                    url: "index.php?option=com_mijovideos&view=videos&task=autocomplete&format=raw&tmpl=component&query="+ query,
                    dataType: "json",
                    success: function( data ) {
                        response( jQuery.map( data, function( item ) {
                            return {
                                label: item.name,
                                value: item.id
                            }
                        }));
                    }
                });
            },
            minLength: 3,
            select: function( video, ui ) {
                //TODO: check if field was added before
                var tr = document.getElementById('custom_fields_'+ui.item.label+'_tr');
                if (typeof tr != 'undefined' &&  tr != null){
                    return false;
                }

                jQuery.ajax({
                    url: "index.php?option=com_mijovideos&view=videos&task=createAutoFieldHtml&format=raw&tmpl=component&fieldid="+ ui.item.value,
                    dataType: "html",
                    success: function( html ) {
                        jQuery('#custom_fields').append(html);
                    }
                });
            },
            open: function() {
                jQuery( this ).removeClass("ui-corner-all" ).addClass( "ui-corner-top" );
            },
            close: function() {
                jQuery( this ).removeClass("ui-corner-top" ).addClass( "ui-corner-all" );
                document.getElementById("auto_video").value = '';
            }
        });
    });

    function removeField(value){
        var row = document.getElementById(value);
        row.parentNode.removeChild(row);
    }

</script>

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
        <?php if($config->get('categories')) { ?>
            <tr>
                <td class="key2">
                    <span class="editlinktip hasTip" title="<?php echo JText::_('COM_MIJOVIDEOS_CPANEL_CATEGORIES');?>::<?php echo JText::_('COM_MIJOVIDEOS_VIDEO_CATEGORY_EXPLAIN'); ?>"><?php echo JText::_('COM_MIJOVIDEOS_CPANEL_CATEGORIES'); ?></span>
                </td>
                <td class="value2">
                    <?php echo $this->lists['video_categories'] ; ?>
                </td>
            </tr>
        <?php } ?>

        <?php if (MijoVideos::is31() and $config->get('tags')) { ?>
            <tr>
                <td class="key2">
                    <span class="editlinktip hasTip" title="<?php echo JText::_('COM_MIJOVIDEOS_TAGS');?>::<?php echo JText::_('COM_MIJOVIDEOS_TAGS_EXPLAIN'); ?>"><?php echo JText::_('COM_MIJOVIDEOS_TAGS'); ?></span>
                </td>
                <td class="value2">
                    <?php echo $this->lists['tags'] ; ?>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td class="key2"><?php echo JText::_('COM_MIJOVIDEOS_PICTURE'); ?></td>
            <td class="value2">
                <input type="file" class="inputbox" name="thumb_image" size="32" />
                <a href="<?php echo $utility->getThumbPath($this->item->id, 'videos', $this->item->thumb); ?>" class="modal"><img src="<?php echo $utility->getThumbPath($this->item->id, 'videos', $this->item->thumb); ?>" class="img_preview" /></a>
                <input type="checkbox" name="del_thumb" value="1" /><?php echo JText::_('COM_MIJOVIDEOS_DELETE_CURRENT'); ?>
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

                echo $editor->display('introtext', $content , '100%', '250', '90', '6'); ?>
            </td>
        </tr>
    </table>

    <!-- Publishing -->
    <?php echo JHtml::_('tabs.panel', JText::_('COM_MIJOVIDEOS_PUBLISHING_OPTIONS'), 'sl_publishing'); ?>
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

            <tr>
                <td class="key2">
                    <?php echo JText::_('COM_MIJOVIDEOS_FEATURE'); ?>
                </td>
                <td class="value2">
                    <?php echo $this->lists['featured']; ?>
                </td>
            </tr>
        <?php } else { ?>
            <input type="hidden" name="published" value="<?php echo $this->item->published; ?>" />
            <input type="hidden" name="featured" value="<?php echo $this->item->featured; ?>" />
        <?php } ?>

        <tr>
            <td class="key2">
                <span class="editlinktip hasTip" title="<?php echo JText::_('COM_MIJOVIDEOS_ACCESS');?>::<?php echo JText::_('COM_MIJOVIDEOS_ACCESS_EXPLAIN'); ?>"><?php echo JText::_('COM_MIJOVIDEOS_ACCESS'); ?></span>
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

        <?php if ($this->acl->canAdmin()) { ?>
            <tr>
                <td class="key2">
                    <?php echo JText::_('COM_MIJOVIDEOS_CREATED_BY'); ?>
                </td>
                <td class="value2">
                    <?php echo MijoVideos::get('utility')->getChannelInputbox($this->item->channel_id, 'channel_id'); ?>
                </td>
            </tr>
        <?php } else { ?>
            <input type="hidden" name="channel_id" value="<?php echo $this->item->channel_id; ?>" />
        <?php } ?>

        <tr>
            <td class="key2">
                <?php echo JText::_('COM_MIJOVIDEOS_DATE_CREATED'); ?>
            </td>
            <td class="value2">
                <?php
                
                echo JHtml::_('calendar', ($this->item->created != $this->null_date) ?  JHtml::_('date', $this->item->created, $format, null) :'', 'created', 'created', '%Y-%m-%d', array("class" => "class"));
                ?>
            </td>
        </tr>
        <tr>
            <td class="key2">
                <?php echo JText::_('COM_MIJOVIDEOS_DATE_MODIFIED'); ?>
            </td>
            <td class="value2">
                <?php
                echo JHtml::_('calendar', ($this->item->modified != $this->null_date) ?  JHtml::_('date', $this->item->modified, $format, null) :'', 'modified', 'modified', '%Y-%m-%d', array("class"=>"class"));
                ?>
            </td>
        </tr>
    </table>
    <!-- Video Stats -->
    <?php echo JHtml::_('tabs.panel', JText::_('COM_MIJOVIDEOS_STATSISTICS'), 'stats'); ?>
    <table class="admintable" width="100%">
        <?php if ($config->get('likes_dislikes')) { ?>
            <tr>
                <td class="key2">
                    <?php echo JText::_('COM_MIJOVIDEOS_LIKES'); ?>
                </td>
                <td class="value2">
                    <input type="text" value="<?php echo $this->item->likes; ?>" class="readonly" style="float:left;" size="6" readonly="readonly" aria-invalid="false">
                    <?php if ($this->acl->canAdmin()) { ?>
                        <div class="button2-left">
                            <div class="blank">
                                &nbsp;<a class="btn" id="likes" title="<?php echo JText::_('COM_MIJOVIDEOS_RESET') ?>" href="<?php echo JRoute::_('index.php?option=com_mijovideos&view=videos&task=resetstats&type=likes&id='.$this->item->id); ?>"><?php echo JText::_('COM_MIJOVIDEOS_RESET') ?></a>
                            </div>
                        </div>
                    <?php } ?>
                </td>
            </tr>

            <tr>
                <td class="key2">
                    <?php echo JText::_('COM_MIJOVIDEOS_DISLIKES'); ?>
                </td>
                <td class="value2">
                    <input type="text" value="<?php echo $this->item->dislikes; ?>" class="readonly" style="float:left;" size="6" readonly="readonly" aria-invalid="false">
                    <?php if ($this->acl->canAdmin()) { ?>
                        <div class="button2-left">
                            <div class="blank">
                                &nbsp;<a class="btn" id="dislikes" title="<?php echo JText::_('COM_MIJOVIDEOS_RESET') ?>" href="<?php echo JRoute::_('index.php?option=com_mijovideos&view=videos&task=resetstats&type=dislikes&id='.$this->item->id); ?>"><?php echo JText::_('COM_MIJOVIDEOS_RESET') ?></a>
                            </div>
                        </div>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>

        <tr>
            <td class="key2">
                <?php echo JText::_('COM_MIJOVIDEOS_HITS'); ?>
            </td>
            <td class="value2">
                <input type="text" value="<?php echo $this->item->hits; ?>" class="readonly" style="float:left;" size="6" readonly="readonly" aria-invalid="false">
                <?php if ($this->acl->canAdmin()) { ?>
                    <div class="button2-left">
                        <div class="blank">
                            &nbsp;<a class="btn" id="hits" title="<?php echo JText::_('COM_MIJOVIDEOS_RESET') ?>" href="<?php echo JRoute::_('index.php?option=com_mijovideos&view=playlists&task=resetstats&type=hits&id='.$this->item->id); ?>"><?php echo JText::_('COM_MIJOVIDEOS_RESET') ?></a>
                        </div>
                    </div>
                <?php } ?>
            </td>
        </tr>
    </table>

    <!-- Custom Fields -->
    <?php if ($config->get('custom_fields')) { ?>
        <?php echo JHtml::_('tabs.panel', JText::_('COM_MIJOVIDEOS_VIDEOS_SL_CF_TITLE'), 'custom'); ?>
        <div class="mijovideos_available_fields">
            <?php foreach ($this->availableFields as $avField) { ?>
                <div class="mijovideos_field"><?php echo $avField->title; ?></div>
            <?php } ?>
        </div>
        <table class="admintable" width="100%">
            <tr>
                <td class="key2">
                    <br/>
                    &nbsp;&nbsp;Search Field <input type="text" value="" name="auto_video" id="auto_video" class="ui-autocomplete-input" autocomplete="off">
                    <br /><br />
                    <table class="admintable" id="custom_fields" width="100%">
                        <?php
                        if (!empty($this->fields)) {
                            foreach ($this->fields as $field){
                                ?>
                                <tr id="<?php echo $field->id.'tr'; ?>">
                                    <td class="key2" style="vertical-align: middle;">
                                        <img style="vertical-align: middle;" src="<?php echo JUri::root().'administrator/components/com_mijovideos/assets/images/delete.png' ?>" onclick="removeField('<?php echo $field->id.'tr'; ?>');">
                                        &nbsp;<?php echo $field->label; ?>
                                    </td>
                                    <td class="value2" style="vertical-align: middle;">
                                        <?php echo $field->input; ?>
                                    </td>
                                </tr>
                            <?php
                            }
                        }
                        ?>
                    </table>
                </td>
            </tr>
        </table>
    <?php } ?>
    <!-- Video Settings -->
    <?php if ($this->acl->canEditOwn($this->item->user_id)) { ?>
        <?php echo JHtml::_('tabs.panel', JText::_('COM_MIJOVIDEOS_VIDEO'), 'video'); ?>
    <?php } ?>

    <!-- Files Settings -->
    <?php if ($this->acl->canAdmin()) { ?>
        <?php echo JHtml::_('tabs.panel', JText::_('COM_MIJOVIDEOS_CPANEL_FILES'), 'files'); ?>
        <?php echo $this->loadTemplate('files'); ?>
    <?php } ?>

    <!-- Meta Settings -->
    <?php echo JHtml::_('tabs.panel', JText::_('COM_MIJOVIDEOS_META_OPTIONS'), 'metadata'); ?>
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
                <input class="hide" type="text" name="edit_product_id" id="edit_product_id" size="1" readonly="readonly" value="<?php echo $this->item->product_id;?>" />
            </td>
        </tr>
    </table>

    <?php echo JHtml::_('tabs.end'); ?>

    <input type="hidden" name="option" value="com_mijovideos" />
    <input type="hidden" name="view" value="videos" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />

    <?php if (MijoVideos::isDashboard()) { ?>
        <input type="hidden" name="dashboard" value="1" />
        <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
    <?php } ?>

    <?php echo JHtml::_('form.token'); ?>

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
                if (form.created.value == '') {
                    alert("<?php echo JText::_( 'COM_MIJOVIDEOS_ENTER_VIDEO_DATE'); ?>");
                    form.created.focus();
                    return ;
                }

                <?php
                    $editorFields = array('introtext');
                    foreach ($editorFields as $editorField) {
                        echo $editor->save($editorField);
                    }

                ?>
                Joomla.submitform(pressbutton);
            }
        }
    </script>
</form>
<dd id="video_dropzone" class="tabs" style="display: none;">
    <?php echo $this->loadForeignTemplate('upload'); ?>
</dd>
<script type="text/javascript">
    jQuery(document).ready(function(){
        var s = jQuery("dt.video");
        if(s[0].className == 'tabs video open'){
            var dd = document.getElementById('video_dropzone');
            dd.style.display = 'inherit';
        }

        jQuery("dt.tabs").click(function(){
            var dd = document.getElementById('video_dropzone');
            var tab = this.innerHTML;
            var isvideo = tab.indexOf("<?php echo JText::_('COM_MIJOVIDEOS_VIDEO'); ?>");

            if(isvideo != -1){
                dd.style.display = 'inherit';
            }
            else{
                dd.style.display = 'none';
            }
        });
    });
</script>
<script type="text/javascript">
    jQuery('#dropzone').append('<input type="hidden" name="item_id" value="<?php echo $this->item->id; ?>">');
    jQuery('#ubr_upload').append('<input type="hidden" name="item_id" id="item_id" value="<?php echo $this->item->id; ?>">');
    jQuery('#remote_links').append('<input type="hidden" name="item_id" value="<?php echo $this->item->id; ?>">');
</script>