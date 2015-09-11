<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die;

$maxUpload = (int)$this->config->get('upload_max_filesize');
$maxPhpUpload = min((int)ini_get('post_max_size'), (int)ini_get('upload_max_filesize'),(int)$maxUpload);
$isVideoPage = false;
$utility = MijoVideos::get('utility');
if (JRequest::getWord('task') == 'edit' and JRequest::getWord('view') == 'videos') {
    $isVideoPage = true;
}
if (MijoVideos::isDashboard()) {
    $dashboard = '&dashboard=1';
}
?>

<?php echo JHtml::_('sliders.start', 'mijovideos_slider_upload'); ?>
    <?php echo JHtml::_('sliders.panel', JText::sprintf('COM_MIJOVIDEOS_UPLOAD_VIDEOS_LESS_THAN_N_MB', $maxPhpUpload), 'small');?>

    <form action="<?php echo $utility->route('index.php?option=com_mijovideos&view=upload&task=upload&format=raw'); ?>" method="post" target="_parent" name="uploadForm" <?php echo $this->config->get('upload_script') == 'dropzone' ? "id=\"dropzone\" class=\"dropzone\"" : "id=\"uploadForm\" class=\"form-validate\""?> enctype="multipart/form-data">
        <?php if ($this->config->get('upload_script') != 'dropzone' ) { // Dropzone ?>
            <fieldset class="adminform" id="mijovideos_fallback">
                <ul class="panelform">
                    <li>
                        <label for="mijovideos_photoupload">
                            <?php echo JText::_('COM_MIJOVIDEOS_UPLOAD_A_FILE') ?>
                        </label>
                        <input type="file" name="Filedata" />
                    </li>
                    <li>
                        <label></label>
                        <button type="button" onclick="Joomla.submitbutton('upload')">
                            <?php echo JText::_('COM_MIJOVIDEOS_UPLOAD') ?>
                        </button>
                    </li>
                </ul>
            </fieldset>
        <?php } ?>
        <?php if ($this->config->get('upload_script') == 'fancy') { // Fancy
            ?>
            <?php echo $this->loadTemplate('fancy'); ?>

            <fieldset class="adminform">
                <div id="mijovideos_status" class="hide">
                    <p>
                        <img src="<?php echo JUri::root(true); ?>/components/com_mijovideos/assets/images/fancy/button_select.png" href="#" id="mijovideos_browse" class="button" />
                        <img src="<?php echo JUri::root(true); ?>/components/com_mijovideos/assets/images/fancy/button_clear.png" href="#" id="mijovideos_clear" class="button" />
                        <img src="<?php echo JUri::root(true); ?>/components/com_mijovideos/assets/images/fancy/button_start.png" href="#" id="mijovideos_upload" class="button" />
                    </p>
                    <div>
                        <span class="overall-title"></span>
                        <img src="<?php echo JUri::root(true); ?>/components/com_mijovideos/assets/images/fancy/bar.gif" class="progress overall-progress" />
                    </div>
                    <div class="clr"></div>
                    <div>
                        <span class="current-title"></span>
                        <img src="<?php echo JUri::root(true); ?>/components/com_mijovideos/assets/images/fancy/bar.gif" class="progress current-progress" />
                    </div>
                    <div class="current-text"></div>
                </div>
                <ul id="mijovideos_list"></ul>
            </fieldset>
        <?php } ?>

        <?php if (MijoVideos::isDashboard()) { ?>
            <input type="hidden" name="dashboard" value="1" />
            <input type="hidden" name="Itemid" value="<?php echo MijoVideos::getInput()->getInt('Itemid', 0); ?>" />
        <?php } ?>
    </form>

    <?php if ($this->config->get('perl_upload') == 1) { ?>
        <?php echo JHtml::_('sliders.panel', JText::sprintf('COM_MIJOVIDEOS_UPLOAD_LARGE_VIDEOS_UP_TO_N_MB', $maxUpload ), 'large');?>
        <form action="<?php echo $utility->route('index.php?option=com_mijovideos&view=upload&task=upload'); ?>" method="post" target="_parent" name="ubr_upload" id="ubr_upload" class="form-validate" enctype="multipart/form-data">
            <?php echo $this->loadTemplate('uber'); ?>

            <?php if (MijoVideos::isDashboard()) { ?>
                <input type="hidden" name="dashboard" value="1" />
                <input type="hidden" name="Itemid" value="<?php echo MijoVideos::getInput()->getInt('Itemid', 0); ?>" />
            <?php } ?>

        </form>
    <?php } ?>
<?php echo JHtml::_('sliders.panel', JText::_( 'COM_MIJOVIDEOS_REMOTE_VIDEO' ), 'remote');?>
    <form action="<?php echo $utility->route('index.php?option=com_mijovideos&view=upload&task=remoteLink'); ?>" method="post" target="_parent" name="remote_links" id="remote_links" class="form-validate" enctype="multipart/form-data">
        <fieldset class="adminform">

            <?php if($isVideoPage) { ?>
                <div class="mijovideos_remote_links"><?php echo JText::_('COM_MIJOVIDEOS_REMOTE_VIDEO_LINK'); ?></div>
                <input type="text" name="remote_links" style="width: 20%">
            <?php } else { ?>
                <div class="mijovideos_remote_links"><?php echo JText::_('COM_MIJOVIDEOS_REMOTE_VIDEO_LINKS'); ?></div>
                <textarea name="remote_links" class="mijovideos_remote_link_textarea"></textarea>
            <?php } ?>
            <button class="btn" onclick="Joomla.submitbutton('remoteLink')"><?php echo JText::_('COM_MIJOVIDEOS_UPLOAD'); ?></button>
            <?php if (MijoVideos::isDashboard()) { ?>
                <input type="hidden" name="dashboard" value="1" />
                <input type="hidden" name="Itemid" value="<?php echo MijoVideos::getInput()->getInt('Itemid', 0); ?>" />
            <?php } ?>
        </fieldset>
    </form>
