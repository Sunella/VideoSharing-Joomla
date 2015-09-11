<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosViewChannels extends MijovideosView {
	
	public function display($tpl = null) {
        if ($this->_mainframe->isAdmin()) {
            $this->addToolbar();
        }
		
		$filter_order		= $this->_mainframe->getUserStateFromRequest($this->_option.'.channels.filter_order',	    'filter_order',			'c.title',	'cmd');
		$filter_order_Dir	= $this->_mainframe->getUserStateFromRequest($this->_option.'.channels.filter_order_Dir',	'filter_order_Dir',     'DESC', 	'word');
        $filter_published   = $this->_mainframe->getUserStateFromRequest($this->_option.'.channels.filter_published',	'filter_published',		'');
        $filter_access      = $this->_mainframe->getUserStateFromRequest($this->_option.'.channels.filter_access',		'filter_access',		'');
        $filter_language	= $this->_mainframe->getUserStateFromRequest($this->_option.'.channels.filter_language',	'filter_language',	    '',		    'string');
		$search				= $this->_mainframe->getUserStateFromRequest($this->_option.'.channels.search', 			'search', 				'', 		'string');
		$search				= JString::strtolower($search);
		
		$lists['search'] 	= $search;	
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] 	= $filter_order;

        $options = array();
        $options[] = JHtml::_('select.option', '', JText::_('JOPTION_SELECT_PUBLISHED'));
        $options[] = JHtml::_('select.option', 1, JText::_('COM_MIJOVIDEOS_PUBLISHED'));
        $options[] = JHtml::_('select.option', 0, JText::_('COM_MIJOVIDEOS_UNPUBLISHED'));
        $lists['filter_published'] = JHtml::_('select.genericlist', $options, 'filter_published', ' class="inputbox" onchange="submit();" ', 'value', 'text', $filter_published);
		
		$this->columnData = $this->get('Columns');
		
		if ($this->columnData != NULL){
			$this->cTitle		= $this->columnData[0];
			$this->cFields		= $this->columnData[1];
			$this->cChannels 	= $this->columnData[2];
		} else {
			$this->cTitle		= NULL;
			$this->cFields		= NULL;
			$this->cChannels 	= $this->get('Items');
		}

        $this->filter_access        = $filter_access;
        $this->filter_language      = $filter_language;
		$this->lists 		        = $lists;
		$this->levels               = MijoVideos::get('utility')->getAccessLevels();
		$this->pagination 	        = $this->get('Pagination');
        $this->acl                  = MijoVideos::get('acl');
			
		parent::display($tpl);				
	}

    public function displayModal($tpl = null){
        $this->display($tpl);
    }

    protected function addToolbar() {
        JToolBarHelper::title(JText::_('COM_MIJOVIDEOS_CPANEL_CHANNELS'), 'mijovideos');

        if ($this->acl->canCreate()) {
            JToolBarHelper::addNew();
        }

        if ($this->acl->canEdit()) {
            JToolBarHelper::editList();
        }

        if ($this->acl->canCreate() or $this->acl->canEdit()) {
            JToolBarHelper::divider();
        }

        if ($this->acl->canEditState()) {
            JToolBarHelper::publishList();
            JToolBarHelper::unpublishList();
            JToolBarHelper::divider();
        }

        JToolBarHelper::makeDefault('defaultChannel');

        if ($this->acl->canDelete()) {
            JToolBarHelper::deleteList(JText::_('COM_MIJOVIDEOS_DELETE_REGISTRANT_CONFIRM'));
        }

        if ($this->acl->canCreate() or $this->acl->canDelete()) {
            JToolBarHelper::divider();
        }

        $this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://mijosoft.com/support/docs/mijovideos/user-manual/channels?tmpl=component', 650, 500);
    }
}