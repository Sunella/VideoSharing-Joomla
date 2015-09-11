<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted Access');

# Backup/Restore class
class MijovideosBackupRestore {
	
	protected $_dbprefix;
	protected $_table;
	protected $_where;
	protected $_jstatus;

    public function __construct($options = "") {
		if (is_array($options)) {
			if (isset($options['_table'])) {
				$this->_table = $options['_table'];
			}
			
			if (isset($options['_where'])) {
				$this->_where = $options['_where'];
			}
		}
		
		$this->_jstatus = MijoVideos::is30();
		
		$this->_dbprefix = JFactory::getConfig()->get('dbprefix');
	}

    # MijoVideos : Backup
    public function backupCategories() {
        $filename = "mijovideos_categories.sql";
        $fields = array('id', 'parent', 'title', 'alias', 'description', 'thumb', 'introtext', 'fulltext', 'ordering', 'access', 'language', 'created', 'modified', 'type', 'meta_desc', 'meta_key', 'meta_author', 'published');
        $line = "INSERT IGNORE INTO {$this->_dbprefix}mijovideos_{$this->_table} (".implode(', ', $fields).")";
        $query = "SELECT `id`, `parent`, `title`, `alias`, `description`, `thumb`, `introtext`, `fulltext`, `ordering`, `access`, `language`, `created`, `modified`, `type`, `meta_desc`, `meta_key`, `meta_author`, `published` FROM {$this->_dbprefix}mijovideos_{$this->_table} {$this->_where}";

        return array($query, $filename, $fields, $line);
    }

    public function backupChannels() {
        $filename = "mijovideos_channels.sql";
        $fields = array('id', 'user_id', 'title', 'alias', 'introtext', 'fulltext', 'thumb', 'banner', 'fields', 'likes', 'dislikes', 'hits', 'params', 'ordering', 'access', 'language', 'created', 'modified', 'category',
            'meta_desc', 'meta_key', 'meta_author', 'share_others', 'featured', 'published', 'default');
        $line = "INSERT IGNORE INTO {$this->_dbprefix}mijovideos_{$this->_table} (".implode(', ', $fields).")";
        $query = "SELECT `id`, `user_id`, `title`, `alias`, `introtext`, `fulltext`, `thumb`, `banner`, `fields`, `likes`, `dislikes`, `hits`, `params`, `ordering`, `access`, `language`, `created`, `modified`, `category`,".
            "`meta_desc`, `meta_key`, `meta_author`, `share_others`, `featured`, `published`, `default` FROM {$this->_dbprefix}mijovideos_{$this->_table} {$this->_where}";

        return array($query, $filename, $fields, $line);
    }

    public function backupPlaylists() {
        $filename = "mijovideos_playlists.sql";
        $fields = array('id', 'channel_id', 'user_id', 'type', 'title', 'alias', 'introtext', 'fulltext', 'thumb', 'fields', 'likes', 'dislikes', 'hits', 'subscriptions', 'params',
            'ordering', 'access', 'language', 'created', 'modified', 'meta_desc', 'meta_key', 'meta_author', 'share_others', 'featured', 'published');
        $line = "INSERT IGNORE INTO {$this->_dbprefix}mijovideos_{$this->_table} (".implode(', ', $fields).")";
        $query = "SELECT `id`, `channel_id`, `user_id`, `type`, `title`, `alias`, `introtext`, `fulltext`, `thumb`, `fields`, `likes`, `dislikes`, `hits`, `subscriptions`, `params`,".
            " `ordering`, `access`, `language`, `created`, `modified`, `meta_desc`, `meta_key`, `meta_author`, `share_others`, `featured`, `published` FROM {$this->_dbprefix}mijovideos_{$this->_table} {$this->_where}";

        return array($query, $filename, $fields, $line);
    }

    public function backupPlaylistvideos() {
        $filename = "mijovideos_playlist_videos.sql";
        $fields = array('id', 'playlist_id', 'video_id', 'ordering');
        $line = "INSERT IGNORE INTO {$this->_dbprefix}mijovideos_playlist_categories (".implode(', ', $fields).")";
        $query = "SELECT `id`, `playlist_id`, `video_id`, `ordering` FROM {$this->_dbprefix}mijovideos_playlist_videos {$this->_where}";

        return array($query, $filename, $fields, $line);
    }

    public function backupVideos() {
        $filename = "mijovideos_videos.sql";
        $fields = array('id', 'user_id', 'channel_id', 'product_id', 'title', 'alias', 'introtext', 'fulltext', 'source', 'duration', 'likes', 'dislikes', 'hits', 'access', 'price',
            'created', 'modified', 'featured', 'published', 'fields', 'thumb', 'meta_desc', 'meta_key', 'meta_author', 'params', 'language');
        $line = "INSERT IGNORE INTO {$this->_dbprefix}mijovideos_{$this->_table} (".implode(', ', $fields).")\n";
        $query = "SELECT `id`, `user_id`, `channel_id`, `product_id`, `title`, `alias`, `introtext`, `fulltext`, `source`, `duration`, `likes`, `dislikes`, `hits`, `access`, `price`,".
            " `created`, `modified`, `featured`, `published`, `fields`, `thumb`, `meta_desc`, `meta_key`, `meta_author`, `params`, `language` FROM {$this->_dbprefix}mijovideos_{$this->_table} {$this->_where}";

        return array($query, $filename, $fields, $line);
    }

    public function backupVideocategories() {
        $filename = "mijovideos_video_categories.sql";
        $fields = array('id', 'video_id', 'category_id');
        $line = "INSERT IGNORE INTO {$this->_dbprefix}mijovideos_video_categories (".implode(', ', $fields).")";
        $query = "SELECT `id`, `video_id`, `category_id` FROM {$this->_dbprefix}mijovideos_video_categories {$this->_where}";

        return array($query, $filename, $fields, $line);
    }

    public function backupSubscriptions() {
        $filename = "mijovideos_channel_subscriptions.sql";
        $fields = array('id', 'item_id', 'item_type', 'user_id', 'channel_id', 'created');
        $line = "INSERT IGNORE INTO {$this->_dbprefix}mijovideos_{$this->_table} (".implode(', ', $fields).")";
        $query = "SELECT `id`, `item_id`, `item_type`, `user_id`, `channel_id`, `created` FROM {$this->_dbprefix}mijovideos_{$this->_table} {$this->_where}";

        return array($query, $filename, $fields, $line);
    }

    public function backupLikes() {
        $filename = "mijovideos_likes.sql";
        $fields = array('id', 'channel_id', 'user_id', 'item_id', 'item_type', 'type', 'created');
        $line = "INSERT IGNORE INTO {$this->_dbprefix}mijovideos_{$this->_table} (".implode(', ', $fields).")";
        $query = "SELECT `id`, `channel_id`, `user_id`, `item_id`, `item_type`, `type`, `created` FROM {$this->_dbprefix}mijovideos_{$this->_table} {$this->_where}";

        return array($query, $filename, $fields, $line);
    }

    public function backupReports() {
        $filename = "mijovideos_reports.sql";
        $fields = array('id', 'channel_id', 'user_id', 'item_id', 'item_type', 'reason_id', 'note', 'created', 'modified');
        $line = "INSERT IGNORE INTO {$this->_dbprefix}mijovideos_{$this->_table} (".implode(', ', $fields).")";
        $query = "SELECT `id`, `channel_id`, `user_id`, `item_id`, `item_type`, `reason_id`, `note`, `created`, `modified` FROM {$this->_dbprefix}mijovideos_{$this->_table} {$this->_where}";

        return array($query, $filename, $fields, $line);
    }

    public function backupFiles() {
        $filename = "mijovideos_files.sql";
        $fields = array('id', 'video_id', 'process_type', 'ext', 'file_size', 'source', 'channel_id',  'user_id', 'created', 'modified', 'published');
        $line = "INSERT IGNORE INTO {$this->_dbprefix}mijovideos_{$this->_table} (".implode(', ', $fields).")";
        $query = "SELECT `id`, `video_id`, `process_type`, `ext`, `file_size`, `source`, `channel_id`,  `user_id`, `created`, `modified`, `published` FROM {$this->_dbprefix}mijovideos_{$this->_table} {$this->_where}";

        return array($query, $filename, $fields, $line);
    }

