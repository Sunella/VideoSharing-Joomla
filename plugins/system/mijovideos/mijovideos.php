<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted access');

class plgSystemMijovideos extends JPlugin {

    public function onAfterRoute() {
        $db = JFactory::getDBO();
        $app = JFactory::getApplication();
		$document = JFactory::getDocument();

        $lib_folder = JUri::root() . 'administrator/components/com_mijovideos/assets/js';

        if ($this->_loadJquery()) {
            JHtml::_('behavior.framework');

            $document->addScript($lib_folder.'/jquery.min.js');
            $document->addScript($lib_folder.'/jquery-migrate.min.js');
            $document->addScript($lib_folder.'/jquery-noconflict.js');
        }

        if ($app->isAdmin()) {
            $document->addScript($lib_folder.'/jquery-ui-1.8.16.custom.min.js');
            $document->addStyleSheet(JUri::root() . '/administrator/components/com_mijovideos/assets/css/jquery-ui-1.8.16.custom.css');
        }

        if ($app->isSite()) {
			$this->_checkRedirection();
			
            return true;
        }
	}

    public function _loadJquery() {
        $document = JFactory::getDocument();
        $option = JRequest::getCmd('option');

        if ($option != 'com_mijovideos') {
            return false;
        }
		
		if (version_compare(JVERSION, '3.0.0', 'ge')) {
			return false;
		}

        if ($document->getType() != 'html') {
            return false;
        }

        if (!file_exists(JPATH_ADMINISTRATOR.'/components/com_mijovideos/library/mijovideos.php')) {
            return false;
        }

        return true;
    }
	
	public function _checkRedirection() {
		$app = JFactory::getApplication();

        $mijovideos = JPATH_ADMINISTRATOR.'/components/com_mijovideos/library/mijovideos.php';
		if (!file_exists($mijovideos)) {
			return;
		}
		
		require_once($mijovideos);
		
		$plugin = JPluginHelper::getPlugin('system', 'mijovideos');
	    $params = new JRegistry($plugin->params);

		$option = JRequest::getCmd('option');
		
		$link = '';
		
		if (!empty($option)) {
			switch ($option) {
				case 'com_jvideos':
					if ($params->get('redirect_jvideos', '0') == '1') {
						$link = self::_getJvideosLink();
					}
					break;
				default:
					return true;
			}
		}
		
		if (empty($link)) {
			return true;
		}
		
		$Itemid = JRequest::getInt('Itemid', '');
		$lang = JRequest::getWord('lang', '');
		
		if (!empty($Itemid)) {
			$Itemid = '&Itemid='.$Itemid;
		}
		
		if (!empty($lang)) {
			$lang = '&lang='.$lang;
		}
		
		$url = JRoute::_('index.php?option=com_mijovideos&'.$link.$Itemid.$lang);

		$app->redirect($url, '', 'message', true);
	}
}