<?php echo JHtml::_('sliders.end'); ?>

<div class="clr"> </div>

<?php if ($this->config->get('upload_script') == 'dropzone' ) { // Dropzone ?>
    <style type="text/css">
        .dropzone a.dz-remove, .dropzone-previews a.dz-remove {
            margin-top: 10px;
        }
    </style>
    <script type="text/javascript">
        Dropzone.options.dropzone = {
            <?php if($isVideoPage) {
            echo 'maxFiles: 1,';
            }
            ?>
            addRemoveLinks: true,
            clickable: true,
            maxFilesize: <?php echo $maxPhpUpload; ?>,
            dictDefaultMessage: "<?php echo JText::_("COM_MIJOVIDEOS_DROPZONE_DEFAULT_MESSAGE"); ?>",
            dictFallbackMessage: "<?php echo JText::_("COM_MIJOVIDEOS_DROPZONE_FALLBACK_MESSAGE"); ?>",
            dictFallbackText: "<?php echo JText::_("COM_MIJOVIDEOS_DROPZONE_FALLBACK_TEXT"); ?>",
            dictFileTooBig: "<?php echo JText::_("COM_MIJOVIDEOS_DROPZONE_FILE_TOO_BIG"); ?>",
            dictInvalidFileType: "<?php echo JText::_("COM_MIJOVIDEOS_DROPZONE_INVALID_FILE_TYPE"); ?>",
            dictResponseError: "<?php echo JText::_("COM_MIJOVIDEOS_DROPZONE_RESPONSE_ERROR"); ?>",
            dictCancelUpload: "<?php echo JText::_("COM_MIJOVIDEOS_DROPZONE_CANCEL_UPLOAD"); ?>",
            dictCancelUploadConfirmation: "<?php echo JText::_("COM_MIJOVIDEOS_DROPZONE_CANCEL_UPLOAD_CONFIRMATION"); ?>",
            dictRemoveFile: "<?php echo JText::_("COM_MIJOVIDEOS_DROPZONE_REMOVE_FILE"); ?>",
            accept: function(file, done) {
                var types = '<?php echo $this->config->get('allow_file_types'); ?>';
                types = types.split('|');
                var asd = file.name.split('.').pop();
                if (types.indexOf(file.name.split('.').pop()) == -1) {
                    done("<?php echo JText::_("COM_MIJOVIDEOS_DROPZONE_INVALID_FILE_TYPE"); ?>");
                } else {
                    done();
                }
            },
            <?php if (MijoVideos::get('utility')->checkFfmpegInstalled()) { ?>
                success: function(file, responseText) {
                    var responseObj = JSON.parse(responseText);
                    if (responseObj['success']) {
                        var a = document.createElement('a');
                        var linkText = document.createTextNode("<?php echo JText::_("COM_MIJOVIDEOS_CONVERTING"); ?>");
                        a.appendChild(linkText);
                        a.href = responseObj['href'];
                        a.id = 'dropzone_info';
                        a.className = 'dz-remove';
                        file.previewTemplate.appendChild(a);
                    }
                    jQuery.ajax({
                        url: 'index.php?option=com_mijovideos&view=upload&task=convertToHtml5&format=raw<?php echo isset($dashboard) ? $dashboard : ''; ?>',
                        type: 'post',
                        data: {video_id: responseObj['id'], location: responseObj['location']},
                        dataType: 'json',
                        success: function(json) {
                            if (json['error']) {
                                var node, _i, _len, _ref, _results;
                                var message = json['error']
                                file.previewElement.classList.add("dz-error");
                                _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
                                _results = [];
                                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                                    node = _ref[_i];
                                    _results.push(node.textContent = message);
                                }
                                var div = file.previewTemplate.getElementById('dropzone_info');
                                div.parentNode.removeChild(div);
                                return _results;
                            }

                            if (json['success']) {
                                file.previewTemplate.getElementById('dropzone_info').innerHTML = '<?php echo JText::_("COM_MIJOVIDEOS_EDIT"); ?>';
                                return file.previewElement.classList.add("dz-success");
                            }
                        }

                    });
                }
            <?php } else { ?>
                success: function(file, responseText) {
                    var responseObj = JSON.parse(responseText);
                    if (responseObj['success']) {
                        var a = document.createElement('a');
                        var linkText = document.createTextNode("<?php echo JText::_("COM_MIJOVIDEOS_CONVERTING"); ?>");
                        a.appendChild(linkText);
                        a.href = responseObj['href'];
                        a.id = 'dropzone_info';
                        a.className = 'dz-remove';
                        file.previewTemplate.appendChild(a);
                        file.previewTemplate.getElementById('dropzone_info').innerHTML = '<?php echo JText::_("COM_MIJOVIDEOS_EDIT"); ?>';
                        return file.previewElement.classList.add("dz-success");
                    }
                }
            <?php } ?>
        };
    </script>
<?php } ?>
<script type="text/javascript">
    jQuery('#dropzone').append('<input type="hidden" name="channel_id" value="<?php echo JRequest::getInt('channel_id', MijoVideos::get('channels')->getDefaultChannel()->id); ?>">');
    jQuery('#ubr_upload').append('<input type="hidden" name="channel_id" id="channel_id" value="<?php echo JRequest::getInt('channel_id', MijoVideos::get('channels')->getDefaultChannel()->id); ?>">');
    jQuery('#remote_links').append('<input type="hidden" name="channel_id" value="<?php echo JRequest::getInt('channel_id', MijoVideos::get('channels')->getDefaultChannel()->id); ?>">');
</script>

