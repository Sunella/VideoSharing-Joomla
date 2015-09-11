<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosControllerPlaylists extends MijoVideosController {
	
	public function __construct($config = array()) {
		parent::__construct('playlists');
	}

    public function save() {
        $date = JFactory::getDate();
        $user_id = JFactory::getUser()->id;
        JRequest::setVar('cid', 0, 'post');
        JRequest::setVar('created', $date->format('Y-m-d H:i:s'), 'post');
        $json = array();
        if ($user_id) {
            $result = parent::save();
            $insertid = MijoDB::insertid();
            if (!$result) {
                $json['error'] = JText::_($result);
            } else {
                $json['success'] = 1;
                $json['id'] = $insertid;
            }
        } else {
            $json['redirect'] = MijoVideos::get('utility')->redirectWithReturn();
        }
        echo json_encode($json);
        exit();
    }

    public function addVideoToPlaylist() {
        $user_id = JFactory::getUser()->id;
        $playlist_id = JRequest::getInt('playlist_id');
        $video_id = JRequest::getInt('video_id');
        $ordering = JRequest::getWord('ordering');
        if ($user_id) {
            $result = MijoVideos::get('utility')->checkVideoInPlaylists($playlist_id, $video_id);
            if (!empty($result)) {
                $json['error'] = JText::_('COM_MIJOVIDEOS_ALREADY_ADDED');
                echo json_encode($json);
                exit();
            }
            $result = $this->_model->addVideoToPlaylist($playlist_id, $video_id, $ordering);
            if (!$result) {
                $json['error'] = 1;
            } else {
                $json['success'] = JText::_('COM_MIJOVIDEOS_ADDED_TO_PLAYLIST');
            }
        } else {
            $json['redirect'] = MijoVideos::get('utility')->redirectWithReturn();
        }
        echo json_encode($json);
        exit();
    }

    public function removeVideoFromPlaylist() {
        $user_id = JFactory::getUser()->id;
        $playlist_id = JRequest::getInt('playlist_id');
        $video_id = JRequest::getInt('video_id');
        if ($user_id) {
            $result = MijoVideos::get('utility')->checkVideoInPlaylists($playlist_id, $video_id);
            if (empty($result)) {
                $json['error'] = JText::_('COM_MIJOVIDEOS_ALREADY_REMOVED');
                echo json_encode($json);
                exit();
            }
            $result = $this->_model->removeVideoToPlaylist($playlist_id, $video_id);
            if (!$result) {
                $json['error'] = 1;
            } else {
                $json['success'] = JText::_('COM_MIJOVIDEOS_REMOVED_FROM_PLAYLIST');
            }
        } else {
            $json['redirect'] = MijoVideos::get('utility')->redirectWithReturn();
        }
        echo json_encode($json);
        exit();
    }
}