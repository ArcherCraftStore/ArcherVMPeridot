<?php
/**
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OC;

use OCP\IAvatarManager;

/*
 * This class implements methods to access Avatar functionality
 */
class AvatarManager implements IAvatarManager {

	/**
	 * @brief return a user specific instance of \OCP\IAvatar
	 * @see \OCP\IAvatar
	 * @param $user string the ownCloud user id
	 * @return \OCP\IAvatar
	 */
	function getAvatar($user) {
		return new \OC_Avatar($user);
	}
}
