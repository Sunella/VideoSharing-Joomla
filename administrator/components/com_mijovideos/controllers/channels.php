<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosControllerChannels extends MijoVideosController {
	
	public function __construct($config = array()) {
		parent::__construct('channels');
	}

    # Default
    public function defaultChannel() {
        # Check token
        JRequest::checkToken() or jexit('Invalid Token');

        # Action
        MijoVideos::get('channels')->updateDefaultChannel(1);

        # Return
        parent::route();
    }

    # Not Default
    public function notDefaultChannel() {
        # Check token
        JRequest::checkToken() or jexit('Invalid Token');

        # Action
        MijoVideos::get('channels')->updateDefaultChannel(0);

        # Return
        parent::route();
    }

    # Publish
    public function publish() {
        # Check token
        JRequest::checkToken() or jexit('Invalid Token');

        # Action
        self::updateField($this->_table, 'published', 1, $this->_model);

        $cid = JRequest::getVar('cid', array(), 'post');
        JArrayHelper::toInteger($cid);

        foreach ($cid as $id) {
            $video_ids = MijoVideos::get('channels')->getVideos($id);
            if (!empty($video_ids)) {
                $this->_model->publish_rel($video_ids);
            }
        }

        $this->_model->publish($cid);

        # Return
        self::route();
    }

    # Unpublish
    public function unpublish() {
        # Check token
        JRequest::checkToken() or jexit('Invalid Token');

        # Action
        self::updateField($this->_table, 'published', 0, $this->_model);

        $cid = JRequest::getVar('cid', array(), 'post');
        JArrayHelper::toInteger($cid);

        foreach ($cid as $id) {
            $video_ids = MijoVideos::get('channels')->getVideos($id);
            if (!empty($video_ids)) {
                $this->_model->unpublish_rel($video_ids);
            }
        }

        $this->_model->unpublish($cid);

        # Return
        self::route();
    }

    public function delete() {
        # Check token
        JRequest::checkToken() or jexit('Invalid Token');

        $cid = JRequest::getVar('cid', array(), 'post');
        JArrayHelper::toInteger($cid);

        foreach ($cid as $id) {
            $video_ids = MijoVideos::get('channels')->getVideos($id);

            # Action
            if (!empty($video_ids)) {
                foreach ($video_ids as $video_id) {
                    if (JFolder::exists(JPATH_ROOT. '/media/com_mijovideos/videos/'.$video_id)) {
                        JFolder::delete(JPATH_ROOT. '/media/com_mijovideos/videos/'.$video_id);
                    }
                    if (JFolder::exists(JPATH_ROOT. '/media/com_mijovideos/images/videos/'.$video_id)) {
                        JFolder::delete(JPATH_ROOT. '/media/com_mijovideos/images/videos/'.$video_id);
                    }
                }

                if (JFolder::exists(JPATH_ROOT. '/media/com_mijovideos/images/channels/'.$id)) {
                    JFolder::delete(JPATH_ROOT. '/media/com_mijovideos/images/channels/'.$id);
                }

                $del_rel_video_row = $this->_model->delete_rel($video_ids);

            } else {
                $del_rel_video_row = true;
            }
        }

        $del_row = $this->deleteRecord($this->_table, $this->_model);
        $del_rel_row = $this->_model->delete($cid);

        if (!$del_rel_row and !$del_row and !$del_rel_video_row) {
            $msg = JText::_('COM_MIJOVIDEOS_COMMON_RECORDS_DELETED_NOT');
        } else {
            $msg = JText::_('COM_MIJOVIDEOS_COMMON_RECORDS_DELETED');
        }

        # Return
        $this->setRedirect('index.php?option='.$this->_option.'&view='.$this->_context, $msg);

        return $msg;
    }
}