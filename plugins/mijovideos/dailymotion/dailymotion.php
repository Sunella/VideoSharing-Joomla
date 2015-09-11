<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die ('Restricted access');

require_once(JPATH_ADMINISTRATOR . '/components/com_mijovideos/library/remote.php');

class plgMijovideosDailymotion extends MijovideosRemote {

    public function __construct(&$subject, $config) {
        parent::__construct($subject, $config);
    }

    public function getPlayer(&$output, $pluginParams, $item) {
        $config = MijoVideos::getConfig();
        $autoplay = $this->params->get('autoplay');
        if ($autoplay == "global") {
            $autoplay = $config->get('autoplay');
        }

        $this->width  = $this->params->get('width');
        $this->height = $this->params->get('height');

        $id = $this->parse($item->source, '');

        ob_start();
        ?>
        <iframe frameborder="0" width="<?php echo $this->width; ?>" height="<?php echo $this->height; ?>"
                src="http://www.dailymotion.com/embed/video/<?php echo $id; ?>?logo=0&amp;autoPlay=<?php echo $autoplay; ?>"></iframe>
        <?php
        $output = ob_get_contents();
        ob_end_clean();
    }

    public function getThumbnail() {
        $code = $this->parse($this->url);
        $thumbnail = 'http://www.dailymotion.com/thumbnail/video/' . $code;

        return $thumbnail;

    }

    protected function parse($url) {
        preg_match('#http://www.dailymotion.com/video/([A-Za-z0-9]+)#s', $url, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }

        return false;
    }
}