    public function backupReasons() {
        $filename = "mijovideos_report_reasons.sql";
        $fields = array('id', 'parent', 'title', 'alias', 'description', 'access', 'language', 'association', 'published', 'created', 'modified');
        $line = "INSERT IGNORE INTO {$this->_dbprefix}mijovideos_{$this->_table} (".implode(', ', $fields).")";
        $query = "SELECT `id`, `parent`, `title`, `alias`, `description`, `access`, `language`, `association`, `published`, `created`, `modified` FROM {$this->_dbprefix}mijovideos_{$this->_table} {$this->_where}";

        return array($query, $filename, $fields, $line);
    }

    # MijoVideos : Restore
    public function restoreCategories($line) {
        $preg = '/^INSERT IGNORE INTO `?(\w)+mijovideos_categories`?/';

        return array($preg, $line);
    }

    public function restoreChannels($line) {
        $preg = '/^INSERT IGNORE INTO `?(\w)+mijovideos_channels`?/';

        return array($preg, $line);
    }

    public function restorePlayists($line) {
        $preg = '/^INSERT IGNORE INTO `?(\w)+mijovideos_playlists`?/';

        return array($preg, $line);
    }

    public function restorePlayistvideos($line) {
        $preg = '/^INSERT IGNORE INTO `?(\w)+mijovideos_playlist_videos`?/';

        return array($preg, $line);
    }

    public function restoreVideos($line) {
        $preg = '/^INSERT IGNORE INTO `?(\w)+mijovideos_videos`?/';

        return array($preg, $line);
    }

    public function restoreVideocategories($line) {
        $preg = '/^INSERT IGNORE INTO `?(\w)+mijovideos_video_categories`?/';

        return array($preg, $line);
    }

    public function restoreSubscriptions($line) {
        $preg = '/^INSERT IGNORE INTO `?(\w)+mijovideos_channel_subscriptions`?/';

        return array($preg, $line);
    }

    public function restoreLikes($line) {
        $preg = '/^INSERT IGNORE INTO `?(\w)+mijovideos_likes`?/';

        return array($preg, $line);
    }

    public function restoreReports($line) {
        $preg = '/^INSERT IGNORE INTO `?(\w)+mijovideos_reports`?/';

        return array($preg, $line);
    }

    public function restoreFiles($line) {
        $preg = '/^INSERT IGNORE INTO `?(\w)+mijovideos_files`?/';

        return array($preg, $line);
    }

    public function restoreReasons($line) {
        $preg = '/^INSERT IGNORE INTO `?(\w)+mijovideos_report_reasons`?/';

        return array($preg, $line);
    }

    # HWDMediaShare Migration
    protected function _getMediaFile($file, $file_type){
        $db = JFactory::getDBO();
        static $cache = array();

        if (!isset($cache[$file])) {
            $db->setQuery("SELECT MAX(id) AS maxid, basename, ext FROM #__hwdms_files WHERE file_type='{$file_type}' AND element_id = '{$file}'");
            $cache[$file] = $db->loadObject();
        }

        return $cache[$file];
    }

    protected function _getUserChannelId($id){
        $db = JFactory::getDBO();
        static $cache = array();

        if (!isset($cache[$id])) {
            $db->setQuery("SELECT id FROM #__mijovideos_channels WHERE user_id = '{$id}' AND `default` = 1");
            $cache[$id] = $db->loadObject();
        }

        return $cache[$id];
    }

    protected function _getUserChannelName($name){
        $db = JFactory::getDBO();
        static $cache = array();
        $name = strtolower($name);

        if (!isset($cache[$name])) {
            $db->setQuery("SELECT c.id, c.user_id FROM #__mijovideos_channels AS c LEFT JOIN #__users AS u ON u.id = c.user_id LEFT JOIN #__user_usergroup_map AS g ON g.user_id = u.id WHERE g.group_id = '8' AND u.username = '{$name}'");
            $cache[$name] = $db->loadObject();
        }

        return $cache[$name];
    }

    public function migrateHwdMediaShareCats(){
        $db = JFactory::getDBO();

        $cat = "SELECT `id`, `parent_id`, `title`, `alias`, `description`, `params`, `published`, `access`, `metadesc`, `metakey`, `language`, `created_time`, `modified_time` FROM #__categories WHERE extension = 'com_hwdmediashare' ORDER BY `id`";
        $db->setQuery($cat);
        $cats = $db->loadAssocList();

        if (empty($cats)) {
            return false;
        }

        foreach($cats as $cat) {
            if($cat['parent_id'] == 1){
                $cat['parent_id'] = 0;
            }

            $params = @json_decode($cat['params'], true);

            $cat_image = ($params['image']) ? $params['image'] : '';
            $cat_image = str_replace('\/', '/', $cat_image);

            $images = explode('/', $cat_image);
            $image = array_pop($images);

            $imagges = '';
            foreach($images AS $imagge){
                $imagges .= $imagge.'/';
            }
            if(!empty($imagges)){
                $this->_copyImagesFiles(JPATH_SITE.'/'.$imagges, 'categories/', $cat, 'com_hwdmediashare', $image);
            }

            $cat_name = ($this->_jstatus) ? $db->escape($cat['title']) : $db->getEscaped($cat['title']);
			$cat_desc = ($this->_jstatus) ? $db->escape($cat['description']) : $db->getEscaped($cat['description']);

            $q = "INSERT IGNORE INTO `#__mijovideos_categories` (`id`, `parent`, `title`, `alias`, `introtext`, `thumb`, `published`, `ordering`, `access`, `meta_desc`, `meta_key`, `language`, `created`, `modified`) ".
                "VALUES ('".$cat['id']."', '".$cat['parent_id']."', '".$cat_name."', '".$cat['alias']."', '".$cat_desc."', '".$image."', '".$cat['published']."', '1', '".$cat['access']."', '".$cat['metadesc']."', '".$cat['metakey']."', '".$cat['language']."', '".$cat['created_time']."', '".$cat['modified_time']."')";
            $db->setQuery($q);
            $db->query();
        }

        return true;
    }

    public function migrateHwdMediaShareChannels(){
        $db = JFactory::getDBO();

        $chnl = "SELECT `id`, `alias`, `description`, `thumbnail_ext_id`, `access`, `key`, `ordering`, `hits`, `likes`, `dislikes`, `featured`, `published`, `created`, `modified` FROM #__hwdms_users ORDER BY `id`";
        $db->setQuery($chnl);
        $chnls = $db->loadAssocList();

        if (empty($chnls)) {
            return false;
        }

        foreach($chnls as $chnl) {
            $image_name = '';

            if(!empty($chnl['thumbnail_ext_id'])){
                $image_media_file = self::_getMediaFile($chnl['id'], '10');

                $part1 = substr($chnl['key'], 0, 2);
                $part2 = substr($chnl['key'], 2, 2);
                $part3 = substr($chnl['key'], 4, 2);
                $image_name = $image_media_file->basename .'.'. $image_media_file->ext;
                $filename = 'media/com_hwdmediashare/files/' . $part1 .'/'. $part2 .'/'. $part3 .'/'. $image_media_file->basename .'.'. $image_media_file->ext;
                if (file_exists($filename)) {
                    $this->_copyImagesFiles(JPATH_SITE.'/media/com_hwdmediashare/files/' . $part1 .'/'. $part2 .'/'. $part3 .'/', 'channels/', $chnl, 'com_hwdmediashare', $image_name);
                }
            }

			$chnl_desc = ($this->_jstatus) ? $db->escape($chnl['description']) : $db->getEscaped($chnl['description']);

            $q = "UPDATE `#__mijovideos_channels` SET `alias`='{$chnl['alias']}', `introtext`='{$chnl_desc}', `thumb`='{$image_name}', `banner`='{$image_name}', `access`='{$chnl['access']}', `ordering`='{$chnl['ordering']}',
                 `hits`='{$chnl['hits']}', `likes`='{$chnl['likes']}', `dislikes`='{$chnl['dislikes']}', `featured`='{$chnl['featured']}', `published`='{$chnl['published']}', `created`='{$chnl['created']}', `modified`='{$chnl['modified']}'
                 WHERE user_id='{$chnl['id']}'";

            $db->setQuery($q);
            $db->query();
        }

        return true;
    }

