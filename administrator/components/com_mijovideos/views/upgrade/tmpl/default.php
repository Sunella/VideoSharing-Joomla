<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted access');

if (MijoVideos::get('utility')->is30()) {
?>

<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton) {
		var form = document.getElementById('upgradeFromUpload');

		if (form.install_package.value == ""){
			alert("<?php echo JText::_('No file selected', true); ?>");
		}
        else {
			form.submit();
		}
	}
</script>

<fieldset class="adminform">
	<legend><?php echo JText::_('COM_MIJOVIDEOS_UPGRADE_VERSION_INFO'); ?></legend>
	<table class="adminform">
		<tr>
			<th>
				<?php echo JText::_('COM_MIJOVIDEOS_INSTALLED_VERSION'); ?> : <?php echo MijoVideos::get('utility')->getMijovideosVersion();?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php echo JText::_('COM_MIJOVIDEOS_LATEST_VERSION'); ?> : <?php echo MijoVideos::get('utility')->getLatestMijovideosVersion();?>
			</th>
		</tr>
	</table>
</fieldset>
<br/><br/>
<div id="installer-install">
  <ul class="nav nav-tabs">
      <li class="active"><a href="#automatic" data-toggle="tab"><?php echo JText::_('COM_MIJOVIDEOS_UPGRADE_FROM_SERVER'); ?></a></li>
      <li><a href="#manual" data-toggle="tab"><?php echo JText::_('COM_MIJOVIDEOS_UPGRADE_FROM_FILE'); ?></a></li>
  </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="automatic">
            <form enctype="multipart/form-data" action="index.php" method="post" name="upgradeFromServer" id="upgradeFromServer" class="form-horizontal">
                <fieldset class="uploadform">
                    <legend><?php echo JText::_('COM_MIJOVIDEOS_UPGRADE_FROM_SERVER'); ?></legend>
                    <?php
                    $pid = MijoVideos::getConfig()->get('pid');
					
					if (empty($pid)) {
						echo '<b><font color="red">'.JText::_('COM_MIJOVIDEOS_UPGRADE_PERSONAL_ID_3').'</font></b>';
					} else {
				    ?>
                    <div class="form-actions">
                        <input class="btn btn-primary" type="button" value="<?php echo JText::_('COM_MIJOVIDEOS_UPGRADE_FROM_SERVER_BTN'); ?>" onclick="form.submit()" />
                    </div>
                    <?php } ?>
                </fieldset>

                <input type="hidden" name="option" value="com_mijovideos" />
                <input type="hidden" name="view" value="upgrade" />
                <input type="hidden" name="task" value="upgrade" />
                <input type="hidden" name="type" value="server" />
                <?php echo JHtml::_('form.token'); ?>
            </form>
        </div>
        <div class="tab-pane" id="manual">
            <form enctype="multipart/form-data" action="index.php" method="post" name="upgradeFromUpload" id="upgradeFromUpload" class="form-horizontal">
                <fieldset class="uploadform">
                    <legend><?php echo JText::_('COM_MIJOVIDEOS_UPGRADE_FROM_FILE'); ?></legend>
                    <div class="control-group">
                        <label for="install_package" class="control-label"><?php echo JText::_('COM_MIJOVIDEOS_UPGRADE_PACKAGE'); ?></label>
                        <div class="controls">
                            <input class="input_box" id="install_package" name="install_package" type="file" size="57" />
                        </div>
                    </div>
                    <div class="form-actions">
                        <input class="btn btn-primary" type="button" value="<?php echo JText::_('COM_MIJOVIDEOS_UPGRADE_UPLOAD_UPGRADE'); ?>" onclick="Joomla.submitbutton()" />
                    </div>
                </fieldset>

                <input type="hidden" name="option" value="com_mijovideos" />
                <input type="hidden" name="view" value="upgrade" />
                <input type="hidden" name="task" value="upgrade" />
                <input type="hidden" name="type" value="upload" />
                <?php echo JHtml::_('form.token'); ?>
            </form>
        </div>
    </div>
</div>

<?php } else { ?>

<fieldset class="adminform">
	<legend><?php echo JText::_('COM_MIJOVIDEOS_UPGRADE_VERSION_INFO'); ?></legend>
	<table class="adminform">
		<tr>
			<th>
				<?php echo JText::_('COM_MIJOVIDEOS_INSTALLED_VERSION'); ?> : <?php echo MijoVideos::get('utility')->getMijovideosVersion();?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php echo JText::_('COM_MIJOVIDEOS_LATEST_VERSION'); ?> : <?php echo MijoVideos::get('utility')->getLatestMijovideosVersion();?>
			</th>
		</tr>
	</table>
</fieldset>

<table class="noshow">
	<tr>
		<td width="50%">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_MIJOVIDEOS_UPGRADE_FROM_SERVER'); ?></legend>
				<?php
                    $pid = MijoVideos::getConfig()->get('pid');
					
					if (empty($pid)) {
                ?>
                    <table class="adminform">
                        <tr>
                            <th>
                                <br/>
                                <font color="red"><?php echo JText::_('COM_MIJOVIDEOS_UPGRADE_PERSONAL_ID'); ?></font>
                                <br/><br/>
                            </th>
                        </tr>
                    </table>
                <?php
                    } else {
				?>
				<form enctype="multipart/form-data" action="index.php" method="post" name="upgradeFromServer">
					<table class="adminform">
						<tr>
							<th>
								<br/>
								<button><?php echo JText::_('COM_MIJOVIDEOS_UPGRADE_FROM_SERVER_BTN'); ?></button>
								<br/><br/>
							</th>
						</tr>
					</table>

					<input type="hidden" name="option" value="com_mijovideos" />
					<input type="hidden" name="view" value="upgrade" />
					<input type="hidden" name="task" value="upgrade" />
					<input type="hidden" name="type" value="server" />
					<?php echo JHtml::_('form.token'); ?>
				</form>
				<?php } ?>
			</fieldset>
		</td>

		<td width="%50">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_MIJOVIDEOS_UPGRADE_FROM_FILE'); ?></legend>
				<form enctype="multipart/form-data" action="index.php" method="post" name="upgradeFromUpload">
					<table class="adminform">
						<tr>
							<th colspan="2"><?php echo JText::_('COM_MIJOVIDEOS_UPGRADE_PACKAGE'); ?></th>
						</tr>
						<tr>
							<td width="100">
								<label for="install_package"><?php echo JText::_('COM_MIJOVIDEOS_UPGRADE_SELECT_FILE'); ?>:</label>
							</td>
							<td>
								<input class="input_box" id="install_package" name="install_package" type="file" size="40" />
								<input class="button" type="submit" value="<?php echo JText::_('COM_MIJOVIDEOS_UPGRADE_UPLOAD_UPGRADE'); ?>" />
							</td>
						</tr>
					</table>
					<input type="hidden" name="option" value="com_mijovideos" />
					<input type="hidden" name="view" value="upgrade" />
					<input type="hidden" name="task" value="upgrade" />
					<input type="hidden" name="type" value="upload" />
					<?php echo JHtml::_('form.token'); ?>
				</form>
			</fieldset>
		</td>
	</tr>
</table>
<?php } ?>