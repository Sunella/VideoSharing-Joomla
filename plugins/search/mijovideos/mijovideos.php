<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');
require_once(JPATH_ADMINISTRATOR.'/components/com_mijovideos/library/mijovideos.php');

class plgSearchMijovideos extends JPlugin {
	
	public function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
		
		$this->loadLanguage();
	}

	public function onContentSearchAreas()	{
		static $areas = array('mijovideos' => 'COM_MIJOVIDEOS_VIDEOS');
		
		return $areas;
	}

	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null) {
		if (is_array($areas)) {
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
				return array();
			}
		}
		
		$text = trim($text);
		if ($text == '') {
			return array();
		}
		
		$db	= JFactory::getDBO();
		$user = JFactory::getUser();
		$limit = $this->params->get('search_limit', 50);
		$Itemid = MijoVideos::get('router')->getItemid();
		
		$section = JText::_('COM_MIJOVIDEOS_VIDEOS');

        $wheres = array();
		
		switch ($phrase) {
			case 'exact':
				$text = $db->Quote('%'.$db->escape($text, true).'%', false);

                $wheres[] 	= 'a.title LIKE '.$text;
                $wheres[] 	= 'a.introtext LIKE '.$text;
                $wheres[] 	= 'a.fulltext LIKE '.$text;
				
				$where = '(' . implode(') OR (', $wheres) . ')';

				break;
			case 'all':
			case 'any':
			default:
				$words = explode(' ', $text);

				foreach ($words as $word) {
					$word = $db->Quote('%'.$db->escape($word, true).'%', false);
					
					$wheres2 	= array();
					$wheres2[] 	= 'a.title LIKE '.$word;
					$wheres2[] 	= 'a.introtext LIKE '.$word;
					$wheres2[] 	= 'a.fulltext LIKE '.$word;

					$wheres[] 	= implode(' OR ', $wheres2);
				}
				
				$where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';

				break;
		}
	
		switch ($ordering) {
			case 'oldest':
				$order = 'a.created DESC';
				break;		
			case 'alpha':
				$order = 'a.title ASC';
				break;
			case 'newest':
				$order = 'a.created ASC';
                break;
			default:
				$order = 'a.created';
		}
		
		$query = 'SELECT a.id, a.title AS title, a.introtext AS text, created AS `created`, '.$db->Quote($section).' AS section, "0" AS browsernav '
				.'FROM #__mijovideos_videos AS a '
				.'WHERE ('.$where.') AND (a.access = 0 OR a.access IN ('.implode(',', $user->getAuthorisedViewLevels()).')) AND a.published = 1 '
				.'ORDER BY '.$order;
				
		$db->setQuery($query, 0, $limit);
		$rows = $db->loadObjectList();
		
		if (count($rows)) {
			foreach($rows as $key => $row) {
				$rows[$key]->href = JRoute::_('index.php?option=com_mijovideos&view=video&video_id='.$row->id.$Itemid);
			}
		}
		
		return $rows;
	}	
}