    public function migrateHwdMediaSharePlaylists(){
        $db = JFactory::getDBO();

        $play = "SELECT `id`, `thumbnail_ext_id`, `title`, `alias`, `description`, `key`, `hits`, `likes`, `dislikes`, `published`, `featured`, `access`, `params`, `ordering`, `created`, `modified`, `created_user_id` FROM #__hwdms_playlists ORDER BY `id`";
        $db->setQuery($play);
        $plays = $db->loadAssocList();

        if (empty($plays)) {
            return false;
        }

        foreach($plays as $play) {
            $image_name = '';

            if(!empty($play['thumbnail_ext_id'])){
                $image_media_file = self::_getMediaFile($play['id'], '10');

                $part1 = substr($play['key'], 0, 2);
                $part2 = substr($play['key'], 2, 2);
                $part3 = substr($play['key'], 4, 2);
                $image_name = $image_media_file->basename .'.'. $image_media_file->ext;
                $filename = 'media/com_hwdmediashare/files/' . $part1 .'/'. $part2 .'/'. $part3 .'/'. $image_media_file->basename .'.'. $image_media_file->ext;
                if (file_exists($filename)) {
                    $this->_copyImagesFiles(JPATH_SITE.'/media/com_hwdmediashare/files/' . $part1 .'/'. $part2 .'/'. $part3 .'/', 'playlists/', $play, 'com_hwdmediashare', $image_name);
                }
            }

            $chnl_id = self::_getUserChannelId($play['created_user_id']);

			$play_name = ($this->_jstatus) ? $db->escape($play['title']) : $db->getEscaped($play['title']);
			$play_desc = ($this->_jstatus) ? $db->escape($play['description']) : $db->getEscaped($play['description']);

            $q = "INSERT IGNORE INTO `#__mijovideos_playlists` (`id`, `channel_id`, `user_id`, `type`, `title`, `alias`, `introtext`, `thumb`, `likes`, `dislikes`".
                ", `hits`, `published`, `access`, `featured`, `ordering`, `created`, `modified`) ".
                "VALUES ('".$play['id']."', '".$chnl_id->id."', '".$play['created_user_id']."', '0', '".$play_name."', '".$play['alias']."', '".$play_desc."', '".$image_name."', '".$play['likes']."', '".$play['dislikes'].
                "', '".$play['hits']."', '".$play['published']."', '".$play['access']."', '".$play['featured']."', '".$play['ordering']."', '".$play['created']."', '".$play['modified']."')";
            $db->setQuery($q);
            $db->query();
        }

        $playmap = "SELECT `id`, `playlist_id`, `media_id`, `ordering` FROM #__hwdms_playlist_map ORDER BY `id`";
        $db->setQuery($playmap);
        $playmaps = $db->loadAssocList();

        if (empty($playmaps)) {
            return false;
        }

        foreach($playmaps as $playmap) {
            $q = "INSERT IGNORE INTO `#__mijovideos_playlist_videos` (`id`, `playlist_id`, `video_id`, `ordering`) VALUES ('".$playmap['id']."', '".$playmap['playlist_id']."', '".$playmap['media_id']."', '".$playmap['ordering']."')";
            $db->setQuery($q);
            $db->query();
        }

        return true;
    }

    public function migrateHwdMediaShareVideos(){
        $db = JFactory::getDBO();

        $vid = "SELECT m.`id`, m.`title`, m.`alias`, m.`description`, m.`key`, m.`type`, m.`source`, m.`duration`, m.`thumbnail`, m.`thumbnail_ext_id`, m.`likes`, m.`dislikes`, m.`hits`, m.`published`,
                m.`featured`, m.`access`, m.`created`, m.`modified`, m.`params`, m.`created_user_id`
                FROM #__hwdms_media AS m LEFT JOIN #__hwdms_ext AS e ON m.ext_id = e.id WHERE e.media_type = 4 ORDER BY m.`id`";
        $db->setQuery($vid);
        $vids = $db->loadAssocList();

        if (empty($vids)) {
            return false;
        }

        foreach($vids as $vid) {
            $image_name = '';

            if(!empty($vid['thumbnail_ext_id'])){
                $image_media_file = self::_getMediaFile($vid['id'], '10');
                $video_media_file = self::_getMediaFile($vid['id'], '1');

                $part1 = substr($vid['key'], 0, 2);
                $part2 = substr($vid['key'], 2, 2);
                $part3 = substr($vid['key'], 4, 2);
                $image_name = $image_media_file->basename .'.'. $image_media_file->ext;
                $video_name = $video_media_file->basename .'.'. $video_media_file->ext;

                $filename = 'media/com_hwdmediashare/files/'.$part1.'/'.$part2.'/'.$part3.'/'.$image_name;
                if (file_exists($filename)) {
                    $this->_copyImagesFiles(JPATH_SITE.'/media/com_hwdmediashare/files/'. $part1 .'/'. $part2 .'/'. $part3 .'/', 'videos/', $vid, 'com_hwdmediashare', $image_name);
                }
                $this->_copyVideosFiles(JPATH_SITE.'/media/com_hwdmediashare/files/'.$part1.'/'.$part2.'/'.$part3.'/', $vid['id'], $video_name, $vid, 'com_hwdmediashare');
                //$this->_copyVideosFiles(JPATH_SITE.'/media/com_hwdmediashare/files/'. $part1 .'/'. $part2 .'/'. $part3 .'/', $vid['id'], $vid, $video_media_file->basename .'.'. $video_media_file->ext);
                //$vid_url = '/media/com_mijovideos/videos/'.$vid['id'].'/orig/'.$video_name;
                $vid_url = $video_name;
            }

            $chnl_id = self::_getUserChannelId($vid['created_user_id']);

            $params = @json_decode($vid['params'], true);

            $meta_desc = ($params['meta_desc']) ? $params['meta_desc'] : '';
            $meta_keys = ($params['meta_keys']) ? $params['meta_keys'] : '';
            $meta_author = ($params['meta_author']) ? $params['meta_author'] : '';

            $vid_name = ($this->_jstatus) ? $db->escape($vid['title']) : $db->getEscaped($vid['title']);
            $vid_desc = ($this->_jstatus) ? $db->escape($vid['description']) : $db->getEscaped($vid['description']);

            $q = "INSERT IGNORE INTO `#__mijovideos_videos` (`id`, `user_id`, `channel_id`, `product_id`, `title`, `alias`, `introtext`, `source`, `published`, `created`, `modified`, ".
                "`thumb`, `duration`, `likes`, `dislikes`, `hits`, `featured`, `access`, `meta_desc`, `meta_key`, `meta_author`, `language`) ".
                "VALUES ('".$vid['id']."', '".$vid['created_user_id']."', '".$chnl_id->id."', '0', '".$vid_name."', '".$vid['alias']."', '".$vid_desc."', '".$vid_url."', '".$vid['published']."', '".$vid['created']."', '".$vid['modified']
                ."', '".$image_name."', '".$vid['duration']."', '".$vid['likes']."', '".$vid['dislikes']."', '".$vid['hits']."', '".$vid['featured']."', '".$vid['access']."', '".$meta_desc."', '".$meta_keys."', '".$meta_author."', '*')";
            $db->setQuery($q);
            $db->query();
        }

        $cat = "SELECT `id`, `element_id`, `category_id` FROM #__hwdms_category_map WHERE element_type='1' ORDER BY `id`";
        $db->setQuery($cat);
        $cats = $db->loadAssocList();

        if (empty($cats)) {
            return false;
        }

        foreach($cats as $cat) {
            $q = "INSERT IGNORE INTO `#__mijovideos_video_categories` (`id`, `video_id`, `category_id`) VALUES ('".$cat['id']."', '".$cat['element_id']."', '".$cat['category_id']."')";
            $db->setQuery($q);
            $db->query();
        }

        return true;
    }

