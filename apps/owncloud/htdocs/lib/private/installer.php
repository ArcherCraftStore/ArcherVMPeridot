<?php
/**
 * ownCloud
 *
 * @author Robin Appelman
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

/**
 * This class provides the functionality needed to install, update and remove plugins/apps
 */
class OC_Installer{
	/**
	 * @brief Installs an app
	 * @param $data array with all information
	 * @throws \Exception
	 * @returns integer
	 *
	 * This function installs an app. All information needed are passed in the
	 * associative array $data.
	 * The following keys are required:
	 *   - source: string, can be "path" or "http"
	 *
	 * One of the following keys is required:
	 *   - path: path to the file containing the app
	 *   - href: link to the downloadable file containing the app
	 *
	 * The following keys are optional:
	 *   - pretend: boolean, if set true the system won't do anything
	 *   - noinstall: boolean, if true appinfo/install.php won't be loaded
	 *   - inactive: boolean, if set true the appconfig/app.sample.php won't be
	 *     renamed
	 *
	 * This function works as follows
	 *   -# fetching the file
	 *   -# unzipping it
	 *   -# check the code
	 *   -# installing the database at appinfo/database.xml
	 *   -# including appinfo/install.php
	 *   -# setting the installed version
	 *
	 * It is the task of oc_app_install to create the tables and do whatever is
	 * needed to get the app working.
	 */
	public static function installApp( $data = array()) {
		$l = \OC_L10N::get('lib');

		if(!isset($data['source'])) {
			throw new \Exception($l->t("No source specified when installing app"));
		}

		//download the file if necesary
		if($data['source']=='http') {
			$path=OC_Helper::tmpFile();
			if(!isset($data['href'])) {
				throw new \Exception($l->t("No href specified when installing app from http"));
			}
			copy($data['href'], $path);
		}else{
			if(!isset($data['path'])) {
				throw new \Exception($l->t("No path specified when installing app from local file"));
			}
			$path=$data['path'];
		}

		//detect the archive type
		$mime=OC_Helper::getMimeType($path);
		if($mime=='application/zip') {
			rename($path, $path.'.zip');
			$path.='.zip';
		}elseif($mime=='application/x-gzip') {
			rename($path, $path.'.tgz');
			$path.='.tgz';
		}else{
			throw new \Exception($l->t("Archives of type %s are not supported", array($mime)));
		}

		//extract the archive in a temporary folder
		$extractDir=OC_Helper::tmpFolder();
		OC_Helper::rmdirr($extractDir);
		mkdir($extractDir);
		if($archive=OC_Archive::open($path)) {
			$archive->extract($extractDir);
		} else {
			OC_Helper::rmdirr($extractDir);
			if($data['source']=='http') {
				unlink($path);
			}
			throw new \Exception($l->t("Failed to open archive when installing app"));
		}

		//load the info.xml file of the app
		if(!is_file($extractDir.'/appinfo/info.xml')) {
			//try to find it in a subdir
			$dh=opendir($extractDir);
			if(is_resource($dh)) {
				while (($folder = readdir($dh)) !== false) {
					if($folder[0]!='.' and is_dir($extractDir.'/'.$folder)) {
						if(is_file($extractDir.'/'.$folder.'/appinfo/info.xml')) {
							$extractDir.='/'.$folder;
						}
					}
				}
			}
		}
		if(!is_file($extractDir.'/appinfo/info.xml')) {
			OC_Helper::rmdirr($extractDir);
			if($data['source']=='http') {
				unlink($path);
			}
			throw new \Exception($l->t("App does not provide an info.xml file"));
		}
		$info=OC_App::getAppInfo($extractDir.'/appinfo/info.xml', true);
		// check the code for not allowed calls
		if(!OC_Installer::checkCode($info['id'], $extractDir)) {
			OC_Helper::rmdirr($extractDir);
			throw new \Exception($l->t("App can't be installed because of not allowed code in the App"));
		}

		// check if the app is compatible with this version of ownCloud
		if(
			!isset($info['require'])
				or !OC_App::isAppVersionCompatible(OC_Util::getVersion(), $info['require'])
		) {
			OC_Helper::rmdirr($extractDir);
			throw new \Exception($l->t("App can't be installed because it is not compatible with this version of ownCloud"));
		}

		// check if shipped tag is set which is only allowed for apps that are shipped with ownCloud
		if(isset($info['shipped']) and ($info['shipped']=='true')) {
			OC_Helper::rmdirr($extractDir);
			throw new \Exception($l->t("App can't be installed because it contains the <shipped>true</shipped> tag which is not allowed for non shipped apps"));
		}

		// check if the ocs version is the same as the version in info.xml/version
		$versionFile= $extractDir.'/appinfo/version';
		if(is_file($versionFile)) {
			$version = trim(file_get_contents($versionFile));
		}else{
			$version = trim($info['version']);
		}
		
		if($version<>trim($data['appdata']['version'])) {
			OC_Helper::rmdirr($extractDir);
			throw new \Exception($l->t("App can't be installed because the version in info.xml/version is not the same as the version reported from the app store"));
		}

		$basedir=OC_App::getInstallPath().'/'.$info['id'];
		//check if the destination directory already exists
		if(is_dir($basedir)) {
			OC_Helper::rmdirr($extractDir);
			if($data['source']=='http') {
				unlink($path);
			}
			throw new \Exception($l->t("App directory already exists"));
		}

		if(isset($data['pretent']) and $data['pretent']==true) {
			return false;
		}

		//copy the app to the correct place
		if(@!mkdir($basedir)) {
			OC_Helper::rmdirr($extractDir);
			if($data['source']=='http') {
				unlink($path);
			}
			throw new \Exception($l->t("Can't create app folder. Please fix permissions. %s", array($basedir)));
		}
		OC_Helper::copyr($extractDir, $basedir);

		//remove temporary files
		OC_Helper::rmdirr($extractDir);

		//install the database
		if(is_file($basedir.'/appinfo/database.xml')) {
			if (OC_Appconfig::getValue($info['id'], 'installed_version') === null) {
				OC_DB::createDbFromStructure($basedir.'/appinfo/database.xml');
			} else {
				OC_DB::updateDbFromStructure($basedir.'/appinfo/database.xml');
			}
		}

		//run appinfo/install.php
		if((!isset($data['noinstall']) or $data['noinstall']==false) and file_exists($basedir.'/appinfo/install.php')) {
			include $basedir.'/appinfo/install.php';
		}

		//set the installed version
		OC_Appconfig::setValue($info['id'], 'installed_version', OC_App::getAppVersion($info['id']));
		OC_Appconfig::setValue($info['id'], 'enabled', 'no');

		//set remote/public handelers
		foreach($info['remote'] as $name=>$path) {
			OCP\CONFIG::setAppValue('core', 'remote_'.$name, $info['id'].'/'.$path);
		}
		foreach($info['public'] as $name=>$path) {
			OCP\CONFIG::setAppValue('core', 'public_'.$name, $info['id'].'/'.$path);
		}

		OC_App::setAppTypes($info['id']);

		return $info['id'];
	}

