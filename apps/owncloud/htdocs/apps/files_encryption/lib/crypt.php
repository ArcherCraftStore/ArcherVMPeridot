<?php

/**
 * ownCloud
 *
 * @author Sam Tuke, Frank Karlitschek, Robin Appelman
 * @copyright 2012 Sam Tuke samtuke@owncloud.com,
 * Robin Appelman icewind@owncloud.com, Frank Karlitschek
 * frank@owncloud.org
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

namespace OCA\Encryption;

require_once __DIR__ . '/../3rdparty/Crypt_Blowfish/Blowfish.php';

/**
 * Class for common cryptography functionality
 */

class Crypt {

	const ENCRYPTION_UNKNOWN_ERROR = -1;
	const ENCRYPTION_NOT_INITIALIZED_ERROR = 1;
	const ENCRYPTION_PRIVATE_KEY_NOT_VALID_ERROR = 2;
	const ENCRYPTION_NO_SHARE_KEY_FOUND = 3;


	/**
	 * @brief return encryption mode client or server side encryption
	 * @param string $user name (use system wide setting if name=null)
	 * @return string 'client' or 'server'
	 */
	public static function mode($user = null) {

		return 'server';

	}

	/**
	 * @brief Create a new encryption keypair
	 * @return array publicKey, privatekey
	 */
	public static function createKeypair() {

		$return = false;

		$res = Helper::getOpenSSLPkey();

		if ($res === false) {
			\OCP\Util::writeLog('Encryption library', 'couldn\'t generate users key-pair for ' . \OCP\User::getUser(), \OCP\Util::ERROR);
			while ($msg = openssl_error_string()) {
				\OCP\Util::writeLog('Encryption library', 'openssl_pkey_new() fails:  ' . $msg, \OCP\Util::ERROR);
			}
		} elseif (openssl_pkey_export($res, $privateKey, null, Helper::getOpenSSLConfig())) {
			// Get public key
			$keyDetails = openssl_pkey_get_details($res);
			$publicKey = $keyDetails['key'];

			$return = array(
				'publicKey' => $publicKey,
				'privateKey' => $privateKey
			);
		} else {
			\OCP\Util::writeLog('Encryption library', 'couldn\'t export users private key, please check your servers openSSL configuration.' . \OCP\User::getUser(), \OCP\Util::ERROR);
			while($errMsg = openssl_error_string()) {
				\OCP\Util::writeLog('Encryption library', $errMsg, \OCP\Util::ERROR);
			}
		}

		return $return;
	}

	/**
	 * @brief Add arbitrary padding to encrypted data
	 * @param string $data data to be padded
	 * @return string padded data
	 * @note In order to end up with data exactly 8192 bytes long we must
	 * add two letters. It is impossible to achieve exactly 8192 length
	 * blocks with encryption alone, hence padding is added to achieve the
	 * required length.
	 */
	private static function addPadding($data) {

		$padded = $data . 'xx';

		return $padded;

	}

	/**
	 * @brief Remove arbitrary padding to encrypted data
	 * @param string $padded padded data to remove padding from
	 * @return string unpadded data on success, false on error
	 */
	private static function removePadding($padded) {

		if (substr($padded, -2) === 'xx') {

			$data = substr($padded, 0, -2);

			return $data;

		} else {

			// TODO: log the fact that unpadded data was submitted for removal of padding
			return false;

		}

	}

	/**
	 * @brief Check if a file's contents contains an IV and is symmetrically encrypted
	 * @param $content
	 * @return boolean
	 * @note see also OCA\Encryption\Util->isEncryptedPath()
	 */
	public static function isCatfileContent($content) {

		if (!$content) {

			return false;

		}

		$noPadding = self::removePadding($content);

		// Fetch encryption metadata from end of file
		$meta = substr($noPadding, -22);

		// Fetch IV from end of file
		$iv = substr($meta, -16);

		// Fetch identifier from start of metadata
		$identifier = substr($meta, 0, 6);

		if ($identifier === '00iv00') {

			return true;

		} else {

			return false;

		}

	}

	/**
	 * Check if a file is encrypted according to database file cache
	 * @param string $path
	 * @return bool
	 */
	public static function isEncryptedMeta($path) {

		// TODO: Use DI to get \OC\Files\Filesystem out of here

		// Fetch all file metadata from DB
		$metadata = \OC\Files\Filesystem::getFileInfo($path);

		// Return encryption status
		return isset($metadata['encrypted']) && ( bool )$metadata['encrypted'];

	}

	/**
	 * @brief Check if a file is encrypted via legacy system
	 * @param $data
	 * @param string $relPath The path of the file, relative to user/data;
	 *        e.g. filename or /Docs/filename, NOT admin/files/filename
	 * @return boolean
	 */
	public static function isLegacyEncryptedContent($isCatFileContent, $relPath) {

		// Fetch all file metadata from DB
		$metadata = \OC\Files\Filesystem::getFileInfo($relPath, '');

		// If a file is flagged with encryption in DB, but isn't a
		// valid content + IV combination, it's probably using the
		// legacy encryption system
		if (isset($metadata['encrypted'])
			&& $metadata['encrypted'] === true
			&& $isCatFileContent === false
		) {

			return true;

		} else {

			return false;

		}

	}

