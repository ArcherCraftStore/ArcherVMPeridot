<?php
/**
 * Copyright (c) 2012 Frank Karlitschek <frank@owncloud.org>
 *               2013 Bjoern Schiessle <schiessle@owncloud.com>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

/**
 * Versions
 *
 * A class to handle the versioning of files.
 */

namespace OCA\Files_Versions;

class Storage {

	const DEFAULTENABLED=true;
	const DEFAULTMAXSIZE=50; // unit: percentage; 50% of available disk space/quota
	const VERSIONS_ROOT = 'files_versions/';

	// files for which we can remove the versions after the delete operation was successful
	private static $deletedFiles = array();

	private static $max_versions_per_interval = array(
		//first 10sec, one version every 2sec
		1 => array('intervalEndsAfter' => 10,      'step' => 2),
		//next minute, one version every 10sec
		2 => array('intervalEndsAfter' => 60,      'step' => 10),
		//next hour, one version every minute
		3 => array('intervalEndsAfter' => 3600,    'step' => 60),
		//next 24h, one version every hour
		4 => array('intervalEndsAfter' => 86400,   'step' => 3600),
		//next 30days, one version per day
		5 => array('intervalEndsAfter' => 2592000, 'step' => 86400),
		//until the end one version per week
		6 => array('intervalEndsAfter' => -1,      'step' => 604800),
	);

	public static function getUidAndFilename($filename) {
		$uid = \OC\Files\Filesystem::getOwner($filename);
		\OC\Files\Filesystem::initMountPoints($uid);
		if ( $uid != \OCP\User::getUser() ) {
			$info = \OC\Files\Filesystem::getFileInfo($filename);
			$ownerView = new \OC\Files\View('/'.$uid.'/files');
			$filename = $ownerView->getPath($info['fileid']);
		}
		return array($uid, $filename);
	}

	/**
	 * get current size of all versions from a given user
	 *
	 * @param $user user who owns the versions
	 * @return mixed versions size or false if no versions size is stored
	 */
	private static function getVersionsSize($user) {
		$query = \OC_DB::prepare('SELECT `size` FROM `*PREFIX*files_versions` WHERE `user`=?');
		$result = $query->execute(array($user))->fetchAll();

		if ($result) {
			return $result[0]['size'];
		}
		return false;
	}

	/**
	 * write to the database how much space is in use for versions
	 *
	 * @param $user owner of the versions
	 * @param $size size of the versions
	 */
	private static function setVersionsSize($user, $size) {
		if ( self::getVersionsSize($user) === false) {
			$query = \OC_DB::prepare('INSERT INTO `*PREFIX*files_versions` (`size`, `user`) VALUES (?, ?)');
		}else {
			$query = \OC_DB::prepare('UPDATE `*PREFIX*files_versions` SET `size`=? WHERE `user`=?');
		}
		$query->execute(array($size, $user));
	}

	/**
	 * store a new version of a file.
	 */
	public static function store($filename) {
		if(\OCP\Config::getSystemValue('files_versions', Storage::DEFAULTENABLED)=='true') {

			// if the file gets streamed we need to remove the .part extension
			// to get the right target
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			if ($ext === 'part') {
				$filename = substr($filename, 0, strlen($filename)-5);
			}

			list($uid, $filename) = self::getUidAndFilename($filename);

			$files_view = new \OC\Files\View('/'.$uid .'/files');
			$users_view = new \OC\Files\View('/'.$uid);

			// check if filename is a directory
			if($files_view->is_dir($filename)) {
				return false;
			}

			// we should have a source file to work with, and the file shouldn't
			// be empty
			$fileExists = $files_view->file_exists($filename);
			if (!($fileExists && $files_view->filesize($filename) > 0)) {
				return false;
			}

			// create all parent folders
			self::createMissingDirectories($filename, $users_view);

			$versionsSize = self::getVersionsSize($uid);
			if (  $versionsSize === false || $versionsSize < 0 ) {
				$versionsSize = self::calculateSize($uid);
			}

			// assumption: we need filesize($filename) for the new version +
			// some more free space for the modified file which might be
			// 1.5 times as large as the current version -> 2.5
			$neededSpace = $files_view->filesize($filename) * 2.5;

			$versionsSize = self::expire($filename, $versionsSize, $neededSpace);

			// disable proxy to prevent multiple fopen calls
			$proxyStatus = \OC_FileProxy::$enabled;
			\OC_FileProxy::$enabled = false;

			// store a new version of a file
			$mtime = $users_view->filemtime('files'.$filename);
			$users_view->copy('files'.$filename, 'files_versions'.$filename.'.v'. $mtime);
			// call getFileInfo to enforce a file cache entry for the new version
			$users_view->getFileInfo('files_versions'.$filename.'.v'.$mtime);

			// reset proxy state
			\OC_FileProxy::$enabled = $proxyStatus;

			$versionsSize += $users_view->filesize('files'.$filename);

			self::setVersionsSize($uid, $versionsSize);
		}
	}


