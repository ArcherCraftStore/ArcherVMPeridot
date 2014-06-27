var orbisius_child_theme_creator = {
    /**
     * This is a file name that the user enters. It will be cleaned of spaces and repeating chars.
     * @param str val
     * @returns str
     */
    sanitize_file_name : function (val) {
        val = val.replace(/[^\w-.\s]/ig, '');
        val = val.replace(/\s+/ig, '-');
        val = val.replace(/\.+/ig, '.');
        val = val.replace(/-+/ig, '-');
        val = val.replace(/_+/ig, '_');

        // rm leading/trailing chars
        val = val.replace(/^[._-]+/ig, '');
        val = val.replace(/[._-]+$/ig, '');
        
        val = jQuery.trim(val);

        return val;
    },

    /**
     * Deletes a file by sending an ajax request.
     * The file should be the currently selected one from the dropdown menu.
     * After the ajax call finishes the selected element is removed from the
     * dropdown and a trigger event is triggered so the content box gets
     * reloaded/refilled with new content.
     *
     * @param str file_name
     * @param str form_id
     * @returns void
     */
    delete_file : function(file_name, form_id) {
        jQuery.ajax({
            type : "post",
            url : ajaxurl, // WP defines it and it contains all the necessary params
            data : jQuery(form_id).serialize() + '&action=orbisius_ctc_theme_editor_ajax&sub_cmd=' + escape('delete_file'),

            success : function (result) {
                var form_num = form_id.indexOf('#theme_1') >= 0 ? 1 : 2;

                // theme_1_ or theme_2_
                jQuery("#theme_" + form_num + "_file option:selected").remove();
                jQuery("#theme_" + form_num + "_file").trigger('change');
            }
        });
    }
};

jQuery(document).ready(function($) {
    orbisius_ctc_theme_editor_setup();
});

/**
 * This is called when on doc ready.
 * It setups the actoins that we want to handle e.g. what happens when
 * somebody selects something from the dropdowns.
 * @returns {undefined}
 */
