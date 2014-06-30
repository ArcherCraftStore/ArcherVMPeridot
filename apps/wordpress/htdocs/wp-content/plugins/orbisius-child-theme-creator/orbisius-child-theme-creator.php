<?php
/*
  Plugin Name: Orbisius Child Theme Creator
  Plugin URI: http://club.orbisius.com/products/wordpress-plugins/orbisius-child-theme-creator/
  Description: This plugin allows you to quickly create child themes from any theme that you have currently installed on your site/blog.
  Version: 1.1.9
  Author: Svetoslav Marinov (Slavi)
  Author URI: http://orbisius.com
 */

/*  Copyright 2012-2050 Svetoslav Marinov (Slavi) <slavi@orbisius.com>

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// Set up plugin
add_action('admin_init', 'orbisius_child_theme_creator_admin_init');
add_action('admin_init', 'orbisius_child_theme_creator_register_settings');
add_action('admin_enqueue_scripts', 'orbisius_child_theme_creator_admin_enqueue_scripts');
add_action('admin_menu', 'orbisius_child_theme_creator_setup_admin');
add_action('network_admin_menu', 'orbisius_child_theme_creator_setup_admin'); // manage_network_themes
add_action('wp_footer', 'orbisius_child_theme_creator_add_plugin_credits', 1000); // be the last in the footer
add_action('admin_notices', 'orbisius_child_theme_creator_admin_notice_message');
add_action('network_admin_notices', 'orbisius_child_theme_creator_admin_notice_message');

add_action('wp_before_admin_bar_render', 'orbisius_child_theme_creator_admin_bar_render', 100);

add_action( 'wp_ajax_orbisius_ctc_theme_editor_ajax', 'orbisius_ctc_theme_editor_ajax');
add_action( 'wp_ajax_nopriv_orbisius_ctc_theme_editor_ajax', 'orbisius_ctc_theme_editor_ajax');


register_activation_hook( __FILE__, 'orbisius_child_theme_creator_on_activate' );

/**
 * Adds admin bar items for easy access to the theme creator and editor
 */
function orbisius_child_theme_creator_admin_bar_render() {
    orbisius_child_theme_creator_add_admin_bar('Orbisius');
    orbisius_child_theme_creator_add_admin_bar('Orbisius Child Theme Creator', orbisius_child_theme_creator_util::get_create_child_pages_link(), 'Orbisius');
    orbisius_child_theme_creator_add_admin_bar('Orbisius Theme Editor', orbisius_child_theme_creator_util::get_theme_editor_link(), 'Orbisius');
}

/**
 * 
 */
function orbisius_child_theme_creator_on_activate() {
    $opts = orbisius_child_theme_creator_get_options();

    // Let's set the activation time so we can hide the notice in the plugins area.
    if (empty($opts['setup_time'])) {
        $opts['setup_time'] = time();
        orbisius_child_theme_creator_set_options($opts);
    }
}

/**
 * Show a notice in the plugins area to let the user know how to work with the plugin.
 * On multisite the message is shown only on the network site.
 */
function orbisius_child_theme_creator_admin_notice_message() {
    global $pagenow;
    $opts = orbisius_child_theme_creator_get_options();
    
    $plugin_data = orbisius_child_theme_creator_get_plugin_data();
    $name = $plugin_data['Name'];

    // show notice only the first 24h
    $show_notice = empty($opts['setup_time']) || ( time() - $opts['setup_time'] < 24 * 3600 );

    if ($show_notice
            && ( stripos($pagenow, 'plugins.php') !== false )
            && ( !is_multisite() || ( is_multisite() && is_network_admin() ) ) ) {
        $just_link = orbisius_child_theme_creator_util::get_create_child_pages_link();
        echo "<div class='updated'><p>$name has been installed. To create a child theme go to
          <a href='$just_link'><strong>Appearance &rarr; $name</strong></a></p></div>";
    }
}

/**
 * @package Orbisius Child Theme Creator
 * @since 1.0
 */
function orbisius_child_theme_creator_admin_init() {

}

/**
 * Add's menu parent or submenu item.
 * @param string $name the label of the menu item
 * @param string $href the link to the item (settings page or ext site)
 * @param string $parent Parent label (if creating a submenu item)
 *
 * @return void
 * @author Slavi Marinov <http://orbisius.com>
 * */
function orbisius_child_theme_creator_add_admin_bar($name, $href = '', $parent = '', $custom_meta = array()) {
    global $wp_admin_bar;

    if (!is_super_admin()
            || !is_admin_bar_showing()
            || !is_object($wp_admin_bar)
            || !function_exists('is_admin_bar_showing')) {
        return;
    }

    // Generate ID based on the current filename and the name supplied.
    $id = str_replace('.php', '', basename(__FILE__)) . '-' . $name;
    $id = preg_replace('#[^\w-]#si', '-', $id);
    $id = strtolower($id);
    $id = trim($id, '-');

    $parent = trim($parent);

    // Generate the ID of the parent.
    if (!empty($parent)) {
        $parent = str_replace('.php', '', basename(__FILE__)) . '-' . $parent;
        $parent = preg_replace('#[^\w-]#si', '-', $parent);
        $parent = strtolower($parent);
        $parent = trim($parent, '-');
    }

    // links from the current host will open in the current window
    $site_url = site_url();

    $meta_default = array();
    $meta_ext = array( 'target' => '_blank' ); // external links open in new tab/window

    $meta = (strpos($href, $site_url) !== false) ? $meta_default : $meta_ext;
    $meta = array_merge($meta, $custom_meta);

    $wp_admin_bar->add_node(array(
        'parent' => $parent,
        'id' => $id,
        'title' => $name,
        'href' => $href,
        'meta' => $meta,
    ));
}

/**
 * Sets the setting variables
 */
function orbisius_child_theme_creator_register_settings() { // whitelist options
    register_setting('orbisius_child_theme_creator_settings', 'orbisius_child_theme_creator_options',
        'orbisius_child_theme_creator_validate_settings');
}

/**
 * This is called by WP after the user hits the submit button.
 * The variables are trimmed first and then passed to the who ever wantsto filter them.
 * @param array the entered data from the settings page.
 * @return array the modified input array
 */
function orbisius_child_theme_creator_validate_settings($input) { // whitelist options
    $input = array_map('trim', $input);

    // let extensions do their thing
    $input_filtered = apply_filters('orbisius_child_theme_creator_ext_filter_settings', $input);

    // did the extension break stuff?
    $input = is_array($input_filtered) ? $input_filtered : $input;

    return $input;
}

/**
 * Retrieves the plugin options. It inserts some defaults.
 * The saving is handled by the settings page. Basically, we submit to WP and it takes
 * care of the saving.
 *
 * @return array
 */
function orbisius_child_theme_creator_get_options() {
    $defaults = array(
        'status' => 1,
        'setup_time' => '',
    );

    $opts = get_option('orbisius_child_theme_creator_options');

    $opts = (array) $opts;
    $opts = array_merge($defaults, $opts);

    return $opts;
}

/**
* Updates options but it merges them unless $override is set to 1
* that way we could just update one variable of the settings.
*/
function orbisius_child_theme_creator_set_options($opts = array(), $override = 0) {
    if (!$override) {
        $old_opts = orbisius_child_theme_creator_get_options();
        $opts = array_merge($old_opts, $opts);
    }

    update_option('orbisius_child_theme_creator_options', $opts);

    return $opts;
}

/**
 * @package Orbisius Child Theme Creator
 * @since 1.0
 *
 * Loads plugin's JS/CSS files only on child theme creator pages in admin area.
 * Also searches tags
 */
function orbisius_child_theme_creator_admin_enqueue_scripts($current_page = '') {
    if (strpos($current_page, 'orbisius_child_theme_creator') === false) {
        return ;
    }
    
    $suffix = empty($_SERVER['DEV_ENV']) ? '.min' : '';

    wp_register_style('orbisius_child_theme_creator', plugins_url("/assets/main{$suffix}.css", __FILE__), false,
            filemtime( plugin_dir_path( __FILE__ ) . "/assets/main{$suffix}.css" ) );
    wp_enqueue_style('orbisius_child_theme_creator');

    wp_enqueue_script( 'jquery' );
    wp_register_script( 'orbisius_child_theme_creator', plugins_url("/assets/main{$suffix}.js", __FILE__), array('jquery', ),
            filemtime( plugin_dir_path( __FILE__ ) . "/assets/main{$suffix}.js" ), true);
    wp_enqueue_script( 'orbisius_child_theme_creator' );
}

/**
 * Set up administration
 *
 * @package Orbisius Child Theme Creator
 * @since 0.1
 */
function orbisius_child_theme_creator_setup_admin() {
    add_options_page('Orbisius Child Theme Creator', 'Orbisius Child Theme Creator', 'manage_options', 
            'orbisius_child_theme_creator_settings_page', 'orbisius_child_theme_creator_settings_page');

    add_theme_page('Orbisius Child Theme Creator', 'Orbisius Child Theme Creator', 'manage_options',
            'orbisius_child_theme_creator_themes_action', 'orbisius_child_theme_creator_tools_action');

    /*add_submenu_page('tools.php', 'Orbisius Child Theme Creator', 'Orbisius Child Theme Creator', 'manage_options',
            'orbisius_child_theme_creator_tools_action', 'orbisius_child_theme_creator_tools_action');*/

    // when plugins are show add a settings link near my plugin for a quick access to the settings page.
    add_filter('plugin_action_links', 'orbisius_child_theme_creator_add_plugin_settings_link', 10, 2);

    // Theme Editor
    add_theme_page( 'Orbisius Theme Editor', 'Orbisius Theme Editor', 'manage_options',
            'orbisius_child_theme_creator_theme_editor_action', 'orbisius_ctc_theme_editor' );
    add_filter('theme_action_links', 'orbisius_child_theme_creator_add_edit_theme_link', 50, 2);
}

// Add the ? settings link in Plugins page very good
function orbisius_child_theme_creator_add_plugin_settings_link($links, $file) {
    if ($file == plugin_basename(__FILE__)) {
        $link = orbisius_child_theme_creator_util::get_settings_link();
        $link_html = "<a href='$link'>Settings</a>";
        array_unshift($links, $link_html);

        $link = orbisius_child_theme_creator_util::get_theme_editor_link();
        $link_html = "<a href='$link'>Edit Themes</a>";
        array_unshift($links, $link_html);

        $link = orbisius_child_theme_creator_util::get_create_child_pages_link();
        $link_html = "<a href='$link'>Create Child Theme</a>";
        array_unshift($links, $link_html);
    }

    return $links;
}

/**
 * This adds an edit button in Apperance under each theme.
 * @param array $actions
 * @param WP_Theme/string Obj $theme
 * @return array
 */
