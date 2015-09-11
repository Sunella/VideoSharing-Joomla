<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosViewFields extends MijovideosView {

	public function display($tpl = null){
        if ($this->_mainframe->isAdmin()) {
            $this->addToolbar();
        }
		
		$filter_order		= $this->_mainframe->getUserStateFromRequest($this->_option.'.fields.filter_order',			'filter_order',		'ordering',	'cmd');
		$filter_order_Dir	= $this->_mainframe->getUserStateFromRequest($this->_option.'.fields.filter_order_Dir',		'filter_order_Dir',	'',		'word');
        $filter_type	    = $this->_mainframe->getUserStateFromRequest($this->_option.'.fields.filter_type',		    'filter_type',	    '',		'word');
		$filter_published	= $this->_mainframe->getUserStateFromRequest($this->_option.'.fields.filter_published',		'filter_published',	'');
		$filter_language	= $this->_mainframe->getUserStateFromRequest($this->_option.'.fields.filter_language',		'filter_language',	'',		'string');
        $search				= $this->_mainframe->getUserStateFromRequest($this->_option.'.fields.search',				'search',			'',		'string');
		$search				= JString::strtolower($search);

        $options = array();
        $options[] = JHtml::_('select.option', '', 						JText::_('COM_MIJOVIDEOS_SEL_FIELD_TYPE'));
        $options[] = JHtml::_('select.option', 'text', 					JText::_('COM_MIJOVIDEOS_FIELDS_TEXT'));
        $options[] = JHtml::_('select.option', 'textarea', 				JText::_('COM_MIJOVIDEOS_FIELDS_TEXTAREA'));
        $options[] = JHtml::_('select.option', 'radio', 				JText::_('COM_MIJOVIDEOS_FIELDS_RADIO'));
        $options[] = JHtml::_('select.option', 'list', 					JText::_('COM_MIJOVIDEOS_FIELDS_LIST'));
        $options[] = JHtml::_('select.option', 'multilist', 			JText::_('COM_MIJOVIDEOS_FIELDS_MULTILIST'));
        $options[] = JHtml::_('select.option', 'checkbox', 				JText::_('COM_MIJOVIDEOS_FIELDS_CHECKBOX'));
        $options[] = JHtml::_('select.option', 'calendar', 				JText::_('COM_MIJOVIDEOS_FIELDS_CALENDAR'));
        $options[] = JHtml::_('select.option', 'mijovideoscountries',	JText::_('COM_MIJOVIDEOS_FIELDS_MIJOVIDEOSCOUNTRIES'));
        $options[] = JHtml::_('select.option', 'email', 				JText::_('COM_MIJOVIDEOS_FIELDS_EMAIL'));
        $options[] = JHtml::_('select.option', 'language', 				JText::_('COM_MIJOVIDEOS_FIELDS_LANGUAGE'));
        $options[] = JHtml::_('select.option', 'timezone', 				JText::_('COM_MIJOVIDEOS_FIELDS_TIMEZONE'));
        $lists['filter_type'] = JHtml::_('select.genericlist', $options, 'filter_type', ' class="inputbox" onchange="submit();"', 'value', 'text', $filter_type);

        $options = array();
        $options[] = JHtml::_('select.option', '',	JText::_('JOPTION_SELECT_PUBLISHED'));
        $options[] = JHtml::_('select.option', 1, 	JText::_('COM_MIJOVIDEOS_PUBLISHED'));
        $options[] = JHtml::_('select.option', 0, 	JText::_('COM_MIJOVIDEOS_UNPUBLISHED'));
        $lists['filter_published'] = JHtml::_('select.genericlist', $options, 'filter_published', ' class="inputbox" onchange="submit();" ', 'value', 'text', $filter_published);
		
		$lists['order_Dir']			= $filter_order_Dir;
		$lists['order'] 			= $filter_order;
		$lists['filter_language'] 	= $filter_language;
		$lists['search'] 			= $search;

        JHtml::_('behavior.tooltip');

        $this->lists				= $lists;
		$this->items				= $this->get('Items');
		$this->pagination			= $this->get('Pagination');
        $this->langs 				= MijoVideos::get('utility')->getLanguages();
		
		parent::display($tpl);
	}

    protected function addToolbar() {
        JToolBarHelper::title(JText::_('COM_MIJOVIDEOS_CPANEL_FIELDS'), 'mijovideos');

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
            JToolBarHelper::deleteList(JText::_('COM_MIJOVIDEOS_DELETE_FIELD_CONFIRM'));
        }

        if ($this->acl->canCreate() or $this->acl->canDelete()) {
            JToolBarHelper::divider();
        }

        $this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://mijosoft.com/support/docs/mijovideos/user-manual/fields?tmpl=component', 650, 500);
    }
}