    public function migrateHwdMediaShareLikes(){
        $db = JFactory::getDBO();

        $like = "SELECT * FROM #__hwdms_likes ORDER BY `id`";
        $db->setQuery($like);
        $likes = $db->loadAssocList();

        if (empty($likes)) {
            return false;
        }

        foreach($likes as $like) {		
			$channel_id = MijoVideos::get('channels')->getDefaultChannel($like['user_id']);

            $q = "INSERT IGNORE INTO `#__mijovideos_likes` (`id`, `channel_id`, `user_id`, `item_id`, `item_type`, `type`, `created`) ".
                "VALUES ('".$like['id']."', '".$channel_id."',, '".$like['user_id']."', '".$like['element_id']."', '".$like['element_type']."', '".$like['element_type']."', '".$like['created']."')";
            $db->setQuery($q);
            $db->query();
        }

        return true;
    }

    public function migrateHwdMediaShareReports(){
        $db = JFactory::getDBO();

        $like = "SELECT * FROM #__hwdms_reports ORDER BY `id`";
        $db->setQuery($like);
        $likes = $db->loadAssocList();

        if (empty($likes)) {
            return false;
        }

        foreach($likes as $like) {		
			$channel_id = MijoVideos::get('channels')->getDefaultChannel($like['user_id']);

            $q = "INSERT IGNORE INTO `#__mijovideos_reports` (`id`, `channel_id`, `user_id`, `item_id`, `item_type`, `reason_id`, `note`, `created`) ".
                "VALUES ('".$like['id']."', '".$channel_id."',, '".$like['user_id']."', '".$like['element_id']."', '".$like['element_type']."', '".$like['report_id']."', '".$like['description']."', '".$like['created']."')";
            $db->setQuery($q);
            $db->query();
        }

        return true;
    }

    # Contus HD Video Share Migration
    public function migrateContusHDVideoShareCats(){
        $db = JFactory::getDBO();

        $cat = "SELECT * FROM #__hdflv_category ORDER BY `id`";
        $db->setQuery($cat);
        $cats = $db->loadAssocList();

        if (empty($cats)) {
            return false;
        }

        foreach($cats as $cat) {
			$cat_name = ($this->_jstatus) ? $db->escape($cat['category']) : $db->getEscaped($cat['category']);

            $q = "INSERT IGNORE INTO `#__mijovideos_categories` (`id`, `parent`, `title`, `alias`, `published`, `access`, `ordering`, `language`) ".
                "VALUES ('".$cat['id']."', '".$cat['parent_id']."', '".$cat_name."', '".$cat['seo_category']."', '".$cat['published']."', '1', '".$cat['ordering']."', '*')";
            $db->setQuery($q);
            $db->query();
        }

        return true;
    }

    public function migrateContusHDVideoShareVideos(){
        $db = JFactory::getDBO();

        $vid = "SELECT * FROM #__hdflv_upload ORDER BY id";
        $db->setQuery($vid);
        $vids = $db->loadAssocList();

        if (empty($vids)) {
            return false;
        }

        foreach($vids as $vid) {
            $image_name = $vid_url = '';

            if(!empty($vid['filepath'])){
                switch($vid['filepath']){
                    case 'File':
                        if(!empty($vid['thumburl'])){
                            $image_name = $vid['thumburl'];

                            $this->_copyImagesFiles(JPATH_SITE.'/components/com_contushdvideoshare/videos/', 'videos/', $vid, 'com_contushdvideoshare');
                        }
                        if(!empty($vid['videourl'])){
                            //$vid_url = '/media/com_mijovideos/videos/'.$vid['id'].'/orig/'.$vid['videourl'];
                            $vid_url = $vid['videourl'];
                        }
                        break;
                    case 'Direct URL':
                    case 'Youtube Videos':
                    case 'Dailymotion Videos':
                    case 'Vimeo Videos':
                        if(!empty($vid['previewurl'])){
                            $image_name = $vid['previewurl'];
                        }
                        if(!empty($vid['videourl'])){
                            $vid_url = $vid['videourl'];
                        }
                        break;
                    default:
                        if(!empty($vid['previewurl'])){
                            $image_name = $vid['previewurl'];
                        }
                        if(!empty($vid['videourl'])){
                            $vid_url = $vid['videourl'];
                        }
                        break;
                }
            }

            $chnl_id = self::_getUserChannelId($vid['memberid']);

            $this->_copyVideosFiles(JPATH_SITE.'/components/com_contushdvideoshare/videos/', $vid['id'], $vid['videourl'], 'com_contushdvideoshare');
            //$this->_copyVideosFiles(JPATH_SITE.'/media/com_contushdvideoshare/videos/', $vid['id'], $vid['hdurl']);

            $vid_name = ($this->_jstatus) ? $db->escape($vid['title']) : $db->getEscaped($vid['title']);
            $vid_desc = ($this->_jstatus) ? $db->escape($vid['description']) : $db->getEscaped($vid['description']);

            $q = "INSERT IGNORE INTO `#__mijovideos_videos` (`id`, `user_id`, `channel_id`, `product_id`, `title`, `alias`, `introtext`, `source`, `published`, `created`, `modified`, ".
                "`thumb`, `hits`, `featured`, `access`, `language`) ".
                "VALUES ('".$vid['id']."', '".$vid['memberid']."', '".$chnl_id->id."', '0', '".$vid_name."', '".$vid['seotitle']."', '".$vid_desc."', '".$vid_url."', '".$vid['published']."', '".$vid['created_date']."', '".$vid['addedon']
                ."', '".$image_name."', '".$vid['times_viewed']."', '".$vid['featured']."', '1', '*')";
            $db->setQuery($q);
            $db->query();
        }

        $cat = "SELECT `vid`, `catid` FROM #__hdflv_video_category ORDER BY `vid`";
        $db->setQuery($cat);
        $cats = $db->loadAssocList();

        if (empty($cats)) {
            return false;
        }

        foreach($cats as $cat) {
            $q = "INSERT IGNORE INTO `#__mijovideos_video_categories` (`video_id`, `category_id`) VALUES ('".$cat['vid']."', '".$cat['catid']."')";
            $db->setQuery($q);
            $db->query();
        }

        return true;
    }

    # Jom Webplayer Migration
    public function migrateJomWebplayerCats(){
        $db = JFactory::getDBO();

        $cat = "SELECT * FROM #__jomwebplayer_category ORDER BY id";
        $db->setQuery($cat);
        $cats = $db->loadAssocList();

        if (empty($cats)) {
            return false;
        }

        foreach($cats as $cat) {
            $image_name = '';

            $cat_image = ($cat['image']) ? $cat['image'] : '';
            $images = explode('/', $cat_image);
            $image_name = array_pop($images);

            if(!empty($image_name)){
                $this->_copyImagesFiles(JPATH_SITE.'/media/com_jomwebplayer/', 'categories/', $cat, 'com_jomwebplayer');
            }

            $alias = JApplication::stringURLSafe(htmlspecialchars_decode($cat['name'], ENT_QUOTES));
            $cat_name = ($this->_jstatus) ? $db->escape($cat['name']) : $db->getEscaped($cat['name']);

            $q = "INSERT IGNORE INTO `#__mijovideos_categories` (`id`, `parent`, `title`, `alias`, `published`, `ordering`, `access`, `thumb`, `meta_key`, `meta_desc`, `language`) ".
                "VALUES ('".$cat['id']."', '".$cat['parent']."', '".$cat_name."', '".$alias."', '".$cat['published']."', '".$cat['ordering']."', '1', '".$image_name."', '".$cat['metakeywords']."', '".$cat['metadescription']."', '*')";
            $db->setQuery($q);
            $db->query();
        }

        return true;
    }

