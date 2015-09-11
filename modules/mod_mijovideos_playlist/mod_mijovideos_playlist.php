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
$tmpl = $app->getTemplate();
if (file_exists(JPATH_ROOT . '/templates/'.$tmpl.'/html/com_mijovideos/assets/css/modules.css') and !MijoVideos::isDashboard()) {
    $document->addStyleSheet(JUri::root() . 'templates/'.$tmpl.'/html/com_mijovideos/assets/css/modules.css');
} else {
    $document->addStyleSheet(JUri::root() . 'components/com_mijovideos/assets/css/mijovideosmodules.css');
}

$numberVideos = $params->get('number_videos', 5);
$filterby = $params->get('filterby');
if(!$filterby){
    $filterbywhere = 'created';
}else{
    $filterbywhere = 'ordering';
}
	
if ($app->getLanguageFilter()) {
	$extraWhere = ' AND language IN (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
} else {
	$extraWhere = '' ;
}

$sql = 'SELECT id, title FROM #__mijovideos_playlists WHERE published=1'
	.' AND access IN ('.implode(',', $user->getAuthorisedViewLevels()).')'.$extraWhere.' ORDER BY '.$filterbywhere.''.($numberVideos ? ' LIMIT '.$numberVideos : '');
   
$db->setQuery($sql) ;	
$rows = $db->loadObjectList() ;

require(JModuleHelper::getLayoutPath('mod_mijovideos_playlist', 'default'));