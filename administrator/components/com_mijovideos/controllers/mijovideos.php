<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die( 'Restricted access' );

class MijovideosControllerMijovideos extends MijovideosController {

	# Main constructer
    public function __construct() {
        parent::__construct('mijovideos');
    }
	
	public function savePersonalID() {
		# Check token
		JRequest::checkToken() or jexit('Invalid Token');

		$msg = $this->_model->savePersonalID();
        
        $this->setRedirect('index.php?option=com_mijovideos', $msg);
    }
	
	public function jusersync() {
		# Check token
		JRequest::checkToken() or jexit('Invalid Token');

		$msg = $this->_model->jusersync();

        $this->setRedirect('index.php?option=com_mijovideos', JText::sprintf('COM_MIJOVIDEOS_ACCOUNT_SYNC_DONE'));
    }
}