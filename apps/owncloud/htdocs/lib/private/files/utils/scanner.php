<?php
/**
 * Copyright (c) 2013 Robin Appelman <icewind@owncloud.com>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OC\Files\Utils;

use OC\Files\Filesystem;
use OC\Hooks\PublicEmitter;

/**
 * Class Scanner
 *
 * Hooks available in scope \OC\Utils\Scanner
 *  - scanFile(string $absolutePath)
 *  - scanFolder(string $absolutePath)
 *
 * @package OC\Files\Utils
 */
class Scanner extends PublicEmitter {
	/**
	 * @var string $user
	 */
	private $user;

	/**
	 * @param string $user
	 */
	public function __construct($user) {
		$this->user = $user;
	}

	/**
	 * get all storages for $dir
	 *
	 * @param string $dir
	 * @return \OC\Files\Mount\Mount[]
	 */
	protected function getMounts($dir) {
		//TODO: move to the node based fileapi once that's done
		\OC_Util::tearDownFS();
		\OC_Util::setupFS($this->user);
		$absolutePath = Filesystem::getView()->getAbsolutePath($dir);

		$mountManager = Filesystem::getMountManager();
		$mounts = $mountManager->findIn($absolutePath);
		$mounts[] = $mountManager->find($absolutePath);
		$mounts = array_reverse($mounts); //start with the mount of $dir

		return $mounts;
	}

	/**
	 * attach listeners to the scanner
	 *
	 * @param \OC\Files\Mount\Mount $mount
	 */
	protected function attachListener($mount) {
		$scanner = $mount->getStorage()->getScanner();
		$emitter = $this;
		$scanner->listen('\OC\Files\Cache\Scanner', 'scanFile', function ($path) use ($mount, $emitter) {
			$emitter->emit('\OC\Files\Utils\Scanner', 'scanFile', array($mount->getMountPoint() . $path));
		});
		$scanner->listen('\OC\Files\Cache\Scanner', 'scanFolder', function ($path) use ($mount, $emitter) {
			$emitter->emit('\OC\Files\Utils\Scanner', 'scanFolder', array($mount->getMountPoint() . $path));
		});
	}

	public function backgroundScan($dir) {
		$mounts = $this->getMounts($dir);
		foreach ($mounts as $mount) {
			if (is_null($mount->getStorage())) {
				continue;
			}
			$scanner = $mount->getStorage()->getScanner();
			$this->attachListener($mount);
			$scanner->backgroundScan();
		}
	}

	public function scan($dir) {
		$mounts = $this->getMounts($dir);
		foreach ($mounts as $mount) {
			if (is_null($mount->getStorage())) {
				continue;
			}
			$scanner = $mount->getStorage()->getScanner();
			$this->attachListener($mount);
			$scanner->scan('', \OC\Files\Cache\Scanner::SCAN_RECURSIVE, \OC\Files\Cache\Scanner::REUSE_ETAG);
		}
	}
}

