<?php
// Load other apps for file previews
OC_App::loadApps();

if (\OC_Appconfig::getValue('core', 'shareapi_allow_links', 'yes') !== 'yes') {
	header('HTTP/1.0 404 Not Found');
	$tmpl = new OCP\Template('', '404', 'guest');
	$tmpl->printPage();
	exit();
}

function fileCmp($a, $b) {
	if ($a['type'] == 'dir' and $b['type'] != 'dir') {
		return -1;
	} elseif ($a['type'] != 'dir' and $b['type'] == 'dir') {
		return 1;
	} else {
		return strnatcasecmp($a['name'], $b['name']);
	}
}

function determineIcon($file, $sharingRoot, $sharingToken) {
	// for folders we simply reuse the files logic
	if($file['type'] == 'dir') {
		return \OCA\Files\Helper::determineIcon($file);
	}

	$relativePath = substr($file['path'], 6);
	$relativePath = substr($relativePath, strlen($sharingRoot));
	if($file['isPreviewAvailable']) {
		return OCP\publicPreview_icon($relativePath, $sharingToken) . '&c=' . $file['etag'];
	}
	return OCP\mimetype_icon($file['mimetype']);
}

if (isset($_GET['t'])) {
	$token = $_GET['t'];
	$linkItem = OCP\Share::getShareByToken($token, false);
	if (is_array($linkItem) && isset($linkItem['uid_owner'])) {
		// seems to be a valid share
		$type = $linkItem['item_type'];
		$fileSource = $linkItem['file_source'];
		$shareOwner = $linkItem['uid_owner'];
		$path = null;
		$rootLinkItem = OCP\Share::resolveReShare($linkItem);
		if (isset($rootLinkItem['uid_owner'])) {
			OCP\JSON::checkUserExists($rootLinkItem['uid_owner']);
			OC_Util::tearDownFS();
			OC_Util::setupFS($rootLinkItem['uid_owner']);
			$path = \OC\Files\Filesystem::getPath($linkItem['file_source']);
		}
	}
}
if (isset($path)) {
	if (!isset($linkItem['item_type'])) {
		OCP\Util::writeLog('share', 'No item type set for share id: ' . $linkItem['id'], \OCP\Util::ERROR);
		header('HTTP/1.0 404 Not Found');
		$tmpl = new OCP\Template('', '404', 'guest');
		$tmpl->printPage();
		exit();
	}
	if (isset($linkItem['share_with'])) {
		// Authenticate share_with
		$url = OCP\Util::linkToPublic('files') . '&t=' . $token;
		if (isset($_GET['file'])) {
			$url .= '&file=' . urlencode($_GET['file']);
		} else {
			if (isset($_GET['dir'])) {
				$url .= '&dir=' . urlencode($_GET['dir']);
			}
		}
		if (isset($_POST['password'])) {
			$password = $_POST['password'];
			if ($linkItem['share_type'] == OCP\Share::SHARE_TYPE_LINK) {
				// Check Password
				$forcePortable = (CRYPT_BLOWFISH != 1);
				$hasher = new PasswordHash(8, $forcePortable);
				if (!($hasher->CheckPassword($password.OC_Config::getValue('passwordsalt', ''),
											 $linkItem['share_with']))) {
					OCP\Util::addStyle('files_sharing', 'authenticate');
					$tmpl = new OCP\Template('files_sharing', 'authenticate', 'guest');
					$tmpl->assign('URL', $url);
					$tmpl->assign('wrongpw', true);
					$tmpl->printPage();
					exit();
				} else {
					// Save item id in session for future requests
					\OC::$session->set('public_link_authenticated', $linkItem['id']);
				}
			} else {
				OCP\Util::writeLog('share', 'Unknown share type '.$linkItem['share_type']
										   .' for share id '.$linkItem['id'], \OCP\Util::ERROR);
				header('HTTP/1.0 404 Not Found');
				$tmpl = new OCP\Template('', '404', 'guest');
				$tmpl->printPage();
				exit();
			}

		} else {
			// Check if item id is set in session
			if ( ! \OC::$session->exists('public_link_authenticated')
				|| \OC::$session->get('public_link_authenticated') !== $linkItem['id']
			) {
				// Prompt for password
				OCP\Util::addStyle('files_sharing', 'authenticate');
				$tmpl = new OCP\Template('files_sharing', 'authenticate', 'guest');
				$tmpl->assign('URL', $url);
				$tmpl->printPage();
				exit();
			}
		}
	}
	$basePath = $path;
	if (isset($_GET['path']) && \OC\Files\Filesystem::isReadable($basePath . $_GET['path'])) {
		$getPath = \OC\Files\Filesystem::normalizePath($_GET['path']);
		$path .= $getPath;
	} else {
		$getPath = '';
	}
	$dir = dirname($path);
	$file = basename($path);
	// Download the file
	if (isset($_GET['download'])) {
		if (isset($_GET['files'])) { // download selected files
			$files = urldecode($_GET['files']);
			$files_list = json_decode($files);
			// in case we get only a single file
			if ($files_list === NULL ) {
				$files_list = array($files);
			}
			OC_Files::get($path, $files_list, $_SERVER['REQUEST_METHOD'] == 'HEAD');
		} else {
			OC_Files::get($dir, $file, $_SERVER['REQUEST_METHOD'] == 'HEAD');
		}
		exit();
	} else {
		OCP\Util::addScript('files', 'file-upload');
		OCP\Util::addStyle('files_sharing', 'public');
		OCP\Util::addStyle('files_sharing', 'mobile');
		OCP\Util::addScript('files_sharing', 'public');
		OCP\Util::addScript('files', 'fileactions');
		OCP\Util::addScript('files', 'jquery.iframe-transport');
		OCP\Util::addScript('files', 'jquery.fileupload');
		$maxUploadFilesize=OCP\Util::maxUploadFilesize($path);
		$tmpl = new OCP\Template('files_sharing', 'public', 'base');
		$tmpl->assign('uidOwner', $shareOwner);
		$tmpl->assign('displayName', \OCP\User::getDisplayName($shareOwner));
		$tmpl->assign('filename', $file);
		$tmpl->assign('directory_path', $linkItem['file_target']);
		$tmpl->assign('mimetype', \OC\Files\Filesystem::getMimeType($path));
		$tmpl->assign('fileTarget', basename($linkItem['file_target']));
		$tmpl->assign('dirToken', $linkItem['token']);
		$tmpl->assign('sharingToken', $token);
		$tmpl->assign('disableSharing', true);
		$allowPublicUploadEnabled = (bool) ($linkItem['permissions'] & OCP\PERMISSION_CREATE);
		if (OC_Appconfig::getValue('core', 'shareapi_allow_public_upload', 'yes') === 'no') {
			$allowPublicUploadEnabled = false;
		}
		if ($linkItem['item_type'] !== 'folder') {
			$allowPublicUploadEnabled = false;
		}
		$tmpl->assign('uploadMaxFilesize', $maxUploadFilesize);
		$tmpl->assign('uploadMaxHumanFilesize', OCP\Util::humanFileSize($maxUploadFilesize));

		$urlLinkIdentifiers= (isset($token)?'&t='.$token:'')
							.(isset($_GET['dir'])?'&dir='.$_GET['dir']:'')
							.(isset($_GET['file'])?'&file='.$_GET['file']:'');
		// Show file list
		if (\OC\Files\Filesystem::is_dir($path)) {
			$tmpl->assign('dir', $getPath);

			OCP\Util::addStyle('files', 'files');
			OCP\Util::addStyle('files', 'upload');
			OCP\Util::addScript('files', 'files');
			OCP\Util::addScript('files', 'filelist');
			OCP\Util::addscript('files', 'keyboardshortcuts');
			$files = array();
			$rootLength = strlen($basePath) + 1;
			$totalSize = 0;
			foreach (\OC\Files\Filesystem::getDirectoryContent($path) as $i) {
				$totalSize += $i['size'];
				$i['date'] = OCP\Util::formatDate($i['mtime']);
				if ($i['type'] == 'file') {
					$fileinfo = pathinfo($i['name']);
					$i['basename'] = $fileinfo['filename'];
					if (!empty($fileinfo['extension'])) {
						$i['extension'] = '.' . $fileinfo['extension'];
					} else {
						$i['extension'] = '';
					}
				}
				$i['isPreviewAvailable'] = \OC::$server->getPreviewManager()->isMimeSupported($i['mimetype']);
				$i['directory'] = $getPath;
				$i['permissions'] = OCP\PERMISSION_READ;
				$i['icon'] = determineIcon($i, $basePath, $token);
				$files[] = $i;
			}
			usort($files, "fileCmp");

			// Make breadcrumb
			$breadcrumb = array();
			$pathtohere = '';
			foreach (explode('/', $getPath) as $i) {
				if ($i != '') {
					$pathtohere .= '/' . $i;
					$breadcrumb[] = array('dir' => $pathtohere, 'name' => $i);
				}
			}
			$list = new OCP\Template('files', 'part.list', '');
			$list->assign('files', $files);
			$list->assign('baseURL', OCP\Util::linkToPublic('files') . $urlLinkIdentifiers . '&path=');
			$list->assign('downloadURL',
				OCP\Util::linkToPublic('files') . $urlLinkIdentifiers . '&download&path=');
			$list->assign('isPublic', true);
			$list->assign('sharingtoken', $token);
			$list->assign('sharingroot', $basePath);
			$breadcrumbNav = new OCP\Template('files', 'part.breadcrumb', '');
			$breadcrumbNav->assign('breadcrumb', $breadcrumb);
			$breadcrumbNav->assign('baseURL', OCP\Util::linkToPublic('files') . $urlLinkIdentifiers . '&path=');
			$maxUploadFilesize=OCP\Util::maxUploadFilesize($path);
			$fileHeader = (!isset($files) or count($files) > 0);
			$emptyContent = ($allowPublicUploadEnabled and !$fileHeader);
			$folder = new OCP\Template('files', 'index', '');
			$folder->assign('fileList', $list->fetchPage());
			$folder->assign('breadcrumb', $breadcrumbNav->fetchPage());
			$folder->assign('dir', $getPath);
			$folder->assign('isCreatable', $allowPublicUploadEnabled);
			$folder->assign('dirToken', $linkItem['token']);
			$folder->assign('permissions', OCP\PERMISSION_READ);
			$folder->assign('isPublic',true);
			$folder->assign('publicUploadEnabled', 'no');
			$folder->assign('files', $files);
			$folder->assign('uploadMaxFilesize', $maxUploadFilesize);
			$folder->assign('uploadMaxHumanFilesize', OCP\Util::humanFileSize($maxUploadFilesize));
			$folder->assign('allowZipDownload', intval(OCP\Config::getSystemValue('allowZipDownload', true)));
			$folder->assign('usedSpacePercent', 0);
			$folder->assign('fileHeader', $fileHeader);
			$folder->assign('disableSharing', true);
			$folder->assign('trash', false);
			$folder->assign('emptyContent', $emptyContent);
			$folder->assign('ajaxLoad', false);
			$tmpl->assign('folder', $folder->fetchPage());
			$maxInputFileSize = OCP\Config::getSystemValue('maxZipInputSize', OCP\Util::computerFileSize('800 MB'));
			$allowZip = OCP\Config::getSystemValue('allowZipDownload', true)
						&& ( $maxInputFileSize === 0 || $totalSize <= $maxInputFileSize);
			$tmpl->assign('allowZipDownload', intval($allowZip));
			$tmpl->assign('downloadURL',
				OCP\Util::linkToPublic('files') . $urlLinkIdentifiers . '&download&path=' . urlencode($getPath));
		} else {
			$tmpl->assign('dir', $dir);

			// Show file preview if viewer is available
			if ($type == 'file') {
				$tmpl->assign('downloadURL', OCP\Util::linkToPublic('files') . $urlLinkIdentifiers . '&download');
			} else {
				$tmpl->assign('downloadURL', OCP\Util::linkToPublic('files')
										.$urlLinkIdentifiers.'&download&path='.urlencode($getPath));
			}
		}
		$tmpl->printPage();
	}
	exit();
} else {
	OCP\Util::writeLog('share', 'could not resolve linkItem', \OCP\Util::DEBUG);
}

$errorTemplate = new OCP\Template('files_sharing', 'part.404', '');
$errorContent = $errorTemplate->fetchPage();

header('HTTP/1.0 404 Not Found');
OCP\Util::addStyle('files_sharing', '404');
$tmpl = new OCP\Template('', '404', 'guest');
$tmpl->assign('content', $errorContent);
$tmpl->printPage();
