<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted Access');

require_once(dirname(__FILE__).'/initialise.php');

abstract class MijoVideos {

    public static function &get($filePath, $options = null) {
        static $instances = array();

        $parts = explode('.', $filePath);
        $class = array_pop($parts);

		if (!isset($instances[$class])) {
			require_once(JPATH_MIJOVIDEOS_LIB.'/'.strtolower(str_replace('.', '/', $filePath)).'.php');
			
			$class_name = 'Mijovideos'.ucfirst($class);
			if (class_exists($class_name)) {
                $instances[$class] = new $class_name($options);
            }
		}

		return $instances[$class];
    }

    public static function is30() {
        return self::get('utility')->is30();
   	}

    public static function is31() {
        return self::get('utility')->is31();
   	}

    public static function is32() {
        return self::get('utility')->is32();
   	}

    public static function getConfig($type = null) {
        return self::get('utility')->getConfig($type);
    }

	public static function getTable($name) {
        return self::get('utility')->getTable($name);
	}

    // @TODO : get JRequest/MRequest instance before Wordpress release
    public static function getInput() {
        return JFactory::getApplication()->input;
    }

    public static function getButtonClass() {
        return self::get('utility')->getConfig()->get('button_class');
    }

    public static function isDashboard() {
        return self::get('utility')->isDashboard();
    }

    public static function getVideoRoute($id) {
        return 'index.php?option=com_mijovideos&view=video&video_id='. $id;
    }

    public static function getPlugin($name) {
        return self::get('utility')->getPlugin($name);
    }

    public static function log($message, $priority = null) {
        return self::get('utility')->log($message, $priority);
    }
}