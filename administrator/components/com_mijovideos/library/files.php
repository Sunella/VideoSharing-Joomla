<?php
/**
 * @package        MijoVideos
 * @copyright    2009-2014 Mijosoft LLC, mijosoft.com
 * @license        GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.filesystem.file');

class MijovideosFiles {

    protected $_fileset = null;

    public function __construct() {
        $this->config = MijoVideos::getConfig();
    }

    public function add($video, $fileType, $source, $size, $process_type = 100) {
        $date = JFactory::getDate();
        $user = JFactory::getUser();
        $db   = JFactory::getDBO();
        $config = $this->config;

        # 100 = HTML5
        if ($process_type != 100) {
            $query = "DELETE FROM #__mijovideos_files WHERE process_type = " . $db->quote($process_type) . " AND video_id = " . $db->quote($video->id);
            $db->setQuery($query);
            if (!$db->query() && $config->get('debug')) {
                JFactory::getApplication()->enqueueMessage(nl2br($db->getErrorMsg()), 'error');
            }
        }

        JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_mijovideos/tables');
        $row = JTable::getInstance('MijovideosFiles', 'Table');

        if ($fileType == 'thumb' or $fileType == 'jpg') {
            $item_path = JPATH_ROOT . '/media/com_mijovideos/images/videos/'.$video->id.'/'.$size.'/'. $source;
        } else {
            $item_path = JPATH_ROOT . '/media/com_mijovideos/videos/'.$video->id.'/'.$size.'/'. $source;
        }

        if (file_exists($item_path)) {
            $post                 = array();
            $post['video_id']     = $video->id;
            $post['process_type'] = $process_type;
            $post['ext']          = $fileType;
            $post['file_size']    = intval(filesize($item_path));
            $post['source']       = $source;
            $post['channel_id']   = MijoVideos::get('channels')->getDefaultChannel()->id;
            $post['user_id']      = $user->id;
            $post['created']      = $date->format('Y-m-d H:i:s');
            $post['published']    = 1;

            // Bind it to the table
            if (!$row->bind($post)) {
                return JError::raiseWarning(500, $row->getError());
            }

            // Store it in the db
            if (!$row->store()) {
                return JError::raiseError(500, $row->getError());
            }
        } else {
            return JError::raiseError(500, "COM_MIJOVIDEOS_FAILED_TO_ADD_FILE_TO_DATABASE_FILE_DOES_NOT_EXIST");
        }
    }

    public function delete($ids, $img_type = 'videos') {
        if (empty($ids)) {
            return false;
        }

        $files = MijoDB::loadObjectList("SELECT * FROM #__mijovideos_files WHERE id IN (".implode(',', $ids).")");

        if (empty($files)) {
            return false;
        }

        foreach ($files as $file) {
            if ($file->process_type < 7 or $file->ext == 'jpg') { // Images
                $file_path = str_replace(JUri::root(), '', '/'.MijoVideos::get('utility')->getThumbPath($file->video_id, $img_type, $file->source));
            } else { // Videos
                if ($file->process_type == 100) { // HTML5 formats
                    $item = MijoVideos::getTable('MijovideosVideos');
                    $item->load($file->video_id);
                    $file_path = MijoVideos::get('utility')->getVideoFilePath($item->id, 'orig', $item->source);
                    $default_size = MijoVideos::get('utility')->getVideoSize(JPATH_ROOT . $file_path);
                    $file_path = MijoVideos::get('utility')->getVideoFilePath($file->video_id, $default_size, $file->source);

                } else {
                    $p_size = MijoVideos::get('processes')->getTypeSize($file->process_type);
                    $file_path = MijoVideos::get('utility')->getVideoFilePath($file->video_id, $p_size, $file->source);
                }
            }

            if (empty($file_path) or !file_exists(JPATH_ROOT . $file_path)) {
                continue;
            }

            JFile::delete(JPATH_ROOT . $file_path);
        }

        return true;
    }

    public function getVideoFiles($item_id) {
        if (empty($item_id) || $item_id == 0) return false;

        static $cache = array();

        if (!isset($cache[$item_id])) {
            $cache[$item_id] = MijoDB::loadObjectList('SELECT * FROM #__mijovideos_files WHERE video_id = ' . (int)$item_id . ' AND published = 1');
        }

        return $cache[$item_id];
    }
}