<?php
/**
 * @package        MijoVideos
 * @copyright    2009-2014 Mijosoft LLC, mijosoft.com
 * @license        GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die;


class MijovideosViewUpload extends MijovideosView {

    function display($tpl = null) {
        if ($this->_mainframe->isAdmin()) {
            $this->addToolbar();
        }

        $this->config = MijoVideos::getConfig();

        if ($this->config->get('upload_script') == 'fancy') {
            $this->document->addScript(JUri::root() . "components/com_mijovideos/assets/js/Swiff.Uploader.js");
            $this->document->addScript(JUri::root() . "components/com_mijovideos/assets/js/Fx.ProgressBar.js");
            $this->document->addScript(JUri::root() . "components/com_mijovideos/assets/js/FancyUpload2.js");
            $this->document->addStyleSheet(JUri::root() . "components/com_mijovideos/assets/css/fancyupload.css");
        }

        if ($this->config->get('upload_script') == 'dropzone') {
            $this->document->addScript(JUri::root() . "components/com_mijovideos/assets/js/dropzone.js");
            $this->document->addStyleSheet(JUri::root() . "components/com_mijovideos/assets/css/dropzone.css");
        }

        parent::display($tpl);
    }

    protected function addToolbar() {
        if ($this->_view == 'videos') {
            return;
        }

        JToolBarHelper::title(JText::_('COM_MIJOVIDEOS_UPLOAD_NEW_VIDEO'), 'mijovideos');

        $this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://mijosoft.com/support/docs/mijovideos/user-manual/fields?tmpl=component', 650, 500);
    }
}