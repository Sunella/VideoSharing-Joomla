<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosViewProcesses extends MijovideosView {
	
	public function display($tpl = null) {
        if ($this->_mainframe->isAdmin()) {
            $this->addToolbar();
        }
		
		$filter_order		= $this->_mainframe->getUserStateFromRequest($this->_option.'.playlists.filter_order',	    'filter_order',		    'pt.title',	'cmd');
		$filter_order_Dir	= $this->_mainframe->getUserStateFromRequest($this->_option.'.playlists.filter_order_Dir',	'filter_order_Dir',     'DESC', 	'word');
        $filter_status   	= $this->_mainframe->getUserStateFromRequest($this->_option.'.categories.filter_status',	'filter_status',		'');
		$search				= $this->_mainframe->getUserStateFromRequest($this->_option.'.playlists.search',			'search', 				'', 		'string');
		$search				= JString::strtolower($search);
		
		$lists['search'] 	= $search;	
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] 	= $filter_order;						

        $options = array();
        $options[] = JHtml::_('select.option', '', JText::_('JOPTION_SELECT_PUBLISHED'));
        $options[] = JHtml::_('select.option', 1, JText::_('COM_MIJOVIDEOS_SUCCESSFUL'));
        $options[] = JHtml::_('select.option', 2, JText::_('COM_MIJOVIDEOS_FAILED'));
        $options[] = JHtml::_('select.option', 3, JText::_('COM_MIJOVIDEOS_QUEUED'));
        $lists['filter_status'] = JHtml::_('select.genericlist', $options, 'filter_status', ' class="inputbox" onchange="submit();" ', 'value', 'text', $filter_status);
		
        $this->items = $this->get('Items');

        JHtml::_('behavior.tooltip');

		$this->lists 		        = $lists;
		$this->levels               = MijoVideos::get('utility')->getAccessLevels();
		$this->pagination 	        = $this->get('Pagination');
        $this->acl                  = MijoVideos::get('acl');
			
		parent::display($tpl);				
	}

    protected function addToolbar() {
        $acl = MijoVideos::get('acl');

        JToolBarHelper::title(JText::_('COM_MIJOVIDEOS_CPANEL_PROCESSES'), 'mijovideos');

        if ($acl->canEdit()) {
            $process_icon_1 = '';
            $process_icon_2 = '';
            if (MijoVideos::is30()){
                $process_icon_1 = 'play-2';
                $process_icon_2 = 'play';
            }

            JToolBarHelper::custom('process', $process_icon_1, '', 'Process', true);
            JToolBarHelper::custom('processAll', $process_icon_2, '', 'Process All', true);
        }

        if ($acl->canCreate() or $acl->canEdit()) {
            JToolBarHelper::divider();
        }

        if ($acl->canDelete()) {
            JToolBarHelper::deleteList(JText::_('COM_MIJOVIDEOS_DELETE_PROCESS_CONFIRM'));
        }

        if ($acl->canCreate() or $acl->canDelete()) {
            JToolBarHelper::divider();
        }

        $this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://mijosoft.com/support/docs/mijovideos/user-manual/processes?tmpl=component', 650, 500);
    }
}