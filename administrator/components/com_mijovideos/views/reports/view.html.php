<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;


class MijovideosViewReports extends MijovideosView {
	
	function display($tpl = null) {
        if ($this->_mainframe->isAdmin()) {
            $this->addToolbar();
        }
		
		$filter_order		= $this->_mainframe->getUserStateFromRequest($this->_option.'.reports.video_filter_order',	'filter_order',		    'r.created',	'cmd');
		$filter_order_Dir	= $this->_mainframe->getUserStateFromRequest($this->_option.'.reports.filter_order_Dir',	'filter_order_Dir',	    'DESC',		    'word');
        $filter_reason		= $this->_mainframe->getUserStateFromRequest($this->_option.'.reports.filter_reason',	    'filter_reason',		'',				'string');
        $filter_type 		= $this->_mainframe->getUserStateFromRequest($this->_option.'.reports.filter_type',			'filter_type',	    	'',		    	'string');
        $filter_language	= $this->_mainframe->getUserStateFromRequest($this->_option.'.reports.filter_language',		'filter_language',	    '',		    	'string');
		$search				= $this->_mainframe->getUserStateFromRequest($this->_option.'.reports.search',				'search',			    '',		    	'string');
		$search				= JString::strtolower($search);

        $reasons = $this->get('Reasons');
        $options = array();
        $options[] = JHtml::_('select.option', '', JText::_('COM_MIJOVIDEOS_SELECT_REASON'));
        foreach($reasons as $reason){
            $options[] = JHtml::_('select.option',  $reason->id, $reason->title);
        }
        $lists['filter_reason'] = JHtml::_('select.genericlist', $options, 'filter_reason', ' class="inputbox" style="width: 220px;" onchange="submit();" ', 'value', 'text', $filter_reason);

        $lists['search']		 	= $search ;
		$lists['order_Dir']			= $filter_order_Dir;
		$lists['order'] 		 	= $filter_order;

		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('COM_MIJOVIDEOS_SELECT_TYPE'));
		$options[] = JHtml::_('select.option',  'video', JText::_('COM_MIJOVIDEOS_VIDEO'));
		$options[] = JHtml::_('select.option',  'channel', JText::_('COM_MIJOVIDEOS_CHANNEL'));
		$lists['filter_type'] = JHtml::_('select.genericlist', $options, 'filter_type', ' class="inputbox" style="width: 140px;" onchange="submit();" ', 'value', 'text', $filter_type);


        JHtml::_('behavior.tooltip');
		
		$this->lists 			= $lists;
		$this->items 			= $this->get('Items');
		$this->pagination 		= $this->get('Pagination');
        $this->langs            = MijoVideos::get('utility')->getLanguages();
		$this->filter_language 	= $filter_language;

		parent::display($tpl);				
	}

    function displayDetails($tpl = null) {
        $this->items = $this->get('Items');
		parent::display($tpl);
	}

    protected function addToolbar() {
        JToolBarHelper::title(JText::_('COM_MIJOVIDEOS_CPANEL_REPORTS'), 'mijovideos');

        $reasons_icon = 'icon-32-mijovideos-reasons';
        if (MijoVideos::is30()){
            $reasons_icon = 'checkbox-partial';
        }

		$this->toolbar->appendButton('link', $reasons_icon, JText::_('COM_MIJOVIDEOS_REASONS'), JRoute::_('index.php?option=com_mijovideos&view=reasons'));

        if ($this->acl->canDelete()) {
            JToolBarHelper::deleteList(JText::_('COM_MIJOVIDEOS_DELETE_REPORTS_CONFIRM'));
        }

        $this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://mijosoft.com/support/docs/mijovideos/user-manual/fields?tmpl=component', 650, 500);
    }
}