	/**
	 * @brief checks whether or not an app is installed
	 * @param $app app
	 * @returns true/false
	 *
	 * Checks whether or not an app is installed, i.e. registered in apps table.
	 */
	public static function isInstalled( $app ) {

		if( null == OC_Appconfig::getValue( $app, "installed_version" )) {
			return false;
		}

		return true;
	}

	/**
	 * @brief Update an application
	 * @param $data array with all information
	 *
	 * This function installs an app. All information needed are passed in the
	 * associative array $data.
	 * The following keys are required:
	 *   - source: string, can be "path" or "http"
	 *
	 * One of the following keys is required:
	 *   - path: path to the file containing the app
	 *   - href: link to the downloadable file containing the app
	 *
	 * The following keys are optional:
	 *   - pretend: boolean, if set true the system won't do anything
	 *   - noupgrade: boolean, if true appinfo/upgrade.php won't be loaded
	 *
	 * This function works as follows
	 *   -# fetching the file
	 *   -# removing the old files
	 *   -# unzipping new file
	 *   -# including appinfo/upgrade.php
	 *   -# setting the installed version
	 *
	 * upgrade.php can determine the current installed version of the app using
	 * "OC_Appconfig::getValue($appid, 'installed_version')"
	 */
	public static function updateApp( $app ) {
		$ocsid=OC_Appconfig::getValue( $app, 'ocsid');
		OC_App::disable($app);
		OC_App::enable($ocsid);
		return(true);
	}

	/**
	 * @brief Check if an update for the app is available
	 * @param $name name of the application
	 * @return boolean false or the version number of the update
	 *
	 * The function will check if an update for a version is available
	 */
	public static function isUpdateAvailable( $app ) {
		$ocsid=OC_Appconfig::getValue( $app, 'ocsid', '');

		if($ocsid<>'') {

			$ocsdata=OC_OCSClient::getApplication($ocsid);
			$ocsversion= (string) $ocsdata['version'];
			$currentversion=OC_App::getAppVersion($app);
			if($ocsversion<>$currentversion) {
				return($ocsversion);

			}else{
				return false;
			}

		}else{
			return false;
		}

	}

	/**
	 * @brief Check if app is already downloaded
	 * @param $name name of the application to remove
	 * @returns true/false
	 *
	 * The function will check if the app is already downloaded in the apps repository
	 */
	public static function isDownloaded( $name ) {

		$downloaded=false;
		foreach(OC::$APPSROOTS as $dir) {
			if(is_dir($dir['path'].'/'.$name)) $downloaded=true;
		}
		return($downloaded);
	}

