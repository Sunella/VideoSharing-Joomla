<?php
/*
* @package		MijoVideos
* @copyright	2009-2014 Mijosoft LLC, mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
# No Permission
defined('_JEXEC') or die ('Restricted access');

# Controller Class
class MijovideosControllerSupport extends MijovideosController {

	# Main constructer
    public function __construct() {
        parent::__construct('support');
    }
	
	# Support page
    public function support() {
        $view = $this->getView(ucfirst($this->_context), 'html');
        $view->setLayout('support');
        $view->display();
    }
    
	# Translators page
    public function translators() {
        $view = $this->getView(ucfirst($this->_context), 'html');
        $view->setLayout('translators');
        $view->display();
    }
}