function orbisius_child_theme_creator_add_edit_theme_link($actions, $theme) {
    $link = orbisius_child_theme_creator_util::get_theme_editor_link( array( 'theme_1' => is_scalar($theme) ? $theme : $theme->get_template()) );
    $link_html = "<a href='$link' title='Opens this theme in Orbisius Theme Editor which features double textx editor.'>Orbisius: Edit</a>";

    $actions['orb_ctc_editor'] = $link_html;

    return $actions;
}

// Generates Options for the plugin
function orbisius_child_theme_creator_settings_page() {
    ?>

    <div class="wrap orbisius_child_theme_creator_container">

        <div id="icon-options-general" class="icon32"></div>
        <h2>Orbisius Child Theme Creator</h2>

        <div class="updated"><p>
                Some untested themes and plugin may break your site. We have launched a <strong>free</strong> service
                (<a href="http://qsandbox.com/?utm_source=orbisius-child-theme-creator&utm_medium=settings_screen&utm_campaign=product"
                    target="_blank" title="[new window]">http://qsandbox.com</a>)
                that allows you to setup a test/sandbox
                WordPress site in seconds. No technical knowledge is required.
                <br/>Join today and test themes and plugins before you actually put them on your live site. For more info go to:
                <a href="http://qsandbox.com/?utm_source=orbisius-child-theme-creator&utm_medium=settings_screen&utm_campaign=product"
                   target="_blank" title="[new window]">http://qsandbox.com</a>
        </p></div>

        <div class="updated0"><p>
                This plugin doesn't currently have any configuration options. To use it go to <strong>Appearance &rarr; Orbisius Child Theme Creator</strong>
        </p></div>

        <div id="poststuff">

            <div id="post-body" class="metabox-holder columns-2">

                <!-- main content -->
                <div id="post-body-content">

                    <div class="meta-box-sortables ui-sortable">


                        <div class="postbox">

                            <h3><span>Usage / Help</span></h3>
                            <div class="inside">

                                <strong>Process</strong><br/>
                                <ul>
                                    <li>Download a Theme that you like</li>
                                    <li>Create a child theme based on it</li>
                                    <li>Activate the child theme</li>
                                    <li>Customize the child theme</li>
                                </ul>

                                <iframe width="560" height="315" src="http://www.youtube.com/embed/BZUVq6ZTv-o" frameborder="0" allowfullscreen></iframe>

                            </div> <!-- .inside -->

                        </div> <!-- .postbox -->

<div class="postbox">
                            <?php
                                $plugin_data = orbisius_child_theme_creator_get_plugin_data();

                                $app_link = urlencode($plugin_data['PluginURI']);
                                $app_title = urlencode($plugin_data['Name']);
                                $app_descr = urlencode($plugin_data['Description']);
                                ?>
                                <h3>Share</h3>
                                <p>
                                    <!-- AddThis Button BEGIN -->
                                <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
                                    <a class="addthis_button_facebook" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_twitter" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_google_plusone" g:plusone:count="false" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_linkedin" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_email" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_myspace" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_google" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_digg" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_delicious" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_stumbleupon" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_tumblr" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_favorites" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_compact"></a>
                                </div>
                                <!-- The JS code is in the footer -->

                                <script type="text/javascript">
                                    var addthis_config = {"data_track_clickback": true};
                                    var addthis_share = {
                                        templates: {twitter: 'Check out {{title}} #WordPress #plugin at {{lurl}} (via @orbisius)'}
                                    }
                                </script>
                                <!-- AddThis Button START part2 -->
                                <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=lordspace"></script>
                                <!-- AddThis Button END part2 -->
                        </div> <!-- .postbox -->
                        
                    </div> <!-- .meta-box-sortables .ui-sortable -->

                </div> <!-- post-body-content -->

                <!-- sidebar -->
                <div id="postbox-container-1" class="postbox-container">

                    <div class="meta-box-sortables">

                        <div class="postbox">
                            <h3><span>Hire Us</span></h3>
                            <div class="inside">
                                Hire us to create a plugin/web/mobile app for your business.
                                <br/><a href="http://orbisius.com/page/free-quote/?utm_source=orbisius-child-theme-creator&utm_medium=plugin-settings&utm_campaign=product"
                                   title="If you want a custom web/mobile app/plugin developed contact us. This opens in a new window/tab"
                                    class="button-primary" target="_blank">Get a Free Quote</a>
                            </div> <!-- .inside -->
                        </div> <!-- .postbox -->

                        <div class="postbox">
                            <h3><span>Newsletter</span></h3>
                            <div class="inside">
                                <!-- Begin MailChimp Signup Form -->
                                <div id="mc_embed_signup">
                                    <?php
                                        $current_user = wp_get_current_user();
                                        $email = empty($current_user->user_email) ? '' : $current_user->user_email;
                                    ?>

                                    <form action="http://WebWeb.us2.list-manage.com/subscribe/post?u=005070a78d0e52a7b567e96df&amp;id=1b83cd2093" method="post"
                                          id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
                                        <input type="hidden" value="settings" name="SRC2" />
                                        <input type="hidden" value="orbisius-child-theme-creator" name="SRC" />

                                        <span>Get notified about cool plugins we release</span>
                                        <!--<div class="indicates-required"><span class="app_asterisk">*</span> indicates required
                                        </div>-->
                                        <div class="mc-field-group">
                                            <label for="mce-EMAIL">Email <span class="app_asterisk">*</span></label>
                                            <input type="email" value="<?php echo esc_attr($email); ?>" name="EMAIL" class="required email" id="mce-EMAIL">
                                        </div>
                                        <div id="mce-responses" class="clear">
                                            <div class="response" id="mce-error-response" style="display:none"></div>
                                            <div class="response" id="mce-success-response" style="display:none"></div>
                                        </div>	<div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button-primary"></div>
                                    </form>
                                </div>
                                <!--End mc_embed_signup-->
                            </div> <!-- .inside -->
                        </div> <!-- .postbox -->

                        <?php
                                        $plugin_data = get_plugin_data(__FILE__);
                                        $product_name = trim($plugin_data['PluginName']);
                                        $product_page = trim($plugin_data['PluginURI']);
                                        $product_descr = trim($plugin_data['Description']);
                                        $product_descr_short = substr($product_descr, 0, 50) . '...';

                                        $base_name_slug = basename(__FILE__);
                                        $base_name_slug = str_replace('.php', '', $base_name_slug);
                                        $product_page .= (strpos($product_page, '?') === false) ? '?' : '&';
                                        $product_page .= "utm_source=$base_name_slug&utm_medium=plugin-settings&utm_campaign=product";

                                        $product_page_tweet_link = $product_page;
                                        $product_page_tweet_link = str_replace('plugin-settings', 'tweet', $product_page_tweet_link);
                                    ?>

                        <div class="postbox">
                            <div class="inside">
                                <!-- Twitter: code -->
                                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="http://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                                <!-- /Twitter: code -->

                                <!-- Twitter: Orbisius_Follow:js -->
                                    <a href="https://twitter.com/orbisius" class="twitter-follow-button"
                                       data-align="right" data-show-count="false">Follow @orbisius</a>
                                <!-- /Twitter: Orbisius_Follow:js -->

                                &nbsp;

                                <!-- Twitter: Tweet:js -->
                                <a href="https://twitter.com/share" class="twitter-share-button"
                                   data-lang="en" data-text="Checkout Like Gate Pro #WordPress #plugin.Increase your site & fb page's likes"
                                   data-count="none" data-via="orbisius" data-related="orbisius"
                                   data-url="<?php echo $product_page_tweet_link;?>">Tweet</a>
                                <!-- /Twitter: Tweet:js -->


                                <br/>
                                 <a href="<?php echo $product_page; ?>" target="_blank" title="[new window]">Product Page</a>
                                    |
                                <span>Support: <a href="http://club.orbisius.com/forums/forum/community-support-forum/wordpress-plugins/orbisius-child-theme-creator/?utm_source=orbisius-child-theme-creator&utm_medium=plugin-settings&utm_campaign=product"
                                    target="_blank" title="[new window]">Forums</a>

                                    <!--|
                                     <a href="http://docs.google.com/viewer?url=https%3A%2F%2Fdl.dropboxusercontent.com%2Fs%2Fwz83vm9841lz3o9%2FOrbisius_LikeGate_Documentation.pdf" target="_blank">Documentation</a>
                                    -->
                                </span>
                            </div>

                            <h3><span>Troubleshooting</span></h3>
                            <div class="inside">
                                If your site becomes broken because of a child theme check:
                                <a href="http://club.orbisius.com/products/wordpress-plugins/orbisius-theme-fixer/?utm_source=orbisius-child-theme-creator&utm_medium=settings_troubleshooting&utm_campaign=product"
                                target="_blank" title="[new window]">Orbisius Theme Fixer</a>
                            </div>
                        </div> <!-- .postbox -->


                        <div class="postbox"> <!-- quick-contact -->
                            <?php
                            $current_user = wp_get_current_user();
                            $email = empty($current_user->user_email) ? '' : $current_user->user_email;
                            $quick_form_action = is_ssl()
                                    ? 'https://ssl.orbisius.com/apps/quick-contact/'
                                    : 'http://apps.orbisius.com/quick-contact/';

                            if (!empty($_SERVER['DEV_ENV'])) {
                                $quick_form_action = 'http://localhost/projects/quick-contact/';
                            }
                            ?>
                            <script>
                                var like_gate_pro_quick_contact = {
                                    validate_form : function () {
                                        try {
                                            var msg = jQuery('#like_gate_pro_msg').val().trim();
                                            var email = jQuery('#like_gate_pro_email').val().trim();

                                            email = email.replace(/\s+/, '');
                                            email = email.replace(/\.+/, '.');
                                            email = email.replace(/\@+/, '@');

                                            if ( msg == '' ) {
                                                alert('Enter your message.');
                                                jQuery('#like_gate_pro_msg').focus().val(msg).css('border', '1px solid red');
                                                return false;
                                            } else {
                                                // all is good clear borders
                                                jQuery('#like_gate_pro_msg').css('border', '');
                                            }

                                            if ( email == '' || email.indexOf('@') <= 2 || email.indexOf('.') == -1) {
                                                alert('Enter your email and make sure it is valid.');
                                                jQuery('#like_gate_pro_email').focus().val(email).css('border', '1px solid red');
                                                return false;
                                            } else {
                                                // all is good clear borders
                                                jQuery('#like_gate_pro_email').css('border', '');
                                            }

                                            return true;
                                        } catch(e) {};
                                    }
                                };
                            </script>
                            <h3><span>Quick Question or Suggestion</span></h3>
                            <div class="inside">
                                <div>
                                    <form method="post" action="<?php echo $quick_form_action; ?>" target="_blank">
                                        <?php
                                            global $wp_version;
											$plugin_data = get_plugin_data(__FILE__);

                                            $hidden_data = array(
                                                'site_url' => site_url(),
                                                'wp_ver' => $wp_version,
                                                'first_name' => $current_user->first_name,
                                                'last_name' => $current_user->last_name,
                                                'product_name' => $plugin_data['Name'],
                                                'product_ver' => $plugin_data['Version'],
                                                'woocommerce_ver' => defined('WOOCOMMERCE_VERSION') ? WOOCOMMERCE_VERSION : 'n/a',
                                            );
                                            $hid_data = http_build_query($hidden_data);
                                            echo "<input type='hidden' name='data[sys_info]' value='$hid_data' />\n";
                                        ?>
                                        <textarea class="widefat" id='like_gate_pro_msg' name='data[msg]' required="required"></textarea>
                                        <br/>Your Email: <input type="text" class=""
                                               id="like_gate_pro_email" name='data[sender_email]' placeholder="Email" required="required"
                                               value="<?php echo esc_attr($email); ?>"
                                               />
                                        <br/><input type="submit" class="button-primary" value="<?php _e('Send Feedback') ?>"
                                                    onclick="return like_gate_pro_quick_contact.validate_form();" />
                                        <br/>
                                        What data will be sent
                                        <a href='javascript:void(0);'
                                            onclick='jQuery(".like_gate_pro_data_to_be_sent").toggle();'>(show/hide)</a>
                                        <div class="hide app_hide like_gate_pro_data_to_be_sent">
                                            <textarea class="widefat" rows="4" readonly="readonly" disabled="disabled"><?php
                                            foreach ($hidden_data as $key => $val) {
                                                if (is_array($val)) {
                                                    $val = var_export($val, 1);
                                                }

                                                echo "$key: $val\n";
                                            }
                                            ?></textarea>
                                        </div>
                                    </form>
                                </div>
                            </div> <!-- .inside -->
                         </div> <!-- .postbox --> <!-- /quick-contact -->

                    </div> <!-- .meta-box-sortables -->

                </div> <!-- #postbox-container-1 .postbox-container -->

            </div> <!-- #post-body .metabox-holder .columns-2 -->

            <br class="clear">
        </div> <!-- #poststuff -->

    </div> <!-- .wrap -->

    <!--<h2>Support & Feature Requests</h2>
    <div class="updated"><p>
            ** NOTE: ** Support is handled on our site: <a href="http://club.orbisius.com/forums/forum/community-support-forum/wordpress-plugins/orbisius-child-theme-creator/?utm_source=orbisius-child-theme-editor&utm_medium=action_screen&utm_campaign=product" target="_blank" title="[new window]">http://club.orbisius.com/support/</a>.
            Please do NOT use the WordPress forums or other places to seek support.
    </p></div>-->

    <?php orbisius_child_theme_creator_generate_ext_content(); ?>
    <?php
}

/**
 * Returns some plugin data such name and URL. This info is inserted as HTML
 * comment surrounding the embed code.
 * @return array
 */
function orbisius_child_theme_creator_get_plugin_data() {
    // pull only these vars
    $default_headers = array(
        'Name' => 'Plugin Name',
        'PluginURI' => 'Plugin URI',
        'Description' => 'Description',
    );

    $plugin_data = get_file_data(__FILE__, $default_headers, 'plugin');

    $url = $plugin_data['PluginURI'];
    $name = $plugin_data['Name'];

    $data['name'] = $name;
    $data['url'] = $url;

    $data = array_merge($data, $plugin_data);

    return $data;
}

/**
 * Outputs or returns the HTML content for IFRAME promo content.
 */
function orbisius_child_theme_creator_generate_ext_content($echo = 1) {
    $plugin_slug = basename(__FILE__);
    $plugin_slug = str_replace('.php', '', $plugin_slug);
    $plugin_slug = strtolower($plugin_slug); // jic

    $domain = !empty($_SERVER['DEV_ENV']) ? 'http://orbclub.com.clients.com' : 'http://club.orbisius.com';

    $url = $domain . '/wpu/content/wp/' . $plugin_slug . '/';

    $buff = <<<BUFF_EOF
    <iframe style="width:100%;min-height:300px;height: auto;" width="100%" height="480"
            src="$url" frameborder="0" allowfullscreen></iframe>

BUFF_EOF;

    if ($echo) {
        echo $buff;
    } else {
        return $buff;
    }
}

/**
 * Upload page.
 * Ask the user to upload a file
 * Preview
 * Process
 *
 * @package Permalinks to Category/Permalinks
 * @since 1.0
 */
function orbisius_child_theme_creator_tools_action() {
    // ACL checks *borrowed* from wp-admin/theme-install.php
    if ( ! current_user_can('install_themes') ) {
    	wp_die( __( 'You do not have sufficient permissions to install themes on this site.' ) );
    }

    $multi_site = is_multisite();

    if ( $multi_site && ! is_network_admin() ) {
        $next_url = orbisius_child_theme_creator_util::get_create_child_pages_link();

        if (headers_sent()) {
            $success = "In order to create a child theme in a multisite WordPress environment you must do it from Network Admin &gt; Apperance"
                    . "<br/><a href='$next_url' class='button button-primary'>Continue</a>";
            wp_die($success);
        } else {
            wp_redirect($next_url);
        }

        exit();
    }
    
    $msg = '';
    $errors = $success = array();
    $parent_theme_base_dirname = empty($_REQUEST['parent_theme_base_dirname']) ? '' : wp_kses($_REQUEST['parent_theme_base_dirname'], array());
    $orbisius_child_theme_creator_nonce = empty($_REQUEST['orbisius_child_theme_creator_nonce']) ? '' : $_REQUEST['orbisius_child_theme_creator_nonce'];
    $child_custom_info = empty($_REQUEST['child_custom_info']) ? array() : $_REQUEST['child_custom_info'];

    $parent_theme_base_dirname = trim($parent_theme_base_dirname);
    $parent_theme_base_dirname = preg_replace('#[^\w-]#si', '-', $parent_theme_base_dirname);
    $parent_theme_base_dirname = preg_replace('#[_-]+#si', '-', $parent_theme_base_dirname);
    $parent_theme_base_dirname = trim($parent_theme_base_dirname, '-');

    if (!empty($_POST) || !empty($parent_theme_base_dirname)) {
        if (!wp_verify_nonce($orbisius_child_theme_creator_nonce, basename(__FILE__) . '-action')) {
            $errors[] = "Invalid action";
        } elseif (empty($parent_theme_base_dirname) || !preg_match('#^[\w-]+$#si', $parent_theme_base_dirname)) {
            $errors[] = "Parent theme's directory is invalid. May contain only [a-z0-9-]";
        } elseif (strlen($parent_theme_base_dirname) > 70) {
            $errors[] = "Parent theme's directory should be fewer than 70 characters long.";
        }

        if (empty($errors)) {
            try {
                $installer = new orbisius_child_theme_creator($parent_theme_base_dirname);
                $installer->custom_info($child_custom_info);

                // Does the user want to copy the functions.php?
                if (!empty($_REQUEST['copy_functions_php'])) {
                    $installer->add_files('functions.php');
                }

                $installer->check_permissions();
                $installer->copy_main_files();
                $installer->generate_style();

                $success[] = "The child theme has been successfully created.";
                $success[] = $installer->get_details();
                
                if (!$multi_site && !empty($_REQUEST['switch'])) {
                    $child_theme_base_dir = $installer->get_child_base_dir();
                    $theme = wp_get_theme($child_theme_base_dir);

                    if (!$theme->exists() || !$theme->is_allowed()) {
                        throw new Exception('Cannot switch to the new theme for some reason.');
                    }

                    switch_theme($theme->get_stylesheet());
                    $next_url = admin_url('themes.php?activated=true');
                    
                    if (headers_sent()) {
                        $success = "Child theme created and switched. <a href='$next_url'>Continue</a>";
                    } else {
                        wp_safe_redirect($next_url);
                        exit;
                    }
                } elseif ($multi_site && !empty($_REQUEST['orbisius_child_theme_creator_make_network_wide_available'])) {
                    // Make child theme an allowed theme (network enable theme)
                    $allowed_themes = get_site_option( 'allowedthemes' );
                    $new_theme_name = $installer->get_child_base_dir();
                    $allowed_themes[ $new_theme_name ] = true;
                    update_site_option( 'allowedthemes', $allowed_themes );
                }
            } catch (Exception $e) {
                $errors[] = "There was an error during the child theme creation.";
                $errors[] = $e->getMessage();

                if (is_object($installer->result)) {
                    $errors[] = var_export($installer->result);
                }
            }
        }
    }

    if (!empty($errors)) {
        $msg .= orbisius_child_theme_creator_util::msg($errors);
    }

    if (!empty($success)) {
        $msg .= orbisius_child_theme_creator_util::msg($success, 1);
    }
    ?>
    <div class="wrap orbisius_child_theme_creator_container">
        <h2 style="display:inline;">Orbisius Child Theme Creator</h2>
        <div style="float: right;padding: 3px;" class="updated">
                <a href="http://qsandbox.com/?utm_source=orbisius-child-theme-creator&utm_medium=action_screen&utm_campaign=product"
                     target="_blank" title="Opens in new tab/window. qSandbox is a FREE service that allows you to setup a test/sandbox WordPress site in 2 seconds. No technical knowledge is required.
                     Test themes and plugins before you actually put them on your site">Free Test Site</a> <small>(2 sec setup)</small>

                | <a href="http://orbisius.com/page/free-quote/?utm_source=child-theme-creator&utm_medium=plugin-links&utm_campaign=plugin-update"
                     target="_blank" title="If you want a custom web/mobile app or a plugin developed contact us. This opens in a new window/tab">Hire Us</a>

                | <a href="http://orbisius.us2.list-manage.com/subscribe?u=005070a78d0e52a7b567e96df&id=1b83cd2093" target="_blank"
                     title="This opens in a new window/tab">Newsletter</a>

                | <a href="http://club.orbisius.com/forums/forum/community-support-forum/wordpress-plugins/orbisius-child-theme-creator/?utm_source=orbisius-child-theme-editor&utm_medium=action_screen&utm_campaign=product" target="_blank" title="[new window]">Support Forums</a>

                | <a href="http://club.orbisius.com/products/wordpress-plugins/orbisius-child-theme-creator/?utm_source=orbisius-child-theme-editor&utm_medium=action_screen&utm_campaign=product" target="_blank" title="[new window]">Product Page</a>

                | <a href="#help" title="">Help</a>
        </div>
        
    <?php echo $msg; ?>

    <?php
    $buff = '';
    $buff .= "<h2>Available Themes</h2>\n";

    // call to action.
    $buff .= "<div class='updated'><p>\n";
    $buff .= "Decide which parent theme you want to use from the list below and then click on the <strong>Create Child Theme</strong> button.";
    $buff .= "</p></div>\n";
    
    $buff .= "<div id='availablethemes' class='theme_container'>\n";
    $nonce = wp_create_nonce(basename(__FILE__) . '-action');

    $args = array();
    $themes = wp_get_themes($args);

    // we use the same CSS as in WP's appearances but put only the buttons we want.
    foreach ($themes as $theme_basedir_name => $theme_obj) {
        $parent_theme = $theme_obj->get('Template');

        if (!empty($parent_theme)) {
            continue; // no kids allowed here.
        }
        
        // get the web uri for the current theme and go 1 level up
        $src = dirname(get_template_directory_uri()) . "/$theme_basedir_name/screenshot.png";
        $functions_file = dirname(get_template_directory()) . "/$theme_basedir_name/functions.php";
        $parent_theme_base_dirname_fmt = urlencode($theme_basedir_name);
        $create_url = $_SERVER['REQUEST_URI'];

        // cleanup old links or refreshes.
        $create_url = preg_replace('#&parent_theme_base_dirname=[\w-]+#si', '', $create_url);
        $create_url = preg_replace('#&orbisius_child_theme_creator_nonce=[\w-]+#si', '', $create_url);

        $create_url .= '&parent_theme_base_dirname=' . $parent_theme_base_dirname_fmt;
        $create_url .= '&orbisius_child_theme_creator_nonce=' . $nonce;

        /* $create_url2 = esc_url( add_query_arg(
          array( 'parent_theme_base_dirname' => $parent_theme_base_dirname_fmt,
          ), admin_url( 'themes.php' ) ) ); */

        $author_name = $theme_obj->get('Author');
        $author_name = orbisius_child_theme_creator_util::sanitize_data($author_name);
        $author_name = empty($author_name) ? 'n/a' : $author_name;

        $ver = $theme_obj->get('Version');
        $ver = orbisius_child_theme_creator_util::sanitize_data($ver);
        $ver_esc = esc_attr($ver);
        
        $theme_name = $theme_obj->get('Name');
        $theme_name = orbisius_child_theme_creator_util::sanitize_data($theme_name);

        $theme_uri = $theme_obj->get('ThemeURI');
        $theme_uri = orbisius_child_theme_creator_util::sanitize_data($theme_uri);

        $author_uri = $theme_obj->get('AuthorURI');
        $author_uri = orbisius_child_theme_creator_util::sanitize_data($author_uri);

        $author_line = empty($author_uri)
                ? $author_name
                : "<a title='Visit author homepage' href='$author_uri' target='_blank'>$author_name</a>";
        
        $author_line .= " | Ver.$ver_esc\n";

        $edit_theme_link = orbisius_child_theme_creator_util::get_theme_editor_link( array('theme_1' => $theme_basedir_name) );
        $author_line .= " | <a href='$edit_theme_link' title='Edit with Orbisius Theme Editor'>Edit</a>\n";

        $buff .= "<div class='available-theme'>\n";
        $buff .= "<form action='$create_url' method='post'>\n";
        $buff .= "<img class='screenshot' src='$src' alt='' />\n";
        $buff .= "<h3>$theme_name</h3>\n";
        $buff .= "<div class='theme-author'>By $author_line</div>\n";
        $buff .= "<div class='action-links'>\n";
        $buff .= "<ul>\n";

        if (isset($_REQUEST['orb_show_copy_functions']) && file_exists($functions_file)) {
            $adv_container_id = md5($src);

            $buff .= "
                <li>
                    <a href='javascript:void(0)' onclick='jQuery(\"#orbisius_ctc_act_adv_$adv_container_id\").slideToggle(\"slow\");'>+ Advanced</a> (show/hide)
                    <div id='orbisius_ctc_act_adv_$adv_container_id' class='app-hide'>";

            $buff .= "<label>
                                <input type='checkbox' id='orbisius_child_theme_creator_copy_functions_php' name='copy_functions_php' value='1' /> Copy functons.php
                                (<span class='app-serious-notice'><strong>Danger</strong>: if the theme doesn't support
                                <a href='http://wp.tutsplus.com/tutorials/creative-coding/understanding-wordpress-pluggable-functions-and-their-usage/'
                                    target='_blank'>pluggable functions</a> this <strong>will crash your site</strong>. Make a backup is highly recommended. In most cases you won't need to copy functions.php</span>)
                      </label>
                    ";

            $buff .= "
                    </div> <!-- /orbisius_ctc_act_adv_$adv_container_id -->
                </li>\n";
        }

        // Let's allow the user to make that theme network wide usable
        if ($multi_site) {
            $buff .= "<li>
                        <label>
                            <input type='checkbox' id='orbisius_child_theme_creator_make_network_wide_available'
                            name='orbisius_child_theme_creator_make_network_wide_available' value='1' /> Make the new theme network wide available
                        </label>
                    </li>\n";
        } else {
            $buff .= "<li><label><input type='checkbox' id='orbisius_child_theme_creator_switch' name='switch' value='1' /> "
                    . "Switch theme to the new theme after it is created</label></li>\n";
        }

        // This allows the users to specify title and description of the target child theme
        $customize_info_container_id = 'orbisius_ctc_cust_info_' . md5($src);
        
        $buff .= "<li><label><input type='checkbox' id='orbisius_child_theme_creator_customize_info' name='customize_info' value='1'"
                . " onclick='jQuery(\"#$customize_info_container_id\").toggle(\"fast\");' /> "
                    . "Customize title, description etc.<br/></label></li>\n";

        $cust_info_name = 'Child of ' . $theme_name;
        $cust_info_name_esc = esc_attr($cust_info_name);

        $cust_info_descr = $theme_obj->Description;
        $cust_info_descr = wp_kses($cust_info_descr, array());
        $cust_info_descr_esc = esc_attr($cust_info_descr);

        $author_name_esc = esc_attr($author_name);
        $author_uri_esc = esc_attr($author_uri);
        $theme_uri_esc = esc_attr($theme_uri);

        $buff .= "<div id='$customize_info_container_id' class='app-hide'>
                  <table class='form-table'>
                    <tr>
                        <td>Title</td>
                        <td><input type='text' id='cust_child_theme_title_$customize_info_container_id' name='child_custom_info[name]' value='' placeholder='$cust_info_name_esc' /></td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td><textarea id='cust_child_theme_descr_$customize_info_container_id' name='child_custom_info[descr]' placeholder='$cust_info_descr_esc' rows='4'></textarea></td>
                    </tr>
                    <tr>
                        <td>Theme Site</td>
                        <td><input type='text' id='cust_child_theme_uri_$customize_info_container_id' name='child_custom_info[theme_uri]' value='' placeholder='$theme_uri_esc' /></td>
                    </tr>
                    <tr>
                        <td>Author Name</td>
                        <td><input type='text' id='cust_child_theme_author_$customize_info_container_id' name='child_custom_info[author]' value='' placeholder='$author_name_esc' /></td>
                    </tr>
                    <tr>
                        <td>Author Site</td>
                        <td><input type='text' id='cust_child_theme_author_site_$customize_info_container_id' name='child_custom_info[author_uri]' value='' placeholder='$author_uri_esc' /></td>
                    </tr>
                    <tr>
                        <td>Version</td>
                        <td><input type='text' id='cust_child_theme_ver_$customize_info_container_id' name='child_custom_info[ver]' value='' placeholder='$ver' /></td>
                    </tr>
                  </table>
                </div> <!-- /$customize_info_container_id -->
        ";
        // /This allows the users to specify title and description of the target child theme

        $buff .= "<li> <button type='submit' class='button button-primary'>Create Child Theme</button> </li>\n";
    
        $buff .= "</ul>\n";
        $buff .= "</div> <!-- /action-links -->\n";
        $buff .= "</form> <!-- /form -->\n";
        $buff .= "</div> <!-- /available-theme -->\n";
    }

    $buff .= "<br class='clear' /><h2>Child Themes</h2>\n";
    $child_themes_cnt = 0;
    
    // list child themes
    // we use the same CSS as in WP's appearances but put only the buttons we want.
    foreach ($themes as $theme_basedir_name => $theme_obj) {
        $parent_theme = $theme_obj->get('Template');

        if (empty($parent_theme)) {
            continue; // no parents allowed here.
        }

        $child_themes_cnt++;
        
        // get the web uri for the current theme and go 1 level up
        $src = dirname(get_template_directory_uri()) . "/$theme_basedir_name/screenshot.png";
       
        $author_name = $theme_obj->get('Author');
        $author_name = strip_tags($author_name);
        $author_name = empty($author_name) ? 'n/a' : $author_name;

        $author_uri = $theme_obj->get('AuthorURI');
        $author_line = empty($author_uri)
                ? $author_name
                : "<a title='Visit author homepage' href='$author_uri' target='_blank'>$author_name</a>";

        $author_line .= " | Ver.$theme_obj->Version\n";

        $edit_theme_link = orbisius_child_theme_creator_util::get_theme_editor_link( array('theme_1' => $theme_basedir_name) );
        $author_line .= " | <a href='$edit_theme_link' title='Edit with Orbisius Theme Editor'>Edit</a>\n";

        $buff .= "<div class='available-theme'>\n";
        $buff .= "<img class='screenshot' src='$src' alt='' />\n";
        $buff .= "<h3>$theme_obj->Name</h3>\n";
        $buff .= "<div class='theme-author'>By $author_line</div>\n";
        $buff .= "</div> <!-- /available-theme -->\n";
    }

    if ( $child_themes_cnt == 0 ) {
        $buff .= "<div>No child themes found.</div>\n";
    }

    $buff .= "</div> <!-- /availablethemes -->\n <br class='clear' />";

    //var_dump($themes);
    echo $buff;
    ?>

        <a name="help"></a>
        <h2>Support &amp; Premium Plugins</h2>
        <div class="updated">
            <p>
                The support is handled on our Club Orbisius site: <a href="http://club.orbisius.com/forums/forum/community-support-forum/wordpress-plugins/orbisius-child-theme-creator/" target="_blank" title="[new window]">http://club.orbisius.com/</a>.
                Please do NOT use the WordPress forums or other places to seek support.
            </p>
        </div>
        <?php if (1) : ?>
        <?php
        $plugin_data = orbisius_child_theme_creator_get_plugin_data();

        $app_link = urlencode($plugin_data['PluginURI']);
        $app_title = urlencode($plugin_data['Name']);
        $app_descr = urlencode($plugin_data['Description']);
        ?>

        <h2>Like this plugin? Share it with your friends</h2>
        <p>
            <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
                <a class="addthis_button_facebook" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                <a class="addthis_button_twitter" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                <a class="addthis_button_google_plusone" g:plusone:count="false" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                <a class="addthis_button_linkedin" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                <a class="addthis_button_email" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                <a class="addthis_button_myspace" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                <a class="addthis_button_google" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                <a class="addthis_button_digg" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                <a class="addthis_button_delicious" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                <a class="addthis_button_stumbleupon" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                <a class="addthis_button_tumblr" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                <a class="addthis_button_favorites" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                <a class="addthis_button_compact"></a>
            </div>
            <!-- The JS code is in the footer -->

            <script type="text/javascript">
                var addthis_config = { "data_track_clickback": true };
                var addthis_share = {
                    templates: {twitter: 'Check out {{title}} #wordpress #plugin at {{lurl}} (via @orbisius)'}
                };
            </script>
            <!-- AddThis Button START part2 -->
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>
            <!-- AddThis Button END part2 -->
        </p>
    <?php endif ?>

    <h2>Want to hear about future plugins? Join our mailing List! (no spam)</h2>
    <p>
        Get the latest news and updates about this and future cool <a href="http://profiles.wordpress.org/lordspace/"
                                                                      target="_blank" title="Opens a page with the pugins we developed. [New Window/Tab]">plugins we develop</a>.
    </p>

    <p>
        <!-- // MAILCHIMP SUBSCRIBE CODE \\ -->
        1) Subscribe by going to <a href="http://eepurl.com/guNzr" target="_blank">http://eepurl.com/guNzr</a>
        <!-- \\ MAILCHIMP SUBSCRIBE CODE // -->
        OR
        2) by using our QR code. [Scan it with your mobile device].<br/>
        <img src="<?php echo plugin_dir_url(__FILE__); ?>/i/guNzr.qr.2.png" alt="" />
    </p>

    <h2>Demo</h2>
    <p>
        <iframe width="560" height="315" src="http://www.youtube.com/embed/BZUVq6ZTv-o" frameborder="0" allowfullscreen></iframe>

        <br/>Video Link: <a href="http://www.youtube.com/watch?v=BZUVq6ZTv-o&feature=youtu.be" target="_blank">http://www.youtube.com/watch?v=BZUVq6ZTv-o&feature=youtu.be</a>
    </p>
    </div> <!-- /wrap -->
    <?php
}

