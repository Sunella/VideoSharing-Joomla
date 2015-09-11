<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted Access');

# Imports
jimport('joomla.installer.installer');
jimport('joomla.installer.helper');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

# Model Class
class MijovideosModelUpgrade extends MijovideosModel {

	# Main constructer
	public function __construct() {
        parent::__construct('upgrade');
    }
    
	# Upgrade
    public function upgrade() {
        $utility = MijoVideos::get('utility');

		# Get package
		$type = MijoVideos::getInput()->getCmd('type');
		if ($type == 'upload') {
			$userfile = JRequest::getVar('install_package', null, 'files', 'array');
			$package = $utility->getPackageFromUpload($userfile);
		}
        else if ($type == 'server') {
			$package = $utility->getPackageFromServer('index.php?option=com_mijoextensions&view=download&model=com_mijovideos&pid='.$utility->getConfig()->pid);
		}

		# Was the package unpacked?
		if (!$package or empty($package['dir'])) {
			$this->setState('message', 'Unable to find install package.');
			return false;
		}

        $video_file = $package['dir'].'/com_mijovideos.zip';
        $jquery_file = $package['dir'].'/plg_mijovideos_jquery.zip';
		
        if (JFile::exists($video_file)) {
            $p1 = $utility->unpack($video_file);
            $installer = new JInstaller();
            $installer->install($p1['dir']);
			
			if (JFile::exists($jquery_file)) {
				$p2 = $utility->unpack($jquery_file);
				$installer = new JInstaller();
				$installer->install($p2['dir']);
			}
        }
        else {
            $installer = new JInstaller();
            $installer->install($package['dir']);
        }

		JFolder::delete($package['dir']);

		return true;
    }
}