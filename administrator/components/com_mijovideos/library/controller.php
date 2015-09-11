<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.application.component.controller');

if (!class_exists('MijosoftController')) {
	if (interface_exists('JController')) {
		abstract class MijosoftController extends JControllerLegacy {}
	}
	else {
		class MijosoftController extends JController {}
	}
}

class MijovideosController extends MijosoftController {
	
	public $_mainframe;
	public $_option;
	public $_component;
	public $_context;
	public $_table;
	public $_model;
	
    public function __construct($context = '', $table = '') {
		parent::__construct();

        $this->_view = MijoVideos::getInput()->getCmd('view', '');
        $this->_task = MijoVideos::getInput()->getCmd('task', '');
        $this->_dashboard = MijoVideos::getInput()->getInt('dashboard', 0);

		if (empty($context) and !empty($this->_view)) {
			$context = $this->_view;
		}
		
		$this->_mainframe = JFactory::getApplication();
		if ($this->_mainframe->isAdmin()) {
			$this->_option = MijoVideos::get('utility')->findOption();
		}
		else {
			$this->_option = JRequest::getCmd('option');
		}
		
		$this->_component = str_replace('com_', '', $this->_option);
		
		$this->_context = $context;
		
		$this->_table = $table;
		if ($this->_table == '') {
			$this->_table = $this->_context;
		}

        if (!MijoVideos::isDashboard()) {
		    $this->_model = $this->getModel($context);
        }

        $this->_document = JFactory::getDocument();
		$this->config = MijoVideos::getConfig();
		
		# Register tasks
		$this->registerTask('add', 'edit');
		$this->registerTask('apply', 'save');
		$this->registerTask('save2new', 'save');
		$this->registerTask('remove', 'delete');
	}
	
	public function display($cachable = false, $urlparams = false) {
        $layout = JRequest::getCmd('layout');

        $function = 'display'.ucfirst($layout);

        $view = $this->getView(ucfirst($this->_context), 'html');

        if (!empty($this->_model)) {
            $view->setModel($this->_model, true);
        }

        if (!empty($layout)) {
            $view->setLayout($layout);
        }

        $view->$function();
	}

	public function edit() {
		JRequest::setVar('hidemainmenu', 1);
		
		$view = $this->getView(ucfirst($this->_context), 'edit');
		$view->setModel($this->_model, true);
		$view->display('edit');
	}

    public function cancel() {
        $this->route();
    }

    public function getDashboardView() {
        $type = 'html';

        $tasks = array('add', 'edit', 'apply', 'save2new');
        if (in_array($this->_task, $tasks)) {
            $type = 'edit';
        }

        $path = JPATH_MIJOVIDEOS_ADMIN.'/views/'.$this->_context.'/view.'.$type.'.php';

        if (file_exists($path)) {
            require_once $path;
        }
        else {
            return null;
        }

        $layout = MijoVideos::getInput()->getCmd('layout', 'default');

        $view_name = 'MijovideosView'.ucfirst($this->_context);
        $model_name = 'MijovideosModel'.ucfirst($this->_context);

        $options['name'] = $this->_context;
        $options['layout'] = $layout;
        $options['base_path'] = JPATH_MIJOVIDEOS_ADMIN;

        $view = new $view_name($options);

        $model_file = JPATH_MIJOVIDEOS_ADMIN.'/models/'.$this->_context.'.php';
        if (file_exists($model_file)) {
            require_once($model_file);

            $this->_model = new $model_name();

            $view->setModel($this->_model, true);
        }

        if (MijoVideos::is30()) {
            JHtml::_('formbehavior.chosen', 'select');
        }

        $this->_document->addStyleSheet(JUri::root() . 'components/com_mijovideos/assets/css/dashboard.css');
        $this->_document->addStyleSheet(JUri::root() . 'administrator/components/com_mijovideos/assets/css/joomla3.css');

        return $view;
    }
	