/**
 * It seems WP intentionally adds slashes for consistency with php.
 * Please note: WordPress Core and most plugins will still be expecting slashes, and the above code will confuse and break them.
 * If you must unslash, consider only doing it to your own data which isn't used by others:
 * @see http://codex.wordpress.org/Function_Reference/stripslashes_deep
 */
function orbisius_child_theme_creator_get_request() {
    $req = $_REQUEST;
    $req = stripslashes_deep($req);

    return $req;
}

/**
 * adds some HTML comments in the page so people would know that this plugin powers their site.
 */
function orbisius_child_theme_creator_add_plugin_credits() {
    // pull only these vars
    $default_headers = array(
        'Name' => 'Plugin Name',
        'PluginURI' => 'Plugin URI',
    );

    $plugin_data = get_file_data(__FILE__, $default_headers, 'plugin');

    $url = $plugin_data['PluginURI'];
    $name = $plugin_data['Name'];

    printf(PHP_EOL . PHP_EOL . '<!-- ' . "Powered by $name | URL: $url " . '-->' . PHP_EOL . PHP_EOL);
}

/**
 */
class orbisius_child_theme_creator {

    public $result = null;
    public $target_dir_path; // /var/www/vhosts/domain.com/www/wp-content/themes/Parent-Theme-child-01/

