<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_ADMINISTRATOR.'/components/com_mijovideos/library/mijovideos.php');

function MijovideosBuildRoute(&$query) {
    return MijoVideos::get('router')->buildRoute($query);
}

function MijovideosParseRoute($segments) {
    return MijoVideos::get('router')->parseRoute($segments);
}