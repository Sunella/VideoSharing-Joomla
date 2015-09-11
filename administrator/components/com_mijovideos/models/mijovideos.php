<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted access');

class MijovideosModelMijovideos extends MijovideosModel {

	public function __construct(){
		parent::__construct('mijovideos');
	}
	
    public function savePersonalID() {
		$pid = trim(JRequest::getVar('pid', '', 'post', 'string'));
		
		if (!empty($pid)) {
			$config = MijoVideos::getConfig();
			$config->set('pid', $pid);

			MijoVideos::get('utility')->storeConfig($config);
		}
	}
	
	public function jusersync() {

        $users = MijoDB::loadObjectList('SELECT id,name FROM `#__users`');

        if (!empty($users)) {
            foreach($users as $user) {
                $user->name = str_replace("'", "\\'", $user->name);
                MijoDB::query("INSERT INTO `#__mijovideos_channels` (`user_id`, `title`, `alias`, `introtext`, `fulltext`, `thumb`, `banner`, `fields`,`likes`, `dislikes`, `hits`, `params`, `ordering`, `access`, `language`, `created`, `modified`, `meta_desc`, `meta_key`, `meta_author`, `share_others`, `featured`, `published`, `default`) VALUES
                ({$user->id}, '{$user->name}', '{$user->name}', '{$user->name}', '', '', '', '', 0, 0, 0, '', '', 1, '*', NOW(), NOW(), '', '', '', 0, 0, 1, 1)");
                $channel_id = MijoDB::insertid();

                # Watch Later
                 MijoDB::query("INSERT INTO `#__mijovideos_playlists` (`channel_id`, `user_id`, `type`, `title`, `alias`, `introtext`, `fulltext`, `thumb`, `fields`,`likes`, `dislikes`, `hits`, `subscriptions`, `params`, `ordering`, `access`, `language`, `created`, `modified`, `meta_desc`, `meta_key`, `meta_author`, `share_others`, `featured`, `published`) VALUES
                ({$channel_id}, {$user->id}, 1, 'Watch Later', '', '', '', '', '', '', 0, 0, 0, '', '', 1, '*', NOW(), NOW(), '', '', '', 0, 0, 1)");

                # Favorite Videos
                MijoDB::query("INSERT INTO `#__mijovideos_playlists` (`channel_id`, `user_id`,  `type`, `title`, `alias`, `introtext`, `fulltext`, `thumb`, `fields`,`likes`, `dislikes`, `hits`, `subscriptions`, `params`, `ordering`, `access`, `language`, `created`, `modified`, `meta_desc`, `meta_key`, `meta_author`, `share_others`, `featured`, `published`) VALUES
                ({$channel_id}, {$user->id}, 2, 'Favorite Videos', '', '', '', '', '', '', 0, 0, 0, '', '', 1, '*', NOW(), NOW(), '', '', '', 0, 0, 1)");
            }

            $channel_id = MijoVideos::get('channels')->getDefaultChannel()->id;
            MijoDB::query('UPDATE `#__mijovideos_videos` SET `channel_id` = '.$channel_id.' WHERE `channel_id` = 0');
            MijoDB::query('UPDATE `#__mijovideos_playlists` SET `channel_id` = '.$channel_id.' WHERE `channel_id` = 0');

            $config = MijoVideos::getConfig();
            $config->set('jusersync', 1);

            MijoVideos::get('utility')->storeConfig($config);
        }
    }
	
	# Check info
    public function getInfo() {
		static $info;
		
		if (!isset($info)) {
			$info = array();
			
			if ($this->config->get('version_checker') == 1){
				$utility = MijoVideos::get('utility');
				$info['version_installed'] = $utility->getMijovideosVersion();
				$info['version_latest'] = $utility->getLatestMijovideosVersion();

				# Set the version status
				$info['version_status'] = version_compare($info['version_installed'], $info['version_latest']);
				$info['version_enabled'] = 1;
			}
			else {
				$info['version_status'] = 0;
				$info['version_enabled'] = 0;
			}
			
			$info['pid'] = $this->config->get('pid');

            $server = array();
            $server[] = array('name' => 'FFmpeg',                       'value' => (MijoVideos::get('utility')->checkFfmpegInstalled()) ? JText::_('JYES') : JText::_('JNO'));
            $server[] = array('name' => 'allow_fileuploads',            'value' => ini_get('file_uploads') ? JText::_('JYES') : JText::_('JNO'));
            $server[] = array('name' => 'upload_max_filesize',          'value' => ini_get('upload_max_filesize'));
            $server[] = array('name' => 'max_input_time',               'value' => ini_get('max_input_time'));
            $server[] = array('name' => 'memory_limit',                 'value' => ini_get('memory_limit'));
            $server[] = array('name' => 'max_execution_time',           'value' => ini_get('max_execution_time'));
            $server[] = array('name' => 'post_max_size',                'value' => ini_get('post_max_size'));
            $server[] = array('name' => 'upload_folder_permission',     'value' => (is_writable(JPATH_ROOT.'/media/com_mijovideos/')) ? JText::_('JYES') : JText::_('JNO'));
            $server[] = array('name' => 'curl',                         'value' => (extension_loaded('curl')) ? JText::_('JYES') : JText::_('JNO'));
            $server[] = array('name' => 'exec',                         'value' => (function_exists('exec')) ? JText::_('JYES') : JText::_('JNO'));
            $server[] = array('name' => 'php-cli',                      'value' => $this->checkPhpCli());
            if($this->config->get('perl_upload')) {
                $server[] = array('name' => 'ubr_upload script',            'value' => ($this->isUrlExist($this->config->get('uber_upload_perl_url'))) ? JText::_('JYES') : JText::_('JNO'));
            }

            $info['server'] = $server;

		}
		
		return $info;
	}
	
    public function getStats() {
		$count = array();

        $where = '';
        if (!MijoVideos::get('acl')->canAdmin()) {
            $user_id = JFactory::getUser()->get('id');

            $where = ' WHERE user_id = '.$user_id;
        }
		
		$count['categories'] = MijoDB::loadResult("SELECT COUNT(*) FROM #__mijovideos_categories");
		$count['channels'] = MijoDB::loadResult("SELECT COUNT(*) FROM #__mijovideos_channels {$where}");
		$count['playlists'] = MijoDB::loadResult("SELECT COUNT(*) FROM #__mijovideos_playlists {$where}");
        $count['videos'] = MijoDB::loadResult("SELECT COUNT(*) FROM #__mijovideos_videos {$where}");
        $count['subscriptions'] = MijoDB::loadResult("SELECT COUNT(*) FROM #__mijovideos_subscriptions {$where}");

		return $count;
	}

    function isUrlExist($url){
        $status = false;
        if (extension_loaded('curl')) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if($code == 200){
                $status = true;
            }else{
                $status = false;
            }
            curl_close($ch);
        }

        return $status;
    }

    function checkPhpCli(){
        $config = MijoVideos::getConfig();
        if (substr(PHP_OS, 0, 3) != "WIN") {
            // @TODO Log if throw an error
            @exec($config->get('php_path', '/usr/bin/php') . " -v 2>&1", $output, $error);
        }
        else {
            @exec('where php.exe', $php_path);
            // @TODO Log if throw an error
            @exec($config->get('php_path', $php_path)." -v", $output , $error);
        }

	    MijoVideos::log('CLI : ');
	    MijoVideos::log($output);
	    MijoVideos::log($error);

        if (strpos($output[0], 'cli') === false) {
            return JText::_('JNO');
        }
        else {
            return JText::_('JYES');
        }
    }
}