<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosViewReasons extends MijovideosView {

    public function display($tpl = null) {
        if (!$this->acl->canEdit()) {
            JFactory::getApplication()->redirect('index.php?option=com_mijovideos', JText::_('JERROR_ALERTNOAUTHOR'));
        }

        $task = JRequest::getCmd('task');
		$text = ($task == 'edit') ? JText::_('COM_MIJOVIDEOS_EDIT') : JText::_('COM_MIJOVIDEOS_NEW');

        if ($this->_mainframe->isAdmin()) {
            JToolBarHelper::title(JText::_('COM_MIJOVIDEOS_CPANEL_REASONS').': <small><small>[ ' . $text.' ]</small></small>' , 'mijovideos' );
            JToolBarHelper::apply();
            JToolBarHelper::save();
            JToolBarHelper::save2new();
            JToolBarHelper::cancel();
            JToolBarHelper::divider();
            $this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://mijosoft.com/support/docs/mijovideos/user-manual/reasons?tmpl=component', 650, 500);
        }

        $item = $this->get('EditData');
		
		$associations = $this->get('Association');
        $options = array();
        $options[] = JHtml::_('select.option', '', JText::_('COM_MIJOVIDEOS_SELECT_ASSOCIATION'));
        foreach($associations as $association) {
            $options[] = JHtml::_('select.option',  $association->id, $association->title);
        }

        $lists['association'] = JHtml::_('select.genericlist', $options, 'association', array(
                                                                                            'option.text.toHtml' => false,
                                                                                            'option.text' => 'text',
                                                                                            'option.value' => 'value',
                                                                                            'list.attr' => ' class="inputbox" style="width: 220px;" ',
                                                                                            'list.select' => $item->association));

        $lists['access']    = JHtml::_('access.level', 'access', $item->access, 'class="inputbox"', false) ;
        $lists['published'] = MijoVideos::get('utility')->getRadioList('published', $item->published);
        $lists['language']  = JHtml::_('select.genericlist', JHtml::_('contentlanguage.existing', true, true), 'language', ' class="inputbox" ', 'value', 'text', $item->language);

        JHtml::_('behavior.tooltip');
        JHtml::_('behavior.modal');
						
		$this->item		    = $item;
		$this->lists	    = $lists;
				
		parent::display($tpl);				
	}
}