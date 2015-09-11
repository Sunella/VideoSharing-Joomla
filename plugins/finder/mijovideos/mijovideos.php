<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.helper');

require_once JPATH_ADMINISTRATOR . '/components/com_finder/helpers/indexer/adapter.php';

class plgFinderMijovideos extends FinderIndexerAdapter {

	protected $context = 'Mijovideos';
	protected $extension = 'com_mijovideos';
	protected $layout = 'video';
	protected $type_title = 'Videos';
	protected $table = '#__mijovideos_videos';

	function __construct(&$subject, $params) {
		parent::__construct($subject, $params);
	}

	public function onFinderAfterDelete($context, $table) {
        if ($context == 'com_mijovideos.video') {
            $id = $table;
        }
        elseif ($context == 'com_finder.index') {
            $id = $table->link_id;
        }
        else {
            return true;
        }
        
        return $this->remove($id);
	}

	public function onFinderAfterSave($context, $row, $isNew) {
        if ($context == 'com_mijovideos.video') {
            $this->reindex($row['id']);
        }

        return true;
	}

	public function onFinderChangeState($context, $pks, $value) {
        if ($context == 'com_mijovideos.category') {
            $this->categoryStateChange($pks, $value);
        }
	}

	protected function index(FinderIndexerResult $item, $format = 'html') {
        if (JComponentHelper::isEnabled($this->extension) == false) {
            return;
        }

        $registry = new JRegistry;
        $registry->loadString($item->metadata);
        $item->metadata = $registry;

        $item->addInstruction(FinderIndexer::META_CONTEXT, 'link');
        $item->addInstruction(FinderIndexer::META_CONTEXT, 'metakey');
        $item->addInstruction(FinderIndexer::META_CONTEXT, 'metadesc');

        $item->url = 'index.php?option=com_mijovideos&view=video&video_id='.$item->id;
		$item->route = 'index.php?option=com_mijovideos&view=video&video_id='.$item->id;
        //$item->route = JRoute::_('index.php?option=com_mijovideos&view=video&video_id='.$item->id);
        $item->access = 1;

        $item->state = $this->translateState(intval($item->state));

        $item->addTaxonomy('Type', 'MijoVideos Video');

        $channel =$item->getElement('channel_name');
        if (!empty($manufacturer)) {
            $item->addTaxonomy('MijoVideos Channel', $channel);
        }

        $cats = $this->getVideoCategoryId($item->id);
        foreach($cats as $cat) {
            if (!empty($cat->name)){
                $item->addTaxonomy('MijoVideos Category', $cat->name);
            }
        }

        FinderIndexerHelper::getContentExtras($item);

        if (method_exists('FinderIndexer', 'getInstance')) {
            FinderIndexer::getInstance()->index($item);
        }
        else {
            FinderIndexer::index($item);
        }
	}

	protected function setup() {
		return true;
	}

	protected function getListQuery($sql = null) {
        $db = JFactory::getDbo();

        $sql = is_a($sql, 'JDatabaseQuery') ? $sql : $db->getQuery(true);
        $sql->select('v.id as id, v.published AS state, v.created as start_date, v.id AS slug');
        $sql->select('v.title as title, v.introtext, v.meta_key AS metakey, v.meta_desc AS metadesc');
        $sql->select('c.title As channel_name');

        $sql->from('#__mijovideos_videos AS v');
        $sql->join('LEFT', '#__mijovideos_channels AS c ON c.id = v.channel_id');

        return $sql;
	}

    protected function getItem($id) {
        JLog::add('FinderIndexerAdapter::getItem', JLog::INFO);

        $sql = $this->getListQuery();
        $sql->where('v.' . $this->db->quoteName('id') . ' = ' . (int) $id);

        $this->db->setQuery($sql);
        $row = $this->db->loadAssoc();

        if ($this->db->getErrorNum()) {
            throw new Exception($this->db->getErrorMsg(), 500);
        }

        $item = JArrayHelper::toObject($row, 'FinderIndexerResult');

        $item->type_id = $this->type_id;

        $item->layout = $this->layout;

        return $item;
    }

    protected function getVideoCategoryId($id){
        $db = JFactory::getDbo();

        $sql = 'SELECT c.title as name FROM #__mijovideos_categories AS c , #__mijovideos_video_categories ec
                WHERE ec.category_id = c.id AND ec.video_id = '.$id;
        $db->setQuery($sql);
        $result = $db->loadObjectList();

        return $result;
    }

    protected function categoryStateChange($pk, $value) {
        $sql = $this->getStateQuery($pk);
        $this->db->setQuery($sql);
        $items = $this->db->loadObjectList();

        foreach ($items as $item) {
            if ($value !== null) {
                $temp = intval($value);
            }
            else {
                $temp = intvall($item->state);
            }

            $this->change($item->id, 'state', $temp);

            $this->reindex($item->id);
        }
    }

    protected function getStateQuery($id) {
        $sql = "SELECT v.id AS id, v.published AS state, c.published AS cat_state
                FROM #__mijovideos_categories AS c
                LEFT JOIN #__mijovideos_videos AS v ON v.category_id = c.id
                WHERE c.category_id ={$id}";
				
        return $sql;
    }
}