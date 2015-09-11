<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosModelVideos extends MijovideosModel {
	
	public $process;
	
    public function __construct() {
		parent::__construct('videos');

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
		$this->filter_order			= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_order',			'filter_order',			'title');
		$this->filter_order_Dir		= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_order_Dir',		'filter_order_Dir',		'ASC');
		$this->filter_category	    = parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_category', 	    'filter_category', 	    0);
		$this->filter_channel	    = parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_channel', 	    'filter_channel', 	    0);
		$this->filter_published	    = parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_published', 	'filter_published', 	'');
		$this->filter_access	    = parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_access', 	    'filter_access', 	    '');
		$this->filter_language	    = parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_language', 	    'filter_language', 	    '');
		$this->search				= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.search', 				'search', 				'');
		$this->search 	 			= JString::strtolower($this->search);
	}

	public function getItems() {
		if (empty($this->_data)) {
			$rows = parent::getItems();
			
			foreach ($rows as $row) {
				$sql = "SELECT c.title FROM #__mijovideos_categories AS c, #__mijovideos_video_categories AS ec WHERE c.id = ec.category_id AND ec.video_id = {$row->id}";
				$this->_db->setQuery($sql);
				
				$row->categories = implode(' | ', $this->_db->loadColumn());

                $sql = "SELECT c.title FROM #__mijovideos_channels AS c WHERE c.id = {$row->channel_id}";
                $this->_db->setQuery($sql);

                $row->channel_title = $this->_db->loadResult();
			}

            $pagination = parent::getPagination();
            $rows = array_slice($rows, 0, $pagination->limit);
			
			$this->_data = $rows;									
		}
		
		return $this->_data;
	}

    public function getTotal() {
		if (empty($this->_total)) {
			$this->_total = MijoDB::loadResult("SELECT COUNT(*) FROM #__{$this->_component}_{$this->_table} AS v".$this->_buildViewWhere());
		}

		return $this->_total;
	}
	
	public function getCategories() {
		return MijoDB::loadObjectList('SELECT id, parent, parent AS parent_id, title FROM #__mijovideos_categories');
	}

    public function getChannels() {
		return MijoDB::loadObjectList('SELECT id, title FROM #__mijovideos_channels');
	}

	public function getVideoCategories() {
		return MijoDB::loadResultArray('SELECT category_id FROM #__mijovideos_video_categories WHERE video_id='.$this->_id);
	}

	public function getFiles() {
		return MijoDB::loadObjectList('SELECT * FROM #__mijovideos_files WHERE video_id = '.$this->_id);
	}

    public function _buildViewQuery() {
        $where = self::_buildViewWhere();

        $orderby = "";
        if (!empty($this->filter_order) and !empty($this->filter_order_Dir)) {
            $orderby = " ORDER BY {$this->filter_order} {$this->filter_order_Dir}";
        }

        $this->_query = 'SELECT v.* FROM #__mijovideos_videos AS v '
            . $where
            .' GROUP BY v.id '
            . $orderby
        ;
    }

	public function _buildViewWhere() {
		$where = array();

        if ($this->search) {
            $src = parent::secureQuery($this->search, true);
            $where[] = "LOWER(v.title) LIKE {$src}";
        }

		if ($this->filter_category) {
			$where[] = 'v.id IN (SELECT video_id FROM #__mijovideos_video_categories WHERE category_id='.$this->filter_category.')';
		}

		if ($this->filter_channel) {
            $where[] = 'v.channel_id IN (SELECT channel_id FROM #__mijovideos_channels WHERE channel_id='.$this->filter_channel.')';
        }
				
		if (is_numeric($this->filter_published)) {
			$where[] = 'v.published = '.(int) $this->filter_published;
		}

        if (is_numeric($this->filter_access)) {
            $where[] = 'v.access = '.(int) $this->filter_access;
        }

        if ($this->filter_language) {
            $where[] = 'v.language IN (' . $this->_db->Quote($this->filter_language) . ',' . $this->_db->Quote('*') . ')';
        }

        if (!$this->acl->canAdmin()) {
            $where[] = 'v.user_id = '.$this->user->get('id');
        }
			
		$where = (count($where) ? ' WHERE '. implode(' AND ', $where) : '');
		
		return $where;
	}
	
	public function store(&$data) {
        $row = MijoVideos::getTable('MijovideosVideos');
		
        if (isset($data['channel_id'])){
            $data['user_id'] = MijoVideos::get('channels')->getUserId($data['channel_id']);
        }

        $data['fields'] = json_encode($data['custom_fields']);

        if ((!empty($data['tags']) and $data['tags'][0] != '')) {
            if (!is_array($data['tags'])) {
                $data['tags'] = array($data['tags']);
            }

            $row->newTags = $data['tags'];
        }

        if (!$row->bind($data)) {
            return false;
        }
		
		if (!$row->check($data)) {
            return false;
        }

        if (!$row->store()) {
            return false;
        }

        if (!empty($row->id)){
            MijoDB::execute("DELETE FROM `#__mijovideos_video_categories` WHERE video_id = {$row->id}");
			
			if (!empty($data['video_categories'])) {
				foreach($data['video_categories'] as $category_id){
					MijoDB::execute("INSERT INTO `#__mijovideos_video_categories` SET video_id = {$row->id}, category_id = {$category_id}");
				}
			}
        }

        $data['id'] = $row->id;

        return true;
	}
	
	public function getEditData($table = NULL) {
		if (empty($this->_data)) {
			$row = parent::getEditData();
                
            $this->_data = $row;
		}

		return $this->_data;
	}

	public function copy($id) {
		$rowOld = MijoVideos::getTable('MijovideosVideos');
		$rowOld->load($id);

		$row = MijoVideos::getTable('MijovideosVideos');
		$data = JArrayHelper::fromObject($rowOld);
		$row->bind($data);

		$row->id = 0;
		$row->title = $row->title.' - Copy';
		$row->store();
	
		# Need to enter categories for this video
		$sql = 'INSERT INTO #__mijovideos_video_categories(video_id, category_id) '
		.' SELECT '.$row->id.' , category_id FROM #__mijovideos_video_categories '
		.' WHERE video_id='.$id;
		
		$this->_db->setQuery($sql);
		$this->_db->query();
	
		return $row->id;
	}
	
	public function getProductID() {
		if ($this->_id) {
			$sql = "SELECT product_id FROM #__mijovideos_videos WHERE id= {$this->_id} ORDER BY id DESC LIMIT 1";
			$this->_db->setQuery($sql);
			$productID = $this->_db->loadResult();
		}
        else {
			$productID = 0;
		}
		return $productID ;
	}
	
	public function autoComplete($query){
        if (!empty($query)) {
            $sql = "SELECT id, name FROM #__mijovideos_fields WHERE LOWER(name) LIKE '%".strtolower($query)."%' ORDER BY name DESC";
            $this->_db->setQuery($sql);
            $videos = $this->_db->loadAssocList();
        }
        else {
            $videos = array();
        }

        return $videos;
    }
    
    public function getFields() {
    	return MijoDB::loadObjectList("SELECT * FROM #__mijovideos_fields WHERE display_in = 1 AND published = 1 ORDER BY ordering");
    }
    
    public function delete($ids) {
    	if(!MijoDB::query('DELETE FROM #__mijovideos_video_categories WHERE video_id IN ('.implode(',', $ids).')')) {
            return false;
        }

        if(!MijoDB::query('DELETE FROM #__mijovideos_videos WHERE id IN ('.implode(',', $ids).')')) {
            return false;
        }

        if(!MijoDB::query('DELETE FROM #__mijovideos_files WHERE id IN ('.implode(',', $ids).')')) {
            return false;
        }

        if(!MijoDB::query('DELETE FROM #__mijovideos_playlist_videos WHERE id IN ('.implode(',', $ids).')')) {
            return false;
        }

        if(!MijoDB::query('DELETE FROM #__mijovideos_processes WHERE id IN ('.implode(',', $ids).')')) {
            return false;
        }

        return true;

    }
}