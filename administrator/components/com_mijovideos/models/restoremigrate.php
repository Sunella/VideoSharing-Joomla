<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted Access');

# Imports
jimport('joomla.filesystem.file');

class MijovideosModelRestoreMigrate extends MijovideosModel {

	public function __construct() {
		parent::__construct('restoremigrate');
	}

    public function backup() {
		list($query, $filename, $fields, $line) = $this->_getBackupVars();
		
		$ret = Mijosef::get('utility')->backupDB($query, $filename, $fields, $line);

		return $ret;
    }

    public function restore() {
		# Get the uploaded file
		if (!$file = $this->_getUploadedFile()) {
			return false;
		}

		# Load SQL
		$lines = file($file);

		$result = true;
		for ($i = 0, $n = count($lines); $i < $n; $i++) {
			# Trim line
			$line = trim($lines[$i]);
			
			list($preg, $line) = $this->_getRestorePregLine($line);
			
			# Ignore empty lines
			if (strlen($line) == 0 || empty($line) || $line == '') {
				continue;
			}

			# If the query continues at the next line.
			while (substr($line, -1) != ';' && $i + 1 < count($lines)) {
				$i++;
				$newLine = trim($lines[$i]);
				
				if (strlen($newLine) == 0) {
					continue;
				}
				
				$line .= ' '.$lines[$i];
			}

			if (preg_match($preg, $line) > 0) {
				$this->_db->setQuery($line);
				if (!$this->_db->query()) {
					JError::raiseWarning( 100, JText::_('Error importing line').': '.$line.'<br />'.$this->_db->getErrorMsg());
					$result = false;
				}
			} else {
				JError::raiseWarning(100, JText::_('Ignoring line').': '.$line);
			}
		}

		JFile::delete($file);
		
		return $result;
    }

    public function migrate() {
        $ret = false;

        Mijovideos::get('utility')->import('library.backuprestore');

        $items = array('HwdMediaShareCats', 'HwdMediaShareChannels', 'HwdMediaSharePlaylists', 'HwdMediaShareVideos', 'HwdMediaShareLikes', 'HwdMediaShareReports',
                        'ContusHDVideoShareCats', 'ContusHDVideoShareVideos',
                        'JomWebplayerCats', 'JomWebplayerVideos',
                        'HdwPlayerCats', 'HdwPlayerVideos',
                        'AllVideoShareCats', 'AllVideoShareVideos',
                        'XMoviesCats', 'XMoviesVideos',
                        'VideoFlowCats', 'VideoFlowVideos',
                        'HdFlvPlayerCats', 'HdFlvPlayerVideos');

        foreach ($items as $item) {
            if (MijoVideos::getInput()->getCmd('migrate_'.$item, 0, 'post')) {
                $class = new MijovideosBackupRestore(array('_table' => $item, '_where' => ''));
                $function = 'migrate' . ucfirst($item);

                $ret = $class->$function();

                return $ret;
            }
        }

        return $ret;
    }

    public function _getBackupVars() {
        Mijovideos::get('utility')->import('library.backuprestore');

        $items = array('categories', 'channels', 'playlists', 'playlistvideos', 'videos', 'videocategories', 'subscriptions', 'likes', 'reports', 'files', 'reasons');

        foreach ($items as $item) {
            if (MijoVideos::getInput()->getCmd('backup_'.$item, 0, 'post')) {
                $class = new MijovideosBackupRestore(array('_table' => $item, '_where' => ''));
                $function = 'backup' . ucfirst($item);

                list($query, $filename, $fields, $line) = $class->$function();

                return array($query, $filename, $fields, $line);
            }
        }
    }

    public function _getRestorePregLine($line) {
		Mijovideos::get('utility')->import('library.backuprestore');
		
		$items = array('categories', 'channels', 'playlists', 'playlistvideos', 'videos',  'videocategories', 'subscriptions', 'likes', 'reports', 'files', 'reasons');

		foreach ($items as $item) {
			if (MijoVideos::getInput()->getCmd('restore_'.$item, 0, 'post')) {
				$class = new MijovideosBackupRestore();
				$function = 'restore' . ucfirst($item);
				
				list($preg, $line) = $class->$function($line);
				
				return array($preg, $line);
			}
		}
	}

    public function _getUploadedFile () {
		$userfile = MijoVideos::getInput()->getVar('file_restore', null, 'files', 'array');

		# Make sure that file uploads are enabled in php
		if (!(bool) ini_get('file_uploads')) {
			JError::raiseWarning(100, JText::_('WARNINSTALLFILE'));
			return false;
		}

		# Make sure that zlib is loaded so that the package can be unpacked
		if (!extension_loaded('zlib')) {
			JError::raiseWarning(100, JText::_('WARNINSTALLZLIB'));
			return false;
		}

		# If there is no uploaded file, we have a problem...
		if (!is_array($userfile) ) {
			JError::raiseWarning(100, JText::_('No file selected'));
			return false;
		}

		# Check if there was a problem uploading the file.
		if ( $userfile['error'] || $userfile['size'] < 1 ) {
			JError::raiseWarning(100, JText::_('WARNINSTALLUPLOADERROR'));
			return false;
		}

		# Build the appropriate paths
		$config = JFactory::getConfig();
		$tmp_dest = $config->get('tmp_path').'/'.$userfile['name'];
		$tmp_src  = $userfile['tmp_name'];

		# Move uploaded file
		jimport('joomla.filesystem.file');
		$uploaded = JFile::upload($tmp_src, $tmp_dest);
		
		if (!$uploaded) {
			JError::raiseWarning('SOME_ERROR_CODE', '<br /><br />' . JText::_('File not uploaded, please, make sure the "Global Configuration => Server => Path to Temp-folder" is valid.') . '<br /><br /><br />');
			return false;
		}
		
		return $tmp_dest;
	}
}