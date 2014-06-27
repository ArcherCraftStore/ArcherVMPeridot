<?php

class Comment_Form_Admin extends Comment_Form_Main {

    public function __construct() {
        parent::__construct();

        add_action('admin_menu', array($this, 'add_menu_item'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * add a menu item unter the comment comments menu
     *
     * @since 1.0.0
     */
    public function add_menu_item() {
        add_comments_page(__('Customize Comment Form', 'commentform'), __('Comment Form', 'commentform'), 'manage_options', 'comment-form-customizer', array($this, 'render_settings_page'));
    }

    /**
     * render the comment form settings page
     *
     * @since 1.0.0
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'comment_form_url_section' );
                do_settings_sections( 'comment-form-customizer' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * initialize settings with Settings API
     *
     * @since 1.0.0
     * @link http://codex.wordpress.org/Settings_API
     */
    public function register_settings() {
        // add sections to comment form settings page
        add_settings_section('comment_form_url_section', __('Commenter URL', 'commentform'), array($this, 'render_url_section_callback'), 'comment-form-customizer');
        add_settings_section('comment_form_notes_section', __('Comment notes', 'commentform'), array($this, 'render_notes_section_callback'), 'comment-form-customizer');

        // add settings fields
        add_settings_field('commentform_settings_hide_url', __('remove url field', 'commentform'), array($this, 'render_hide_url_field_callback'), 'comment-form-customizer', 'comment_form_url_section');
        add_settings_field('commentform_settings_notes_before', __('text before the form', 'commentform'), array($this, 'render_comment_notes_before_callback'), 'comment-form-customizer', 'comment_form_notes_section');
        add_settings_field('commentform_settings_hide_notes_before', __('hide text before the form', 'commentform'), array($this, 'render_hide_notes_before_callback'), 'comment-form-customizer', 'comment_form_notes_section');
        add_settings_field('commentform_settings_hide_notes_after', __('hide text after the form', 'commentform'), array($this, 'render_hide_notes_after_callback'), 'comment-form-customizer', 'comment_form_notes_section');
        add_settings_field('commentform_settings_notes_after', __('text after the form', 'commentform'), array($this, 'render_comment_notes_after_callback'), 'comment-form-customizer', 'comment_form_notes_section');

        // register setting for $_POST handling
        register_setting('comment_form_url_section', 'commentform_settings');
    }

    /**
     * content put at the top of the url section on the comment form settings apge
     *
     * @since 1.0.0
     */
    public function render_url_section_callback() {
        echo '<p>'.__('Customizing the field where a commenter can write his url into.').'</p>';
    }

    /**
     * section to customize comment notes
     *
     * @since 1.0.0
     */
    public function render_notes_section_callback() {
        echo '<p>'.__('Customizing additional notes and texts.').'</p>';
    }

    /**
     * content of the hide url setting
     *
     * @since 1.0.0
     */
    public function render_hide_url_field_callback() {
        echo '<input name="commentform_settings[hide_url]" id="commentform_settings_hide_url" type="checkbox" value="1" class="code" ' . checked(1, $this->options('hide_url'), false) . ' />';
        echo '<p class="description">'.__('Removes the "website" field from the frontend.', 'commentform').'</p>';
        echo '<input name="commentform_settings[hide_url_css]" id="commentform_settings_hide_url_css" type="checkbox" value="1" class="code" ' . checked(1, $this->options('hide_url_css'), false) . ' />';
        echo '<p class="description">'.__('Removes the "website" field with css. Use this only if the method above doesn’t work. This uses "display:none" on the most common css selectors for the url field. The value might still get submitted by bots and tech-savvy users.', 'commentform').'</p>';
    }

    /**
     * remove default text before the comment form
     *
     * @since 1.0.0
     */
    public function render_hide_notes_before_callback() {
        echo '<input name="commentform_settings[hide_notes_before]" id="commentform_settings_hide_notes_before" type="checkbox" value="1" class="code" ' . checked(1, $this->options('hide_notes_before'), false) . ' />';
        echo '<p class="description">'.__('Removes the default text before the comment form. This is currently:', 'commentform').'</p>';

        $req = get_option( 'require_name_email' );
        $required_text = sprintf( ' ' . __('Required fields are marked %s'), '<span class="required">*</span>' );

        echo '<blockquote><i>' . __( 'Your email address will not be published.' ) . ( $req ? $required_text : '' ) . '</i></blockquote>';
    }

    /**
     * remove default text after the comment form
     *
     * @since 1.0.0
     */
    public function render_hide_notes_after_callback() {
        echo '<input name="commentform_settings[hide_notes_after]" id="commentform_settings_hide_notes_after" type="checkbox" value="1" class="code" ' . checked(1, $this->options('hide_notes_after'), false) . ' />';
        echo '<p class="description">'.__('Removes the default text after the comment form. This is currently:', 'commentform').'</p>';

        echo '<blockquote><i>' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ), ' <code>' . allowed_tags() . '</code>' ) . '</i></blockquote>';
    }

    /**
     * add addtional text before the form
     *
     * @since 1.0.0
     */
    public function render_comment_notes_before_callback() {
        echo '<textarea name="commentform_settings[text_before]" id="commentform_settings_text_before">' . $this->options('text_before') . '</textarea>';
        echo '<p class="description">'.__('This text is inserted between the headline and the first output if commenting is allowed to the user.', 'commentform').'</p>';
    }

    /**
     * add addtional text after the form
     *
     * @since 1.0.0
     */
    public function render_comment_notes_after_callback() {
        echo '<textarea name="commentform_settings[text_after]" id="commentform_settings_text_after">' . $this->options('text_after') . '</textarea>';
        echo '<p class="description">'.__('This text is inserted after the form when commenting if commiting is allowed to the user.', 'commentform').'</p>';
    }

}
