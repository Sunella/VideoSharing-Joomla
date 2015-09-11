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
        if (!$this->acl->canEdit()) {
            JFactory::getApplication()->redirect('index.php?option=com_mijovideos', JText::_('JERROR_ALERTNOAUTHOR'));
        }

        $task = JRequest::getCmd('task');
        $text = ($task == 'edit') ? JText::_('COM_MIJOVIDEOS_EDIT') : JText::_('COM_MIJOVIDEOS_NEW');

        if ($this->_mainframe->isAdmin()) {
            JToolBarHelper::title(JText::_('COM_MIJOVIDEOS_CATEGORY').': <small><small>[ ' . $text.' ]</small></small>', 'mijovideos');
            JToolBarHelper::apply();
            JToolBarHelper::save();
            JToolBarHelper::save2new();
            JToolBarHelper::cancel();
            JToolBarHelper::divider();
            $this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://mijosoft.com/support/docs/mijovideos/user-manual/categories?tmpl=component', 650, 500);
        }
        
		$item = $this->get('EditData');			
		
		$options = array() ;
		$options[] = JHtml::_('select.option', '', JText::_('Default Layout')) ;
		$options[] = JHtml::_('select.option', 'table', JText::_('Table Layout')) ;
		$options[] = JHtml::_('select.option', 'calendar', JText::_('Calendar Layout')) ;

		$lists['parent'] = MijoVideos::get('utility')->buildParentCategoryDropdown($item);
		$lists['published'] = MijoVideos::get('utility')->getRadioList('published', $item->published);
		$lists['access'] = JHtml::_('access.level', 'access', $item->access, ' class="inputbox" ', false);
		$lists['language'] = JHtml::_('select.genericlist', JHtml::_('contentlanguage.existing', true, true), 'language', ' class="inputbox"', 'value', 'text', $item->language);

        JHtml::_('behavior.tooltip');

		$this->item = $item;
		$this->lists = $lists;
		
		parent::display($tpl);				
	}
}