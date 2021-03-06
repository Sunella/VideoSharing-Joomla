<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_ADMINISTRATOR . '/components/com_mijovideos/library/cdn.php');

class plgMijovideosAmazons3 extends MijovideosCdn {

    public function __construct(&$subject, $config) {
        parent::__construct($subject, $config);
    }

    public function maintenance() {
        JLoader::register('S3', JPATH_ROOT . '/plugins/mijovideos/amazons3/assets/S3.php');
        JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_mijovideos/tables');
        $utility = MijoVideos::get('utility');
        $config = MijoVideos::getConfig();

        // AWS access info
        $awsAccessKey      = $this->params->get('awsAccessKey', '');
        $awsSecretKey      = $this->params->get('awsSecretKey', '');
        $bucketName        = $this->params->get('awsBucket', '');
        $reducedRedundancy = $this->params->get('awsRrs', 0);
        $location          = $this->params->get('awsRegion', 'us-west-1');

        // marpada-S
        switch ($location) {
            case "us-west-1":
                $endpoint = 's3-us-west-1.amazonaws.com';
                break;
            case "EU":
                $endpoint = 's3-eu-west-1.amazonaws.com';
                break;
            case "ap-southeast-1":
                $endpoint = "s3-ap-southeast-1.amazonaws.com";
                break;
            case "ap-northeast-1":
                $endpoint = "s3-ap-northeast-1.amazonaws.com";
                break;
            default:
                $endpoint = 's3.amazonaws.com';
        }

        // Windows curl extension has trouble with SSL connections, so we won't use it
        if (substr(PHP_OS, 0, 3) == "WIN") {
            $useSSL = 0;
        } else {
            $useSSL = 1;
        }

        //marpada-E

        // Check for CURL
        if (!extension_loaded('curl') && !@dl(PHP_SHLIB_SUFFIX == 'so' ? 'curl.so' : 'php_curl.dll'))
            exit("ERROR: CURL extension not loaded");

        // Pointless without your keys!
        if ($awsAccessKey == '' || $awsSecretKey == '')
            exit("ERROR: AWS access information required");

        // Instantiate the class
        $s3 = new S3($awsAccessKey, $awsSecretKey, $useSSL, $endpoint);

        if ($reducedRedundancy) {
            $storage = S3::STORAGE_CLASS_RRS;
        } else {
            $storage = S3::STORAGE_CLASS_STANDARD;
        }

        //Check if bucket exists and if it belongs to the defautt region
        $bucketlocation = $s3->getBucketLocation($bucketName);
        if (($bucketlocation) && ($bucketlocation <> $location)) {
            echo "Bucket already exist in " . $bucketlocation . " region";
            $location = $bucketlocation;
            switch ($location) {
                case "us-west-1":
                    $s3->setEndpoint('s3-us-west-1.amazonaws.com');
                    break;
                case "EU":
                    $s3->setEndpoint('s3-eu-west-1.amazonaws.com');
                    break;
                case "ap-southeast-1":
                    $s3->setEndpoint('s3-ap-southeast-1.amazonaws.com');
                    break;
                case "ap-northeast-1":
                    $s3->setEndpoint('s3-ap-northeast-1.amazonaws.com');
                    break;
                default:
                    $s3->setEndpoint('s3.amazonaws.com');
            }
        }

        // Create a bucket with public read access
        $s3->putBucket($bucketName, S3::ACL_PUBLIC_READ, $location);

        // Get the contents of our bucket
        $cdnContents = $s3->getBucket($bucketName);

        // Get local queue
        $queued = $this->getLocalQueue();
        echo "About to process " . count($queued) . " video items<br />" . PHP_EOL;
        foreach ($queued as $video) {
            $errors = false;
            $db = JFactory::getDBO();

            // Select queued processes from the table.
            $query = 'SELECT COUNT(*) FROM #__mijovideos_processes WHERE video_id = ' . $video->id . ' AND (status = 0) AND (published = 1)';

            $db->setQuery($query);
            $queuedProcesses = $db->loadResult();
            if ($queuedProcesses > 0) {
                echo "[ID:" . $video->id . "] Video has queued processes which need to be completed or deleted before the transfer of this video item</strong><br />" . PHP_EOL;
                $errors = true;
                continue;
            }

            // Get files for local media
            $files = MijoVideos::get('files')->getVideoFiles($video->id);
            if (count($files) == 0) {
                echo "[ID:" . $video->id . "] Video has no files</strong><br />" . PHP_EOL;
                $errors = true;
                continue;
            }

            foreach ($files as $file) {
                if ($file->process_type == 100) {
                    if ($file->ext == 'jpg') {
                        $size = MijoVideos::get('utility')->getThumbSize($config->get('thumb_size'));
                    } else {
                        $file_path = $utility->getVideoFilePath($video->id, 'orig', $video->source);
                        $size = $utility->getVideoSize(JPATH_ROOT . $file_path);
                    }
                } else {
                    $size = MijoVideos::get('processes')->getTypeSize($file->process_type);
                }

                if ($file->process_type == 200) {
                    $relativePath = $utility->getVideoFilePath($video->id, 'orig', $video->source);
                } else {
                    if ($file->ext == 'jpg' or $file->ext == 'thumb') {
                        $relativePath = str_replace(JUri::root(), '', '/'.$utility->getThumbPath($file->video_id, 'videos', $file->source, $size));
                    } else {
                        $relativePath = $utility->getVideoFilePath($file->video_id, $size, $file->source);
                    }
                }
                $path = JPATH_ROOT . $relativePath;
                // Check local file exists
                if (!file_exists($path)) {
                    echo "[ID:" . $video->id . "] Source File [<strong>$path</strong>] does not exist so skipping</strong><br />" . PHP_EOL;
                    $errors = true;
                    continue;
                }

                // If more than 500mb, we might struggle to transfer this in the timeout...
                if (filesize($path) > 524288000) {
                    echo "[ID:" . $video->id . "] Source File [<strong>$path</strong>] is larger than 0.5GB so skipping</strong><br />" . PHP_EOL;
                    $errors = true;
                    continue;
                }

                if (isset($cdnContents[$relativePath])) {
                    if ($cdnContents[$relativePath]['size'] == filesize($path)) {
                        echo "[ID:" . $video->id . "] File [<strong>$relativePath</strong>] already exists in <strong>{$bucketName}</strong><br />" . PHP_EOL;
                    } else {
                        echo "[ID:" . $video->id . "] File [<strong>$relativePath</strong>] must be updated <strong>{$bucketName}</strong><br />" . PHP_EOL;
                        if (@$s3->putObject($s3->inputFile($path, false), $bucketName, $relativePath, S3::ACL_PUBLIC_READ, array(), array(), $storage)) {
                            echo "[ID:" . $video->id . "] S3::putObject(): File copied to {$bucketName}/" . $relativePath . "</br>" . PHP_EOL;
                        } else {
                            echo "[ID:" . $video->id . "] S3::putObject(): Failed to copy file </br>" . PHP_EOL;
                            $errors = true;
                            continue;
                        }
                    }
                } else {
                    // Put our file (also with public read access)
                    if ($s3->putObject($s3->inputFile($path, false), $bucketName, $relativePath, S3::ACL_PUBLIC_READ, array(), array(), $storage)) {
                        echo "[ID:" . $video->id . "] S3::putObject(): File copied to {$bucketName}/" . $relativePath . "</br>" . PHP_EOL;
                    } else {
                        echo "[ID:" . $video->id . "] S3::putObject(): Failed to copy file </br>" . PHP_EOL;
                        $errors = true;
                        continue;
                    }
                }
                if (!$errors) {
                    JRequest::setVar('cid', $file->id, 'post');
                    $source = "http://" . $bucketName . "." . $endpoint . $relativePath;
                    if (!MijoVideos::get('controller')->updateField('files', 'source', $source, null)) {
                        JError::raiseWarning(500, 'Error when updating file source');
                    }
                }

            }

            // If no errors, and all local files exist on CDN then modify database so media
            // if switched to CDN, delete all local files
            if (!$errors) {
                echo "[ID:" . $video->id . "] Updating database </br>" . PHP_EOL;
                $file_path = $utility->getVideoFilePath($video->id, 'orig', $video->source);
                $thumb_size = $utility->getThumbSize($config->get('thumb_size'));
                $thumb_path = '/media/com_mijovideos/images/videos/'. $video->id . '/' . $thumb_size . '/' . $video->thumb;

                JRequest::setVar('cid', $video->id, 'post');
                $source = "http://" . $bucketName . "." . $endpoint . $file_path;
                $thumb = "http://" . $bucketName . "." . $endpoint . $thumb_path;

                if (!MijoVideos::get('controller')->updateField('videos', 'source', $source, null)) {
                    JError::raiseWarning(500, 'Error when updating video source');
                }

                if (!MijoVideos::get('controller')->updateField('videos', 'thumb', $thumb, null)) {
                    JError::raiseError(500, 'Error when updating video thumbnail');
                }

                echo "[ID:" . $video->id . "] Database updated </br>" . PHP_EOL;

                if (!JFolder::delete(JPATH_ROOT . '/media/com_mijovideos/videos/' . $video->id) or !JFolder::delete(JPATH_ROOT . '/media/com_mijovideos/images/videos/' . $video->id)) {
                    JError::raiseWarning(500, "File delete error");
                } else {
                    echo "[ID:" . $video->id . "] Delete local file </br>" . PHP_EOL;
                }

            }
        }
    }
}