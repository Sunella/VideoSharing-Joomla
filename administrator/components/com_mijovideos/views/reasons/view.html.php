<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;


class MijovideosViewReasons extends MijovideosView {
	
	function display($tpl = null) {
        if ($this->_mainframe->isAdmin()) {
            $this->addToolbar();
        }
		
		$filter_order		= $this->_mainframe->getUserStateFromRequest($this->_option.'.videos.video_filter_order',	'filter_order',		    'rs.title',	'cmd');
		$filter_order_Dir	= $this->_mainframe->getUserStateFromRequest($this->_option.'.videos.filter_order_Dir',	    'filter_order_Dir',	    '',		    'word');
        $filter_published	= $this->_mainframe->getUserStateFromRequest($this->_option.'.videos.filter_published',	    'filter_published',		'');
        $filter_access      = $this->_mainframe->getUserStateFromRequest($this->_option.'.videos.filter_access',	    'filter_access',	    '');
        $filter_language	= $this->_mainframe->getUserStateFromRequest($this->_option.'.videos.filter_language',		'filter_language',	    '',		    'string');
		$search				= $this->_mainframe->getUserStateFromRequest($this->_option.'.videos.search',				'search',			    '',		    'string');
		$search				= JString::strtolower($search);

        $lists['search']		 	= $search ;
		$lists['order_Dir']			= $filter_order_Dir;
		$lists['order'] 		 	= $filter_order;
		
		$items		= $this->get('Items');

		$pagination = $this->get('Pagination');

		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('COM_MIJOVIDEOS_SELECT_STATUS'));
		$options[] = JHtml::_('select.option',  1, JText::_('COM_MIJOVIDEOS_PUBLISHED'));
		$options[] = JHtml::_('select.option',  0, JText::_('COM_MIJOVIDEOS_UNPUBLISHED'));
		$lists['filter_published'] = JHtml::_('select.genericlist', $options, 'filter_published', ' class="inputbox" style="width: 140px;" onchange="submit();" ', 'value', 'text', $filter_published);

        JHtml::_('behavior.tooltip');
		
		$this->lists 			= $lists;
		$this->items 			= $items;
		$this->pagination 		= $pagination;
        $this->levels           = MijoVideos::get('utility')->getAccessLevels();
        $this->langs            = MijoVideos::get('utility')->getLanguages();
		$this->filter_language 	= $filter_language;
		$this->filter_access 	= $filter_access;

		parent::display($tpl);				
	}

    protected function addToolbar() {
        JToolBarHelper::title(JText::_('COM_MIJOVIDEOS_CPANEL_REASONS'), 'mijovideos');

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

        if ($this->acl->canCreate()) {
            JToolBarHelper::custom('copy', 'copy.png', 'copy_f2.png', 'Copy', true);
        }

        if ($this->acl->canDelete()) {
            JToolBarHelper::deleteList(JText::_('COM_MIJOVIDEOS_DELETE_REASONS_CONFIRM'));
        }

        if ($this->acl->canCreate() or $this->acl->canDelete()) {
            JToolBarHelper::divider();
        }

        $this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://mijosoft.com/support/docs/mijovideos/user-manual/reasons?tmpl=component', 650, 500);
    }
}