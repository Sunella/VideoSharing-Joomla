<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;


class MijovideosControllerReasons extends MijoVideosController {

	public function __construct($config = array())	{
		parent::__construct('reasons');
	}

	public function save() {
		$post       = JRequest::get('post', JREQUEST_ALLOWRAW);
        $cid = JRequest::getVar('cid', array(), 'post');

        if(!empty($cid[0])){
            $post['id'] = $cid[0];
        }

        $table = ucfirst($this->_component).ucfirst($this->_context);
        $row = MijoVideos::getTable($table);
        $row->load($post['id']);

        $ret = $this->_model->store($post);

        JRequest::setVar('cid', $post['id'], 'post');

        if (empty($post['association'])) {
            parent::updateField('reasons', 'association', $post['id'], 'reasons');
        }

        if($ret){
            $msg = JText::_('COM_MIJOVIDEOS_COMMON_RECORD_SAVED');
        }
        else {
            $msg = JText::_('COM_MIJOVIDEOS_COMMON_RECORD_SAVED_NOT');
        }

		parent::route($msg, $post);

        return $msg;
	}
}