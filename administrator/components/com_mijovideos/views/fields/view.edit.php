<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosViewFields extends MijovideosView {

    public function display($tpl = null) {
        if (!$this->acl->canEdit()) {
            JFactory::getApplication()->redirect('index.php?option=com_mijovideos', JText::_('JERROR_ALERTNOAUTHOR'));
        }

        $db	= JFactory::getDBO();

        $item = $this->get('EditData');

		$task = MijoVideos::getInput()->getCmd('task', '');

		$text = ($task == 'edit') ? JText::_('COM_MIJOVIDEOS_EDIT') : JText::_('COM_MIJOVIDEOS_NEW');

        if ($this->_mainframe->isAdmin()) {
            JToolBarHelper::title(JText::_('Field').': <small><small>[ ' . $text.' ]</small></small>' , 'mijovideos' );
            JToolBarHelper::apply();
            JToolBarHelper::save();
            JToolBarHelper::save2new();
            JToolBarHelper::cancel();
            JToolBarHelper::divider();
            $this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://mijosoft.com/support/docs/mijovideos/user-manual/fields?tmpl=component', 650, 500);
        }

		$options = array();
		$options[] = JHtml::_('select.option', '', 						JText::_('COM_MIJOVIDEOS_SEL_FIELD_TYPE'));
		$options[] = JHtml::_('select.option', 'text', 					JText::_('COM_MIJOVIDEOS_FIELDS_TEXT'));
		$options[] = JHtml::_('select.option', 'textarea', 				JText::_('COM_MIJOVIDEOS_FIELDS_TEXTAREA'));
        $options[] = JHtml::_('select.option', 'radio',	 				JText::_('COM_MIJOVIDEOS_FIELDS_RADIO'));
		$options[] = JHtml::_('select.option', 'list', 					JText::_('COM_MIJOVIDEOS_FIELDS_LIST'));
		$options[] = JHtml::_('select.option', 'multilist', 			JText::_('COM_MIJOVIDEOS_FIELDS_MULTILIST'));
		$options[] = JHtml::_('select.option', 'checkbox', 				JText::_('COM_MIJOVIDEOS_FIELDS_CHECKBOX'));
		$options[] = JHtml::_('select.option', 'calendar', 				JText::_('COM_MIJOVIDEOS_FIELDS_CALENDAR'));
        $options[] = JHtml::_('select.option', 'mijovideoscountries',	JText::_('COM_MIJOVIDEOS_FIELDS_MIJOVIDEOSCOUNTRIES'));
		$options[] = JHtml::_('select.option', 'email', 				JText::_('COM_MIJOVIDEOS_FIELDS_EMAIL'));
		$options[] = JHtml::_('select.option', 'language', 				JText::_('COM_MIJOVIDEOS_FIELDS_LANGUAGE'));
		$options[] = JHtml::_('select.option', 'timezone', 				JText::_('COM_MIJOVIDEOS_FIELDS_TIMEZONE'));
		$lists['field_type'] = JHtml::_('select.genericlist', $options, 'field_type',' class="inputbox" ', 'value', 'text', $item->field_type);

		if ($this->config->get('cb_integration')) {
			if ($this->config->get('cb_integration') == 1) {
				$sql = 'SELECT name AS `value`, name AS `text` FROM #__comprofiler_fields WHERE `table` = "#__comprofiler"';
			}
            elseif ($this->config->get('cb_integration') == 2) {
				$sql = 'SELECT fieldcode AS `value`, fieldcode AS `text` FROM #__community_fields WHERE published = 1 AND fieldcode != ""' ;
			}

			$db->setQuery($sql);
			$options = array();

			$options[] = JHtml::_('select.option', '', JText::_('COM_MIJOVIDEOS_SEL_FIELD'));
			$options = array_merge($options, $db->loadObjectList());

			$lists['field_mapping'] = JHtml::_('select.genericlist', $options, 'field_mapping', ' class="inputbox" ', 'value', 'text', $item->field_mapping);
		}
					
        $lists['published'] = MijoVideos::get('utility')->getRadioList('published', $item->published);
		$lists['language'] = JHtml::_('select.genericlist', JHtml::_('contentlanguage.existing', true, true), 'language', ' class="inputbox" ', 'value', 'text', $item->language);

        JHtml::_('behavior.tooltip');

		$this->item		= $item;
		$this->lists	= $lists;

		parent::display($tpl);				
	}
}