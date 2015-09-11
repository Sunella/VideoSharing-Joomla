<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosModelProcesses extends MijovideosModel {

    public function __construct() {
        parent::__construct('processes');

        $task = JRequest::getCmd('task');
        $tasks = array('delete');

        if (in_array($task, $tasks)) {
            $cid = JRequest::getVar('cid', array(0), '', 'array');
            $this->setId((int)$cid[0]);
        } else {
            $this->_getUserStates();
            $this->_buildViewQuery();
        }
    }

    public function _getUserStates(){
        $this->filter_order            	= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_order',            	'filter_order',         'v.title');
        $this->filter_order_Dir        	= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_order_Dir',        	'filter_order_Dir',     'ASC');
        $this->filter_process_type      = parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_process_type', 	    'filter_process_type', 	0);
        $this->filter_status        	= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_status',            'filter_status',        '');
        $this->filter_published        	= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_published',         'filter_published',     '');
        $this->search                	= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.search',                 	'search',               '');
        $this->search                  	= JString::strtolower($this->search);
    }

    public function _buildViewQuery() {
        $where = $this->_buildViewWhere();

        $orderby = "";
        if (!empty($this->filter_order) and !empty($this->filter_order_Dir)) {
            $orderby = " ORDER BY {$this->filter_order} {$this->filter_order_Dir}";
        }

        $this->_query = "SELECT p.*, pt.title ".
                        "FROM #__mijovideos_processes p ".
                        "LEFT JOIN #__mijovideos_videos v ON (p.video_id = v.id) ".
        		        "LEFT JOIN #__mijovideos_process_type pt ON (p.process_type = pt.id) ".
        		        $where.$orderby;
    }

    public function _buildViewWhere() {
        $where = array();

        if ($this->search) {
            $src = parent::secureQuery($this->search, true);
            $where[] = "(LOWER(pt.title) LIKE {$src})";
        }

        if (is_numeric($this->filter_status)) {
            $where[] = 'p.status = '.(int) $this->filter_status;
        }

        if (is_numeric($this->filter_published)) {
            $where[] = 'p.published = '.(int) $this->filter_published;
        } else  {
        	$where[] = 'p.published = 1';
        }
        

        $where = (count($where) ? ' WHERE '. implode(' AND ', $where) : '');

        return $where;
    }

    public function getTotal() {
        if (empty($this->_total)) {
            $this->_total = MijoDB::loadResult("SELECT COUNT(*) FROM #__mijovideos_{$this->_table} AS p LEFT JOIN #__mijovideos_process_type AS pt ON pt.id = p.process_type".$this->_buildViewWhere());
        }

        return $this->_total;
    }

    public function getVideos() {
        return MijoDB::loadObjectList('SELECT id, title FROM #__mijovideos_videos WHERE published = 1 ORDER BY title');
    }

    public function getSuccessful($id = null) {
	    $query = 'SELECT COUNT(*) FROM #__mijovideos_processes WHERE status = 1';

	    if ($id) {
		    $query .= ' AND id = ' . $id;
	    }

	    return MijoDB::loadResult($query);
    }

    public function getProcessing($video_id = null) {
        return MijoDB::loadResult('SELECT COUNT(*) FROM #__mijovideos_processes WHERE status = 3 AND published = 1 AND video_id = ' . $video_id);
    }
}