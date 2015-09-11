<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted Access');

define('MIJOVIDEOS_PACK', 'booking');
define('JPATH_MIJOVIDEOS', JPATH_ROOT.'/components/com_mijovideos');
define('JPATH_MIJOVIDEOS_ADMIN', JPATH_ROOT.'/administrator/components/com_mijovideos');
define('JPATH_MIJOVIDEOS_LIB', JPATH_MIJOVIDEOS_ADMIN.'/library');

if (!class_exists('MijoDB')) {
	JLoader::register('MijoDB', JPATH_MIJOVIDEOS_LIB.'/database.php');
}

if (JFactory::$application->isAdmin()) {
    $_side = JPATH_ADMINISTRATOR;
}
else {
    $_side = JPATH_SITE;
}

$_lang = JFactory::getLanguage();
$_lang->load('com_mijovideos', $_side, 'en-GB', true);
$_lang->load('com_mijovideos', $_side, $_lang->getDefault(), true);
$_lang->load('com_mijovideos', $_side, null, true);

JTable::addIncludePath(JPATH_MIJOVIDEOS_ADMIN.'/tables');

JLoader::register('MijovideosController', JPATH_MIJOVIDEOS_ADMIN.'/library/controller.php');
JLoader::register('MijovideosModel', JPATH_MIJOVIDEOS_ADMIN.'/library/model.php');
JLoader::register('MijovideosView', JPATH_MIJOVIDEOS_ADMIN.'/library/view.php');

// Register MijoVideos logger
JLog::addLogger(array('text_file' => 'mijovideos.log.php', 'text_entry_format' => '{DATETIME} {PRIORITY} {MESSAGE}'), JLog::ALL, array('mijovideos'));