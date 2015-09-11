<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosViewHome extends MijovideosView {

    public function display($tpl = null) {
        $this->params = $this->_mainframe->getParams();

        parent::display($tpl);
    }
}