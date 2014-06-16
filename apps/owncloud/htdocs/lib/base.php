<?php
/**
 * ownCloud
 *
 * @author Frank Karlitschek
 * @copyright 2012 Frank Karlitschek frank@owncloud.org
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

require_once 'public/constants.php';

/**
 * Class that is a namespace for all global OC variables
 * No, we can not put this class in its own file because it is used by
 * OC_autoload!
 */
class OC {
	/**
	 * Associative array for autoloading. classname => filename
	 */
	public static $CLASSPATH = array();
	/**
	 * The installation path for owncloud on the server (e.g. /srv/http/owncloud)
	 */
	public static $SERVERROOT = '';
	/**
	 * the current request path relative to the owncloud root (e.g. files/index.php)
	 */
	private static $SUBURI = '';
	/**
	 * the owncloud root path for http requests (e.g. owncloud/)
	 */
	public static $WEBROOT = '';
	/**
	 * The installation path of the 3rdparty folder on the server (e.g. /srv/http/owncloud/3rdparty)
	 */
	public static $THIRDPARTYROOT = '';
	/**
	 * the root path of the 3rdparty folder for http requests (e.g. owncloud/3rdparty)
	 */
	public static $THIRDPARTYWEBROOT = '';
	/**
	 * The installation path array of the apps folder on the server (e.g. /srv/http/owncloud) 'path' and
	 * web path in 'url'
	 */
	public static $APPSROOTS = array();
	/*
	 * requested app
	 */
	public static $REQUESTEDAPP = '';
	/*
	 * requested file of app
	 */
	public static $REQUESTEDFILE = '';
	/**
	 * check if owncloud runs in cli mode
	 */
	public static $CLI = false;

	/**
	 * @var OC_Router
	 */
	protected static $router = null;

	/**
	 * @var \OC\Session\Session
	 */
	public static $session = null;

	/**
	 * @var \OC\Autoloader $loader
	 */
	public static $loader = null;

	/**
	 * @var \OC\Server
	 */
	public static $server = null;

	public static function initPaths() {
		// calculate the root directories
		OC::$SERVERROOT = str_replace("\\", '/', substr(__DIR__, 0, -4));

		// ensure we can find OC_Config
		set_include_path(
			OC::$SERVERROOT . '/lib' . PATH_SEPARATOR .
			get_include_path()
		);

		OC::$SUBURI = str_replace("\\", "/", substr(realpath($_SERVER["SCRIPT_FILENAME"]), strlen(OC::$SERVERROOT)));
		$scriptName = OC_Request::scriptName();
		if (substr($scriptName, -1) == '/') {
			$scriptName .= 'index.php';
			//make sure suburi follows the same rules as scriptName
			if (substr(OC::$SUBURI, -9) != 'index.php') {
				if (substr(OC::$SUBURI, -1) != '/') {
					OC::$SUBURI = OC::$SUBURI . '/';
				}
				OC::$SUBURI = OC::$SUBURI . 'index.php';
			}
		}

		OC::$WEBROOT = substr($scriptName, 0, strlen($scriptName) - strlen(OC::$SUBURI));

		if (OC::$WEBROOT != '' and OC::$WEBROOT[0] !== '/') {
			OC::$WEBROOT = '/' . OC::$WEBROOT;
		}

		// search the 3rdparty folder
		if (OC_Config::getValue('3rdpartyroot', '') <> '' and OC_Config::getValue('3rdpartyurl', '') <> '') {
			OC::$THIRDPARTYROOT = OC_Config::getValue('3rdpartyroot', '');
			OC::$THIRDPARTYWEBROOT = OC_Config::getValue('3rdpartyurl', '');
		} elseif (file_exists(OC::$SERVERROOT . '/3rdparty')) {
			OC::$THIRDPARTYROOT = OC::$SERVERROOT;
			OC::$THIRDPARTYWEBROOT = OC::$WEBROOT;
		} elseif (file_exists(OC::$SERVERROOT . '/../3rdparty')) {
			OC::$THIRDPARTYWEBROOT = rtrim(dirname(OC::$WEBROOT), '/');
			OC::$THIRDPARTYROOT = rtrim(dirname(OC::$SERVERROOT), '/');
		} else {
			throw new Exception('3rdparty directory not found! Please put the ownCloud 3rdparty'
				. ' folder in the ownCloud folder or the folder above.'
				. ' You can also configure the location in the config.php file.');
		}
		// search the apps folder
		$config_paths = OC_Config::getValue('apps_paths', array());
		if (!empty($config_paths)) {
			foreach ($config_paths as $paths) {
				if (isset($paths['url']) && isset($paths['path'])) {
					$paths['url'] = rtrim($paths['url'], '/');
					$paths['path'] = rtrim($paths['path'], '/');
					OC::$APPSROOTS[] = $paths;
				}
			}
		} elseif (file_exists(OC::$SERVERROOT . '/apps')) {
			OC::$APPSROOTS[] = array('path' => OC::$SERVERROOT . '/apps', 'url' => '/apps', 'writable' => true);
		} elseif (file_exists(OC::$SERVERROOT . '/../apps')) {
			OC::$APPSROOTS[] = array(
				'path' => rtrim(dirname(OC::$SERVERROOT), '/') . '/apps',
				'url' => '/apps',
				'writable' => true
			);
		}

		if (empty(OC::$APPSROOTS)) {
			throw new Exception('apps directory not found! Please put the ownCloud apps folder in the ownCloud folder'
				. ' or the folder above. You can also configure the location in the config.php file.');
		}
		$paths = array();
		foreach (OC::$APPSROOTS as $path) {
			$paths[] = $path['path'];
		}

		// set the right include path
		set_include_path(
			OC::$SERVERROOT . '/lib/private' . PATH_SEPARATOR .
			OC::$SERVERROOT . '/config' . PATH_SEPARATOR .
			OC::$THIRDPARTYROOT . '/3rdparty' . PATH_SEPARATOR .
			implode($paths, PATH_SEPARATOR) . PATH_SEPARATOR .
			get_include_path() . PATH_SEPARATOR .
			OC::$SERVERROOT
		);
	}

