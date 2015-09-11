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
$thumb = $params->get('show_thumb'); 
$width = $params->get('thumb_width', 130);
$height = $params->get('thumb_height', 100);
if (file_exists(JPATH_ROOT . '/templates/'.$tmpl.'/html/com_mijovideos/assets/css/modules.css') and !MijoVideos::isDashboard()) {
    $document->addStyleSheet(JUri::root() . 'templates/'.$tmpl.'/html/com_mijovideos/assets/css/modules.css');
} else {
    $document->addStyleSheet(JUri::root() . 'components/com_mijovideos/assets/css/mijovideosmodules.css');
}
$numberCategories = $params->get('number_categories', 5);
$showsub = $params->get('show_subcategories');
if(!$showsub){
    $showsubwhere = 'parent=0 AND ';
}else{
    $showsubwhere = '';
}
	
if ($app->getLanguageFilter()) {
	$extraWhere = ' AND language IN (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
} else {
	$extraWhere = '' ;
}

$sql = 'SELECT id, title, thumb FROM #__mijovideos_categories WHERE '.$showsubwhere.'published=1 '
	.' AND access IN ('.implode(',', $user->getAuthorisedViewLevels()).')'.$extraWhere.' ORDER BY ordering '.($numberCategories ? ' LIMIT '.$numberCategories : '');
   
$db->setQuery($sql) ;	
$rows = $db->loadObjectList() ;

require(JModuleHelper::getLayoutPath('mod_mijovideos_categories', 'default'));