<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted access');

$view = JRequest::getCmd('view');

JHtml::_('behavior.switcher');

// Load submenus
$views = array( ''							    => JText::_('COM_MIJOVIDEOS_COMMON_PANEL'),
				'&view=fields'				    => JText::_('COM_MIJOVIDEOS_CPANEL_FIELDS'),
				'&view=categories'			    => JText::_('COM_MIJOVIDEOS_CPANEL_CATEGORIES'),
                '&view=channels'			    => JText::_('COM_MIJOVIDEOS_CPANEL_CHANNELS'),
				'&view=playlists'				=> JText::_('COM_MIJOVIDEOS_CPANEL_PLAYLISTS'),
				'&view=videos'			    	=> JText::_('COM_MIJOVIDEOS_CPANEL_VIDEOS'),
                '&view=subscriptions'			=> JText::_('COM_MIJOVIDEOS_CPANEL_SUBSCRIPTIONS'),
				'&view=reports'			    	=> JText::_('COM_MIJOVIDEOS_CPANEL_REPORTS'),
				'&view=files'			    	=> JText::_('COM_MIJOVIDEOS_CPANEL_FILES'),
				'&view=processes'			   	=> JText::_('COM_MIJOVIDEOS_CPANEL_PROCESSES'),
				'&view=upgrade'				    => JText::_('COM_MIJOVIDEOS_CPANEL_UPGRADE'),
				'&view=support&task=support'	=> JText::_('COM_MIJOVIDEOS_CPANEL_SUPPORT'),
				);

if (!class_exists('JSubMenuHelper')) {
    return;
}

foreach($views as $key => $val) {
	if ($key == '') {
		$active	= ($view == $key);
		
		$img = 'icon-16-mijovideos.png';
	}
	else {
	    $a = explode('&', $key);
	  	$c = explode('=', $a[1]);
	
		$active	= ($view == $c[1]);
	
		$img = 'icon-16-mijovideos-'.$c[1].'.png';
	}
	
	JSubMenuHelper::addEntry('<img src="components/com_mijovideos/assets/images/'.$img.'" style="margin-right: 2px;" align="absmiddle" />&nbsp;'.$val, 'index.php?option=com_mijovideos'.$key, $active);
}