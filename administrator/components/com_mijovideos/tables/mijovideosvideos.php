<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted Access');

class TableMijovideosVideos extends JTable {
	
    public $id									= 0;
    public $user_id								= null;
    public $channel_id							= 0;
    public $product_id							= 0;
    public $title								= null;
    public $alias								= '';
    public $introtext							= null;
    public $fulltext							= null;
    public $source								= null;
    public $likes								= 0;
    public $dislikes							= 0;
    public $hits								= 0;
    public $created								= null;
    public $modified							= null;
    public $featured							= 0;
    public $published							= 1;
    public $fields								= null;
    public $thumb								= null;
    public $meta_desc 							= '';
    public $meta_key							= '';
    public $meta_author							= '';
    public $language							= '*';

    public $_tableName = 'mijovideos_videos';
    public $tagsHelper = null;
    public $newTags = null;

	public function __construct(&$db) {
		parent::__construct('#__mijovideos_videos', 'id', $db);

		if (MijoVideos::is31()) {
			jimport('joomla.helper.tags');
			
			$this->tagsHelper = new JHelperTags();
			$this->tagsHelper->typeAlias = 'com_mijovideos.video';
		}
	}

 	public function check() {
        # Set title
        $this->title = htmlspecialchars_decode($this->title, ENT_QUOTES);

        # Set alias
        $this->alias = JApplication::stringURLSafe($this->alias);
        if (empty($this->alias)) {
            $this->alias = JApplication::stringURLSafe($this->title);
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

    public function store($updateNulls = false) {
		if (MijoVideos::is31()) {
			jimport('joomla.helper.tags');
			
			if (!is_object($this->tagsHelper)) {
				$this->tagsHelper = new JHelperTags();
				$this->tagsHelper->typeAlias = 'com_mijovideos.video';
			}

			$this->tagsHelper->preStoreProcess($this);

			parent::store($updateNulls);

			$result = $this->tagsHelper->postStoreProcess($this);

			return $result;
		}
		else {
            $result = parent::store($updateNulls);
            return $result;
		}
    }
}