	public static function checkConfig() {
		if (file_exists(OC::$SERVERROOT . "/config/config.php")
			and !is_writable(OC::$SERVERROOT . "/config/config.php")
		) {
			$defaults = new OC_Defaults();
			if (self::$CLI) {
				echo "Can't write into config directory!\n";
				echo "This can usually be fixed by giving the webserver write access to the config directory\n";
				echo "\n";
				echo "See " . \OC_Helper::linkToDocs('admin-dir_permissions') . "\n";
				exit;
			} else {
				OC_Template::printErrorPage(
					"Can't write into config directory!",
					'This can usually be fixed by '
					. '<a href="' . \OC_Helper::linkToDocs('admin-dir_permissions') . '" target="_blank">giving the webserver write access to the config directory</a>.'
				);
			}
		}
	}

	public static function checkInstalled() {
		// Redirect to installer if not installed
		if (!OC_Config::getValue('installed', false) && OC::$SUBURI != '/index.php') {
			if (!OC::$CLI) {
				$url = 'http://' . $_SERVER['SERVER_NAME'] . OC::$WEBROOT . '/index.php';
				header("Location: $url");
			}
			exit();
		}
	}

	/*
	* This function adds some security related headers to all requests served via base.php
	* The implementation of this function has to happen here to ensure that all third-party 
	* components (e.g. SabreDAV) also benefit from this headers.
	*/
	public static function addSecurityHeaders() {
		header('X-XSS-Protection: 1; mode=block'); // Enforce browser based XSS filters
		header('X-Content-Type-Options: nosniff'); // Disable sniffing the content type for IE

		// iFrame Restriction Policy
		$xFramePolicy = OC_Config::getValue('xframe_restriction', true);
		if($xFramePolicy) {
			header('X-Frame-Options: Sameorigin'); // Disallow iFraming from other domains
		}

		// Content Security Policy
		// If you change the standard policy, please also change it in config.sample.php
		$policy = OC_Config::getValue('custom_csp_policy',
			'default-src \'self\'; '
			.'script-src \'self\' \'unsafe-eval\'; '
			.'style-src \'self\' \'unsafe-inline\'; '
			.'frame-src *; '
			.'img-src *; '
			.'font-src \'self\' data:; '
			.'media-src *');
		header('Content-Security-Policy:'.$policy);
	}

	public static function checkSSL() {
		// redirect to https site if configured
		if (OC_Config::getValue("forcessl", false)) {
			header('Strict-Transport-Security: max-age=31536000');
			ini_set("session.cookie_secure", "on");
			if (OC_Request::serverProtocol() <> 'https' and !OC::$CLI) {
				$url = "https://" . OC_Request::serverHost() . OC_Request::requestUri();
				header("Location: $url");
				exit();
			}
		} else {
			// Invalidate HSTS headers
			if (OC_Request::serverProtocol() === 'https') {
				header('Strict-Transport-Security: max-age=0');
			}
		}
	}

	public static function checkMaintenanceMode() {
		// Allow ajax update script to execute without being stopped
		if (OC_Config::getValue('maintenance', false) && OC::$SUBURI != '/core/ajax/update.php') {
			// send http status 503
			header('HTTP/1.1 503 Service Temporarily Unavailable');
			header('Status: 503 Service Temporarily Unavailable');
			header('Retry-After: 120');

			// render error page
			$tmpl = new OC_Template('', 'update.user', 'guest');
			$tmpl->printPage();
			die();
		}
	}

