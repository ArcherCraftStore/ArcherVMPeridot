<?php

/**
 * ownCloud
 *
 * @author Artuhr Schiwon
 * @copyright 2013 Arthur Schiwon blizzz@owncloud.com
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

namespace OCA\user_ldap;

use OCA\user_ldap\lib\ILDAPWrapper;

class User_Proxy extends lib\Proxy implements \OCP\UserInterface {
	private $backends = array();
	private $refBackend = null;

	/**
	 * @brief Constructor
	 * @param $serverConfigPrefixes array containing the config Prefixes
	 */
	public function __construct($serverConfigPrefixes, ILDAPWrapper $ldap) {
		parent::__construct($ldap);
		foreach($serverConfigPrefixes as $configPrefix) {
		    $this->backends[$configPrefix] =
				new \OCA\user_ldap\USER_LDAP($this->getAccess($configPrefix));
			if(is_null($this->refBackend)) {
				$this->refBackend = &$this->backends[$configPrefix];
			}
		}
	}

	/**
	 * @brief Tries the backends one after the other until a positive result is returned from the specified method
	 * @param $uid string, the uid connected to the request
	 * @param $method string, the method of the user backend that shall be called
	 * @param $parameters an array of parameters to be passed
	 * @return mixed, the result of the method or false
	 */
	protected  function walkBackends($uid, $method, $parameters) {
		$cacheKey = $this->getUserCacheKey($uid);
		foreach($this->backends as $configPrefix => $backend) {
			$instance = $backend;
			if(!method_exists($instance, $method)
				&& method_exists($this->getAccess($configPrefix), $method)) {
				$instance = $this->getAccess($configPrefix);
			}
			if($result = call_user_func_array(array($instance, $method), $parameters)) {
				$this->writeToCache($cacheKey, $configPrefix);
				return $result;
			}
		}
		return false;
	}

	/**
	 * @brief Asks the backend connected to the server that supposely takes care of the uid from the request.
	 * @param $uid string, the uid connected to the request
	 * @param $method string, the method of the user backend that shall be called
	 * @param $parameters an array of parameters to be passed
	 * @param $passOnWhen the result matches this variable
	 * @return mixed, the result of the method or false
	 */
	protected  function callOnLastSeenOn($uid, $method, $parameters, $passOnWhen) {
		$cacheKey = $this->getUserCacheKey($uid);
		$prefix = $this->getFromCache($cacheKey);
		//in case the uid has been found in the past, try this stored connection first
		if(!is_null($prefix)) {
			if(isset($this->backends[$prefix])) {
				$instance = $this->backends[$prefix];
				if(!method_exists($instance, $method)
					&& method_exists($this->getAccess($prefix), $method)) {
					$instance = $this->getAccess($prefix);
				}
				$result = call_user_func_array(array($instance, $method), $parameters);
				if($result === $passOnWhen) {
					//not found here, reset cache to null if user vanished
					//because sometimes methods return false with a reason
					$userExists = call_user_func_array(
						array($this->backends[$prefix], 'userExists'),
						array($uid)
					);
					if(!$userExists) {
						$this->writeToCache($cacheKey, null);
					}
				}
				return $result;
			}
		}
		return false;
	}

	/**
	 * @brief Check if backend implements actions
	 * @param $actions bitwise-or'ed actions
	 * @returns boolean
	 *
	 * Returns the supported actions as int to be
	 * compared with OC_USER_BACKEND_CREATE_USER etc.
	 */
	public function implementsActions($actions) {
		//it's the same across all our user backends obviously
		return $this->refBackend->implementsActions($actions);
	}

	/**
	 * @brief Get a list of all users
	 * @returns array with all uids
	 *
	 * Get a list of all users.
	 */
	public function getUsers($search = '', $limit = 10, $offset = 0) {
		//we do it just as the /OC_User implementation: do not play around with limit and offset but ask all backends
		$users = array();
		foreach($this->backends as $backend) {
			$backendUsers = $backend->getUsers($search, $limit, $offset);
			if (is_array($backendUsers)) {
				$users = array_merge($users, $backendUsers);
			}
		}
		return $users;
	}

	/**
	 * @brief check if a user exists
	 * @param string $uid the username
	 * @return boolean
	 */
	public function userExists($uid) {
		return $this->handleRequest($uid, 'userExists', array($uid));
	}

	/**
	 * @brief Check if the password is correct
	 * @param $uid The username
	 * @param $password The password
	 * @returns true/false
	 *
	 * Check if the password is correct without logging in the user
	 */
	public function checkPassword($uid, $password) {
		return $this->handleRequest($uid, 'checkPassword', array($uid, $password));
	}

	/**
	 * @brief get the user's home directory
	 * @param string $uid the username
	 * @return boolean
	 */
	public function getHome($uid) {
		return $this->handleRequest($uid, 'getHome', array($uid));
	}

	/**
	 * @brief get display name of the user
	 * @param $uid user ID of the user
	 * @return display name
	 */
	public function getDisplayName($uid) {
		return $this->handleRequest($uid, 'getDisplayName', array($uid));
	}

	/**
	 * @brief checks whether the user is allowed to change his avatar in ownCloud
	 * @param $uid string the ownCloud user name
	 * @return boolean either the user can or cannot
	 */
	public function canChangeAvatar($uid) {
		return $this->handleRequest($uid, 'canChangeAvatar', array($uid), true);
	}

	/**
	 * @brief Get a list of all display names
	 * @returns array with  all displayNames (value) and the corresponding uids (key)
	 *
	 * Get a list of all display names and user ids.
	 */
	public function getDisplayNames($search = '', $limit = null, $offset = null) {
		//we do it just as the /OC_User implementation: do not play around with limit and offset but ask all backends
		$users = array();
		foreach($this->backends as $backend) {
			$backendUsers = $backend->getDisplayNames($search, $limit, $offset);
			if (is_array($backendUsers)) {
				$users = $users + $backendUsers;
			}
		}
		return $users;
	}

	/**
	 * @brief delete a user
	 * @param $uid The username of the user to delete
	 * @returns true/false
	 *
	 * Deletes a user
	 */
	public function deleteUser($uid) {
		return false;
	}

	/**
	 * @return bool
	 */
	public function hasUserListings() {
		return $this->refBackend->hasUserListings();
	}

	/**
	 * @brief Count the number of users
	 * @returns int | bool
	 */
	public function countUsers() {
		$users = false;
		foreach($this->backends as $backend) {
			$backendUsers = $backend->countUsers();
			if ($backendUsers !== false) {
				$users += $backendUsers;
			}
		}
		return $users;
	}

}