	/**
	 * @brief mark file as deleted so that we can remove the versions if the file is gone
	 * @param string $path
	 */
	public static function markDeletedFile($path) {
		list($uid, $filename) = self::getUidAndFilename($path);
		self::$deletedFiles[$path] = array(
			'uid' => $uid,
			'filename' => $filename);
	}

	/**
	 * Delete versions of a file
	 */
	public static function delete($path) {

		$deletedFile = self::$deletedFiles[$path];
		$uid = $deletedFile['uid'];
		$filename = $deletedFile['filename'];

		if (!\OC\Files\Filesystem::file_exists($path)) {

			$versions_fileview = new \OC\Files\View('/' . $uid . '/files_versions');

			$abs_path = $versions_fileview->getLocalFile($filename . '.v');
			$versions = self::getVersions($uid, $filename);
			if (!empty($versions)) {
				$versionsSize = self::getVersionsSize($uid);
				if ($versionsSize === false || $versionsSize < 0) {
					$versionsSize = self::calculateSize($uid);
				}
				foreach ($versions as $v) {
					unlink($abs_path . $v['version']);
					\OC_Hook::emit('\OCP\Versions', 'delete', array('path' => $abs_path . $v['version']));
					$versionsSize -= $v['size'];
				}
				self::setVersionsSize($uid, $versionsSize);
			}
		}
		unset(self::$deletedFiles[$path]);
	}

	/**
	 * rename versions of a file
	 */
	public static function rename($old_path, $new_path) {
		list($uid, $oldpath) = self::getUidAndFilename($old_path);
		list($uidn, $newpath) = self::getUidAndFilename($new_path);
		$versions_view = new \OC\Files\View('/'.$uid .'/files_versions');
		$files_view = new \OC\Files\View('/'.$uid .'/files');

		// if the file already exists than it was a upload of a existing file
		// over the web interface -> store() is the right function we need here
		if ($files_view->file_exists($newpath)) {
			return self::store($new_path);
		}

		self::expire($newpath);

		if ( $files_view->is_dir($oldpath) && $versions_view->is_dir($oldpath) ) {
			$versions_view->rename($oldpath, $newpath);
		} else  if ( ($versions = Storage::getVersions($uid, $oldpath)) ) {
			// create missing dirs if necessary
			self::createMissingDirectories($newpath, new \OC\Files\View('/'. $uidn));

			foreach ($versions as $v) {
				$versions_view->rename($oldpath.'.v'.$v['version'], $newpath.'.v'.$v['version']);
			}
		}
	}

	/**
	 * rollback to an old version of a file.
	 */
	public static function rollback($file, $revision) {

		if(\OCP\Config::getSystemValue('files_versions', Storage::DEFAULTENABLED)=='true') {
			list($uid, $filename) = self::getUidAndFilename($file);
			$users_view = new \OC\Files\View('/'.$uid);
			$files_view = new \OC\Files\View('/'.\OCP\User::getUser().'/files');
			$versionCreated = false;

			//first create a new version
			$version = 'files_versions'.$filename.'.v'.$users_view->filemtime('files'.$filename);
			if ( !$users_view->file_exists($version)) {

				// disable proxy to prevent multiple fopen calls
				$proxyStatus = \OC_FileProxy::$enabled;
				\OC_FileProxy::$enabled = false;

				$users_view->copy('files'.$filename, 'files_versions'.$filename.'.v'.$users_view->filemtime('files'.$filename));

				// reset proxy state
				\OC_FileProxy::$enabled = $proxyStatus;

				$versionCreated = true;
			}

			// rollback
			if( @$users_view->rename('files_versions'.$filename.'.v'.$revision, 'files'.$filename) ) {
				$files_view->touch($file, $revision);
				Storage::expire($file);
				return true;

			}else if ( $versionCreated ) {
				$users_view->unlink($version);
			}
		}
		return false;

	}


