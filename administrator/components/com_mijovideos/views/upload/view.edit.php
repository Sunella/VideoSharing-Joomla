<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosViewUpload extends MijovideosView {

    public function display($tpl = null) {
        JFactory::getApplication()->redirect('index.php?option=com_mijovideos&view=upload');
	}
}