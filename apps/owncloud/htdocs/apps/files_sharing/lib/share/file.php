<?php
/**
* ownCloud
*
* @author Michael Gapczynski
* @copyright 2012 Michael Gapczynski mtgap@owncloud.com
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
*/

class OC_Share_Backend_File implements OCP\Share_Backend_File_Dependent {

	const FORMAT_SHARED_STORAGE = 0;
	const FORMAT_GET_FOLDER_CONTENTS = 1;
	const FORMAT_FILE_APP_ROOT = 2;
	const FORMAT_OPENDIR = 3;
	const FORMAT_GET_ALL = 4;
	const FORMAT_PERMISSIONS = 5;

	private $path;

	public function isValidSource($itemSource, $uidOwner) {
		$query = \OC_DB::prepare('SELECT `name` FROM `*PREFIX*filecache` WHERE `fileid` = ?');
		$result = $query->execute(array($itemSource));
		if ($row = $result->fetchRow()) {
			$this->path = $row['name'];
			return true;
		}
		return false;
	}

	public function getFilePath($itemSource, $uidOwner) {
		if (isset($this->path)) {
			$path = $this->path;
			$this->path = null;
			return $path;
		}
		return false;
	}

	public function generateTarget($filePath, $shareWith, $exclude = null) {
		$target = '/'.basename($filePath);
		if (isset($exclude)) {
			if ($pos = strrpos($target, '.')) {
				$name = substr($target, 0, $pos);
				$ext = substr($target, $pos);
			} else {
				$name = $target;
				$ext = '';
			}
			$i = 2;
			$append = '';
			while (in_array($name.$append.$ext, $exclude)) {
				$append = ' ('.$i.')';
				$i++;
			}
			$target = $name.$append.$ext;
		}
		return $target;
	}

	public function formatItems($items, $format, $parameters = null) {
		if ($format == self::FORMAT_SHARED_STORAGE) {
			// Only 1 item should come through for this format call
			return array(
				'parent' => $items[key($items)]['parent'],
				'path' => $items[key($items)]['path'],
				'storage' => $items[key($items)]['storage'],
				'permissions' => $items[key($items)]['permissions'],
				'uid_owner' => $items[key($items)]['uid_owner']
			);
		} else if ($format == self::FORMAT_GET_FOLDER_CONTENTS) {
			$files = array();
			foreach ($items as $item) {
				$file = array();
				$file['fileid'] = $item['file_source'];
				$file['storage'] = $item['storage'];
				$file['path'] = $item['file_target'];
				$file['parent'] = $item['file_parent'];
				$file['name'] = basename($item['file_target']);
				$file['mimetype'] = $item['mimetype'];
				$file['mimepart'] = $item['mimepart'];
				$file['mtime'] = $item['mtime'];
				$file['encrypted'] = $item['encrypted'];
				$file['etag'] = $item['etag'];
				$storage = \OC\Files\Filesystem::getStorage('/');
				$cache = $storage->getCache();
				if ($item['encrypted'] or ($item['unencrypted_size'] > 0 and $cache->getMimetype($item['mimetype']) === 'httpd/unix-directory')) {
					$file['size'] = $item['unencrypted_size'];
					$file['encrypted_size'] = $item['size'];
				} else {
					$file['size'] = $item['size'];
				}
				$files[] = $file;
			}
			return $files;
		} else if ($format == self::FORMAT_FILE_APP_ROOT) {
			$mtime = 0;
			$size = 0;
			foreach ($items as $item) {
				if ($item['mtime'] > $mtime) {
					$mtime = $item['mtime'];
				}
				$size += (int)$item['size'];
			}
			return array(
				'fileid' => -1,
				'name' => 'Shared',
				'mtime' => $mtime,
				'mimetype' => 'httpd/unix-directory',
				'size' => $size
			);
		} else if ($format == self::FORMAT_OPENDIR) {
			$files = array();
			foreach ($items as $item) {
				$files[] = basename($item['file_target']);
			}
			return $files;
		} else if ($format == self::FORMAT_GET_ALL) {
			$ids = array();
			foreach ($items as $item) {
				$ids[] = $item['file_source'];
			}
			return $ids;
		} else if ($format === self::FORMAT_PERMISSIONS) {
			$filePermissions = array();
			foreach ($items as $item) {
				$filePermissions[$item['file_source']] = $item['permissions'];
			}
			return $filePermissions;
		}
		return array();
	}

	public static function getSource($target) {
		if ($target == '') {
			return false;
		}
		$target = '/'.$target;
		$target = rtrim($target, '/');
		$pos = strpos($target, '/', 1);
		// Get shared folder name
		if ($pos !== false) {
			$folder = substr($target, 0, $pos);
			$source = \OCP\Share::getItemSharedWith('folder', $folder, \OC_Share_Backend_File::FORMAT_SHARED_STORAGE);
			if ($source) {
				$source['path'] = $source['path'].substr($target, strlen($folder));
			}
		} else {
			$source = \OCP\Share::getItemSharedWith('file', $target, \OC_Share_Backend_File::FORMAT_SHARED_STORAGE);
		}
		if ($source) {
			if (isset($source['parent'])) {
				$parent = $source['parent'];
				while (isset($parent)) {
					$query = \OC_DB::prepare('SELECT `parent`, `uid_owner` FROM `*PREFIX*share` WHERE `id` = ?', 1);
					$item = $query->execute(array($parent))->fetchRow();
					if (isset($item['parent'])) {
						$parent = $item['parent'];
					} else {
						$fileOwner = $item['uid_owner'];
						break;
					}
				}
			} else {
				$fileOwner = $source['uid_owner'];
			}
			$source['fileOwner'] = $fileOwner;
			return $source;
		}
		\OCP\Util::writeLog('files_sharing', 'File source not found for: '.$target, \OCP\Util::DEBUG);
		return false;
	}

}
