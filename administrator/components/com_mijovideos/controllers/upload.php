<?php
/*
* @package		MijoVideos
* @copyright	2009-2014 Mijosoft LLC, mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
# No Permission
defined('_JEXEC') or die ('Restricted access');

# Controller Class
class MijovideosControllerUpload extends MijovideosController {

    # Main constructer
    public function __construct() {
        parent::__construct('upload');
    }

    public function upload() {
        $upload = MijoVideos::get('upload');

        $dashboard = '';
        if (MijoVideos::isDashboard()) {
            $dashboard = '&dashboard=1';
        }

        // Add embed code
        if (!$upload->process()) {
            if (JRequest::getWord('format') != 'raw') {
                JError::raiseWarning(500, $upload->getError());
                $this->setRedirect('index.php?option=com_mijovideos&view=upload'.$dashboard);
            }
            else {
                $result = array(
                    'status' => '0',
                    'error' => $upload->getError(),
                    'code' => 0
                );
            }
        }
        else {
            if (JRequest::getWord('format') != 'raw') {
                $this->_mainframe->enqueueMessage(JText::sprintf('COM_MIJOVIDEOS_SUCCESSFULLY_UPLOADED_X', $upload->_title));
                $this->setRedirect('index.php?option=com_mijovideos&view=videos&task=edit&cid[]=' . $upload->_id.$dashboard);
            }
            else {
                $result = array(
                    'success' => 1,
                    'id' => $upload->_id,
                    'href' => MijoVideos::get('utility')->route('index.php?option=com_mijovideos&view=videos&task=edit&cid[]=' . $upload->_id.$dashboard),
                    'location' => $upload->_location
                );
            }
        }

        echo json_encode($result);
    }

    public function uberUpload() {
        $upload = MijoVideos::get('upload');

        $dashboard = '';
        if (MijoVideos::isDashboard()) {
            $dashboard = '&dashboard=1';
        }

        // Add embed code
        if (!$upload->uber()) {
            JError::raiseWarning(500, $upload->getError());
            $this->setRedirect('index.php?option=com_mijovideos&view=videos'.$dashboard);
        }
        else {
            $this->_mainframe->enqueueMessage(JText::sprintf('COM_MIJOVIDEOS_SUCCESSFULLY_UPLOADED_X', $upload->_title));
            $this->setRedirect('index.php?option=com_mijovideos&view=videos&task=edit&cid[]=' . $upload->_id.$dashboard);
        }

        return $upload;
    }

    public function link_upload() {
        header('Content-type: text/javascript');
        MijoVideos::get('uber.ubr_link_upload');
        return;
    }

    public function set_progress() {
        header('Content-type: text/javascript');
        MijoVideos::get('uber.ubr_set_progress');
        return;
    }

    public function get_progress() {
        header('Content-type: text/javascript');
        MijoVideos::get('uber.ubr_get_progress');
        return;
    }

    public function convertToHtml5() {
        $config = MijoVideos::getConfig();

	    if (!$config->get('auto_process_video')) {
		    MijoVideos::get('videos')->convertToHtml5();
	    }
	    else {
		    $cli = JPATH_ROOT . '/cli/mijovideoscli.php';
		    $video_id = JRequest::getInt('video_id');
		    $location =  JRequest::getString('location');
		    if (substr(PHP_OS, 0, 3) != "WIN") {
			    // @TODO Log if throw an error
			    @exec("env -i " . $this->config->get('php_path', '/usr/bin/php') . " $cli convertToHtml5 " . $video_id . " " . $location . " > /dev/null 2>&1  &", $output, $error);
		    }
		    else {
			    @exec('where php.exe', $php_path);
			    // @TODO Log if throw an error
			    @exec($config->get('php_path', $php_path)." $cli convertToHtml5 " . $video_id . " " . $location . " NUL", $output , $error);
		    }

		    MijoVideos::log('CLI : ');
		    MijoVideos::log($output);
		    MijoVideos::log($error);

		    if (!$error) {
			    $json = array(
				    'success' => 1,
				    'href' => MijoVideos::get('utility')->route('index.php?option=com_mijovideos&view=videos&task=edit&cid[]=' . $video_id)
			    );
			    echo json_encode($json);
			    return true;
		    }
		    else {
			    $json['error'] = JText::_('COM_MIJOVIDEOS_ERROR_FRAMES_PROCESSING');
			    echo json_encode($json);
			    return false;
		    }
	    }
    }

    public function remoteLink() {
        $upload = MijoVideos::get('upload');

        $dashboard = '';
        if (MijoVideos::isDashboard()) {
            $dashboard = '&dashboard=1';
        }

        $upload->remoteLink();
        if (!count($upload->getErrors())) {
            if ($upload->_count > 1) {
                $this->_mainframe->enqueueMessage(JText::sprintf('COM_MIJOVIDEOS_SUCCESSFULLY_UPLOADED'));
                $this->setRedirect('index.php?option=com_mijovideos&view=videos'.$dashboard);
            }
            else {
                $this->_mainframe->enqueueMessage(JText::sprintf('COM_MIJOVIDEOS_SUCCESSFULLY_UPLOADED_X', $upload->_title));
                $redirect_url = MijoVideos::get('utility')->route('index.php?option=com_mijovideos&view=videos&task=edit&cid[]=' . $upload->_id.$dashboard);
                $this->setRedirect($redirect_url);
            }
        } else {
            $this->_mainframe->enqueueMessage($upload->getError(0));
            $redirect_url = MijoVideos::get('utility')->route('index.php?option=com_mijovideos&view=upload'.$dashboard);
            $this->setRedirect($redirect_url);
        }

        return $upload;
    }
}