    public function migrateJomWebplayerVideos(){
        $db = JFactory::getDBO();

        $vid = "SELECT * FROM #__jomwebplayer_videos ORDER BY id";
        $db->setQuery($vid);
        $vids = $db->loadAssocList();

        if (empty($vids)) {
            return false;
        }

        foreach($vids as $vid) {
            $image_name = $vid_url = '';

            if(!empty($vid['type'])){
                switch($vid['type']){
                    case 'General Upload':
                        if(!empty($vid['thumb'])){
                            $image_name = $vid['thumb'];

                            $images = explode('/', $image_name);
                            $image_name = array_pop($images);

                            if(!empty($image_name)){
                                $this->_copyImagesFiles(JPATH_SITE.'/media/com_jomwebplayer/', 'videos/', $vid, 'com_jomwebplayer');
                            }
                        }
                        if(!empty($vid['video'])){
                            //$vid_url = '/media/com_mijovideos/videos/'.$vid['id'].'/orig/'.$vid['video'];
                            $vid_url = $vid['video'];
                        }
                        break;
                    case 'Direct URL':
                    case 'Youtube Videos':
                    case 'Dailymotion Videos':
                    case 'Vimeo Videos':
                        if(!empty($vid['thumb'])){
                            $image_name = $vid['thumb'];
                        }
                        if(!empty($vid['video'])){
                            $vid_url = $vid['video'];
                        }
                        break;
                    default:
                        if(!empty($vid['thumb'])){
                            $image_name = $vid['thumb'];
                        }
                        if(!empty($vid['video'])){
                            $vid_url = $vid['video'];
                        }
                        break;
                }
            }

            $chnl_id = self::_getUserChannelName($vid['user']);
            $cat_id = self::_getJwCatID($vid['category']);

            $this->_copyVideosFiles(JPATH_SITE.'/media/com_jomwebplayer/', $vid['id'], $vid['video'], $vid, 'com_jomwebplayer');
            //$this->_copyVideosFiles(JPATH_SITE.'/media/com_jomwebplayer/', $vid['id'], $vid['hdvideo']);

            $alias = JApplication::stringURLSafe(htmlspecialchars_decode($vid['title'], ENT_QUOTES));
            $vid_name = ($this->_jstatus) ? $db->escape($vid['title']) : $db->getEscaped($vid['title']);
            $vid_desc = ($this->_jstatus) ? $db->escape($vid['description']) : $db->getEscaped($vid['description']);

            $q = "INSERT IGNORE INTO `#__mijovideos_videos` (`id`, `user_id`, `channel_id`, `product_id`, `title`, `alias`, `introtext`, `source`, `published`, ".
                "`thumb`, `hits`, `featured`, `access`, `language`) ".
                "VALUES ('".$vid['id']."', '".$chnl_id->user_id."', '".$chnl_id->id."', '0', '".$vid_name."', '".$alias."', '".$vid_desc."', '".$vid_url."', '".$vid['published']
                ."', '".$image_name."', '".$vid['views']."', '".$vid['featured']."', '1', '*')";
            $db->setQuery($q);
            $db->query();

            $q = "INSERT IGNORE INTO `#__mijovideos_video_categories` (`video_id`, `category_id`) VALUES ('".$vid['id']."', '".$cat_id->id."')";
            $db->setQuery($q);
            $db->query();
        }

        return true;
    }

    protected function _getJwCatID($name){
        $db = JFactory::getDBO();
        static $cache = array();

        if (!isset($cache[$name])) {
            $db->setQuery("SELECT id FROM #__jomwebplayer_category WHERE name = '{$name}'");
            $cache[$name] = $db->loadObject();
        }

        return $cache[$name];
    }

    # HD Webplayer Migration
    public function migrateHdwPlayerCats(){
        $db = JFactory::getDBO();

        $cat = "SELECT * FROM #__hdwplayer_category ORDER BY id";
        $db->setQuery($cat);
        $cats = $db->loadAssocList();

        if (empty($cats)) {
            return false;
        }

        foreach($cats as $cat) {
            $image_name = '';

            $cat_image = ($cat['image']) ? $cat['image'] : '';
            $images = explode('/', $cat_image);
            $image_name = array_pop($images);

            if(!empty($image_name)){
                $this->_copyImagesFiles(JPATH_SITE.'/media/com_hdwplayer/', 'categories/', $cat, 'com_hdwplayer');
            }

            $alias = JApplication::stringURLSafe(htmlspecialchars_decode($cat['name'], ENT_QUOTES));
            $cat_name = ($this->_jstatus) ? $db->escape($cat['name']) : $db->getEscaped($cat['name']);

            $q = "INSERT IGNORE INTO `#__mijovideos_categories` (`id`, `parent`, `title`, `alias`, `published`, `ordering`, `access`, `thumb`, `meta_key`, `meta_desc`, `language`) ".
                "VALUES ('".$cat['id']."', '".$cat['parent']."', '".$cat_name."', '".$alias."', '".$cat['published']."', '".$cat['ordering']."', '1', '".$image_name."', '".$cat['metakeywords']."', '".$cat['metadescription']."', '*')";
            $db->setQuery($q);
            $db->query();
        }

        return true;
    }

    public function migrateHdwPlayerVideos(){
        $db = JFactory::getDBO();

        $vid = "SELECT * FROM #__hdwplayer_videos ORDER BY id";
        $db->setQuery($vid);
        $vids = $db->loadAssocList();

        if (empty($vids)) {
            return false;
        }

        foreach($vids as $vid) {
            $image_name = $vid_url ='';

            if(!empty($vid['type'])){
                switch($vid['type']){
                    case 'General Upload':
                        if(!empty($vid['thumb'])){
                            $image_name = $vid['thumb'];

                            $images = explode('/', $image_name);
                            $image_name = array_pop($images);

                            if(!empty($image_name)){
                                $this->_copyImagesFiles(JPATH_SITE.'/media/com_hdwplayer/', 'videos/', $vid, 'com_hdwplayer');
                            }
                        }
                        if(!empty($vid['video'])){
                            //$vid_url = '/media/com_mijovideos/videos/'.$vid['id'].'/orig/'.$vid['video'];
                            $vid_url = $vid['video'];
                        }
                        break;
                    case 'Direct URL':
                    case 'Youtube Videos':
                    case 'Dailymotion Videos':
                    case 'Vimeo Videos':
                        if(!empty($vid['thumb'])){
                            $image_name = $vid['thumb'];
                        }
                        if(!empty($vid['video'])){
                            $vid_url = $vid['video'];
                        }
                        break;
                    default:
                        if(!empty($vid['thumb'])){
                            $image_name = $vid['thumb'];
                        }
                        if(!empty($vid['video'])){
                            $vid_url = $vid['video'];
                        }
                        break;
                }
            }

            $chnl_id = self::_getUserChannelName($vid['user']);
            $cat_id = self::_getHDCatID($vid['category']);

            $this->_copyVideosFiles(JPATH_SITE.'/media/com_hdwplayer/', $vid['id'], $vid['video'], $vid, 'com_hdwplayer');
            //$this->_copyVideosFiles(JPATH_SITE.'/media/com_hdwplayer/', $vid['id'], $vid['hdvideo'], $vid);

            $alias = JApplication::stringURLSafe(htmlspecialchars_decode($vid['title'], ENT_QUOTES));
            $vid_name = ($this->_jstatus) ? $db->escape($vid['title']) : $db->getEscaped($vid['title']);
            $vid_desc = ($this->_jstatus) ? $db->escape($vid['description']) : $db->getEscaped($vid['description']);

            $q = "INSERT IGNORE INTO `#__mijovideos_videos` (`id`, `user_id`, `channel_id`, `product_id`, `title`, `alias`, `introtext`, `source`, `published`, ".
                "`thumb`, `hits`, `featured`, `access`, `language`) ".
                "VALUES ('".$vid['id']."', '".$chnl_id->user_id."', '".$chnl_id->id."', '0', '".$vid_name."', '".$alias."', '".$vid_desc."', '".$vid_url."', '".$vid['published']
                ."', '".$image_name."', '".$vid['views']."', '".$vid['featured']."', '1', '*')";
            $db->setQuery($q);
            $db->query();

            $q = "INSERT IGNORE INTO `#__mijovideos_video_categories` (`video_id`, `category_id`) VALUES ('".$vid['id']."', '".$cat_id->id."')";
            $db->setQuery($q);
            $db->query();
        }

        return true;
    }

