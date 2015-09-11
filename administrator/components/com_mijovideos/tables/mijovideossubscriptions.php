<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted Access');

class TableMijovideosSubscriptions extends JTable {

    public $id 	 		    	= 0;
    public $item_id 		 	= null;
    public $item_type 		 	= 'channels';
    public $user_id 		 	= null;
    public $channel_id 	 		= null;
    public $created 		 	= null;

	public function __construct($db) {
		parent::__construct('#__mijovideos_subscriptions', 'id', $db);
	}
	
	public function check() {
        return true;
    }
}