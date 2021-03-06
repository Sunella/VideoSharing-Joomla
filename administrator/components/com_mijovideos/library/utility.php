<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted Access');

# Imports
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.archive');
jimport('joomla.filesystem.path');
jimport('joomla.application.component.helper');

# Utility class
class MijovideosUtility {

	private static $data = array();

    public function __construct() {
        $this->config = $this->getConfig();
    }

    public function get($name, $default = null) {
        if (!is_array(self::$data) || !isset(self::$data[$name])) {
            return $default;
        }

        return self::$data[$name];
    }

    public function set($name, $value) {
        if (!is_array(self::$data)) {
            self::$data = array();
        }

        $previous = self::get($name);

        self::$data[$name] = $value;

        return $previous;
    }

    public function import($path) {
        require_once(JPATH_ADMINISTRATOR . '/components/com_mijovideos/' . str_replace('.', '/', $path).'.php');
    }

	public static function is30() {
		static $status;

		if (!isset($status)) {
			if (version_compare(JVERSION, '3.0.0', 'ge')) {
				$status = true;
			}
			else {
				$status = false;
			}
		}

		return $status;
	}

	public static function is31() {
		static $status;

		if (!isset($status)) {
			if (version_compare(JVERSION, '3.1.0', 'ge')) {
				$status = true;
			}
			else {
				$status = false;
			}
		}

		return $status;
	}

	public static function is32() {
		static $status;

		if (!isset($status)) {
			if (version_compare(JVERSION, '3.2.0', 'ge')) {
				$status = true;
			}
			else {
				$status = false;
			}
		}

		return $status;
	}

    public function route($link) {
        if ($this->isDashboard()) {
            if (!strstr($link, 'dashboard=1')) {
                $link .= '&dashboard=1';
            }

            $Itemid = MijoVideos::getInput()->getInt('Itemid', 0);
            if (!empty($Itemid) and !strstr($link, '&Itemid')) {
                $link .= '&Itemid='.$Itemid;
            }
        }

        return JRoute::_($link, false);
    }

    public function isDashboard() {
        $s = false;

        $app = JFactory::getApplication();
        $view = MijoVideos::getInput()->getCmd('view', '');
        $dashboard = MijoVideos::getInput()->getInt('dashboard', 0);

        if ($app->isSite() and ($view == 'dashboard') or ($dashboard == 1)) {
            $s = true;
        }

        return $s;
    }

    public function checkRequirements($src = 'all') {
        if (version_compare(PHP_VERSION, '5.2.0', '<')) {
            JError::raiseWarning('100', JText::sprintf('MijoVideos requires PHP 5.2.x to run, please contact your hosting company.'));
            return false;
        }

        /*$pid = $base->getConfig()->get('pid');
        if (($src == 'site') and empty($pid)) {
            JError::raiseWarning('404', JText::sprintf('COM_MIJOSHOP_CPANEL_PID_NOTE', '<a href="http://mijosoft.com/my-profile">', '</a>', '<a href="administrator/index.php?option=com_mijoshop&route=setting/setting">', '</a>'));
            return false;
        }*/

        return true;
    }

    public static function getConfig() {

        if (version_compare(PHP_VERSION, '5.2.0', '<')) {
   			JError::raiseWarning('100', JText::sprintf('MijoVideos requires PHP 5.2.x to run, please contact your hosting company.'));
   			return false;
   		}
		
        return JComponentHelper::getParams('com_mijovideos');
        
   	}

    public static function getTable($name) {
   		static $tables = array();

   		if (!isset($tables[$name])) {
   			JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_mijovideos/tables');
   			$tables[$name] = JTable::getInstance($name, 'Table');
   		}

   		return $tables[$name];
   	}
	
	public function getMijovideosVersion() {
        static $version;

        if (!isset($version)) {
            $version = $this->getXmlText(JPATH_MIJOVIDEOS_ADMIN.'/mijovideos.xml', 'version');
        }

		return $version;
	}

	public function getLatestMijovideosVersion() {
        static $version;

        if (!isset($version)) {
            $cache = JFactory::getCache('com_mijovideos', 'output');
            $cache->setCaching(1);

            $version = $cache->get('mv_version', 'com_mijovideos');

            if (empty($version)) {
                $version = $this->getRemoteVersion();
                $cache->store($version, 'mv_version', 'com_mijovideos');
            }
        }

		return $version;
	}

    public function renderModules($position) {
        $modules = JModuleHelper::getModules($position);

        if (count($modules) > 0) {
            $renderer = JFactory::getDocument()->loadRenderer('module');
            $attribs = array('style' => 'xhtml');

            ?><div><?php

            foreach ($modules as $mod) {
                echo $renderer->render($mod, $attribs);
            }

            ?></div><?php
        }
    }
	
	public function getMenu() {
		jimport('joomla.application.menu');
		$options = array();
		
		$menu = JMenu::getInstance('site', $options);
		
		if (JError::isError($menu)) {
			$null = null;
			return $null;
		}
		
		return $menu;
	}

    public function getCategory($id) {
        static $cache = array();

        if (!isset($cache[$id])) {
            $cache[$id] = MijoDB::loadObject("SELECT * FROM #__mijovideos_categories WHERE id = {$id} AND published = 1");
        }

        if (!is_object($cache[$id])) {
            $row = MijoVideos::getTable('MijovideosCategories');
            $row->load($id);

            $cache[$id] = $row;
        }

        return $cache[$id];
    }

    public function getVideoCategory($id) {
        static $cache = array();

        if (!isset($cache[$id])) {
            $cache[$id] = MijoDB::loadObject("SELECT c.* FROM #__mijovideos_categories AS c, #__mijovideos_video_categories AS vc WHERE vc.video_id = {$id} AND c.id = vc.category_id");
        }

        if (!is_object($cache[$id])) {
            $row = MijoVideos::getTable('MijovideosCategories');
            $row->load($id);

            $cache[$id] = $row;
        }

        return $cache[$id];
    }

    public function getCategories($id, $is_video = false) {
        $categories = array();

        if ($is_video == true) {
            $id = $this->getVideoCategory($id)->id;
        }

        while ($id != 0) {
            $cat = $this->getCategory($id);

            if (empty($cat)) {
                break;
            }

            $categories[] = $cat;

            $id = $cat->parent;
        }

        return $categories;
    }
	
