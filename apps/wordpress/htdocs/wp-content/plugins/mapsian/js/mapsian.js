
jQuery(window).load(function(){
  jQuery(".group_menu_area").mCustomScrollbar();
  //jQuery(".gm-style").css("width",(jQuery(".gm-style").width() - 20)+"px" ); 
  //jQuery(".gm-style").css("overflow","none");
  //jQuery("div:first",".gm-style").css("overflow","none");
  //jQuery("div:first",".gm-style").css("width",jQuery("#mapsian_maps").width()+"px" );
});

var globalMap = [];
var arrMarker = new Array();

function initialize(x,y,map_id,plugins_url,group_id,keyword, indexing) {

var myLatlng = new google.maps.LatLng(x, y);

 var myOptions = {
  zoom: 2,
  center: myLatlng, 
  mapTypeControl: false, // 지도,위성,하이브리드 등등 선택 컨트롤 보여줄 것인지
  scaleControl: false, // 지도 축적 보여줄 것인지.
  navigationControl: true, // 눈금자 형태로 스케일 조절하는 컨트롤 활성화 선택.
  maxZoom: 18,
  scrollwheel: false,
  indexing: indexing
 }

 globalMap[indexing] = new google.maps.Map(document.getElementById("mapsian_maps_"+indexing), myOptions);

if(!group_id){
  group_id = "";
}
if(!keyword){
  keyword = "";
}
var latlngbounds = new google.maps.LatLngBounds(); 

if(map_id){

  var data = {
    'action': 'getjson_data',
    'map_id' : map_id,
    'group_id' : group_id,
    'keyword' : keyword
  };

jQuery(".group_title_flow").show();
jQuery(".group_title_flow").css("opacity","0");
jQuery(".group_title_flow").css("top","20px");

  jQuery.post(ajax_mapsian.ajax_url, data, function(response) {






    if(response == 0){

        jQuery(".group_title_flow").html("Sorry, no result.");  

        jQuery(".group_title_flow").stop().animate({
          'opacity' : '1',
          'top' : '40px'
        },500, function(){

          jQuery(".group_title_flow").stop().animate({
            'opacity' : '0',
            'top' : '60px'
          }, 1000, function(){
            jQuery(".group_title_flow").hide();
          });

        });

    }

    else {
    jQuery.each(response,function(i,element){
        var latlng_pos = new google.maps.LatLng(element.lat,element.lng);
        latlngbounds.extend(latlng_pos);
    });
        globalMap[indexing].fitBounds(latlngbounds);


    if(keyword){

        jQuery(".group_title_flow").html("Search by "+keyword);  

        jQuery(".group_title_flow").stop().animate({
          'opacity' : '1',
          'top' : '40px'
        },500, function(){

          jQuery(".group_title_flow").stop().animate({
            'opacity' : '0',
            'top' : '60px'
          }, 1000, function(){
            jQuery(".group_title_flow").hide();
          });

        });

    }


    }

  });



}
else {

        var latlng_pos = new google.maps.LatLng(x,y);
        latlngbounds.extend(latlng_pos);
        globalMap[indexing].fitBounds(latlngbounds);

}


} 

function setMarker(x,y,title,desc,url,indexing){
  
 var myOptions = {
  position: new google.maps.LatLng(x, y),
  draggable: false,
  //animation: google.maps.Animation.DROP,
  map: globalMap[indexing],
  title: name,
  //icon: "images/squat_marker_crimson-555px.png", // 아이콘 설정할 때
  visible: true
 };

 var marker = new google.maps.Marker(myOptions);
 arrMarker.push(marker); //push earch marker.
 

 if(url != ""){
   link_url = "<tr><td style='word-break:break-all; padding:10px 0px 10px 0px; border:none'><a href='http://"+url+"' target='_blank'>"+url+"</a></td></tr>";
}
else {
   link_url = "";
}


 var html = "";
 html += "<div style='padding:5px 10px 5px 10px; max-width:300px; border:none;'>";
 html += "<table style='border:none !important'><tr><td style='padding:10px 0px 10px 0px; font-size:14px; border:none'><b>"+title+"</b></td></tr><tr><td style='height:auto; border:none'>"+desc+"</td></tr>"+link_url+"</table>";
 html += "</div>";
 
 var infoWin = new google.maps.InfoWindow({content: html});

 google.maps.event.addListener(marker, 'click', function(){
  infoWin.open(globalMap[indexing], marker);
 });


}