    protected function _getHDCatID($name){
        $db = JFactory::getDBO();
        static $cache = array();

        if (!isset($cache[$name])) {
            $db->setQuery("SELECT id FROM #__hdwplayer_category WHERE name = '{$name}'");
            $cache[$name] = $db->loadObject();
        }

        return $cache[$name];
    }

    # AllVideoShare Migration
    public function migrateAllVideoShareCats(){
        $db = JFactory::getDBO();

        $cat = "SELECT * FROM #__allvideoshare_categories ORDER BY id";
        $db->setQuery($cat);
        $cats = $db->loadAssocList();

        if (empty($cats)) {
            return false;
        }

        foreach($cats as $cat) {
            $image_name = '';
            $cat_image = ($cat['thumb']) ? $cat['thumb'] : '';
            $images = explode('/', $cat_image);
            $image_name = array_pop($images);
            $cat_slug = array_pop($images);

            if(!empty($image_name)){
                $this->_copyImagesFiles(JPATH_SITE.'/media/com_allvideoshare/'.$cat_slug.'/', 'categories/', $cat, 'com_allvideoshare');
            }

            $cat_name = ($this->_jstatus) ? $db->escape($cat['name']) : $db->getEscaped($cat['name']);

            $q = "INSERT IGNORE INTO `#__mijovideos_categories` (`id`, `parent`, `title`, `alias`, `published`, `ordering`, `access`, `thumb`, `meta_key`, `meta_desc`, `language`) ".
                "VALUES ('".$cat['id']."', '".$cat['parent']."', '".$cat_name."', '".$cat['slug']."', '".$cat['published']."', '".$cat['ordering']."', '1', '".$image_name."', '".$cat['metakeywords']."', '".$cat['metadescription']."', '*')";
            $db->setQuery($q);
            $db->query();
        }

        return true;
    }

    public function migrateAllVideoShareVideos(){
        $db = JFactory::getDBO();

        $vid = "SELECT * FROM #__allvideoshare_videos ORDER BY id";
        $db->setQuery($vid);
        $vids = $db->loadAssocList();

        if (empty($vids)) {
            return false;
        }

        foreach($vids as $vid) {
            $image_name = '';
            $vid_url = $vid['video'];

            $chnl_id = self::_getUserChannelName($vid['user']);
            $cat_id = self::_getAllVideoShareCatID($vid['category']);

            if(!empty($vid['type'])){
                switch($vid['type']){
                    case 'upload':
                        if(!empty($vid['thumb'])){
                            $image_name = $vid['thumb'];

                            $images = explode('/', $image_name);
                            $image_name = array_pop($images);
                            $cat_slug = array_pop($images);

                            if(!empty($image_name)){
                                $this->_copyImagesFiles(JPATH_SITE.'/media/com_allvideoshare/'.$cat_slug.'/', 'videos/', $vid, 'com_allvideoshare');
                            }
                        }
                        if(!empty($vid['video'])){
                            //$vid_url = '/media/com_mijovideos/videos/'.$vid['id'].'/orig/'.$vid['video'];
                            $vid_url = $vid['video'];
                        }
                        break;
                    case 'youtube':
                        if(!empty($vid['thumb'])){
                            $image_name = $vid['thumb'];
                        }
                        if(!empty($vid['video'])){
                            $vid_url = $vid['video'];
                        }
                        break;
                    default:
                        if(!empty($vid['thumb'])){
                            $image_name = $vid['thumb'];
                        }
                        if(!empty($vid['video'])){
                            $vid_url = $vid['video'];
                        }
                        break;
                }
            }

            //$cat_alias = JApplication::stringURLSafe(htmlspecialchars_decode($vid['title'], ENT_QUOTES));

            $this->_copyVideosFiles(JPATH_SITE.'/media/com_allvideoshare/'.$cat_id->slug.'/', $vid['id'], $vid['video'], $vid, 'com_allvideoshare', $cat_id->slug);
            //$this->_copyVideosFiles(JPATH_SITE.'/media/com_allvideoshare/'.$cat_id->slug.'/', $vid['id'], $vid['hdvideo'], $vid);

            $vid_name = ($this->_jstatus) ? $db->escape($vid['title']) : $db->getEscaped($vid['title']);
            $vid_desc = ($this->_jstatus) ? $db->escape($vid['description']) : $db->getEscaped($vid['description']);

            $q = "INSERT IGNORE INTO `#__mijovideos_videos` (`id`, `user_id`, `channel_id`, `product_id`, `title`, `alias`, `introtext`, `source`, `published`, ".
                "`thumb`, `hits`, `featured`, `access`, `language`) ".
                "VALUES ('".$vid['id']."', '".$chnl_id->user_id."', '".$chnl_id->id."', '0', '".$vid_name."', '".$vid['slug']."', '".$vid_desc."', '".$vid_url."', '".$vid['published']
                ."', '".$image_name."', '".$vid['views']."', '".$vid['featured']."', '1', '*')";
            $db->setQuery($q);
            $db->query();

            $q = "INSERT IGNORE INTO `#__mijovideos_video_categories` (`video_id`, `category_id`) VALUES ('".$vid['id']."', '".$cat_id->id."')";
            $db->setQuery($q);
            $db->query();
        }

        return true;
    }

    protected function _getAllVideoShareCatID($name){
        $db = JFactory::getDBO();
        static $cache = array();

        if (!isset($cache[$name])) {
            $db->setQuery("SELECT id, slug FROM #__allvideoshare_categories WHERE name = '{$name}'");
            $cache[$name] = $db->loadObject();
        }

        return $cache[$name];
    }

    # XMovies Migration
    public function migrateXMoviesCats(){
        $db = JFactory::getDBO();

        $cat = "SELECT `id`, `parent_id`, `title`, `alias`, `description`, `params`, `published`, `access`, `metadesc`, `metakey`, `language` FROM #__categories WHERE extension = 'com_xmovie' ORDER BY `id`";
        $db->setQuery($cat);
        $cats = $db->loadAssocList();

        if (empty($cats)) {
            return false;
        }

        foreach($cats as $cat) {
            if($cat['parent_id'] == 1){
                $cat['parent_id'] = 0;
            }

            $params = @json_decode($cat['params'], true);

            $cat_image = ($params['image']) ? $params['image'] : '';
            $cat_image = str_replace('\/', '/', $cat_image);

            $images = explode('/', $cat_image);
            $image = array_pop($images);

            $imagges = '';
            foreach($images AS $imagge){
                $imagges .= $imagge.'/';
            }
            if(!empty($imagges)){
                $this->_copyImagesFiles(JPATH_SITE.'/'.$imagges, 'categories/');
            }

            $cat_name = ($this->_jstatus) ? $db->escape($cat['title']) : $db->getEscaped($cat['title']);
            $cat_desc = ($this->_jstatus) ? $db->escape($cat['description']) : $db->getEscaped($cat['description']);

            $q = "INSERT IGNORE INTO `#__mijovideos_categories` (`id`, `parent`, `title`, `alias`, `introtext`, `thumb`, `published`, `ordering`, `access`, `meta_desc`, `meta_key`, `language`) ".
                "VALUES ('".$cat['id']."', '".$cat['parent_id']."', '".$cat_name."', '".$cat['alias']."', '".$cat_desc."', '".$image."', '".$cat['published']."', '1', '".$cat['access']."', '".$cat['metadesc']."', '".$cat['metakey']."', '".$cat['language']."')";
            $db->setQuery($q);
            $db->query();
        }

        return true;
    }