    public function replaceLoop($search, $replace, $text) {
        $count = 0;
		
		if (!is_string($text)) {
			return $text;
		}
		
		while ((strpos($text, $search) !== false) && ($count < 10)) {
            $text = str_replace($search, $replace, $text);
			$count++;
        }

        return $text;
    }

    public function getAccessLevels() {
        static $levels;

        if (!isset($levels)) {
            $levels = MijoDB::loadObjectList("SELECT id, title FROM #__viewlevels", 'id');
        }

        return $levels;
   	}

    public function getLanguages() {
        jimport('joomla.language.helper');
        $langs = JLanguageHelper::getLanguages('lang_code');

        return $langs;
    }

    public function getFilesizeFromNumber($size) {
        $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
    }
	
	public function storeConfig($config) {
		$config = $config->toString();
		
		$db = JFactory::getDBO();
		$db->setQuery('UPDATE `#__extensions` SET `params` = '.$db->Quote($config).' WHERE `element` = "com_mijovideos" AND `type` = "component"');
		$db->query();
	}
	
	public function getParam($text, $param) {
		$params = new JRegistry($text);
		return $params->get($param);
	}
	
	public function storeParams($table, $id, $db_field, $new_params) {
		$row = MijoVideos::getTable($table);
		if (!$row->load($id)) {
			return false;
		}
		
		$params = new JRegistry($row->$db_field);
		
		foreach ($new_params as $name => $value) {
			$params->set($name, $value);
		}
		
		$row->$db_field = $params->toString();
		
		if (!$row->check()) {
			return false;
		}
		
		if (!$row->store()) {
			return false;
		}
	}
	
	public function setData($table, $id, $db_field, $new_field) {
		$row = MijoVideos::getTable($table);
		if (!$row->load($id)) {
			return false;
		}
		$row->$db_field = $new_field;	

		if (!$row->check()) {
			return false;
		}
		
		if (!$row->store()) {
			return false;
		}
	}

    public function getPackageFromUpload($userfile) {
        # Make sure that file uploads are enabled in php
        if (!(bool) ini_get('file_uploads')) {
            JError::raiseWarning(100, JText::_('WARNINSTALLFILE'));
            return false;
        }

        # Make sure that zlib is loaded so that the package can be unpacked
        if (!extension_loaded('zlib')) {
            JError::raiseWarning(100, JText::_('WARNINSTALLZLIB'));
            return false;
        }

        # If there is no uploaded file, we have a problem...
        if (!is_array($userfile) ) {
            JError::raiseWarning(100, JText::_('No file selected'));
            return false;
        }

        # Check if there was a problem uploading the file.
        if ( $userfile['error'] || $userfile['size'] < 1 ) {
            JError::raiseWarning(100, JText::_('WARNINSTALLUPLOADERROR'));
            return false;
        }

        # Build the appropriate paths
        $JoomlaConfig = JFactory::getConfig();

        $tmp_dest = $JoomlaConfig->get('tmp_path').'/'.$userfile['name'];

        $tmp_src  = $userfile['tmp_name'];

        # Move uploaded file
        jimport('joomla.filesystem.file');
        $uploaded = JFile::upload($tmp_src, $tmp_dest);

        if (!$uploaded) {
            JError::raiseWarning('SOME_ERROR_CODE', '<br /><br />' . JText::_('File not uploaded, please, make sure that your "MijoVideos => Configuration => Personal ID" and/or the "Global Configuration => Server => Path to Temp-folder" field has a valid value.') . '<br /><br /><br />');
            return false;
        }

        # Unpack the downloaded package file
        $package = self::unpack($tmp_dest);

        # Delete the package file
        JFile::delete($tmp_dest);

        return $package;
    }

    public function unpack($p_filename) {
        # Path to the archive
        $archivename = $p_filename;

        # Temporary folder to extract the archive into
        $tmpdir = uniqid('install_');

        # Clean the paths to use for archive extraction
        $extractdir = JPath::clean(dirname($p_filename).'/'.$tmpdir);
        $archivename = JPath::clean($archivename);

        $package = array();
        $package['dir'] = $extractdir;

        # do the unpacking of the archive
        $package['res'] = JArchive::extract($archivename, $extractdir);

        return $package;
    }

    public function getMijovideosIcon($link, $image, $text) {
    	$lang = JFactory::getLanguage();

    	$div_class = 'class="icon-wrapper"';
    	?>
    	<div <?php echo $div_class; ?> style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
    		<div class="icon">
    			<a href="<?php echo $link; ?>">
    				<img src="<?php echo JUri::root(true); ?>/administrator/components/com_mijovideos/assets/images/<?php echo $image; ?>" alt="<?php echo $text; ?>" />
    				<span><?php echo $text; ?></span>
    			</a>
    		</div>
    	</div>
    	<?php
    }

    public function getPackageFromServer($url) {
        # Make sure that file uploads are enabled in php
        if (!(bool) ini_get('file_uploads')) {
            JError::raiseWarning('1001', JText::_('Your PHP settings does not allow uploads'));
            return false;
        }

        # Make sure that zlib is loaded so that the package can be unpacked
        if (!extension_loaded('zlib')) {
            JError::raiseWarning('1001', JText::_('The PHP extension ZLIB is not loaded, file cannot be unziped'));
            return false;
        }

        # Get temp path
        $JoomlaConfig = JFactory::getConfig();

        $tmp_dest = $JoomlaConfig->get('tmp_path');

        $url = str_replace('http://mijosoft.com/', '', $url);
        $url = str_replace('https://mijosoft.com/', '', $url);
        $url = 'http://mijosoft.com/'.$url;

        # Grab the package
        $data = $this->getRemoteData($url);

        $target = $tmp_dest.'/mijovideos_upgrade.zip';

        # Write buffer to file
        $written = JFile::write($target, $data);

        if (!$written) {
            JError::raiseWarning('SOME_ERROR_CODE', '<br /><br />' . JText::_('File not uploaded, please, make sure that your "MijoVideos => Configuration => Personal ID" and/or the "Global Configuration=>Server=>Path to Temp-folder" field has a valid value.') . '<br /><br /><br />');
            return false;
        }

        $p_file = basename($target);

        # Was the package downloaded?
        if (!$p_file) {
            JError::raiseWarning('SOME_ERROR_CODE', JText::_('Invalid Personal ID'));
            return false;
        }

        # Unpack the downloaded package file
        $package = self::unpack($tmp_dest.'/'.$p_file);

        if (!$package) {
            JError::raiseWarning('SOME_ERROR_CODE', JText::_('An error occured, please, make sure that your "MijoVideos => Configuration => Personal ID" and/or the "Global Configuration=>Server=>Path to Temp-folder" field has a valid value.'));
            return false;
        }

        # Delete the package file
        JFile::delete($tmp_dest.'/'.$p_file);

        return $package;
    }

