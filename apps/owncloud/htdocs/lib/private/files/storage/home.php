<?php
/**
 * Copyright (c) 2012 Robin Appelman <icewind@owncloud.com>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OC\Files\Storage;

/**
 * Specialized version of Local storage for home directory usage
 */
class Home extends Local {
	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @var \OC\User\User $user
	 */
	protected $user;

	/**
	 * @brief Construct a Home storage instance
	 * @param array $arguments array with "user" containing the
	 * storage owner and "legacy" containing "true" if the storage is
	 * a legacy storage with "local::" URL instead of the new "home::" one.
	 */
	public function __construct($arguments) {
		$this->user = $arguments['user'];
		$datadir = $this->user->getHome();
		if (isset($arguments['legacy']) && $arguments['legacy']) {
			// legacy home id (<= 5.0.12)
			$this->id = 'local::' . $datadir . '/';
		}
		else {
		    $this->id = 'home::' . $this->user->getUID();
		}

		parent::__construct(array('datadir' => $datadir));
	}

	public function getId() {
		return $this->id;
	}

	/**
	 * @return \OC\Files\Cache\HomeCache
	 */
	public function getCache($path = '') {
		if (!isset($this->cache)) {
			$this->cache = new \OC\Files\Cache\HomeCache($this);
		}
		return $this->cache;
	}

	/**
	 * @brief Returns the owner of this home storage
	 * @return \OC\User\User owner of this home storage
	 */
	public function getUser() {
		return $this->user;
	}
	
	/**
	 * get the owner of a path
	 *
	 * @param string $path The path to get the owner
	 * @return string uid or false
	 */
	public function getOwner($path) {
		return $this->getUser()->getUID();
	}
}
