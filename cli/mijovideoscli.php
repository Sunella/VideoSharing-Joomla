<?php
/**
 * @package        MijoVideos
 * @copyright      2009-2014 Mijosoft LLC, mijosoft.com
 * @license        GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

define('_JEXEC', 1);
define('_JCLI', 1);

if (!defined('_JDEFINES')) {
	define('JPATH_BASE', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'administrator');
	require_once JPATH_BASE . '/includes/defines.php';
}

require_once JPATH_BASE . '/includes/framework.php';
require_once JPATH_BASE . '/includes/helper.php';
require_once JPATH_BASE . '/includes/toolbar.php';


define('JPATH_COMPONENT', JPATH_BASE . '/components/com_mijovideos');
define('JPATH_COMPONENT_SITE', JPATH_SITE . '/components/com_mijovideos');
define('JPATH_COMPONENT_ADMINISTRATOR', JPATH_ADMINISTRATOR . '/components/com_mijovideos');
JRequest::setVar('option', 'com_mijovideos', 'GET');
$_SERVER['REQUEST_METHOD'] = 'GET';

// Instantiate the application.
$app = JFactory::getApplication('administrator');

jimport('joomla.application.cli');
jimport('joomla.database.database');
jimport('joomla.database.table');
jimport('joomla.database.table.extension');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class MijovideosCli extends JApplicationCli {
	public function __construct() {
		JLoader::register('JApplication', JPATH_PLATFORM . '/legacy/application/application.php');
		JLoader::register('JApplicationHelper', JPATH_PLATFORM . '/cms/application/helper.php');
		JLoader::register('JApplicationCms', JPATH_PLATFORM . '/cms/application/cms.php');
		JLoader::register('JApplicationAdministrator', JPATH_PLATFORM . '/cms/application/administrator.php');
		JLoader::register('JControllerLegacy', JPATH_PLATFORM . '/legacy/controller/legacy.php');
		JLoader::register('JModelLegacy', JPATH_PLATFORM . '/legacy/model/legacy.php');
		JLoader::register('JViewLegacy', JPATH_PLATFORM . '/legacy/view/legacy.php');
		JLoader::register('JRequest', JPATH_PLATFORM . '/joomla/environment/request.php');
		JLoader::register('JComponentHelper', JPATH_PLATFORM . '/cms/component/helper.php');
		JLoader::register('JParameter', JPATH_PLATFORM . '/joomla/html/parameter.php');
		JFactory::getApplication('administrator');
		JLoader::register('MijoVideos', JPATH_ROOT . '/administrator/components/com_mijovideos/library/mijovideos.php');
		parent::__construct();
	}

	public function process() {

		// Load process object
		$process_lib = MijoVideos::get('processes');

		$args      = $GLOBALS['argv'];
		$processes = array_slice($args, 1);
		if (count($processes) > 1) {
			foreach ($processes as $process) {
				$process = (int)$process;
				if ($process > 0) {
					$process_lib->run($process);
				}

			}
		}
		else {
			for ($i = 1; $i <= 50; $i++) {
				$process_lib->run();
			}
		}
	}

	public function cdn() {
		$config      = MijoVideos::getConfig();
		$pluginClass = 'plgMijovideos' . $config->get('cdn', 'amazons3');
		$pluginPath  = JPATH_ROOT . '/plugins/mijovideos/' . $config->get('cdn', 'amazons3') . '/' . $config->get('cdn', 'amazons3') . '.php';

		if (file_exists($pluginPath)) {
			JLoader::register($pluginClass, $pluginPath);
			$cdn = call_user_func(array($pluginClass, 'getInstance'));

			return $cdn->maintenance();
		}
	}

	public function convertToHtml5() {
		$args   = $GLOBALS['argv'];
		$upload = MijoVideos::get('videos');
		$upload->convertToHtml5($args[2], $args[3]);
	}

	public function test() {
		$filename = JPATH_SITE . '/tmp/com_mijovideos.background';
		$buffer   = '';
		JFile::write($filename, $buffer);
	}

	public function execute() {
		$args = $GLOBALS['argv'];
		if ($args[1] == 'test') {
			$this->test();
		}
		else if ($args[1] == 'cdn') {
			$this->cdn();
		}
		else if ($args[1] == 'process') {
			$this->process();
		}
		else if ($args[1] == 'convertToHtml5') {
			$this->convertToHtml5();
		}
	}
}

JApplicationCli::getInstance('MijovideosCli')->execute();