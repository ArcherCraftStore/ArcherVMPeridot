<?php

/* Custom post  ********************************************************************/

add_action( 'init', 'create_mapsian_custom_post' );
function create_mapsian_custom_post() {
	register_post_type( 'maps',
		array(
			'labels' => array(
				'name' => __( 'Maps', 'mapsian'),
				'menu_name' => 'Maps',
				'singular_name' => __( 'Maps', 'mapsian'),
				'add_new' => __('Add new map', 'mapsian'),
				'add_new_item' => __( 'Add new map', 'mapsian')
			),
		'menu_icon' => PLUGINSURL.'/images/mapsian_admin_icon.png',
		'public' => true,
		'show_ui' => true,
		'has_archive' => true,
		'supports' => array('title')
		)
	);

	//flush_rewrite_rules();

	register_post_type( 'locations',
		array(
			'labels' => array(
				'name' => __( 'Locations' , 'mapsian' ),
				'singular_name' => __( 'Locations' , 'mapsian' ),
				'add_new' => __('Add new location', 'mapsian' ),
				'add_new_item' => __( 'Add new location' , 'mapsian' )
			),
		'public' => false,
		'show_ui' => true,
		'show_in_menu' => 'edit.php?post_type=maps',
		'has_archive' => true,
		'supports' => array('title')
		)
	);
	//flush_rewrite_rules();

   register_taxonomy('mapsian_group',
   	'locations',
    array(
    	'public' => true,
        'hierarchical' => true,
        'show_tagcloud' => false,
        'label' => 'Groups',
        'update_count_callback' => '_update_post_term_count',
        'query_var' => true,
        'rewrite' => array('slug' => 'mapsian_group'),
        'show_ui' => true
    	)
	);

   register_taxonomy_for_object_type( 'mapsian_group', 'locations' );

}

//add_action( 'pre_get_posts', 'add_mapsian_custom_post_type' );


 function add_mapsian_custom_post_type( $query ) {
if ($query->is_main_query()) 
    $query->set( 'post_type', array( 'locations', 'maps' ) );
return $query;
}  


function custom_menu_order() {
	return array( 'index.php', 'edit.php', 'edit-comments.php' );
}

add_filter( 'custom_menu_order', '__return_true' );
add_filter( 'menu_order', 'custom_menu_order' );

add_filter('manage_edit-maps_columns', 'add_maps_columns'); 	// 컬럼 추가

function add_maps_columns($serial_list_columns) {
    $new_columns['cb'] = '<input type="checkbox" />';   

    $new_columns['title'] = __('Title', 'mapsian');
    $new_columns['shortcode'] = __('Shortcode','mapsian');
    $new_columns['locgroup'] = __('Locations/Groups','mapsian');
    $new_columns['status'] = __('Status','mapsian');
    $new_columns['preview'] = __('Preview','mapsian');
 
    return $new_columns;
}

add_filter('manage_edit-locations_columns', 'add_locations_columns'); 	// 컬럼 추가

function add_locations_columns($serial_list_columns) {
    $new_columns['cb'] = '<input type="checkbox" />';   

    $new_columns['title'] = __('Title', 'mapsian');
    $new_columns['address'] = __('Address','mapsian');
    $new_columns['shortcode'] = __('Shortcode','mapsian');
    $new_columns['groups'] = __('Groups','mapsian');
    $new_columns['status'] = __('Status','mapsian');
    $new_columns['preview'] = __('Preview', 'mapsian');
 
    return $new_columns;
}

add_action('manage_maps_posts_custom_column', 'manage_maps_columns', 10, 2);

function manage_maps_columns($column_name, $id) {
    global $wpdb;

    switch ($column_name) {

    case 'shortcode':
        echo "[map-view id=".$id."]";
        break;

    case 'locgroup' :
		$groups_array = get_post_meta( $id, 'mapsian_added_groups',true );
		$groups = explode(",",$groups_array);

		for ($i=0; $i < count($groups); $i++) { 
			$term_id = str_replace("group_", "", $groups[$i]);
			$term = get_term($term_id, "mapsian_group");
			$term_count += $term->count;
		}

		echo $term_count."/".count($groups);
    break;

    case 'status' :

	if(get_post_status($id) == "publish"){

		echo "<img src='".PLUGINSURL."/images/mapsian_admin_icon_enable.png' class='mapsian-icon-status' onclick=\"mapsian_maps_status_change('".PLUGINSURL."',".$id.");\" id='mapsian_icon_status_".$id."'>";

	}
	else {

		echo "<img src='".PLUGINSURL."/images/mapsian_admin_icon_disable.png' class='mapsian-icon-status' onclick=\"mapsian_maps_status_change('".PLUGINSURL."',".$id.");\" id='mapsian_icon_status_".$id."'>";

	}


    break;

    case 'preview' :
    	echo "<img src='".PLUGINSURL."/images/mapsian_icon_preview2.png' class='mapsian-icon-preview' onclick=\"maps_preview(".$id.",'".PLUGINSURL."');\">";
    break;

    default:
        break;
    } // end switch
}


add_action('manage_locations_posts_custom_column', 'manage_locations_columns', 10, 2);

function manage_locations_columns($column_name, $id) {
    global $wpdb;

    $location_meta = unserialize(get_post_meta($id, 'location_detail', true));
	$group_terms = get_the_terms( $id, 'mapsian_group' );

		if($group_terms && ! is_wp_error($group_terms)){
			
			$group_added = "added";
			$terms_name = array();
			$terms_slug = array();

			foreach( $group_terms as $term ){
				$terms_name[] = $term->name;
				$terms_slug[] = $term->slug;
			}

		}
		else {
			$group_added = "not added";
		}
	for ($i=0; $i < count($terms_name); $i++) {

		if($i == 0){
		$term_name .= "<a href='?mapsian_group=".$terms_slug[$i]."&post_type=locations'>".$terms_name[$i]."</a>";
		}
		else { 
		$term_name .= ","."<a href='?mapsian_group=".$terms_slug[$i]."&post_type=locations'>".$terms_name[$i]."</a>";
		}
	}
    switch ($column_name) {

    case 'address' :
    	echo $location_meta[2];
    break;

    case 'groups' :
		if($term_name){
			echo $term_name;
		}
    break;

    case 'shortcode' :
        echo "[location-view id=".$id."]";
        break;

    case 'status' :
	if(get_post_status($id) == "publish"){

		echo "<img src='".PLUGINSURL."/images/mapsian_admin_icon_enable.png' class='mapsian-icon-status' onclick=\"mapsian_maps_status_change('".PLUGINSURL."',".$id.");\" id='mapsian_icon_status_".$id."'>";

	}
	else {

		echo "<img src='".PLUGINSURL."/images/mapsian_admin_icon_disable.png' class='mapsian-icon-status' onclick=\"mapsian_maps_status_change('".PLUGINSURL."',".$id.");\" id='mapsian_icon_status_".$id."'>";

	}
    break;

    case 'preview' :
    	echo "<img src='".PLUGINSURL."/images/mapsian_icon_preview2.png' class='mapsian-icon-preview' onclick=\"location_preview(".$id.",".$location_meta[3].",".$location_meta[4].",'".get_the_title($id)."','".$location_meta[0]."','".$location_meta[1]."','".PLUGINSURL."');\">";
    break;


    default:
        break;
    } // end switch
}

function remove_mapsian_group_meta() {
	remove_meta_box( 'mapsian_groupdiv', 'maps', 'side' );
}

add_action( 'admin_menu' , 'remove_mapsian_group_meta' );





?>