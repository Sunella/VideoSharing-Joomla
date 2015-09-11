<?php
/**
 * @package        MijoVideos
 * @copyright      2009-2014 Mijosoft LLC, mijosoft.com
 * @license        GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');
jimport('joomla.filter.filterinput');
JLoader::register('JHtmlString', JPATH_LIBRARIES . '/joomla/html/html/string.php');

class MijovideosRemote extends JPlugin {

    public $url;
    public $buffer;

    public function __construct(&$subject, $config) {
        parent::__construct($subject, $config);
    }

    public function getSource() {
        // We will apply the most strict filter to the variable
        $noHtmlFilter = JFilterInput::getInstance();

        $source = $this->url;
        $source = (string)str_replace(array("\r", "\r\n", "\n"), '', $source);
        $source = $noHtmlFilter->clean($source);
        $source = trim($source);

        return $source;
    }

    public function getDuration() {
        $duration = false;
        preg_match('/<meta property="video:duration" content="([^"]+)/', $this->buffer, $match);
        if (!empty($match[1])) {
            $duration = (int)$match[1];
        }

        if ($duration > 0) {
            return $duration;
        }
    }

    public function getBuffer($url) {
        $url = str_replace("https", "http", $url);
        if ($url) {
            if (function_exists('curl_init')) {
                $useragent = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1)";

                $curl_handle = curl_init();
                curl_setopt($curl_handle, CURLOPT_URL, $url);
                curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 30);
                curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl_handle, CURLOPT_USERAGENT, $useragent);
                $this->buffer = curl_exec($curl_handle);
                curl_close($curl_handle);

                if (!empty($this->buffer)) {
                    return $this->buffer;
                } else {
                    return false;
                }
            }
        }

        return false;
    }

    public function getThumbnail() {
        $thumbnail = $this->getContent('image');
        $thumbnail = trim(strip_tags($thumbnail));
        $isValid = preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $thumbnail);

        if ($isValid) {
            return $thumbnail;
        } else {
            return false;
        }
    }

    public function getContent($type = 'title') {
        $noHtmlFilter = JFilterInput::getInstance();

        // Open Graph Tag
        preg_match('/<meta property="og:'.$type.'" content="([^"]+)/', $this->buffer, $match);

        if (empty($match[1])) {
            // Standard Tag
            if ($type == 'title') {
                $pattern = "/<title>(.*)<\/title>/siU";
            } else {
                $pattern = '/<meta name="description" content="([^"]+)/';
            }
            preg_match($pattern, $this->buffer, $match);
        }

        if (!empty($match[1])) {
            $content = $match[1];
            $content = (string)str_replace(array("\r", "\r\n", "\n"), '', $content);
            $content = $noHtmlFilter->clean($content);
            $content = trim($content);
            if ($content) {
                return $content;
            }
        }

        return JText::_('COM_MIJOVIDEOS_UNNAMED');
    }

    public function getCleanUrl($url) {
        $pattern = '`.*?((http|https|ftp)://[\w#$&+,\/:;=?@.-]+)[^\w#$&+,\/:;=?@.-]*?`i';
        if (preg_match($pattern, $url, $matches)) {
            $url = $matches[1];
        }

        return $url;
    }
}