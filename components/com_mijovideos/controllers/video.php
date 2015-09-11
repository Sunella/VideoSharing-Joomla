<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosControllerVideo extends MijoVideosController {
	
	public function __construct($config = array()) {
		parent::__construct('video');

		$id = MijoVideos::getInput()->getInt('id');
		$video_id = MijoVideos::getInput()->getInt('video_id');

        if (empty($video_id) and !empty($id)) {
            $this->_mainframe->redirect(JRoute::_('index.php?option=com_mijovideos&view=video&video_id='.$id));
        }
	}

    public function display($cachable = false, $urlparams = false) {
        $layout = JRequest::getCmd('layout');

        $function = 'display'.ucfirst($layout);

        $view = $this->getView(ucfirst($this->_context), 'html');
        $view->setModel($this->getModel('video'), true);
        $view->setModel($this->getModel('playlists'));

        if (!empty($layout)) {
            $view->setLayout($layout);
        }

        $view->$function();
    }

    public function submitReport() {
        $post       = JRequest::get('post', JREQUEST_ALLOWRAW);
        $user       = JFactory::getUser();
        $json = array();
        if($user->id != 0) {
            if($this->_model->submitReport($post)) {
                $json['success'] = JText::_('COM_MIJOVIDEOS_SUCCESS_REPORT');
            } else {
                $json['error'] = true;
            }
        } else {
            $json['redirect'] = MijoVideos::get('utility')->redirectWithReturn();
        }
        echo json_encode($json);
        exit();

    }
}