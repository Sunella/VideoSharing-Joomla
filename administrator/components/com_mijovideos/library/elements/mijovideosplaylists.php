<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldMijovideosPlaylists extends JFormField {

	protected $type = 'MijovideosPlaylists';
	
	function getInput() {
		$db = JFactory::getDBO();
		$db->setQuery("SELECT id, title FROM #__mijovideos_playlists WHERE published = 1 AND type = 0 ORDER BY title");
		$rows = $db->loadObjectList();
		
		$options = array();
		$options[] = JHtml::_('select.option', '0', JText::_('Select Playlist'), 'id', 'title');
		$options = array_merge($options, $rows);
		
		return JHtml::_('select.genericlist', $options, $this->name, ' class="inputbox" ', 'id', 'title', $this->value);
	}
}
