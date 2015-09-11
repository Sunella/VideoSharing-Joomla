<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosModelPlaylist extends MijovideosModel {

    public function __construct() {
		parent::__construct('playlist', 'playlists');

        $this->playlist_id = JRequest::getInt('playlist_id');
        if (!is_null(JRequest::getInt('item_id', null))) {
            $this->playlist_id = JRequest::getInt('item_id', null);
        }
        $this->_getUserStates();
        $this->_buildViewQuery();
	}

    public function getItem() {
        $row = MijoVideos::getTable('MijovideosPlaylists');
        $row->load($this->playlist_id);

        return $row;
    }

    public function _getUserStates() {
        $this->filter_order			= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_order',			'filter_order',			'p.title',	'cmd');
        $this->filter_order_Dir		= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_order_Dir',		'filter_order_Dir',		'DESC',     'word');
        $this->search				= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.search', 				'search', 				'',         'string');
        $this->search 	 			= JString::strtolower($this->search);
    }

    public function _buildViewQuery() {
        $where = $this->_buildViewWhere();

        $orderby = "";
        if (!empty($this->filter_order) and !empty($this->filter_order_Dir)) {
            $orderby = " ORDER BY {$this->filter_order} {$this->filter_order_Dir}";
        }

        $this->_query = "SELECT
                    p.*
                FROM #__mijovideos_playlists p" .$where.$orderby;
    }

    public function getTotal() {
        if (empty($this->_total)) {
            $this->_total = MijoDB::loadResult("SELECT COUNT(*) FROM #__mijovideos_{$this->_table} AS p".$this->_buildViewWhere());
        }

        return $this->_total;
    }

    public function _buildViewWhere() {
        $where = array();

        # Playlist page... Update like or dislike field...
        $sel = JRequest::getVar('selection', 'selected', 'post');
        if ($sel == 'filtered' && !is_null(JRequest::getInt('change', null, 'post'))) {
            $user_id = JFactory::getUser()->get('id');
            $where = "WHERE user_id = {$user_id} AND item_id = {$this->playlist_id}";
        ###########
        } else {

            $where[] = 'p.id='.$this->playlist_id;

            $where[] = 'p.published=1';

            if (!empty($this->search)) {
                $src = parent::secureQuery($this->search, true);
                $where[] = "(LOWER(title) LIKE {$src} OR LOWER(description) LIKE {$src})";
            }
			
			$where[] = 'DATE(created) <= CURDATE()';

            $where = (count($where) ? ' WHERE '. implode(' AND ', $where) : '');
        }

        return $where;
    }

    public function getChannelItem() {

        $channel_id = MijoDB::loadResult("SELECT channel_id FROM #__mijovideos_playlists WHERE id = {$this->playlist_id}");

        $row = MijoVideos::getTable('MijovideosChannels');
        $row->load($channel_id);

        $row->subs = MijoVideos::get('model')->getSubscriberCount($channel_id);

        return $row;
    }
}