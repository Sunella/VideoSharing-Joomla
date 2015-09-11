<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted access');

?>

<div id="editcell">
    <table class="adminlist table table-striped">
        <thead>
            <tr>
                <th width="5" style="text-align: center;">
                    <?php echo JText::_('COM_MIJOVIDEOS_ID'); ?>
                </th>

                <th width="20%" style="text-align: left;">
                    <?php echo JText::_('COM_MIJOVIDEOS_FILES_TYPE'); ?>
                </th>

                <th style="text-align: left;">
                    <?php echo JText::_('COM_MIJOVIDEOS_FILES_PATH'); ?>
                </th>

                <th width="10%" style="text-align: center;">
                    <?php echo JText::_('COM_MIJOVIDEOS_FILES_EXTENSION'); ?>
                </th>

                <th width="10%" style="text-align: center;">
                    <?php echo JText::_('COM_MIJOVIDEOS_FILES_SIZE'); ?>
                </th>

                <th width="10%" style="text-align: center;">
                    <?php echo JText::_('COM_MIJOVIDEOS_FILES_DOWNLOAD'); ?>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php
        if (!empty($this->files)){
            $k = 0;
            foreach ($this->files as $file) {
                $p_type = MijoVideos::get('processes')->getTypeTitle($file->process_type);

                if ($file->process_type < 7) {
                    $file_path = str_replace(JUri::root(), '/', MijoVideos::get('utility')->getThumbPath($file->video_id, 'videos', $file->source));
                }
                else {
                    $p_size = MijoVideos::get('processes')->getTypeSize($file->process_type);

                    $file_path = MijoVideos::get('utility')->getVideoFilePath($file->video_id, $p_size, $file->source);
                }

                $item = MijoVideos::getTable('MijovideosVideos');
                $item->load($file->video_id);
                if ($file->process_type == 100) { // HTML5 formats
                    if ($file->ext == 'jpg') {
                        $file_path = str_replace(JUri::root(), '/', MijoVideos::get('utility')->getThumbPath($file->video_id, 'videos', $file->source));
                        $p_type = 'Thumbnail';
                    } elseif ($file->ext == 'mp4' or $file->ext == 'webm' or $file->ext == 'ogg' or $file->ext == 'ogv') {
                        $file_path = MijoVideos::get('utility')->getVideoFilePath($item->id, 'orig', $item->source);
                        $default_size = MijoVideos::get('utility')->getVideoSize(JPATH_ROOT . $file_path);
                        $file_path = MijoVideos::get('utility')->getVideoFilePath($file->video_id, $default_size, $file->source);
                        $p_type .= " (".$default_size."p)";
                    }
                } elseif ($file->process_type == 200) { // Original File
                    $file_path = MijoVideos::get('utility')->getVideoFilePath($item->id, 'orig', $item->source);
                    $p_type = 'Original';
                } else {
                    $p_size = MijoVideos::get('processes')->getTypeSize($file->process_type);
                    if ($file->process_type < 7) {
                        $file_path = str_replace(JUri::root(), '/', MijoVideos::get('utility')->getThumbPath($file->video_id, 'videos', $file->source, $p_size));
                        $file->ext = 'jpg';
                    } else {
                        $file_path = MijoVideos::get('utility')->getVideoFilePath($file->video_id, $p_size, $file->source);
                    }
                }

                ?>
                <tr class="<?php echo "row$k"; ?>">
                    <td style="text-align: center;">
                        <?php echo $file->id; ?>
                    </td>

                    <td style="text-align: left;">
                        <?php echo $p_type; ?>
                    </td>

                    <td style="text-align: left;">
                        <?php echo $file_path; ?>
                    </td>

                    <td style="text-align: center;">
                        <?php echo $file->ext; ?>
                    </td>

                    <td style="text-align: center;">
                        <?php echo MijoVideos::get('utility')->getFilesizeFromNumber($file->file_size); ?>
                    </td>

                    <td style="text-align: center;">
                        <a href="<?php echo JUri::root().$file_path; ?>">
                            <?php echo JText::_('COM_MIJOVIDEOS_FILES_DOWNLOAD'); ?>
                        </a>
                    </td>

                </tr>
                <?php
                $k = 1 - $k;
            }
        }
        ?>
        </tbody>
    </table>
</div>