<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted Access');

class TableMijovideosFiles extends JTable {
    
    public $id 					= 0;
    public $video_id 			= 0;
    public $process_type		= 0;
    public $ext 				= '';
    public $file_size 			= null;
    public $source 				= null;
    public $channel_id			= null;
    public $user_id				= null;
    public $created				= null;
    public $modified			= null;
    public $published 			= 1;

	public function __construct(&$db) {
		parent::__construct('#__mijovideos_files', 'id', $db);
	}
}