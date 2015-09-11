<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosViewCategories extends MijovideosView {

	public function display($tpl = null) {
        if ($this->_mainframe->isAdmin()) {
            $this->addToolbar();
        }

        $filter_order		= $this->_mainframe->getUserStateFromRequest($this->_option.'.categories.filter_order',		        'filter_order',		        'c.title',	    'cmd');
		$filter_order_Dir	= $this->_mainframe->getUserStateFromRequest($this->_option.'.categories.filter_order_Dir',	        'filter_order_Dir',	        'ASC',			'word');
		$filter_parent      = $this->_mainframe->getUserStateFromRequest($this->_option.'.categories.filter_parent',	        'filter_parent',	        '0');
		$filter_published   = $this->_mainframe->getUserStateFromRequest($this->_option.'.categories.filter_published',	        'filter_published',	        '');
        $filter_access      = $this->_mainframe->getUserStateFromRequest($this->_option.'.categories.filter_access',	        'filter_access',	        '');
        $filter_language	= $this->_mainframe->getUserStateFromRequest($this->_option.'.categories.filter_language',		    'filter_language',			'',				'string');
		$search				= $this->_mainframe->getUserStateFromRequest($this->_option.'.categories.search',			        'search',			        '',				'string');
		$search				= JString::strtolower($search);

		$lists['filter_parent'] = MijoVideos::get('utility')->buildCategoryDropdown($filter_parent, 'filter_parent');
		$lists['search'] = $search;		
		
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('JOPTION_SELECT_PUBLISHED'));
		$options[] = JHtml::_('select.option', 1, JText::_('COM_MIJOVIDEOS_PUBLISHED'));
		$options[] = JHtml::_('select.option', 0, JText::_('COM_MIJOVIDEOS_UNPUBLISHED'));
		$lists['filter_published'] = JHtml::_('select.genericlist', $options, 'filter_published', ' class="inputbox" onchange="submit();" ', 'value', 'text', $filter_published);

		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

        JHtml::_('behavior.tooltip');

		$this->lists                = $lists;
		$this->items                = $this->get('Items');
		$this->pagination           = $this->get('Pagination');
		$this->levels               = MijoVideos::get('utility')->getAccessLevels();
        $this->langs                = MijoVideos::get('utility')->getLanguages();
		$this->filter_access        = $filter_access;
		$this->filter_language      = $filter_language;

		parent::display($tpl);				
	}

    protected function addToolbar() {
        JToolBarHelper::title(JText::_('COM_MIJOVIDEOS_CPANEL_CATEGORIES'), 'mijovideos');

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
            JToolBarHelper::deleteList(JText::_('COM_MIJOVIDEOS_DELETE_CATEGORY_CONFIRM'));
        }

        if ($this->acl->canCreate() or $this->acl->canDelete()) {
            JToolBarHelper::divider();
        }

        $this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://mijosoft.com/support/docs/mijovideos/user-manual/categories?tmpl=component', 650, 500);
    }
}