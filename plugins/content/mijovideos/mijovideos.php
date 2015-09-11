<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgContentMijovideos extends JPlugin {

	public function __construct(&$subject, $params) {
		parent::__construct($subject, $params);
	}

	public function onContentPrepare($context, &$article, &$params, $limitstart) {
		if (JFactory::getApplication()->isAdmin()) {
			return true;
		}

        if (strpos($article->text, '{mijovideos id=') === false) {
            return true;
        }
		
		$regex = "#{mijovideos id=(\d+)}#s";
		
		$article->text = preg_replace_callback($regex, array(&$this, '_processMatches'), $article->text);
		
		return true;
	}

	public function _processMatches(&$matches) {
        require_once(JPATH_ADMINISTRATOR.'/components/com_mijovideos/library/mijovideos.php');

        $old_option = JRequest::getCmd('option');
        $old_view = JRequest::getCmd('view');

		JRequest::setVar('option', 'com_mijovideos');
		JRequest::setVar('view', 'video');
		JRequest::setVar('video_id', $matches[1]);

		ob_start();

        require_once(JPATH_MIJOVIDEOS.'/controllers/video.php');
        require_once(JPATH_MIJOVIDEOS.'/models/video.php');
        require_once(JPATH_MIJOVIDEOS.'/views/video/view.html.php');

		$controller = new MijovideosControllerVideo();
        $controller->_model = new MijovideosModelVideo();

        $options['name'] = 'video';
        $options['layout'] = 'default';
        $options['base_path'] = JPATH_MIJOVIDEOS;
        $view = new MijovideosViewVideo($options);

        $view->setModel($controller->_model, true);

        //$view->setLayout('common');
        $view->display();

		$output = ob_get_contents();
		ob_end_clean();

        JRequest::setVar('option', $old_option);
        JRequest::setVar('view', $old_view);
		
		return $output;
	}
}