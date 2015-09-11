<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_ADMINISTRATOR.'/components/com_mijovideos/library/mijovideos.php');

$db 		= JFactory::getDBO();
$user 		= JFactory::getUser();
$document 	= JFactory::getDocument();
$app 		= JFactory::getApplication();
$utility    = MijoVideos::get('utility');
$config 	= MijoVideos::getConfig();

$numberVideos = $params->get('number_videos', 6);
$categoryIds = $params->get('category_ids', '');
$showCategory = $params->get('show_category', 1);
$showChannel = $params->get('show_channel') ;
$tmpl = $app->getTemplate();
if (file_exists(JPATH_ROOT . '/templates/'.$tmpl.'/html/com_mijovideos/assets/css/modules.css') and !MijoVideos::isDashboard()) {
    $document->addStyleSheet(JUri::root() . 'templates/'.$tmpl.'/html/com_mijovideos/assets/css/modules.css');
} else {
    $document->addStyleSheet(JUri::root() . 'components/com_mijovideos/assets/css/mijovideosmodules.css');
}
$where = array() ;
$where[] = 'a.published = 1';
//$where[] = 'DATE(created) >= CURDATE()';
//$where[] = '(created = "'.$db->getNullDate().'" OR DATE(created) >= CURDATE())';

if ($categoryIds != '') {
	$where[] = ' a.id IN (SELECT video_id FROM #__mijovideos_video_categories WHERE category_id IN ('.$categoryIds.'))' ;	
}

$where[] = ' a.access IN ('.implode(',', $user->getAuthorisedViewLevels()).')';
if ($app->getLanguageFilter()) {
	$where[] = 'a.language IN (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
}

$sql = 'SELECT a.id, a.title, a.channel_id, a.created, c.title AS channel_title FROM #__mijovideos_videos AS a '
	 . ' LEFT JOIN #__mijovideos_channels AS c '
	 . ' ON a.channel_id = c.id '
	 . ' WHERE '.implode(' AND ', $where)
	 . ' ORDER BY a.created '
	 . ' LIMIT '.$numberVideos		
;	
$db->setQuery($sql) ;	
$rows = $db->loadObjectList();

for ($i = 0, $n = count($rows); $i < $n; $i++) {
	$row = $rows[$i];

	$sql = 'SELECT a.id, a.title FROM #__mijovideos_categories AS a INNER JOIN #__mijovideos_video_categories AS b ON a.id = b.category_id WHERE b.video_id='.$row->id;
	$db->setQuery($sql) ;
	$categories = $db->loadObjectList();

	if (count($categories)) {
		$itemCategories = array();

		foreach ($categories as  $category) {
            $Itemid = MijoVideos::get('router')->getItemid(array('view' => 'category', 'category_id' => $category->id), null, true);

			$itemCategories[] = '<a href="'.JRoute::_('index.php?option=com_mijovideos&view=category&category_id='.$category->id . $Itemid).'"><strong>'.$category->title.'</strong></a>';
		}

		$row->categories = implode('&nbsp;|&nbsp;', $itemCategories) ;
	}		
}

$document->addStyleSheet(JUri::root(true).'/modules/mod_mijovideos_videos/css/style.css');

require(JModuleHelper::getLayoutPath('mod_mijovideos_videos', 'default'));