<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosModelChannels extends MijovideosModel {

    public function __construct() {
        parent::__construct('channels');

        $this->acl = MijoVideos::get('acl');
        $this->user = JFactory::getUser();

        $task = JRequest::getCmd('task');
        $tasks = array('edit', 'apply', 'save', 'save2new');

        if (in_array($task, $tasks)) {
            $cid = JRequest::getVar('cid', array(0), '', 'array');
            $this->setId((int)$cid[0]);
        }
        else {
            $this->_getUserStates();
            $this->_buildViewQuery();
        }
    }

    public function _getUserStates(){
        $this->filter_order            	= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_order',            	'filter_order',         'c.title');
        $this->filter_order_Dir        	= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_order_Dir',        	'filter_order_Dir',     'ASC');
        $this->filter_status        	= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_published',         'filter_published',     '');
        $this->filter_access            = parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_access',         	'filter_access',        '');
        $this->filter_language        	= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_language',          'filter_language',     '');
        $this->search                	= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.search',                 	'search',               '');
        $this->search                  	= JString::strtolower($this->search);
    }

    public function getItems() {
        if (empty($this->_data)) {
            $rows = parent::getItems();

            foreach ($rows as $row) {
                $sql = "SELECT COUNT(*) FROM #__mijovideos_videos AS v WHERE v.channel_id = {$row->id}";
                $this->_db->setQuery($sql);

                $row->videos = $this->_db->loadResult();
            }

            $this->_data = $rows;
        }

        return $this->_data;
    }

    public function _buildViewQuery() {
        $where = $this->_buildViewWhere();

        $orderby = "";
        if (!empty($this->filter_order) and !empty($this->filter_order_Dir)) {
            $orderby = " ORDER BY {$this->filter_order} {$this->filter_order_Dir}";
        }

        $this->_query = "SELECT
                    c.*,
                    u.id user_id, u.username
                FROM #__mijovideos_channels c
                LEFT JOIN #__users u ON (c.user_id = u.id) ".$where.$orderby;;
        $this->_query;
    }

    public function _buildViewWhere() {
        $where = array();

        if ($this->search) {
			$src = parent::secureQuery($this->search, true);
            $where[] = "(LOWER(c.title) LIKE {$src})";
        }

        if (is_numeric($this->filter_status)) {
            $where[] = 'c.published = '.(int) $this->filter_status;
        }

        if (is_numeric($this->filter_access)) {
            $where[] = 'c.access = '.(int) $this->filter_access;
        }

        if ($this->filter_language) {
            $where[] = 'c.language IN (' . $this->_db->Quote($this->filter_language) . ',' . $this->_db->Quote('*') . ')';
        }

        if (!$this->acl->canAdmin()) {
            $where[] = 'c.user_id = '.$this->user->get('id');
        }

        $where = (count($where) ? ' WHERE '. implode(' AND ', $where) : '');

        return $where;
    }

    public function getTotal() {
        if (empty($this->_total)) {
            $this->_total = MijoDB::loadResult("SELECT COUNT(*) FROM #__mijovideos_{$this->_table} AS c".$this->_buildViewWhere());
        }

        return $this->_total;
    }

    public function getEditData($table = NULL) {
        if (empty($this->_data)) {
            if (!empty($this->_id)) { $this->_data = parent::getEditData(); }
            else {
                $row = parent::getEditData();
                $row->video_id = 0;
                $this->_data = $row;
            }
        }

        return $this->_data;
    }

    public function getFields() {
        $allData = MijoDB::loadObjectList("SELECT * FROM #__mijovideos_fields WHERE display_in = 1 ORDER By ordering ASC");

        # Sort Array Data
        asort($allData);
		
        return $allData;
    }

    public function getColumns() {
        if ($this->config->get('search_list_fields')) {
            $list = $this->config->get('search_list_fields')->list;
        }
        else {
            $list = NULL;
        }

        if ($list != NULL){
            foreach ($list as $key => $value){
                $allData[] = MijoDB::loadObject("SELECT ordering, name, title FROM #__mijovideos_fields WHERE name = '{$key}' ORDER By ordering ASC");
            }

            # Sort Array Data
            asort($allData);

            # table list
            foreach ($allData as $cName){ $columnTitle[]	=  $cName->title; }
            foreach ($allData as $cName){ $columnName[]   	=  $cName->name; }

            $this->columnData[0] = $columnTitle;
            $this->columnData[1] = $columnName;
            $this->columnData[2] = $this->getItems();
        }
        else {
            $this->columnData = NULL;
        }

        return $this->columnData;
    }

    public function delete($ids) {

        if(!MijoDB::query('DELETE FROM #__mijovideos_playlists WHERE channel_id IN ('.implode(',', $ids).')')) {
            return false;
        }

        if(!MijoDB::query('DELETE FROM #__mijovideos_subscriptions WHERE channel_id IN ('.implode(',', $ids).')')) {
            return false;
        }

        if (!MijoDB::query("DELETE FROM #__mijovideos_files WHERE channel_id IN (".implode(',', $ids).")")) {
            return false;
        }

        if (!MijoDB::query("DELETE FROM #__mijovideos_videos WHERE channel_id IN (".implode(',', $ids).")")) {
            return false;
        }

        return true;
    }

    public function delete_rel($video_ids) {

        if (!MijoDB::query("DELETE FROM #__mijovideos_processes WHERE video_id IN (".implode(',', $video_ids).")")) {
            return false;
        }

        if (!MijoDB::query("DELETE FROM #__mijovideos_video_categories WHERE video_id IN (".implode(',', $video_ids).")")) {
            return false;
        }

        if (!MijoDB::query("DELETE FROM #__mijovideos_playlist_videos WHERE video_id IN (".implode(',', $video_ids).")")) {
            return false;
        }

        return true;
    }

    public function unpublish($ids) {

        if(!MijoDB::query('UPDATE #__mijovideos_playlists SET published = 0 WHERE channel_id IN ('.implode(',', $ids).')')) {
            return false;
        }

        if (!MijoDB::query("UPDATE #__mijovideos_files SET published = 0 WHERE channel_id IN (".implode(',', $ids).")")) {
            return false;
        }

        if (!MijoDB::query("UPDATE #__mijovideos_videos SET published = 0 WHERE channel_id IN (".implode(',', $ids).")")) {
            return false;
        }

        return true;
    }

    public function unpublish_rel($video_ids) {

        if (!MijoDB::query("UPDATE #__mijovideos_processes SET published = 0 WHERE video_id IN (".implode(',', $video_ids).")")) {
            return false;
        }

        return true;
    }

    public function publish($ids) {

        if(!MijoDB::query('UPDATE #__mijovideos_playlists SET published = 1 WHERE channel_id IN ('.implode(',', $ids).')')) {
            return false;
        }

        if (!MijoDB::query("UPDATE #__mijovideos_files SET published = 1 WHERE channel_id IN (".implode(',', $ids).")")) {
            return false;
        }

        if (!MijoDB::query("UPDATE #__mijovideos_videos SET published = 1 WHERE channel_id IN (".implode(',', $ids).")")) {
            return false;
        }

        return true;
    }

    public function publish_rel($video_ids) {

        if (!MijoDB::query("UPDATE #__mijovideos_processes SET published = 1 WHERE video_id IN (".implode(',', $video_ids).")")) {
            return false;
        }

        return true;
    }
}