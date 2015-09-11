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
	<table class="eb_video_list" width="100%">
		<?php
			$tabs = array('sectiontableentry1' , 'sectiontableentry2');
			$k = 0 ;
			foreach ($rows as  $row) {
				$tab = $tabs[$k];
				$k = 1 - $k ;

                $Itemid = MijoVideos::get('router')->getItemid(array('view' => 'video', 'video_id' => $row->id), null, true);
			?>	
				<tr class="<?php echo $tab; ?>">
					<td class="eb_video">
						<a href="<?php echo JRoute::_('index.php?option=com_mijovideos&view=video&video_id='.$row->id . $Itemid); ?>" class="mijovideos_video_link">
                            <?php echo htmlspecialchars(JHtmlString::truncate($row->title, $config->get('title_truncation'), false, false)); ?>
                        </a>
						<br />
						<span class="created"><?php echo JHtml::_('date', $row->created, $config->get('date_format'), null); ?></span>
						<?php
							if ($showCategory) {
							?>
								<br />		
								<span><?php echo JText::_('COM_MIJOVIDEOS_CATEGORY'); ?>:&nbsp;&nbsp;<?php echo $row->categories; ?></span>
							<?php	
							}

							if ($showChannel and strlen($row->channel_title)) {
							?>
								<br />
                                <?php $Itemid = MijoVideos::get('router')->getItemid(array('view' => 'channel', 'channel_id' => $row->channel_id), null, true); ?>
                                <span><?php echo JText::_('COM_MIJOVIDEOS_SEF_CHANNEL'); ?>:&nbsp;&nbsp;
								    <a href="<?php echo JRoute::_('index.php?option=com_mijovideos&view=channel&channel_id='.$row->channel_id . $Itemid); ?>" title="<?php echo $row->channel_title; ?>" class="channel_link">
                                        <strong>
                                            <?php echo htmlspecialchars(JHtmlString::truncate($row->channel_title, $config->get('title_truncation'), false, false)); ?>
                                        </strong>
                                    </a>
                                </span>
							<?php	 
							}
						?>											
					</td>
				</tr>
			<?php
			}
		?>
	</table>
<?php	
} else {
?>
	<div class="eb_empty"><?php echo JText::_('COM_MIJOVIDEOS_NO_VIDEOS') ?></div>
<?php	
}