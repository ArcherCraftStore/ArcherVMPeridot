<?php
/**
 * Copyright (c) 2012 Bart Visscher <bartv@thisnet.nl>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

class OC_Request {

	const USER_AGENT_IE = '/MSIE/';
	// Android Chrome user agent: https://developers.google.com/chrome/mobile/docs/user-agent
	const USER_AGENT_ANDROID_MOBILE_CHROME = '#Android.*Chrome/[.0-9]*#';

	const REGEX_LOCALHOST = '/^(127\.0\.0\.1|localhost)(:[0-9]+|)$/';

	/**
	 * @brief Check overwrite condition
	 * @param string $type
	 * @returns bool
	 */
	private static function isOverwriteCondition($type = '') {
		$regex = '/' . OC_Config::getValue('overwritecondaddr', '')  . '/';
		return $regex === '//' or preg_match($regex, $_SERVER['REMOTE_ADDR']) === 1
			or ($type !== 'protocol' and OC_Config::getValue('forcessl', false));
	}

	/**
	 * @brief Checks whether a domain is considered as trusted from the list
	 * of trusted domains. If no trusted domains have been configured, returns
	 * true.
	 * This is used to prevent Host Header Poisoning.
	 * @param string $host
	 * @return bool true if the given domain is trusted or if no trusted domains
	 * have been configured
	 */
	public static function isTrustedDomain($domain) {
		$trustedList = \OC_Config::getValue('trusted_domains', array());
		if (empty($trustedList)) {
			return true;
		}
		if (preg_match(self::REGEX_LOCALHOST, $domain) === 1) {
			return true;
		}
		return in_array($domain, $trustedList);
	}

	/**
	 * @brief Returns the unverified server host from the headers without checking
	 * whether it is a trusted domain
	 * @returns string the server host
	 *
	 * Returns the server host, even if the website uses one or more
	 * reverse proxies
	 */
	public static function insecureServerHost() {
		$host = null;
		if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
			if (strpos($_SERVER['HTTP_X_FORWARDED_HOST'], ",") !== false) {
				$parts = explode(',', $_SERVER['HTTP_X_FORWARDED_HOST']);
				$host = trim(current($parts));
			} else {
				$host = $_SERVER['HTTP_X_FORWARDED_HOST'];
			}
		} else {
			if (isset($_SERVER['HTTP_HOST'])) {
				$host = $_SERVER['HTTP_HOST'];
			} else if (isset($_SERVER['SERVER_NAME'])) {
				$host = $_SERVER['SERVER_NAME'];
			}
		}
		return $host;
	}

	/**
	 * Returns the overwritehost setting from the config if set and
	 * if the overwrite condition is met
	 * @return overwritehost value or null if not defined or the defined condition
	 * isn't met
	 */
	public static function getOverwriteHost() {
		if(OC_Config::getValue('overwritehost', '') !== '' and self::isOverwriteCondition()) {
			return OC_Config::getValue('overwritehost');
		}
		return null;
	}

	/**
	 * @brief Returns the server host from the headers, or the first configured
	 * trusted domain if the host isn't in the trusted list
	 * @returns string the server host
	 *
	 * Returns the server host, even if the website uses one or more
	 * reverse proxies
	 */
	public static function serverHost() {
		if(OC::$CLI) {
			return 'localhost';
		}

		// overwritehost is always trusted
		$host = self::getOverwriteHost();
		if ($host !== null) {
			return $host;
		}

		// get the host from the headers
		$host = self::insecureServerHost();

		// Verify that the host is a trusted domain if the trusted domains
		// are defined
		// If no trusted domain is provided the first trusted domain is returned
		if (self::isTrustedDomain($host)) {
			return $host;
		} else {
			$trustedList = \OC_Config::getValue('trusted_domains', array(''));
			return $trustedList[0];
		}
	}

	/**
	* @brief Returns the server protocol
	* @returns string the server protocol
	*
	* Returns the server protocol. It respects reverse proxy servers and load balancers
	*/
	public static function serverProtocol() {
		if(OC_Config::getValue('overwriteprotocol', '') !== '' and self::isOverwriteCondition('protocol')) {
			return OC_Config::getValue('overwriteprotocol');
		}
		if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
			$proto = strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']);
			// Verify that the protocol is always HTTP or HTTPS
			// default to http if an invalid value is provided
			return $proto === 'https' ? 'https' : 'http';
		}
		if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
			return 'https';
		}
		return 'http';
	}

	/**
	 * @brief Returns the request uri
	 * @returns string the request uri
	 *
	 * Returns the request uri, even if the website uses one or more
	 * reverse proxies
	 */
	public static function requestUri() {
		$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
		if (OC_Config::getValue('overwritewebroot', '') !== '' and self::isOverwriteCondition()) {
			$uri = self::scriptName() . substr($uri, strlen($_SERVER['SCRIPT_NAME']));
		}
		return $uri;
	}

	/**
	 * @brief Returns the script name
	 * @returns string the script name
	 *
	 * Returns the script name, even if the website uses one or more
	 * reverse proxies
	 */
	public static function scriptName() {
		$name = $_SERVER['SCRIPT_NAME'];
		if (OC_Config::getValue('overwritewebroot', '') !== '' and self::isOverwriteCondition()) {
			$serverroot = str_replace("\\", '/', substr(__DIR__, 0, -strlen('lib/private/')));
			$suburi = str_replace("\\", "/", substr(realpath($_SERVER["SCRIPT_FILENAME"]), strlen($serverroot)));
			$name = OC_Config::getValue('overwritewebroot', '') . $suburi;
		}
		return $name;
	}

	/**
	 * @brief get Path info from request
	 * @returns string Path info or false when not found
	 */
	public static function getPathInfo() {
		if (array_key_exists('PATH_INFO', $_SERVER)) {
			$path_info = $_SERVER['PATH_INFO'];
		}else{
			$path_info = self::getRawPathInfo();
			// following is taken from Sabre_DAV_URLUtil::decodePathSegment
			$path_info = rawurldecode($path_info);
			$encoding = mb_detect_encoding($path_info, array('UTF-8', 'ISO-8859-1'));

			switch($encoding) {

				case 'ISO-8859-1' :
					$path_info = utf8_encode($path_info);

			}
			// end copy
		}
		return $path_info;
	}

	/**
	 * @brief get Path info from request, not urldecoded
	 * @returns string Path info or false when not found
	 */
	public static function getRawPathInfo() {
		$requestUri = $_SERVER['REQUEST_URI'];
		// remove too many leading slashes - can be caused by reverse proxy configuration
		if (strpos($requestUri, '/') === 0) {
			$requestUri = '/' . ltrim($requestUri, '/');
		}

		// Remove the query string from REQUEST_URI
		if ($pos = strpos($requestUri, '?')) {
			$requestUri = substr($requestUri, 0, $pos);
		}

		$scriptName = $_SERVER['SCRIPT_NAME'];
		$path_info = $requestUri;

		// strip off the script name's dir and file name
		list($path, $name) = \Sabre_DAV_URLUtil::splitPath($scriptName);
		if (!empty($path)) {
			if( $path === $path_info || strpos($path_info, $path.'/') === 0) {
				$path_info = substr($path_info, strlen($path));
			} else {
				throw new Exception("The requested uri($requestUri) cannot be processed by the script '$scriptName')");
			}
		}
		if (strpos($path_info, '/'.$name) === 0) {
			$path_info = substr($path_info, strlen($name) + 1);
		}
		if (strpos($path_info, $name) === 0) {
			$path_info = substr($path_info, strlen($name));
		}
		if($path_info === '/'){
			return '';
		} else {
			return $path_info;
		}
	}

	/**
	 * @brief Check if this is a no-cache request
	 * @returns boolean true for no-cache
	 */
	static public function isNoCache() {
		if (!isset($_SERVER['HTTP_CACHE_CONTROL'])) {
			return false;
		}
		return $_SERVER['HTTP_CACHE_CONTROL'] == 'no-cache';
	}

	/**
	 * @brief Check if the requestor understands gzip
	 * @returns boolean true for gzip encoding supported
	 */
	static public function acceptGZip() {
		if (!isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
			return false;
		}
		$HTTP_ACCEPT_ENCODING = $_SERVER["HTTP_ACCEPT_ENCODING"];
		if( strpos($HTTP_ACCEPT_ENCODING, 'x-gzip') !== false )
			return 'x-gzip';
		else if( strpos($HTTP_ACCEPT_ENCODING, 'gzip') !== false )
			return 'gzip';
		return false;
	}

	/**
	 * @brief Check if the requester sent along an mtime
	 * @returns false or an mtime
	 */
	static public function hasModificationTime () {
		if (isset($_SERVER['HTTP_X_OC_MTIME'])) {
			return $_SERVER['HTTP_X_OC_MTIME'];
		} else {
			return false;
		}
	}

	/**
	 * Checks whether the user agent matches a given regex
	 * @param string|array $agent agent name or array of agent names
	 * @return boolean true if at least one of the given agent matches,
	 * false otherwise
	 */
	static public function isUserAgent($agent) {
		if (!is_array($agent)) {
			$agent = array($agent);
		}
		foreach ($agent as $regex) {
			if (preg_match($regex, $_SERVER['HTTP_USER_AGENT'])) {
				return true;
			}
		}
		return false;
	}
}