    /**
     * Sets up the params.
     * directories contain trailing slashes.
     * 
     * @param str $parent_theme_basedir
     */

    public function __construct($parent_theme_basedir = '') {
        $all_themes_root = get_theme_root();

        $this->parent_theme_basedir = $parent_theme_basedir;
        $this->parent_theme_dir = $all_themes_root . '/' . $parent_theme_basedir . '/';

        $i = 0;

        // Let's create multiple folders in case the script is run multiple times.
        do {
            $i++;
            $target_dir = $all_themes_root . '/' . $parent_theme_basedir . '-child-' . sprintf("%02d", $i) . '/';
        } while (is_dir($target_dir));

        $this->target_dir_path = $target_dir;
        $this->target_base_dirname = basename($target_dir);

        // this is appended to the new theme's name
        $this->target_name_suffix = 'Child ' . sprintf("%02d", $i);
    }

    /**
     * @param void
     * @return string returns the dirname (not abs) of the child theme
     */
    public function get_child_base_dir() {
        return $this->target_base_dirname;
    }

    private $custom_info = array();

    /**
     * Get/sets custom info that is related to the child theme
     * It accepts array or key val or just get all of the custom data
     * @param void
     * @return string returns the dirname (not abs) of the child theme
     */
    public function custom_info($key = null, $value = null) {
        if (!is_null($key)) {
            if (is_array($key)) { // set array
                $this->custom_info = orbisius_child_theme_creator_util::sanitize_data($key);
            } else if (!is_null($value)) { // set scalar
                $this->custom_info[$key] = orbisius_child_theme_creator_util::sanitize_data($value);
            } else { // get for a key
                return $this->custom_info[$key];
            }
        }

        return $this->custom_info; // all custom info requested
    }