	public function route($msg = "", $post = array()) {
        if (MijoVideos::get('utility')->isDashboard()) {
            $this->routeDashboard($msg, $post);
            return;
        }

        switch ($this->_task) {
            case 'apply':
            case 'resetstats':
                $link = 'index.php?option='.$this->_option.'&view='.$this->_context.'&task=edit&cid[]='.$post['id'];
                break;
            case 'save2new':
                $link = 'index.php?option='.$this->_option.'&view='.$this->_context.'&task=add';
                break;
            default:
                $link = 'index.php?option='.$this->_option.'&view='.$this->_context;
                break;
        }

        parent::setRedirect($link, $msg);
	}

    public function routeDashboard($msg = "", $post = array()) {
        if (empty($post)) {
            $post = JRequest::get('post', JREQUEST_ALLOWRAW);
            $cid = JRequest::getVar('cid', array(), 'post');

            if (!empty($cid[0])){
                $post['id'] = $cid[0];
            }

            $post['Itemid'] = MijoVideos::getInput()->getInt('Itemid', 0);
        }

        switch ($this->_task) {
            case 'apply':
            case 'resetstats':
                $link = JRoute::_('index.php?option='.$this->_option.'&view='.$this->_context.'&task=edit&cid[]='.$post['id'].'&dashboard=1&Itemid='.$post['Itemid']);
                break;
            case 'save2new':
                $link = JRoute::_('index.php?option='.$this->_option.'&view='.$this->_context.'&task=add&dashboard=1&Itemid='.$post['Itemid']);
                break;
            default:
                $link = JRoute::_('index.php?option='.$this->_option.'&view='.$this->_context.'&dashboard=1&Itemid='.$post['Itemid']);
                break;
        }

        $link = str_replace('&amp;', '&', $link);

        parent::setRedirect($link, $msg);
    }
	
	# Delete
	public function delete() {
		# Check token
		JRequest::checkToken() or jexit('Invalid Token');

        $cid = JRequest::getVar('cid', array(), 'post');
        JArrayHelper::toInteger($cid);

        // Is this really a folder?
        foreach ($cid as $id) {
			$path_folder = JPATH_ROOT. '/media/com_mijovideos/images/'.$this->_context.'/'.$id;
            if(JFolder::exists($path_folder)) {
                JFolder::delete($path_folder);
            }
        }
		
		# Action
		if (!self::deleteRecord($this->_table, $this->_model)) {
			$msg = JText::_('COM_MIJOVIDEOS_COMMON_RECORDS_DELETED_NOT');
		} else {
			$msg = JText::_('COM_MIJOVIDEOS_COMMON_RECORDS_DELETED');
		}
		
		# Return
		self::route($msg);

        return $msg;
	}
	
	public function deleteRecord($table, $model, $where = true) {
		if ($where === true) {
			$where = self::_getWhere($model);
		}
		
		if (!MijoDB::query("DELETE FROM #__{$this->_component}_{$table}{$where}")) {
			return false;
		}

		return true;
    }
	
	public function _getWhere($model, $prefix = "") {
        $where = '';
		
        $sel = JRequest::getVar('selection', 'selected', 'post');
        if ($sel == 'selected') {
            $where = self::_buildSelectedWhere($prefix);
        } elseif ($sel == 'filtered') {
            $where = $model->_buildViewWhere($prefix);
        }
        
        return $where;
    }
	
	# Get the id's of selected records
	public function _buildSelectedWhere($prefix = "") {
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cid);
		
		$where = '';
		if(count($cid) > 0){
			$where = " WHERE {$prefix}id IN (".implode(',',$cid).")";
		}

