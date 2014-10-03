<?php
/*
Plugin Name: Gravity Forms Extended Merge Tags
Plugin URI: http://ericjuden.com/projects/wordpress/gravityforms-extended-merge-tags/
Description: Enables adding $_COOKIE, $_SERVER and $_SESSION data to a Gravity Form through merge tags
Author: ericjuden
Author URI: http://ericjuden.com
Version: 1.0
*/

class GF_Extended_Merge_Tags {
	function __construct(){
		add_action('gform_admin_pre_render', array($this, 'add_merge_tags'));
		add_filter('gform_field_input', array($this, 'replace_merge_tags'), 10, 5);
	}
	
	function add_merge_tags($form){
?>
		<script type="text/javascript">
			gform.addFilter("gform_merge_tags", "add_extended_tags");
			function add_extended_tags(mergeTags, elementId, hideAllFields, excludeFieldTypes, isPrepop, option){
				mergeTags['custom'].tags.push({
					tag: '{COOKIE:key}',
					label: 'Cookie Data'
				});
				mergeTags['custom'].tags.push({
					tag: '{SERVER:key}',
					label: 'Server Data'
				});
				mergeTags['custom'].tags.push({
					tag: '{SESSION:key}',
					label: 'Session Data'
				});
				return mergeTags;
			}
		</script>
<?php

		return $form;
	}
	
	function replace_merge_tags($input, $field, $value, $lead_id, $form_id){
		if(!is_admin()){	// Keep field the same while editing form. Only modify when actually displaying the form on the front-end.
			if(!is_array($value)){
				switch($value){				
					case (stristr($value, '{COOKIE:') !== FALSE):
						$key = $this->get_search_key($value);
						
						if($key != ''){
							if(isset($_COOKIE[$key])){
								$value = $_COOKIE[$key];
								$input = $this->build_hidden_field($input, $field, $value, $form_id);
							} else {
								$value = '';
							}
						}
						break;
						
					case (stristr($value, '{SERVER:') !== FALSE):
						$key = $this->get_search_key($value);
						
						if($key != ''){
							if(isset($_SERVER[$key])){
								$value = $_SERVER[$key];
								$input = $this->build_hidden_field($input, $field, $value, $form_id);
							} else {
								$value = '';
							}
						}
						break;
						
					case (stristr($value, '{SESSION:') !== FALSE):
						$key = $this->get_search_key($value);
						
						if($key != ''){
							if(isset($_SESSION[$key])){
								$value = $_SESSION[$key];
								$input = $this->build_hidden_field($input, $field, $value, $form_id);
							} else {
								$value = '';
							}
							
						}
						break;
						
					default:
						
						break;
				}
			} else {
				// do nothing with the array
			} 
		}
		
		return $input;
	}
	
	function build_hidden_field($input, $field, $value, $form_id){
		$input = '<input name="input_'. $field['id'] .'" id="input_'. $form_id . '_' . $field['id'] .'" type="hidden" class="gform_hidden" value="'. $value .'" />';
		return $input;
	}
	
	function get_search_key($text){
		$key = '';
		if(!is_array($text)){
			$key = substr($text, strpos($text, ':')+1);			
		} else {
			$key = false;
		}
		
		if($key === FALSE){
			return '';
		}
		
		// Remove curly brace from end
		$key = str_replace('}', '', $key);
		
		return $key;
	}
}
$gf_extended_merge_tags = new GF_Extended_Merge_Tags();
?>