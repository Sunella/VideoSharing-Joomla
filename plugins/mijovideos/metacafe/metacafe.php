<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die ('Restricted access');

require_once(JPATH_ADMINISTRATOR . '/components/com_mijovideos/library/remote.php');

class plgMijovideosMetacafe extends MijovideosRemote {

    public function __construct(&$subject, $config) {
        parent::__construct($subject, $config);
    }

    public function getPlayer(&$output, $pluginParams, $item) {
        $this->width  = $this->params->get('width');
        $this->height = $this->params->get('height');

        $id = str_replace('watch', 'embed', $item->source);
        ob_start();
        ?>
        <iframe src="<?php echo $id; ?>" width="<?php echo $this->width; ?>" height="<?php echo $this->height; ?>"
                allowFullScreen frameborder=0></iframe>
        <?php
        $output = ob_get_contents();
        ob_end_clean();
    }

    public function parse($url) {
        $url = str_replace("https", "http", $url);
        preg_match('/<link rel="video_src" href="([^"]+)/', $url, $match);
        if (!empty($match[1])) {
            return $match[1];
        }

        return false;
    }
}