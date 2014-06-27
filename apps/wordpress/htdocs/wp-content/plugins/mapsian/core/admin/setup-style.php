<?php

/* Mapsian add admin style css files with bootstrap **********************************************/

function mapsian_admin_style() {
    wp_enqueue_style('mapsian-admin-style', PLUGINSURL.'/css/mapsian-admin.css');

    if(get_option("mapsian_maps_language")){
        $language = "&language=".get_option("mapsian_maps_language");
    }
    else {
        $language = "";
    }

if(get_option("mapsian_maps_api_key")){
    $key = get_option("mapsian_maps_api_key");
        if(get_option("mapsian_maps_language") == "ko"){
            $url = "http://maps.google.co.kr/maps/api/js?key=".$key."&sensor=false".$language;
        }
        else {
            $url = "http://maps.google.com/maps/api/js?key=".$key."&sensor=false".$language;
        }
    $url = str_replace("&#038;", "&", $url);
    wp_enqueue_script('mapsian-admin-maps-display-js', $url);
}
else {
    if(get_option("mapsian_maps_language") == "ko"){
        wp_enqueue_script('mapsian-maps-display-js', 'http://maps.google.co.kr/maps/api/js?sensor=false'.$language);
    }
    else {
        wp_enqueue_script('mapsian-maps-display-js', 'http://maps.google.com/maps/api/js?sensor=false'.$language);
    }
}
    wp_enqueue_script('mapsian-admin-js', PLUGINSURL.'/js/mapsian-admin.js');
}
add_action('admin_enqueue_scripts', 'mapsian_admin_style');


function mapsian_style() {
	wp_enqueue_script('jquery');
    wp_enqueue_script('mapsian-scrollbar-js', PLUGINSURL.'/js/jquery.mCustomScrollbar.concat.min.js');
    wp_enqueue_style('mapsian-scrollbar-css', PLUGINSURL.'/css/jquery.mCustomScrollbar.css');



    if(get_option("mapsian_maps_language")){

        $language = "&language=".get_option("mapsian_maps_language");
        
    }
    else {
        $language = "";
    }

if(get_option("mapsian_maps_api_key")){
    $key = get_option("mapsian_maps_api_key");
        if(get_option("mapsian_maps_language") == "ko"){
            $url = "http://maps.google.co.kr/maps/api/js?key=".$key."&sensor=false".$language;
        }
        else {
            $url = "http://maps.google.com/maps/api/js?key=".$key."&sensor=false".$language;
        }
    $url = str_replace("&#038;", "&", $url);
    wp_enqueue_script('mapsian-maps-display-js', $url, false);
}
else {

    if(get_option("mapsian_maps_language") == "ko"){
        wp_enqueue_script('mapsian-maps-display-js', 'http://maps.google.co.kr/maps/api/js?v=3.14&sensor=false'.$language);
    }
    else {
        wp_enqueue_script('mapsian-maps-display-js', 'http://maps.google.com/maps/api/js?sensor=false'.$language);
    }
    
}
    wp_enqueue_script('mapsian-js', PLUGINSURL.'/js/mapsian.js');
    wp_localize_script('mapsian-js', 'ajax_mapsian', 
                array(
                            'ajax_url' => admin_url('admin-ajax.php'),
                            'security' => wp_create_nonce( 'mapsian-security-nonce' )
                        ));
    wp_enqueue_style('mapsian-style', PLUGINSURL."/css/mapsian.css");

}

add_action('wp_enqueue_scripts', 'mapsian_style');


add_filter('clean_url', 'so_handle_038', 99, 3);
function so_handle_038($url, $original_url, $_context) {
    if (strstr($url, "maps.google.com") !== false) {
        $url = str_replace("&#038;", "&", $url); // or $url = $original_url
    }
    if (strstr($url, "maps.google.co.kr") !== false) {
        $url = str_replace("&#038;", "&", $url); // or $url = $original_url
    }
    return $url;
}

?>