    /**
     * Loads files from a directory but skips . and ..
     */
    public function load_files($dir) {
        $files = orbisius_child_theme_creator_util::load_files();
        return $files;
    }

    private $info_result = 'n/a';
    private $data_file = '.ht_orbisius_child_theme_creator.json';

    /**
     * Checks for correct permissions by trying to create a file in the target dir
     * Also it checks if there are files in the target directory in case it exists.
     */
    public function check_permissions() {
        $target_dir_path = $this->target_dir_path;

        if (!is_dir($target_dir_path)) {
            if (!mkdir($target_dir_path, 0775)) {
                throw new Exception("Target child theme directory cannot be created. This is probably a permission error. Cannot continue.");
            }
        } else { // let's see if there will be files in that folder.
            $files = $this->load_files($target_dir_path);

            if (count($files) > 0) {
                throw new Exception("Target folder already exists and has file(s) in it. Cannot continue. Files: ["
                . join(',', array_slice($files, 0, 5)) . ' ... ]');
            }
        }

        // test if we can create the folder and then delete it.
        if (!touch($target_dir_path . $this->data_file)) {
            throw new Exception("Target directory is not writable.");
        }
    }

    /**
     * What files do we have to copy from the parent theme.
     * @var array
     */
    private $main_files = array('screenshot.png', 'header.php', 'footer.php', );

    /**
     * 
     */
    public function add_files($files) {
        $files = (array) $files;
        $this->main_files = array_merge($files, $this->main_files);
    }
    
    /**
     * Copy some files from the parent theme.
     * @return bool success
     */
    public function copy_main_files() {
        $stats = 0;

        $main_files = $this->main_files;

        foreach ($main_files as $file) {
            if (!file_exists($this->parent_theme_dir . $file)) {
                continue;
            }

            $stat = copy($this->parent_theme_dir . $file, $this->target_dir_path . $file);
            $stat = intval($stat);
            $stats += $stat;
        }

        // Some themes have admin files for control panel stuff. So Let's copy it as well.
        if (is_dir($this->parent_theme_dir . 'admin/')) {
            orbisius_child_theme_creator_util::copy($this->parent_theme_dir . 'admin/', $this->target_dir_path . 'admin/');
        }
    }

    /**
     *
     * @return bool success
     * @see http://codex.wordpress.org/Child_Themes
     */
    public function generate_style() {
        global $wp_version;

        $plugin_data = get_plugin_data(__FILE__);
        $app_link = $plugin_data['PluginURI'];
        $app_title = $plugin_data['Name'];

        $parent_theme_data = version_compare($wp_version, '3.4', '>=') ? wp_get_theme($this->parent_theme_basedir) : (object) get_theme_data($this->target_dir_path . 'style.css');

        $theme_name = "$parent_theme_data->Name $this->target_name_suffix";
        $theme_uri = $parent_theme_data->ThemeURI;
        $theme_descr = "$this->target_name_suffix theme for the $parent_theme_data->Name theme";
        $theme_author = $parent_theme_data->Author;
        $theme_author_uri = $parent_theme_data->AuthorURI;
        $ver = $parent_theme_data->Version;

        $custom_info = $this->custom_info();

        if (!empty($custom_info['name'])) {
            $theme_name = $custom_info['name'];
        }

        if (!empty($custom_info['theme_uri'])) {
            $theme_uri = $custom_info['theme_uri'];
        }

        if (!empty($custom_info['descr'])) {
            $theme_descr = $custom_info['descr'];
        }

        if (!empty($custom_info['author'])) {
            $theme_author = $custom_info['author'];
        }

        if (!empty($custom_info['author_uri'])) {
            $theme_author_uri = $custom_info['author_uri'];
        }

        if (!empty($custom_info['ver'])) {
            $ver = $custom_info['ver'];
        }

        $buff = '';
        $buff .= "/*\n";
        $buff .= "Theme Name: $theme_name\n";
        $buff .= "Theme URI: $theme_uri\n";
        $buff .= "Description: $theme_descr\n";
        $buff .= "Author: $theme_author\n";
        $buff .= "Author URI: $theme_author_uri\n";
        $buff .= "Template: $this->parent_theme_basedir\n";
        $buff .= "Version: $ver\n";
        $buff .= "*/\n";

        $buff .= "\n/* Generated by $app_title ($app_link) on " . date('r') . " */ \n\n";

        $buff .= "@import url('../$this->parent_theme_basedir/style.css');\n";

        file_put_contents($this->target_dir_path . 'style.css', $buff);

        // RTL langs; make rtl.css to point to the parent file as well
        if (file_exists($this->parent_theme_dir . 'rtl.css')) {
            $rtl_buff = '';
            $rtl_buff .= "/*\n";
            $rtl_buff .= "Theme Name: $theme_name\n";
            $rtl_buff .= "Template: $this->parent_theme_basedir\n";
            $rtl_buff .= "*/\n";

            $rtl_buff .= "\n/* Generated by $app_title ($app_link) on " . date('r') . " */ \n\n";

            $rtl_buff .= "@import url('../$this->parent_theme_basedir/rtl.css');\n";

            file_put_contents($this->target_dir_path . 'rtl.css', $rtl_buff);
        }

        $themes_url = admin_url('themes.php');

        $this->info_result = "$parent_theme_data->Name " . $this->target_name_suffix . ' has been created in ' . $this->target_dir_path
                . ' based on ' . $parent_theme_data->Name . ' theme.'
                . "\n<br/>Next go to <a href='$themes_url'><strong>Appearance &gt; Themes</strong></a> and Activate the new theme.";
    }

    /**
     *
     * @return string
     */
    public function get_details() {
        return $this->info_result;
    }

    /**
     *
     * @param type $filename
     */
    function log($msg) {
        error_log($msg . "\n", 3, ini_get('error_log'));
    }

}

/**
 * Util funcs
 */
class orbisius_child_theme_creator_util {
    /**
     * This cleans filenames but leaves some of the / because some files can be dir1/file.txt.
     * $jail_root must be added because it will also prefix the path with a directory i.e. jail
     *
     * @param type $file_name
     * @param type $jail_root
     * @return string
     */
    public static function sanitize_file_name($file_name = null, $jail_root = '') {
        if (empty($jail_root)) {
            $file_name = sanitize_file_name($file_name); // wp func
        } else {
            $file_name = str_replace('/', '__SLASH__', $file_name);
            $file_name = sanitize_file_name($file_name); // wp func
            $file_name = str_replace('__SLASH__', '/', $file_name);
        }

        $file_name = preg_replace('#(?:\/+|\\+)#si', '/', $file_name);
        $file_name = ltrim($file_name, '/'); // rm leading /

        if (!empty($jail_root)) {
            $file_name = $jail_root . $file_name;
        }

        return $file_name;
    }

