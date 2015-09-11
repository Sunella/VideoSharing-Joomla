<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;

$param = null;

//Load greybox lib
$greyBox = JURI::base().'components/com_mijovideos/assets/js/greybox/';
$utility = MijoVideos::get('utility');
?>
<script type="text/javascript">
    var GB_ROOT_DIR = "<?php echo $greyBox ; ?>";
</script>
<script type="text/javascript" src="<?php echo $greyBox; ?>/AJS.js"></script>
<script type="text/javascript" src="<?php echo $greyBox; ?>/AJS_fx.js"></script>
<script type="text/javascript" src="<?php echo $greyBox; ?>/gb_scripts.js"></script>
<link href="<?php echo $greyBox; ?>/gb_styles.css" rel="stylesheet" type="text/css" />

<!-- categories -->
<?php if (($this->params->get('show_page_heading', '0') == '1')) { ?>
    <?php $page_title = $this->params->get('page_title', ''); ?>

    <?php if (!empty($this->category->title)) { ?>
        <h1><?php echo $this->category->title;?></h1>
    <?php } else if (!empty($page_title)) { ?>
        <h1><?php echo $page_title; ?></h1>
    <?php } ?>
<?php } ?>

<?php if (!empty($this->category->id)) { ?>
	<div class="mijovideos_cat">
        <img class="category-item-thumb80" src="<?php echo $utility->getThumbPath($this->category->id, 'categories', $this->category->thumb); ?>" title="<?php echo $this->category->title; ?>" alt="<?php echo $this->category->title; ?>"/>
        <?php if (!empty($this->category->introtext) or !empty($this->category->fulltext)) { ?>
            <div class="mijo_description"><?php echo $this->category->introtext.$this->category->fulltext; ?></div>
        <?php } ?>
	</div>
    <div class="clr"></div>
<?php 
}

if (!empty($this->categories)) {
    ?>

	<div id="mijovideos_cats">
        <?php if (!empty($this->category->id)) { ;?>
	        <h2 class="mijovideos_title"><?php echo JText::_('COM_MIJOVIDEOS_SUB_CATEGORIES');?></h2>
        <?php } ?>

	    <?php			     	
    	foreach ($this->categories as $category) {
	    	if (!$this->config->get('show_empty_cat') and !$category->total_videos) {
	    		continue;
            }

	    	$link = JRoute::_('index.php?option=com_mijovideos&view=category&category_id='.$category->id.$this->Itemid);
    	?>

        <div class="mijovideos_box">
            <div class="mijovideos_box_heading">
                <h3 class="mijovideos_box_h3">
                    <a href="<?php echo $link; ?>" title="<?php echo $category->title; ?>">
                        <img class="category-item-thumb16" src="<?php echo $utility->getThumbPath($category->id, 'categories', $category->thumb); ?>" title="<?php echo $category->title; ?>" alt="<?php echo $category->title; ?>"/>
                        <?php echo $category->title; ?>
                        <?php if ($this->config->get('show_number_videos')) { ?>
                            <small>( <?php echo $category->total_videos ;?> <?php echo $category->total_videos > 1 ? JText::_('COM_MIJOVIDEOS_VIDEOS') :  JText::_( 'COM_MIJOVIDEOS_VIDEO'); ?>)</small>
                        <?php } ?>
                    </a>
                </h3>
            </div>
            <?php if (!empty($category->introtext)) { ?>
            <div class="mijovideos_box_content">
                <?php echo $this->escape(JHtmlString::truncate($category->introtext, $this->config->get('desc_truncation'), false, false)); ?>
            </div>
            <?php } ?>
        </div>
    	<?php } ?>
    </div>
    <div class="clr"></div>
<?php 
}
?>
<!-- categories -->

<!-- category -->
<?php if ($this->category->id != 0){ ?>
<form method="post" name="adminForm" id="adminForm" action="<?php echo JRoute::_('index.php?option=com_mijovideos&view=category&category_id='.$this->category->id.$this->Itemid); ?>">
	<!-- Videos List -->
	<?php if(count($this->items)) { ?>
	    <div id="mijovideos_docs">
	   		<h2 class="mijovideos_title"><?php echo JText::_('COM_MIJOVIDEOS_VIDEOS'); ?></h2>
		    <?php
                $n = count($this->items);
		        for ($i = 0; $i < $n; $i++) {
		        	$item = $this->items[$i];

		        	$url = JRoute::_('index.php?option=com_mijovideos&view=video&video_id='.$item->id.$this->Itemid);

                    $template = JFactory::getApplication()->getTemplate();
                    $ovrr_path = JPATH_ROOT.'/templates/'.$template.'/html/com_mijovideos/video/common.php';
                    
                    if (file_exists($ovrr_path)) {
                        include $ovrr_path;
                    }
                    else {
		        	    include JPATH_MIJOVIDEOS.'/views/video/tmpl/common.php';
                    }
		        }
		    ?>
		</div>
        <?php if ($this->pagination->total > $this->pagination->limit) { ?>
            <div align="center" class="pagination">
                <?php echo $this->pagination->getListFooter(); ?>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="mijovideos_box">
            <div class="mijovideos_box_heading"><h3 class="mijovideos_box_h3"></h3></div>
            <div class="mijovideos_box_content">
                <div id="mijovideos_docs">
                    <i><?php echo JText::_('COM_MIJOVIDEOS_NO_VIDEOS'); ?></i>
                </div>
            </div>
        </div>
	<?php } ?>

	<input type="hidden" name="option" value="com_mijovideos" />	
	<input type="hidden" name="view" value="category" />
	<input type="hidden" name="task" value="" />	
	<input type="hidden" name="Itemid" value="<?php echo $this->Itemid ; ?>" />
    <input type="hidden" name="id" value="0" />

	<script type="text/javascript">
    	function cancelRegistration(registrant_id) {
    		var form = document.adminForm;

    		if (confirm("<?php echo JText::_('COM_MIJOVIDEOS_CANCEL_REGISTRATION_CONFIRM'); ?>")) {
                form.view.value = 'registration';
                form.task.value = 'cancel';
                form.id.value = registrant_id;
                form.submit() ;
    		}	
    	}
	</script>	
</form>
<?php } ?>