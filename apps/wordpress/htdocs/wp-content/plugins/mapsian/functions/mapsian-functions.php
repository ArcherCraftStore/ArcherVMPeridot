<?php

	function added_group_save_order(){
		$priority = sanitize_text_field( $_POST['mapsian_meta_added_group'] );
		$post_id = sanitize_text_field( $_POST['post_id'] );

		for ($i=0; $i < count($priority); $i++) {
			if($i == 0){
			$result .= "group_".$priority[$i];
			}
			else { 
			$result .= ",group_".$priority[$i];
			}
		}
		update_post_meta($post_id,"mapsian_added_groups",$result);
		die();
	}

	add_action('wp_ajax_added_group_order', 'added_group_save_order');

?>