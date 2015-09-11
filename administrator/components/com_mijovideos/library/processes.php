<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted Access');

class MijovideosProcesses {

    public function __construct() {
		$this->config = MijoVideos::getConfig();
	}

    // Method to add a process
    public function add($item , $processType = null, $published) {
        $date = JFactory::getDate();

        $row = MijoVideos::getTable('MijovideosProcesses');

        $post                    = array();
        $post['id']              = '';
        $post['process_type']    = $processType;
        $post['video_id']        = $item->id;
	    if ($this->config->get('auto_process_videos')) {
		    $post['status']          = 3;
	    }
	    else {
		    $post['status']          = 0;
	    }
        $post['published']       = $published;
        $post['attempts']        = '0';
        $post['created_user_id'] = JFactory::getUser()->id;
        $post['created']         = $date->format('Y-m-d H:i:s');

        // Bind it to the table
        if (!$row->bind( $post )) {
            return JError::raiseWarning( 500, $row->getError() );
        }

        // Store it in the db
        if (!$row->store()) {
            return JError::raiseError(500, $row->getError() );
        }

        return $row->id;
    }

    // Method to select and run a queued process
    public function run($processes_id = null) {
        $db = JFactory::getDBO();

        $result = 0; // Queued as default

        // Get total queued tasks
        $this->_total = self::getQueue();

        // Get next task
        $task = $this->getTask($processes_id);
        $videos_lib = MijoVideos::get('videos');

        if ($task) {
            if (!empty($task->filetype)) {
                $method = "process$task->filetype";
                $result = $videos_lib->$method($task, $task->filetype, $task->size);
            } else {
                $item = MijoVideos::getTable('MijovideosVideos');
                $item->load($task->video_id);
                switch ($task->process_type) {
                    case 22 :
                        return true;//$result = $videos_lib->processInjectMetadata($task, $task->filetype, $item->source);
                        break;
                    case 23 :
                        $result = $videos_lib->checkMoovAtoms($task);
                        break;
                    case 24 :
                        $result = $videos_lib->getDuration($task);
                        break;
                    case 25 :
                        $result = $videos_lib->getTitle($task);
                        break;
                    case 100 :
                        $result = $videos_lib->convertToHtml5($task->video_id, $task->source);
                        break;

                }
            }

            if ($result) {
                $result = 1; // Successful
            } else {
                $result = 2; // Failed
            }

            // Ping the SQL database to check connection hasn't timed out.
            $db->connected();

            // Update proceses
            $model = MijoVideos::get('controller')->getModel('processes');
            JRequest::setVar('cid', $task->id, 'post');
            MijoVideos::get('controller')->updateField('processes', 'status', $result, $model);

            if ($result == 2 or $result == 0) {
                return false;
            }

        } else {
            return false;
        }

        return true;
    }

    // Method to get a queued task
    public function getTask($processes_id = null) {
        $db = JFactory::getDBO();

        // Attempt to increase MySQL timeout
        $query = 'SET SESSION wait_timeout = 28800';
        $db->setQuery($query);
        $db->query($query);

        // Setup query
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select('p.*, pt.filetype, pt.size, v.source');
        $query->from('#__mijovideos_processes AS p');
        $query->join('LEFT', '`#__mijovideos_videos` AS v ON v.id = p.video_id');
        $query->join('LEFT', '`#__mijovideos_process_type` AS pt ON pt.id = p.process_type');
        $query->where('p.attempts < 5');
        $query->where('p.published = 1');
        if (!empty($processes_id)) {
	        $query->where('(p.status = 3)'); // Processing
	        $query->where('p.id = '.$processes_id);
        }
	    else {
		    $query->where('(p.status = 0)'); // Queued
	    }

        // If we are running over CLI then don't allow multiple executions
        $args = @$GLOBALS['argv'];
        if ($args[1] == 'process') {
            $query->where('p.modified < DATE_SUB(SYSDATE(), INTERVAL 1 MINUTE)');
        }
        $db->setQuery($query);
        $task = $db->loadObject();

        if(strpos('com_mijovideos',$task->source)){
            return false;
        }
        if (isset($task->id)) {
            return $task;
        }
        return false;
    }


    public function getQueue() {
        // Create a new query object.
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select('COUNT(*)');
        $query->from('#__mijovideos_processes AS p');
        $query->where('p.status = 1 || p.status = 3');
        $query->where('p.attempts < 5');

        $db->setQuery($query);
        return $db->loadResult();
    }

    // Method to update a process (post-run)
    public function update($task, $result) {
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        $row = MijoVideos::getTable('MijovideosProcesses');

        $row->load($task->id);

        $data = array();
        $data['id'] = $task->id;
        $data['status'] = isset($result->status) ? $result->status : 3;
        $data['modified'] = $date->format('Y-m-d H:i:s');
        $data['modified_user_id'] = $user->id;
        $data['attempts'] = $row->attempts+1;

        if (!$row->bind( $data )) {
            return JError::raiseWarning( 500, $row->getError() );
        }
        if (!$row->store()) {
            JError::raiseError(500, $row->getError() );
        }
    }

    public function _getProcessTypeValue($item) {
        switch ($item) {
            case 1:
                return 75;
                break;
            case 2:
                return 100;
                break;
            case 3:
            case 7:
            case 12:
            case 17:
                return 240;
                break;
            case 4:
                return 500;
                break;
            case 5:
                return 640;
                break;
            case 6:
                return 1024;
                break;
            case 8:
            case 13:
            case 18:
                return 360;
                break;
            case 9:
            case 14:
            case 19:
                return 480;
                break;
            case 10:
            case 15:
            case 20:
                return 720;
                break;
            case 11:
            case 16:
            case 21:
                return 1080;
                break;
        }
    }

    public function getTypes() {
        static $cache;

        if (!isset($cache)) {
            $cache = MijoDB::loadAssocList('SELECT * FROM #__mijovideos_process_type', 'id');
        }

        return $cache;
    }

    public function getTypeTitle($id) {
        $title = '';

        $types = $this->getTypes();

        if (isset($types[$id])) {
            $title = $types[$id]['title'];
        }

        return $title;
    }

    public function getTypeSize($id) {
        $title = '';

        $types = $this->getTypes();

        if (isset($types[$id])) {
            $title = $types[$id]['size'];
        }

        return $title;
    }
}