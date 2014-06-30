<?php
/**
    Plugin Name: Advanced WPMU Plugin Manager 
    Plugin URI: http://www.phpconsultant.co/wp-plugins
    Description: Enable Plugins For Some Specific Site in WPMU
    Version: 1.0
    Author: Anand Thakkar
    Author URI: http://www.anandthakkar.me/
    License: GPL
    Copyright: Anand Thakkar
*/

define('AWPM_PLUGIN_FILE','advanced-wpmu-pluginmanager/init.php');

add_action('network_admin_menu','awpm_network_admin_menu',50);

function awpm_network_admin_menu(){
    
        add_menu_page(__('Plugins Manager','apm'),__('Plugins Manager','apm'), 'manage_network', 'awpm_plugin_manager', 'awpm_plugin_manager');
    
}


function awpm_get_sites($args=array()){
    
    global $wpdb;
    
    $qry = "SELECT * FROM {$wpdb->blogs} WHERE site_id = '{$wpdb->siteid}' ";
    
    return $wpdb->get_results($qry);
    
}

function awpm_plugin_manager(){
    
    $sites=  awpm_get_sites();
    
    ?>
    <style type="text/css">
        .col1{
            float:left;
            width:40%; 
        }
        .col2{
            float:left; 
            width:60%; 
        }
        .social img {
            margin:5px 10px; 
        }
        .social a {
            text-decoration:none; 
        }
    </style>
    <script type="text/javascript">
        function load_blog_plugin(blog_id){
            jQuery.ajax({
                    url:ajaxurl,
                    data:"action=awpm_load_site_plugin&blog_id=" + blog_id,
                    beforeSend:function(){
                        jQuery('#site_loading_img').show();
                    },
                    complete:function(){
                        jQuery('#site_loading_img').hide();
                    },
                    success:function(data){
                        jQuery('#ajax_data').html(data);
                    }
                });
        }
        jQuery().ready(function(){
            jQuery('.loading').hide();
            
            jQuery(document).delegate('.activate_btn','click',function(){
                
                jQuery.ajax({
                    url:ajaxurl,
                    data:"action=awpm_activate_plugin&blog_id=" + jQuery('#blog_id').val() + "&plugin=" + jQuery(this).attr('plugin'),
                    beforeSend:jQuery.proxy(function(){
                        jQuery(this).parent().find('.loading').show();
                    },this),
                    complete:jQuery.proxy(function(){
                        jQuery(this).parent().find('.loading').hide();
                    },this),
                    success:function(data){
                        load_blog_plugin(jQuery('#blog_id').val());
                    }
                });
                
            });
            
            jQuery(document).delegate('.deactivate_btn','click',function(){
                
                jQuery.ajax({
                    url:ajaxurl,
                    data:"action=awpm_deactivate_plugin&blog_id=" + jQuery('#blog_id').val() + "&plugin=" + jQuery(this).attr('plugin'),
                    beforeSend:jQuery.proxy(function(){
                        jQuery(this).parent().find('.loading').show();
                    },this),
                    complete:jQuery.proxy(function(){
                        jQuery(this).parent().find('.loading').hide();
                    },this),
                    success:function(data){
                        load_blog_plugin(jQuery('#blog_id').val());
                    }
                });
                
            });
            
            jQuery('#site_list').change(function(){
                if(jQuery(this).val()=='NS')
                    return;
                load_blog_plugin(jQuery(this).val());
            });
            
        })
    </script>
    <div class="wrap">
    <h2>Advanced WPMU Plugin Manager</h2>
    <div>
        <div class="col1">
            Site List
            <select id="site_list">
                <option value="NS"> ----- Select Site ----- </option>
                <?PHP
                    for($i=0;$i<count($sites);$i++) {
                        echo '<option value="' . $sites[$i]->blog_id .'">' . $sites[$i]->domain . ' [' .  $sites[$i]->path . ']'  . '</option>';

                    }
                ?>
            </select>
            <img src="<?PHP echo admin_url() ?>images/wpspin_light.gif" class="loading" id="site_loading_img" />
        </div>
        <div class="col2">
            <?PHP echo __('I am Baroda , Gujarat [India]  Based Developer if you 
                Like this plugin then You can Share Your Views With Me using Any 
                of the Below Media.','awpm');
            ?>
            <br/>
            <div class="social">
                <a href="http://www.facebook.com/anthakkar08" target="_blank" >
                    <img src="<?PHP echo plugins_url('img/facebook.png',__FILE__); ?>" />
                </a>
                <a href="https://twitter.com/anthakkar08" target="_blank" >
                    <img src="<?PHP echo plugins_url('img/twitter.png',__FILE__); ?>" />
                </a>
                <a href="http://anandthakkar.me" target="_blank" >
                    <img src="<?PHP echo plugins_url('img/wordpress.png',__FILE__); ?>" />
                </a>
            </div>
            <div>
            <?PHP
                echo __('You Can Also Support Me Through Donataion would Be happy to have it :)')
            ?>
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                    <input type="hidden" name="cmd" value="_donations">
                    <input type="hidden" name="business" value="anthakkar08@gmail.com">
                    <input type="hidden" name="lc" value="US">
                    <input type="hidden" name="item_name" value="to Support the Development of  Advanced WPMU Plugin Manager">
                    <input type="hidden" name="no_note" value="0">
                    <input type="hidden" name="currency_code" value="USD">
                    <input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
                    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
            </div>

            
        </div>
        <div style="clear:both;"></div>
    </div>
    <div  id="ajax_data"></div>
    </div>
    <?PHP
}

add_action('wp_ajax_awpm_load_site_plugin','awpm_load_site_plugin');

function awpm_load_site_plugin(){
    switch_to_blog($_REQUEST['blog_id']);
    ?>
    <h2><?PHP echo sprintf(__('Plugin Status of %s site','awpm'),  get_bloginfo('site_name')); ?></h2>
    <?PHP
    include 'class/plugin_table.php';
    $plugins_list = new awpm_plugin_list();
    $plugins=get_plugins();
    foreach($plugins as $key=>$plugin){
        $plugin['file']=$key;
        $new_plugins[]=$plugin;
    }
    $table_array['data']=$new_plugins;
    $plugins_list->prepare_items($table_array);
    ?>
    <form>
        <input type="hidden" name="blog_id" id="blog_id" value="<?PHP echo $_REQUEST['blog_id']; ?>" />
    <?PHP
        $plugins_list->display();
    ?>
    </form>
    <div id="exe" style="visibility:hidden;"></div>
    <script type="text/javascript">
        jQuery('.loading').hide();
    </script>
    <?PHP
    die('');
}

add_action('wp_ajax_awpm_activate_plugin','awpm_activate_plugin');

function awpm_activate_plugin(){
    
    $plugin=$_REQUEST['plugin'];
    $blog_id=$_REQUEST['blog_id'];
    
    if(is_super_admin()){
        switch_to_blog($_REQUEST['blog_id']);
        activate_plugin($plugin,'',false);
    }
    
}

add_action('wp_ajax_awpm_deactivate_plugin','awpm_deactivate_plugin');

function awpm_deactivate_plugin(){
    
    $plugin=$_REQUEST['plugin'];
    $blog_id=$_REQUEST['blog_id'];
    
    if(is_super_admin()){
        switch_to_blog($_REQUEST['blog_id']);   
        deactivate_plugins($plugin,TRUE);
    }
}