    /**
     * Uses wp_kses to sanitize the data
     * @param  str/array $value
     * @return mixed: str/array
     * @throws Exception
     */
    public static function sanitize_data($value = null) {
        if (is_scalar($value)) {
            $value = wp_kses($value, array());
            $value = preg_replace('#\s+#si', ' ', $value);
            $value = trim($value);
        } else if (is_array($value)) {
            $value = array_map(__METHOD__, $value);
        } else {
            throw new Exception(__METHOD__.  " Cannot sanitize because of invalid input data.");
        }

        return $value;
    }

    /**
     * Returns a link to appearance. Taking into account multisite.
     * 
     * @param array $params
     * @return string
     */
    static public function get_create_child_pages_link($params = array()) {
        $rel_path = 'themes.php?page=orbisius_child_theme_creator_themes_action';

        if (!empty($params)) {
            $rel_path = orbisius_child_theme_creator_html::add_url_params($rel_path, $params);
        }

        $create_child_themes_page_link = is_multisite()
                    ? network_admin_url($rel_path)
                    : admin_url($rel_path);

        return $create_child_themes_page_link;
    }

    /**
     * Returns the link to the Theme Editor e.g. when a theme_1 or theme_2 is supplied.
     * @param type $params
     * @return string
     */
    static public function get_theme_editor_link($params = array()) {
        $rel_path = 'themes.php?page=orbisius_child_theme_creator_theme_editor_action';

        if (!empty($params)) {
            $rel_path = orbisius_child_theme_creator_html::add_url_params($rel_path, $params);
        }

        $link = is_multisite()
                    ? network_admin_url($rel_path)
                    : admin_url($rel_path);

        return $link;
    }

    /**
     * Returns the link to the Theme Editor e.g. when a theme_1 or theme_2 is supplied.
     * @param type $params
     * @return string
     */
    static public function get_settings_link($params = array()) {
        $rel_path = 'options-general.php?page=orbisius_child_theme_creator_settings_page';

        if (!empty($params)) {
            $rel_path = orbisius_child_theme_creator_html::add_url_params($rel_path, $params);
        }

        $link = is_multisite()
                    ? network_admin_url($rel_path)
                    : admin_url($rel_path);

        return $link;
    }

    /**
     * Recursive function to copy (all subdirectories and contents).
     * It doesn't create folder in the target folder.
     * Note: this may be slow if there are a lot of files.
     * The native call might be quicker.
     *
     * Example: src: folder/1/ target: folder/2/
     * @see http://stackoverflow.com/questions/5707806/recursive-copy-of-directory
     */
    static public function copy($src, $dest, $perm = 0775) {
        if (!is_dir($dest)) {
            mkdir($dest, $perm, 1);
        }

        if (is_dir($src)) {
            $dir = opendir($src);

            while ( false !== ( $file = readdir($dir) ) ) {
                if ( $file == '.' || $file == '..' || $file == '.git'  || $file == '.svn' ) {
                    continue;
                }

                $new_src = rtrim($src, '/') . '/' . $file;
                $new_dest = rtrim($dest, '/') . '/' . $file;

                if ( is_dir( $new_src ) ) {
                    self::copy( $new_src, $new_dest );
                } else {
                    copy( $new_src, $new_dest );
                }
            }

            closedir($dir);
        } else { // can also handle simple copy commands
            copy($src, $dest);
        }
    }

    /**
     * Create an zip file. Requires ZipArchive class to exist.
     * Usage: $result = create_zip($files_to_zip, 'my-archive.zip', true, $prefix_to_strip, 'Slavi created this archive at ' . date('r') );
     *
     * @param array $files
     * @param str $destination zip file
     * @param str $overwrite
     * @param str $prefix_to_strip
     * @param str $comment
     * @return boolean
     */
    function create_zip($files = array(), $destination = '', $overwrite = false, $prefix_to_strip = '', $comment = '' ) {
        if ((file_exists($destination) && !$overwrite) || !class_exists('ZipArchive')) {
            return false;
        }

        $zip = new ZipArchive();

        if ($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
            return false;
        }

        foreach ($files as $file) {
            // if we specify abs path to the dir we'll add a relative folder in the archive.
            $file_in_archive = str_ireplace($prefix_to_strip, '', $file);
            $zip->addFile($file, $file_in_archive);
        }

        if (!empty($comment)) {
            $zip->setArchiveComment($comment);
        }

        $zip->close();

        return file_exists($destination);
    }

    /**
     * Loads files from a directory and skips . and ..
     * By default it retuns files relativ to the theme's folder.
     * 
     * @since 1.1.3 it supports recusiveness
     * @param bool $ret_full_paths
     */
    public static function load_files($dir, $ret_full_paths = 0) {
        $files = array();

        $dir = rtrim($dir, '/') . '/';
        $all_files = scandir($dir);

        foreach ($all_files as $file) {
            if ($file == '.' || $file == '..' || substr($file, 0, 1) == '.') { // skip hidden files
                continue;
            }

            if (is_dir($dir . $file)) {
                $dir_in_themes_folder = $file;
                $sub_dir_files = self::load_files($dir . $dir_in_themes_folder, $ret_full_paths);
                
                foreach ($sub_dir_files as $sub_dir_file) {
                    $files[] = $ret_full_paths ? $sub_dir_file : $dir_in_themes_folder . '/' . $sub_dir_file;
                }
            } else {
                $files[] = ($ret_full_paths ? $dir : '') . $file;
            }
        }

        return $files;
    }

    /**
     * Outputs a message (adds some paragraphs).
     */
    static public function msg($msg, $status = 0) {
        $msg = join("<br/>\n", (array) $msg);

        if (empty($status)) {
            $cls = 'app-alert-error';
        } elseif ($status == 1) {
            $cls = 'app-alert-success';
        } else {
            $cls = 'app-alert-notice';
        }

        $str = "<div class='$cls'><p>$msg</p></div>";

        return $str;
    }

}

/**
 * HTML related methods
 */
class orbisius_child_theme_creator_html {

    /**
     *
     * Appends a parameter to an url; uses '?' or '&'. It's the reverse of parse_str().
     * If no URL is supplied no prefix is added (? or &)
     *
     * @param string $url
     * @param array $params
     * @return string
     */
    public static function add_url_params($url, $params = array()) {
        $str = $query_start = '';

        $params = (array) $params;

        if (empty($params)) {
            return $url;
        }

        if (!empty($url)) {
            $query_start = (strpos($url, '?') === false) ? '?' : '&';
        }

        $str = $url . $query_start . http_build_query($params);

        return $str;
    }

    // generates HTML select
    public static function html_select($name = '', $sel = null, $options = array(), $attr = '') {
        $name = trim($name);
        $elem_name = $name;
        $elem_name = strtolower($elem_name);
        $elem_name = preg_replace('#[^\w]#si', '_', $elem_name);
        $elem_name = trim($elem_name, '_');

        $html = "\n" . '<select id="' . esc_attr($elem_name) . '" name="' . esc_attr($name) . '" ' . $attr . '>' . "\n";

        foreach ($options as $key => $label) {
            $selected = $sel == $key ? ' selected="selected"' : '';

            // if the key contains underscores that means these are labels
            // and should be readonly
            if (strpos($key, '__') !== false) {
                $selected .= ' disabled="disabled" ';
            }

            // This makes certain options to have certain CSS class
            // which can be used to highlight the row
            // the key must start with __sys_CLASS_NAME
            if (preg_match('#__sys_([\w-]+)#si', $label, $matches)) {
                $label = str_replace($matches[0], '', $label);
                $selected .= " class='$matches[1]' ";
            }

            $html .= "\t<option value='$key' $selected>$label</option>\n";
        }

        $html .= '</select>';
        $html .= "\n";

        return $html;
    }
}

/**
 * This method creates 2 panes that the user is able to use to edit theme files.
 * Everythin happens with AJAX
 */
