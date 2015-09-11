<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted Access');

require_once(JPATH_ADMINISTRATOR.'/components/com_mijovideos/library/mijovideos.php');

if (!MijoVideos::get('utility')->checkRequirements('site')) {
    return;
}

$view = MijoVideos::getInput()->getCmd('view', '');
$task = MijoVideos::getInput()->getCmd('task', '');

if (MijoVideos::isDashboard()) {
    require_once(JPATH_MIJOVIDEOS.'/controllers/dashboard.php');

    $controller = new MijovideosControllerDashboard();
    $controller->execute($task);
    $controller->redirect();
    return;
}

if (empty($view)) {
    $view = 'category';
    JRequest::setVar('view', 'category');
}

if ($view) {
	$path = JPATH_MIJOVIDEOS.'/controllers/'.$view.'.php';

	if (file_exists($path)) {
		require_once($path);
	}
    else {
		$view = '';
	}
}

$class_name = 'MijovideosController'.$view;

$controller = new $class_name();
$controller->execute($task);
$controller->redirect();