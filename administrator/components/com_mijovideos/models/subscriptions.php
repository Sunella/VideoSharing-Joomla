<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosModelSubscriptions extends MijovideosModel {

    public function __construct() {
        parent::__construct('subscriptions');

        $this->acl = MijoVideos::get('acl');
        $this->user = JFactory::getUser();

        $task = JRequest::getCmd('task');
        $tasks = array('apply', 'save', 'save2new');

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
        $this->filter_order            	= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_order',         	'filter_order',         's.user_id');
        $this->filter_order_Dir        	= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_order_Dir',        	'filter_order_Dir',     'ASC');
        $this->filter_user        		= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_user',         		'filter_user',     		'');
        $this->search                	= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.search',                 	'search',               '');
        $this->search                  	= JString::strtolower($this->search);
    }
	
    public function _buildViewQuery() {
        $where = $this->_buildViewWhere();

        $orderby = "";
        if (!empty($this->filter_order) and !empty($this->filter_order_Dir)) {
            $orderby = " ORDER BY {$this->filter_order} {$this->filter_order_Dir}";
        }

        $this->_query = "SELECT s.id, s.channel_id, c.title, u.id AS user_id, u.username ".
                        "FROM #__mijovideos_subscriptions s ".
                        "LEFT JOIN #__users AS u ON (s.user_id = u.id) ".
                        "LEFT JOIN #__mijovideos_channels AS c ON (c.id = s.channel_id) ".
                        $where.
                        $orderby;
        $this->_query;
    }

    public function _buildViewWhere() {
        $where = array();
        
        if ($this->search) {
        	$search = parent::secureQuery(trim($this->search), true);
        	
            if ($search != NULL){
            	$where[] = "(c.title LIKE {$search})";
            } else {
                $where[] = null;
            }
        }

        if (!$this->acl->canAdmin()) {
            $where[] = 's.user_id = '.$this->user->get('id');
        }
        else if ($this->filter_user) {
            $where[] = 's.user_id = '.$this->filter_user;
        }
        
        $where = (count($where) ? ' WHERE '. implode(' AND ', $where) : '');

        return $where;
    }
    
	public function getTotal() {
		if (empty($this->_total)) {	
			$this->_total = MijoDB::loadResult("SELECT COUNT(*) FROM #__mijovideos_subscriptions s ".$this->_buildViewWhere());	
		}
		
		return $this->_total;
	}
}