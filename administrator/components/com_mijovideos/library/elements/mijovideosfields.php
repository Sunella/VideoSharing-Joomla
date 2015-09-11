<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

require_once(JPATH_ADMINISTRATOR.'/components/com_mijovideos/library/mijovideos.php');

class JFormFieldMijovideosFields extends JFormField {

	protected $type = 'MijovideosFields';
	
	function getInput() {
        JFactory::getDocument()->addStyleSheet(JUri::root() . 'administrator/components/com_mijovideos/assets/css/config.css');

        $config = MijoVideos::getConfig();

		$rows = MijoDB::loadObjectList("SELECT * FROM #__mijovideos_fields WHERE display_in = 1 AND published = 1 ORDER BY ordering");
		
		$fieldName = $this->name; // jform[individual_fields]
		$fieldName = str_replace ("jform[", "", $fieldName);
		$fieldName = str_replace ("]", "", $fieldName);

        if ($config->get('individual_fields')) {
            $config->set('individual_fields');
        }

        if ($config->get('group_fields')) {
            $config->set('group_fields');
        }
		
		$html = '';

		foreach ($rows as $row) {
			$_name = $row->name;

            if (!isset($config->get('individual_fields')->$_name)) {
                $config->get('individual_fields')->$_name = 0;
            }

            if (!isset($config->get('group_fields')->$_name)) {
                $config->get('group_fields')->$_name = 0;
            }
			
			$labelID 	= "jform_{$row->name}-lbl";
			$labelFor 	= "jform_{$row->name}";
			$labelDesc	= $row->description;
			$labelTitle = $row->title;
			
			$radioName	= "jform[{$fieldName}][{$_name}]";
			$fieldsetID = "jform{$fieldName}_{$_name}";

            if (MijoVideos::is30()) {
                $radioID = "jform_{$fieldName}_{$_name}";

                $html .= '<div class="control-group" style="margin-left: -155px !important;">';
                $html .= '<div class="control-label">';
                $html .= "<label id='$labelID' for='$labelFor' class='hasTip' title='{$labelDesc}'>{$labelTitle}</label>";
                $html .= '</div>';
                $html .= '<div class="controls">';
                $html .= $this->_getRadioList($radioName, $config->get($fieldName)->$_name, "", "", $fieldsetID, $radioID);
                $html .= '</div>';
                $html .= '</div>';
            }
            else {
                $html .= '<li>';
                $html .= "<label id='$labelID' for='$labelFor' class='hasTip' title='{$labelDesc}'>{$labelTitle}</label>";
                $html .= $this->_getRadioList($radioName, $config->get($fieldName)->$_name, "", "", $fieldsetID);
                $html .= '</li>';
            }
		}

		return $html;
	}

    public function getLabel() {
        return '';
    }
	
	public function _getRadioList($name, $selected, $attrs = '', $id = false, $fieldsetID, $radioID = null) {
        if (empty($attrs)) {
            $attrs = 'class="inputbox" size="2"';
        }

    	$arr = array(
    			JHtml::_('select.option', 0, JText::_('JHIDE')),
            	JHtml::_('select.option', 1, JText::_('JSHOW')),
            	JHtml::_('select.option', 2, JText::_('COM_MIJOVIDEOS_REQUIRED'))
            );

        /*if (MijoVideos::is30()) {
            $radio = $this->_getRadioHtml($arr, $name, $attrs, 'value', 'text', (int) $selected, $id);
        }
        else {
            $radio = JHtml::_('select.radiolist', $arr, $name, $attrs, 'value', 'text', (int) $selected, $id);
            $radio = str_replace(array('<div class="controls">', '</div>'), '', $radio);
        }*/

        $html = "<fieldset id='{$fieldsetID}' class='radio btn-group'>";
        $html .= $this->_getRadioHtml($arr, $name, $attrs, 'value', 'text', (int) $selected, $radioID);
        $html .= '</fieldset>';

        return $html;
    }

    private function _getRadioHtml($data, $name, $attribs = null, $optKey = 'value', $optText = 'text', $selected = null, $idtag = false, $translate = false) {
        reset($data);

        if (is_array($attribs)) {
            $attribs = JArrayHelper::toString($attribs);
        }

        $id_text = $idtag ? $idtag : $name;

        $html = '';

        foreach ($data as $obj) {
            $k = $obj->$optKey;

            $t = $translate ? JText::_($obj->$optText) : $obj->$optText;

            $id = (isset($obj->id) ? $obj->id : null);

            $class = 'radio';

            $extra = '';
            $extra .= $id ? ' id="' . $obj->id . '"' : '';

            if ((string) $k == (string) $selected) {
                if (MijoVideos::is30()) {
                    $class = 'btn';

                    if ($k == 0) {
                        $class .= ' btn-danger';
                    }
                    else if ($k == 1) {
                        $class .= ' btn-success';
                    }
                    else {
                        $class .= ' btn-primary';
                    }
                }

                $extra .= ' checked="checked"';
            }

            $html .= "\n\t" . '<input type="radio" name="' . $name . '" id="' . $id_text . $k . '" value="' . $k . '" ' . $extra . ' '. $attribs . '>'."\n\t\t";
            $html .= '<label for="' . $id_text . $k . '" id="' . $id_text . $k . '-lbl" class="'.$class.'">' . $t . '</label>'."\t\t\t\t";
        }

        $html .= "\n";

        return $html;
    }
}