    public function getUsers() {
        return MijoDB::LoadObjectList("SELECT * FROM #__users");
    }

    public function getRemoteVersion() {
        $version = '?.?.?';

        $components = $this->getRemoteData('http://mijosoft.com/index.php?option=com_mijoextensions&view=xml&format=xml&catid=1');

        if (!strstr($components, '<?xml version="1.0" encoding="UTF-8" ?>')) {
            return $version;
        }

        $manifest = simplexml_load_string($components, 'SimpleXMLElement');

        if (is_null($manifest)) {
            return $version;
        }

        $category = $manifest->category;
        if (!($category instanceof SimpleXMLElement) or (count($category->children()) == 0)) {
            return $version;
        }

        foreach ($category->children() as $component) {
            $option = (string)$component->attributes()->option;
            $compability = (string)$component->attributes()->compability;

            if (($option == 'com_mijovideos') and ($compability == 'all' or $compability == '3.0' or $compability == '1.6_3.0')) {
                $version = trim((string)$component->attributes()->version);
                break;
            }
        }

        return $version;
    }
	
	public function getRemoteData($url) {
		$user_agent = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)";
		$data = false;

		# cURL
		if (extension_loaded('curl')) {
			$process = @curl_init($url);

            @curl_setopt($process, CURLOPT_HEADER, false);
            @curl_setopt($process, CURLOPT_USERAGENT, $user_agent);
            @curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
            @curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
            @curl_setopt($process, CURLOPT_AUTOREFERER, true);
            @curl_setopt($process, CURLOPT_FAILONERROR, true);
            @curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
            @curl_setopt($process, CURLOPT_TIMEOUT, 10);
            @curl_setopt($process, CURLOPT_CONNECTTIMEOUT, 10);
            @curl_setopt($process, CURLOPT_MAXREDIRS, 20);

            $data = @curl_exec($process);

            @curl_close($process);
			
			return $data;
		}

		# fsockopen
		if (function_exists('fsockopen')) {
			$errno = 0;
			$errstr = '';
			
			$url_info = parse_url($url);
			if($url_info['host'] == 'localhost')  {
				$url_info['host'] = '127.0.0.1';
			}

			# Open socket connection
			$fsock = @fsockopen($url_info['scheme'].'://'.$url_info['host'], 80, $errno, $errstr, 5);
		
			if ($fsock) {				
				@fputs($fsock, 'GET '.$url_info['path'].(!empty($url_info['query']) ? '?'.$url_info['query'] : '').' HTTP/1.1'."\r\n");
				@fputs($fsock, 'HOST: '.$url_info['host']."\r\n");
				@fputs($fsock, "User-Agent: ".$user_agent."\n");
				@fputs($fsock, 'Connection: close'."\r\n\r\n");
		
				# Set timeout
				@stream_set_blocking($fsock, 1);
				@stream_set_timeout($fsock, 5);
				
				$data = '';
				$passed_header = false;
				while (!@feof($fsock)) {
					if ($passed_header) {
						$data .= @fread($fsock, 1024);
					} else {
						if (@fgets($fsock, 1024) == "\r\n") {
							$passed_header = true;
						}
					}
				}
				
				#  Clean up
				@fclose($fsock);
				
				# Return data
				return $data;
			}
		}

		# fopen
		if (function_exists('fopen') && ini_get('allow_url_fopen')) {
			# Set timeout
			if (ini_get('default_socket_timeout') < 5) {
				ini_set('default_socket_timeout', 5);
			}
			
			@stream_set_blocking($handle, 1);
			@stream_set_timeout($handle, 5);
			@ini_set('user_agent',$user_agent);
			
			$url = str_replace('://localhost', '://127.0.0.1', $url);
			
			$handle = @fopen($url, 'r');
			
			if ($handle) {
				$data = '';
				while (!feof($handle)) {
					$data .= @fread($handle, 8192);
				}
				
				# Clean up
				@fclose($handle);
			
				# Return data
				return $data;
			}
		}
		
		# file_get_contents
		if (function_exists('file_get_contents') && ini_get('allow_url_fopen')) {
			$url = str_replace('://localhost', '://127.0.0.1', $url);
			@ini_set('user_agent',$user_agent);
			$data = @file_get_contents($url);
			
			# Return data
			return $data;
		}
		
