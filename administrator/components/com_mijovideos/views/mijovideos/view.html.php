<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die( 'Restricted access' );

class MijovideosViewMijovideos extends MijovideosView {

	function display($tpl = null) {
        if ($this->_mainframe->isAdmin()) {
            JToolBarHelper::title(JText::_('COM_MIJOVIDEOS_COMMON_PANEL'),'mijovideos');

            if (JFactory::getUser()->authorise('core.admin', 'com_mijovideos')) {
                JToolBarHelper::preferences('com_mijovideos');
                JToolBarHelper::divider();
            }

            $this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://mijosoft.com/support/docs/mijovideos/user-manual/control-panel?tmpl=component', 650, 500);
        }

        $this->info = $this->get('Info');
		$this->stats = $this->get('Stats');
		
		parent::display($tpl);
	}
	
	function quickIconButton($link, $image, $text, $modal = 0, $x = 500, $y = 450, $new_window = false) {
		// Initialise variables
		$lang = JFactory::getLanguage();
		
		$new_window	= ($new_window) ? ' target="_blank"' : '';
  		?>

		<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
			<div class="icon" <?php echo (!$this->acl->canAdmin()) ? 'style="margin-top:15px;"' : ''; ?>">
				<?php
				if ($modal) {
					JHtml::_('behavior.modal');
					
					if (!strpos($link, 'tmpl=component')) {
						$link .= '&amp;tmpl=component';
					}
				?>
					<a href="<?php echo $link; ?>" style="cursor:pointer" class="modal" rel="{handler: 'iframe', size: {x: <?php echo $x; ?>, y: <?php echo $y; ?>}}"<?php echo $new_window; ?>>
				<?php
				} else {
				?>
					<a href="<?php echo $link; ?>"<?php echo $new_window; ?>>
				<?php
				}
                ?>
                    <img src="<?php echo JUri::root(true); ?>/administrator/components/com_mijovideos/assets/images/<?php echo $image; ?>" alt="<?php echo $text; ?>" />
					<span><?php echo $text; ?></span>
				</a>
			</div>
		</div>
		<?php
	}
}