<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die;

class MijovideosControllerFields extends MijoVideosController {

	public function __construct($config = array()) {
		parent::__construct('fields');
	}

	public function save() {
		JRequest::checkToken() or jexit('Invalid Token');
		$post = JRequest::get('post', JREQUEST_ALLOWRAW);
		$cid = $post['cid'];
		$post['id'] = (int) $cid[0];

		$ret = $this->_model->store($post);
		if ($ret) {
			$msg = JText::_('COM_MIJOVIDEOS_COMMON_RECORD_SAVED');
		}
        else {
			$msg = JText::_('COM_MIJOVIDEOS_COMMON_RECORD_SAVED_NOT');
		}

		parent::route($msg, $post);

        return $msg;
	}
}