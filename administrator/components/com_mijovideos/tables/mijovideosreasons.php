<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted Access');

class TableMijovideosReasons extends JTable {
    
    public $id 					= 0;
    public $parent 				= NULL;
    public $title			 	= '';
    public $alias			 	= '';
    public $description 		= '';
    public $access 				= 0;
    public $language			= '*';
    public $association 		= 0;
    public $published 			= 1;
    public $created				= null;
    public $modified			= null;

	public function __construct(&$db) {
		parent::__construct('#__mijovideos_report_reasons', 'id', $db);
	}
}