<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosControllerPlaylists extends MijoVideosController {
	
	public function __construct($config = array()) {
		parent::__construct('playlists');
	}
}