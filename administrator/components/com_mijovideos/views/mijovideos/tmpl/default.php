<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted access');
?>

<script language="javascript" type="text/javascript">
	function upgrade() {	    
	    document.adminForm.view.value = 'upgrade';
		document.adminForm.submit();
	}
</script>

<form name="adminForm" id="adminForm" action="index.php?option=com_mijovideos" method="post">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<th>		
				<?php
                $pid = $this->config->get('pid');
					if(empty($pid)){
						JError::raiseWarning('100', JText::sprintf('COM_MIJOVIDEOS_CPANEL_STATUS_NOTE_PERSONAL_ID', '<a href="index.php?option=com_config&view=component&component=com_mijovideos&path=&tmpl=component">', '</a>'));
					}
				?>	
			</th>
		</tr>
		<tr>
			<th>
				<?php
                $jusersync = $this->config->get('jusersync');
					if(empty($jusersync)){
						JError::raiseWarning('100', JText::sprintf('COM_MIJOVIDEOS_ACCOUNT_SYNC_WARN', '<a href="#" onclick="javascript : submitform(\'jusersync\')">', '</a>'));
					}
				?>
			</th>
		</tr>
		<tr>
            <?php
            $layout = 'user';
            if ($this->acl->canAdmin()) {
                $layout = 'admin';
            }

            echo $this->loadTemplate($layout);
            ?>
		</tr>
	</table>
	
	<input type="hidden" name="option" value="com_mijovideos" />
	<input type="hidden" name="view" value="mijovideos"/>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::_('form.token'); ?>
</form>