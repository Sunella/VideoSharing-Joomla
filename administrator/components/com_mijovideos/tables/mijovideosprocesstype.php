<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

class TableMijovideosProcesstype extends JTable {

    public $id 	 		    = 0;
    public $title 			= '';
    public $alias			= '';
    public $filetype		= null;
    public $size			= null;
    public $ordering		= 0;
    public $published		= 1;

	public function __construct(&$db) {
		parent::__construct('#__mijovideos_process_type', 'id', $db);
	}

    public function check() {
        # Set title
        $this->title = htmlspecialchars_decode($this->title, ENT_QUOTES);

        # Set alias
        $this->alias = JApplication::stringURLSafe($this->alias);
        if (empty($this->alias)) {
            $this->alias = JApplication::stringURLSafe($this->title);
        }
	}
}
