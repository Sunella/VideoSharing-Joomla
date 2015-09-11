<?php
/*
* @package		MijoVideos
* @copyright	2009-2014 Mijosoft LLC, mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.plugin.plugin');

class plgUserMijovideos extends JPlugin {
	
	public function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
		
        $mijovideos = JPATH_ADMINISTRATOR.'/components/com_mijovideos/library/mijovideos.php';
        if (!file_exists($mijovideos)) {
            return;
        }

        require_once($mijovideos);
	}

	public function onUserAfterSave($user, $isnew, $succes, $msg){
        if (!$succes or empty($user)) {
            return false;
        }

        $db = JFactory::getDBO();

        if(!$this->_hasChannel($db, $user['id'])){
            return $this->_newUser($db, $user);
        }

        return $this->_oldUserUpdate($db, $user);
    }
	
	public function onUserAfterDelete($user, $succes, $msg){
		if (!$succes or empty($user)) {
			return false;
		}

        $db = JFactory::getDBO();

        $st = MijoVideos::getConfig()->get('ondelete_channel_status', 1);
        if($st == 0){
            $this->_delete($db, 'channels', $user['id']);

            $this->_delete($db, 'channel_subscriptions', $user['id']);

            $this->_deleteLikes($db, 'channels', $user['id']);
        }
        else if($st == 1){
            $this->_unpublish($db, 'channels', $user['id']);
        }


        $st = MijoVideos::getConfig()->get('ondelete_playlists_status', 1);
        if($st == 0){
            $this->_delete($db, 'playlists', $user['id']);

            $this->_deletePlaylistRels($db, $user['id']);

            $this->_deleteLikes($db, 'playlists', $user['id']);
        }
        else if($st == 1){
            $this->_unpublish($db, 'playlists', $user['id']);
        }

        $st = MijoVideos::getConfig()->get('ondelete_videos_status', 1);
        if($st == 0){
            $this->_delete($db, 'videos', $user['id']);

            $this->_deleteVideoRels($db, 'video_categories', $user['id']);
            $this->_deleteVideoRels($db, 'playlist_videos', $user['id']);

            $this->_deleteLikes($db, 'videos', $user['id']);
        }
        else if($st == 1){
            $this->_unpublish($db, 'videos', $user['id']);
        }

		return true;
	}

    private function _deleteLikes($db, $type, $user_id){
        $db->setQuery("SELECT id FROM `#__mijovideos_{$type}` WHERE `user_id` = {$user_id} ");
        $ids = $db->loadColumn();

        if(empty($ids)){
            return;
        }

        $ids = implode(',', $ids);
        $db->setQuery("DELETE FROM `#__mijovideos_likes` WHERE `user_id` = {$user_id} AND `item_type` = '{$type}' AND item_id IN ({$ids})");
        $db->execute();
    }

    private function _deleteVideoRels($db, $type, $user_id){
        $db->setQuery("SELECT id FROM `#__mijovideos_videos` WHERE `user_id` = {$user_id} ");
        $ids = $db->loadColumn();

        if(empty($ids)){
            return;
        }

        $ids = implode(',', $ids);
        $db->setQuery("DELETE FROM `#__mijovideos_{$type}` WHERE video_id IN ({$ids})");
        $db->execute();
    }

    private function _deletePlaylistRels($db, $user_id){
        $db->setQuery("SELECT id FROM `#__mijovideos_playlists` WHERE `user_id` = {$user_id} ");
        $ids = $db->loadColumn();

        if(empty($ids)){
            return;
        }

        $ids = implode(',', $ids);
        $db->setQuery("DELETE FROM `#__mijovideos_playlist_videos` WHERE playlist_id IN ({$ids})");
        $db->execute();
    }

    private function _delete($db, $type, $user_id){
        $db->setQuery("DELETE FROM `#__mijovideos_{$type}` WHERE `user_id` = {$user_id}");
        $db->execute();
    }

    private function _unpublish($db, $type, $user_id){
        $db->setQuery("UPDATE `#__mijovideos_{$type}` SET `published` = 0 WHERE user_id = {$user_id}");
        $db->execute();
    }

    private function _newUser($db, $user){
        $published = 0;
        if($user['block'] == 0){
            $published = 1;
        }

        # Add Channel
        $db->setQuery("INSERT INTO `#__mijovideos_channels` (`user_id`, `title`, `alias`, `introtext`, `fulltext`, `thumb`, `banner`, `fields`,`likes`, `dislikes`, `hits`, `params`, `ordering`, `access`, `language`, `created`, `modified`, `featured`, `published`, `default`, `share_others`, `meta_desc`, `meta_key`, `meta_author`) VALUES
        ({$user['id']}, '{$user['name']}', '{$user['name']}', '{$user['name']}', '', '', '', '', 0, 0, 0, '', '', 1, '*', NOW(), NOW(), 0, {$published}, 1, 0, '', '', '')");
        $db->execute();

        $channel_id = $db->insertid();
        if(empty($channel_id)){
            return false;
        }

        # Add Watch Later
        $db->setQuery("INSERT INTO `#__mijovideos_playlists` (`channel_id`, `user_id`, `type`, `title`, `alias`, `introtext`, `fulltext`, `thumb`, `fields`,`likes`, `dislikes`, `hits`, `subscriptions`, `params`, `ordering`, `access`, `language`, `created`, `modified`, `meta_desc`, `meta_key`, `meta_author`, `share_others`, `featured`, `published`) VALUES
        ({$channel_id}, {$user['id']}, 1, 'Watch Later', '', '', '', '', '', '', 0, 0, 0, '', '', 1, '*', NOW(), NOW(), '', '', '', 0, 0, {$published})");
        $db->execute();

        # Add Favorite Videos
        $db->setQuery("INSERT INTO `#__mijovideos_playlists` (`channel_id`, `user_id`,  `type`, `title`, `alias`, `introtext`, `fulltext`, `thumb`, `fields`,`likes`, `dislikes`, `hits`, `subscriptions`, `params`, `ordering`, `access`, `language`, `created`, `modified`, `meta_desc`, `meta_key`, `meta_author`, `share_others`, `featured`, `published`) VALUES
        ({$channel_id}, {$user['id']}, 2, 'Favorite Videos', '', '', '', '', '', '', 0, 0, 0, '', '', 1, '*', NOW(), NOW(), '', '', '', 0, 0, {$published})");
        $db->execute();

        return true;
    }

    private function _oldUserUpdate($db, $user){
        if($user['block'] == 0) {
            return true;
        }

        $st = MijoVideos::getConfig()->get('onupdate_channel_status', 1);
        if($st == 1){
            $this->_unpublish($db, 'channels', $user['id']);
        }

        $st = MijoVideos::getConfig()->get('onupdate_playlists_status', 1);
        if($st == 1){
            $this->_unpublish($db, 'playlists', $user['id']);
        }

        $st = MijoVideos::getConfig()->get('onupdate_videos_status', 1);
        if($st == 1){
            $this->_unpublish($db, 'videos', $user['id']);
        }

        return true;
    }

    private function _hasChannel($db, $user_id){
        $db->setQuery("SELECT id FROM `#__mijovideos_channels` WHERE `user_id` = {$user_id} ");
        $ids = $db->loadColumn();

        if(empty($ids)){
            return false;
        }

        return true;
    }

}

//todo:: add to config
/**********
ondelete_channel_status
ondelete_videos_status
ondelete_playlists_status

onupdate_channel_status
onupdate_playlists_status
onupdate_videos_status
*/