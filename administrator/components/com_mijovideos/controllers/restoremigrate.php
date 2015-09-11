<?php
/*
* @package		MijoVideos
* @copyright	2009-2014 Mijosoft LLC, mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
# No Permission
defined('_JEXEC') or die ('Restricted access');

class MijovideosControllerRestoreMigrate extends MijovideosController {

    public function __construct() {
        parent::__construct('restoremigrate');
    }

    public function backup() {
		JRequest::checkToken() or jexit('Invalid Token');

		if(!$this->_model->backup()){
			JError::raiseWarning(500, JText::_('COM_MIJOVIDEOS_RESTOREMIGRATE_MSG_BACKUP_NO'));
		}
    }

    public function restore() {
		JRequest::checkToken() or jexit('Invalid Token');

		if(!$this->_model->restore()){
			$msg = JText::_('COM_MIJOVIDEOS_RESTOREMIGRATE_MSG_RESTORE_NO');
		} else {
			$msg = JText::_('COM_MIJOVIDEOS_RESTOREMIGRATE_MSG_RESTORE_OK');
		}

		parent::route($msg);
    }

    public function migrate() {
        JRequest::checkToken() or jexit('Invalid Token');

        if(!$this->_model->migrate()){
            $msg = JText::_('COM_MIJOVIDEOS_RESTOREMIGRATE_MSG_RESTORE_NO');
        } else {
            $msg = JText::_('COM_MIJOVIDEOS_RESTOREMIGRATE_MSG_RESTORE_OK');
        }

        parent::route($msg);
    }
}