    public function migrateXMoviesVideos(){
        $db = JFactory::getDBO();

        $vid = "SELECT * FROM #__xmovie_movies ORDER BY id";
        $db->setQuery($vid);
        $vids = $db->loadAssocList();

        if (empty($vids)) {
            return false;
        }

        foreach($vids as $vid) {
            if(!empty($vid['link'])){
                $vid_url = $vid['link'];
            }

            $chnl_id = self::_getUserChannelId($vid['user_id']);

            $vid_name = ($this->_jstatus) ? $db->escape($vid['title']) : $db->getEscaped($vid['title']);
            $vid_desc = ($this->_jstatus) ? $db->escape($vid['description']) : $db->getEscaped($vid['description']);

            $q = "INSERT IGNORE INTO `#__mijovideos_videos` (`id`, `user_id`, `channel_id`, `product_id`, `title`, `alias`, `introtext`, `source`, `published`, ".
                "`thumb`, `hits`, `featured`, `access`, `meta_key`, `meta_desc`, `meta_author`, `language`) ".
                "VALUES ('".$vid['id']."', '".$vid['user_id']."', '".$chnl_id->id."', '0', '".$vid_name."', '".$vid['alias']."', '".$vid_desc."', '".$vid_url."', '".$vid['published']
                ."', '".$vid['thumb']."', '".$vid['hits']."', '".$vid['featured']."', '".$vid['access']."', '".$vid['metakey']."', '".$vid['metadesc']."', '".$vid['metaauthor']."', '*')";
            $db->setQuery($q);
            $db->query();

            $q = "INSERT IGNORE INTO `#__mijovideos_video_categories` (`video_id`, `category_id`) VALUES ('".$vid['id']."', '".$vid['catid']."')";
            $db->setQuery($q);
            $db->query();
        }

        return true;
    }

    # VideoFlow Migration
    public function migrateVideoFlowCats(){
        $db = JFactory::getDBO();

        $cat = "SELECT * FROM #__vflow_categories ORDER BY id";
        $db->setQuery($cat);
        $cats = $db->loadAssocList();

        if (empty($cats)) {
            return false;
        }

        foreach($cats as $cat) {

            $cat_image = ($cat['pixlink']) ? $cat['pixlink'] : '';
            $cat_image = str_replace('\/', '/', $cat_image);

            $images = explode('/', $cat_image);
            $image = array_pop($images);

            $imagges = '';
            foreach($images AS $imagge){
                $imagges .= $imagge.'/';
            }
            if(!empty($imagges)){
                $this->_copyImagesFiles(JPATH_SITE.'/'.$imagges, 'categories/');
            }

            $alias = JApplication::stringURLSafe(htmlspecialchars_decode($cat['name'], ENT_QUOTES));
            $cat_name = ($this->_jstatus) ? $db->escape($cat['name']) : $db->getEscaped($cat['name']);
            $cat_desc = ($this->_jstatus) ? $db->escape($cat['desc']) : $db->getEscaped($cat['desc']);

            $q = "INSERT IGNORE INTO `#__mijovideos_categories` (`id`, `parent`, `title`, `alias`, `introtext`, `thumb`, `published`, `ordering`, `access`, `language`) ".
                "VALUES ('".$cat['id']."', '0', '".$cat_name."', '".$alias."', '".$cat_desc."', '".$image."', '1', '1', '1', '".$cat['language']."')";
            $db->setQuery($q);
            $db->query();
        }

        return true;
    }

    public function migrateVideoFlowVideos(){
        $db = JFactory::getDBO();

        $vid = "SELECT * FROM #__vflow_data ORDER BY id";
        $db->setQuery($vid);
        $vids = $db->loadAssocList();

        if (empty($vids)) {
            return false;
        }

        foreach($vids as $vid) {
            if(!empty($vid['medialink'])){
                $vid_url = $vid['medialink'];
            }

            $this->_copyImagesFiles(JPATH_SITE.'/videoflow/photos/', 'videos/');

            $chnl_id = self::_getUserChannelId($vid['user_id']);

            $alias = JApplication::stringURLSafe(htmlspecialchars_decode($vid['title'], ENT_QUOTES));
            $vid_name = ($this->_jstatus) ? $db->escape($vid['title']) : $db->getEscaped($vid['title']);
            $vid_desc = ($this->_jstatus) ? $db->escape($vid['details']) : $db->getEscaped($vid['details']);

            $q = "INSERT IGNORE INTO `#__mijovideos_videos` (`id`, `user_id`, `channel_id`, `product_id`, `title`, `alias`, `introtext`, `source`, `published`, ".
                "`thumb`, `hits`, `access`, `featured`, `language`) ".
                "VALUES ('".$vid['id']."', '".$vid['user_id']."', '".$chnl_id->id."', '0', '".$vid_name."', '".$alias."', '".$vid_desc."', '".$vid_url."', '".$vid['published']
                ."', '".$vid['file']."', '".$vid['views']."', '1', '0', '*')";
            $db->setQuery($q);
            $db->query();

            $q = "INSERT IGNORE INTO `#__mijovideos_video_categories` (`video_id`, `category_id`) VALUES ('".$vid['id']."', '".$vid['cat']."')";
            $db->setQuery($q);
            $db->query();
        }

        return true;
    }

    # HD FLV Player Migration
    public function migrateHdFlvPlayerCats(){
        $db = JFactory::getDBO();

        $cat = "SELECT * FROM #__hdflvplayername ORDER BY id";
        $db->setQuery($cat);
        $cats = $db->loadAssocList();

        if (empty($cats)) {
            return false;
        }

        foreach($cats as $cat) {
            $alias = JApplication::stringURLSafe(htmlspecialchars_decode($cat['name'], ENT_QUOTES));
			$cat_name = ($this->_jstatus) ? $db->escape($cat['name']) : $db->getEscaped($cat['name']);
			
            $q = "INSERT IGNORE INTO `#__mijovideos_categories` (`id`, `parent`, `title`, `alias`, `published`, `ordering`, `access`, `language`) ".
                "VALUES ('".$cat['id']."', '0', '".$cat_name."', '".$alias."', '".$cat['published']."', '1', '1', '*')";
            $db->setQuery($q);
            $db->query();
        }

        return true;
    }

    public function migrateHdFlvPlayerVideos(){
        $db = JFactory::getDBO();

        $vid = "SELECT * FROM #__hdflvplayerupload ORDER BY id";
        $db->setQuery($vid);
        $vids = $db->loadAssocList();

        if (empty($vids)) {
            return false;
        }

        foreach($vids as $vid) {
            $image_name = $vid_url ='';

            if(!empty($vid['filepath'])){
                switch($vid['filepath']){
                    case 'File':
                        if(!empty($vid['thumburl'])){
                            $image_name = $vid['thumburl'];
                        }

                        $this->_copyImagesFiles(JPATH_SITE.'/components/com_hdflvplayer/videos/', 'videos/', $vid, 'com_hdflvplayer');
                        $this->_copyVideosFiles(JPATH_SITE.'/components/com_hdflvplayer/videos/', $vid['id'], $vid['title'], $vid, 'com_hdflvplayer');

                        if(!empty($vid['videourl'])){
                            //$vid_url = '/media/com_mijovideos/videos/'.$vid['id'].'/orig/'.$vid['videourl'];
                            $vid_url = $vid['videourl'];
                        }
                        break;
                    case 'Youtube':
                    case 'Url':
                        if(!empty($vid['thumburl'])){
                            $image_name = $vid['thumburl'];
                        }
                        if(!empty($vid['videourl'])){
                            $vid_url = $vid['videourl'];
                        }
                        break;
                }
            }

            //$this->_copyVideosFiles(JPATH_SITE.'/components/com_hdflvplayer/videos/', $vid['id'], $vid['hdurl'], $vid, 'com_hdflvplayer');

            $alias = JApplication::stringURLSafe(htmlspecialchars_decode($vid['title'], ENT_QUOTES));
            $vid_name = ($this->_jstatus) ? $db->escape($vid['title']) : $db->getEscaped($vid['title']);
            $vid_desc = ($this->_jstatus) ? $db->escape($vid['description']) : $db->getEscaped($vid['description']);

            $q = "INSERT IGNORE INTO `#__mijovideos_videos` (`id`, `user_id`, `channel_id`, `product_id`, `title`, `alias`, `introtext`, `source`, `published`, ".
                "`thumb`, `hits`, `duration`, `access`, `featured`, `language`) ".
                "VALUES ('".$vid['id']."', '0', '".$vid['playlistid']."', '0', '".$vid_name."', '".$alias."', '".$vid_desc."', '".$vid_url."', '".$vid['published']
                ."', '".$image_name."', '".$vid['times_viewed']."', '".$vid['duration']."', '".$vid['access']."', '0', '*')";
            $db->setQuery($q);
            $db->query();

            $q = "INSERT IGNORE INTO `#__mijovideos_video_categories` (`video_id`, `category_id`) VALUES ('".$vid['id']."', '".$vid['playlistid']."')";
            $db->setQuery($q);
            $db->query();
        }

        return true;
    }

