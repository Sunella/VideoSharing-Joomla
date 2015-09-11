<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined( '_JEXEC' ) or die ;
if (count($this->items)) {
    $utility = MijoVideos::get('utility'); ?>
    <table class="category table table-striped" style="margin-top: 10px;">
        <thead>
            <tr>
                <th>
                    <?php echo JText::_('COM_MIJOVIDEOS_THUMB'); ?>
                </th>
                <th>
                    <?php echo JHtml::_('grid.sort', JText::_('COM_MIJOVIDEOS_NAME'), 'c.title', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th style="text-align: center">
                    <?php echo JHtml::_('grid.sort', JText::_('COM_MIJOVIDEOS_SUBSCRIPTIONS'), 'cs.subs', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th style="text-align: center">
                    <?php echo JHtml::_('grid.sort', JText::_('COM_MIJOVIDEOS_LIKES'), 'c.likes', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th style="text-align: center">
                    <?php echo JHtml::_('grid.sort', JText::_('COM_MIJOVIDEOS_DISLIKES'), 'c.dislikes', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th style="text-align: center">
                    <?php echo JHtml::_('grid.sort', JText::_('COM_MIJOVIDEOS_HITS'), 'c.hits', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $k = 0;
            $n = count($this->items);
            for ($i = 0; $i < $n; $i++) {
                $item = &$this->items[$i];
                $this->Itemid = MijoVideos::get('router')->getItemid(array('view' => 'channel', 'channel_id' => $item->id), null, true);

                $url = JRoute::_('index.php?option=com_mijovideos&view=channel&channel_id='.$item->id.$this->Itemid);
                ?>
                <tr class="cat-list-row-<?php echo $i % 2; ?>">
                    <td>
                        <div class="videos-list-item">
                            <div class="videos-aspect<?php echo $this->config->get('thumb_aspect'); ?>"></div>
                            <a href="<?php echo $url; ?>">
                                <img class="videos-items-grid-thumb" src="<?php echo $utility->getThumbPath($item->id, 'channels', $item->thumb); ?>" title="<?php echo $item->title; ?>" alt="<?php echo $item->title; ?>"/>
                            </a>
                        </div>
                    </td>
                    <td>
                        <a href="<?php echo $url; ?>" title="<?php echo $item->title; ?>">
                            <?php echo $this->escape(JHtmlString::truncate($item->title, $this->config->get('title_truncation'), false, false)); ?>
                        </a><br>
                        <?php echo $this->escape(JHtmlString::truncate($item->introtext, $this->config->get('desc_truncation'), false, false)); ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo isset($item->subs) ? number_format($item->subs) : '0'; ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $item->likes; ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $item->dislikes; ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo number_format($item->hits); ?>
                    </td>
                </tr>
                <?php
                $k = 1 - $k;
            }
            if (count($this->items) == 0) {
            ?>
                <tr>
                    <td colspan="4" style="text-align: center;">
                        <div class="info"><?php echo JText::_('COM_MIJOVIDEOS_NO_LOCATION_RECORDS');?></div>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>

    </table>
<?php } ?>