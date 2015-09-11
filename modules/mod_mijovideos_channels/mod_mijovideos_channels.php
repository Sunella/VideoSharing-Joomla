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
$width = $params->get('thumb_width', 130);
$height = $params->get('thumb_height', 100);
$tmpl = $app->getTemplate();
if (file_exists(JPATH_ROOT . '/templates/'.$tmpl.'/html/com_mijovideos/assets/css/modules.css') and !MijoVideos::isDashboard()) {
    $document->addStyleSheet(JUri::root() . 'templates/'.$tmpl.'/html/com_mijovideos/assets/css/modules.css');
} else {
    $document->addStyleSheet(JUri::root() . 'components/com_mijovideos/assets/css/mijovideosmodules.css');
}
$numberChannels = $params->get('number_channels', 5);
$showfeat = $params->get('show_featured');
if(!$showfeat){
    $showfeatwhere = 'featured=0 AND ';
}else{
    $showfeatwhere = '';
}
	
if ($app->getLanguageFilter()) {
	$extraWhere = ' AND language IN (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
} else {
	$extraWhere = '' ;
}

$sql = 'SELECT id, title, thumb, hits FROM #__mijovideos_channels WHERE '.$showfeatwhere.'published=1 '
	.' AND access IN ('.implode(',', $user->getAuthorisedViewLevels()).')'.$extraWhere.' ORDER BY ordering '.($numberChannels ? ' LIMIT '.$numberChannels : '');
   
$db->setQuery($sql) ;	
$rows = $db->loadObjectList() ;
require(JModuleHelper::getLayoutPath('mod_mijovideos_channels', 'default'));