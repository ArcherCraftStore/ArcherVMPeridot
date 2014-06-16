<?php
/**
 * Copyright (c) 2011 Bart Visscher <bartv@thisnet.nl>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

class OC_JSON{
	static protected $send_content_type_header = false;
	/**
	 * set Content-Type header to jsonrequest
	 */
	public static function setContentTypeHeader($type='application/json') {
		if (!self::$send_content_type_header) {
			// We send json data
			header( 'Content-Type: '.$type . '; charset=utf-8');
			self::$send_content_type_header = true;
		}
	}

	/**
	* Check if the app is enabled, send json error msg if not
	*/
	public static function checkAppEnabled($app) {
		if( !OC_App::isEnabled($app)) {
			$l = OC_L10N::get('lib');
			self::error(array( 'data' => array( 'message' => $l->t('Application is not enabled') )));
			exit();
		}
	}

	/**
	* Check if the user is logged in, send json error msg if not
	*/
	public static function checkLoggedIn() {
		if( !OC_User::isLoggedIn()) {
			$l = OC_L10N::get('lib');
			self::error(array( 'data' => array( 'message' => $l->t('Authentication error') )));
			exit();
		}
	}

	/**
	 * @brief Check an ajax get/post call if the request token is valid.
	 * @return json Error msg if not valid.
	 */
	public static function callCheck() {
		if( !OC_Util::isCallRegistered()) {
			$l = OC_L10N::get('lib');
			self::error(array( 'data' => array( 'message' => $l->t('Token expired. Please reload page.') )));
			exit();
		}
	}

	/**
	* Check if the user is a admin, send json error msg if not
	*/
	public static function checkAdminUser() {
		if( !OC_User::isAdminUser(OC_User::getUser())) {
			$l = OC_L10N::get('lib');
			self::error(array( 'data' => array( 'message' => $l->t('Authentication error') )));
			exit();
		}
	}

	/**
	 * Check is a given user exists - send json error msg if not
	 * @param string $user
	 */
	public static function checkUserExists($user) {
		if (!OCP\User::userExists($user)) {
			$l = OC_L10N::get('lib');
			OCP\JSON::error(array('data' => array('message' => $l->t('Unknown user'))));
			exit;
		}
	}



	/**
	* Check if the user is a subadmin, send json error msg if not
	*/
	public static function checkSubAdminUser() {
		if(!OC_SubAdmin::isSubAdmin(OC_User::getUser())) {
			$l = OC_L10N::get('lib');
			self::error(array( 'data' => array( 'message' => $l->t('Authentication error') )));
			exit();
		}
	}

	/**
	* Send json error msg
	*/
	public static function error($data = array()) {
		$data['status'] = 'error';
		self::encodedPrint($data);
	}

	/**
	* Send json success msg
	*/
	public static function success($data = array()) {
		$data['status'] = 'success';
		self::encodedPrint($data);
	}

	/**
	 * Convert OC_L10N_String to string, for use in json encodings
	 */
	protected static function to_string(&$value) {
		if ($value instanceof OC_L10N_String) {
			$value = (string)$value;
		}
	}

	/**
	* Encode and print $data in json format
	*/
	public static function encodedPrint($data, $setContentType=true) {
		if($setContentType) {
			self::setContentTypeHeader();
		}
		array_walk_recursive($data, array('OC_JSON', 'to_string'));
		echo json_encode($data);
	}
}
