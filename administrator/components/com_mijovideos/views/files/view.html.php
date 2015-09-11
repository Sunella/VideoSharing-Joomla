<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosViewFiles extends MijovideosView {
	
	public function display($tpl = null) {
        if ($this->_mainframe->isAdmin()) {
            $this->addToolbar();
        }
		
		$filter_order		= $this->_mainframe->getUserStateFromRequest($this->_option.'.files.filter_order',	    'filter_order',		    'f.id',	    'cmd');
		$filter_order_Dir	= $this->_mainframe->getUserStateFromRequest($this->_option.'.files.filter_order_Dir',	'filter_order_Dir',     'DESC', 	'word');
        $filter_published   = $this->_mainframe->getUserStateFromRequest($this->_option.'.files.filter_published',	'filter_published',	    '');
        //$filter_process     = $this->_mainframe->getUserStateFromRequest($this->_option.'.files.filter_process',	'filter_process',	    '');
		$search				= $this->_mainframe->getUserStateFromRequest($this->_option.'.files.search',			'search', 				'', 		'string');
		$search				= JString::strtolower($search);
		
		$lists['search'] 	= $search;	
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] 	= $filter_order;
        
        # Publish Options
        $options = array();
        $options[] = JHtml::_('select.option', '', JText::_('JOPTION_SELECT_PUBLISHED'));
        $options[] = JHtml::_('select.option', 1, JText::_('COM_MIJOVIDEOS_PUBLISHED'));
        $options[] = JHtml::_('select.option', 0, JText::_('COM_MIJOVIDEOS_UNPUBLISHED'));
        $lists['filter_published'] = JHtml::_('select.genericlist', $options, 'filter_published', ' class="inputbox" onchange="submit();" ', 'value', 'text', $filter_published);

        JHtml::_('behavior.tooltip');

        $this->items 	            = $this->get('Items');
		$this->lists 		        = $lists;
		$this->levels               = MijoVideos::get('utility')->getAccessLevels();
		$this->pagination 	        = $this->get('Pagination');
        $this->acl                  = MijoVideos::get('acl');
			
		parent::display($tpl);				
	}

    protected function addToolbar() {
        $acl = MijoVideos::get('acl');

        JToolBarHelper::title(JText::_('COM_MIJOVIDEOS_CPANEL_FILES'), 'mijovideos');

        if ($acl->canDelete()) {
            JToolBarHelper::deleteList(JText::_('COM_MIJOVIDEOS_DELETE_REGISTRANT_CONFIRM'));
            JToolBarHelper::divider();
        }

        $this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://mijosoft.com/support/docs/mijovideos/user-manual/files?tmpl=component', 650, 500);
    }
}