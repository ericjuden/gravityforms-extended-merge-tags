<?php
/*
Plugin Name: Gravity Forms Extended Merge Tags
Plugin URI: http://ericjuden.com/projects/wordpress/gravityforms-extended-merge-tags/
Description: Enables adding $_COOKIE, $_SERVER, $_SESSION, $_GET, $_POST and $_REQUEST data to a Gravity Form through merge tags
Author: ericjuden
Author URI: http://ericjuden.com
Version: 1.1
*/

class GF_Extended_Merge_Tags {
	function __construct() {
		add_action( 'gform_admin_pre_render' , array( $this, 'add_merge_tags' ) );
		add_filter( 'gform_field_input' , array( $this , 'replace_merge_tags' ) , 10 , 5);
	}
	
	function add_merge_tags( $form ) {
?>
		<script type="text/javascript">
			gform.addFilter("gform_merge_tags", "add_extended_tags");
			function add_extended_tags(mergeTags, elementId, hideAllFields, excludeFieldTypes, isPrepop, option){
				mergeTags['custom'].tags.push({
					tag: '{COOKIE:key}',
					label: 'Cookie Data'
				});
				mergeTags['custom'].tags.push({
					tag: '{GET:key}',
					label: 'GET Data'
				});
				mergeTags['custom'].tags.push({
					tag: '{POST:key}',
					label: 'POST Data'
				});
				mergeTags['custom'].tags.push({
					tag: '{REQUEST:key}',
					label: 'REQUEST Data'
				});
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
	
	function replace_merge_tags( $input , $field , $value , $lead_id , $form_id ) {
		if( !is_admin() ) {	// Keep field the same while editing form. Only modify when actually displaying the form on the front-end.
			if( !is_array( $value ) ) {
				$key = $this->get_search_key( $value );

				$did_process = true;
				if( $key != '' ) {
					switch( $value ) {				
						case ( stristr( $value, '{COOKIE:' ) !== FALSE ):
							if( isset( $_COOKIE[$key] ) ) {
								$value = $_COOKIE[$key];
							} else {
								$value = '';
							}
							break;

						case ( stristr( $value, '{GET:' ) !== FALSE ):
							if( isset( $_GET[$key] ) ) {
								$value = $_GET[$key];
							} else {
								$value = '';
							}
							break;

						case ( stristr( $value, '{POST:' ) !== FALSE ):
							if( isset( $_POST[$key] ) ) {
								$value = $_POST[$key];
							} else {
								$value = '';
							}
							break;

						case ( stristr( $value, '{REQUEST:' ) !== FALSE ):
							if( isset( $_REQUEST[$key] ) ) {
								$value = $_REQUEST[$key];
							} else {
								$value = '';
							}
							break;
							
						case ( stristr( $value, '{SERVER:' ) !== FALSE ):
							if( isset( $_SERVER[$key] ) ) {
								$value = $_SERVER[$key];
							} else {
								$value = '';
							}
							break;
							
						case ( stristr( $value, '{SESSION:' ) !== FALSE ):
							if( isset( $_SESSION[$key] ) ) {
								$value = $_SESSION[$key];
							} else {
								$value = '';
							}
							break;
							
						default:
							$did_process = false;
							break;
					}

					if( $value != '' && $did_process ){
						$input = $this->build_hidden_field( $input , $field , $value , $form_id );
					}
				}
			} else {
				// do nothing with the array
			} 
		}
		
		return $input;
	}
	
	function build_hidden_field( $input , $field , $value , $form_id ) {
		$input = '<input name="input_'. $field['id'] .'" id="input_'. $form_id . '_' . $field['id'] .'" type="hidden" class="gform_hidden" value="'. $value .'" />';
		return $input;
	}
	
	function get_search_key( $text ) {
		$key = '';
		if( !is_array( $text ) ) {
			$key = substr( $text , strpos( $text , ':' ) + 1 );			
		} else {
			$key = false;
		}
		
		if($key === FALSE){
			return '';
		}
		
		// Remove curly brace from end
		$key = str_replace( '}', '' , $key );
		
		return $key;
	}
}
$gf_extended_merge_tags = new GF_Extended_Merge_Tags();
?>