	/**
	 * @brief get a list of all available versions of a file in descending chronological order
	 * @param string $uid user id from the owner of the file
	 * @param string $filename file to find versions of, relative to the user files dir
	 * @param string $userFullPath
	 * @returns array versions newest first
	 */
	public static function getVersions($uid, $filename, $userFullPath = '') {
		$versions = array();
		// fetch for old versions
		$view = new \OC\Files\View('/' . $uid . '/' . self::VERSIONS_ROOT);

		$pathinfo = pathinfo($filename);

		$files = $view->getDirectoryContent($pathinfo['dirname']);

		$versionedFile = $pathinfo['basename'];

		foreach ($files as $file) {
			if ($file['type'] === 'file') {
				$pos = strrpos($file['path'], '.v');
				$currentFile = substr($file['name'], 0, strrpos($file['name'], '.v'));
				if ($currentFile === $versionedFile) {
					$version = substr($file['path'], $pos + 2);
					$key = $version . '#' . $filename;
					$versions[$key]['cur'] = 0;
					$versions[$key]['version'] = $version;
					$versions[$key]['humanReadableTimestamp'] = self::getHumanReadableTimestamp($version);
					if (empty($userFullPath)) {
						$versions[$key]['preview'] = '';
					} else {
						$versions[$key]['preview'] = \OCP\Util::linkToRoute('core_ajax_versions_preview', array('file' => $userFullPath, 'version' => $version));
					}
					$versions[$key]['path'] = $filename;
					$versions[$key]['name'] = $versionedFile;
					$versions[$key]['size'] = $file['size'];
				}
			}
		}

		// sort with newest version first
		krsort($versions);

		return $versions;
	}

	/**
	 * @brief translate a timestamp into a string like "5 days ago"
	 * @param int $timestamp
	 * @return string for example "5 days ago"
	 */
	private static function getHumanReadableTimestamp($timestamp) {

		$diff = time() - $timestamp;

		if ($diff < 60) { // first minute
			return  $diff . " seconds ago";
		} elseif ($diff < 3600) { //first hour
			return round($diff / 60) . " minutes ago";
		} elseif ($diff < 86400) { // first day
			return round($diff / 3600) . " hours ago";
		} elseif ($diff < 604800) { //first week
			return round($diff / 86400) . " days ago";
		} elseif ($diff < 2419200) { //first month
			return round($diff / 604800) . " weeks ago";
		} elseif ($diff < 29030400) { // first year
			return round($diff / 2419200) . " months ago";
		} else {
			return round($diff / 29030400) . " years ago";
		}

	}

	/**
	 * @brief deletes used space for files versions in db if user was deleted
	 *
	 * @param type $uid id of deleted user
	 * @return result of db delete operation
	 */
	public static function deleteUser($uid) {
		$query = \OC_DB::prepare('DELETE FROM `*PREFIX*files_versions` WHERE `user`=?');
		return $query->execute(array($uid));
	}

	/**
	 * @brief get the size of all stored versions from a given user
	 * @param $uid id from the user
	 * @return size of vesions
	 */
	private static function calculateSize($uid) {
		if (\OCP\Config::getSystemValue('files_versions', Storage::DEFAULTENABLED) == 'true') {
			$view = new \OC\Files\View('/' . $uid . '/files_versions');

			$size = 0;

			$dirContent = $view->getDirectoryContent('/');

			while (!empty($dirContent)) {
				$path = reset($dirContent);
				if ($path['type'] === 'dir') {
					$dirContent = array_merge($dirContent, $view->getDirectoryContent(substr($path['path'], strlen('files_versions'))));
				} else {
					$size += $view->filesize(substr($path['path'], strlen('files_versions')));
				}
				unset($dirContent[key($dirContent)]);
			}

			return $size;
		}
	}