	public static function checkSingleUserMode() {
		$user = OC_User::getUserSession()->getUser();
		$group = OC_Group::getManager()->get('admin');
		if ($user && OC_Config::getValue('singleuser', false) && !$group->inGroup($user)) {
			// send http status 503
			header('HTTP/1.1 503 Service Temporarily Unavailable');
			header('Status: 503 Service Temporarily Unavailable');
			header('Retry-After: 120');

			// render error page
			$tmpl = new OC_Template('', 'singleuser.user', 'guest');
			$tmpl->printPage();
			die();
		}
	}

	/**
	 * check if the instance needs to preform an upgrade
	 *
	 * @return bool
	 */
	public static function needUpgrade() {
		if (OC_Config::getValue('installed', false)) {
			$installedVersion = OC_Config::getValue('version', '0.0.0');
			$currentVersion = implode('.', OC_Util::getVersion());
			return version_compare($currentVersion, $installedVersion, '>');
		} else {
			return false;
		}
	}

	public static function checkUpgrade($showTemplate = true) {
		if (self::needUpgrade()) {
			if ($showTemplate && !OC_Config::getValue('maintenance', false)) {
				OC_Config::setValue('theme', '');
				$minimizerCSS = new OC_Minimizer_CSS();
				$minimizerCSS->clearCache();
				$minimizerJS = new OC_Minimizer_JS();
				$minimizerJS->clearCache();
				OC_Util::addScript('config'); // needed for web root
				OC_Util::addScript('update');
				$tmpl = new OC_Template('', 'update.admin', 'guest');
				$tmpl->assign('version', OC_Util::getVersionString());
				$tmpl->printPage();
				exit();
			} else {
				return true;
			}
		}
		return false;
	}

	public static function initTemplateEngine() {
		// Add the stuff we need always
		OC_Util::addScript("jquery-1.10.0.min");
		OC_Util::addScript("jquery-migrate-1.2.1.min");
		OC_Util::addScript("jquery-ui-1.10.0.custom");
		OC_Util::addScript("jquery-showpassword");
		OC_Util::addScript("jquery.infieldlabel");
		OC_Util::addScript("jquery.placeholder");
		OC_Util::addScript("jquery-tipsy");
		OC_Util::addScript("compatibility");
		OC_Util::addScript("jquery.ocdialog");
		OC_Util::addScript("oc-dialogs");
		OC_Util::addScript("js");
		OC_Util::addScript("octemplate");
		OC_Util::addScript("eventsource");
		OC_Util::addScript("config");
		//OC_Util::addScript( "multiselect" );
		OC_Util::addScript('search', 'result');
		OC_Util::addScript('router');
		OC_Util::addScript("oc-requesttoken");

		// avatars
		if (\OC_Config::getValue('enable_avatars', true) === true) {
			\OC_Util::addScript('placeholder');
			\OC_Util::addScript('3rdparty', 'md5/md5.min');
			\OC_Util::addScript('jquery.avatar');
			\OC_Util::addScript('avatar');
		}

		OC_Util::addStyle("styles");
		OC_Util::addStyle("icons");
		OC_Util::addStyle("apps");
		OC_Util::addStyle("fixes");
		OC_Util::addStyle("multiselect");
		OC_Util::addStyle("jquery-ui-1.10.0.custom");
		OC_Util::addStyle("jquery-tipsy");
		OC_Util::addStyle("jquery.ocdialog");
	}

	public static function initSession() {
		// prevents javascript from accessing php session cookies
		ini_set('session.cookie_httponly', '1;');

		// set the cookie path to the ownCloud directory
		$cookie_path = OC::$WEBROOT ? : '/';
		ini_set('session.cookie_path', $cookie_path);

		//set the session object to a dummy session so code relying on the session existing still works
		self::$session = new \OC\Session\Memory('');

		try {
			// set the session name to the instance id - which is unique
			self::$session = new \OC\Session\Internal(OC_Util::getInstanceId());
			// if session cant be started break with http 500 error
		} catch (Exception $e) {
			//show the user a detailed error page
			OC_Response::setStatus(OC_Response::STATUS_INTERNAL_SERVER_ERROR);
			OC_Template::printExceptionErrorPage($e);
		}

		$sessionLifeTime = self::getSessionLifeTime();
		// regenerate session id periodically to avoid session fixation
		if (!self::$session->exists('SID_CREATED')) {
			self::$session->set('SID_CREATED', time());
		} else if (time() - self::$session->get('SID_CREATED') > $sessionLifeTime / 2) {
			session_regenerate_id(true);
			self::$session->set('SID_CREATED', time());
		}

		// session timeout
		if (self::$session->exists('LAST_ACTIVITY') && (time() - self::$session->get('LAST_ACTIVITY') > $sessionLifeTime)) {
			if (isset($_COOKIE[session_name()])) {
				setcookie(session_name(), '', time() - 42000, $cookie_path);
			}
			session_unset();
			session_destroy();
			session_start();
		}

		self::$session->set('LAST_ACTIVITY', time());
	}

