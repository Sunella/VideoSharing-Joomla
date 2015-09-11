<?php
/*
* @package		MijoVideos
* @copyright	2009-2014 Mijosoft LLC, mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
# No Permission
defined('_JEXEC') or die ('Restricted access');

# Controller Class
class MijovideosControllerUpgrade extends MijovideosController {

	# Main constructer
	public function __construct() {
		parent::__construct('upgrade');
	}
	
	# Upgrade
    public function upgrade() {
		# Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		# Upgrade
		if ($this->_model->upgrade()) {
            $msg = JText::_('COM_MIJOVIDEOS_UPGRADE_SUCCESS');
        }
        else {
            $msg = '';
        }
		
		# Return
		$this->setRedirect('index.php?option=com_mijovideos&view=upgrade', $msg);
    }
}