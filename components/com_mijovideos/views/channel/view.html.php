<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosViewChannel extends MijovideosView {

	public function display($tpl = null) {
        $pathway = $this->_mainframe->getPathway();

        $channel = $this->get('Item');

        if (is_object($channel) and !$this->acl->canAccess($channel->access)) {
            $this->_mainframe->redirect(JRoute::_('index.php?option=com_mijovideos&view=category'), JText::_('JERROR_ALERTNOAUTHOR'), 'error');
        }

        MijoVideos::get('utility')->hitsCounter('channel');

        $Itemid = MijoVideos::get('router')->getItemid(array('view' => 'channel', 'channel_id' => $channel->id), null, true);

        $channel->description = $channel->introtext.$channel->fulltext;

        $page_title = $channel->title;

        if ($this->_mainframe->getCfg('sitename_pagetitles', 0) == 1) {
            $page_title = JText::sprintf('JPAGETITLE', $this->_mainframe->getCfg('sitename'), $page_title);
        }
        elseif ($this->_mainframe->getCfg('sitename_pagetitles', 0) == 2) {
            $page_title = JText::sprintf('JPAGETITLE', $page_title, $this->_mainframe->getCfg('sitename'));
        }

        $this->document->setTitle($page_title);
        $this->document->setMetadata('description', $channel->meta_desc);
        $this->document->setMetadata('keywords', 	$channel->meta_key);
        $this->document->setMetadata('author', 		$channel->meta_author);

        # BreadCrumbs
        $active_menu = $this->_mainframe->getMenu()->getActive();
        if (!isset($active_menu->query['channel_id']) or ($active_menu->query['channel_id'] != $channel->id)) {
            $pathway->addItem($channel->title);
        }

        $filter_videos 	    = $this->_mainframe->getUserStateFromRequest('com_mijovideos.history.filter_videos',		'filter_videos',	        'uploads',		    'string');
        $filter_order		= $this->_mainframe->getUserStateFromRequest('com_mijovideos.history.filter_order',         'filter_order',             'title',          'cmd');
        $filter_order_Dir	= $this->_mainframe->getUserStateFromRequest('com_mijovideos.history.filter_order_Dir',     'filter_order_Dir',         'DESC',             'word');
        $search				= $this->_mainframe->getUserStateFromRequest('com_mijovideos.history.mijovideos_search',    'mijovideos_search',        '',                 'string');
        $display			= $this->_mainframe->getUserStateFromRequest('com_mijovideos.history.display',              'display',                  ''.$this->config->get('listing_style').'',                 'string');
        $search				= MijoVideos::get('utility')->cleanUrl(JString::strtolower($search));

        $pagenum = MijoVideos::getInput()->getInt('page', 1, 'post');
        $rowsperpage = $this->config->get('videos_per_page');
        $offset = ($pagenum - 1) * $rowsperpage;

        $options = array();
        $options[] = JHtml::_('select.option', 'uploads', JText::_('COM_MIJOVIDEOS_UPLOADS'));
        if($this->config->get('playlists')) {
            $options[] = JHtml::_('select.option', 'playlists', JText::_('COM_MIJOVIDEOS_PLAYLISTS'));
        }

        $lists = array();
        $lists['filter_videos'] = JHtml::_('select.genericlist', $options, 'filter_videos', ' class="inputbox" style="width: 100px;" onchange="submit();" ', 'value', 'text', $filter_videos);

        $options = array();
        $options[] = JHtml::_('select.option', 'title_az', JText::_('COM_MIJOVIDEOS_TITLE_AZ'));
        $options[] = JHtml::_('select.option', 'title_za', JText::_('COM_MIJOVIDEOS_TITLE_ZA'));
        $options[] = JHtml::_('select.option', 'hits', JText::_('COM_MIJOVIDEOS_MOST_POPULAR'));
        $options[] = JHtml::_('select.option', 'created_on', JText::_('COM_MIJOVIDEOS_DATE_CREATED_O_N'));
        $options[] = JHtml::_('select.option', 'created_no', JText::_('COM_MIJOVIDEOS_DATE_CREATED_N_O'));

        $lists['order'] = JHtml::_('select.genericlist', $options, 'filter_order', ' class="inputbox" style="width: 200px;" onchange="submit();" ', 'value', 'text', $filter_order);

        $lists['search'] = $search;
        $lists['order_Dir'] = $filter_order_Dir;

        $this->lists                = $lists;
        $this->fields               = MijoVideos::get('fields')->getVideoFields($channel->id,"yes");
        $this->display              = $filter_videos.'_'.$display;
		$this->item                 = $channel;
        $this->params               = $this->_mainframe->getParams();
        $this->Itemid               = $Itemid;
        $this->checksubscription    = $this->getModel()->checkSubscription($channel->id, 'channels');
        switch ($filter_videos) {
            case 'uploads' :
                $this->getModel()->setState('limitstart', $offset);
                $this->getModel()->setState('limit', $rowsperpage);
                $this->total_items              = $this->get('ChannelVideosTotal');
                $this->items                    = $this->get('ChannelVideos');
                $this->total_pages  = ceil($this->total_items/$rowsperpage);
                break;
            case 'playlists' :
                $this->getModel('playlists')->setState('limitstart', $offset);
                $this->getModel('playlists')->setState('limit', $rowsperpage);
                JRequest::setVar('channel_id', $channel->id, 'post');
                $this->total_items              = $this->getModel('playlists')->getTotal();
                $this->items                    = $this->getModel('playlists')->getChannelPlaylists();
                $this->total_pages  = ceil($this->total_items/$rowsperpage);
                break;
        }

        if ($pagenum > 1) {
            ob_start();
            parent::display($filter_videos.'_'.$display);
            $result = ob_get_contents();
            ob_end_clean();
            echo json_encode($result);
            exit();
        } else {
            parent::display($tpl);
        }
	}	
}