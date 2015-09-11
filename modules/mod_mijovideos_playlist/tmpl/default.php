<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted access');

if (count($rows)) {
?>
	<ul class="menu">
		<?php
			foreach ($rows as $row) {
                $Itemid = MijoVideos::get('router')->getItemid(array('view' => 'playlist', 'playlist_id' => $row->id), null, true);

	    		$link = JRoute::_('index.php?option=com_mijovideos&view=playlist&playlist_id='.$row->id . $Itemid);?>
				<li>
					<a href="<?php echo $link; ?>">
                        <?php echo htmlspecialchars(JHtmlString::truncate($row->title, $config->get('title_truncation'), false, false)); ?>
                    </a>
				</li>
	  <?php } ?>
	</ul>
<?php
}