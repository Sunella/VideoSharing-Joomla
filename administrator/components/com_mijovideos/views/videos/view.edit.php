<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

class MijovideosViewVideos extends MijovideosView {

    public function display($tpl = null) {
        $item = $this->get('EditData');

        if (!$this->acl->canEditOwn($item->user_id)) {
            JFactory::getApplication()->redirect('index.php?option=com_mijovideos', JText::_('JERROR_ALERTNOAUTHOR'));
        }

        $task = JRequest::getCmd('task');
		$text = ($task == 'edit') ? JText::_('COM_MIJOVIDEOS_EDIT') : JText::_('COM_MIJOVIDEOS_NEW');

        if ($this->_mainframe->isAdmin()) {
            JToolBarHelper::title(JText::_('COM_MIJOVIDEOS_CPANEL_VIDEOS').': <small><small>[ ' . $text.' ]</small></small>' , 'mijovideos' );
            JToolBarHelper::apply();
            JToolBarHelper::save();
            JToolBarHelper::save2new();
            JToolBarHelper::cancel();
            JToolBarHelper::divider();
            $this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://mijosoft.com/support/docs/mijovideos/user-manual/videos?tmpl=component', 650, 500);
        }

        $categories = $this->get('Categories');
        $null_date 	= JFactory::getDbo()->getNullDate();
        $params 	= new JRegistry($item->params);

		//Get list of location
		$children = array();
		if ($categories) {
			foreach ($categories as $v) {
				$pt = $v->parent;
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}
		$list = JHtml::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);
		$options = array();
		foreach ($list as $listItem) {
			$options[] = JHtml::_('select.option', $listItem->id, '&nbsp;&nbsp;&nbsp;'. $listItem->treename);
		}

		$itemCategories = array() ;
		if ($item->id) {
			$cats = $this->get('VideoCategories');

            $n = count($cats);
			for ($i = 0; $i < $n; $i++) {
				$itemCategories[] = JHtml::_('select.option', $cats[$i], $cats[$i]);
			}	
		}
		$lists['video_categories'] = JHtml::_('select.genericlist', $options, 'video_categories[]', array(
				'option.text.toHtml'=> false ,
				'option.text' 		=> 'text',
				'option.value' 		=> 'value',
				'list.attr' 		=> 'class="inputbox" size="5" multiple="multiple" aria-invalid="false"',
				'list.select' 		=> $itemCategories
		));

        if (MijoVideos::is31()) {
            // Tags field ajax
            $chosenAjaxSettings = new JRegistry(
                array(
                    'selector'    => '#tags',
                    'type'        => 'GET',
                    'url'         => JUri::root() . 'index.php?option=com_tags&task=tags.searchAjax',
                    'dataType'    => 'json',
                    'jsonTermKey' => 'like'
                )
            );
            JHtml::_('formbehavior.ajaxchosen', $chosenAjaxSettings);

            $item_tags = MijoVideos::get('videos')->getTags($item->id, false, true);

            $lists['tags'] = JHtml::_('select.genericlist', $item_tags, 'tags[]', array(
                    'option.text.toHtml'=> false ,
                    'option.text' 		=> 'text',
                    'option.value' 		=> 'value',
                    'list.attr' 		=> 'class="inputbox" size="5" multiple="multiple"',
                    'list.select' 		=> $item_tags
            ));
        }

        $lists['access']    = JHtml::_('access.level', 'access', $item->access, 'class="inputbox"', false) ;
        $lists['published'] = MijoVideos::get('utility')->getRadioList('published', $item->published);
        $lists['featured'] = MijoVideos::get('utility')->getRadioList('featured', $item->featured);
        $lists['language']  = JHtml::_('select.genericlist', JHtml::_('contentlanguage.existing', true, true), 'language', ' class="inputbox" ', 'value', 'text', $item->language);

        if($this->getModel('processes')->getProcessing($item->id) > 0) {
            JError::raiseNotice('100', JText::_('COM_MIJOVIDEOS_STILL_PROCESSING'));
        }

        JHtml::_('behavior.tooltip');
        JHtml::_('behavior.modal');

        $this->item            = $item;
        $this->lists           = $lists;
        $this->files           = $this->get('Files');
        $this->null_date       = $null_date;
        $this->fields          = MijoVideos::get('fields')->getVideoFields($item->id);
        $this->availableFields = MijoVideos::get('fields')->getAvailableFields();
				
		parent::display($tpl);				
	}
}