function orbisius_ctc_theme_editor_setup() {
    var $ = jQuery;

    var current_theme_dir = $('#theme_1').val();

    if (current_theme_dir != '') {
        // prefill dropdown files with the current theme's files.
        app_load('#orbisius_ctc_theme_editor_theme_1_form', 'generate_dropdown', '#theme_1_file', app_handle_theme_change);
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////
    // Delete File #1
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    $('#theme_1_delete_file_btn').on("click", function () {
        var selected_file = $('#theme_1_file').val();

        if (confirm('Delete: [' + selected_file + '] ? Are you sure?', '')) {
            orbisius_child_theme_creator.delete_file(selected_file, '#orbisius_ctc_theme_editor_theme_1_form');
        }
    });
    ///////////////////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////////////////////////////////////
    // Delete File #2
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    $('#theme_2_delete_file_btn').on("click", function () {
        var selected_file = $('#theme_2_file').val();

        if (confirm('Delete: [' + selected_file + '] ? Are you sure?', '')) {
            orbisius_child_theme_creator.delete_file(selected_file, '#orbisius_ctc_theme_editor_theme_2_form');
        }
    });
    ///////////////////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////////////////////////////////////
    // New File #1
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    $('#theme_1_new_file_btn').on("click", function () {
        $('#theme_1_new_file_container').toggle('slow');
        $('#theme_1_new_file').focus();
    });

    // The user enters a file name. Let's check if it exists.
    $('#theme_1_new_file').on("input", function (event) {
        var new_file = $('#theme_1_new_file').val();
        new_file = orbisius_child_theme_creator.sanitize_file_name(new_file);

        var ok = 1; // let's by positive by default

        // Let's check if that file exists by checking if there is an entry in the options
        $("#theme_1_file option").each(function() { // idx, val
            var cur_val = $(this).val();

            if (cur_val == new_file) {
                ok = 0;
                return ;
            }
        });

        if (ok) {
            $('.status', $('#theme_1_new_file_container')).text('').removeClass('app-alert-error');
        } else {
            var err = 'File with that name already exists.';
            $('.status', $('#theme_1_new_file_container')).text(err).addClass('app-alert-error');
        }
    });

    /**
     * New File: On OK this will hide the form but will add a new element
     * to the theme files dropdown.
     */
    $('#theme_1_new_file_btn_ok').on("click", function () {
        var val = $('#theme_1_new_file').val();
        val = orbisius_child_theme_creator.sanitize_file_name(val);
        var text = val;

        if (val == '') {
            alert('Invalid or empty value for filename.');
            $('#theme_1_new_file').focus();
            return ;
        }

        var ok = 1; // let's by positive by default
        
        // Let's check if that file exists by checking if there is an entry in the options
        $("#theme_1_file option").each(function() { // idx, val
            var cur_val = $(this).val();

            if (cur_val == val) {
                ok = 0;
                return ;
            }
        });

        if (!ok) {
            alert('File with that name already exists.');
            $('#theme_1_new_file').focus();
            return ;
        }

        // unselects current element from the dropdown.
        $("select theme_1_file").prop("selected", false);
        var new_option = $('<option></option>').val(val).html(text).prop("selected", true);

        $('#theme_1_file').append( new_option ); // select
        $('#theme_1_new_file_container').hide('slow');
        $('#theme_1_new_file').val(''); // text box for new file
        $('#theme_1_file_contents').val('').focus(); // textarea
    });

    // This is when the cancel button is clicked so the user doesn't want a new file.
    $('#theme_1_new_file_btn_cancel').on("click", function () {
        $('#theme_1_new_file').val('');
        $('#theme_1_new_file_container').hide('slow');
        $('.status', $('#theme_1_new_file_container')).text('').removeClass('app-alert-error');
    });
    ///////////////////////////////////////////////////////////////////////////////////////////////////


    ///////////////////////////////////////////////////////////////////////////////////////////////////
    // New File #2
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    $('#theme_2_new_file_btn').on("click", function () {
        $('#theme_2_new_file_container').toggle('slow');
        $('#theme_2_new_file').focus();
    });

    // The user enters a file name. Let's check if it exists.
    $('#theme_2_new_file').on("input", function (event) {
        var new_file = $('#theme_2_new_file').val();
        new_file = orbisius_child_theme_creator.sanitize_file_name(new_file);

        var ok = 1; // let's by positive by default

        // Let's check if that file exists by checking if there is an entry in the options
        $("#theme_2_file option").each(function() { // idx, val
            var cur_val = $(this).val();

            if (cur_val == new_file) {
                ok = 0;
                return ;
            }
        });

        if (ok) {
            $('.status', $('#theme_2_new_file_container')).text('').removeClass('app-alert-error');
        } else {
            var err = 'File with that name already exists.';
            $('.status', $('#theme_2_new_file_container')).text(err).addClass('app-alert-error');
        }
    });

    /**
     * New File: On OK this will hide the form but will add a new element
     * to the theme files dropdown.
     */
    $('#theme_2_new_file_btn_ok').on("click", function () {
        var val = $('#theme_2_new_file').val();
        val = orbisius_child_theme_creator.sanitize_file_name(val);
        var text = val;

        if (val == '') {
            alert('Invalid or empty value for filename.');
            $('#theme_2_new_file').focus();
            return ;
        }

        var ok = 1; // let's by positive by default

        // Let's check if that file exists by checking if there is an entry in the options
        $("#theme_2_file option").each(function() { // idx, val
            var cur_val = $(this).val();

            if (cur_val == val) {
                ok = 0;
                return ;
            }
        });

        if (!ok) {
            alert('File with that name already exists.');
            $('#theme_2_new_file').focus();
            return ;
        }

        // unselects current element from the dropdown.
        $("select theme_2_file").prop("selected", false);
        var new_option = $('<option></option>').val(val).html(text).prop("selected", true);

        $('#theme_2_file').append( new_option ); // select
        $('#theme_2_new_file_container').hide('slow');
        $('#theme_2_new_file').val(''); // text box for new file
        $('#theme_2_file_contents').val('').focus(); // textarea
    });

    // This is when the cancel button is clicked so the user doesn't want a new file.
    $('#theme_2_new_file_btn_cancel').on("click", function () {
        $('#theme_2_new_file').val('');
        $('#theme_2_new_file_container').hide('slow');
        $('.status', $('#theme_2_new_file_container')).text('').removeClass('app-alert-error');
    });
    ///////////////////////////////////////////////////////////////////////////////////////////////////


    ///////////////////////////////////////////////////////////////////////////////////////////////////
    // Syntax check button #1
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    $('#theme_1_syntax_chk_btn').on("click", function () {
        var form_id = '#orbisius_ctc_theme_editor_theme_1_form';
        var action = 'syntax_check';
        var target_container = '.orbisius_ctc_theme_editor_theme_1_primary_buttons .status';

        jQuery(target_container)
                .empty()
                .removeClass('app-alert-success app-alert-error app-alert-notice')
                .addClass( 'app-alert-notice')
                .html('Checking ...');

        jQuery.ajax({
            type : "post",
            //dataType : "json",
            url : ajaxurl, // WP defines it and it contains all the necessary params
            data : jQuery(form_id).serialize() + '&action=orbisius_ctc_theme_editor_ajax&sub_cmd=' + escape(action),

            success : function (json) {
                jQuery(target_container)
                        .empty()
                        .removeClass('app-alert-notice')
                        .html(json.msg)
                        .addClass( json.status ? 'app-alert-success' : 'app-alert-error' );

                if (json.status) { // auto hide on success
                    setTimeout(function () {
                        jQuery(target_container).empty().removeClass('app-alert-success app-alert-error');
                    }, 2000);
                }
            } // success
        }); // ajax
    }); // click
    ///////////////////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////////////////////////////////////
    // Syntax check button #2
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    $('#theme_2_syntax_chk_btn').on("click", function () {
        var form_id = '#orbisius_ctc_theme_editor_theme_2_form';
        var action = 'syntax_check';
        var target_container = '.orbisius_ctc_theme_editor_theme_2_primary_buttons .status';

        jQuery(target_container)
                .empty()
                .removeClass('app-alert-success app-alert-error app-alert-notice')
                .addClass( 'app-alert-notice')
                .html('Checking ...');

        jQuery.ajax({
            type : "post",
            //dataType : "json",
            url : ajaxurl, // WP defines it and it contains all the necessary params
            data : jQuery(form_id).serialize() + '&action=orbisius_ctc_theme_editor_ajax&sub_cmd=' + escape(action),

            success : function (json) {
                jQuery(target_container)
                        .empty()
                        .removeClass('app-alert-notice')
                        .html(json.msg)
                        .addClass( json.status ? 'app-alert-success' : 'app-alert-error' );

                if (json.status) { // auto hide on success
                    setTimeout(function () {
                        jQuery(target_container).empty().removeClass('app-alert-success app-alert-error');
                    }, 2000);
                }
            } // success
        }); // ajax
    }); // click
    ///////////////////////////////////////////////////////////////////////////////////////////////////


    ///////////////////////////////////////////////////////////////////////////////////////////////////
    // Send #1
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    $('#theme_1_send_btn').on("click", function () {
        $('#theme_1_send_container').toggle('slow');
        $('#theme_1_send_to').focus();
    });

    // This is when the cancel button is clicked so the user doesn't want a new folder.
    $('#theme_1_send_btn_cancel').on("click", function () {
        //$('#theme_1_send_to').val(''); //??
        $('#theme_1_send_container').hide('slow');
    });

    $('#theme_1_send_btn_ok').on("click", function () {
        var to = jQuery('#theme_1_send_to').val().trim();

        if (to.indexOf('@') == -1 || to.indexOf('.') < 1) {
            alert('Invalid email(s).');
            $('#theme_1_send_to').focus();
            return;
        }

        var form_id = '#orbisius_ctc_theme_editor_theme_1_form';
        var action = 'send_theme';
        var target_container = '.orbisius_ctc_theme_editor_theme_1_primary_buttons .status';

        jQuery(target_container)
                .empty()
                .removeClass('app-alert-success app-alert-error app-alert-notice')
                .addClass( 'app-alert-notice')
                .html('Processing ...');

        jQuery.ajax({
            type : "post",
            //dataType : "json",
            url : ajaxurl, // WP defines it and it contains all the necessary params
            data : jQuery(form_id).serialize() + '&action=orbisius_ctc_theme_editor_ajax&sub_cmd=' + escape(action),

            success : function (json) {
                jQuery(target_container)
                        .empty()
                        .removeClass('app-alert-notice')
                        .html(json.msg)
                        .addClass( json.status ? 'app-alert-success' : 'app-alert-error' );

                if (json.status) { // auto hide on success
                    setTimeout(function () {
                        jQuery(target_container).empty().removeClass('app-alert-success app-alert-error');
                        $('#theme_1_send_btn_cancel').click();
                    }, 2000);
                }
            } // success
        }); // ajax
    }); // click
    ///////////////////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////////////////////////////////////
    // Send #2
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    $('#theme_2_send_btn').on("click", function () {
        $('#theme_2_send_container').toggle('slow');
        $('#theme_2_send_to').focus();
    });

    // This is when the cancel button is clicked so the user doesn't want a new folder.
    $('#theme_2_send_btn_cancel').on("click", function () {
        //$('#theme_2_send_to').val(''); //??
        $('#theme_2_send_container').hide('slow');
    });

    $('#theme_2_send_btn_ok').on("click", function () {
        var to = jQuery('#theme_2_send_to').val().trim();

        if (to.indexOf('@') == -1 || to.indexOf('.') < 1) {
            alert('Invalid email(s).');
            $('#theme_2_send_to').focus();
            return;
        }

        var form_id = '#orbisius_ctc_theme_editor_theme_2_form';
        var action = 'send_theme';
        var target_container = '.orbisius_ctc_theme_editor_theme_2_primary_buttons .status';

        jQuery(target_container)
                .empty()
                .removeClass('app-alert-success app-alert-error app-alert-notice')
                .addClass( 'app-alert-notice')
                .html('Processing ...');

        jQuery.ajax({
            type : "post",
            //dataType : "json",
            url : ajaxurl, // WP defines it and it contains all the necessary params
            data : jQuery(form_id).serialize() + '&action=orbisius_ctc_theme_editor_ajax&sub_cmd=' + escape(action),

            success : function (json) {
                jQuery(target_container)
                        .empty()
                        .removeClass('app-alert-notice')
                        .html(json.msg)
                        .addClass( json.status ? 'app-alert-success' : 'app-alert-error' );

                if (json.status) { // auto hide on success
                    setTimeout(function () {
                        jQuery(target_container).empty().removeClass('app-alert-success app-alert-error');
                        $('#theme_2_send_btn_cancel').click();
                    }, 2000);
                }
            } // success
        }); // ajax
    }); // click
    ///////////////////////////////////////////////////////////////////////////////////////////////////


    ///////////////////////////////////////////////////////////////////////////////////////////////////
    // New Folder
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    $('#theme_1_new_folder_btn').on("click", function () {
        $('#theme_1_new_folder_container').toggle('slow');
        $('#theme_1_new_folder').focus();
    });

    $('#theme_2_new_folder_btn').on("click", function () {
        $('#theme_2_new_folder_container').toggle('slow');
        $('#theme_2_new_folder').focus();
    });

    // The user enters a folder name. Let's check if it exists.
    $('#theme_1_new_folder').on("input", function (event) {
        var new_folder = $('#theme_1_new_folder').val();
        new_folder = orbisius_child_theme_creator.sanitize_file_name(new_folder);

        var ok = 1; // let's by positive by default

        // Let's check if that folder exists by checking if there is an entry in the options
        $("#theme_1_file option").each(function() { // idx, val
            var cur_val = $(this).val();

            if (cur_val == new_folder) {
                ok = 0;
                return ;
            }
        });

        if (ok) {
            $('.status', $('#theme_1_new_folder_container')).text('').removeClass('app-alert-error');
        } else {
            var err = 'File/folder with that name already exists.';
            $('.status', $('#theme_1_new_folder_container')).text(err).addClass('app-alert-error');
        }
    });

    // The user enters a folder name. Let's check if it exists.
    $('#theme_2_new_folder').on("input", function (event) {
        var new_folder = $('#theme_2_new_folder').val();
        new_folder = orbisius_child_theme_creator.sanitize_file_name(new_folder);

        var ok = 1; // let's by positive by default

        // Let's check if that folder exists by checking if there is an entry in the options
        $("#theme_2_file option").each(function() { // idx, val
            var cur_val = $(this).val();

            if (cur_val == new_folder) {
                ok = 0;
                return ;
            }
        });

        if (ok) {
            $('.status', $('#theme_2_new_folder_container')).text('').removeClass('app-alert-error');
        } else {
            var err = 'File/folder with that name already exists.';
            $('.status', $('#theme_2_new_folder_container')).text(err).addClass('app-alert-error');
        }
    });

    /**
     * New Folder: On OK this will hide the form but will add a new element
     * to the theme files dropdown.
     * @TODO
     */
    $('#theme_1_new_folder_btn_ok').on("click", function () {
        var val = $('#theme_1_new_folder').val();
        val = orbisius_child_theme_creator.sanitize_file_name(val);
        var text = val;

        if (val == '') {
            alert('Invalid or empty value for folder name.');
            $('#theme_1_new_folder').focus();
            return ;
        }

        var ok = 1; // let's by positive by default

        // Let's check if that file exists by checking if there is an entry in the options
        $("#theme_1_folder option").each(function() { // idx, val
            var cur_val = $(this).val();

            if (cur_val == val) {
                ok = 0;
                return ;
            }
        });

        if (!ok) {
            alert('File with that name already exists.');
            $('#theme_1_new_folder').focus();
            return ;
        }

        // unselects current element from the dropdown.
        $("select theme_1_folder").prop("selected", false);
        var new_option = $('<option></option>').val(val).html(text).prop("selected", true);

        $('#theme_1_folder').append( new_option ); // select
        $('#theme_1_new_folder_container').hide('slow');
        $('#theme_1_new_folder').val(''); // text box for new folder
        $('#theme_1_folder_contents').val('').focus(); // textarea
    });

    // This is when the cancel button is clicked so the user doesn't want a new folder.
    $('#theme_1_new_folder_btn_cancel').on("click", function () {
        $('#theme_1_new_folder').val('');
        $('#theme_1_new_folder_container').hide('slow');
        $('.status', $('#theme_1_new_folder_container')).text('').removeClass('app-alert-error');
    });
    ///////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * New Folder: On OK this will hide the form but will add a new element
     * to the theme files dropdown.
     * @TODO
     */
    $('#theme_2_new_folder_btn_ok').on("click", function () {
        var val = $('#theme_2_new_folder').val();
        val = orbisius_child_theme_creator.sanitize_file_name(val);
        var text = val;

        if (val == '') {
            alert('Invalid or empty value for folder name.');
            $('#theme_2_new_folder').focus();
            return ;
        }

        var ok = 1; // let's by positive by default

        // Let's check if that file exists by checking if there is an entry in the options
        $("#theme_2_folder option").each(function() { // idx, val
            var cur_val = $(this).val();

            if (cur_val == val) {
                ok = 0;
                return ;
            }
        });

        if (!ok) {
            alert('File with that name already exists.');
            $('#theme_2_new_folder').focus();
            return ;
        }

        // unselects current element from the dropdown.
        $("select theme_2_folder").prop("selected", false);
        var new_option = $('<option></option>').val(val).html(text).prop("selected", true);

        $('#theme_2_folder').append( new_option ); // select
        $('#theme_2_new_folder_container').hide('slow');
        $('#theme_2_new_folder').val(''); // text box for new folder
        $('#theme_2_folder_contents').val('').focus(); // textarea
    });

    // This is when the cancel button is clicked so the user doesn't want a new folder.
    $('#theme_2_new_folder_btn_cancel').on("click", function () {
        $('#theme_2_new_folder').val('');
        $('#theme_2_new_folder_container').hide('slow');
        $('.status', $('#theme_2_new_folder_container')).text('').removeClass('app-alert-error');
    });
    ///////////////////////////////////////////////////////////////////////////////////////////////////

    // Change theme selection
    $('#theme_1').on("change", function () {
        app_load('#orbisius_ctc_theme_editor_theme_1_form', 'generate_dropdown', '#theme_1_file', app_handle_theme_change);
    });

    // Change theme selection
    $('#theme_2').on("change", function () {
        app_load('#orbisius_ctc_theme_editor_theme_2_form', 'generate_dropdown', '#theme_2_file', app_handle_theme_change);
    });

    $('#orbisius_ctc_theme_editor_theme_1_form').submit(function () {
        app_load('#orbisius_ctc_theme_editor_theme_1_form', 'save_file', '#theme_1_file_contents');

        return false;
    });

    var current_theme_dir = $('#theme_2').val();

    if (current_theme_dir != '') {
        app_load('#orbisius_ctc_theme_editor_theme_2_form', 'generate_dropdown', '#theme_2_file', app_handle_theme_change);
    }

    // Change theme selection
    $('#theme_2').on("change", function () {
        app_load('#orbisius_ctc_theme_editor_theme_2_form', 'generate_dropdown', '#theme_2_file', app_handle_theme_change);
    });

    $('#orbisius_ctc_theme_editor_theme_2_form').submit(function () {
        app_load('#orbisius_ctc_theme_editor_theme_2_form', 'save_file', '#theme_2_file_contents');

        return false;
    });
}

/**
 * When the theme is selected we need to check if there is a file selected so we can load it.
 * When the file dropdown is changed/selected we'll load the selected file.
 *
 * @returns void
 */
function app_handle_theme_change(form_id, action, target_container, result) {
    var form_prefix = jQuery(form_id) ? jQuery(form_id).attr('id') : ''; // orbisius_ctc_theme_editor_theme_1_form
    form_prefix = form_prefix || '';

    if (form_prefix == '') {
        return;
    }

    form_prefix = form_prefix.replace(/.+(theme[-_]*\d+).*/, '$1');
    form_prefix = '#' + form_prefix + '_'; // jQuery ID prefix. Res: #theme_2_

    // Let's notify the extensions (if any)
    if ( (typeof OrbisiusChildThemeCreatorExt != 'undefined')
            && (typeof OrbisiusChildThemeCreatorExt.Editors != 'undefined')
            && (typeof OrbisiusChildThemeCreatorExt.Editors.onThemeChange != 'undefined') )
        {
            var dropdown_id = form_prefix;
            var dropdown_id = dropdown_id.replace(/_+$/g, ''); // rm trailing stuff
            //var dropdown_id = dropdown_id.replace(/\#+/g, ''); // rm #
            OrbisiusChildThemeCreatorExt.Editors.onThemeChange(dropdown_id, jQuery(dropdown_id).val());
    }

    var cur_file = jQuery(form_prefix + '_file').val();

    if (cur_file !== '') {
        app_load(form_id, 'load_file', form_prefix + 'file_contents');
    }

    jQuery(form_prefix + 'file').on("change", function () {
        app_load(form_id, 'load_file', form_prefix + 'file_contents');
    });
}

/**
 * Sends ajax call to WP. Different requests append sub_cmd because WP is using key: 'action'.
 * Depending on the target element a different method for setting the value is used.
 *
 * @param {type} form_id
 * @param {type} action
 * @param {type} target_container
 * @param {type} callback
 * @returns {undefined}
 */
function app_load(form_id, action, target_container, callback) {
    var loading_text = '<span class="app-alert-notice">Loading...</span>';
    var loading_text_just_text = 'Loading...'; // used in textarea, select etc.
    var undo_readonly = 0;
    var is_save_action = action.indexOf('save') >= 0;

    if (is_save_action) { // save action
        if (jQuery(target_container).is("input,textarea")) {
            jQuery(target_container).attr('readonly', 'readonly');
            jQuery(target_container).addClass('saving_action');
        }

        jQuery('.status', jQuery(target_container).parent()).html(loading_text);
    } else {
        if (jQuery(target_container).is("input,textarea")) {
            jQuery(target_container).val(loading_text_just_text);
            jQuery(target_container).addClass('saving_action');
        } else if (jQuery(target_container).is("select")) { // for loading. we want to override options of the select
            jQuery(target_container + ' option').text(loading_text_just_text);
        } else {
            jQuery(target_container).html(loading_text);
        }
    }

    jQuery.ajax({
        type : "post",
        //dataType : "json",
        url : ajaxurl, // WP defines it and it contains all the necessary params
        data : jQuery(form_id).serialize() + '&action=orbisius_ctc_theme_editor_ajax&sub_cmd=' + escape(action),

        success : function (result) {
            // http://stackoverflow.com/questions/2432749/jquery-delay-not-delaying
            if (result != '') {
                if (jQuery(target_container).is("input,textarea")) {
                    jQuery(target_container).val(result);
                } else {
                    jQuery(target_container).html(result);
                }

                if (is_save_action) { // save action
                    jQuery('.status', jQuery(target_container).parent()).html('Saved.').addClass('app-alert-success');

                    setTimeout(function () {
                        jQuery('.status', jQuery(target_container).parent()).empty().removeClass('app-alert-success app-alert-error');
                    }, 2000);
                }
            } else if (is_save_action) { // save action
                jQuery('.status', jQuery(target_container).parent()).html('Oops. Cannot save.').addClass('app-alert-error');
            }

            if (typeof callback != 'undefined') {
                callback(form_id, action, target_container, result);
            }
        },

        complete : function (result) { // this is always called
            jQuery(target_container).removeClass('saving_action');

            if (is_save_action) { // save action
                if (jQuery(target_container).is("input,textarea")) {
                    jQuery(target_container).removeAttr('readonly');
                }
            }
        }
    });
}
