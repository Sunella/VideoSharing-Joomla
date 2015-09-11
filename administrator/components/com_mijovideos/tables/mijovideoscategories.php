<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted Access');

class TableMijovideosCategories extends JTable {

    public $id 	 		    = 0;
    public $parent 			= 0;
    public $title 			= '';
    public $alias			= '';
    public $thumb			= null;
    public $introtext		= '';
    public $fulltext		= '';
    public $ordering		= 0;
    public $access		    = 1;
    public $language		= '*';
    public $created			= null;
    public $modified		= null;
    public $meta_desc 		= '';
    public $meta_key		= '';
    public $meta_author		= '';
    public $published		= 1;

	public function __construct(&$db) {
		parent::__construct('#__mijovideos_categories', 'id', $db);
	}

    public function check() {
        # Set title
        $this->title = htmlspecialchars_decode($this->title, ENT_QUOTES);

        # Set alias
        $this->alias = JApplication::stringURLSafe($this->alias);
        if (empty($this->alias)) {
            $this->alias = JApplication::stringURLSafe($this->title);
        }

        if (!$this->id) {
            $where = '`parent` = ' . (int) $this->parent;
            $this->ordering = $this->getNextOrder($where);
        }
        
    	# Description Exploding
		$delimiter = "<hr id=\"system-readmore\" />";
				
		if(strpos($this->introtext, $delimiter) == true){
			$exp = explode($delimiter, $this->introtext);
			$this->introtext	= $exp[0];
			$this->fulltext		= $exp[1];
		} else {
			$this->fulltext		= "";
		}
		
        return true;
    }
}