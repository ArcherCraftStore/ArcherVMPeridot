<?php
/**
 * Copyright (c) 2012 Bart Visscher <bartv@thisnet.nl>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

// Post installation check
$this->create('post_setup_check', '/post-setup-check')
	->action('OC_Setup', 'postSetupCheck');

// Core ajax actions
// Search
$this->create('search_ajax_search', '/search/ajax/search.php')
	->actionInclude('search/ajax/search.php');
// AppConfig
$this->create('core_ajax_appconfig', '/core/ajax/appconfig.php')
	->actionInclude('core/ajax/appconfig.php');
// Share
$this->create('core_ajax_share', '/core/ajax/share.php')
	->actionInclude('core/ajax/share.php');
// Translations
$this->create('core_ajax_translations', '/core/ajax/translations.php')
	->actionInclude('core/ajax/translations.php');
// Tags
$this->create('core_tags_tags', '/tags/{type}')
	->get()
	->action('OC\Core\Tags\Controller', 'getTags')
	->requirements(array('type'));
$this->create('core_tags_favorites', '/tags/{type}/favorites')
	->get()
	->action('OC\Core\Tags\Controller', 'getFavorites')
	->requirements(array('type'));
$this->create('core_tags_ids_for_tag', '/tags/{type}/ids')
	->get()
	->action('OC\Core\Tags\Controller', 'getIdsForTag')
	->requirements(array('type'));
$this->create('core_tags_favorite', '/tags/{type}/favorite/{id}/')
	->post()
	->action('OC\Core\Tags\Controller', 'favorite')
	->requirements(array('type', 'id'));
$this->create('core_tags_unfavorite', '/tags/{type}/unfavorite/{id}/')
	->post()
	->action('OC\Core\Tags\Controller', 'unFavorite')
	->requirements(array('type', 'id'));
$this->create('core_tags_tag', '/tags/{type}/tag/{id}/')
	->post()
	->action('OC\Core\Tags\Controller', 'tagAs')
	->requirements(array('type', 'id'));
$this->create('core_tags_untag', '/tags/{type}/untag/{id}/')
	->post()
	->action('OC\Core\Tags\Controller', 'unTag')
	->requirements(array('type', 'id'));
$this->create('core_tags_add', '/tags/{type}/add')
	->post()
	->action('OC\Core\Tags\Controller', 'addTag')
	->requirements(array('type'));
$this->create('core_tags_delete', '/tags/{type}/delete')
	->post()
	->action('OC\Core\Tags\Controller', 'deleteTags')
	->requirements(array('type'));
// oC JS config
$this->create('js_config', '/core/js/config.js')
	->actionInclude('core/js/config.php');
// Routing
$this->create('core_ajax_routes', '/core/routes.json')
	->action('OC_Router', 'JSRoutes');
$this->create('core_ajax_preview', '/core/preview.png')
	->actionInclude('core/ajax/preview.php');
$this->create('core_lostpassword_index', '/lostpassword/')
	->get()
	->action('OC\Core\LostPassword\Controller', 'index');
$this->create('core_lostpassword_send_email', '/lostpassword/')
	->post()
	->action('OC\Core\LostPassword\Controller', 'sendEmail');
$this->create('core_lostpassword_reset', '/lostpassword/reset/{token}/{user}')
	->get()
	->action('OC\Core\LostPassword\Controller', 'reset');
$this->create('core_lostpassword_reset_password', '/lostpassword/reset/{token}/{user}')
	->post()
	->action('OC\Core\LostPassword\Controller', 'resetPassword');

// Avatar routes
$this->create('core_avatar_get_tmp', '/avatar/tmp')
	->get()
	->action('OC\Core\Avatar\Controller', 'getTmpAvatar');
$this->create('core_avatar_get', '/avatar/{user}/{size}')
	->get()
	->action('OC\Core\Avatar\Controller', 'getAvatar');
$this->create('core_avatar_post', '/avatar/')
	->post()
	->action('OC\Core\Avatar\Controller', 'postAvatar');
$this->create('core_avatar_delete', '/avatar/')
	->delete()
	->action('OC\Core\Avatar\Controller', 'deleteAvatar');
$this->create('core_avatar_post_cropped', '/avatar/cropped')
	->post()
	->action('OC\Core\Avatar\Controller', 'postCroppedAvatar');

// Not specifically routed
$this->create('app_css', '/apps/{app}/{file}')
	->requirements(array('file' => '.*.css'))
	->action('OC', 'loadCSSFile');
$this->create('app_index_script', '/apps/{app}/')
	->defaults(array('file' => 'index.php'))
	//->requirements(array('file' => '.*.php'))
	->action('OC', 'loadAppScriptFile');
$this->create('app_script', '/apps/{app}/{file}')
	->defaults(array('file' => 'index.php'))
	->requirements(array('file' => '.*.php'))
	->action('OC', 'loadAppScriptFile');

// used for heartbeat
$this->create('heartbeat', '/heartbeat')->action(function(){
	// do nothing
});