function orbisius_ctc_theme_editor() {
    if ( is_multisite() && ! is_network_admin() ) {
        $next_url = orbisius_child_theme_creator_util::get_create_child_pages_link();

        if (headers_sent()) {
            $success = "In order to edit a theme in a multisite WordPress environment you must do it from Network Admin &gt; Apperance"
                    . "<br/><a href='$next_url' class='button button-primary'>Continue</a>";
            wp_die($success);
        } else {
            wp_redirect($next_url);
        }

        exit();
    }

    if (defined('DISALLOW_FILE_EDIT') && DISALLOW_FILE_EDIT) {
        wp_die('Theme editor is disabled due to DISALLOW_FILE_EDIT constant is set to true in wp-config.php', 'Orbisius Theme Editor disabled by config');
    }

    $msg = 'Pick any two themes and copy snippets from one to the other.';

    $plugin_data = orbisius_child_theme_creator_get_plugin_data();
    
    ?>
    <div class="wrap orbisius_child_theme_creator_container orbisius_ctc_theme_editor_container">
        <h2 style="display:inline;">Orbisius Theme Editor <small>(Part of <a href='<?php echo $plugin_data['url'];?>' target="_blank">Orbisius Child Theme Creator</a>)</small></h2>
        <div style="float: right;padding: 3px;" class="updated">
                <a href="http://qsandbox.com/?utm_source=orbisius-child-theme-editor&utm_medium=action_screen&utm_campaign=product"
                     target="_blank" title="Opens in new tab/window. qSandbox is a FREE service that allows you to setup a test/sandbox WordPress site in 2 seconds. No technical knowledge is required.
                     Test themes and plugins before you actually put them on your site">Free Test Site</a> <small>(2 sec setup)</small>

                | <a href="http://orbisius.com/page/free-quote/?utm_source=child-theme-editor&utm_medium=plugin-links&utm_campaign=plugin-update"
                     target="_blank" title="If you want a custom web/mobile app or a plugin developed contact us. This opens in a new window/tab">Hire Us</a>

                | <a href="http://orbisius.us2.list-manage.com/subscribe?u=005070a78d0e52a7b567e96df&id=1b83cd2093" target="_blank"
                     title="This opens in a new window/tab">Newsletter</a>

                | <a href="http://club.orbisius.com/forums/forum/community-support-forum/wordpress-plugins/orbisius-child-theme-creator/?utm_source=orbisius-child-theme-editor&utm_medium=action_screen&utm_campaign=product" target="_blank" title="[new window]">Support Forums</a>
                
                | <a href="http://club.orbisius.com/products/wordpress-plugins/orbisius-child-theme-creator/?utm_source=orbisius-child-theme-editor&utm_medium=action_screen&utm_campaign=product" target="_blank" title="[new window]">Product Page</a>
        </div>

        <div class="updated"><p><?php echo $msg; ?></p></div>

        <?php
            $buff = $theme_1_file = $theme_2_file = '';
            $req = orbisius_ctc_theme_editor_get_request();

            $current_theme = wp_get_theme();
            
            $html_dropdown_themes = array('' => '== SELECT THEME ==');

            $theme_1 = empty($req['theme_1']) ? $current_theme->get_stylesheet() : $req['theme_1'];
            $theme_2 = empty($req['theme_2']) ? '' : $req['theme_2'];

            $theme_load_args = array();
            $themes = wp_get_themes( $theme_load_args );

            // we use the same CSS as in WP's appearances but put only the buttons we want.
            foreach ($themes as $theme_basedir_name => $theme_obj) {
                $theme_name = $theme_obj->Name;

                $theme_dir = $theme_basedir_name;

                $parent_theme = $theme_obj->get('Template');

                // Is this a child theme?
                if ( !empty($parent_theme) ) {
                    $theme_name .= " (child of $parent_theme)";
                }

                // Is this the current theme?
                if ($theme_basedir_name == $current_theme->get_stylesheet()) {
                    $theme_name .= ' (site theme) __sys_highlight';
                }

                $html_dropdown_themes[$theme_dir] = $theme_name;
            }

            $html_dropdown_theme_1_files = array(
                '' => '<== SELECT THEME ==',
            );

        ?>

        <table class="widefat">
            <tr>
                <td width="50%">
                    <form id="orbisius_ctc_theme_editor_theme_1_form" class="orbisius_ctc_theme_editor_theme_1_form">
                        <strong>Theme #1:</strong>
                        <?php echo orbisius_child_theme_creator_html::html_select('theme_1', $theme_1, $html_dropdown_themes); ?>

                        <span class="theme_1_file_container">
                            | <strong>File:</strong>
                            <?php echo orbisius_child_theme_creator_html::html_select('theme_1_file', $theme_1_file, $html_dropdown_theme_1_files); ?>
                        </span>

                        <textarea id="theme_1_file_contents" name="theme_1_file_contents" rows="22" class="widefat"></textarea>

                        <div class="orbisius_ctc_theme_editor_theme_1_primary_buttons primary_buttons">
                            <button type='submit' class='button button-primary' id="theme_1_submit" name="theme_1_submit">Save Changes</button>
                            <span class="status"></span>
                        </div>

                        <hr />
                        <div class="orbisius_ctc_theme_editor_theme_1_secondary_buttons secondary_buttons">
                            <button type="button" class='button' id="theme_1_new_file_btn" name="theme_1_new_file_btn">New File</button>
                            <button type="button" class='button' id="theme_1_syntax_chk_btn" name="theme_1_syntax_chk_btn">PHP Syntax Check</button>
                            <button type="button" class='button' id="theme_1_send_btn" name="theme_1_send_btn">Send</button>
                            <a href="<?php echo site_url('/');?>" class='button' target="_blank" title="new tab/window"
                                id="theme_1_site_preview_btn" name="theme_1_site_preview_btn">View Site</a>

                            <?php do_action('orbisius_child_theme_creator_editors_ext_actions', 'theme_1'); ?>

                            <!--
                            <button type="button" class='button' id="theme_1_new_folder_btn" name="theme_1_new_folder_btn">New Folder</button>-->

                            <a href='javascript:void(0)' class='app-button-right app-button-negative' id="theme_1_delete_file_btn" name="theme_1_delete_file_btn">Delete File</a>

                            <div id='theme_1_new_file_container' class="theme_1_new_file_container app-hide">
                                <strong>New File</strong>
                                <input type="text" id="theme_1_new_file" name="theme_1_new_file" value="" />
                                <span>e.g. test.js, extra.css etc</span>
                                <span class="status"></span>

                                <br/>
                                <button type='button' class='button button-primary' id="theme_1_new_file_btn_ok" name="theme_1_new_file_btn_ok">Save</button>
                                <a href='javascript:void(0)' class='app-button-negative00 button delete' id="theme_1_new_file_btn_cancel" name="theme_1_new_file_btn_cancel">Cancel</a>
                            </div>

                            <!-- send -->
                            <div id='theme_1_send_container' class="theme_1_send_container app-hide">
                                <p>
                                    Email selected theme and parent theme (if any) to yourself or a colleague.
                                    Separate multiple emails with commas.<br/>
                                    <strong>To:</strong>
                                    <input type="text" id="theme_1_send_to" name="email" value="" placeholder="Enter email" />

                                    <button type='button' class='button button-primary' id="theme_1_send_btn_ok" name="theme_1_send_btn_ok">Send</button>
                                    <a href='javascript:void(0)' class='app-button-negative00 button delete'
                                       id="theme_1_send_btn_cancel" name="theme_1_send_btn_cancel">Cancel</a>
                                </p>
                            </div>
                            <!-- /send -->

                            <div>
                                <h3>Premium Plugins/Addons</h3>
                                <span>Please, support our work by purchasing a premium addon</span>
                                <ul>
                                    <li><a href="http://club.orbisius.com/products/wordpress-plugins/orbisius-theme-switcher/?utm_source=orbisius-child-theme-creator&utm_medium=editors&utm_campaign=product"
                                           target="_blank" title="Opens in a new tab/window">Orbisius Theme Switcher</a> - Allows you to preview any of the installed themes on your site.</li>
                                </ul>
                            </div>


                            <!-- new folder -->
                            <!--
                            <div id='theme_1_new_folder_container' class="theme_1_new_folder_container app-hide">
                                <strong>New Folder</strong>
                                <input type="text" id="theme_1_new_folder" name="theme_1_new_folder" value="" />
                                <span>e.g. includes, data</span>
                                <span class="status"></span>

                                <br/>
                                <button type='button' class='button button-primary' id="theme_1_new_folder_btn_ok" name="theme_1_new_folder_btn_ok">Save</button>
                                <a href='javascript:void(0)' class='app-button-negative00 button delete' id="theme_1_new_folder_btn_cancel" name="theme_1_new_folder_btn_cancel">Cancel</a>
                            </div>-->
                            <!-- /new folder -->
                        </div> <!-- /secondary_buttons -->
                    </form>

                </td>
                <td width="50%">
                    <form id="orbisius_ctc_theme_editor_theme_2_form" class="orbisius_ctc_theme_editor_theme_2_form">
                        <strong>Theme #2:</strong>
                        <?php echo orbisius_child_theme_creator_html::html_select('theme_2', $theme_2, $html_dropdown_themes); ?>

                        <span class="theme_2_file_container">
                            | <strong>File:</strong>
                            <?php echo orbisius_child_theme_creator_html::html_select('theme_2_file', $theme_2_file, $html_dropdown_theme_1_files); ?>
                        </span>

                        <textarea id="theme_2_file_contents" name="theme_2_file_contents" rows="22" class="widefat"></textarea>
                        <div class="orbisius_ctc_theme_editor_theme_2_primary_buttons primary_buttons">
                            <button type='submit' class='button button-primary' id="theme_2_submit" name="theme_2_submit">Save Changes</button>
                            <span class="status"></span>
                        </div>

                        <hr />
                        <div class="orbisius_ctc_theme_editor_theme_2_secondary_buttons secondary_buttons">
                            <!-- If you're looking at this code. Slavi says Hi to the curious developer! :) -->
                            
                            <button type="button" class='button' id="theme_2_new_file_btn" name="theme_2_new_file_btn">New File</button>
                            <button type="button" class='button' id="theme_2_syntax_chk_btn" name="theme_2_syntax_chk_btn">PHP Syntax Check</button>
                            <button type="button" class='button' id="theme_2_send_btn" name="theme_2_send_btn">Send</button>
                            <a href="<?php echo site_url('/');?>" class='button' target="_blank" title="new tab/window"
                                id="theme_2_site_preview_btn" name="theme_2_site_preview_btn">View Site</a>

                            <?php do_action('orbisius_child_theme_creator_editors_ext_actions', 'theme_2'); ?>

                            <a href='javascript:void(0)' class='app-button-right app-button-negative' id="theme_2_delete_file_btn" name="theme_2_delete_file_btn">Delete File</a>

                            <div id='theme_2_new_file_container' class="theme_2_new_file_container app-hide">
                                <strong>New File</strong>
                                <input type="text" id="theme_2_new_file" name="theme_2_new_file" value="" />
                                <span>e.g. test.js, extra.css etc</span>
                                <span class="status"></span>

                                <br/>
                                <button type='button' class='button button-primary' id="theme_2_new_file_btn_ok" name="theme_2_new_file_btn_ok">Save</button>
                                <a href='javascript:void(0)' class='app-button-negative00 button delete' id="theme_2_new_file_btn_cancel" name="theme_2_new_file_btn_cancel">Cancel</a>
                            </div>

                            <!-- send -->
                            <div id='theme_2_send_container' class="theme_2_send_container app-hide">
                                <p>
                                    Email selected theme and parent theme (if any) to yourself or a colleague.
                                    Separate multiple emails with commas.<br/>
                                    <strong>To:</strong>
                                    <input type="text" id="theme_2_send_to" name="email" value="" placeholder="Enter email" />

                                    <button type='button' class='button button-primary' id="theme_2_send_btn_ok" name="theme_2_send_btn_ok">Send</button>
                                    <a href='javascript:void(0)' class='app-button-negative00 button delete'
                                       id="theme_2_send_btn_cancel" name="theme_2_send_btn_cancel">Cancel</a>
                                </p>
                            </div>
                            <!-- /send -->
                        </div>
                    </form>
                </td>
            </tr>
        </table>
    <?php
}

/**
 * This is called via ajax. Depending on the sub_cmd param a different method will be called.
 *
 */
function orbisius_ctc_theme_editor_ajax() {
    $buff = 'INVALID AJAX SUB_CMD';

    $req = orbisius_ctc_theme_editor_get_request();
    $sub_cmd = empty($req['sub_cmd']) ? '' : $req['sub_cmd'];

    switch ($sub_cmd) {
        case 'generate_dropdown':
            $buff = orbisius_ctc_theme_editor_generate_dropdown();

            break;

        case 'load_file':
            $buff = orbisius_ctc_theme_editor_manage_file(1);
            break;

        case 'save_file':
            $buff = orbisius_ctc_theme_editor_manage_file(2);

            break;

        case 'delete_file':
            $buff = orbisius_ctc_theme_editor_manage_file(3);

            break;

        case 'syntax_check':
            $buff = orbisius_ctc_theme_editor_manage_file(4);

            break;

        case 'send_theme':
            $buff = orbisius_ctc_theme_editor_manage_file(5);

            break;

        default:
            break;
    }

    die($buff);
}

/**
 *
 * @param string $theme_base_dir
 */
