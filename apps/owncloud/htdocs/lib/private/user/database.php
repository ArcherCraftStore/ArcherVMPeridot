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
/*
 *
 * The following SQL statement is just a help for developers and will not be
 * executed!
 *
 * CREATE TABLE `users` (
 *   `uid` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
 *   `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 *   PRIMARY KEY (`uid`)
 * ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 *
 */

require_once 'phpass/PasswordHash.php';

/**
 * Class for user management in a SQL Database (e.g. MySQL, SQLite)
 */
class OC_User_Database extends OC_User_Backend {
	/**
	 * @var PasswordHash
	 */
	static private $hasher = null;

	private function getHasher() {
		if (!self::$hasher) {
			//we don't want to use DES based crypt(), since it doesn't return a hash with a recognisable prefix
			$forcePortable = (CRYPT_BLOWFISH != 1);
			self::$hasher = new PasswordHash(8, $forcePortable);
		}
		return self::$hasher;

	}

	/**
	 * @brief Create a new user
	 * @param $uid The username of the user to create
	 * @param $password The password of the new user
	 * @returns true/false
	 *
	 * Creates a new user. Basic checking of username is done in OC_User
	 * itself, not in its subclasses.
	 */
	public function createUser($uid, $password) {
		if ($this->userExists($uid)) {
			return false;
		} else {
			$hasher = $this->getHasher();
			$hash = $hasher->HashPassword($password . OC_Config::getValue('passwordsalt', ''));
			$query = OC_DB::prepare('INSERT INTO `*PREFIX*users` ( `uid`, `password` ) VALUES( ?, ? )');
			$result = $query->execute(array($uid, $hash));

			return $result ? true : false;
		}
	}

	/**
	 * @brief delete a user
	 * @param $uid The username of the user to delete
	 * @returns true/false
	 *
	 * Deletes a user
	 */
	public function deleteUser($uid) {
		// Delete user-group-relation
		$query = OC_DB::prepare('DELETE FROM `*PREFIX*users` WHERE `uid` = ?');
		$query->execute(array($uid));
		return true;
	}

	/**
	 * @brief Set password
	 * @param $uid The username
	 * @param $password The new password
	 * @returns true/false
	 *
	 * Change the password of a user
	 */
	public function setPassword($uid, $password) {
		if ($this->userExists($uid)) {
			$hasher = $this->getHasher();
			$hash = $hasher->HashPassword($password . OC_Config::getValue('passwordsalt', ''));
			$query = OC_DB::prepare('UPDATE `*PREFIX*users` SET `password` = ? WHERE `uid` = ?');
			$query->execute(array($hash, $uid));

			return true;
		} else {
			return false;
		}
	}

	/**
	 * @brief Set display name
	 * @param $uid The username
	 * @param $displayName The new display name
	 * @returns true/false
	 *
	 * Change the display name of a user
	 */
	public function setDisplayName($uid, $displayName) {
		if ($this->userExists($uid)) {
			$query = OC_DB::prepare('UPDATE `*PREFIX*users` SET `displayname` = ? WHERE LOWER(`uid`) = ?');
			$query->execute(array($displayName, $uid));
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @brief get display name of the user
	 * @param $uid user ID of the user
	 * @return string display name
	 */
	public function getDisplayName($uid) {
		$query = OC_DB::prepare('SELECT `displayname` FROM `*PREFIX*users` WHERE `uid` = ?');
		$result = $query->execute(array($uid))->fetchAll();
		$displayName = trim($result[0]['displayname'], ' ');
		if (!empty($displayName)) {
			return $displayName;
		} else {
			return $uid;
		}
	}

	/**
	 * @brief Get a list of all display names
	 * @returns array with  all displayNames (value) and the correspondig uids (key)
	 *
	 * Get a list of all display names and user ids.
	 */
	public function getDisplayNames($search = '', $limit = null, $offset = null) {
		$displayNames = array();
		$query = OC_DB::prepare('SELECT `uid`, `displayname` FROM `*PREFIX*users`'
			. ' WHERE LOWER(`displayname`) LIKE LOWER(?) OR '
			. 'LOWER(`uid`) LIKE LOWER(?)', $limit, $offset);
		$result = $query->execute(array($search . '%', $search . '%'));
		$users = array();
		while ($row = $result->fetchRow()) {
			$displayNames[$row['uid']] = $row['displayname'];
		}

		return $displayNames;
	}

	/**
	 * @brief Check if the password is correct
	 * @param $uid The username
	 * @param $password The password
	 * @returns string
	 *
	 * Check if the password is correct without logging in the user
	 * returns the user id or false
	 */
	public function checkPassword($uid, $password) {
		$query = OC_DB::prepare('SELECT `uid`, `password` FROM `*PREFIX*users` WHERE LOWER(`uid`) = LOWER(?)');
		$result = $query->execute(array($uid));

		$row = $result->fetchRow();
		if ($row) {
			$storedHash = $row['password'];
			if ($storedHash[0] == '$') { //the new phpass based hashing
				$hasher = $this->getHasher();
				if ($hasher->CheckPassword($password . OC_Config::getValue('passwordsalt', ''), $storedHash)) {
					return $row['uid'];
				} else {
					return false;
				}
			} else { //old sha1 based hashing
				if (sha1($password) == $storedHash) {
					//upgrade to new hashing
					$this->setPassword($row['uid'], $password);
					return $row['uid'];
				} else {
					return false;
				}
			}
		} else {
			return false;
		}
	}

	/**
	 * @brief Get a list of all users
	 * @returns array with all uids
	 *
	 * Get a list of all users.
	 */
	public function getUsers($search = '', $limit = null, $offset = null) {
		$query = OC_DB::prepare('SELECT `uid` FROM `*PREFIX*users` WHERE LOWER(`uid`) LIKE LOWER(?)', $limit, $offset);
		$result = $query->execute(array($search . '%'));
		$users = array();
		while ($row = $result->fetchRow()) {
			$users[] = $row['uid'];
		}
		return $users;
	}

	/**
	 * @brief check if a user exists
	 * @param string $uid the username
	 * @return boolean
	 */
	public function userExists($uid) {
		$query = OC_DB::prepare('SELECT COUNT(*) FROM `*PREFIX*users` WHERE LOWER(`uid`) = LOWER(?)');
		$result = $query->execute(array($uid));
		if (OC_DB::isError($result)) {
			OC_Log::write('core', OC_DB::getErrorMessage($result), OC_Log::ERROR);
			return false;
		}
		return $result->fetchOne() > 0;
	}

	/**
	 * @brief get the user's home directory
	 * @param string $uid the username
	 * @return boolean
	 */
	public function getHome($uid) {
		if ($this->userExists($uid)) {
			return OC_Config::getValue("datadirectory", OC::$SERVERROOT . "/data") . '/' . $uid;
		} else {
			return false;
		}
	}

	/**
	 * @return bool
	 */
	public function hasUserListings() {
		return true;
	}

	/**
	 * counts the users in the database
	 *
	 * @return int | bool
	 */
	public function countUsers() {
		$query = OC_DB::prepare('SELECT COUNT(*) FROM `*PREFIX*users`');
		$result = $query->execute();
		if (OC_DB::isError($result)) {
			OC_Log::write('core', OC_DB::getErrorMessage($result), OC_Log::ERROR);
			return false;
		}
		return $result->fetchOne();
	}

}
