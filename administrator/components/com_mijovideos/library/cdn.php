<?php
/**
 * @package        MijoVideos
 * @copyright      2009-2014 Mijosoft LLC, mijosoft.com
 * @license        GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class MijovideosCdn extends JPlugin {

    public $url;
    public $buffer;

    public function __construct(&$subject, $config) {
        parent::__construct($subject, $config);
    }

    public function maintenance() {
    }

    public function getCdnLocation() {
    }

    public function createCdnLocation() {
    }

    public function getCdnContents() {
    }

    public function putFile() {
    }

    public function publicUrl($video = null, $fileType = null) {
    }

    public function getLocalQueue() {
        $db = JFactory::getDBO();

        // Select locally videos from the table.
        $query = 'SELECT v.* FROM #__mijovideos_videos AS v WHERE v.source NOT LIKE \'http%\' AND v.published = 1 ORDER BY v.created ASC';

        $db->setQuery($query);

        return $db->loadObjectList();
    }
}