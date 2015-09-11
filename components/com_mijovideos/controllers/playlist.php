<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosControllerPlaylist extends MijoVideosController {
	
	public function __construct($config = array()) {
		parent::__construct('playlist');
	}

    public function display($cachable = false, $urlparams = false) {
    $layout = JRequest::getCmd('layout');

    $function = 'display'.ucfirst($layout);

    $view = $this->getView(ucfirst($this->_context), 'html');
    $view->setModel($this->getModel('playlist'), true);
    $view->setModel($this->getModel('video'));

    if (!empty($layout)) {
        $view->setLayout($layout);
    }

    $view->$function();
}
}