		return $where;
	}
	
	# Publish
	public function publish() {
		# Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		# Action
		self::updateField($this->_table, 'published', 1, $this->_model);
		
		# Return
		self::route();
	}
	
	# Unpublish
	public function unpublish() {
		# Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		# Action
		self::updateField($this->_table, 'published', 0, $this->_model);
		
		# Return
		self::route();
	}

    # Feature
    public function feature() {
        # Check token
        JRequest::checkToken() or jexit('Invalid Token');

        # Action
        self::updateField($this->_table, 'featured', 1, $this->_model);

        # Return
        self::route();
    }

    # Unfeature
    public function unfeature() {
        # Check token
        JRequest::checkToken() or jexit('Invalid Token');

        # Action
        self::updateField($this->_table, 'featured', 0, $this->_model);

        # Return
        self::route();
    }

    public function resetStats() {
        $id = MijoVideos::getInput()->getInt('id', 0);
        $type = MijoVideos::getInput()->getWord('type', '');

        self::updateField($this->_table, $type, 0, $this->_model);

        $post = array();
        $post['id'] = $id;

        self::route(JText::_('COM_MIJOVIDEOS_RESET_STATS'), $post);
    }
	
	public function checkLikesDislikes() {
        $item_id    = MijoVideos::getInput()->getInt('item_id');
        $item_type  = MijoVideos::getInput()->getWord('item_type');
        $user       = JFactory::getUser();
        $json       = array();
        if($user->id != 0) {
            $ret = $this->_model->checkLikesDislikes($item_id, $item_type);
            if(is_null($ret)) {
                $json['type'] = -1;
            } else {
                $json['type'] = $ret;
            }
            $json['success'] = 1;
        } else {
            $json['redirect'] = MijoVideos::get('utility')->redirectWithReturn();
        }
        echo json_encode($json);
        exit();
    }

    public function likeDislikeItem() {
        $type       = MijoVideos::getInput()->getInt('type', '');
        $item_id    = MijoVideos::getInput()->getInt('item_id');
        $item_type  = MijoVideos::getInput()->getWord('item_type', '');
        $change     = MijoVideos::getInput()->getInt('change', '');
        JRequest::setVar('selection', 'filtered', 'post');
        $user       = JFactory::getUser();
        $json       = array();
        if($user->id != 0) {
            switch ($type) {
                case 1:
                    switch ($change) {
                        case 1:
                            self::updateField('likes', 'type', $type, $this->_model);
                            if(self::decreaseField($item_type, 'dislikes', 1, 'WHERE id IN('.$item_id.')') && self::increaseField($item_type, 'likes', 1, 'WHERE id IN('.$item_id.')')) $ret = true;
                            break;
                        case -1:
                            $this->_model->unlikeItem($item_id, $item_type, 1);
                            $ret = self::decreaseField($item_type, 'likes', 1, 'WHERE id IN('.$item_id.')');
                            break;
                        default:
                            $ret = self::increaseField($item_type, 'likes', 1, 'WHERE id IN('.$item_id.')');
                            $this->_model->likeDislikeItem($user->id, $item_id, $item_type, $type);
                            break;
                    }
                    break;
                case 2:
                    switch ($change) {
                        case 1:
                            self::updateField('likes', 'type', $type, $this->_model);
                            if (self::decreaseField($item_type, 'likes', 1, 'WHERE id IN('.$item_id.')') && self::increaseField($item_type, 'dislikes', 1, 'WHERE id IN('.$item_id.')')) $ret = true;
                            break;
                        case -1:
                            $this->_model->unlikeItem($item_id, $item_type, 2);
                            $ret =  self::decreaseField($item_type, 'dislikes', 1, 'WHERE id IN('.$item_id.')');
                            break;
                        default:
                            $ret = self::increaseField($item_type, 'dislikes', 1, 'WHERE id IN('.$item_id.')');
                            $this->_model->likeDislikeItem($user->id, $item_id, $item_type, $type);
                            break;
                    }
                    break;
            }

            if($ret) $json['success'] = 1;
        } else {
            $json['redirect'] = MijoVideos::get('utility')->redirectWithReturn();
        }
        echo json_encode($json);
        exit();
    }
	
   	# Save changed record
	public function saveRecord($post, $table, &$id = 0) {
		# Get row
		$row = MijoVideos::getTable($table);
		
		# Bind the form fields to the table
		if (!$row->bind($post)) {
			return JError::raiseWarning(500, $row->getError());
		}
		
		# Make sure the record is valid
		if (!$row->check()) {
			return JError::raiseWarning(500, $row->getError());
		}
		
		# Save record
		if (!$row->store()) {
			return JError::raiseWarning(500, $row->getError());
		}
		
		if (empty($id)) {
			$id = $row->id;
		}
		
		return true;
	}
	
	# Save changes
	public function save() {
		# Check token
		JRequest::checkToken() or jexit('Invalid Token');

		# Get post
		$post = JRequest::get('post', JREQUEST_ALLOWRAW);

        $cid = $post['cid'];
        $post['id'] = (int) $cid[0];

        if (isset($post['channel_id'])) {
            $post['user_id'] = MijoVideos::get('channels')->getUserId($post['channel_id']);
        }

        if (empty($post['channel_id']) and empty($post['user_id'])) {
            $user_id = JFactory::getUser()->get('id');
            $post['channel_id'] = MijoVideos::get('channels')->getDefaultChannel()->id;
            $post['user_id'] = $user_id;
        }

        $table = ucfirst($this->_component).ucfirst($this->_context);
        $row = MijoVideos::getTable($table);
        $row->load($post['id']);

        # Custom Fields
        if (!empty($post['custom_fields'])) {
  		    $row->fields = json_encode($post['custom_fields']);
        }

		# Save record
		$table = ucfirst($this->_component).ucfirst($this->_context);
		
		if (!self::saveRecord($post, $table, $post['id'])) {
            $msg = JText::_('COM_MIJOVIDEOS_COMMON_RECORD_SAVED_NOT');

			return JError::raiseWarning(500, $msg);
		}
        else {
            JRequest::setVar('id', $post['id']);

            $msg = JText::_('COM_MIJOVIDEOS_COMMON_RECORD_SAVED');

            self::route($msg, $post);
		}

        $thumb_size = MijoVideos::get('utility')->getThumbSize($this->config->get('thumb_size'));

        # Thumb Image
        if (isset($_FILES['thumb_image']['name'])) {
            $fileExt = strtolower(JFile::getExt($_FILES['thumb_image']['name']));
            $supportedTypes = array('jpg', 'png', 'gif');
            if (in_array($fileExt, $supportedTypes)) {
                $fileName = hash('haval256,5', JString::strtolower($_FILES['thumb_image']['name'])) . '.' . $fileExt;
                $imagePath = JPATH_ROOT.'/media/com_mijovideos/images/'.$this->_context.'/'.$post['id'].'/orig/'.$fileName;
                $thumbPath = JPATH_ROOT.'/media/com_mijovideos/images/'.$this->_context.'/'.$post['id'].'/'.$thumb_size.'/'.$fileName;
                JFile::upload($_FILES['thumb_image']['tmp_name'], $imagePath);
                JFolder::create(JPATH_ROOT.'/media/com_mijovideos/images/'.$this->_context.'/'.$post['id'].'/'.$thumb_size.'/');
                MijoVideos::get('utility')->resizeImage($imagePath, $thumbPath, $thumb_size, $thumb_size, 95);
                $post['thumb'] = $fileName;
                $this->updateField($this->_table, 'thumb', $fileName, $this->_model);
            }
        }

        # Banner Image
        if (isset($_FILES['banner_image']['name'])) {
            $fileExt = strtolower(JFile::getExt($_FILES['banner_image']['name']));
            $supportedTypes = array('jpg', 'png', 'gif');
            if (in_array($fileExt, $supportedTypes)) {
                $fileName = hash('haval256,5', JString::strtolower($_FILES['banner_image']['name'])) . '.' . $fileExt;
                $imagePath = JPATH_ROOT.'/media/com_mijovideos/images/'.$this->_context.'/'.$post['id'].'/banner/'.$fileName;
                $thumbPath = JPATH_ROOT.'/media/com_mijovideos/images/'.$this->_context.'/'.$post['id'].'/banner/thumb/'.$fileName;
                JFile::upload($_FILES['banner_image']['tmp_name'], $imagePath);
                JFolder::create(JPATH_ROOT.'/media/com_mijovideos/images/'.$this->_context.'/'.$post['id'].'/banner/thumb/');
                MijoVideos::get('utility')->resizeImage($imagePath, $thumbPath, 1070, 175, 95);
                $post['banner'] = $fileName;
                $this->updateField($this->_table, 'banner', $fileName, $this->_model);
            }
        }

        if (isset($post['del_thumb']) and $row->thumb) {
            if (JFile::exists(JPATH_ROOT.'/media/com_mijovideos/images/'.$this->_context.'/'.$post['id'].'/orig/'.$row->thumb)) {
                JFile::delete(JPATH_ROOT.'/media/com_mijovideos/images/'.$this->_context.'/'.$post['id'].'/orig/'.$row->thumb);
            }

            if (JFile::exists(JPATH_ROOT.'/media/com_mijovideos/images/'.$this->_context.'/'.$post['id'].'/'.$thumb_size.'/'.$row->thumb)) {
                JFile::delete(JPATH_ROOT.'/media/com_mijovideos/images/'.$this->_context.'/'.$post['id'].'/'.$thumb_size.'/'.$row->thumb);
            }

            $post['thumb'] = '';
        }

        if (isset($post['del_banner']) and $row->banner) {
            if (JFile::exists(JPATH_ROOT.'/media/com_mijovideos/images/'.$this->_context.'/'.$post['id'].'/banner/'.$row->banner)) {
                JFile::delete(JPATH_ROOT.'/media/com_mijovideos/images/'.$this->_context.'/'.$post['id'].'/banner/'.$row->banner);
            }

            if (JFile::exists(JPATH_ROOT.'/media/com_mijovideos/images/'.$this->_context.'/'.$post['id'].'/banner/thumb/'.$row->banner)) {
                JFile::delete(JPATH_ROOT.'/media/com_mijovideos/images/'.$this->_context.'/'.$post['id'].'/banner/thumb/'.$row->banner);
            }

            $post['banner'] = '';
        }

        if (($this->_context == 'channels') and ($post['default'] == 1)) {
            MijoDB::query("UPDATE `#__mijovideos_channels` SET `default` = 0 WHERE user_id = {$post['user_id']} AND id <> {$post['id']}");
        }

        return $msg;
	}
	
	# Update field
	public function updateField($table, $field, $value, $model, $where = true) {
		if ($where === true) {
			$where = self::_getWhere($model);
		}
		
		if (!MijoDB::query("UPDATE #__{$this->_component}_{$table} SET `{$field}` = '{$value}' {$where}")) {
			return false;
		}

		return true;
	}
	
	# Increase likes,dislikes,hits fields
	public function increaseField($table, $field, $value, $where = true) {

		if (!MijoDB::query("UPDATE #__{$this->_component}_{$table} SET `{$field}` = `{$field}` + {$value} {$where}")) {
			return false;
		}

		return true;
	}

    # Decrease likes,dislikes,hits fields
    public function decreaseField($table, $field, $value, $where = true) {

		if (!MijoDB::query("UPDATE #__{$this->_component}_{$table} SET `{$field}` = `{$field}` - {$value} {$where}")) {
			return false;
		}

		return true;
	}
	
	# Update param
	public function updateParam($table, $table_m, $field, $param, $value, $model, $where = true) {
		if (!$ids = self::_getIDs($table, $model, $where)) {
			return;
		}
		
		$row = MijoVideos::getTable($table_m);
		
		if (!empty($ids) && is_array($ids)) {
			foreach ($ids as $index => $id) {
				if (!$row->load($id)) {
					continue;
				}
				
				$params = new JRegistry($row->$field);
				$params->set($param, $value);
				
				$row->$field = $params->toString();
				
				if (!$row->check()) {
					continue;
				}
				
				if (!$row->store()) {
					continue;
				}
			}
		}
	}
	
	# Get IDs
	public function _getIDs($table, $model, $where = true) {
		if ($where === true) {
			$where = self::_getWhere($model);
		}
		
		if (!$ids = MijoDB::loadResultArray("SELECT id FROM #__{$this->_component}_{$table} {$where}")) {
			return false;
		}
		
		return $ids;
	}

    public function saveOrder() {
        # Check token
        JRequest::checkToken() or jexit('Invalid Token');

        $ret = $this->_model->saveOrder(ucfirst($this->_component).ucfirst($this->_context));
        if ($ret) {
            $msg = JText::_('COM_MIJOVIDEOS_ORDERING_SAVED');
        }
        else {
            $msg = JText::_('COM_MIJOVIDEOS_ORDERING_SAVING_ERROR');
        }

        $this->setRedirect('index.php?option='.$this->_option.'&view='.$this->_context, $msg);
    }

    public function subscribeToItem() {
        # Get vars
        $user = JFactory::getUser();
        $json = array();
        if($user->id != 0) {
            $type = MijoVideos::getInput()->getWord('type', 0);
            $item_id = MijoVideos::getInput()->getInt('id', 0);
                if ($type == "subscribe") {
                    if (is_null($this->_model->checkSubscription($item_id))) {
                    $ret = $this->_model->subscribeToItem($item_id);
                    } else {
                        $json['error'] = JText::_('COM_MIJOVIDEOS_SUBSCRIBER_EXIST');
                        $ret = false;
                    }
                } else {
                    $ret = $this->_model->unsubscribeItem($item_id);
                }

                $subs = $this->_model->getSubscriberCount($item_id);

                if ($ret) {
                    $json['success'] = 1;
                    $json['count'] = $subs;
                }
                else {
                    $json['success'] = 0;
                    $json['count'] = $subs;
                }
        } else {
            $json['redirect'] = MijoVideos::get('utility')->redirectWithReturn();
        }
        echo json_encode($json);
        exit();
    }
    
    public function orderUp() {
        # Check token
        JRequest::checkToken() or jexit('Invalid Token');

        $this->_model->move(ucfirst($this->_component).ucfirst($this->_context), -1);

        $msg = JText::_('COM_MIJOVIDEOS_ORDERING_UPDATED');

        $this->setRedirect('index.php?option='.$this->_option.'&view='.$this->_context, $msg);
    }

    public function orderDown() {
        # Check token
        JRequest::checkToken() or jexit('Invalid Token');

        $this->_model->move(ucfirst($this->_component).ucfirst($this->_context), 1);

        $msg = JText::_('COM_MIJOVIDEOS_ORDERING_UPDATED');

        $this->setRedirect('index.php?option='.$this->_option.'&view='.$this->_context, $msg);
    }

    public function copy() {
        # Check token
        JRequest::checkToken() or jexit('Invalid Token');

        $this->_model->copy(ucfirst($this->_component).ucfirst($this->_context));

        $msg = JText::_('COM_MIJOVIDEOS_RECORD_COPIED');

        $this->setRedirect('index.php?option='.$this->_option.'&view='.$this->_context, $msg);

        return $msg;
    }

    public function autoComplete() {
        $query = JRequest::getString('query');
        $channels = json_encode($this->_model->autoComplete($query));

        echo $channels;
        exit();
    }

    public function createAutoFieldHtml() {
        $fieldid = JRequest::getInt('fieldid');
        $html = MijoVideos::get('fields')->createAutoFieldHtml($fieldid);

        echo $html;
        exit();
    }

    function cdn() {

        if ($this->config->get('pid') != JRequest::getVar('token')) {
            die('Invalid token');
        }

        $plugin = MijoVideos::getPlugin($this->config->get('cdn'));

        if ($plugin) {
            return $plugin->maintenance();
        } else {
            die($this->config->get('cdn') . ' plugin is not published');
        }
    }
}