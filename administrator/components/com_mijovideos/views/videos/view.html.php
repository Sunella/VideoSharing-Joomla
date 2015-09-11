<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;


class MijovideosViewVideos extends MijovideosView {
	
	function display($tpl = null) {
        if ($this->_mainframe->isAdmin()) {
            $this->addToolbar();
        }
		
		$filter_order		= $this->_mainframe->getUserStateFromRequest($this->_option.'.videos.video_filter_order',	'filter_order',		    'title',	'cmd');
		$filter_order_Dir	= $this->_mainframe->getUserStateFromRequest($this->_option.'.videos.filter_order_Dir',	    'filter_order_Dir',	    '',		    'word');
        $filter_published	= $this->_mainframe->getUserStateFromRequest($this->_option.'.videos.filter_published',	    'filter_published',		'');
        $filter_category 	= $this->_mainframe->getUserStateFromRequest($this->_option.'.videos.filter_category',		'filter_category',	    0,		    'int');
        $filter_channel 	= $this->_mainframe->getUserStateFromRequest($this->_option.'.videos.filter_channel',		'filter_channel',	    0,		    'int');
        $filter_access      = $this->_mainframe->getUserStateFromRequest($this->_option.'.videos.filter_access',	    'filter_access',	    '');
        $filter_language	= $this->_mainframe->getUserStateFromRequest($this->_option.'.videos.filter_language',		'filter_language',	    '',		    'string');
		$search				= $this->_mainframe->getUserStateFromRequest($this->_option.'.videos.search',				'search',			    '',		    'string');
		$search				= JString::strtolower($search);

		$lists['filter_category']	= MijoVideos::get('utility')->buildCategoryDropdown($filter_category, 'filter_category', true);

        /*$channels = $this->get('Channels');
        $options = array();
        $options[] = JHtml::_('select.option', '', JText::_('COM_MIJOVIDEOS_SELECT_CHANNEL'));
        foreach($channels as $channel){
            $options[] = JHtml::_('select.option',  $channel->id, $channel->title);
        }
        $lists['filter_channel'] = JHtml::_('select.genericlist', $options, 'filter_channel', ' class="inputbox" style="width: 220px;" onchange="submit();" ', 'value', 'text', $filter_channel);
        */

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
        $acl = MijoVideos::get('acl');

        JToolBarHelper::title(JText::_('COM_MIJOVIDEOS_CPANEL_VIDEOS'), 'mijovideos');

        if ($acl->canCreate()) {
            //$this->toolbar->appendButton('link', 'new', 'JTOOLBAR_NEW', JRoute::_('index.php?option=com_mijovideos&view=upload'));
            JToolBarHelper::addNew();
        }

        if ($acl->canEdit()) {
            JToolBarHelper::editList();
        }

        if ($acl->canCreate() or $acl->canEdit()) {
            JToolBarHelper::divider();
        }

        if ($acl->canEditState()) {
            JToolBarHelper::publishList();
            JToolBarHelper::unpublishList();
            JToolBarHelper::divider();
        }

        if ($acl->canCreate()) {
            JToolBarHelper::custom('copy', 'copy.png', 'copy_f2.png', 'Copy', true);
        }

        if ($acl->canDelete()) {
            JToolBarHelper::deleteList(JText::_('COM_MIJOVIDEOS_DELETE_VIDEO_CONFIRM'));
        }

        if ($acl->canCreate() or $acl->canDelete()) {
            JToolBarHelper::divider();
        }

        $this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://mijosoft.com/support/docs/mijovideos/user-manual/fields?tmpl=component', 650, 500);
    }
}