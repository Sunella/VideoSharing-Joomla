<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted Access');

# Imports
jimport('joomla.application.component.view');

if (!class_exists('MijosoftView')) {
	if (interface_exists('JView')) {
		abstract class MijosoftView extends JViewLegacy {}
	}
	else {
		class MijosoftView extends JView {}
	}
}

class MijovideosView extends MijosoftView {

	public $toolbar;
	public $document;

    public function __construct($config = array()) {
		parent::__construct($config);

        $this->_mainframe = JFactory::getApplication();
        if ($this->_mainframe->isAdmin()) {
            $this->_option = MijoVideos::get('utility')->findOption();
        }
        else {
            $this->_option = MijoVideos::getInput()->getCmd('option');
        }

        $this->_view = MijoVideos::getInput()->getCmd('view');

		# Get toolbar object
        if ($this->_mainframe->isAdmin()) {
		    $this->toolbar = JToolBar::getInstance();
        }

		$this->document = JFactory::getDocument();

        $tmpl = $this->_mainframe->getTemplate();

		# Template CSS
        if (file_exists(JPATH_ROOT . '/templates/'. $tmpl .'/html/com_mijovideos/assets/css/stylesheet.css') and !MijoVideos::isDashboard()) {
            $this->document->addStyleSheet(JUri::root() . 'templates/'. $tmpl .'/html/com_mijovideos/assets/css/stylesheet.css');
            if(MijoVideos::is30()) {
                JHtml::_('jquery.framework');
            }
            $this->document->addScript(JUri::root() . 'templates/'. $tmpl .'/html/com_mijovideos/assets/js/watchlater.js');
            $color = MijoVideos::getConfig()->get('override_color');
            $color1 = '#'.dechex(hexdec($color) - 1379346);
            $color2 = '#'.dechex(hexdec($color) + 654582);
            $color3 = '#'.dechex(hexdec($color) + 1967870);
            $css = '.subscribe {
                        background-color:'.$color.' !important;
                        background-image: -webkit-gradient(linear, 0 0, 0 100%, from('.$color1.'), to('.$color2.'));
                        background-image: -webkit-linear-gradient(to top, '.$color1.' 0%, '.$color2.' 100%);
                        background-image: -moz-linear-gradient(to top, '.$color1.' 0%, '.$color2.' 100%);
                        background-image: -o-linear-gradient(to top, '.$color1.' 0%, '.$color2.' 100%);
                        background-image: linear-gradient(to top, '.$color1.' 0%, '.$color2.' 100%);
                    }
                    .subscribe:hover{
                        background         : '.$color2.' !important; // Dailymotion
                        background-image: -webkit-gradient(linear, 0 0, 0 100%, from('.$color.'), to('.$color3.'));
                        background-image: -webkit-linear-gradient(to top, '.$color.' 0%, '.$color3.' 100%);
                        background-image: -moz-linear-gradient(to top, '.$color.' 0%, '.$color3.' 100%);
                        background-image: -o-linear-gradient(to top, '.$color.' 0%, '.$color3.' 100%);
                        background-image: linear-gradient(to top, '.$color.' 0%, '.$color3.' 100%);
                    }
                    .follow_button { // Dailymotion
                        border-color: #52882F;
                        background: #6CB23E;
                    }
                    .play_all_pp {
                        /*background : #6CB23E !important;*/
                    }
                    .mijovideos_flow_select_cp .toggled span {

                    }
                    .mijovideos_tabs li.active {
                        border-bottom:3px solid '.$color.';
                    }
                    .mijovideos_tabs li:hover{
                        border-bottom:3px solid '.$color.';
                    }
                    .mijovideos_box .nav-tabs li.active{
                        border-bottom:3px solid '.$color.';
                    }
                    .vjs-default-skin .vjs-volume-level {
                        background: '.$color.' !important;
                    }

                    .vjs-default-skin .vjs-play-progress {
                        background: '.$color.' !important;
                    }';
            $this->document->addStyleDeclaration($css);

        }
        # Component CSS
        else {
            if ($this->_mainframe->isAdmin()) {
                $this->document->addStyleSheet(JUri::root() . 'administrator/components/com_mijovideos/assets/css/mijovideos.css');
            } else {
                $this->document->addStyleSheet(JUri::root() . 'components/com_mijovideos/assets/css/mijovideos.css');
            }
        }

