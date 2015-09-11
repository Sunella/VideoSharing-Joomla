<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosViewCategory extends MijovideosView {
	
	public function display($tpl = null) {
        $user = JFactory::getUser();
		$nullDate = JFactory::getDBO()->getNullDate();
		$pathway = $this->_mainframe->getPathway();

		$category_id = MijoVideos::getInput()->getInt('category_id', 0);
		$category = Mijovideos::get('utility')->getCategory($category_id);

        if (is_object($category) and !$this->acl->canAccess($category->access)) {
            $this->_mainframe->redirect(JRoute::_('index.php?option=com_mijovideos&view=category'), JText::_('JERROR_ALERTNOAUTHOR'), 'error');
        }

        $Itemid = MijoVideos::get('router')->getItemid(array('view' => 'category', 'category_id' => $category_id), null, true);

        $videos 			= $this->get('Videos');
        $categories 		= $this->get('Categories');

        if (!empty($category_id)) {
            $page_title = JText::_('COM_MIJOVIDEOS_CATEGORY_PAGE_TITLE');
            $page_title = str_replace('[CATEGORY_NAME]', $category->title, $page_title);

            if ($this->_mainframe->getCfg('sitename_pagetitles', 0) == 1) {
                $page_title = JText::sprintf('JPAGETITLE', $this->_mainframe->getCfg('sitename'), $page_title);
            }
            elseif ($this->_mainframe->getCfg('sitename_pagetitles', 0) == 2) {
                $page_title = JText::sprintf('JPAGETITLE', $page_title, $this->_mainframe->getCfg('sitename'));
            }

            $this->document->setTitle($page_title);
            $this->document->setMetadata('description', $category->meta_desc);
            $this->document->setMetadata('keywords', 	$category->meta_key);
            $this->document->setMetadata('author', 		$category->meta_author);
        }
        else {
            $this->document->setTitle(JText::_('COM_MIJOVIDEOS_CATEGORIES_PAGE_TITLE'));
        }

		if ($this->config->get('load_plugins')) {
            $n = count($videos);
			
			for ($i = 0; $i < $n; $i++) {
				$item = &$videos[$i];
				
				$item->introtext = JHtml::_('content.prepare', $item->introtext);
			}
			
			if ($category) {	
				$category->description = JHtml::_('content.prepare', $category->introtext.$category->fulltext);
			}
		}
		
		# BreadCrumbs
        $active_menu = $this->_mainframe->getMenu()->getActive();
        if (!isset($active_menu->query['category_id']) or ($active_menu->query['category_id'] != $category_id)) {
            $cats = Mijovideos::get('utility')->getCategories($category_id);

            if (!empty($cats)) {
                asort($cats);

                foreach ($cats as $cat) {
                    if($cat->id != $category_id) {
                        $Itemid = MijoVideos::get('router')->getItemid(array('view' => 'category', 'category_id' => $cat->id), null, true);

                        $path_url = JRoute::_('index.php?option=com_mijovideos&view=category&category_id='.$cat->id.$Itemid);
                        $pathway->addItem($cat->title, $path_url);
                    }
                }

                $pathway->addItem($category->title);
            }
        }

		$userId = $user->get('id');
		$_SESSION['last_category_id'] = $category_id;

        JHtml::_('behavior.modal');

		$this->userId			= $userId;
		$this->items			= $videos;
		$this->categories		= $categories;									
		$this->pagination		= $this->get('Pagination');
		$this->Itemid			= $Itemid;
		$this->category			= $category;
		$this->nullDate			= $nullDate;
        $this->params       	= $this->_mainframe->getParams();
        $this->viewLevels		= $user->getAuthorisedViewLevels();
        $this->view_levels      = $user->getAuthorisedViewLevels();
		
		parent::display($tpl);
	}
}