<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosViewVideo extends MijovideosView {

    public function __construct($config = array()) {
        parent::__construct($config);
    }

	public function display($tpl = null) {
        $user = JFactory::getUser();
        $user_id = $user->get('id');

        $pathway = $this->_mainframe->getPathway();

        $item = $this->get('Data');

        if (is_object($item) and !$this->acl->canAccess($item->access)) {
            $this->_mainframe->redirect(JRoute::_('index.php?option=com_mijovideos&view=category'), JText::_('JERROR_ALERTNOAUTHOR'), 'error');
        }

        MijoVideos::get('utility')->hitsCounter('video');

        $Itemid = MijoVideos::get('router')->getItemid(array('view' => 'video', 'video_id' => $item->id), null, true);
        if (empty($Itemid)) {
            $Itemid = MijoVideos::get('router')->getItemid(array('view' => 'category', 'category_id' => $item->category_id), null, true);

            if (empty($Itemid)) {
                $Itemid = MijoVideos::get('router')->getItemid(array('view' => 'channel', 'channel_id' => $item->channel_id), null, true);
            }
        }

        $item->description = $item->introtext.$item->fulltext;

		$category = Mijovideos::get('utility')->getCategory($item->category_id);

        if ($this->config->get('load_plugins')) {
            $item->description = JHtml::_('content.prepare', $item->description);
        }

		# BreadCrumbs
        $active_menu = $this->_mainframe->getMenu()->getActive();
        if (!isset($active_menu->query['video_id']) or ($active_menu->query['video_id'] != $item->id)) {
            $cats = Mijovideos::get('utility')->getCategories($item->category_id);

            if (!empty($cats)) {
                asort($cats);
                foreach ($cats as $cat) {
                    $Itemid = MijoVideos::get('router')->getItemid(array('view' => 'category', 'category_id' => $cat->id), null, true);

                    $path_url = JRoute::_('index.php?option=com_mijovideos&view=category&category_id='.$cat->id.$Itemid);
                    $pathway->addItem($cat->title, $path_url);
                }

                $pathway->addItem($item->title);
            }
        }

		if ($item->channel_id) {
			$this->channels = "";
		}

		$page_title = JText::_('COM_MIJOVIDEOS_VIDEO_PAGE_TITLE');
        $page_title = str_replace('[VIDEO_TITLE]', $item->title, $page_title);
        $page_title = str_replace('[CATEGORY_NAME]', $category->title, $page_title);

        if ($this->_mainframe->getCfg('sitename_pagetitles', 0) == 1) {
            $page_title = JText::sprintf('JPAGETITLE', $this->_mainframe->getCfg('sitename'), $page_title);
        }
        elseif ($this->_mainframe->getCfg('sitename_pagetitles', 0) == 2) {
            $page_title = JText::sprintf('JPAGETITLE', $page_title, $this->_mainframe->getCfg('sitename'));
        }

		$this->document->setTitle($page_title);
		$this->document->setMetaData('keywords', $item->meta_key);
        $this->document->setMetaData('description', $item->meta_desc);
        $this->document->setMetadata('author', $item->meta_author);

        JHtml::_('behavior.modal');

        //$playlist_order	    = $this->_mainframe->getUserStateFromRequest('com_mijovideos.history.playlist_order',         'playlist_order',             'title_za',  'cmd');

        $options = array();
        $options[] = JHtml::_('select.option', 'title_az', JText::_('COM_MIJOVIDEOS_TITLE_AZ'));
        $options[] = JHtml::_('select.option', 'title_za', JText::_('COM_MIJOVIDEOS_TITLE_ZA'));
        $options[] = JHtml::_('select.option', 'access', JText::_('COM_MIJOVIDEOS_ACCESS'));
        $options[] = JHtml::_('select.option', 'created_on', JText::_('COM_MIJOVIDEOS_DATE_CREATED_O_N'));
        $options[] = JHtml::_('select.option', 'created_no', JText::_('COM_MIJOVIDEOS_DATE_CREATED_N_O'));

        $lists = array();
        $lists['playlist_order'] = JHtml::_('select.genericlist', $options, 'playlist_order', ' class="inputbox" style="width: 150px; margin:0;" onchange="ajaxOrder();" ', 'value', 'text');


        $options = array();
        $reasons = $this->get('Reasons');
        $options[] = JHtml::_('select.option', '', JText::_('COM_MIJOVIDEOS_SELECT'));
        foreach ($reasons as $reason) {
            $options[] = JHtml::_('select.option', $reason->id, $reason->title);
        }


        $lists['reasons'] = JHtml::_('select.genericlist', $options, 'mijovideos_reasons', ' class="inputbox" style="width: 150px; margin:0;"" ', 'value', 'text');

        $lists['access'] = JHtml::_('select.genericlist', JHtml::_('access.assetgroups'), 'playlist_access', ' class="mijovideos_access_box" ' , 'value', 'text');

        $this->lists                = $lists;
        $this->item                 = $item;
        $this->item->channel        = MijoVideos::get('channels')->getChannel($item->channel_id);
        $this->item->categories     = $this->get('VideoCategories');
        $this->playlistitems        = $this->getModel('playlists')->getChannelPlaylists();
		$this->view_levels          = $user->getAuthorisedViewLevels();
		$this->checksubscription    = $this->getModel()->checkSubscription($item->channel_id, 'channels');
		$this->checklikesdislikes   = $this->getModel()->checkLikesDislikes($item->id, 'videos');
		$this->reasons              = $reasons;
		$this->Itemid               = $Itemid;
		$this->userId               = $user_id;
		$this->nullDate             = JFactory::getDBO()->getNullDate();
		$this->tmpl                 = JRequest::getCmd('tmpl');
        $this->params               = $this->_mainframe->getParams();
        $this->fields               = MijoVideos::get('fields')->getVideoFields($item->id,"yes");
        $this->levels               = MijoVideos::get('utility')->getAccessLevels();

        if(!empty($item->playlist_id)){
            $this->playlistvideos       = $this->getModel('playlists')->_playlistVideos($item->playlist_id);
        }

        if ($this->getModel()->getProcessing($item->id) > 0) {
            JError::raiseNotice('100', JText::_('COM_MIJOVIDEOS_STILL_PROCESSING'));
        }

		if (MijoVideos::is31()) {
			$this->item->tags = MijoVideos::get('videos')->getTags($this->item->id);
		}

        if (JRequest::getWord('task') == "ajaxOrder") {
            $json = array();
            if ($user_id) {
                $html = "";
                foreach ($this->playlistitems as $item) {
                    $style = $id = "";
                    $playlist_videos = array();
                    foreach ($item->videos as $video) {
                        $playlist_videos[] = $video->video_id;
                    }
                    if (!in_array($this->item->id, $playlist_videos)) {
                       $style = "visibility: hidden";
                    }
                    if ($item->type == 1) {
                       $id = "type1";
                    }
                    $html .= "<li class=\"mijovideos_playlist_item\">\r";
                    $html .= "   <a class=\"playlist_item\" id=\"playlist_item".$item->id."\">\r";
                    $html .= "       <img src=\"components/com_mijovideos/assets/images/tick.png\" style=\"".$style."\"/>\r";
                    $html .= "       <span class=\"mijovideos_playlist_title\" id=\"".$id."\">".$item->title."&nbsp;(<span id=\"total_videos\">".$item->total."</span>)</span>\r";
                    $html .= "       <span class=\"mijovideos_playlist_access\">".$this->levels[$item->access]->title."</span>\r";
                    $html .= "       <span class=\"mijovideos_playlist_created\">".JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4'))."</span>\r";
                    $html .= "    </a>\r";
                    $html .= "</li>\r";
                }
                $json['html'] = $html;
            } else {
                $json['redirect'] = MijoVideos::get('utility')->redirectWithReturn();
            }
            echo json_encode($json);
            exit();
        } else {
            parent::display($tpl);
        }
	}

    public function displayPlayer($tpl = null) {
        $this->item = $this->get('Data');

        parent::display('player');
    }
}