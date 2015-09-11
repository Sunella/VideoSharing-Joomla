<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosControllerProcesses extends MijoVideosController {
	
	public function __construct($config = array()) {
		parent::__construct('processes');
	}
	
	public function process() {
		# Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		$cid = JRequest::getVar('cid', array(), 'post');
		$ret = false;

		foreach ($cid as $id) {
			$exists = $this->_model->getSuccessful($id);
			if ($exists) {
				$ids[] = $id;
				continue;
			}
            $ret = MijoVideos::get('processes')->run($id);
		}

		if (isset($ids) and count($ids) > 0) {
			JError::raiseNotice(100, JText::sprintf('COM_MIJOVIDEOS_ALREADY_PROCESSED', implode(',', $ids)));
		}

        if ($ret) {
            $this->_mainframe->enqueueMessage(JText::_('COM_MIJOVIDEOS_RECORD_PROCESSED'));
        } else {
            JError::raiseError(100, JText::_('COM_MIJOVIDEOS_PROCESS_FAILED'));
        }

        $this->setRedirect('index.php?option='.$this->_option.'&view='.$this->_context);
		
	}
	
	public function processAll() {
		$this->process();
	}
}