	/**
	 * @brief Symmetrically encrypt a string
	 * @param $plainContent
	 * @param $iv
	 * @param string $passphrase
	 * @return string encrypted file content
	 */
	private static function encrypt($plainContent, $iv, $passphrase = '') {

		if ($encryptedContent = openssl_encrypt($plainContent, 'AES-128-CFB', $passphrase, false, $iv)) {
			return $encryptedContent;
		} else {
			\OCP\Util::writeLog('Encryption library', 'Encryption (symmetric) of content failed', \OCP\Util::ERROR);
			\OCP\Util::writeLog('Encryption library', openssl_error_string(), \OCP\Util::ERROR);
			return false;

		}

	}

	/**
	 * @brief Symmetrically decrypt a string
	 * @param $encryptedContent
	 * @param $iv
	 * @param $passphrase
	 * @throws \Exception
	 * @return string decrypted file content
	 */
	private static function decrypt($encryptedContent, $iv, $passphrase) {

		if ($plainContent = openssl_decrypt($encryptedContent, 'AES-128-CFB', $passphrase, false, $iv)) {

			return $plainContent;

		} else {

			throw new \Exception('Encryption library: Decryption (symmetric) of content failed');

		}

	}

	/**
	 * @brief Concatenate encrypted data with its IV and padding
	 * @param string $content content to be concatenated
	 * @param string $iv IV to be concatenated
	 * @returns string concatenated content
	 */
	private static function concatIv($content, $iv) {

		$combined = $content . '00iv00' . $iv;

		return $combined;

	}

	/**
	 * @brief Split concatenated data and IV into respective parts
	 * @param string $catFile concatenated data to be split
	 * @returns array keys: encrypted, iv
	 */
	private static function splitIv($catFile) {

		// Fetch encryption metadata from end of file
		$meta = substr($catFile, -22);

		// Fetch IV from end of file
		$iv = substr($meta, -16);

		// Remove IV and IV identifier text to expose encrypted content
		$encrypted = substr($catFile, 0, -22);

		$split = array(
			'encrypted' => $encrypted,
			'iv' => $iv
		);

		return $split;

	}

	/**
	 * @brief Symmetrically encrypts a string and returns keyfile content
	 * @param string $plainContent content to be encrypted in keyfile
	 * @param string $passphrase
	 * @return bool|string
	 * @return string encrypted content combined with IV
	 * @note IV need not be specified, as it will be stored in the returned keyfile
	 * and remain accessible therein.
	 */
	public static function symmetricEncryptFileContent($plainContent, $passphrase = '') {

		if (!$plainContent) {
			\OCP\Util::writeLog('Encryption library', 'symmetrically encryption failed, no content given.', \OCP\Util::ERROR);
			return false;
		}

		$iv = self::generateIv();

		if ($encryptedContent = self::encrypt($plainContent, $iv, $passphrase)) {
			// Combine content to encrypt with IV identifier and actual IV
			$catfile = self::concatIv($encryptedContent, $iv);
			$padded = self::addPadding($catfile);

			return $padded;

		} else {
			\OCP\Util::writeLog('Encryption library', 'Encryption (symmetric) of keyfile content failed', \OCP\Util::ERROR);
			return false;
		}

	}


	/**
	 * @brief Symmetrically decrypts keyfile content
	 * @param $keyfileContent
	 * @param string $passphrase
	 * @throws \Exception
	 * @return bool|string
	 * @internal param string $source
	 * @internal param string $target
	 * @internal param string $key the decryption key
	 * @returns string decrypted content
	 *
	 * This function decrypts a file
	 */
	public static function symmetricDecryptFileContent($keyfileContent, $passphrase = '') {

		if (!$keyfileContent) {

			throw new \Exception('Encryption library: no data provided for decryption');

		}

		// Remove padding
		$noPadding = self::removePadding($keyfileContent);

		// Split into enc data and catfile
		$catfile = self::splitIv($noPadding);

		if ($plainContent = self::decrypt($catfile['encrypted'], $catfile['iv'], $passphrase)) {

			return $plainContent;

		} else {
			return false;
		}

	}

	/**
	 * @brief Decrypt private key and check if the result is a valid keyfile
	 * @param string $encryptedKey encrypted keyfile
	 * @param string $passphrase to decrypt keyfile
	 * @returns encrypted private key or false
	 *
	 * This function decrypts a file
	 */
	public static function decryptPrivateKey($encryptedKey, $passphrase) {

		$plainKey = self::symmetricDecryptFileContent($encryptedKey, $passphrase);

		// check if this a valid private key
		$res = openssl_pkey_get_private($plainKey);
		if (is_resource($res)) {
			$sslInfo = openssl_pkey_get_details($res);
			if (!isset($sslInfo['key'])) {
				$plainKey = false;
			}
		} else {
			$plainKey = false;
		}

		return $plainKey;

	}

