<?php

class Comment_Form_Main {

    /**
     * array with plugin options and settings
     *
     * @since 1.0.0
     */
    public $options = array();

    /**
     * initialize the plugin
     *
     * @since 1.0
     */
    public function __construct() {
        // load options

        // load plugin text domain
        add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
    }

    /**
     * get and load plugin options
     *
     * @since 1.0.0
     *
     * @param str $field which option to return
     *  empty to return all of them
     */
    public function options($field = false){
        $defaults = $this->get_default_options();
        $options = wp_parse_args(get_option('commentform_settings', array()), $defaults);

        $this->options = $options;

        if($field != '' && isset($options[$field])) {
            return $options[$field];
        } elseif($field != false && !isset($options[$field])) {
            return false;
        } else {
            return $options;
        }
    }

    /**
     * prepare default options
     *
     * @since 1.0.0
     */
    public function get_default_options(){

        $req = get_option( 'require_name_email' );
        $required_text = sprintf( ' ' . __('Required fields are marked %s'), '<span class="required">*</span>' );

        $defaults = array(
            'hide_notes_after' => 0,
            'hide_notes_before' => 0,
            'hide_url' => 0,
            'hide_url_css' => false,
            'text_after' => '',
            'text_before' => '',
        );

        return $defaults;
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.2.0
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain("commentform", false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

}
