jQuery(document).ready(function(){
jQuery(".mapsian-icon-setup").toggle(
	function(){
		jQuery(".dropdown-menu").removeClass("mapsian-open");
		jQuery(this).parent().find(".dropdown-menu").addClass("mapsian-open");
	},
	function(){
		if(jQuery(this).parent().find(".dropdown-menu").hasClass("mapsian-open")){
			jQuery(this).parent().find(".dropdown-menu").removeClass("mapsian-open");
		}
		else {
			jQuery(".dropdown-menu").removeClass("mapsian-open");
			jQuery(this).parent().find(".dropdown-menu").addClass("mapsian-open");
		}
	}
	);

jQuery("body").not(".dropdown-menu").click(function(){
  jQuery(".dropdown-menu").removeClass("mapsian-open");
})
   if(jQuery(".added_group_list_div").html()){
	jQuery(".added_group_list_div").sortable({
		items: '.mapsian-meta-added-group',
		opacity: 0.6,
		cursor: 'move',
		axis:'y',
		update: function(){
			var order = jQuery(this).sortable('serialize') + '&action=added_group_order&post_id='+jQuery('#post_id').val();
			jQuery.post(ajaxurl, order, function(response) {
				//alert(response)
			});
		}
	});
}

  jQuery(".checkbox-all").toggle(
    function(){
      jQuery(".checkbox-post").prop("checked",true);
      jQuery(this).prop("checked",true);
    },
    function(){
      jQuery(".checkbox-post").prop("checked",false);
      jQuery(this).prop("checked",false);
    }

    );
  });


function mapsian_maps_status_change(plugins_url,map_id){

  var data = {
    'action': 'maps_status_change',
    'map_id': map_id
  };

  jQuery.post(ajaxurl, data, function(response) {

          switch (jQuery.trim(response)){

            case 'publish' :
              jQuery("#mapsian_icon_status_"+map_id).attr("src",plugins_url+"/images/mapsian_admin_icon_enable.png");
              location.reload();
            break;

            case 'draft' :
              jQuery("#mapsian_icon_status_"+map_id).attr("src",plugins_url+"/images/mapsian_admin_icon_disable.png");
              location.reload();
            break;

          }
  });

}


function mapsian_add_group_to_map(plugins_url,term_id,post_id){

  var data = {
    'action': 'add_group_to_map',
    'term_id': term_id,
    'post_id': post_id
  };


  jQuery.post(ajaxurl, data, function(response) {
        if(response == 0){
          alert('Already added group.');
        }
        else {
        jQuery(".added_group_list_div").append(response);
        }

  });

}


function remove_added_group_by_maps(plugins_url,term_id,post_id){

  var data = {
    'action': 'remove_added_group_by_maps',
    'term_id': term_id,
    'post_id': post_id
  };


  jQuery.post(ajaxurl, data, function(response) {

    jQuery("#mapsian_meta_added_group_"+term_id).remove();

  });

}


function check_geo_maps_address(){
   var value = jQuery('#full_address').val();
   var url = "http://maps.googleapis.com/maps/api/geocode/json?address="+value+"&sensor=false";
   url = encodeURI(url);

  jQuery.getJSON(url,function(list){ //JSON Load

         var message, key;

        for (key in list) {
            message = list[key];
            break;
        }
        if(list.status == "OK"){
        var lat = message[0].geometry.location.lat; //lat
        var lng = message[0].geometry.location.lng; //lng
        var format_address = message[0].formatted_address;

            jQuery(".mapsian_lat").val(lat);
            jQuery(".mapsian_lng").val(lng);
            jQuery("html, body").animate({scrollTop: jQuery(".admin_modify_marker_map").offset().top})
            add_location_single_step(lat,lng);
        }
        else {
        alert("failed searching address. please check address.")
        }

  });


}

function add_location_single_step(x,y){
add_location_initialize(x,y);
set_modify_marker(x,y,'single');
}


var globalMap;
var arrMarker = new Array();

function admin_maps_initialize(x,y,map_id,plugins_url) {

var myLatlng = new google.maps.LatLng(x, y);

 var myOptions = {
  zoom: 2,
  center: myLatlng, 
  mapTypeControl: true, // 지도,위성,하이브리드 등등 선택 컨트롤 보여줄 것인지
  scaleControl: true, // 지도 축적 보여줄 것인지.
  navigationControl: true, // 눈금자 형태로 스케일 조절하는 컨트롤 활성화 선택.
  maxZoom: 17,
  scrollwheel: false
 }

if(document.getElementById("mapsian_maps")){
 globalMap = new google.maps.Map(document.getElementById("mapsian_maps"), myOptions);
}


var latlngbounds = new google.maps.LatLngBounds( ); 


  var data = {
    'action': 'getjson_data',
    'map_id' : map_id
  };


  jQuery.post(ajaxurl, data, function(response) {

    jQuery.each(response,function(i,element){
        var latlng_pos = new google.maps.LatLng(element.lat,element.lng);
        latlngbounds.extend(latlng_pos);
    });
        globalMap.fitBounds(latlngbounds);

  });


} 


