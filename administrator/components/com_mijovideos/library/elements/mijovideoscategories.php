<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldMijovideosCategories extends JFormField {

	protected $type = 'MijovideosCategories';
	
	function getInput() {    		
		$db = JFactory::getDBO();			
		$db->setQuery("SELECT id, parent, parent AS parent_id, title FROM #__mijovideos_categories WHERE published = 1");
		$rows = $db->loadObjectList();
		
		$children = array();
		if ($rows) {
			// first pass - collect children
			foreach ($rows as $v) {
				$pt 	= $v->parent;
				$list 	= @$children[$pt] ? $children[$pt] : array();
				array_push($list, $v);
				$children[$pt] = $list;
			}
		}
		
		$list = JHtml::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);
		
		$options = array();
		$options[] = JHtml::_('select.option', '0', JText::_('Top'));
		foreach ($list as $item) {
			$options[] = JHtml::_('select.option', $item->id, '&nbsp;&nbsp;&nbsp;'. $item->treename);
		}
		
		return JHtml::_('select.genericlist', $options, $this->name, array(
			'option.text.toHtml' => false ,
			'option.value' => 'value', 
			'option.text' => 'text', 
			'list.attr' => ' class="inputbox" ',
			'list.select' => $this->value    		        		
		));					    		
	}
}