	/**
	 * @brief Removes an app
	 * @param $name name of the application to remove
	 * @param $options array with options
	 * @returns true/false
	 *
	 * This function removes an app. $options is an associative array. The
	 * following keys are optional:ja
	 *   - keeppreferences: boolean, if true the user preferences won't be deleted
	 *   - keepappconfig: boolean, if true the config will be kept
	 *   - keeptables: boolean, if true the database will be kept
	 *   - keepfiles: boolean, if true the user files will be kept
	 *
	 * This function works as follows
	 *   -# including appinfo/remove.php
	 *   -# removing the files
	 *
	 * The function will not delete preferences, tables and the configuration,
	 * this has to be done by the function oc_app_uninstall().
	 */
	public static function removeApp( $name, $options = array()) {

		if(isset($options['keeppreferences']) and $options['keeppreferences']==false ) {
			// todo
			// remove preferences
		}

		if(isset($options['keepappconfig']) and $options['keepappconfig']==false ) {
			// todo
			// remove app config
		}

		if(isset($options['keeptables']) and $options['keeptables']==false ) {
			// todo
			// remove app database tables
		}

		if(isset($options['keepfiles']) and $options['keepfiles']==false ) {
			// todo
			// remove user files
		}

		if(OC_Installer::isDownloaded( $name )) {
			$appdir=OC_App::getInstallPath().'/'.$name;
			OC_Helper::rmdirr($appdir);

		}else{
			OC_Log::write('core', 'can\'t remove app '.$name.'. It is not installed.', OC_Log::ERROR);

		}

	}

	/**
	 * @brief Installs shipped apps
	 *
	 * This function installs all apps found in the 'apps' directory that should be enabled by default;
	 */
	public static function installShippedApps() {
		foreach(OC::$APPSROOTS as $app_dir) {
			if($dir = opendir( $app_dir['path'] )) {
				while( false !== ( $filename = readdir( $dir ))) {
					if( substr( $filename, 0, 1 ) != '.' and is_dir($app_dir['path']."/$filename") ) {
						if( file_exists( $app_dir['path']."/$filename/appinfo/app.php" )) {
							if(!OC_Installer::isInstalled($filename)) {
								$info=OC_App::getAppInfo($filename);
								$enabled = isset($info['default_enable']);
								if( $enabled ) {
									OC_Installer::installShippedApp($filename);
									OC_Appconfig::setValue($filename, 'enabled', 'yes');
								}
							}
						}
					}
				}
				closedir( $dir );
			}
		}
	}

	/**
	 * install an app already placed in the app folder
	 * @param string $app id of the app to install
	 * @returns array see OC_App::getAppInfo
	 */
	public static function installShippedApp($app) {
		//install the database
		if(is_file(OC_App::getAppPath($app)."/appinfo/database.xml")) {
			OC_DB::createDbFromStructure(OC_App::getAppPath($app)."/appinfo/database.xml");
		}

		//run appinfo/install.php
		if(is_file(OC_App::getAppPath($app)."/appinfo/install.php")) {
			include OC_App::getAppPath($app)."/appinfo/install.php";
		}
		$info=OC_App::getAppInfo($app);
		OC_Appconfig::setValue($app, 'installed_version', OC_App::getAppVersion($app));

		//set remote/public handelers
		foreach($info['remote'] as $name=>$path) {
			OCP\CONFIG::setAppValue('core', 'remote_'.$name, $app.'/'.$path);
		}
		foreach($info['public'] as $name=>$path) {
			OCP\CONFIG::setAppValue('core', 'public_'.$name, $app.'/'.$path);
		}

		OC_App::setAppTypes($info['id']);

		return $info['id'];
	}


	/**
	 * check the code of an app with some static code checks
	 * @param string $folder the folder of the app to check
	 * @returns true for app is o.k. and false for app is not o.k.
	 */
	public static function checkCode($appname, $folder) {

		$blacklist=array(
			'exec(',
			'eval(',
			// more evil pattern will go here later

			// classes replaced by the public api
			'OC_API::',
			'OC_App::',
			'OC_AppConfig::',
			'OC_Avatar',
			'OC_BackgroundJob::',
			'OC_Config::',
			'OC_DB::',
			'OC_Files::',
			'OC_Helper::',
			'OC_Hook::',
			'OC_Image::',
			'OC_JSON::',
			'OC_L10N::',
			'OC_Log::',
			'OC_Mail::',
			'OC_Preferences::',
			'OC_Request::',
			'OC_Response::',
			'OC_Template::',
			'OC_User::',
			'OC_Util::',
		);

		// is the code checker enabled?
		if(OC_Config::getValue('appcodechecker', false)) {

			// check if grep is installed
			$grep = exec('which grep');
			if($grep=='') {
				OC_Log::write('core',
					'grep not installed. So checking the code of the app "'.$appname.'" was not possible',
					OC_Log::ERROR);
				return true;
			}

			// iterate the bad patterns
			foreach($blacklist as $bl) {
				$cmd = 'grep -ri '.escapeshellarg($bl).' '.$folder.'';
				$result = exec($cmd);
				// bad pattern found
				if($result<>'') {
					OC_Log::write('core',
						'App "'.$appname.'" is using a not allowed call "'.$bl.'". Installation refused.',
						OC_Log::ERROR);
					return false;
				}
			}
			return true;

		}else{
			return true;
		}
	}
}
