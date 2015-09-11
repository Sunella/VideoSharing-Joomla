<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

class TableMijovideosProcesses extends JTable {

    public $id 	 		    	= 0;
    public $process_type 		= 0;
    public $video_id 	 		= 0;
    public $status 	 			= 0;
    public $attempts		    = 0;
    public $checked_out		    = 0;
    public $checked_out_time	= NULL;
    public $params				= '';
    public $created_user_id		= 0;
    public $created				= NULL;
    public $modified_user_id	= 0;
    public $modified			= NULL;
    public $published			= 1;

	public function __construct($db) {
		parent::__construct('#__mijovideos_processes', 'id', $db);
	}
}