    public function _copyImagesFiles($dir, $source = '', $id, $vid, $component = NULL, $thumb_name = NULL) {
        foreach (glob($dir . "*") as $filename) {
            if (JFolder::exists($filename)) {
                continue;
            }

            if (file_exists('/media/com_mijovideos/images/'. $source . $id .'/orig/'.$thumb_name)) {
                continue;
            }

            if (!JFolder::exists('/media/com_mijovideos/images/'. $source . $id .'/orig/')) {
                mkdir(JPATH_SITE . '/media/com_mijovideos/images/'. $source . $id .'/orig/', 0777, true);
            }

            if($component == 'com_hwdmediashare'){
                if($thumb_name == basename($filename)){
                    if (!JFile::copy($filename, JPATH_SITE . '/media/com_mijovideos/images/'. $source . $id . '/orig/' . basename($filename))){
                        echo 'Failed to copy <i>' . $filename . '</i> to image directory.<br />';
                    }
                }
            }
            else if($component == 'com_contushdvideoshare'){
                if($vid['filepath'] == 'File'){

                    if($vid['thumburl'] == basename($filename)){
                        if (!JFile::copy($filename, JPATH_SITE . '/media/com_mijovideos/images/'. $source . $id . '/orig/' . basename($filename))){
                            echo 'Failed to copy <i>' . $filename . '</i> to image directory.<br />';
                        }
                    }
                }
            }
            else if($component == 'com_hdwplayer' || $component == 'com_jomwebplayer'){
                if($vid['type'] == 'Upload'){
                    $imagess = explode('/', $vid['image']);
                    $imagess_name = array_pop($imagess);

                    if($imagess_name == basename($filename)){
                        if (!JFile::copy($filename, JPATH_SITE . '/media/com_mijovideos/images/'. $source . $id . '/orig/' . basename($filename))){
                            echo 'Failed to copy <i>' . $filename . '</i> to image directory.<br />';
                        }
                    }
                }
                if($vid['type'] == 'General Upload'){
                    $imagess = explode('/', $vid['thumb']);
                    $imagess_name = array_pop($imagess);

                    if($imagess_name == basename($filename)){
                        if (!JFile::copy($filename, JPATH_SITE . '/media/com_mijovideos/images/'. $source . $id . '/orig/' . basename($filename))){
                            echo 'Failed to copy <i>' . $filename . '</i> to image directory.<br />';
                        }
                    }
                }
            }
            else if($component == 'com_allvideoshare'){
                if($vid['type'] == 'upload'){
                    $thumbss = explode('/', $vid['thumb']);
                    $thumbss_name = array_pop($thumbss);

                    if($thumbss_name == basename($filename)){
                        if (!JFile::copy($filename, JPATH_SITE . '/media/com_mijovideos/images/'. $source . $id . '/orig/' . basename($filename))){
                            echo 'Failed to copy <i>' . $filename . '</i> to image directory.<br />';
                        }
                    }
                }
            }
            else if($component == 'com_hdflvplayer'){
                if($vid['thumburl'] == basename($filename)){
                    if (!JFile::copy($filename, JPATH_SITE . '/media/com_mijovideos/images/'. $source . $id . '/orig/' . basename($filename))){
                        echo 'Failed to copy <i>' . $filename . '</i> to image directory.<br />';
                    }
                }
            }
            else {
                if (!JFile::copy($filename, JPATH_SITE . '/media/com_mijovideos/images/'. $source . $id . '/orig/' . basename($filename))){
                    echo 'Failed to copy <i>' . $filename . '</i> to image directory.<br />';
                }
            }
        }
    }

    public function _copyVideosFiles($dir, $id, $video_name, $vid, $component = NULL) {
        foreach (glob($dir . "*") as $filename) {
            $name_id = explode('_', $video_name);

            if (JFolder::exists($filename)) {
                continue;
            }

            if (file_exists('/media/com_mijovideos/videos/'. $id .'/orig/'.$video_name)) {
                continue;
            }

            if (!JFolder::exists('/media/com_mijovideos/videos/'. $id .'/orig/')) {
                mkdir(JPATH_SITE . '/media/com_mijovideos/videos/'. $id .'/orig/', 0777, true);
            }

            if($component == 'com_hwdmediashare'){
                if($video_name == basename($filename)){
                    if (!JFile::copy($filename, JPATH_SITE . '/media/com_mijovideos/videos/' . $id . '/orig/' . basename($filename))){
                        echo 'Failed to copy <i>' . $filename . '</i> to image directory.<br />';
                    }
                }
            }
            else if($component == 'com_contushdvideoshare'){
                if($name_id[0] == $id){
                    if (!JFile::copy($filename, JPATH_SITE . '/media/com_mijovideos/videos/' . $id . '/orig/' . basename($filename))){
                        echo 'Failed to copy <i>' . $filename . '</i> to image directory.<br />';
                    }
                }
            }
            else if($component == 'com_jomwebplayer'){
                if($vid['type'] == 'General Upload'){
                    $videoss = explode('/', $vid['video']);
                    $videoss_name = array_pop($videoss);
                    if($videoss_name == basename($filename)){
                        if (!JFile::copy($filename, JPATH_SITE . '/media/com_mijovideos/videos/' . $id . '/orig/' . basename($filename))){
                            echo 'Failed to copy <i>' . $filename . '</i> to image directory.<br />';
                        }
                    }
                }
            }
            else if($component == 'com_hdwplayer'){
                if($vid['type'] == 'General Upload'){
                    $videoss = explode('/', $vid['video']);
                    $videoss_name = array_pop($videoss);
                    if($videoss_name == basename($filename)){
                        if (!JFile::copy($filename, JPATH_SITE . '/media/com_mijovideos/videos/' . $id . '/orig/' . basename($filename))){
                            echo 'Failed to copy <i>' . $filename . '</i> to image directory.<br />';
                        }
                    }
                }
            }
            else if($component == 'com_allvideoshare'){
                if($vid['type'] == 'upload'){
                    $videoss = explode('/', $vid['video']);
                    $videoss_name = array_pop($videoss);
                    if($videoss_name == basename($filename)){
                        if (!JFile::copy($filename, JPATH_SITE . '/media/com_mijovideos/videos/' . $id . '/orig/' . basename($filename))){
                            echo 'Failed to copy <i>' . $filename . '</i> to image directory.<br />';
                        }
                    }
                }
            }
            else if($component == 'com_hdflvplayer'){
                if($vid['filepath'] == 'File'){
                    if($vid['videourl'] == basename($filename)){
                        if (!JFile::copy($filename, JPATH_SITE . '/media/com_mijovideos/videos/' . $id . '/orig/' . basename($filename))){
                            echo 'Failed to copy <i>' . $filename . '</i> to image directory.<br />';
                        }
                    }
                }
            }
            else {
                if (!JFile::copy($filename, JPATH_SITE . '/media/com_mijovideos/videos/' . $id . '/orig/' . basename($filename))){
                    echo 'Failed to copy <i>' . $filename . '</i> to image directory.<br />';
                }
            }
        }
    }
}