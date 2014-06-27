<?php

add_shortcode("map-view","map_view");

function map_view($attr){

$map_id = $attr['id'];

$groups = get_post_meta($map_id,"mapsian_added_groups",true);
$groups = explode(",",$groups);

$mapsize = unserialize(get_post_meta($map_id, 'map_size', true));


if($mapsize[0] == "" or $mapsize[1] == ""){
	$mapsize[0] = "100%";
	$mapsize[1] = "400px";
}

if(strpos($mapsize[0], "%") === false and strpos($mapsize[0], "px") === false){
	$mapsize[0] = $mapsize[0]."px";
}
if(strpos($mapsize[1], "%") === false and strpos($mapsize[1], "px") === false){
	$mapsize[1] = $mapsize[1]."px";
}

$real_height = str_replace("%","",$mapsize[1]);
$real_height = str_replace("px","",$mapsize[1]);
$real_height = str_replace("em","",$mapsize[1]);

for ($i=0; $i < count($groups); $i++) { 
	$group_id = str_replace("group_", "", $groups[$i]);
	$group_names = get_term($group_id,"mapsian_group");

	$group_name[$i] = $group_names->name;
	$group_term_id[$i] = $group_names->term_id;
	$group_slug[$i] = $group_names->slug;
	$group_count[$i] = $group_names->count;
	$total_count += $group_names->count;
} 
		$group_button .= "<li class='each_group_menu' onclick=\"switch_group(".$map_id.",'','".PLUGINSURL."');\">View All</li>";
		$group_button2 .= "<li onclick=\"switch_group(".$map_id.",'','".PLUGINSURL."');\">View All</li>";

	for ($i=0; $i < count($group_name); $i++) { 
		$group_button .= "<li class='each_group_menu' id='each_group_menu_".$group_term_id[$i]."' onclick=\"switch_group(".$map_id.",".$group_term_id[$i].",'".PLUGINSURL."','".$group_name[$i]."');\">".$group_name[$i]."<div class='location_count'><img src='".PLUGINSURL."/images/mapsian_icon_balloon.png' style='width:40px; height:auto'><div class='location_count_number'>".$group_count[$i]."</div></div></li>";

		$group_button2 .= "<li id='each_group_menu_".$group_term_id[$i]."' onclick=\"switch_group(".$map_id.",".$group_term_id[$i].",'".PLUGINSURL."','".$group_name[$i]."');\">".$group_name[$i]."</li>";
	}


?>
<div id="mapsian_outgrid">
	<input type='hidden' class='pannel_status'>
	<div class="mapsian_map_view_area" style="width:<?php echo $mapsize[0];?>; height:<?php echo $mapsize[1];?>">
		<ul>
			<li style="margin:0">
				<div id="mapsian_maps_<?php echo $map_id;?>" class="mapsian_maps" style="width:100%; height:<?php echo $mapsize[1];?>"></div>
<?php 
	if ($mapsize[2] == 1){
?>

				<div id="mapsian_maps_top_bg"></div>
				<div id="mapsian_maps_title"><?php echo get_the_title($map_id);?></div>
				<div id="mapsian_maps_search">
					<ul>
						<li style="float:left"><input type="text" name="search_keyword" onchange="search_location(<?php echo $attr['id'];?>,'<?php echo PLUGINSURL;?>');"></li>
						<li style="float:left"><img src="<?php echo PLUGINSURL;?>/images/mapsian_icon_search.png" style='width:30px; height:auto; position:relative; top:0px; left:5px; cursor:pointer; cursor:hand; margin-right:10px' onclick="search_location(<?php echo $attr['id'];?>"></li>
					</ul>
				</div>
				<div id="mapsian_maps_uder_menu">
				</div>
				<div id="mapsian_maps_each_menu">
					<ul>
						<?php echo $group_button2;?>
					</ul>
				</div>
<?php
}
?>
			</li>
			<li class='group_title_flow'></li>
			<li class="loading_flow_bg"><li>
			<li class="loading_flow_title">
				<ul>
					<li style='text-align:center'><img src="<?php echo PLUGINSURL;?>/images/mapsian_icon_loading2.gif"></li>
					<li style='font-size:16px; margin-top:10px;'><?php echo _e('Now loading', 'mapsian');?></li>
				</ul>
			</li>

			<li class="loading_flow_faild"><?php echo _e('No locations found.', 'mapsian');?></li>
<?php
	if ($mapsize[2] == 2){
?>
			<li class="group_select_pannel">
				<div class="group_select_arrow" onclick="pannel_flow();"><</div>
				<div class="group_menu_area" style="height:90%; position:relative; top:5%;">
				<div class="group_menu">
					<ul style="margin:0; padding:0">
						<li style="padding:0px 0px 10px 0px !important; margin:0;"><input type="text" style="width:60%" name="search_keyword" onchange="search_location(<?php echo $attr['id'];?>,'<?php echo PLUGINSURL;?>');"><img src="<?php echo PLUGINSURL;?>/images/mapsian_icon_search.png" style='width:30px; height:auto; position:relative; top:10px; left:5px; cursor:pointer; cursor:hand' onclick="search_location(<?php echo $attr['id'];?>,'<?php echo PLUGINSURL;?>');"></li>
					</ul>
					<ul>
						<?php echo $group_button;?>
					</ul>
				</div>
				</div>
			</li>
<?php
}
?>
		</ul>
	</div>
</div>
<script>
initialize(0,0,<?php echo $attr['id'];?>,'<?php echo PLUGINSURL;?>',null,null,'<?php echo $map_id;?>');
getJSON(<?php echo $attr['id'];?>,'<?php echo PLUGINSURL;?>');
</script>
<?php
}
?>