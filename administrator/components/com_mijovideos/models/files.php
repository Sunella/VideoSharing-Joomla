<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosModelFiles extends MijovideosModel {

    public function __construct() {
		parent::__construct('files');

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
		$this->filter_order			= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_order',			'filter_order',			'id');
		$this->filter_order_Dir		= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_order_Dir',		'filter_order_Dir',		'ASC');
		//$this->filter_process	    = parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_process', 	    'filter_process', 	    '');
		$this->filter_published	    = parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_published', 	'filter_published', 	'');
		$this->search				= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.search', 				'search', 				'');
		$this->search 	 			= JString::strtolower($this->search);
	}

    public function getTotal() {
        if (empty($this->_total)) {
            $this->_total = MijoDB::loadResult("SELECT COUNT(f.id) FROM #__mijovideos_files AS f, #__mijovideos_videos AS v ".$this->_buildViewWhere());
        }

        return $this->_total;
    }

    public function _buildViewQuery() {
        $where = self::_buildViewWhere();

        $orderby = "";
        if (!empty($this->filter_order) and !empty($this->filter_order_Dir)) {
            $orderby = " ORDER BY {$this->filter_order} {$this->filter_order_Dir}";
        }

        $this->_query = 'SELECT f.*, v.title AS video_title, v.user_id '.
                        'FROM #__mijovideos_files AS f '.
                        'LEFT JOIN #__mijovideos_videos AS v '.
                        'ON f.video_id = v.id '.
                        $where.' '.
                        $orderby;
    }

    public function _buildViewWhere() {
		$where = array();			
		
		if ($this->search) {
            $src = parent::secureQuery($this->search, true);
			$where[] = "LOWER(v.title) LIKE {$src}";
		}

		/*if (!empty($this->filter_process)) {
			$where[] = 'process_type = "'.$this->filter_process.'"';
		}*/

		if (is_numeric($this->filter_published)) {
			$where[] = 'f.published = '.(int) $this->filter_published;
		}
						
		$where = (count($where) ? ' WHERE '. implode(' AND ', $where) : '');
		
		return $where;
	}

    public function delete($ids) {
        if (empty($ids)) {
            return false;
        }

        if (!MijoVideos::get('files')->delete($ids)) {
            return false;
        }

        if (!MijoDB::query("DELETE FROM #__mijovideos_files WHERE id IN (".implode(',', $ids).")")) {
            return false;
        }

        return true;
    }
}