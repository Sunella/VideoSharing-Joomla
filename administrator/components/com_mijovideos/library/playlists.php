<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted Access');

class MijovideosPlaylists {

    public function __construct() {
		$this->config = MijoVideos::getConfig();
	}

    public function getPlaylist($id) {
        static $cache = array();

        if (!isset($cache[$id])) {
            $cache[$id] = MijoDB::loadObject('SELECT * FROM #__mijovideos_playlists WHERE id = '.$id);
        }

        return $cache[$id];
    }

    public function getTotalPlaylists($video_id = 0, $status = 3) {
        static $cache = array();

        if (!isset($cache[$video_id][$status])) {
			$where = array();
		
            if ($video_id != 0) {
                $where[] = 'video_id = '.$video_id;
            }
		
            if ($status != 0) {
                $where[] = 'status = '.$status;
            }

			$where = count($where) ? ' WHERE '. implode(' AND ', $where) : '';

            $cache[$video_id][$status] = (int)MijoDB::loadResult('SELECT COUNT(*) AS total_playlists FROM #__mijovideos_playlists '.$where);
        }

        return $cache[$video_id][$status];
    }
}