<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted Access');

# Access check
if (!JFactory::getUser()->authorise('core.manage', 'com_mijovideos')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JHtml::_('behavior.framework');

require_once(JPATH_ADMINISTRATOR.'/components/com_mijovideos/library/mijovideos.php');


if (!MijoVideos::get('utility')->checkRequirements('admin')) {
    return;
}


$task = MijoVideos::getInput()->getCmd('task', '');

if (!(($task == 'add' or $task == 'edit') and MijoVideos::is30())) {
    require_once(JPATH_MIJOVIDEOS_ADMIN.'/toolbar.php');
}

if ($view = MijoVideos::getInput()->getCmd('view', '')) {
    if ($view == 'videos' and $task == 'add') {
        $view = 'upload';
        MijoVideos::getInput()->setVar('view', $view);
    }

    $path = JPATH_MIJOVIDEOS_ADMIN.'/controllers/'.$view.'.php';

	if (file_exists($path)) {
		require_once($path);
	} else {
		$view = '';
	}
}

$class_name = 'MijovideosController'.$view;

$controller = new $class_name();
$controller->execute(MijoVideos::getInput()->getCmd('task', ''));
$controller->redirect();