	/**
	 * @return int
	 */
	private static function getSessionLifeTime() {
		return OC_Config::getValue('session_lifetime', 60 * 60 * 24);
	}

	/**
	 * @return OC_Router
	 */
	public static function getRouter() {
		if (!isset(OC::$router)) {
			OC::$router = new OC_Router();
			OC::$router->loadRoutes();
		}

		return OC::$router;
	}


	public static function loadAppClassPaths() {
		foreach (OC_APP::getEnabledApps() as $app) {
			$file = OC_App::getAppPath($app) . '/appinfo/classpath.php';
			if (file_exists($file)) {
				require_once $file;
			}
		}
	}


	public static function init() {
		// register autoloader
		require_once __DIR__ . '/autoloader.php';
		self::$loader = new \OC\Autoloader();
		self::$loader->registerPrefix('Doctrine\\Common', 'doctrine/common/lib');
		self::$loader->registerPrefix('Doctrine\\DBAL', 'doctrine/dbal/lib');
		self::$loader->registerPrefix('Symfony\\Component\\Routing', 'symfony/routing');
		self::$loader->registerPrefix('Symfony\\Component\\Console', 'symfony/console');
		self::$loader->registerPrefix('Sabre\\VObject', '3rdparty');
		self::$loader->registerPrefix('Sabre_', '3rdparty');
		self::$loader->registerPrefix('Patchwork', '3rdparty');
		spl_autoload_register(array(self::$loader, 'load'));

		// set some stuff
		//ob_start();
		error_reporting(E_ALL | E_STRICT);
		if (defined('DEBUG') && DEBUG) {
			ini_set('display_errors', 1);
		}
		self::$CLI = (php_sapi_name() == 'cli');

		date_default_timezone_set('UTC');
		ini_set('arg_separator.output', '&amp;');

		// try to switch magic quotes off.
		if (get_magic_quotes_gpc() == 1) {
			ini_set('magic_quotes_runtime', 0);
		}

		//try to configure php to enable big file uploads.
		//this doesn´t work always depending on the webserver and php configuration.
		//Let´s try to overwrite some defaults anyways

		//try to set the maximum execution time to 60min
		@set_time_limit(3600);
		@ini_set('max_execution_time', 3600);
		@ini_set('max_input_time', 3600);

		//try to set the maximum filesize to 10G
		//@ini_set('upload_max_filesize', '10G');
		//@ini_set('post_max_size', '10G');
		@ini_set('file_uploads', '50');

		//copy http auth headers for apache+php-fcgid work around
		if (isset($_SERVER['HTTP_XAUTHORIZATION']) && !isset($_SERVER['HTTP_AUTHORIZATION'])) {
			$_SERVER['HTTP_AUTHORIZATION'] = $_SERVER['HTTP_XAUTHORIZATION'];
		}

		//set http auth headers for apache+php-cgi work around
		if (isset($_SERVER['HTTP_AUTHORIZATION'])
			&& preg_match('/Basic\s+(.*)$/i', $_SERVER['HTTP_AUTHORIZATION'], $matches)
		) {
			list($name, $password) = explode(':', base64_decode($matches[1]), 2);
			$_SERVER['PHP_AUTH_USER'] = strip_tags($name);
			$_SERVER['PHP_AUTH_PW'] = strip_tags($password);
		}

		//set http auth headers for apache+php-cgi work around if variable gets renamed by apache
		if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])
			&& preg_match('/Basic\s+(.*)$/i', $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], $matches)
		) {
			list($name, $password) = explode(':', base64_decode($matches[1]), 2);
			$_SERVER['PHP_AUTH_USER'] = strip_tags($name);
			$_SERVER['PHP_AUTH_PW'] = strip_tags($password);
		}

		self::initPaths();
		if (OC_Config::getValue('instanceid', false)) {
			// \OC\Memcache\Cache has a hidden dependency on
			// OC_Util::getInstanceId() for namespacing. See #5409.
			try {
				self::$loader->setMemoryCache(\OC\Memcache\Factory::createLowLatency('Autoloader'));
			} catch (\Exception $ex) {
			}
		}
		OC_Util::isSetLocaleWorking();

		// set debug mode if an xdebug session is active
		if (!defined('DEBUG') || !DEBUG) {
			if (isset($_COOKIE['XDEBUG_SESSION'])) {
				define('DEBUG', true);
			}
		}

		if (!defined('PHPUNIT_RUN')) {
			if (defined('DEBUG') and DEBUG) {
				OC\Log\ErrorHandler::register(true);
				set_exception_handler(array('OC_Template', 'printExceptionErrorPage'));
			} else {
				OC\Log\ErrorHandler::register();
			}
			OC\Log\ErrorHandler::setLogger(OC_Log::$object);
		}

		// register the stream wrappers
		stream_wrapper_register('fakedir', 'OC\Files\Stream\Dir');
		stream_wrapper_register('static', 'OC\Files\Stream\StaticStream');
		stream_wrapper_register('close', 'OC\Files\Stream\Close');
		stream_wrapper_register('quota', 'OC\Files\Stream\Quota');
		stream_wrapper_register('oc', 'OC\Files\Stream\OC');

		// setup the basic server
		self::$server = new \OC\Server();

		self::initTemplateEngine();
		if (!self::$CLI) {
			self::initSession();
		} else {
			self::$session = new \OC\Session\Memory('');
		}
		self::checkConfig();
		self::checkInstalled();
		self::checkSSL();
		self::addSecurityHeaders();

		$errors = OC_Util::checkServer();
		if (count($errors) > 0) {
			if (self::$CLI) {
				foreach ($errors as $error) {
					echo $error['error'] . "\n";
					echo $error['hint'] . "\n\n";
				}
			} else {
				OC_Response::setStatus(OC_Response::STATUS_SERVICE_UNAVAILABLE);
				OC_Template::printGuestPage('', 'error', array('errors' => $errors));
			}
			exit;
		}

		//try to set the session lifetime
		$sessionLifeTime = self::getSessionLifeTime();
		@ini_set('gc_maxlifetime', (string)$sessionLifeTime);

		// User and Groups
		if (!OC_Config::getValue("installed", false)) {
			self::$session->set('user_id', '');
		}

		OC_User::useBackend(new OC_User_Database());
		OC_Group::useBackend(new OC_Group_Database());

		if (isset($_SERVER['PHP_AUTH_USER']) && self::$session->exists('loginname')
			&& $_SERVER['PHP_AUTH_USER'] !== self::$session->get('loginname')) {
			$sessionUser = self::$session->get('loginname');
			$serverUser = $_SERVER['PHP_AUTH_USER'];
			OC_Log::write('core',
				"Session loginname ($sessionUser) doesn't match SERVER[PHP_AUTH_USER] ($serverUser).",
				OC_Log::WARN);
			OC_User::logout();
		}

		// Load Apps
		// This includes plugins for users and filesystems as well
		global $RUNTIME_NOAPPS;
		global $RUNTIME_APPTYPES;
		if (!$RUNTIME_NOAPPS && !self::checkUpgrade(false)) {
			if ($RUNTIME_APPTYPES) {
				OC_App::loadApps($RUNTIME_APPTYPES);
			} else {
				OC_App::loadApps();
			}
		}

		//setup extra user backends
		OC_User::setupBackends();

		self::registerCacheHooks();
		self::registerFilesystemHooks();
		self::registerPreviewHooks();
		self::registerShareHooks();
		self::registerLogRotate();

		//make sure temporary files are cleaned up
		register_shutdown_function(array('OC_Helper', 'cleanTmp'));

		//parse the given parameters
		self::$REQUESTEDAPP = (isset($_GET['app']) && trim($_GET['app']) != '' && !is_null($_GET['app']) ? OC_App::cleanAppId(strip_tags($_GET['app'])) : OC_Config::getValue('defaultapp', 'files'));
		if (substr_count(self::$REQUESTEDAPP, '?') != 0) {
			$app = substr(self::$REQUESTEDAPP, 0, strpos(self::$REQUESTEDAPP, '?'));
			$param = substr($_GET['app'], strpos($_GET['app'], '?') + 1);
			parse_str($param, $get);
			$_GET = array_merge($_GET, $get);
			self::$REQUESTEDAPP = $app;
			$_GET['app'] = $app;
		}
		self::$REQUESTEDFILE = (isset($_GET['getfile']) ? $_GET['getfile'] : null);
		if (substr_count(self::$REQUESTEDFILE, '?') != 0) {
			$file = substr(self::$REQUESTEDFILE, 0, strpos(self::$REQUESTEDFILE, '?'));
			$param = substr(self::$REQUESTEDFILE, strpos(self::$REQUESTEDFILE, '?') + 1);
			parse_str($param, $get);
			$_GET = array_merge($_GET, $get);
			self::$REQUESTEDFILE = $file;
			$_GET['getfile'] = $file;
		}
		if (!is_null(self::$REQUESTEDFILE)) {
			$subdir = OC_App::getAppPath(OC::$REQUESTEDAPP) . '/' . self::$REQUESTEDFILE;
			$parent = OC_App::getAppPath(OC::$REQUESTEDAPP);
			if (!OC_Helper::issubdirectory($subdir, $parent)) {
				self::$REQUESTEDFILE = null;
				header('HTTP/1.0 404 Not Found');
				exit;
			}
		}

		if (OC_Config::getValue('installed', false) && !self::checkUpgrade(false)) {
			if (OC_Appconfig::getValue('core', 'backgroundjobs_mode', 'ajax') == 'ajax') {
				OC_Util::addScript('backgroundjobs');
			}
		}
	}

	/**
	 * register hooks for the cache
	 */
	public static function registerCacheHooks() {
		if (OC_Config::getValue('installed', false) && !self::needUpgrade()) { //don't try to do this before we are properly setup
			\OCP\BackgroundJob::registerJob('OC\Cache\FileGlobalGC');

			// NOTE: This will be replaced to use OCP
			$userSession = \OC_User::getUserSession();
			$userSession->listen('postLogin', '\OC\Cache\File', 'loginListener');
		}
	}

	/**
	 * register hooks for the cache
	 */
	public static function registerLogRotate() {
		if (OC_Config::getValue('installed', false) && OC_Config::getValue('log_rotate_size', false) && !self::needUpgrade()) {
			//don't try to do this before we are properly setup
			\OCP\BackgroundJob::registerJob('OC\Log\Rotate', OC_Config::getValue("datadirectory", OC::$SERVERROOT . '/data') . '/owncloud.log');
		}
	}

	/**
	 * register hooks for the filesystem
	 */
	public static function registerFilesystemHooks() {
		// Check for blacklisted files
		OC_Hook::connect('OC_Filesystem', 'write', 'OC_Filesystem', 'isBlacklisted');
		OC_Hook::connect('OC_Filesystem', 'rename', 'OC_Filesystem', 'isBlacklisted');
	}

	/**
	 * register hooks for previews
	 */
	public static function registerPreviewHooks() {
		OC_Hook::connect('OC_Filesystem', 'post_write', 'OC\Preview', 'post_write');
		OC_Hook::connect('OC_Filesystem', 'delete', 'OC\Preview', 'post_delete');
		OC_Hook::connect('\OCP\Versions', 'delete', 'OC\Preview', 'post_delete');
		OC_Hook::connect('\OCP\Trashbin', 'delete', 'OC\Preview', 'post_delete');
	}

	/**
	 * register hooks for sharing
	 */
	public static function registerShareHooks() {
		if (\OC_Config::getValue('installed')) {
			OC_Hook::connect('OC_User', 'post_deleteUser', 'OCP\Share', 'post_deleteUser');
			OC_Hook::connect('OC_User', 'post_addToGroup', 'OCP\Share', 'post_addToGroup');
			OC_Hook::connect('OC_User', 'post_removeFromGroup', 'OCP\Share', 'post_removeFromGroup');
			OC_Hook::connect('OC_User', 'post_deleteGroup', 'OCP\Share', 'post_deleteGroup');
		}
	}

	/**
	 * @brief Handle the request
	 */
	public static function handleRequest() {
		// load all the classpaths from the enabled apps so they are available
		// in the routing files of each app
		OC::loadAppClassPaths();

		// Check if ownCloud is installed or in maintenance (update) mode
		if (!OC_Config::getValue('installed', false)) {
			require_once 'core/setup.php';
			exit();
		}

		$host = OC_Request::insecureServerHost();
		// if the host passed in headers isn't trusted
		if (!OC::$CLI
			// overwritehost is always trusted
			&& OC_Request::getOverwriteHost() === null
			&& !OC_Request::isTrustedDomain($host)) {

			header('HTTP/1.1 400 Bad Request');
			header('Status: 400 Bad Request');
			OC_Template::printErrorPage(
				'You are accessing the server from an untrusted domain.',
				'Please contact your administrator. If you are an administrator of this instance, configure the "trusted_domain" setting in config/config.php. An example configuration is provided in config/config.sample.php.'
			);
			return;
		}

		$request = OC_Request::getPathInfo();
		if (substr($request, -3) !== '.js') { // we need these files during the upgrade
			self::checkMaintenanceMode();
			self::checkUpgrade();
		}

		// Test it the user is already authenticated using Apaches AuthType Basic... very usable in combination with LDAP
		OC::tryBasicAuthLogin();

		if (!self::$CLI and (!isset($_GET["logout"]) or ($_GET["logout"] !== 'true'))) {
			try {
				if (!OC_Config::getValue('maintenance', false)) {
					OC_App::loadApps();
				}
				self::checkSingleUserMode();
				OC::getRouter()->match(OC_Request::getRawPathInfo());
				return;
			} catch (Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {
				//header('HTTP/1.0 404 Not Found');
			} catch (Symfony\Component\Routing\Exception\MethodNotAllowedException $e) {
				OC_Response::setStatus(405);
				return;
			}
		}

		$app = OC::$REQUESTEDAPP;
		$file = OC::$REQUESTEDFILE;
		$param = array('app' => $app, 'file' => $file);
		// Handle app css files
		if (substr($file, -3) == 'css') {
			self::loadCSSFile($param);
			return;
		}

		// Handle redirect URL for logged in users
		if (isset($_REQUEST['redirect_url']) && OC_User::isLoggedIn()) {
			$location = OC_Helper::makeURLAbsolute(urldecode($_REQUEST['redirect_url']));

			// Deny the redirect if the URL contains a @
			// This prevents unvalidated redirects like ?redirect_url=:user@domain.com
			if (strpos($location, '@') === false) {
				header('Location: ' . $location);
				return;
			}
		}
		// Handle WebDAV
		if ($_SERVER['REQUEST_METHOD'] == 'PROPFIND') {
			// not allowed any more to prevent people
			// mounting this root directly.
			// Users need to mount remote.php/webdav instead.
			header('HTTP/1.1 405 Method Not Allowed');
			header('Status: 405 Method Not Allowed');
			return;
		}

		// Someone is logged in :
		if (OC_User::isLoggedIn()) {
			OC_App::loadApps();
			OC_User::setupBackends();
			if (isset($_GET["logout"]) and ($_GET["logout"])) {
				if (isset($_COOKIE['oc_token'])) {
					OC_Preferences::deleteKey(OC_User::getUser(), 'login_token', $_COOKIE['oc_token']);
				}
				OC_User::logout();
				header("Location: " . OC::$WEBROOT . '/');
			} else {
				if (is_null($file)) {
					$param['file'] = 'index.php';
				}
				$file_ext = substr($param['file'], -3);
				if ($file_ext != 'php'
					|| !self::loadAppScriptFile($param)
				) {
					header('HTTP/1.0 404 Not Found');
				}
			}
			return;
		}
		// Not handled and not logged in
		self::handleLogin();
	}

	public static function loadAppScriptFile($param) {
		OC_App::loadApps();
		$app = $param['app'];
		$file = $param['file'];
		$app_path = OC_App::getAppPath($app);
		if (OC_App::isEnabled($app) && $app_path !== false) {
			$file = $app_path . '/' . $file;
			unset($app, $app_path);
			if (file_exists($file)) {
				require_once $file;
				return true;
			}
		}
		header('HTTP/1.0 404 Not Found');
		return false;
	}

	public static function loadCSSFile($param) {
		$app = $param['app'];
		$file = $param['file'];
		$app_path = OC_App::getAppPath($app);
		if (file_exists($app_path . '/' . $file)) {
			$app_web_path = OC_App::getAppWebPath($app);
			$filepath = $app_web_path . '/' . $file;
			$minimizer = new OC_Minimizer_CSS();
			$info = array($app_path, $app_web_path, $file);
			$minimizer->output(array($info), $filepath);
		}
	}

	protected static function handleLogin() {
		OC_App::loadApps(array('prelogin'));
		$error = array();

		// auth possible via apache module?
		if (OC::tryApacheAuth()) {
			$error[] = 'apacheauthfailed';
		} // remember was checked after last login
		elseif (OC::tryRememberLogin()) {
			$error[] = 'invalidcookie';
		} // logon via web form
		elseif (OC::tryFormLogin()) {
			$error[] = 'invalidpassword';
			if ( OC_Config::getValue('log_authfailip', false) ) {
				OC_Log::write('core', 'Login failed: user \''.$_POST["user"].'\' , wrong password, IP:'.$_SERVER['REMOTE_ADDR'],
				OC_Log::WARN);
			} else {
				OC_Log::write('core', 'Login failed: user \''.$_POST["user"].'\' , wrong password, IP:set log_authfailip=true in conf',
                                OC_Log::WARN);
			}
		}

		OC_Util::displayLoginPage(array_unique($error));
	}

	protected static function cleanupLoginTokens($user) {
		$cutoff = time() - OC_Config::getValue('remember_login_cookie_lifetime', 60 * 60 * 24 * 15);
		$tokens = OC_Preferences::getKeys($user, 'login_token');
		foreach ($tokens as $token) {
			$time = OC_Preferences::getValue($user, 'login_token', $token);
			if ($time < $cutoff) {
				OC_Preferences::deleteKey($user, 'login_token', $token);
			}
		}
	}

	protected static function tryApacheAuth() {
		$return = OC_User::handleApacheAuth();

		// if return is true we are logged in -> redirect to the default page
		if ($return === true) {
			$_REQUEST['redirect_url'] = \OC_Request::requestUri();
			OC_Util::redirectToDefaultPage();
			exit;
		}

		// in case $return is null apache based auth is not enabled
		return is_null($return) ? false : true;
	}

	protected static function tryRememberLogin() {
		if (!isset($_COOKIE["oc_remember_login"])
			|| !isset($_COOKIE["oc_token"])
			|| !isset($_COOKIE["oc_username"])
			|| !$_COOKIE["oc_remember_login"]
			|| !OC_Util::rememberLoginAllowed()
		) {
			return false;
		}
		OC_App::loadApps(array('authentication'));
		if (defined("DEBUG") && DEBUG) {
			OC_Log::write('core', 'Trying to login from cookie', OC_Log::DEBUG);
		}
		// confirm credentials in cookie
		if (isset($_COOKIE['oc_token']) && OC_User::userExists($_COOKIE['oc_username'])) {
			// delete outdated cookies
			self::cleanupLoginTokens($_COOKIE['oc_username']);
			// get stored tokens
			$tokens = OC_Preferences::getKeys($_COOKIE['oc_username'], 'login_token');
			// test cookies token against stored tokens
			if (in_array($_COOKIE['oc_token'], $tokens, true)) {
				// replace successfully used token with a new one
				OC_Preferences::deleteKey($_COOKIE['oc_username'], 'login_token', $_COOKIE['oc_token']);
				$token = OC_Util::generateRandomBytes(32);
				OC_Preferences::setValue($_COOKIE['oc_username'], 'login_token', $token, time());
				OC_User::setMagicInCookie($_COOKIE['oc_username'], $token);
				// login
				OC_User::setUserId($_COOKIE['oc_username']);
				OC_Util::redirectToDefaultPage();
				// doesn't return
			}
			// if you reach this point you have changed your password
			// or you are an attacker
			// we can not delete tokens here because users may reach
			// this point multiple times after a password change
			OC_Log::write('core', 'Authentication cookie rejected for user ' . $_COOKIE['oc_username'], OC_Log::WARN);
		}
		OC_User::unsetMagicInCookie();
		return true;
	}

	protected static function tryFormLogin() {
		if (!isset($_POST["user"]) || !isset($_POST['password'])) {
			return false;
		}

		OC_App::loadApps();

		//setup extra user backends
		OC_User::setupBackends();

		if (OC_User::login($_POST["user"], $_POST["password"])) {
			// setting up the time zone
			if (isset($_POST['timezone-offset'])) {
				self::$session->set('timezone', $_POST['timezone-offset']);
			}

			$userid = OC_User::getUser();
			self::cleanupLoginTokens($userid);
			if (!empty($_POST["remember_login"])) {
				if (defined("DEBUG") && DEBUG) {
					OC_Log::write('core', 'Setting remember login to cookie', OC_Log::DEBUG);
				}
				$token = OC_Util::generateRandomBytes(32);
				OC_Preferences::setValue($userid, 'login_token', $token, time());
				OC_User::setMagicInCookie($userid, $token);
			} else {
				OC_User::unsetMagicInCookie();
			}
			OC_Util::redirectToDefaultPage();
			exit();
		}
		return true;
	}

	protected static function tryBasicAuthLogin() {
		if (!isset($_SERVER["PHP_AUTH_USER"])
			|| !isset($_SERVER["PHP_AUTH_PW"])
		) {
			return false;
		}
		OC_App::loadApps(array('authentication'));
		if (OC_User::login($_SERVER["PHP_AUTH_USER"], $_SERVER["PHP_AUTH_PW"])) {
			//OC_Log::write('core',"Logged in with HTTP Authentication", OC_Log::DEBUG);
			OC_User::unsetMagicInCookie();
			$_SERVER['HTTP_REQUESTTOKEN'] = OC_Util::callRegister();
		}
		return true;
	}

}

// define runtime variables - unless this already has been done
if (!isset($RUNTIME_NOAPPS)) {
	$RUNTIME_NOAPPS = false;
}

if (!function_exists('get_temp_dir')) {
	function get_temp_dir() {
		if ($temp = ini_get('upload_tmp_dir')) return $temp;
		if ($temp = getenv('TMP')) return $temp;
		if ($temp = getenv('TEMP')) return $temp;
		if ($temp = getenv('TMPDIR')) return $temp;
		$temp = tempnam(__FILE__, '');
		if (file_exists($temp)) {
			unlink($temp);
			return dirname($temp);
		}
		if ($temp = sys_get_temp_dir()) return $temp;

		return null;
	}
}

OC::init();

