<?php

/**
 * Plugin Name: Advanced Comment Form
 * Description: Easily customize and optimize the standard comment form.
 * Version: 1.1.0
 * Plugin URI: http://webgilde.com/
 * Author: Thomas Maier
 * Author URI: http://www.webgilde.com/
 * License: GPL v2 or later
 *

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
//avoid direct calls to this file
if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

define('CFVERSION', '1.1.0');
define('CFDIR', basename(dirname(__FILE__)));
define('CFPATH', plugin_dir_path(__FILE__));

/**
 * load classes
 */
if (!class_exists('Comment_Form_Main')) {
    require_once( plugin_dir_path(__FILE__) . 'inc/comment_form_main.php' );
}

if (is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX )) {
    if (!class_exists('Comment_Form_Admin')) {
        require_once( plugin_dir_path(__FILE__) . 'admin/comment_form_admin.php' );
    }
    $cf_admin = new Comment_Form_Admin();
} elseif (!is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX )) {
    if (!class_exists('Comment_Form_Frontend')) {
        require_once( plugin_dir_path(__FILE__) . 'frontend/comment_form_frontend.php' );
    }
    $cf_frontend = new Comment_Form_Frontend();
}