<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die;

class MijovideosControllerFiles extends MijoVideosController {

	public function __construct($config = array()) {
		parent::__construct('files');
	}

    public function delete() {
        JRequest::checkToken() or jexit('Invalid Token');

        $cid = JRequest::getVar('cid', array(), 'post', 'array');
        JArrayHelper::toInteger($cid);

        if (!$this->_model->delete($cid)) {
            $msg = JText::_('COM_MIJOVIDEOS_COMMON_RECORDS_DELETED_NOT');
        } else {
            $msg = JText::_('COM_MIJOVIDEOS_COMMON_RECORDS_DELETED');
        }

        parent::route($msg);

        return $msg;
    }
}