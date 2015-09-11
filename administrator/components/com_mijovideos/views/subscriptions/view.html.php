<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosViewSubscriptions extends MijovideosView {
	
	public function display($tpl = null) {
        if ($this->_mainframe->isAdmin()) {
            $this->addToolbar();
        }
		
		$filter_order		= $this->_mainframe->getUserStateFromRequest($this->_option.'.subscriptions.filter_order',	    'filter_order',			's.user_id',	'cmd');
		$filter_order_Dir	= $this->_mainframe->getUserStateFromRequest($this->_option.'.subscriptions.filter_order_Dir',	'filter_order_Dir',     'DESC', 		'word');
        $filter_user 	    = $this->_mainframe->getUserStateFromRequest($this->_option.'.subscriptions.filter_user',		'filter_user',	    	0,		    	'int');
		$search				= $this->_mainframe->getUserStateFromRequest($this->_option.'.subscriptions.search', 			'search', 				'', 			'string');
		$search				= JString::strtolower($search);
		
		$lists['search'] 	= $search;	
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] 	= $filter_order;

        $users = MijoVideos::get('utility')->getUsers();
        $options = array();
        $options[] = JHtml::_('select.option', '', JText::_('JLIB_FORM_CHANGE_USER'));
        foreach($users as $user){
            $options[] = JHtml::_('select.option',  $user->id, $user->username);
        }
        $lists['filter_user'] = JHtml::_('select.genericlist', $options, 'filter_user', ' class="inputbox" style="width: 220px;" onchange="submit();" ', 'value', 'text', $filter_user);

		$this->items 	            = $this->get('Items');
		$this->lists 		        = $lists;
		$this->levels               = MijoVideos::get('utility')->getAccessLevels();
		$this->pagination 	        = $this->get('Pagination');
			
		parent::display($tpl);				
	}

    public function displayModal($tpl = null){
        $this->display($tpl);
    }

    protected function addToolbar() {
        JToolBarHelper::title(JText::_('COM_MIJOVIDEOS_CPANEL_SUBSCRIPTIONS'), 'mijovideos');

        if ($this->acl->canDelete()) {
            JToolBarHelper::deleteList(JText::_('COM_MIJOVIDEOS_DELETE_REGISTRANT_CONFIRM'));
            JToolBarHelper::divider();
        }

        $this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://mijosoft.com/support/docs/mijovideos/user-manual/subscriptions?tmpl=component', 650, 500);
    }
}