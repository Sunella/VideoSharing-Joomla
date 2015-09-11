<?php
/**
 * @package		MijoVideos
 * @copyright	2009-2014 Mijosoft LLC, mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
# No Permission
defined('_JEXEC') or die('Restricted Access');

class MijovideosFields {

    public function __construct() {}

    public function getField($name) {
		static $cache = array();

        if (!isset($cache[$name])) {
            $cache[$name] = MijoDB::loadObject("SELECT * FROM `#__mijovideos_fields` WHERE `name` = '{$name}'");
        }

        return $cache[$name];
	}

    public function getFieldTitle($name) {
        return $this->getField($name)->title;
	}

    public function getCustomField($name, $default = '', $options = null, $type = 'text', $label = 'mijo_label', $description = 'mijo_description', $name_is_array = false) {
        jimport('joomla.form.form');

        $form = JForm::getInstance('custom_fields', JPATH_ADMINISTRATOR.'/components/com_mijovideos/library/custom_fields.xml', array(), true, 'config');

        if (!empty($options) and !is_array($options)) {
        	$search = array("\r\n","\n\n","\r","\n");
        	
        	for ($i = 0; $i < 4; $i++) {
        		$options = str_replace($search, ";;;", $options);
        	}
        	
        	$options = explode(";;;", $options);
        }
        
        
        $name_suffix = ($name_is_array == true) ? '[]' : '';

        if (($type == 'checkbox') or ($type == 'radio') or ($type == 'list') or ($type == 'multilist')) {
            $b_default = $default;

            if (($type == 'multilist') and strstr($default, '***')) {
                $default = '';
            }

            $xml = '<field name="custom_fields['.$name.']'.$name_suffix.'" type="'.$type.'" default="'.$default.'" label="'.$label.'" description="'.$description.'" class="nopadding" >';

            if ($type == 'radio') {
                $xml = str_replace('class="nopadding"', 'class="btn-group nopadding"', $xml);
            }

            if ($type == 'multilist') {
                $xml = str_replace('type="multilist"', 'type="list" multiple="multiple"', $xml);
            }

			foreach ($options as $option) {
                $xml .= '<option value="'.$option.'">'.$option.'</option>';
            }

            $xml .= '</field>';

            $_f = simplexml_load_string($xml);
        }
        else {
            $_f = simplexml_load_string('<field name="custom_fields['.$name.']'.$name_suffix.'" type="'.$type.'" default="'.$default.'" label="'.$label.'" description="'.$description.'" />');
        }

        @$form->setField($_f);

        if (($type == 'multilist') and strstr($b_default, '***')) {
            $v = explode("***", $b_default);
            $selected = array_combine($v, $v);

            $f_name = 'custom_fields['.$name.']'.$name_suffix.'';

            $data = new stdClass();
            $data->$f_name = $selected;

            $form->bind(array('field' => $data));
        }

        $field = $form->getField('custom_fields['.$name.']'.$name_suffix.'');

        return $field;
    }

    public function getVideoFields($videoId, $clear = NULL) {
		# General Settings
		$app	= JFactory::getApplication();
		$db		= JFactory::getDBO();
		$type	= JRequest::getString("view");
		$user	= JFactory::getUser();
		$userId = $user->get('id');

        if($type == 'video' or $type == 'playlist' or $type == 'channel'){
            $type = $type. 's';
        }

		$type = strtolower($type);
        $sql = "SELECT fields FROM #__mijovideos_{$type} WHERE id = $videoId";
        $db->setQuery($sql);
		$videoFields = $db->loadResult();

		if (empty($videoFields)) {
            return null;
        }

        $videoFields = json_decode($videoFields);

        if (!empty($videoFields)){
	        foreach ($videoFields as $key => $videoField){
	            $sql = "
	                SELECT
	                    f.ordering, f.name, f.title, f.description, f.field_type, f.values, f.default_values, f.rows, f.cols, f.size, f.css_class
	                FROM #__mijovideos_fields f
	                WHERE f.name = '$key'
	                ORDER BY f.ordering
	                ";
	            $db->setQuery($sql);
	            $obj = $db->loadObject();
	            $obj->field_value = $videoField;
	            $rows[] = $obj;
	        }

	        # sorting Array From ordering
	        asort($rows);
        }

        if ($clear == "yes") {
            if(empty($rows)){ return; } else { return $rows;}
        }
        else {
            if(empty($rows)){ return; }

            foreach ($rows as $row){
                $x[] = $this->getCustomField($row->name, $row->field_value, $row->values, $row->field_type, $row->title, $row->description);
            }

            return $x;
        }
    }

    public function createAutoFieldHtml($field_id){
        $db	= JFactory::getDBO();

        $sql = "
        SELECT f.id, f.name, f.title, f.description, f.field_type, f.values, f.default_values, f.rows, f.cols, f.size, f.css_class
        FROM #__mijovideos_fields f
        WHERE id = {$field_id}";

        $db->setQuery($sql);
        $row = $db->loadObject();

        $_html = $this->getCustomField($row->name, $row->default_values, $row->values, $row->field_type, $row->title, $row->description);

        $trID = "{$_html->id}tr";

        $html = '<tr id="'.$trID.'">
                    <td class="key2" style="vertical-align: middle;">
                        <img style="vertical-align: middle;" src="'.JUri::root().'administrator/components/com_mijovideos/assets/images/delete.png" onclick="removeField(\''.$trID.'\');">&nbsp;'.$_html->label.'</td>
                    <td class="value2" style="vertical-align: middle;">
                        '.$_html->input.'
            		</td>
                </tr>';

        return $html;
    }
    public function getAvailableFields(){
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $app = JFactory::getApplication();

        $where = array();
        $where[] = 'published = 1';
        $where[] = 'access IN ('.implode(',', $user->getAuthorisedViewLevels()).')';
        $where[] = 'language IN (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
        $where = (count($where) ? ' WHERE '. implode(' AND ', $where) : '');

        $sql = "SELECT title FROM #__mijovideos_fields" . $where;

        $db->setQuery($sql);
        $result = $db->loadObjectList();

        return $result;
    }
}