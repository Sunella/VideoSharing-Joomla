<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted Access');

class TableMijovideosReports extends JTable {

    public $id 	 		    	= 0;
    public $channel_id 	 		= 0;
    public $user_id 	 		= 0;
    public $item_id 	 		= 0;
    public $item_type 	 		= '';
    public $reason_id	 		= 0;
    public $note	 			= '';
    public $created				= null;
    public $modified			= null;

	public function __construct($db) {
		parent::__construct('#__mijovideos_reports', 'id', $db);
	}
}