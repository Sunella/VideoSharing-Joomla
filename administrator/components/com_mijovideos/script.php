<?php
/**
* @package		MijoVideos
* @copyright	2009-2012 Mijosoft LLC, mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
# No Permission
defined('_JEXEC') or die('Restricted access');

// Import Libraries
jimport('joomla.application.helper');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.installer.installer');

class com_MijovideosInstallerScript {

    private $_current_version = null;
    private $_is_new_installation = true;

    public function preflight($type, $parent) {
        $db = JFactory::getDBO();
        $db->setQuery('SELECT params FROM #__extensions WHERE element = "com_mijovideos" AND type = "component"');
        $config = $db->loadResult();

        if (!empty($config)) {
            $this->_is_new_installation = false;

            $mijovideos_xml = JPATH_ADMINISTRATOR.'/components/com_mijovideos/mijovideos.xml';

            if (JFile::exists($mijovideos_xml)) {
                $xml = simplexml_load_file($mijovideos_xml, 'SimpleXMLElement');
                $this->_current_version = (string)$xml->version;
            }
        }
    }
	
	public function postflight($type, $parent) {
        $db = JFactory::getDBO();
        $src = $parent->getParent()->getPath('source');
		
		#Remove htaccess file to support image feature
		if (JFile::exists(JPATH_ROOT.'/media/com_mijovideos/.htaccess')) {
			JFile::delete(JPATH_ROOT.'/media/com_mijovideos/.htaccess');
		}
		
		require_once(JPATH_ADMINISTRATOR.'/components/com_mijovideos/library/mijovideos.php');

        $status = new JObject();
        $status->adapters 	= array();
        $status->extensions	= array();
        $status->modules 	= array();
        $status->plugins 	= array();
		
		/***********************************************************************************************
		* ---------------------------------------------------------------------------------------------
		* MODULE INSTALLATION SECTION
		* ---------------------------------------------------------------------------------------------
		***********************************************************************************************/
        $modules = array(
            array('title' => 'MijoVideos - Categories', 		'element' => 'mod_mijovideos_categories', 		'client' => 'Site', 			'position' => 'position-7', 		'update' => false),
            array('title' => 'MijoVideos - Videos', 			'element' => 'mod_mijovideos_videos', 			'client' => 'Site', 			'position' => 'position-7', 		'update' => false),
            array('title' => 'MijoVideos - Playlist', 			'element' => 'mod_mijovideos_playlist', 		'client' => 'Site', 			'position' => 'position-7', 		'update' => false),
            array('title' => 'MijoVideos - Channels', 			'element' => 'mod_mijovideos_channels', 		'client' => 'Site', 			'position' => 'position-7', 		'update' => false),
            array('title' => 'MijoVideos - Featured Videos', 	'element' => 'mod_mijovideos_videos_featured', 	'client' => 'Site', 			'position' => 'mijovideos_top',     'update' => true),
            array('title' => 'MijoVideos - Popular Videos',		'element' => 'mod_mijovideos_videos_popular', 	'client' => 'Site', 			'position' => 'mijovideos_right',   'update' => true),
            array('title' => 'MijoVideos - Latest Videos',		'element' => 'mod_mijovideos_videos_latest', 	'client' => 'Site', 			'position' => 'mijovideos_left',    'update' => true),
            array('title' => 'MijoVideos - Quick Icons', 		'element' => 'mod_mijovideos_quickicons', 		'client' => 'Administrator', 	'position' => 'icon', 		        'update' => true)
        );
		
		if (!empty($modules)) {
			foreach ($modules as $module) {
				$mtitle		= $module['title'];
				$melement	= $module['element'];
				$mclient	= $module['client'];
				$mposition	= $module['position'];
				$mupdate	= $module['update'];

                $path = $src.'/modules/'.$melement;
                if (!JFolder::exists($path)) {
                    continue;
                }
				
				$installer = new JInstaller();
				$result = $installer->install($path);

                if (!$result) {
                    continue;
                }

				if (($mupdate == true) and $this->_is_new_installation) {
					if (MijoVideos::get('utility')->is30() && ($melement == 'mod_mijovideos_quickicons')) {
						$mposition = 'cpanel';
					}
					
					$db->setQuery("UPDATE #__modules SET position = '{$mposition}', ordering = '0', published = '1' WHERE module = '{$melement}'");
					$db->query();
					
					$db->setQuery("SELECT id FROM #__modules WHERE `module` = ".$db->quote($melement));
					$mid = (int)$db->loadResult();
					
					$db->setQuery("INSERT IGNORE INTO #__modules_menu (`moduleid`, `menuid`) VALUES (".$mid.", 0)");
					$db->query();
				}
				
				$status->modules[] = array('name' => $mtitle, 'client' => $mclient);
			}
		}

		/***********************************************************************************************
		* ---------------------------------------------------------------------------------------------
		* PLUGIN INSTALLATION SECTION
		* ---------------------------------------------------------------------------------------------
		***********************************************************************************************/
		$plugins = array(
					array('title' => 'Content - MijoVideos', 			'folder' => 'content', 		'element' => 'mijovideos',          'ordering' => '0', 'update' => false),
					array('title' => 'MijoVideos - Remote Youtube', 	'folder' => 'mijovideos', 	'element' => 'youtube',          	'ordering' => '0', 'update' => true),
					array('title' => 'MijoVideos - Remote Dailymotion',	'folder' => 'mijovideos', 	'element' => 'dailymotion',         'ordering' => '0', 'update' => false),
					array('title' => 'MijoVideos - Remote Metacafe', 	'folder' => 'mijovideos', 	'element' => 'metacafe',          	'ordering' => '0', 'update' => false),
					array('title' => 'MijoVideos - Remote Vimeo', 		'folder' => 'mijovideos', 	'element' => 'vimeo',          		'ordering' => '0', 'update' => false),
					array('title' => 'MijoVideos - Remote Vine', 		'folder' => 'mijovideos', 	'element' => 'vine',          	    'ordering' => '0', 'update' => false),
					array('title' => 'MijoVideos - Remote Extreme', 	'folder' => 'mijovideos', 	'element' => 'extreme',          	'ordering' => '0', 'update' => false),
					array('title' => 'MijoVideos - Remote Veoh', 		'folder' => 'mijovideos', 	'element' => 'veoh',          	    'ordering' => '0', 'update' => false),
					array('title' => 'MijoVideos - Remote Vevo', 		'folder' => 'mijovideos', 	'element' => 'vevo',          	    'ordering' => '0', 'update' => false),
					array('title' => 'MijoVideos - Remote Fox News', 	'folder' => 'mijovideos', 	'element' => 'videofoxnews',        'ordering' => '0', 'update' => false),
					array('title' => 'MijoVideos - Remote Blip.tv', 	'folder' => 'mijovideos', 	'element' => 'blip',          	    'ordering' => '0', 'update' => false),
					array('title' => 'MijoVideos - Remote Wat.tv', 	    'folder' => 'mijovideos', 	'element' => 'wat',          	    'ordering' => '0', 'update' => false),
					array('title' => 'MijoVideos - Remote Youku', 	    'folder' => 'mijovideos', 	'element' => 'youku',          	    'ordering' => '0', 'update' => false),
					array('title' => 'MijoVideos - Remote Sprout', 	    'folder' => 'mijovideos', 	'element' => 'sproutvideo',         'ordering' => '0', 'update' => false),
                    array('title' => 'MijoVideos - VideoJS Player', 	'folder' => 'mijovideos', 	'element' => 'videojs',             'ordering' => '0', 'update' => true),
                    array('title' => 'MijoVideos - CDN AmazonS3', 		'folder' => 'mijovideos', 	'element' => 'amazons3',            'ordering' => '0', 'update' => false),
                    array('title' => 'Search - MijoVideos', 			'folder' => 'search', 		'element' => 'mijovideos',          'ordering' => '0', 'update' => false),
                    array('title' => 'Smart Search - MijoVideos', 		'folder' => 'finder', 		'element' => 'mijovideos',          'ordering' => '0', 'update' => false),
                    array('title' => 'System - MijoVideos', 			'folder' => 'system', 		'element' => 'mijovideos',          'ordering' => '0', 'update' => true),
					array('title' => 'User - MijoVideos', 			    'folder' => 'user', 		'element' => 'mijovideos',          'ordering' => '0', 'update' => true),
				);

		if (!empty($plugins)) {
			foreach ($plugins as $plugin) {
				$ptitle		= $plugin['title'];
				$pfolder	= $plugin['folder'];
				$pelement	= $plugin['element'];
				$pordering	= $plugin['ordering'];
				$pupdate	= $plugin['update'];

                $path = $src.'/plugins/plg_'.$pfolder.'_'.$pelement;
                if (!JFolder::exists($path)) {
                    continue;
                }

                $installer = new JInstaller();
            	$result = $installer->install($path);

                if (!$result) {
                    continue;
                }
				
				if (($pupdate == true) and $this->_is_new_installation) {
					$db->setQuery("UPDATE #__extensions SET enabled = 1, ordering = '{$pordering}' WHERE type = 'plugin' AND element = '{$pelement}' AND folder = '{$pfolder}'");
					$db->query();
				}

				$status->plugins[] = array('name' => $ptitle, 'group' => $pfolder);
			}
		}
		
		if (!JFolder::exists(JPATH_ROOT. '/cgi-bin')) {
            JFolder::create(JPATH_ROOT. '/cgi-bin');
        }

        JFile::move($src . '/admin/ubr_upload.pl', str_replace('\\', '\\\\', JPATH_ROOT . '/cgi-bin/ubr_upload.pl'));

        if (!JFolder::exists(JPATH_ROOT. '/cli')) {
            JFolder::create(JPATH_ROOT. '/cli');
        }

        JFile::move($src . '/mijovideoscli.php', str_replace('\\', '\\\\', JPATH_ROOT . '/cli/mijovideoscli.php'));

        if ($this->_is_new_installation == true) {
			$this->_installMijovideos();
		}
        else {
            $this->_updateMijovideos();
        }

        $this->_installationOutput($status);
	}
	
    protected function _installMijovideos() {
        $db = JFactory::getDbo();

        $config = new stdClass();
        # General
        $config->pid 										= '';
        $config->version_checker 							= '1';
        $config->show_db_errors 							= '0';
        $config->log             							= '0';
        $config->jusersync 									= '0';
        $config->categories 								= '1';
        $config->playlists 									= '1';
        $config->tags 									    = '1';
        $config->subscriptions 								= '1';
        $config->likes_dislikes 							= '1';
        $config->custom_fields 								= '1';
        # Front-end
        $config->button_class 								= MijoVideos::is30() ? 'btn btn-primary' : 'mijovideos_button';
        $config->override_color 							= '#dc2f2c';
        $config->videos_per_page 							= '6';
        $config->comments 									= '0';
        $config->load_plugins 								= '0';
        $config->show_empty_cat 							= '1';
        $config->show_number_videos 						= '1';
        $config->order_videos 								= '2';
        $config->listing_style 								= 'grid';
        $config->title_truncation 		        			= '20';
        $config->desc_truncation        					= '150';
        $config->thumb_size 								= '3'; // Small Image
        $config->thumb_aspect 								= '43';
        $config->items_per_column 							= '3';
        # Player
		$config->video_player						        = 'videojs';
        $config->watermark									= '1';
        $config->watermark_position							= '4';
        $config->watermark_path								= 'images\/powered_by.png';
        # Upload
		$config->video_upload						        = '1';
        $config->perl_upload						        = '1';
		$config->remote_upload						        = '1';
		$config->upload_script						        = 'dropzone';
		$config->upload_max_filesize						= '128';
		$config->process_videos								= '1';
		$config->auto_process_videos						= '1';
        $config->allow_file_types 							= 'mov|mpeg|divx|flv|mpg|avi|mp4|mkv';
        # Server
		$config->ffmpeg_path								= '/usr/local/bin/ffmpeg';
		$config->qt_faststart_path							= '/usr/local/bin/qt-faststart';
        $config->uber_upload_perl_url 						= '';
        $config->uber_upload_tmp_path 						= '/tmp/ubr_temp/';
        # Processing
        $config->frames 									= '1';
		$config->jpeg_75									= '1';
		$config->jpeg_100									= '1';
		$config->jpeg_240									= '1';
		$config->jpeg_500									= '1';
		$config->jpeg_640									= '1';
		$config->jpeg_1024									= '1';
		$config->mp4_240p									= '1';
		$config->mp4_360p									= '1';
		$config->mp4_480p									= '1';
		$config->mp4_720p									= '1';
		$config->mp4_1080p									= '1';
		$config->webm_240p									= '1';
		$config->webm_360p									= '1';
		$config->webm_480p									= '1';
		$config->webm_720p									= '1';
		$config->webm_1080p									= '1';
		$config->ogg_240p									= '1';
		$config->ogg_360p									= '1';
		$config->ogg_480p									= '1';
		$config->ogg_720p									= '1';
		$config->ogg_1080p									= '1';

        $reg = new JRegistry($config);
        $config = $reg->toString();
		
		$user_id = JFactory::getUser()->id;

        $db->setQuery('UPDATE `#__extensions` SET `params` = '.$db->Quote($config).' WHERE `element` = "com_mijovideos" AND `type` = "component"');
        $db->query();
		
		# SAMPLE DATA
		# Channel
        $db->setQuery("INSERT INTO `#__mijovideos_channels` (`id`, `user_id`, `title`, `alias`, `introtext`, `fulltext`, `thumb`, `banner`, `fields`,`likes`, `dislikes`, `hits`, `params`, `ordering`, `access`, `language`, `created`, `modified`, `meta_desc`, `meta_key`, `meta_author`, `share_others`, `featured`, `published`, `default`) VALUES
                (1, {$user_id}, 'NBA', 'nba', 'National Basketball Association. Official home of the most compelling basketball action from the NBA', '', 'https://yt3.ggpht.com/-iykIxE6HFpM/AAAAAAAAAAI/AAAAAAAAAAA/qho7mLdsPLc/s176-c-k-no/photo.jpg', 'http://i1.ytimg.com/u/WJ2lWNubArHWmf3FIHbfcQ/channels4_banner.jpg', '', 0, 0, '1906403293', '', '', 1, '*', NOW(), NOW(), '', '', '', 0, 0, 1, 0);");
        $db->query();

        $db->setQuery("INSERT INTO `#__mijovideos_channels` (`id`, `user_id`, `title`, `alias`, `introtext`, `fulltext`, `thumb`, `banner`, `fields`,`likes`, `dislikes`, `hits`, `params`, `ordering`, `access`, `language`, `created`, `modified`, `meta_desc`, `meta_key`, `meta_author`, `share_others`, `featured`, `published`, `default`) VALUES
                (2, {$user_id}, 'League of Legends', 'league-of-legends', 'Since its launch, over 30 million players have downloaded the game to engage in session-based, multiplayer battles against rival teams, with more than four million players enjoying League of Legends every day.', '', 'https://yt3.ggpht.com/-AEerXPqHm3M/AAAAAAAAAAI/AAAAAAAAAAA/S8WpkwxItLQ/s176-c-k-no/photo.jpg', 'http://i1.ytimg.com/u/2t5bjwHdUX4vM2g8TRDq5g/channels4_banner.jpg', '', 0, 0, '666474268', '', '', 1, '*', NOW(), NOW(), '', '', '', 0, 0, 1, 0);");
        $db->query();

        $db->setQuery("INSERT INTO `#__mijovideos_channels` (`id`, `user_id`, `title`, `alias`, `introtext`, `fulltext`, `thumb`, `banner`, `fields`,`likes`, `dislikes`, `hits`, `params`, `ordering`, `access`, `language`, `created`, `modified`, `meta_desc`, `meta_key`, `meta_author`, `share_others`, `featured`, `published`, `default`) VALUES
                (3, {$user_id}, 'VISO Trailers', 'viso-trailers', 'Want to get teased on a regular basis? New trailers and clips updated every week! Head over to our Twitter, Facebook and Google+ page for more movie fun!', '', 'https://yt3.ggpht.com/-lvfrq8esjbE/AAAAAAAAAAI/AAAAAAAAAAA/BIXNhC3B7zI/s176-c-k-no/photo.jpg', 'http://i1.ytimg.com/u/KVtW8ExxO21F2sNLtwrq_w/channels4_banner.jpg', '', 0, 0, '666474268', '', '', 1, '*', NOW(), NOW(), '', '', '', 0, 0, 1, 0);");
        $db->query();

        $db->setQuery("INSERT INTO `#__mijovideos_channels` (`id`, `user_id`, `title`, `alias`, `introtext`, `fulltext`, `thumb`, `banner`, `fields`,`likes`, `dislikes`, `hits`, `params`, `ordering`, `access`, `language`, `created`, `modified`, `meta_desc`, `meta_key`, `meta_author`, `share_others`, `featured`, `published`, `default`) VALUES
                (4, {$user_id}, 'EminemVEVO', 'eminemvevo', 'VEVO is working hard to make sure all of our videos are available worldwide. In the meantime, watch your favorite music videos through VEVO on YouTube, and follow us on social media for updates!', '', 'https://yt3.ggpht.com/-NzI5Ni67ppc/AAAAAAAAAAI/AAAAAAAAAAA/7wGQowTOWWg/s176-c-k-no/photo.jpg', 'http://i1.ytimg.com/u/20vb-R_px4CguHzzBPhoyQ/channels4_banner.jpg', '', 0, 0, '3222647140', '', '', 1, '*', NOW(), NOW(), '', '', '', 0, 0, 1, 0);");
        $db->query();

        $db->setQuery("INSERT INTO `#__mijovideos_channels` (`id`, `user_id`, `title`, `alias`, `introtext`, `fulltext`, `thumb`, `banner`, `fields`,`likes`, `dislikes`, `hits`, `params`, `ordering`, `access`, `language`, `created`, `modified`, `meta_desc`, `meta_key`, `meta_author`, `share_others`, `featured`, `published`, `default`) VALUES
                (5, {$user_id}, 'QueenVEVO', 'queenvevo', 'VEVO is working hard to make sure all of our videos are available worldwide. In the meantime, watch your favorite music videos through VEVO on YouTube, and follow us on social media for updates!', '', 'https://yt3.ggpht.com/-hgR-GRAx-dk/AAAAAAAAAAI/AAAAAAAAAAA/2A9ivUKuwvs/s176-c-k-no/photo.jpg', '', '', 0, 0, '146559129', '', '', 1, '*', NOW(), NOW(), '', '', '', 0, 0, 1, 0);");
        $db->query();

		# Playlist
        $db->setQuery("INSERT INTO `#__mijovideos_playlists` (`id`, `user_id`, `channel_id`, `type`, `title`, `alias`, `introtext`, `fulltext` ,`thumb` ,`fields` ,`likes` ,`dislikes` ,`hits` ,`subscriptions` ,`params` ,`ordering` ,`access` ,`language` ,`created` ,`modified` ,`meta_desc` ,`meta_key` ,`meta_author` ,`share_others` ,`featured` ,`published`) VALUES
			(1, ".$user_id.", 0, 0, 'My Playlist', 'my-playlist', 'My Playlist', NULL , 'http://i1.ytimg.com/vi/KAi2QIq1SUs/mqdefault.jpg', NULL , '1445', '23', '45147', '0', NULL , 1, 1, '*', NOW(), NOW(), NULL, NULL, NULL, 0, 0, 1);");
        $db->query();

        $db->setQuery("INSERT INTO `#__mijovideos_playlist_videos` (`id` , `playlist_id`, `video_id`, `ordering`) VALUES
			(1, 1, 1, 1),
			(2, 1, 2, 1);");
        $db->query();

		# Videos
        $db->setQuery("INSERT INTO `#__mijovideos_videos` (`id`, `user_id`, `channel_id`, `product_id`, `title`, `alias`, `introtext`, `fulltext`, `source`, `duration`, `likes`, `dislikes`, `hits`, `access`, `price`, `created`, `modified`, `featured`, `published`, `fields`, `thumb`, `meta_desc`, `meta_key`, `meta_author`, `params`, `language`) VALUES 
			(1, ".$user_id.", 0, 0, 'EOS 600D Sample Video', 'eos-600d-sample-video', 'Your EOS adventure starts here with the new Canon EOS 600D, empowering enthusiast photographers to capture outstanding', 'stills and Full HD video. Be the first to see some of its features in this sample video shot entirely on the camera itself', 'http://www.youtube.com/watch?v=KAi2QIq1SUs', '90', '585', '14', '45689', '1', NULL, NOW(), NOW(), '0', '1', '{\"mijo_license\":\"Standard YouTube License\"}', 'http://i1.ytimg.com/vi/KAi2QIq1SUs/mqdefault.jpg', NULL, NULL, NULL, NULL, '*');");
        $db->query();

        $db->setQuery("INSERT INTO `#__mijovideos_videos` (`id`, `user_id`, `channel_id`, `product_id`, `title`, `alias`, `introtext`, `fulltext`, `source`, `duration`, `likes`, `dislikes`, `hits`, `access`, `price`, `created`, `modified`, `featured`, `published`, `fields`, `thumb`, `meta_desc`, `meta_key`, `meta_author`, `params`, `language`) VALUES 
			(2, ".$user_id.", 0, 0, 'Canon Eos 550D sample video', 'canon-eos-550d-sample-video', 'Sample video from the new Canon EOS 550D shot at 1920x1080 resolution (30fps) and optimised for YouTube. Offering Full HD', 'movie recording with manual control and selectable frame rates, the EOS 550D allows you to capture everything in stunning detail.', 'http://www.youtube.com/watch?v=3f7l-Z4NF70', '193', '585', '14', '45689', '1', NULL, NOW(), NOW(), '0', '1', '{\"mijo_license\":\"Standard YouTube License\"}', 'http://i1.ytimg.com/vi/3f7l-Z4NF70/mqdefault.jpg', NULL, NULL, NULL, NULL, '*');");
        $db->query();

        $db->setQuery("INSERT INTO `#__mijovideos_videos` (`id`, `user_id`, `channel_id`, `product_id`, `title`, `alias`, `introtext`, `fulltext`, `source`, `duration`, `likes`, `dislikes`, `hits`, `access`, `price`, `created`, `modified`, `featured`, `published`, `fields`, `thumb`, `meta_desc`, `meta_key`, `meta_author`, `params`, `language`) VALUES
			(3, ".$user_id.", 3, 0, 'Robocop - The Future Of American Justice', 'robocop-the-future-of-american-justice', 'In 2028 Detroit, when Alex Murphy (Joel Kinnaman) - a lovaing husband, father and good cop - is critically injured in the line of duty, the multinational con...', '', 'http://www.youtube.com/watch?v=7VPbtuevHls', '57', '132', '9', '21325', '1', NULL, NOW(), NOW(), '0', '1', '{\"mijo_license\":\"Standard YouTube License\"}', 'http://i1.ytimg.com/vi/7VPbtuevHls/hqdefault.jpg', NULL, NULL, NULL, NULL, '*');");
        $db->query();

        $db->setQuery("INSERT INTO `#__mijovideos_videos` (`id`, `user_id`, `channel_id`, `product_id`, `title`, `alias`, `introtext`, `fulltext`, `source`, `duration`, `likes`, `dislikes`, `hits`, `access`, `price`, `created`, `modified`, `featured`, `published`, `fields`, `thumb`, `meta_desc`, `meta_key`, `meta_author`, `params`, `language`) VALUES
			(4, ".$user_id.", 4, 0, 'Eminem - The Monster (Explicit) ft. Rihanna', 'eminem-the-monster-explicit-ft-rihanna', 'Download Eminem\'s \'MMLP2\' Album on iTunes now:http://smarturl.it/MMLP2 Music video by Eminem ft. Rihanna \"The Monster\" 2013 Interscope', '', 'http://www.youtube.com/watch?v=EHkozMIXZ8w', '319', '721612', '24072', '87379128', '1', NULL, NOW(), NOW(), '1', '1', '{\"mijo_license\":\"Standard YouTube License\"}', 'http://i1.ytimg.com/vi/EHkozMIXZ8w/hqdefault.jpg', NULL, NULL, NULL, NULL, '*');");
        $db->query();

        $db->setQuery("INSERT INTO `#__mijovideos_videos` (`id`, `user_id`, `channel_id`, `product_id`, `title`, `alias`, `introtext`, `fulltext`, `source`, `duration`, `likes`, `dislikes`, `hits`, `access`, `price`, `created`, `modified`, `featured`, `published`, `fields`, `thumb`, `meta_desc`, `meta_key`, `meta_author`, `params`, `language`) VALUES
			(5, ".$user_id.", 1, 0, 'Top 10 Dunks in the All-Star Game', 'top-10-dunks-in-the-all-star-game', 'Check out the Top 10 dunks in the 62 year history of the All-Star Game. Visit nba.com/video for more highlights. About the NBA: The NBA is the premier profes...', '', 'http://www.youtube.com/watch?v=aXgkgai-OI0', '182', '1997', '972', '114552', '1', NULL, NOW(), NOW(), '1', '1', '{\"mijo_license\":\"Standard YouTube License\"}', 'http://i1.ytimg.com/vi/aXgkgai-OI0/hqdefault.jpg', NULL, NULL, NULL, NULL, '*');");
        $db->query();

        $db->setQuery("INSERT INTO `#__mijovideos_videos` (`id`, `user_id`, `channel_id`, `product_id`, `title`, `alias`, `introtext`, `fulltext`, `source`, `duration`, `likes`, `dislikes`, `hits`, `access`, `price`, `created`, `modified`, `featured`, `published`, `fields`, `thumb`, `meta_desc`, `meta_key`, `meta_author`, `params`, `language`) VALUES
			(6, ".$user_id.", 2, 0, 'League of Legends Cinematic: A Twist of Fate', 'league-of-legends-cinematic-a-twist-of-fate', 'Get up close and personal with your favorite champions in the League of Legends Cinematic: A Twist of Fate.', '', 'http://www.youtube.com/watch?v=tEnsqpThaFg', '275', '277296', '4365', '26381509', '1', NULL, NOW(), NOW(), '0', '1', '{\"mijo_license\":\"Standard YouTube License\"}', 'http://i1.ytimg.com/vi/tEnsqpThaFg/hqdefault.jpg', NULL, NULL, NULL, NULL, '*');");
        $db->query();

        $db->setQuery("INSERT INTO `#__mijovideos_videos` (`id`, `user_id`, `channel_id`, `product_id`, `title`, `alias`, `introtext`, `fulltext`, `source`, `duration`, `likes`, `dislikes`, `hits`, `access`, `price`, `created`, `modified`, `featured`, `published`, `fields`, `thumb`, `meta_desc`, `meta_key`, `meta_author`, `params`, `language`) VALUES
			(7, ".$user_id.", 3, 0, 'Fast & Furious 6 - Official Trailer [HD]', 'fast-furious-6-official-trailer-hd', 'Since Dom (Diesel) and Brians (Walker) Rio heist toppled a kingpins empire and left their crew with $100 million, our heroes have scattered across the globe....', '', 'http://www.youtube.com/watch?v=PP7pH4pqC5A', '155', '7396', '302', '2546526', '1', NULL, NOW(), NOW(), '1', '1', '{\"mijo_license\":\"Standard YouTube License\"}', 'http://i1.ytimg.com/vi/PP7pH4pqC5A/hqdefault.jpg', NULL, NULL, NULL, NULL, '*');");
        $db->query();

        $db->setQuery("INSERT INTO `#__mijovideos_videos` (`id`, `user_id`, `channel_id`, `product_id`, `title`, `alias`, `introtext`, `fulltext`, `source`, `duration`, `likes`, `dislikes`, `hits`, `access`, `price`, `created`, `modified`, `featured`, `published`, `fields`, `thumb`, `meta_desc`, `meta_key`, `meta_author`, `params`, `language`) VALUES
			(8, ".$user_id.", 3, 0, 'The Hobbit : Desolation Of Smaug - I Found', 'the-hobbit-desolation-of-smaug-i-found-something-in-the-goblin-tunnels', 'The Dwarves, Bilbo and Gandalf have successfully escaped the Misty Mountains, and Bilbo has gained the One Ring. They all continue their journey to get their...', '', 'http://www.youtube.com/watch?v=UgjeDk_NWCU', '48', '89', '33', '18477', '1', NULL, NOW(), NOW(), '0', '1', '{\"mijo_license\":\"Standard YouTube License\"}', 'http://i1.ytimg.com/vi/UgjeDk_NWCU/hqdefault.jpg', NULL, NULL, NULL, NULL, '*');");
        $db->query();

        $db->setQuery("INSERT INTO `#__mijovideos_videos` (`id`, `user_id`, `channel_id`, `product_id`, `title`, `alias`, `introtext`, `fulltext`, `source`, `duration`, `likes`, `dislikes`, `hits`, `access`, `price`, `created`, `modified`, `featured`, `published`, `fields`, `thumb`, `meta_desc`, `meta_key`, `meta_author`, `params`, `language`) VALUES
			(9, ".$user_id.", 5, 0, 'Queen - We Will Rock You', 'queen-we-will-rock-you', 'Music video by Queen performing We Will Rock You. (C) 1977 Queen Productions Ltd. under exclusive licence to Universal International Music BV', '', 'http://www.youtube.com/watch?v=XMLiqEqMQyQ', '129', '61573', '2102', '11135550', '1', NULL, NOW(), NOW(), '1', '1', '{\"mijo_license\":\"Standard YouTube License\"}', 'http://i1.ytimg.com/vi/XMLiqEqMQyQ/hqdefault.jpg', NULL, NULL, NULL, NULL, '*');");
        $db->query();

        $db->setQuery("INSERT INTO `#__mijovideos_videos` (`id`, `user_id`, `channel_id`, `product_id`, `title`, `alias`, `introtext`, `fulltext`, `source`, `duration`, `likes`, `dislikes`, `hits`, `access`, `price`, `created`, `modified`, `featured`, `published`, `fields`, `thumb`, `meta_desc`, `meta_key`, `meta_author`, `params`, `language`) VALUES
			(10, ".$user_id.", 0, 0, 'Introducing MijoVideos', 'introducing-mijovideos', 'Introducing, MijoVideos: The revolutionary video sharing component for Joomla. MijoVideos allows you to turn your site into a professional looking video-sharing website with user-interface and features similar to YouTube. The interface has been thought in detail and designed specifically for the users.', '', 'http://www.youtube.com/watch?v=QTxA6XnAQas', '126', '3487', '0', '135657', '1', NULL, NOW(), NOW(), '1', '1', '{\"mijo_license\":\"Standard YouTube License\"}', 'http://i1.ytimg.com/vi/QTxA6XnAQas/maxresdefault.jpg', NULL, NULL, NULL, NULL, '*');");
        $db->query();
		
        $db->setQuery("INSERT INTO `#__mijovideos_video_categories` (`id` , `video_id`, `category_id`) VALUES 
			(1, 1, 2),
			(2, 2, 2),
			(3, 3, 3),
			(4, 4, 4),
			(5, 5, 5),
			(6, 6, 6),
			(7, 7, 3),
			(8, 8, 4),
			(9, 9, 3),
			(10, 10, 2);");
        $db->query();
		
		if (version_compare(JVERSION, '3.1.0', 'ge')) {
			$this->_createContentType();
		}
    }

    protected function _createContentType() {
        $contentType = new JTableContenttype(JFactory::getDbo());

        // Create a new content type for videos
        if (!$contentType->load(array('type_alias' => 'com_mijovideos.video'))) {
            $contentType->type_title = 'MijoVideos Video';
            $contentType->type_alias = 'com_mijovideos.video';
            $contentType->table = json_encode(
                array(
                    'special' => array(
                        'dbtable' => '#__mijovideos_video',
                        'key'     => 'id',
                        'type'    => 'MijoVideos Video',
                        'prefix'  => 'JTable',
                        'config' => 'array()'
                    ),
                    'common' => array(
                        'dbtable' => '#__ucm_content',
                        'key' => 'ucm_id',
                        'type' => 'CoreContent',
                        'prefix' => 'JTable',
                        'config' => 'array()'
                    )
                )
            );

            $contentType->field_mappings = json_encode(
                array(
                    'common' => array(
                        0 => array(
                            "core_content_item_id" => "id",
                            "core_title"           => "title",
                            "core_state"           => "published",
                            "core_alias"           => "alias",
                            "core_created_time"    => "created",
                            "core_modified_time"   => "modified",
                            "core_body"            => "introtext",
                            "core_hits"            => "hits",
                            "core_publish_up"      => "null",
                            "core_publish_down"    => "null",
                            "core_access"          => "access",
                            "core_params"          => "null",
                            "core_featured"        => "featured",
                            "core_metadata"        => "null",
                            "core_language"        => "language",
                            "core_images"          => "null",
                            "core_urls"            => "null",
                            "core_version"         => "null",
                            "core_ordering"        => "null",
                            "core_metakey"         => "meta_key",
                            "core_metadesc"        => "meta_desc",
                            "core_catid"           => "null",
                            "core_xreference"      => "null",
                            "asset_id"             => "null"
                        )
                    ),
                    'special' => array(
                        0 => array(
                            "parent_id" => "parent_id",
                            "lft"       => "lft",
                            "rgt"       => "rgt",
                            "level"     => "level",
                            "path"      => "path",
                            "extension" => "extension",
                            "note"      => "note"
                        )
                    )
                )
            );

            $contentType->router = 'MijoVideos::getVideoRoute';

            $contentType->store();
        }
    }

    protected function _updateMijovideos() {
        if (empty($this->_current_version)) {
            return;
        }
		/* 2013-07-19
        if ($this->_current_version == '1.0.3') {
            $config = MijoVideos::getConfig();

            $config->csv_delimiter = ',';
            $config->show_fields_in_category = '0';

            MijoVideos::get('utility')->storeConfig($config);

            MijoDB::query("ALTER TABLE `#__mijovideos_channels` CHANGE `status` `status` INT(5) NULL DEFAULT '100'");
        }
		*/
    }

    public function uninstall($parent) {
        $db = JFactory::getDBO();
        $src = $parent->getParent()->getPath('source');

        $status = new JObject();
        $status->adapters	= array();
        $status->extensions = array();
        $status->modules 	= array();
        $status->plugins 	= array();

        /***********************************************************************************************
		 * ---------------------------------------------------------------------------------------------
		 * MODULE REMOVAL SECTION
		 * ---------------------------------------------------------------------------------------------
		 ***********************************************************************************************/
		$modules = array(
					array('title' => 'MijoVideos - Categories', 		'element' => 'mod_mijovideos_categories', 		'client' => 'Site', 			'position' => 'position-7', 'update' => false),
					array('title' => 'MijoVideos - Videos', 			'element' => 'mod_mijovideos_videos', 			'client' => 'Site', 			'position' => 'position-7', 'update' => false),
					array('title' => 'MijoVideos - Popular Videos', 	'element' => 'mod_mijovideos_videos_popular', 	'client' => 'Site', 			'position' => 'position-7', 'update' => false),
					array('title' => 'MijoVideos - Featured Videos', 	'element' => 'mod_mijovideos_videos_featured', 	'client' => 'Site', 			'position' => 'position-7', 'update' => false),
					array('title' => 'MijoVideos - Latest Videos', 		'element' => 'mod_mijovideos_videos_latest', 	'client' => 'Site', 			'position' => 'position-7', 'update' => false),
					array('title' => 'MijoVideos - Playlist', 			'element' => 'mod_mijovideos_playlist', 		'client' => 'Site', 			'position' => 'position-7', 'update' => false),
					array('title' => 'MijoVideos - Channels', 			'element' => 'mod_mijovideos_channels', 		'client' => 'Site', 			'position' => 'position-7', 'update' => false),
					array('title' => 'MijoVideos - Quick Icons', 		'element' => 'mod_mijovideos_quickicons', 		'client' => 'Administrator', 	'position' => 'icon', 		'update' => true)
				);

		if (!empty($modules)) {
			foreach ($modules as $module) {
				$mtitle		= $module['title'];
				$melement	= $module['element'];
				$mclient	= $module['client'];
				$mmclient 	= ($mclient == 'Site') ? 0 : 1;
				
				$db->setQuery("SELECT extension_id FROM #__extensions WHERE type = 'module' AND element = '{$melement}' AND client_id = '{$mmclient}' LIMIT 1");
				$id = $db->loadResult();
				if ($id) {
					$installer = new JInstaller();
					$installer->uninstall('module', $id);
				}

				$status->modules[] = array('name' => $mtitle, 'client' => $mclient);
			}
		}

		/***********************************************************************************************
		 * ---------------------------------------------------------------------------------------------
		 * PLUGIN REMOVAL SECTION
		 * ---------------------------------------------------------------------------------------------
		 ***********************************************************************************************/
		$plugins = array(
					array('title' => 'Content - MijoVideos', 			'folder' => 'content', 		'element' => 'mijovideos',          'ordering' => '0', 'update' => true),
					array('title' => 'MijoVideos - Unpublish Videos', 	'folder' => 'mijovideos', 	'element' => 'unpublishvideos',     'ordering' => '0', 'update' => false),
					array('title' => 'MijoShop - MijoVideos', 			'folder' => 'mijoshop', 	'element' => 'mijovideos',          'ordering' => '0', 'update' => true),
					array('title' => 'Search - MijoVideos', 			'folder' => 'search', 		'element' => 'mijovideos',          'ordering' => '0', 'update' => true),
					array('title' => 'Smart Search - MijoVideos', 		'folder' => 'finder', 		'element' => 'mijovideos',          'ordering' => '0', 'update' => true),
					array('title' => 'System - MijoVideos', 			'folder' => 'system', 		'element' => 'mijovideos',          'ordering' => '0', 'update' => true),
					array('title' => 'MijoVideos - Remote Youtube', 	'folder' => 'mijovideos', 	'element' => 'youtube',          	'ordering' => '0', 'update' => true),
					array('title' => 'System - MijoVideos', 			'folder' => 'user', 		'element' => 'mijovideos',          'ordering' => '0', 'update' => true),
					array('title' => 'MijoVideos - CDN AmazonS3', 		'folder' => 'mijovideos', 	'element' => 'amazons3',            'ordering' => '0', 'update' => false),
					array('title' => 'MijoVideos - VideoJs', 			'folder' => 'mijovideos', 	'element' => 'videojs',             'ordering' => '0', 'update' => true)
				);
		
		if (!empty($plugins)) {
			foreach ($plugins as $plugin) {
				$ptitle		= $plugin['title'];
				$pfolder	= $plugin['folder'];
				$pelement	= $plugin['element'];

				$db->setQuery("SELECT extension_id FROM #__extensions WHERE type = 'plugin' AND element = '{$pelement}' AND folder = '{$pfolder}' LIMIT 1");
				$id = $db->loadResult();
				if ($id) {
					$installer = new JInstaller();
					$installer->uninstall('plugin', $id);
				}
				
				$status->plugins[] = array('name' => $ptitle, 'group' => $pfolder);
			}
		}

        $this->_uninstallationOutput($status);
	}

    private function _installationOutput($status) {

$rows = 0;
?>
<img src="components/com_mijovideos/assets/images/logo.png" alt="Videos Management" style="width:80px; height:80px; float: left; padding-right:15px;" />
<?php
					if(empty(MijoVideos::getConfig()->jusersync)){
						JError::raiseWarning('100', JText::sprintf('COM_MIJOVIDEOS_ACCOUNT_SYNC_WARN_INS', '<a href="'.JRoute::_('index.php?option=com_mijovideos').'">', '</a>'));
					}
				?>
<h2>MijoVideos Installation</h2>
<h2><a href="index.php?option=com_mijovideos">Go to MijoVideos</a></h2>
<table class="adminlist table table-striped">
	<thead>
		<tr>
			<th class="title" colspan="2"><?php echo JText::_('Extension'); ?></th>
			<th width="30%"><?php echo JText::_('Status'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="3"></td>
		</tr>
	</tfoot>
	<tbody>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'MijoVideos '.JText::_('Component'); ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
	<?php
if (count($status->modules)) : ?>
		<tr>
			<th><?php echo JText::_('Module'); ?></th>
			<th><?php echo JText::_('Client'); ?></th>
			<th></th>
		</tr>
	<?php foreach ($status->modules as $module) : ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
			<td class="key"><?php echo $module['name']; ?></td>
			<td class="key"><?php echo ucfirst($module['client']); ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
	<?php endforeach;
endif;
if (count($status->plugins)) : ?>
		<tr>
			<th><?php echo JText::_('Plugin'); ?></th>
			<th><?php echo JText::_('Group'); ?></th>
			<th></th>
		</tr>
	<?php foreach ($status->plugins as $plugin) : ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
			<td class="key"><?php echo ucfirst($plugin['name']); ?></td>
			<td class="key"><?php echo ucfirst($plugin['group']); ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
	<?php endforeach;
endif; ?>
	</tbody>
</table>
        <?php
    }

    private function _uninstallationOutput($status) {
 $rows = 0;
?>

<h2>MijoVideos Removal</h2>
<table class="adminlist table table-striped">
	<thead>
		<tr>
			<th class="title" colspan="2"><?php echo JText::_('Extension'); ?></th>
			<th width="30%"><?php echo JText::_('Status'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="3"></td>
		</tr>
	</tfoot>
	<tbody>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'MijoVideos '.JText::_('Component'); ?></td>
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
		</tr>
	<?php
if (count($status->modules)) : ?>
		<tr>
			<th><?php echo JText::_('Module'); ?></th>
			<th><?php echo JText::_('Client'); ?></th>
			<th></th>
		</tr>
	<?php foreach ($status->modules as $module) : ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
			<td class="key"><?php echo $module['name']; ?></td>
			<td class="key"><?php echo ucfirst($module['client']); ?></td>
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
		</tr>
	<?php endforeach;
endif;
if (count($status->plugins)) : ?>
		<tr>
			<th><?php echo JText::_('Plugin'); ?></th>
			<th><?php echo JText::_('Group'); ?></th>
			<th></th>
		</tr>
	<?php foreach ($status->plugins as $plugin) : ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
			<td class="key"><?php echo ucfirst($plugin['name']); ?></td>
			<td class="key"><?php echo ucfirst($plugin['group']); ?></td>
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
		</tr>
	<?php endforeach;
endif;
?>
	</tbody>
</table>
        <?php
    }
}