	/**
	 * @brief returns all stored file versions from a given user
	 * @param $uid id to the user
	 * @return array with contains two arrays 'all' which contains all versions sorted by age and 'by_file' which contains all versions sorted by filename
	 */
	private static function getAllVersions($uid) {
		$view = new \OC\Files\View('/' . $uid . '/');
		$dirs = array(self::VERSIONS_ROOT);

		while (!empty($dirs)) {
			$dir = array_pop($dirs);
			$files = $view->getDirectoryContent($dir);

			foreach ($files as $file) {
				if ($file['type'] === 'dir') {
					array_push($dirs, $file['path']);
				} else {
					$versionsBegin = strrpos($file['path'], '.v');
					$relPathStart = strlen(self::VERSIONS_ROOT);
					$version = substr($file['path'], $versionsBegin + 2);
					$relpath = substr($file['path'], $relPathStart, $versionsBegin - $relPathStart);
					$key = $version . '#' . $relpath;
					$versions[$key] = array('path' => $relpath, 'timestamp' => $version);
				}
			}
		}

		// newest first
		krsort($versions);

		$result = array();

		foreach ($versions as $key => $value) {
			$size = $view->filesize(self::VERSIONS_ROOT.'/'.$value['path'].'.v'.$value['timestamp']);
			$filename = $value['path'];

			$result['all'][$key]['version'] = $value['timestamp'];
			$result['all'][$key]['path'] = $filename;
			$result['all'][$key]['size'] = $size;

			$result['by_file'][$filename][$key]['version'] = $value['timestamp'];
			$result['by_file'][$filename][$key]['path'] = $filename;
			$result['by_file'][$filename][$key]['size'] = $size;
		}

		return $result;
	}

	/**
	 * @brief Erase a file's versions which exceed the set quota
	 */
	private static function expire($filename, $versionsSize = null, $offset = 0) {
		if(\OCP\Config::getSystemValue('files_versions', Storage::DEFAULTENABLED)=='true') {
			list($uid, $filename) = self::getUidAndFilename($filename);
			$versionsFileview = new \OC\Files\View('/'.$uid.'/files_versions');

			// get available disk space for user
			$softQuota = true;
			$quota = \OC_Preferences::getValue($uid, 'files', 'quota');
			if ( $quota === null || $quota === 'default') {
				$quota = \OC_Appconfig::getValue('files', 'default_quota');
			}
			if ( $quota === null || $quota === 'none' ) {
				$quota = \OC\Files\Filesystem::free_space('/');
				$softQuota = false;
			} else {
				$quota = \OCP\Util::computerFileSize($quota);
			}

			// make sure that we have the current size of the version history
			if ( $versionsSize === null ) {
				$versionsSize = self::getVersionsSize($uid);
				if (  $versionsSize === false || $versionsSize < 0 ) {
					$versionsSize = self::calculateSize($uid);
				}
			}

			// calculate available space for version history
			// subtract size of files and current versions size from quota
			if ($softQuota) {
				$files_view = new \OC\Files\View('/'.$uid.'/files');
				$rootInfo = $files_view->getFileInfo('/', false);
				$free = $quota-$rootInfo['size']; // remaining free space for user
				if ( $free > 0 ) {
					$availableSpace = ($free * self::DEFAULTMAXSIZE / 100) - ($versionsSize + $offset); // how much space can be used for versions
				} else {
					$availableSpace = $free - $versionsSize - $offset;
				}
			} else {
				$availableSpace = $quota - $offset;
			}


			// with the  probability of 0.1% we reduce the number of all versions not only for the current file
			$random = rand(0, 1000);
			if ($random == 0) {
				$allFiles = true;
			} else {
				$allFiles = false;
			}

			$allVersions = Storage::getVersions($uid, $filename);
			$versionsByFile[$filename] = $allVersions;

			$sizeOfDeletedVersions = self::delOldVersions($versionsByFile, $allVersions, $versionsFileview);
			$availableSpace = $availableSpace + $sizeOfDeletedVersions;
			$versionsSize = $versionsSize - $sizeOfDeletedVersions;

			// if still not enough free space we rearrange the versions from all files
			if ($availableSpace <= 0 || $allFiles) {
				$result = Storage::getAllVersions($uid);
				$versionsByFile = $result['by_file'];
				$allVersions = $result['all'];

				$sizeOfDeletedVersions = self::delOldVersions($versionsByFile, $allVersions, $versionsFileview);
				$availableSpace = $availableSpace + $sizeOfDeletedVersions;
				$versionsSize = $versionsSize - $sizeOfDeletedVersions;
			}

			// Check if enough space is available after versions are rearranged.
			// If not we delete the oldest versions until we meet the size limit for versions,
			// but always keep the two latest versions
			$numOfVersions = count($allVersions) -2 ;
			$i = 0;
			while ($availableSpace < 0 && $i < $numOfVersions) {
				$version = current($allVersions);
				$versionsFileview->unlink($version['path'].'.v'.$version['version']);
				\OC_Hook::emit('\OCP\Versions', 'delete', array('path' => $version['path'].'.v'.$version['version']));
				$versionsSize -= $version['size'];
				$availableSpace += $version['size'];
				next($allVersions);
				$i++;
			}

			return $versionsSize; // finally return the new size of the version history
		}

		return false;
	}

