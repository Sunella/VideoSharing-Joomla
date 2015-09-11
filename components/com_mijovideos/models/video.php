<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosModelVideo extends MijovideosModel {

    public function __construct() {
		parent::__construct('video', 'videos');

        $this->video_id = JRequest::getInt('video_id');
        if (!is_null(JRequest::getInt('item_id', null))) {
            $this->video_id = JRequest::getInt('item_id', null);
        }
        $this->playlist_id = JRequest::getInt('playlist_id', null);
	}

    public function getData() {
		if (empty($this->_data)) {
            $nullDate = $this->_db->getNullDate();
			$user_id = JFactory::getUser()->get('id');

			$this->_data = MijoVideos::get('videos')->getVideo($this->video_id);

			$this->_data->category_id = Mijovideos::get('utility')->getVideoCategory($this->video_id)->id;

            $sql = "SELECT COUNT(channel_id) AS channel_videos_count FROM #__mijovideos_videos WHERE channel_id = {$this->_data->channel_id}";
            $this->_db->setQuery($sql);

            $this->_data->channel_videos_count = $this->_db->loadResult();

            $this->_data->playlist_id = $this->playlist_id;

            /*$sql = "SELECT COUNT(*) AS channel_subs FROM #__mijovideos_subscriptions WHERE channel_id = {$this->_data->channel_id}";
            $this->_db->setQuery($sql);*/



            $this->_data->channel_subs = MijoVideos::get('model')->getSubscriberCount($this->_data->channel_id);
		}

		return $this->_data;
	}

    public function _buildViewWhere() {
        $where = array();
        $user = JFactory::getUser();

        # Video page... Update like or dislike field...
        $sel = JRequest::getVar('selection', 'selected', 'post');
        if ($sel == 'filtered' && !is_null(JRequest::getInt('change', null, 'post'))) {
            return $where = "WHERE user_id = {$user->id} AND item_id = {$this->video_id}";
        ###########
        } else {
            if  (!empty($this->video_id)) { # Video Page
                $where[] = 'v.id='.$this->video_id;
            } else { # Playlist Page
                $where [] = 'pv.playlist_id='.$this->playlist_id;
            }
            $where[] = 'v.published=1';
            $where[] = 'v.access IN ('.implode(',', $user->getAuthorisedViewLevels()).')';

            if ($this->_mainframe->getLanguageFilter()) {
                $where[] = 'v.language IN (' . $this->_db->Quote(JFactory::getLanguage()->getTag()) . ',' . $this->_db->Quote('*') . ')';
            }

            if (!empty($this->search)) {
                $src = parent::secureQuery($this->search, true);
                $where[] = "(LOWER(title) LIKE {$src} OR LOWER(description) LIKE {$src})";
            }
			
			$where[] = 'DATE(v.created) <= CURDATE()';

            $where = (count($where) ? ' WHERE '. implode(' AND ', $where) : '');
        }

        return $where;
    }
	
	# Get Total Videos
    public function getTotalVideos() {
        return MijoDB::loadResult("SELECT COUNT(*) FROM #__mijovideos_videos".$this->_buildViewWhere());
    }
    
    # Get Video Product ID from Mijoshop
	public function getProductID() {
		return MijoDB::loadResult("SELECT product_id FROM #__mijovideos_videos WHERE id = {$this->video_id} ORDER BY id DESC LIMIT 1");
	}
	
	public function getVideoCategories() {
        return MijoDB::loadObjectList('SELECT c.title,c.id FROM #__mijovideos_video_categories vc LEFT JOIN #__mijovideos_categories c ON vc.category_id=c.id WHERE video_id='.$this->video_id);
    }

    public function getPlaylistVideos() {

        $result = MijoDB::loadObjectList("SELECT v.*
                            FROM #__mijovideos_videos v
                            LEFT JOIN #__mijovideos_playlist_videos pv ON (pv.video_id=v.id)"
            .$this->_buildViewWhere());

        return $result;
    }

    public function getTotalPlaylistVideos() {

        $result = MijoDB::loadResult("SELECT COUNT(*)
                                FROM #__mijovideos_videos v
                                LEFT JOIN #__mijovideos_playlist_videos pv ON (pv.video_id=v.id)
                                ".$this->_buildViewWhere()."
                                GROUP BY pv.playlist_id ");

        return $result;
    }

    public function getReasons() {
        $user = JFactory::getUser();

        $where[] = 'rs.published=1';
        $where[] = 'rs.access IN ('.implode(',', $user->getAuthorisedViewLevels()).')';

        if ($this->_mainframe->getLanguageFilter()) {
            $where[] = 'rs.language IN (' . $this->_db->Quote(JFactory::getLanguage()->getTag()) . ',' . $this->_db->Quote('*') . ')';
        }
        $where = (count($where) ? ' WHERE '. implode(' AND ', $where) : '');

        $result = MijoDB::loadObjectList("SELECT rs.*
                                FROM #__mijovideos_report_reasons rs
                                ".$where);

        return $result;
    }

    public function submitReport($post) {
        $date = JFactory::getDate();
        $user_id = JFactory::getUser()->get('id');
        $row = MijoVideos::getTable('MijovideosReports');

        $data = array();
        $data['channel_id'] = MijoVideos::get('channels')->getDefaultChannel()->id;
        $data['user_id'] = $user_id;
        $data['item_id'] = $post['item_id'];
        $data['item_type'] = $post['item_type'];
        $data['reason_id'] = $post['mijovideos_reasons'];
        $data['note'] = $post['mijovideos_report'];
        $data['created'] = $date->format('Y-m-d H:i:s');
        $data['modified'] = $date->format('Y-m-d H:i:s');
        if (!$row->bind($data)) {
            $this->setError($row->getError());
            return false;
        }

        if (!$row->store()) {
            $this->setError($row->getError());
            return false;
        }

        //MijoDB::query("INSERT INTO #__mijovideos_reports (user_id, item_id, item_type, reason_id, note, created, modified) VALUES ({$user_id}, {$post['item_id']}, '{$post['item_type']}', {$post['mijovideos_reasons']}, '{$post['mijovideos_report']}', NOW(), NOW())");
        return true;
    }

    public function getProcessing($video_id = null) {
        return MijoDB::loadResult('SELECT COUNT(*) FROM #__mijovideos_processes WHERE status = 3 AND video_id = ' . $video_id);
    }
}