function add_location_initialize(x,y) {

var myLatlng = new google.maps.LatLng(x, y);

 var myOptions = {
  zoom: 2,
  center: myLatlng, 
  mapTypeControl: true, // 지도,위성,하이브리드 등등 선택 컨트롤 보여줄 것인지
  scaleControl: true, // 지도 축적 보여줄 것인지.
  navigationControl: true, // 눈금자 형태로 스케일 조절하는 컨트롤 활성화 선택.
  maxZoom: 17,
  scrollwheel: false
 }

if(!jQuery("#mapsian_maps").html()){
  jQuery(".admin_modify_marker_map").attr("id","mapsian_maps");
}
 globalMap = new google.maps.Map(document.getElementById("mapsian_maps"), myOptions);




var latlngbounds = new google.maps.LatLngBounds( );

        var latlng_pos = new google.maps.LatLng(x,y);
        latlngbounds.extend(latlng_pos);
        globalMap.fitBounds(latlngbounds);

}

function setMarker(x,y,title,desc,url){

 var myOptions = {
  position: new google.maps.LatLng(x, y),
  draggable: false,
  //animation: google.maps.Animation.DROP,
  map: globalMap,
  title: name,
  //icon: "images/squat_marker_crimson-555px.png", // 아이콘 설정할 때
  visible: true
 };

 var marker = new google.maps.Marker(myOptions);
 arrMarker.push(marker); //push earch marker.
 

 if(url != ""){
   link_url = "<tr><td><a href='"+url+"'>"+url+"</a></td></tr>";
}
else {
   link_url = "";
}

 var html = "";
 html += "<div style='padding:5px 10px 5px 10px; max-width:300px'>";
 html += "<table><tr><td>"+title+"</td></tr><tr><td>"+desc+"</td></tr>"+link_url+"</table>";
 html += "</div>";
 
 var infoWin = new google.maps.InfoWindow({content: html});

 google.maps.event.addListener(marker, 'click', function(){
  infoWin.open(globalMap, marker);
 });


}


function set_modify_marker(x,y,kind,title,count){

if(x != 0 || y != 0){
 var latlngbounds = new google.maps.LatLngBounds( );
 var myOptions = {
  position: new google.maps.LatLng(x, y),
  draggable: true,
  //animation: google.maps.Animation.DROP,
  map: globalMap,
  title: title,
  mark_id: count,
  //icon: "images/squat_marker_crimson-555px.png", // 아이콘 설정할 때
  visible: true
 };
   marker = new google.maps.Marker(myOptions);
   arrMarker.push(marker); //push earch marker.
}

    if(kind == 'single'){
    google.maps.event.addListener(marker, 'dragend', function(){
    var latlng = marker.getPosition();
      jQuery(".mapsian_lat").val(latlng.lat());
      jQuery(".mapsian_lng").val(latlng.lng());
    });

    }

var title = jQuery("input[name=post_title]").val();
var desc = jQuery(".mapsian_location_description").val();
var url = jQuery(".mapsian_location_linkurl").val();  
var link_url;
if(url != ""){
   link_url = "<tr><td><a href='"+url+"'>"+url+"</a></td></tr>";
}
else {
   link_url = "";
}


 var html = "";
 html += "<div style='padding:5px 10px 5px 10px; max-width:300px'>";
 html += "<table><tr><td><b>"+title+"</b></td></tr><tr><td>"+desc+"</td></tr>"+link_url+"</table>";
 html += "</div>";
 
 var infoWin = new google.maps.InfoWindow({content: html});

 google.maps.event.addListener(marker, 'click', function(){
  infoWin.open(globalMap, marker);
 });


}


function location_preview(post_id,x,y,title,desc,link,plugins_url){

  var scroll_top = jQuery(window).scrollTop() + 100;
  var total_height = jQuery(document).height();
   jQuery("body").append("<div class='mapsian_modal' style='height:"+total_height+"px'><div class='mapsian_modal_location_preview' style='top:"+scroll_top+"px;'><div id='mapsian_maps' style='width:100%; height:100%'></div><div class='mapsian_modal_close'><img src='"+plugins_url+"/images/mapsian_icon_close3.png'  class='mapsian_modal_close_btn' onclick='mapsian_modal_close();'></div></div><div class='mapsian_modal_background'></div></div>");

      add_location_initialize(x,y);
      setMarker(x,y,title,desc,link);

}

function maps_preview(map_id,plugins_url){

  var scroll_top = jQuery(window).scrollTop() + 100;
  var total_height = jQuery(document).height();

  jQuery("body").append("<div class='mapsian_modal' style='height:"+total_height+"px'><div class='mapsian_modal_location_preview' style='top:"+scroll_top+"px;'><div id='mapsian_maps' style='width:100%; height:100%'></div><div class='mapsian_modal_close'><img src='"+plugins_url+"/images/mapsian_icon_close3.png'  class='mapsian_modal_close_btn' onclick='mapsian_modal_close();'></div></div><div class='mapsian_modal_background'></div></div>");

  admin_maps_initialize(0, 0,map_id,plugins_url);
  removeMarkers();
  getJSON(map_id,plugins_url);

}


function getJSON(map_id,plugins_url){

  var data = {
    'action': 'getjson_data',
    'map_id' : map_id
  };

  jQuery.post(ajaxurl, data, function(response) {

    jQuery.each(response,function(i,element){
        setMarker(element.lat, element.lng, element.title, element.desc, element.url);
    });
  });

}

function removeMarkers() { //delete all markers.
  for (var i = 0; i < arrMarker.length; i++) {
    arrMarker[i].setMap(null);
  }
  arrMarker.length=0;
}


function mapsian_modal_close(){
   jQuery(".mapsian_modal").remove();
}