        if (MijoVideos::is30()) {
            if ($this->_mainframe->isAdmin()) {
                $this->document->addStyleSheet(JUri::root() . 'administrator/components/com_mijovideos/assets/css/joomla3.css');
                JHtml::_('formbehavior.chosen', 'select');
            } else {
                $this->document->addStyleSheet(JUri::root() . 'components/com_mijovideos/assets/css/joomla3.css');
            }
        }
        else {
            if ($this->_mainframe->isAdmin()) {
                $this->document->addStyleSheet(JUri::root() . 'administrator/components/com_mijovideos/assets/css/joomla2.css');
                $this->document->addStyleSheet(JUri::root() . 'administrator/components/com_mijovideos/assets/css/table.css');
            } else {
                $this->document->addStyleSheet(JUri::root() . 'components/com_mijovideos/assets/css/joomla2.css');
                JLoader::register('JHtmlString', JPATH_LIBRARIES.'/joomla/html/html/string.php');
            }
        }

		if (MijoVideos::isDashboard()) {
            $this->document->addScript(JUri::root() . 'components/com_mijovideos/assets/js/adminform.js');
        }

		if ($this->_mainframe->isSite()) {
            // Load first jQuery lib
            if(MijoVideos::is30()) {
                JHtml::_('jquery.framework');
            }
            $this->document->addScript(JUri::root() . 'components/com_mijovideos/assets/js/thumbnail.js');
			$this->document->setBase(JUri::root());
		}

        $this->acl = MijoVideos::get('acl');
		$this->config = MijoVideos::getConfig();
        $this->Itemid = MijoVideos::getInput()->getInt('Itemid', 0);
	}
	
	public function getIcon($i, $task, $img, $check_acl = false) {
        if ($check_acl and !$this->acl->canEditState()) {
            $html = '<img src="'.JUri::root(true).'/administrator/components/com_mijovideos/assets/images/'.$img.'" border="0" />';
        }
        else {
            $html = '<a href="javascript:void(0);" onclick="return listItemTask(\'cb'.$i.'\',\''.$task.'\')">';
            $html .= '<img src="'.JUri::root(true).'/administrator/components/com_mijovideos/assets/images/'.$img.'" border="0" />';
            $html .= '</a>';
        }

		return $html;
	}

    public function loadForeignTemplate($view, $layout = 'default', $function = 'display') {
        $type = 'html';

        $task = MijoVideos::getInput()->getCmd('task', '');
        $tasks = array('add', 'edit', 'apply', 'save2new');
        if (in_array($task, $tasks)/* and ($view != 'upload') and ($view != 'files')*/) {
            //$type = 'edit';
        }

        $location = JPATH_MIJOVIDEOS_ADMIN;
        if ($this->_mainframe->isSite()) {
            $location = JPATH_MIJOVIDEOS;
        }

        $path = $location.'/views/'.$view.'/view.'.$type.'.php';

        if (file_exists($path)) {
            require_once $path;
        }
        else {
            return null;
        }

        $view_name = 'MijovideosView'.ucfirst($view);
        $model_name = 'MijovideosModel'.ucfirst($view);

        $options['name'] = $view;
        $options['layout'] = $layout;
        $options['base_path'] = $location;

        $view = new $view_name($options);

        $model_file = $location.'/models/'.$view.'.php';
        if (file_exists($model_file)) {
            require_once($model_file);

            $model = new $model_name();

            $view->setModel($model, true);
        }

        if (MijoVideos::is30()) {
            JHtml::_('formbehavior.chosen', 'select');
        }

        $tpl = null;
        if ($layout != 'default') {
            $tpl = $layout;
        }

        ob_start();

        $view->$function($tpl);

        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    public function display($tpl = null) {
        $is_dashboard = MijoVideos::isDashboard();

        if ($is_dashboard) {
            $result = $this->loadDashboardTemplate($tpl);

            if ($result instanceof Exception) {
                return $result;
            }

            echo $result;
        }
        else {
            parent::display($tpl);
        }
    }

    public function loadDashboardTemplate($tpl = null) {
        $this->_output = null;

        $view = $this->_view;
        if ($view == 'dashboard') {
            $view = 'mijovideos';
        }

        $layout = $this->getLayout();

        // Create the template file name based on the layout
        $file = isset($tpl) ? $layout . '_' . $tpl : $layout;
        $file = preg_replace('/[^A-Z0-9_\.-]/i', '', $file);

        $template_path = JPATH_MIJOVIDEOS_ADMIN . '/views/' . $view . '/tmpl/' . $file . '.php';

        if (file_exists($template_path)) {
            ob_start();

            include($template_path);

            $this->_output = ob_get_contents();
            ob_end_clean();

            return $this->_output;
        }
        else {
            throw new Exception(JText::sprintf('JLIB_APPLICATION_ERROR_LAYOUTFILE_NOT_FOUND', $file), 500);
        }
    }
}