function orbisius_ctc_theme_editor_zip_theme($theme_base_dir, $to) {
    $status_rec = array(
        'status' => 0,
        'msg' => '',
    );

    $status_rec['msg'] = 'Sent.';

    $theme_obj = wp_get_theme( $theme_base_dir ); // since 3.4

    $all_themes_root = get_theme_root();
    $theme_dir = get_theme_root() . "/$theme_base_dir/";

    if (empty($theme_base_dir) || empty($theme_obj) || !$theme_obj->exists() || !is_dir($theme_dir)) {
        $status_rec['msg'] = 'Selected theme is invalid.';
        return $status_rec;
    }

    $host = empty($_SERVER['HTTP_HOST']) ? '' : $_SERVER['HTTP_HOST'];
    $host = preg_replace('#^w+\.#si', '', $host);
    $host_suff = empty($host) ? '' : '' . $host . '_';

    $parent_theme_base_dir = $theme_obj->get('Template');

    $id = !empty($parent_theme_base_dir) ? 'child_theme_' : '';

    $theme_name = $theme_obj->get( 'Name' );
    $all_files = orbisius_child_theme_creator_util::load_files($theme_dir, 1);

    $upload_dir = wp_upload_dir();
    $dir = $upload_dir['basedir'] . '/'; // C:\path\to\wordpress\wp-content\uploads
    $target_zip_file = $dir . $host_suff . $id . $theme_base_dir . '__' . date('Y-m-d__H_m_s') . '.zip';

    $prefix_to_strip = $all_themes_root . '/';
    $result = orbisius_child_theme_creator_util::create_zip($all_files, $target_zip_file, true,
                $prefix_to_strip, 'Created by Orbisius Child Theme Creator at ' . date('r') . "\nSite: " . site_url() );

    $site_str = "Site: " . site_url();

    if ($result) {
       $attachments = array( $target_zip_file );

       if (!empty($parent_theme_base_dir)) { // Parent theme Zipping
           $id = 'parent_theme_';
           $target_parent_zip_file = $dir . $host_suff . $id . $parent_theme_base_dir . '__' . date('Y-m-d__H_m_s') . '.zip';

           $theme_dir = get_theme_root() . "/$parent_theme_base_dir/";
           $all_files = orbisius_child_theme_creator_util::load_files($theme_dir, 1);
           $result = orbisius_child_theme_creator_util::create_zip($all_files, $target_parent_zip_file, true,
                $prefix_to_strip, 'Created by Orbisius Child Theme Creator at ' . date('r') . "\n" . $site_str);

           if ($result) {
               $attachments[] = $target_parent_zip_file;
           }
       }

       $host = empty($_SERVER['HTTP_HOST']) ? '' : str_ireplace('www.', '', $_SERVER['HTTP_HOST']);
       $subject = 'Theme (zip): ' . $theme_name;
       $headers = array();
       $message = "Hi,\n\nPlease find the attached theme file(s). \n" . $site_str . "\n\nSent from Orbisius Child Theme Creator.\n";
       $headers = "From: $host WordPress <wordpress@$host>" . "\r\n";

       $mail_sent = wp_mail($to, $subject, $message, $headers, $attachments );

       if ($mail_sent) {
           $status_rec['status'] = $result;
           
           foreach ($attachments as $attachment) {
              unlink($attachment);
           }
       } else {
          $prefix = $upload_dir['baseurl'] . '/';
          $status_rec['msg'] = "Couldn't send email but was able to create zip file(s). Download it/them from the link(s) below.";

          foreach ($attachments as $idx => $attatchment_abs_path) {
              $cnt = $idx + 1;
              $file = basename($attatchment_abs_path);
              $status_rec['msg'] .= "<br/>&nbsp;$cnt) <a href='$prefix$file'>$file</a>\n";
          }
       }
       
    } else {
        $status_rec['msg'] = "Couldn't create zip files.";
    }

    return $status_rec;
}

/**
 * Receives and argument (string) that will be checked by php for syntax errors.
 * A temp file is created and then php binary is called with -l (that's lowercase L).
 * With exec() we check the return status but with shell_exec() we parse the output
 * (not reliable due to locale).
 * 
 * @requires exec() or shell_exec() functions.
 * @param string $theme_file_contents
 * @return array
 */
function orbisius_ctc_theme_editor_check_syntax($theme_file_contents) {
    $status_rec = array(
        'status' => 0,
        'msg' => '',
    );

    $temp = tmpfile();
    fwrite($temp, $theme_file_contents);

    if (function_exists('shell_exec') || function_exists('exec')) {
        $ok = 0;
        $meta_data = stream_get_meta_data($temp);
        $file = $meta_data['uri'];
        $file = escapeshellarg($file);
        $cmd = "php -l $file";

        // we're relying on exec's return value so we can tell
        // if the syntax is OK
        if (function_exists('exec')) {
            $exit_code = $output = 0;
            $last_line = exec($cmd, $output, $exit_code);
            $output = join('', $output); // this is an array with multiple lines including new lines

            $ok = empty($exit_code); // in linux 0 means success
        } else { // this relies on parsing the php output but if a non-english locale is used this will fail.
            $output = shell_exec($cmd . " 2>&1");
            $ok = stripos($output, 'No syntax errors detected') !== false;
        }

        $error = $output;

        if ($ok) {
            $status_rec['status'] = 1;
            $status_rec['msg'] = 'Syntax OK.';
        } else {
            $status_rec['msg'] = 'Syntax check failed. Error: ' . $error;
        }
    } else {
        $status_rec['msg'] = 'Syntax check: n/a. functiona: exec() and shell_exec() are not available.';
    }

    fclose($temp); // this removes the file

    return $status_rec;
}

/**
 * It seems WP intentionally adds slashes for consistency with php.
 * Please note: WordPress Core and most plugins will still be expecting slashes, and the above code will confuse and break them.
 * If you must unslash, consider only doing it to your own data which isn't used by others:
 * @see http://codex.wordpress.org/Function_Reference/stripslashes_deep
 */
function orbisius_ctc_theme_editor_get_request() {
    $req = $_REQUEST;
    $req = stripslashes_deep( $req );

    return $req;
}

/**
 * This returns an HTML select with the selected theme's files.
 * the name/id of that select must be either theme_1_file or theme_2_file
 * @return string
 */
function orbisius_ctc_theme_editor_generate_dropdown() {
    $theme_base_dir = $theme_1_file = '';
    $req = orbisius_ctc_theme_editor_get_request();

    $select_name = 'theme_1_file';

    if (!empty($req['theme_1'])) {
        $theme_base_dir = empty($req['theme_1']) ? '' : preg_replace('#[^\w-]#si', '', $req['theme_1']);
        $theme_1_file = empty($req['theme_1_file']) ? 'style.css' : $req['theme_1_file'];
    } elseif (!empty($req['theme_2'])) {
        $theme_base_dir = empty($req['theme_2']) ? '' : preg_replace('#[^\w-]#si', '', $req['theme_2']);
        $theme_1_file = empty($req['theme_2_file']) ? 'style.css' : $req['theme_2_file'];
        $select_name = 'theme_2_file';
    } else {
        return 'Invalid params.';
    }

    $theme_dir = get_theme_root() . "/$theme_base_dir/";

    if (empty($theme_base_dir) || !is_dir($theme_dir)) {
        return 'Selected theme is invalid.';
    }

    $files = array();
    $all_files = orbisius_child_theme_creator_util::load_files($theme_dir);

    foreach ($all_files as $file) {
        if (preg_match('#\.(php|css|js|txt)$#si', $file)) {
            $files[] = $file;
        }
    }

    // we're going to make values to be keys as well.
    $html_dropdown_theme_1_files = array_combine($files, $files);
    $buff = orbisius_child_theme_creator_html::html_select($select_name, $theme_1_file, $html_dropdown_theme_1_files);

    return $buff;
}

/**
 * Reads or writes contents to a file.
 * If the saving is not successfull it will return an empty buffer.
 * @param int $cmd_id : read - 1, write - 2, delete - 3
 * @return string
 */
function orbisius_ctc_theme_editor_manage_file($cmd_id = 1) {
    $buff = $theme_base_dir = $theme_dir = $theme_file = '';

    $req = orbisius_ctc_theme_editor_get_request();

    $theme_root = trailingslashit( get_theme_root() );

    if (!empty($req['theme_1']) && !empty($req['theme_1_file'])) {
        $theme_base_dir = empty($req['theme_1']) ? '______________' : preg_replace('#[^\w-]#si', '', $req['theme_1']);
        $theme_dir = $theme_root . "$theme_base_dir/";
        $theme_file = empty($req['theme_1_file']) ? $theme_dir . 'style.css' : orbisius_child_theme_creator_util::sanitize_file_name($req['theme_1_file'], $theme_dir);
        $theme_file_contents = empty($req['theme_1_file_contents']) ? '' : $req['theme_1_file_contents'];
    } elseif (!empty($req['theme_2']) && !empty($req['theme_2_file'])) {
        $theme_base_dir = empty($req['theme_2']) ? '______________' : preg_replace('#[^\w-]#si', '', $req['theme_2']);
        $theme_dir = $theme_root . "$theme_base_dir/";
        $theme_file = empty($req['theme_2_file']) ? $theme_dir . 'style.css' : orbisius_child_theme_creator_util::sanitize_file_name($req['theme_2_file'], $theme_dir);
        $theme_file_contents = empty($req['theme_2_file_contents']) ? '' : $req['theme_2_file_contents'];
    } else {
        return 'Missing data!';
    }
    
    //$theme_dir = $theme_root . "$theme_base_dir/";
    if (empty($theme_base_dir) || !is_dir($theme_dir)) {
        return 'Selected theme is invalid.';
    } elseif (!file_exists($theme_file) && $cmd_id == 1) {
    //} elseif (!file_exists($theme_dir . $theme_file) && $cmd_id == 1) {
        return 'Selected file is invalid.';
    }

    //$theme_file = $theme_dir . $theme_file; //

    if ($cmd_id == 1) {
        $buff = file_get_contents($theme_file);
    } elseif ($cmd_id == 2) {
        $status = file_put_contents($theme_file, $theme_file_contents);
        $buff = !empty($status) ? $theme_file_contents : '';
    } elseif ($cmd_id == 3 && (!empty($req['theme_1_file']) || !empty($req['theme_2_file']))) {
        $status = unlink($theme_file);
    }

    elseif ($cmd_id == 4) { // syntax check. create a tmp file and ask php to check it.
        $status_rec = orbisius_ctc_theme_editor_check_syntax($theme_file_contents);
        
        if (function_exists('wp_send_json')) { // since WP 3.5
            wp_send_json($status_rec);
        } else {
            @header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
            $buff = json_encode($status_rec);
        }
    }

    elseif ($cmd_id == 5) { // zip
        $to = empty($req['email']) ? '' : preg_replace('#[^\w-\.@,\'"]#si', '', $req['email']);
        $status_rec = orbisius_ctc_theme_editor_zip_theme($theme_base_dir, $to);

        if (function_exists('wp_send_json')) { // since WP 3.5
            wp_send_json($status_rec);
        } else {
            @header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
            $buff = json_encode($status_rec);
        }
    }
    
    else {
        
    }

    return $buff;
}
