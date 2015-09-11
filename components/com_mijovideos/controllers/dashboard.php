<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosControllerDashboard extends MijovideosController {
	
	public function __construct($config = array()) {
        $_lang = JFactory::getLanguage();
        $_lang->load('com_mijovideos', JPATH_ADMINISTRATOR, 'en-GB', true);
        $_lang->load('com_mijovideos', JPATH_ADMINISTRATOR, $_lang->getDefault(), true);
        $_lang->load('com_mijovideos', JPATH_ADMINISTRATOR, null, true);

        $view = MijoVideos::getInput()->getCmd('view');

        if (file_exists((JPATH_MIJOVIDEOS_ADMIN.'/controllers/'.$view.'.php'))) {
            require_once(JPATH_MIJOVIDEOS_ADMIN.'/controllers/'.$view.'.php');

            $class_name = 'MijovideosController'.ucfirst($view);

            $this->_admin_controller = new $class_name();

            $model_file = JPATH_MIJOVIDEOS_ADMIN.'/models/'.$view.'.php';
            if (file_exists($model_file)) {
                require_once($model_file);

                $model_class_name = 'MijovideosModel'.ucfirst($view);

                $this->_admin_controller->_model = new $model_class_name();
            }
        }

        if ($view == 'dashboard') {
            $view = 'mijovideos';
        }

		parent::__construct($view);

        if (JFactory::getUser()->get('id') == 0) {
            $this->_mainframe->redirect(MijoVideos::get('utility')->redirectWithReturn());
        }
    }

    public function display($cachable = false, $urlparams = false) {
        $view = $this->getDashboardView();
        if (!is_object($view)) {
            return;
        }

        /*$layout = MijoVideos::getInput()->getCmd('layout', '');

        if (!empty($layout)) {
            $view->setLayout($layout);
        }*/

        $view->display();
    }

    public function edit() {
        JRequest::setVar('hidemainmenu', 1);

        $view = $this->getDashboardView();
        if (!is_object($view)) {
            return;
        }

        $view->display('edit');
    }

    public function support() {
        $view = $this->getDashboardView();
        if (!is_object($view)) {
            return;
        }

        $view->setLayout('support');

        $view->display();
    }

    public function translators() {
        $view = $this->getDashboardView();
        if (!is_object($view)) {
            return;
        }

        $view->setLayout('translators');

        $view->display();
    }

    public function defaultChannel() {
        JRequest::checkToken() or jexit('Invalid Token');

        MijoVideos::get('channels')->updateDefaultChannel(1);

        parent::route();
    }

    public function notDefaultChannel() {
        JRequest::checkToken() or jexit('Invalid Token');

        MijoVideos::get('channels')->updateDefaultChannel(0);

        parent::route();
    }

    public function upload() {
        $this->frontUpload('upload');
    }

    public function uberUpload() {
        $this->frontUpload('uberUpload');
    }

    public function remoteLink() {
        $this->frontUpload('remoteLink');
    }

    public function frontUpload($function = 'upload') {
        $upload = $this->_admin_controller->$function();

        if ($function == 'upload') {
            return;
        }

        $dashboard = '';
        if (MijoVideos::isDashboard()) {
            $dashboard = '&dashboard=1';
        }

        if ($upload->_count > 1) {
            $this->setRedirect('index.php?option=com_mijovideos&view=videos'.$dashboard);
        }
        else {
            $this->setRedirect('index.php?option=com_mijovideos&view=videos&task=edit&cid[]=' . $upload->_id.$dashboard);
        }
    }

    public function convertToHtml5() {
        $this->_admin_controller->convertToHtml5();
    }

    public function link_upload() {
        $this->_admin_controller->link_upload();
    }

    public function set_progress() {
        $this->_admin_controller->set_progress();
    }

    public function get_progress() {
        $this->_admin_controller->get_progress();
    }

    public function save() {
        $msg = $this->_admin_controller->save();

        parent::routeDashboard($msg);
    }

    public function copy() {
        $msg = $this->_admin_controller->copy();

        parent::routeDashboard($msg);
    }

    public function delete() {
        $msg = $this->_admin_controller->delete();

        parent::routeDashboard($msg);
    }
}