function getJSON(map_id,plugins_url,group_id){

jQuery(".loading_flow_bg").show();
jQuery(".loading_flow_faild").hide();
jQuery(".loading_flow_title").show();

if(!group_id){
  group_id = "";
}

  var data = {
    'action': 'getjson_data',
    'map_id' : map_id,
    'group_id' : group_id
  };


  jQuery.post(ajax_mapsian.ajax_url, data, function(response) {

    if(response == 0){

      jQuery(".loading_flow_bg").show();
      jQuery(".loading_flow_title").hide();
      jQuery(".loading_flow_faild").show();

      jQuery(".group_select_arrow").html("<");
      jQuery(".group_menu").animate({'opacity' : '0'},100);
      jQuery(".group_select_pannel").stop().animate({
        'width':'20px',
        'opacity' : '0.6'
      },300, function(){
        jQuery(".group_menu").hide();
      });
      jQuery(".pannel_status").val(0);

    }
    else { 
    jQuery.each(response,function(i,element){
        setMarker(element.lat, element.lng, element.title, element.desc, element.url,map_id); 
    });
      jQuery(".loading_flow_bg").hide();
      jQuery(".loading_flow_title").hide();
    }

  });

}



function removeMarkers() { //delete all markers.
  for (var i = 0; i < arrMarker.length; i++) {
    arrMarker[i].setMap(null);
  }
  arrMarker.length=0;
}

function switch_group(map_id,group_id,plugins_url,group_name){
initialize(0,0,map_id,plugins_url,group_id,null,map_id);
removeMarkers() 
getJSON(map_id,plugins_url,group_id);

jQuery(".group_title_flow").show();
jQuery(".each_group_menu").removeClass("active");
jQuery("#mapsian_maps_each_menu > ul > li").removeClass("active");
jQuery("#each_group_menu_"+group_id).addClass("active");
jQuery(".group_title_flow").css("opacity","0");
jQuery(".group_title_flow").css("top","20px");

if(group_name){
  jQuery(".group_title_flow").html(group_name);
}
else {
  jQuery(".group_title_flow").html("All Locations");  
}
jQuery(".group_title_flow").stop().animate({
  'opacity' : '1',
  'top' : '40px'
},500, function(){

  jQuery(".group_title_flow").stop().animate({
    'opacity' : '0',
    'top' : '60px'
  }, 1000, function(){
    jQuery(".group_title_flow").hide();
  });

});


}

function pannel_flow(keyword){

    if(jQuery(".pannel_status").val() == 1){
      jQuery(".group_select_arrow").html("<");
      jQuery(".group_menu").animate({'opacity' : '0'},100);
      jQuery(".group_select_pannel").stop().animate({
        'width':'20px',
        'opacity' : '0.6'
      },300, function(){
        jQuery(".group_menu").hide();
      });
      jQuery(".pannel_status").val(0);
    }
    else {
      jQuery(".group_select_arrow").html(">");
      jQuery(".group_menu").animate({'opacity' : '1'},100);
      jQuery(".group_select_pannel").stop().animate({
        'width':'200px',
        'opacity' : '0.8'
      },300,function(){
        jQuery(".group_menu").show();
      });
      jQuery(".pannel_status").val(1); 
    }

}

function search_location(map_id,plugins_url){
  var keyword = jQuery("[name=search_keyword]").val();

  if(keyword){
    initialize(0,0,map_id,plugins_url,0,keyword,map_id);
    removeMarkers() 
    getJSON(map_id,plugins_url,0);
  }
}