	/**
	 * @brief Create asymmetrically encrypted keyfile content using a generated key
	 * @param string $plainContent content to be encrypted
	 * @param array $publicKeys array keys must be the userId of corresponding user
	 * @returns array keys: keys (array, key = userId), data
	 * @note symmetricDecryptFileContent() can decrypt files created using this method
	 */
	public static function multiKeyEncrypt($plainContent, array $publicKeys) {

		// openssl_seal returns false without errors if $plainContent
		// is empty, so trigger our own error
		if (empty($plainContent)) {

			throw new \Exception('Cannot mutliKeyEncrypt empty plain content');

		}

		// Set empty vars to be set by openssl by reference
		$sealed = '';
		$shareKeys = array();
		$mappedShareKeys = array();

		if (openssl_seal($plainContent, $sealed, $shareKeys, $publicKeys)) {

			$i = 0;

			// Ensure each shareKey is labelled with its
			// corresponding userId
			foreach ($publicKeys as $userId => $publicKey) {

				$mappedShareKeys[$userId] = $shareKeys[$i];
				$i++;

			}

			return array(
				'keys' => $mappedShareKeys,
				'data' => $sealed
			);

		} else {

			return false;

		}

	}

	/**
	 * @brief Asymmetrically encrypt a file using multiple public keys
	 * @param $encryptedContent
	 * @param $shareKey
	 * @param $privateKey
	 * @return bool
	 * @internal param string $plainContent content to be encrypted
	 * @returns string $plainContent decrypted string
	 * @note symmetricDecryptFileContent() can be used to decrypt files created using this method
	 *
	 * This function decrypts a file
	 */
	public static function multiKeyDecrypt($encryptedContent, $shareKey, $privateKey) {

		if (!$encryptedContent) {

			return false;

		}

		if (openssl_open($encryptedContent, $plainContent, $shareKey, $privateKey)) {

			return $plainContent;

		} else {

			\OCP\Util::writeLog('Encryption library', 'Decryption (asymmetric) of sealed content with share-key "'.$shareKey.'" failed', \OCP\Util::ERROR);

			return false;

		}

	}

	/**
	 * @brief Generates a pseudo random initialisation vector
	 * @return String $iv generated IV
	 */
	private static function generateIv() {

		if ($random = openssl_random_pseudo_bytes(12, $strong)) {

			if (!$strong) {

				// If OpenSSL indicates randomness is insecure, log error
				\OCP\Util::writeLog('Encryption library', 'Insecure symmetric key was generated using openssl_random_pseudo_bytes()', \OCP\Util::WARN);

			}

			// We encode the iv purely for string manipulation
			// purposes - it gets decoded before use
			$iv = base64_encode($random);

			return $iv;

		} else {

			throw new \Exception('Generating IV failed');

		}

	}

	/**
	 * @brief Generate a pseudo random 1024kb ASCII key, used as file key
	 * @returns $key Generated key
	 */
	public static function generateKey() {

		// Generate key
		if ($key = base64_encode(openssl_random_pseudo_bytes(183, $strong))) {

			if (!$strong) {

				// If OpenSSL indicates randomness is insecure, log error
				throw new \Exception('Encryption library, Insecure symmetric key was generated using openssl_random_pseudo_bytes()');

			}

			return $key;

		} else {

			return false;

		}

	}

	/**
	 * @brief Get the blowfish encryption handler for a key
	 * @param $key string (optional)
	 * @return \Crypt_Blowfish blowfish object
	 *
	 * if the key is left out, the default handler will be used
	 */
	private static function getBlowfish($key = '') {

		if ($key) {

			return new \Crypt_Blowfish($key);

		} else {

			return false;

		}

	}

	/**
	 * @brief decrypts content using legacy blowfish system
	 * @param string $content the cleartext message you want to decrypt
	 * @param string $passphrase
	 * @return string cleartext content
	 *
	 * This function decrypts an content
	 */
	public static function legacyDecrypt($content, $passphrase = '') {

		$bf = self::getBlowfish($passphrase);

		$decrypted = $bf->decrypt($content);

		return $decrypted;
	}

	/**
	 * @param $data
	 * @param string $key
	 * @param int $maxLength
	 * @return string
	 */
	public static function legacyBlockDecrypt($data, $key = '', $maxLength = 0) {

		$result = '';
		while (strlen($data)) {
			$result .= self::legacyDecrypt(substr($data, 0, 8192), $key);
			$data = substr($data, 8192);
		}
		if ($maxLength > 0) {
			return substr($result, 0, $maxLength);
		} else {
			return rtrim($result, "\0");
		}
	}

}
