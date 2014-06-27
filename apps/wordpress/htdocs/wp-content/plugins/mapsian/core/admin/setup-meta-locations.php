<?php

/* Maps meta field init ***************************************************************/

add_action("admin_init", "locations_meta_init");

function locations_meta_init(){
	add_meta_box("locations_meta_description", __("Description", "mapsian"), "locations_meta_description", "locations", "normal", "low");
	add_meta_box("locations_meta_linkurl", __("Link URL", "mapsian"), "locations_meta_linkurl", "locations", "normal", "low");
	add_meta_box("locations_meta_add_address", __("Address you want to add", "mapsian"), "locations_meta_add_address", "locations", "normal", "low");
	add_meta_box("locations_meta_preview_map", __("Preview google map", "mapsian"), "locations_meta_preview_map", "locations", "normal", "low");
	add_meta_box("locations_meta_add_latlng", __("Add marker position", "mapsian"), "locations_meta_add_latlng", "locations", "normal", "low");
	register_taxonomy_for_object_type('mapsian_group', 'locations'); 
}

function locations_meta_description(){ 
	global $post;
	$location_meta = unserialize(get_post_meta($post->ID, 'location_detail', true));
?>

<div>
	<textarea name="mapsian_location_description" class="mapsian_location_description"><?php echo $location_meta[0];?></textarea>
</div>

<?php

}

function locations_meta_linkurl(){
	global $post;
	$location_meta = unserialize(get_post_meta($post->ID, 'location_detail', true));
?>

<div>
	http:// <input type="text" name="mapsian_location_linkurl" class="mapsian_location_linkurl" value="<?php echo $location_meta[1];?>">
</div>

<?php

}

function locations_meta_add_address(){
	global $post;
	$location_meta = unserialize(get_post_meta($post->ID, 'location_detail', true));
?>
<?php echo _e('It based on Geocoding by google maps. Input full address you want to add.', 'mapsian');?>
<div>
	<ul>
		<li><?php echo _e('Full Address', 'mapsian');?> : <input type="text" id='full_address' name='full_address' value="<?php echo $location_meta[2];?>" onchange="check_geo_maps_address();"> <input type="button" class="button" value="ok" onclick="check_geo_maps_address()"></li>
	</ul>
</div>

<?php 
}

function locations_meta_preview_map(){
	global $post;
	$location_meta = unserialize(get_post_meta($post->ID, 'location_detail', true));
	if($location_meta[3] and $location_meta[4]){
?>
	<script>
	jQuery(document).ready(function(){
		add_location_single_step(<?php echo $location_meta[3];?>,<?php echo $location_meta[4];?>);
	});
	</script>
<?php
	}
?>
<div style="height:400px" class="admin_modify_marker_map">
Google map will be appear after searching address. please do search address first.
</div>
<?php 

}

function locations_meta_add_latlng(){
	global $post;
	$location_meta = unserialize(get_post_meta($post->ID, 'location_detail', true));
?>
<div>
	<table>
		<tr>
			<td><?php echo _e('Latitude', 'mapsian');?> : </td>
			<td><input type="text" class="mapsian_lat" name="mapsian_lat" value="<?php echo $location_meta[3];?>"></td>
		</tr>
		<tr>
			<td><?php echo _e('Longitude', 'mapsian');?> : </td>
			<td><input type="text" class="mapsian_lng" name="mapsian_lng" value="<?php echo $location_meta[4];?>"></td>
		</tr>
	</table>
</div>
<?php

}

?>