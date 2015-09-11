<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted Access');

# View Class
class MijovideosViewUpgrade extends MijovideosView {
	
	public function display($tpl = null) {
        if ($this->_mainframe->isAdmin()) {
            JToolBarHelper::title(JText::_('COM_MIJOVIDEOS_CPANEL_UPGRADE'), 'mijovideos');
            $this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://mijosoft.com/support/docs/mijovideos/user-manual/upgrade?tmpl=component', 650, 500);
        }
		parent::display($tpl);
	}
}
