<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosViewChannels extends MijovideosView {

	public function display($tpl = null) {
        //$Itemid = '&Itemid='.MijoVideos::getInput()->getInt('Itemid', 0);
        $Itemid = MijoVideos::get('router')->getItemid(array('view' => 'channels'), null, true);

        $filter_order		= $this->_mainframe->getUserStateFromRequest('com_mijovideos.history.filter_order',         'filter_order',             'title',  'cmd');
        $filter_order_Dir	= $this->_mainframe->getUserStateFromRequest('com_mijovideos.history.filter_order_Dir',     'filter_order_Dir',         'DESC',             'word');
        $search				= $this->_mainframe->getUserStateFromRequest('com_mijovideos.history.mijovideos_search',    'mijovideos_search',        '',                 'string');
        $display			= $this->_mainframe->getUserStateFromRequest('com_mijovideos.history.display',              'display',                  ''.$this->config->get('listing_style').'',                 'string');
        $search				= MijoVideos::get('utility')->cleanUrl(JString::strtolower($search));

        $lists = array();
        $lists['search'] = $search;
        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;

        $this->lists                = $lists;
        $this->display              = $display;
		$this->items                = $this->get('Items');
        $this->checksubscription    = $this->getModel()->checkSubscription();
		$this->pagination           = $this->get('Pagination');
        $this->params               = $this->_mainframe->getParams();
        $this->Itemid               = $Itemid;
		
		parent::display($tpl);				
	}	
}