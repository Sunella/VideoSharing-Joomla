<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted Access');

class MijovideosChannels {

    public function __construct() {
		$this->config = MijoVideos::getConfig();
	}

    public function getChannel($id) {
        static $cache = array();

        if (!isset($cache[$id])) {
            $cache[$id] = MijoDB::loadObject('SELECT * FROM #__mijovideos_channels WHERE id = '.$id);
        }

        return $cache[$id];
    }

    public function getTotalChannels($video_id = 0, $status = 3) {
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

            $cache[$video_id][$status] = (int)MijoDB::loadResult('SELECT COUNT(*) AS total_channels FROM #__mijovideos_channels '.$where);
        }

        return $cache[$video_id][$status];
    }

    public function getNextChannelByStatus($status = 11) {
        return MijoDB::loadObject("SELECT * FROM #__mijovideos_channels WHERE status = {$status} ORDER BY id ASC LIMIT 1");
    }

    public function getUserId($channel_id) {
        static $cache = array();

        if (!isset($cache[$channel_id])) {
            $cache[$channel_id] = MijoDB::loadResult('SELECT user_id FROM #__mijovideos_channels WHERE id = '.$channel_id);
        }

        return $cache[$channel_id];
    }

    public function updateDefaultChannel($new) {
        $cid = JRequest::getVar('cid', array(), 'post', 'array');
        $id = $cid[0];

        if (empty($id)) {
            return;
        }

        if (MijoVideos::get('acl')->canAdmin()) {
            $user_id = $this->getUserId($id);
        }
        else {
            $user_id = JFactory::getUser()->get('id');
        }

        if (empty($user_id)) {
            return;
        }

        $limit = '';

        $old = 0;
        if ($new == 0) {
            $old = 1;
            $limit = ' ORDER BY created LIMIT 1';
        }

        # Action
        MijoDB::query("UPDATE `#__mijovideos_channels` SET `default` = {$new} WHERE user_id = {$user_id} AND id = {$id}");
        MijoDB::query("UPDATE `#__mijovideos_channels` SET `default` = {$old} WHERE user_id = {$user_id} AND id <> {$id} {$limit}");
    }

    public function getDefaultChannel($user_id = null) {
        static $cache = array();

        if (empty($user_id)) {
            $user_id = JFactory::getUser()->get('id');
        }

        if (!isset($cache[$user_id])) {
            $cache[$user_id] = MijoDB::loadObject("SELECT * FROM #__mijovideos_channels WHERE user_id = {$user_id} AND `default` = 1");
        }

        if (!is_object($cache[$user_id])) {
            $cache[$user_id] = new stdClass();
            $cache[$user_id]->id = 0;
            $cache[$user_id]->title = '';
        }

        return $cache[$user_id];
    }

    public function getVideos($channel_id) {

        $videos = MijoDB::loadObjectList("SELECT id FROM #__mijovideos_videos WHERE channel_id = {$channel_id}");

        foreach ($videos as $video) {
            $video_ids[] = $video->id;
        }

        return $video_ids;
    }
}