	/**
	 * @brief delete old version from a given list of versions
	 *
	 * @param array $versionsByFile list of versions ordered by files
	 * @param array $allVversions all versions across multiple files
	 * @param $versionsFileview \OC\Files\View on data/user/files_versions
	 * @return size of releted versions
	 */
	private static function delOldVersions($versionsByFile, &$allVersions, $versionsFileview) {

		$time = time();
		$size = 0;

		// delete old versions for every given file
		foreach ($versionsByFile as $versions) {

			$interval = 1;
			$step = Storage::$max_versions_per_interval[$interval]['step'];
			if (Storage::$max_versions_per_interval[$interval]['intervalEndsAfter'] == -1) {
				$nextInterval = -1;
			} else {
				$nextInterval = $time - Storage::$max_versions_per_interval[$interval]['intervalEndsAfter'];
			}

			$firstVersion = reset($versions);
			$firstKey = key($versions);
			$prevTimestamp = $firstVersion['version'];
			$nextVersion = $firstVersion['version'] - $step;
			unset($versions[$firstKey]);

			foreach ($versions as $key => $version) {
				$newInterval = true;
				while ($newInterval) {
					if ($nextInterval == -1 || $prevTimestamp > $nextInterval) {
						if ($version['version'] > $nextVersion) {
							//distance between two version too small, delete version
							\OC_Hook::emit('\OCP\Versions', 'delete', array('path' => $version['path'] . '.v' . $version['version']));
							$versionsFileview->unlink($version['path'] . '.v' . $version['version']);
							$size += $version['size'];
							unset($allVersions[$key]); // update array with all versions
						} else {
							$nextVersion = $version['version'] - $step;
							$prevTimestamp = $version['version'];
						}
						$newInterval = false; // version checked so we can move to the next one
					} else { // time to move on to the next interval
						$interval++;
						$step = Storage::$max_versions_per_interval[$interval]['step'];
						$nextVersion = $prevTimestamp - $step;
						if (Storage::$max_versions_per_interval[$interval]['intervalEndsAfter'] == -1) {
							$nextInterval = -1;
						} else {
							$nextInterval = $time - Storage::$max_versions_per_interval[$interval]['intervalEndsAfter'];
						}
						$newInterval = true; // we changed the interval -> check same version with new interval
					}
				}
			}
		}
		return $size;
	}

	/**
	 * @brief create recursively missing directories
	 * @param string $filename $path to a file
	 * @param \OC\Files\View $view view on data/user/
	 */
	private static function createMissingDirectories($filename, $view) {
		$dirname = \OC_Filesystem::normalizePath(dirname($filename));
		$dirParts = explode('/', $dirname);
		$dir = "/files_versions";
		foreach ($dirParts as $part) {
			$dir = $dir . '/' . $part;
			if (!$view->file_exists($dir)) {
				$view->mkdir($dir);
			}
		}
	}

}