		return $data;
	}
	
	public function getXmlText($file, $variable) {
		jimport('joomla.filesystem.file');
        
		$value = '';
		
		if (JFile::exists($file)) {
            $xml = simplexml_load_file($file, 'SimpleXMLElement');

            if (is_null($xml) || !($xml instanceof SimpleXMLElement)) {
                return $value;
            }

            $value = $xml->$variable;
		}
		
		return $value;
    }

    public function trigger($function, $args = array(), $folder = 'mijovideos') {
        jimport('joomla.plugin.helper');

        JPluginHelper::importPlugin($folder);
        $dispatcher = JDispatcher::getInstance();
        $result = $dispatcher->trigger($function, $args);

        return $result;
    }

    public function triggerContentPlg($text) {
        $config = $this->getConfig();

        $item = new stdClass();
        $item->id = null;
        $item->rating = null;
        $item->rating_count = null;
        $item->text = $text;

        $params = $config;
        $limitstart = JRequest::getInt('limitstart');

        $this->trigger('onContentPrepare', array('com_mijovideos.video', &$item, &$params, $limitstart), 'content');

        return $item->text;
    }

    public function plgEnabled($folder, $name) {
        static $status = array();

        if (!isset($status[$folder][$name])) {
            jimport('joomla.plugin.helper');
            $status[$folder][$name] = JPluginHelper::isEnabled($folder, $name);
        }

        return $status[$folder][$name];
    }

    public function getPlugin($name, $folder = 'mijovideos') {
        static $cache = array();

        if (!isset($cache[$folder][$name])) {
            $file = JPATH_ROOT . '/plugins/'.$folder.'/'.$name.'/'.$name.'.php';

            if (!$this->plgEnabled($folder, $name) or !file_exists($file)) {
                $cache[$folder][$name] = null;

                return $cache[$folder][$name];
            }

            require_once($file);

            $plugin = JPluginHelper::getPlugin($folder, $name);
            $params = new JRegistry(@$plugin->params);

            $subject = JDispatcher::getInstance();

            $config['name'] = $name;
            $config['type'] = $folder;
            $config['params'] = $params;
            $class = 'plg'.ucfirst($folder).ucfirst($name);

            $cache[$folder][$name] = new $class($subject, $config);
        }

        return $cache[$folder][$name];
    }

    public function isAjax($output = '') {
        $is_ajax = false;

        $tmpl = JRequest::getWord('tmpl');
        $format = JRequest::getWord('format');

        if ($tmpl == 'component' or $format == 'raw') {
            $is_ajax = true;
        }
        else if (!empty($output)) {
            if ($this->isJson($output)) {

                $is_ajax = true;

                JRequest::setVar('format', 'raw');
                JRequest::setVar('tmpl', 'component');
            }
        }

        return $is_ajax;
    }

    public function isJson($string) {
		$status = false;

		if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
			$a = json_decode($string);
			$status = (json_last_error() == JSON_ERROR_NONE);
		}
		else {
			if (substr($string, 0, 8) == '{"guid":') {
				$status = true;
			}
		}

		return $status;
    }

    public function isMijosefInstalled() {
        static $status;

        if (!isset($status)) {
            $status = true;

            $file = JPATH_ADMINISTRATOR.'/components/com_mijosef/library/mijosef.php';
            if (!file_exists($file)) {
                $status = false;

                return $status;
            }

            require_once($file);

            if (Mijosef::getConfig()->mode == 0) {
                $status = false;
            }
        }

        return $status;
    }

    public function isSh404sefInstalled() {
        static $status;

        if (!isset($status)) {
            $status = true;

            $file = JPATH_ADMINISTRATOR.'/components/com_sh404sef/sh404sef.class.php';
            if (!file_exists($file)) {
                $status = false;

                return $status;
            }

            require_once($file);

            if (Sh404sefFactory::getConfig()->Enabled == 0) {
                $status = false;
            }
        }

        return $status;
    }

    public function isJoomsefInstalled() {
        static $status;

        if (!isset($status)) {
            $status = true;

            $file = JPATH_ADMINISTRATOR.'/components/com_sh404sef/classes/config.php';
            if (!file_exists($file)) {
                $status = false;

                return $status;
            }

            require_once($file);

            if (!SEFConfig::getConfig()->enabled) {
                $status = false;
            }
        }

        return $status;
    }

    public function getRadioList($name, $selected, $attrs = '', $id = false, $old_style = false, $text_1 = 'JYES', $text_0 = 'JNO') {
        if (MijoVideos::is30()) {
            if (empty($attrs)) {
                $attrs = 'class="inputbox" size="2"';
            }

            if ($old_style == true) {
                $arr = array(JHtml::_('select.option', 2, JText::_($text_1)), JHtml::_('select.option', 1, JText::_($text_0)));
            }
            else {
                $arr = array(JHtml::_('select.option', 1, JText::_($text_1)), JHtml::_('select.option', 0, JText::_($text_0)));
            }

            $output = JHtml::_('select.radiolist', $arr, $name, $attrs, 'value', 'text', (int) $selected, $id);

            $html  = '<fieldset class="radio btn-group">';
            $html .= str_replace(array('<div class="controls">', '</div>'), '', $output);
            $html .= '</fieldset>';
        }
        else {
            $html = JHtml::_('select.booleanlist', $name, 'class="inputbox"', $selected);
        }

        return $html;
    }

    public function buildCategoryDropdown($selected, $name="parent", $onChange=true) {
        $db = JFactory::getDBO();
        $db->setQuery("SELECT id, parent, parent AS parent_id, title FROM #__mijovideos_categories");
        $rows = $db->loadObjectList();

        $children = array();
        if ($rows) {
            # first pass - collect children
            foreach ($rows as $v) {
                $pt = $v->parent;
                $list = @$children[$pt] ? $children[$pt] : array();
                array_push( $list, $v );
                $children[$pt] = $list;
            }
        }

        $list = JHtml::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);

        $options = array();
        $options[] = JHtml::_('select.option', '0', JText::_('COM_MIJOVIDEOS_SELECT_CATEGORY'));
        foreach ($list as $item) {
            $options[] = JHtml::_('select.option', $item->id, '&nbsp;&nbsp;&nbsp;'. $item->treename);
        }

        if ($onChange) {
            return JHtml::_('select.genericlist', $options, $name, array(
                                                                    'option.text.toHtml' => false ,
                                                                    'option.text' => 'text',
                                                                    'option.value' => 'value',
                                                                    'list.attr' => 'class="inputbox" onchange="submit();"',
                                                                    'list.select' => $selected)
                            );
        }
        else {
            return JHtml::_('select.genericlist', $options, $name, array(
                                                                    'option.text.toHtml' => false ,
                                                                    'option.text' => 'text',
                                                                    'option.value' => 'value',
                                                                    'list.attr' => 'class="inputbox" ',
                                                                    'list.select' => $selected)
                            );
        }
    }

    public function buildParentCategoryDropdown($row) {
   		$db = JFactory::getDBO();

   		$sql = "SELECT id, parent, parent AS parent_id, title FROM #__mijovideos_categories WHERE id <> 1";

        if ($row->id) {
   			$sql .= ' AND id != '.$row->id;
        }

   		$db->setQuery($sql);
   		$rows = $db->loadObjectList();

        if (!$row->parent) {
   			$row->parent = 0;
   		}

   		$children = array();
   		if ($rows) {
   			# first pass - collect children
   			foreach ($rows as $v) {
   				$pt = $v->parent;
   				$list = @$children[$pt] ? $children[$pt] : array();
   				array_push($list, $v);
   				$children[$pt] = $list;
   			}
   		}

   		$list = JHtml::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);

   		$options = array();
   		$options[] = JHtml::_('select.option', '0', JText::_('JGLOBAL_ROOT_PARENT'));
   		foreach ($list as $item) {
   			$options[] = JHtml::_('select.option', $item->id, '&nbsp;&nbsp;&nbsp;'. $item->treename);
   		}

   	    return JHtml::_('select.genericlist', $options, 'parent', array(
                                                                      'option.text.toHtml' => false,
                                                                      'option.text' => 'text',
                                                                      'option.value' => 'value',
                                                                      'list.attr' => ' class="inputbox" ',
                                                                      'list.select' => $row->parent)
                           );
   	}

    public function getGDVersion($user_ver = 0) {
        if (!extension_loaded('gd')) {
            return 0;
        }

        static $gd_ver = 0;

        # just accept the specified setting if it's 1.
        if ($user_ver == 1) {
            $gd_ver = 1;
            return $gd_ver;
        }

        # use static variable if function was cancelled previously.
        if (($user_ver != 2) and ($gd_ver > 0)) {
            return $gd_ver;
        }

        # use the gd_info() function if posible.
        if (function_exists('gd_info')) {
            $ver_info = gd_info();
            $match = null;
            preg_match('/\d/', $ver_info['GD Version'], $match);
            $gd_ver = $match[0];

            return $gd_ver;
        }

        # if phpinfo() is disabled use a specified / fail-safe choice...
        if (preg_match('/phpinfo/', ini_get('disable_functions'))) {
            if ($user_ver == 2) {
                $gd_ver = 2;
                return $gd_ver;
            }
            else {
                $gd_ver = 1;
                return $gd_ver;
            }
        }

        # ...otherwise use phpinfo().
        ob_start();
        phpinfo(8);
        $info = ob_get_contents();
        ob_end_clean();
        $info = stristr($info, 'gd version');
        $match = null;
        preg_match('/\d/', $info, $match);
        $gd_ver = $match[0];

        return $gd_ver;
    }

    public function resizeImage($srcFile, $desFile, $thumbWidth, $thumbHeight, $quality) {
        $app = JFactory::getApplication();

        $imgTypes = array( 1 => 'GIF', 2 => 'JPG', 3 => 'PNG', 4 => 'SWF', 5 => 'PSD', 6 => 'BMP', 7 => 'TIFF', 8 => 'TIFF', 9 => 'JPC', 10 => 'JP2', 11 => 'JPX', 12 => 'JB2', 13 => 'SWC', 14 => 'IFF');
        $imgInfo = getimagesize($srcFile);

        if ($imgInfo == null) {
            $app->enqueueMessage(JText::_('COM_MIJOVIDEOS_IMAGE_NOT_FOUND', 'error'));
            return false;
        }

        $type = strtoupper($imgTypes[$imgInfo[2]]) ;
        $gdSupportedTypes = array('JPG', 'PNG', 'GIF');

        if (!in_array($type, $gdSupportedTypes)) {
            $app->enqueueMessage(JText::_('COM_MIJOVIDEOS_ONLY_SUPPORT_TYPES'), 'error');
            return false;
        }

        $srcWidth = $imgInfo[0];
        $srcHeight = $imgInfo[1];

        //Should canculate the ration
        $ratio =  max($srcWidth/$thumbWidth, $srcHeight/$thumbHeight , 1.0);
        $desWidth = (int) $srcWidth / $ratio;
        $desHeight = (int) $srcHeight / $ratio;

        $gd_version = $this->getGDVersion();

        if ($gd_version <= 0) {
            //Simply copy the source to target folder
            jimport('joomla.filesystem.file');
            JFile::copy($srcFile, $desFile);
            return false;
        }
        else if ($gd_version == 1) {
            if ($type == 'JPG') {
                $srcImage =  imagecreatefromjpeg($srcFile);
            }
            elseif ($type == 'PNG') {
                $srcImage = imagecreatefrompng($srcFile);
            }
            else {
                $srcImage = imagecreatefromgif($srcFile);
            }

            $desImage = imagecreate($desWidth, $desHeight);

            imagecopyresized($desImage, $srcImage, 0, 0, 0, 0, $desWidth, $desHeight, $srcWidth, $srcHeight);
            imagejpeg($desImage, $desFile, $quality);
            imagedestroy($srcImage);
            imagedestroy($desImage);
        }
        else {
            if (!function_exists('imagecreatefromjpeg')) {
                echo JText::_('GD_LIB_NOT_INSTALLED');
                return false;
            }

            if (!function_exists('imagecreatetruecolor')) {
                echo JText::_('GD2_LIB_NOT_INSTALLED');
                return false ;
            }

            if ($type == 'JPG') {
                $srcImage =  imagecreatefromjpeg($srcFile);
            }
            elseif ($type == 'PNG') {
                $srcImage = imagecreatefrompng($srcFile);
            }
            else {
                $srcImage = imagecreatefromgif($srcFile);
            }

            if (!$srcImage) {
                echo JText::_('JA_INVALID_IMAGE');
                return false;
            }

            $desImage = imagecreatetruecolor($desWidth, $desHeight);

            imagecopyresampled($desImage, $srcImage, 0, 0, 0, 0, $desWidth, $desHeight, $srcWidth, $srcHeight);
            imagejpeg($desImage, $desFile, $quality);
            imagedestroy($srcImage);
            imagedestroy($desImage);
        }

        return true;
    }

    public function getUserInputbox($user_id, $field_name = 'user_id') {
        $dashboard = '';
        if ($this->isDashboard()) {
            $dashboard = '&amp;dashboard=1';
        }

        # Initialize variables.
   		$html = array();
   		$link = 'index.php?option=com_users&amp;view=users&amp;layout=modal&amp;tmpl=component&amp;field=user_id'.$dashboard;

   		# Initialize some field attributes.
   		$attr = ' class="inputbox"';

   		# Load the modal behavior script.
   		JHtml::_('behavior.modal', 'a.modal_user_id');

   		# Build the script.
   		$script = array();
   		$script[] = '	function jSelectUser_user_id(id, title) {';
   		$script[] = '		var old_id = document.getElementById("'.$field_name.'").value;';
   		$script[] = '		if (old_id != id) {';
   		$script[] = '			document.getElementById("'.$field_name.'").value = id;';
   		$script[] = '			document.getElementById("user_id_name").value = title;';
   		$script[] = '		}';
   		$script[] = '		SqueezeBox.close();';
   		$script[] = '	}';

   		# Add the script to the document head.
   		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

   		# Load the current username if available.
   		$table = JTable::getInstance('user');
   		if ($user_id) {
   			$table->load($user_id);
   		}
   		else {
   			$table->name = '' ;
   		}

   		# Create a dummy text field with the user name.
   		$html[] = '<div class="fltlft">';
   		$html[] = '<input type="text" id="user_id_name"' . ' value="' . htmlspecialchars($table->name, ENT_COMPAT, 'UTF-8') . '"' . ' disabled="disabled"' . $attr . ' />&nbsp;&nbsp;';
   		$html[] = '</div>';

   		# Create the user select button.
   		$html[] = '<div class="button2-left">';
   		$html[] = '<div class="blank">';
   		$html[] = '<a class="modal_user_id btn btn-primary" title="' . JText::_('JLIB_FORM_CHANGE_USER') . '"' . ' href="' . $link . '"' . ' rel="{handler: \'iframe\', size: {x: 800, y: 500}}">';
   		$html[] = '	' . JText::_('JLIB_FORM_CHANGE_USER') . '</a>';
   		$html[] = '</div>';
   		$html[] = '</div>';

   		# Create the real field, hidden, that stored the user id.
   		$html[] = '<input type="hidden" id="'.$field_name.'" name="'.$field_name.'" value="'.$user_id.'" />';

   		return implode("\n", $html);
   	}

    public function getChannelInputbox($channel_id, $field_name = 'created_by') {
        $dashboard = '';
        if ($this->isDashboard()) {
            $dashboard = '&amp;dashboard=1';
        }

        # Initialize variables.
        $html = array();
        $link = JRoute::_('index.php?option=com_mijovideos&view=channels&layout=modal&tmpl=component&field=channel_id'.$dashboard);

        # Initialize some field attributes.
        $attr = ' class="inputbox"';

        # Load the modal behavior script.
        JHtml::_('behavior.modal', 'a.modal_channel_id');

        # Build the script.
        $script = array();
        $script[] = '	function jSelectChannel_channel_id(id, title) {';
        $script[] = '		var old_id = document.getElementById("'.$field_name.'").value;';
        $script[] = '		if (old_id != id) {';
        $script[] = '			document.getElementById("'.$field_name.'").value = id;';
        $script[] = '			document.getElementById("channel_id_name").value = title;';
        $script[] = '		}';
        $script[] = '		SqueezeBox.close();';
        $script[] = '	}';

        # Add the script to the document head.
        JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

        # Load the current username if available.
        $table = JTable::getInstance('MijovideosChannels','Table');
        if ($channel_id) {
            $table->load($channel_id);
        }
        else {
            $table->name = '' ;
        }

        # Create a dummy text field with the user name.
        $html[] = '<div class="fltlft">';
        $html[] = '<input type="text" id="channel_id_name"' . ' value="' . htmlspecialchars($table->title, ENT_COMPAT, 'UTF-8') . '"' . ' disabled="disabled"' . $attr . ' />&nbsp;&nbsp;';
        $html[] = '</div>';

        # Create the user select button.
        $html[] = '<div class="button2-left">';
        $html[] = '<div class="blank">';
        $html[] = '<a class="modal_channel_id btn btn-primary" title="' . JText::_('COM_MIJOVIDEOS_CHANGE_CHANNEL') . '"' . ' href="' . $link . '"' . ' rel="{handler: \'iframe\', size: {x: 800, y: 500}}">';
        $html[] = '	' . JText::_('COM_MIJOVIDEOS_CHANGE_CHANNEL') . '</a>';
        $html[] = '</div>';
        $html[] = '</div>';

        # Create the real field, hidden, that stored the channel id.
        if ($channel_id) {
            $html[] = '<input type="hidden" id="'.$field_name.'" name="'.$field_name.'" value="'.$channel_id.'" />';
        } else {
            $user_id = JFactory::getUser()->id;
            $channel_id = MijoVideos::get('channels')->getDefaultChannel()->id;
            $html[] = '<input type="hidden" id="'.$field_name.'" name="'.$field_name.'" value="'.$channel_id.'" />';
        }
        return implode("\n", $html);
    }

    public function getVideoInputbox($channel_id, $field_name = 'created_by') {
        # Initialize variables.
        $html = array();
        $link = 'index.php?option=com_mijovideos&amp;view=video&amp;layout=modal&amp;tmpl=component&amp;field=video_id';

        # Load the modal behavior script.
        JHtml::_('behavior.modal', 'a.modal_video_id');

        # Build the script.
        $script = array();
        $script[] = '	function jSelectChannel_video_id(id, title) {';
        $script[] = '		var old_id = document.getElementById("'.$field_name.'").value;';
        $script[] = '		if (old_id != id) {';
        $script[] = '			document.getElementById("'.$field_name.'").value = id;';
        $script[] = '			document.getElementById("video_id_name").value = title;';
        $script[] = '		}';
        $script[] = '		SqueezeBox.close();';
        $script[] = '	}';

        # Add the script to the document head.
        JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

        # Create the user select button.
        $html[] = '<div class="button2-left">';
        $html[] = '<div class="blank">';
        $html[] = '<a class="modal_video_id btn btn-primary" title="' . JText::_('COM_MIJOVIDEOS_CHANGE_CHANNEL') . '"' . ' href="' . $link . '"' . ' rel="{handler: \'iframe\', size: {x: 800, y: 500}}">';
        $html[] = '	' . JText::_('COM_MIJOVIDEOS_CHANGE_CHANNEL') . '</a>';
        $html[] = '</div>';
        $html[] = '</div>';

        return implode("\n", $html);
    }

    function secondsToTime($seconds, $isString = false) {
        $ret = "";
        /*** get the days ***/
        $days = intval(intval($seconds) / (3600*24));
        if ($days > 0) {
            $days == 1 ? $ret .= "$days ".JText::_('COM_MIJOVIDEOS_DAY')." " : $ret .= "$days ".JText::_('COM_MIJOVIDEOS_DAYS')." ";
        }
        /*** get the hours ***/
        $hours = (intval($seconds) / 3600) % 24;
        if ($isString) {
            if ($hours > 0) {
                $hours == 1 ? $ret .= "$hours ".JText::_('COM_MIJOVIDEOS_HOUR')." " : $ret .= "$hours ".JText::_('COM_MIJOVIDEOS_HOURS')." ";
            }
        } else {
            if($hours > 0) {
                $ret .= $hours;
            }
        }

        /*** get the minutes ***/
        $minutes = (intval($seconds) / 60) % 60;
        if ($isString) {
            if ($minutes > 0 && $days == 0) {
                $minutes == 1 ? $ret .= "$minutes ".JText::_('COM_MIJOVIDEOS_MINUTE')." " : $ret .= "$minutes ".JText::_('COM_MIJOVIDEOS_MINUTES')." ";
            }
        } else {
            if($minutes >= 0 and $minutes < 10 and !empty($ret)) {
                $ret .= ':0'.$minutes;
            } elseif (empty($ret)) {
                $ret .= $minutes;
            } elseif (!empty($ret) and $minutes >= 10) {
                $ret .= ':'.$minutes;
            }
        }

        /*** get the seconds ***/
        $seconds = intval($seconds) % 60;
        if ($isString) {
            if ($seconds > 0 && $days == 0 && $hours == 0) {
                $seconds == 1 ? $ret .= "$seconds ".JText::_('COM_MIJOVIDEOS_SECOND')." " : $ret .= "$seconds ".JText::_('COM_MIJOVIDEOS_SECONDS')." ";
            }
        } else {
            if($seconds >= 0 and $seconds < 10) {
                $ret .= ':0'.$seconds;
            } else {
                $ret .= ':'.$seconds;
            }
        }

        return $ret;
    }

    function redirectWithReturn() {
        $return = $_SERVER['HTTP_REFERER'];
        $url  = 'index.php?option=com_users';
        $url .= '&return='.urlencode(base64_encode($return));
        return htmlspecialchars_decode(JRoute::_($url));
    }
	
	public function cleanText($text) {
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $text = preg_replace('#<script[^>]*>.*?</script>#si', ' ', $text);
        $text = preg_replace('#<style[^>]*>.*?</style>#si', ' ', $text);
        $text = preg_replace('#<!.*?(--|]])>#si', ' ', $text);
        $text = preg_replace('#<[^>]*>#i', ' ', $text);
        $text = preg_replace('/{.+?}/', '', $text);
        $text = preg_replace("'<(br[^/>]*?/|hr[^/>]*?/|/(div|h[1-6]|li|p|td))>'si", ' ', $text);

        $text = preg_replace('/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/', ' ', $text);

        $text = preg_replace('/\s\s+/', ' ', $text);
        $text = preg_replace('/\n\n+/s', ' ', $text);
        $text = preg_replace('/\s/u', ' ', $text);

        $text = strip_tags($text);

        return $text;
    }

    public function cleanUrl($url) {
        $url = $this->cleanText($url);

        $bad_chars = array('#', '>', '<', '\\', '="', 'px;', 'onmouseover=');
        $url = trim(str_replace($bad_chars, '', $url));

        jimport('joomla.filter.input');
        JFilterInput::getInstance(array('br', 'i', 'em', 'b', 'strong'), array(), 0, 0, 1)->clean($url);

        return $url;
    }

    public function getActiveUrl() {
        return $this->cleanUrl(JFactory::getURI()->toString());
    }

    public function getHost($url) {
        $host = null;
        $pattern = '`.*?((http|https|ftp)://[\w#$&+,\/:;=?@.-]+)[^\w#$&+,\/:;=?@.-]*?`i';
        if (preg_match($pattern, $url, $matches)) {
            $url = $matches[1];
            $host = parse_url($url, PHP_URL_HOST);
            $patterns[] = '#^www\.|\.com$#';
            $patterns[] = '#^v\.|\.com$#'; //v.youku.com
            $patterns[] = '#^www\.|\.co$#'; // vine.co
            $patterns[] = '#^www\.|\.tv$#'; // wat.tv, blip.tv
            $patterns[] = '#\.|\.com$#'; //video.foxnews.com
            $patterns[] = '#^www\.|\.be$#'; //youtu.be
            $host = preg_replace($patterns, '$1', $host);
            if ($host == 'youtu') { //youtu.be
                $host = 'youtube';
            }

            if (strpos($host, 'vidsio') !== false) {
                $host = 'sproutvideo';
            }			

            if (strpos($host, 'amazonaws') !== false) {
                $host = null;
            }
        }

        return $host;
    }

    public function getVideoSize($location) {
        $config = MijoVideos::getConfig();
        if (!file_exists($location)) {
	        MijoVideos::log(JText::_('COM_MIJOVIDEOS_ERROR_SOURCE_VIDEO_NOT_EXIST'));
	        return false;
        }

        if (substr(PHP_OS, 0, 3) == "WIN") {
            $command = "\"" . $config->get('ffmpeg_path', '/usr/bin/ffmpeg') . "\" -i $location 2>&1";
            exec($command, $output);
        } else {
            $command = $config->get('ffmpeg_path', '/usr/bin/ffmpeg') . " -i $location 2>&1";
            exec($command, $output);
        }

        MijoVideos::log('FFmpeg : ' . $command);
        MijoVideos::log($output);

        $flatoutput = is_array($output) ? implode("\n", $output) : $output;
        if (empty($flatoutput)) {
	        MijoVideos::log('Flatoutput is empty');
	        return false;
        } else {
            $pos = strpos($flatoutput, "No such file or directory");
            if ($pos !== false) {
	            MijoVideos::log('No such file or directory');
	            return false;
            }

            $pos = strpos($flatoutput, "not found");
            if ($pos !== false) {
	            MijoVideos::log('Not found');
	            return false;
            }

            $pos = strpos($flatoutput, "Permission denied");
            if ($pos !== false) {
	            MijoVideos::log('Permission denied');
	            return false;
            }
        }
        $input_height = 0;

        // Get original size
        if (preg_match('/Stream.*Video:.* (\d+)x(\d+).* (\d+\.\d+|\d+) tbr/', implode("\n", $output), $matches)) {
            $input_height = $matches[2];
        } elseif (preg_match('/Stream.*Video:.* (\d+)x(\d+).* (\d+\.\d+|\d+) tb/', implode("\n", $output), $matches)) {
            $input_height = $matches[2];
        }

        $input_size = 'original';
        $sizes = array(1080, 720, 480, 360 ,240);
        $i = 0;
        foreach ($sizes as $size) {
            if ($input_height >= $size) {
                switch ($i) {
                    case 0:
                        $input_size = 1080;
                        break;
                    case 1:
                        $input_size = 720;
                        break;
                    case 2:
                        $input_size = 480;
                        break;
                    case 3:
                        $input_size = 360;
                        break;
                    case 4:
                        $input_size = 240;
                        break;
                    default:
                        break;
                }
                return $input_size;
            }
            $i++;
        }
        if (empty($input_size)) {
            return 120;
        }
    }

    public function getThumbSize($size) {
        switch ($size) {
            case 1: // Very Small Image
                $size = 75;
                break;
            case 2: // Thumbnail Image
                $size = 100;
                break;
            case 3: // Small Image
                $size = 240;
                break;
            case 4: // Medium Image
                $size = 500;
                break;
            case 5: // Large Image
                $size = 640;
                break;
            case 6: // Very Large Image
                $size = 1024;
                break;
        }
        return $size;
    }

    public function getSize($process_type) {
        $size = null;
        switch ($process_type) {
            case 7:
            case 12:
            case 17:
                $size = 240;
                break;
            case 8:
            case 13:
            case 18:
                $size = 360;
                break;
            case 9:
            case 14:
            case 19:
                $size = 480;
                break;
            case 10:
            case 15:
            case 20:
                $size = 720;
                break;
            case 11:
            case 16:
            case 21:
                $size = 1080;
                break;
            case 100: // HTML5 type
                //$size = $this->getVideoSize();
                break;
        }
        return $size;
    }

    public function getThumbPath($id, $type, $thumb, $size = null) {
        if (empty($size)) {
            $size = $this->getThumbSize($this->config->get('thumb_size'));
        }
        if ($thumb and file_exists(JPATH_ROOT.'/media/com_mijovideos/images/'.$type.'/'.$id.'/'.$size.'/'.$thumb)) {
            $ret = JUri::root().'media/com_mijovideos/images/'.$type.'/'.$id.'/'.$size.'/'.$thumb;
        } else {
            if (strpos($thumb,'http://') !== false or strpos($thumb,'https://') !== false) {
                $ret = $thumb;
            } else {
                if ($type == 'channels') {
                    $ret = JUri::root().'media/com_mijovideos/images/'.$type.'/default/default.jpg';
                } else {
                    $ret = JUri::root().'media/com_mijovideos/images/default/default'.$size.'.jpg';
                }
            }
        }
        return $ret;
    }

    public function getVideoFilePath($id, $size, $source, $fullPath = false) {
        $ret = $root = '';
        if (strpos($source,'http://') !== false or strpos($source,'https://') !== false) {
            $ret = $source;
        } elseif(file_exists(JPATH_ROOT.'/media/com_mijovideos/videos/'.$id.'/'.$size.'/'.$source)) {
            if($fullPath) {
                $root = JUri::root(true);
            }
            $ret =  $root . '/media/com_mijovideos/videos/'.$id.'/'.$size.'/'.$source;
        }

        return $ret;
    }

    public function getWatchLaterButton($id, $override = null) {
        $watch_later_id = $this->getWatchlater()->id;

        $result = null;

        if(!empty($watch_later_id)) {
            $result = $this->checkVideoInPlaylists($watch_later_id, $id);
        }

        if (empty($result) or empty($watch_later_id)) {
            $html = '<button class="video_watch_later_button mijovideos_video'.$id.'" onclick="return false;">';
            if (!empty($override) and $override == 'dailymotion') {
                $html .= '<div class="video_watch_later mijovideos_watch_later'.$watch_later_id.'">'.JText::_('COM_MIJOVIDEOS_WATCH_LATER').'</div>';
            } else {
                $html .= '<div class="video_watch_later mijovideos_watch_later'.$watch_later_id.'">';
                if($override == 'vimeo'){
                    $html .= '<i class="fa fa-clock-o"></i>';
                }
                $html .= '</div>';
            }
            $html .= '</button>';
        } else {
            $html = '<button class="video_added_button mijovideos_video'.$id.'" onclick="return false;">';
            $html .= '<div class="video_added mijovideos_watch_later'.$watch_later_id.'"></div>';
            if($override == 'vimeo'){
                $html .= '<i class="fa fa-clock-o"></i>';
            }
            $html .= '</button>';
        }

        return $html;
    }

    public function checkVideoInPlaylists($playlist_id, $video_id) {
        $result = MijoDB::loadResult("SELECT * FROM #__mijovideos_playlist_videos WHERE playlist_id = {$playlist_id} AND video_id = {$video_id}");

        return $result;
    }

    public function hitsCounter($item_type) {
        $item_id = JRequest::getInt($item_type.'_id');
        MijoVideos::get('controller')->increaseField($item_type.'s', 'hits', 1, 'WHERE `id` = '.$item_id);
        return true;
    }

    public function getWatchlater() {
        $db         = JFactory::getDBO();
        $channel_id = MijoVideos::get('channels')->getDefaultChannel()->id;
		if (!$channel_id) {
            $watch_later = new stdClass();
            $watch_later->id = null;
            return $watch_later;
        }

        $sql = "SELECT * FROM #__mijovideos_playlists WHERE channel_id = {$channel_id} AND type = 1";
        $db->setQuery($sql);
        $watch_later = $db->loadObject();

        return $watch_later;
    }

    public function log($message, $priority) {
	    if (!$this->config->get('log')) {
		    return false;
	    }

	    /*JLog::add($message, $priority, 'mijovideos');*/
	    $trace = debug_backtrace();
	    $file  = JPATH_CACHE . '/mijovideos.log';
	    if (is_array($message)) {
		    foreach ($message as $msg) {
			    file_put_contents($file, date('Y-m-d G:i:s') . ' - ' . $msg . ' line ' . $trace[1]['line'] . ' on ' . $trace[1]['file'] . "\n", FILE_APPEND);
		    }
	    }
	    else {
		    file_put_contents($file, date('Y-m-d G:i:s') . ' - ' . $message . ' line ' . $trace[1]['line'] . ' on ' . $trace[1]['file'] . "\n", FILE_APPEND);
	    }
    }

    public function checkFfmpegInstalled() {
        if (substr(PHP_OS, 0, 3) == "WIN") {
            $command = "\"" . $this->config->get('ffmpeg_path', 'C:\ffmpeg\bin\ffmpeg.exe') . "\" 2>&1";
        } else {
            $command = "which ffmpeg";
        }

        exec($command, $output);

	    MijoVideos::log('FFmpeg : ' . $command);
	    MijoVideos::log($output);

	    if (count($output)) {
            return true;
        }

        return false;
    }
	
	public function findOption() {
		$option = strtolower(JRequest::getCmd('option'));

		$user = JFactory::getUser();
		if (($user->get('guest')) || !$user->authorise('core.login.admin')) {
			$option = 'com_login';
		}

		if (empty($option)) {
			$option = 'com_cpanel';
		}

		if ($args = @$GLOBALS['argv']) { // If script runs on cli
			$option = 'com_mijovideos';
		}

		JRequest::setVar('option', $option);
		return $option;
	}
}