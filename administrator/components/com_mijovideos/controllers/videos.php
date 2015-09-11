<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;


class MijovideosControllerVideos extends MijoVideosController {

	public function __construct($config = array())	{
		parent::__construct('videos');
	}

    public function edit() {
        JRequest::setVar('hidemainmenu', 1);

        $view = $this->getView('Videos', 'edit');
        $view->setModel($this->getModel('videos'), true);
        $view->setModel($this->getModel('processes'));
        $view->display('edit');
    }

	public function save() {
		$post       = JRequest::get('post', JREQUEST_ALLOWRAW);
        $cid = JRequest::getVar('cid', array(), 'post');

        if(!empty($cid[0])){
            $post['id'] = $cid[0];
        }

        $thumb_size = MijoVideos::get('utility')->getThumbSize($this->config->get('thumb_size'));

        # Thumb Image
        if (isset($_FILES['thumb_image']['name'])) {
            $fileExt = strtolower(JFile::getExt($_FILES['thumb_image']['name']));
            $supportedTypes = array('jpg', 'png', 'gif');
            if (in_array($fileExt, $supportedTypes)) {
                $fileName = hash('haval256,5', JString::strtolower($_FILES['thumb_image']['name'])) . '.' . $fileExt;
                $imagePath = JPATH_ROOT.'/media/com_mijovideos/images/'.$this->_context.'/'.$post['id'].'/orig/'.$fileName;
                $thumbPath = JPATH_ROOT.'/media/com_mijovideos/images/'.$this->_context.'/'.$post['id'].'/'.$thumb_size.'/'.$fileName;
                JFile::upload($_FILES['thumb_image']['tmp_name'], $imagePath);
                JFolder::create(JPATH_ROOT.'/media/com_mijovideos/images/'.$this->_context.'/'.$post['id'].'/'.$thumb_size.'/');
                MijoVideos::get('utility')->resizeImage($imagePath, $thumbPath, $thumb_size, $thumb_size, 95);
                $post['thumb'] = $fileName;
            }
        }

        $table = ucfirst($this->_component).ucfirst($this->_context);
        $row = MijoVideos::getTable($table);
        $row->load($post['id']);

        if (isset($post['del_thumb']) and $row->thumb) {
            if (JFile::exists(JPATH_ROOT.'/media/com_mijovideos/images/'.$this->_context.'/'.$post['id'].'/orig/'.$row->thumb)) {
                JFile::delete(JPATH_ROOT.'/media/com_mijovideos/images/'.$this->_context.'/'.$post['id'].'/orig/'.$row->thumb);
                //JFile::delete(JPATH_ROOT.'/media/com_mijovideos/images/'.$this->_context.'/'.$post['id'].'/orig');
            }

            if (JFile::exists(JPATH_ROOT.'/media/com_mijovideos/images/'.$this->_context.'/'.$post['id'].'/'.$thumb_size.'/'.$row->thumb)) {
                JFile::delete(JPATH_ROOT.'/media/com_mijovideos/images/'.$this->_context.'/'.$post['id'].'/'.$thumb_size.'/'.$row->thumb);
            }

            $post['thumb'] = '';
        }


        if (!$post['channel_id']) {
            $post['channel_id'] = MijoVideos::get('channels')->getDefaultChannel()->id;
        }

        $ret = $this->_model->store($post);

        if($ret){
            $msg = JText::_('COM_MIJOVIDEOS_VIDEO_SAVED');
        }
        else {
            $msg = JText::_('COM_MIJOVIDEOS_VIDEO_SAVE_ERROR');
        }

		parent::route($msg, $post);

        return $msg;
	}

    public function copy() {
        # Check token
        JRequest::checkToken() or jexit('Invalid Token');

        $cid = JRequest::getVar('cid', array(), 'post');
        
        foreach ($cid as $id) {
        	$this->_model->copy($id);
        }
        
        $msg = JText::_('COM_MIJOVIDEOS_RECORD_COPIED');

        $this->setRedirect('index.php?option='.$this->_option.'&view='.$this->_context, $msg);

        return $msg;
    }
    
	public function delete() {
    	# Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		$cid = JRequest::getVar('cid', array(), 'post');
        JArrayHelper::toInteger($cid);

		# Action
        foreach ($cid as $id) {
            if (JFolder::exists(JPATH_ROOT. '/media/com_mijovideos/videos/'.$id)) {
                JFolder::delete(JPATH_ROOT. '/media/com_mijovideos/videos/'.$id);
            }
            if (JFolder::exists(JPATH_ROOT. '/media/com_mijovideos/images/videos/'.$id)) {
                JFolder::delete(JPATH_ROOT. '/media/com_mijovideos/images/videos/'.$id);

            }
        }

        $del_row = $this->deleteRecord($this->_table, $this->_model);
        $del_rel_row = $this->_model->delete($cid);

		if (!$del_row and !$del_rel_row) {
            $msg = JText::_('COM_MIJOVIDEOS_COMMON_RECORDS_DELETED_NOT');
		} else {
			$msg = JText::_('COM_MIJOVIDEOS_COMMON_RECORDS_DELETED');
		}

		$this->setRedirect('index.php?option='.$this->_option.'&view='.$this->_context, $msg);

        return $msg;
    }

    public function autoComplete(){
        $query = JRequest::getVar('query');
        $videos = json_encode($this->_model->autoComplete($query));
        echo $videos;
        exit();
    }

    public function createAutoFieldHtml(){
        $fieldid = JRequest::getInt('fieldid');
        $html = MijoVideos::get('fields')->createAutoFieldHtml($fieldid);
        echo $html;
        exit();
    }

    # Feature
    public function feature() {
        # Check token
        JRequest::checkToken() or jexit('Invalid Token');

        # Action
        self::updateField($this->_table, 'featured', 1, $this->_model);

        # Return
        self::route();
    }

    # Unfeature
    public function unfeature() {
        # Check token
        JRequest::checkToken() or jexit('Invalid Token');

        # Action
        self::updateField($this->_table, 'featured', 0, $this->_model);

        # Return
        self::route();
    }
}