<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted Access');

class MijovideosViewRestoreMigrate extends MijovideosView {

	public function display($tpl = null) {
        if (!MijoVideos::get('acl')->canAdmin()) {
            JFactory::getApplication()->redirect('index.php?option=com_mijovideos', JText::_('JERROR_ALERTNOAUTHOR'));
        }

        if ($this->_mainframe->isAdmin()) {
            JToolBarHelper::title(JText::_('COM_MIJOVIDEOS_CPANEL_RESTORE'), 'mijovideos');
            $this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://mijosoft.com/support/docs/mijovideos/user-manual/restore-migrate?tmpl=component', 650, 500);
        }

		parent::display($tpl);
	}
}