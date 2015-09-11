<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

?>
<?php foreach ($this->items as $item) { 
			if ($item->id == JRequest::getInt('cid', null)) { ?>
				<div style="word-wrap: break-word;"><?php echo $item->note; ?></div>
			<?php } ?>
<?php } ?>