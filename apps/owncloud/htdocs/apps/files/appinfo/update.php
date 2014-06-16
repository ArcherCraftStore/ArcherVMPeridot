<?php

// fix webdav properties,add namespace in front of the property, update for OC4.5
$installedVersion=OCP\Config::getAppValue('files', 'installed_version');
if (version_compare($installedVersion, '1.1.6', '<')) {
	$concat = OC_DB::getConnection()->getDatabasePlatform()->
		getConcatExpression( '\'{DAV:}\'', '`propertyname`' );
	$query = OC_DB::prepare('
		UPDATE `*PREFIX*properties`
		SET    `propertyname` = ' . $concat . '
		WHERE  `propertyname` NOT LIKE \'{%\'
	');
	$query->execute();
}

//update from OC 3

//try to remove remaining files.
//Give a warning if not possible

$filesToRemove = array(
	'ajax',
	'appinfo',
	'css',
	'js',
	'l10n',
	'templates',
	'admin.php',
	'download.php',
	'index.php',
	'settings.php'
);

foreach($filesToRemove as $file) {
	$filepath = OC::$SERVERROOT . '/files/' . $file;
	if(!file_exists($filepath)) {
		continue;
	}
	$success = OCP\Files::rmdirr($filepath);
	if($success === false) {
		//probably not sufficient privileges, give up and give a message.
		OCP\Util::writeLog('files', 'Could not clean /files/ directory.'
				.' Please remove everything except webdav.php from ' . OC::$SERVERROOT . '/files/', OCP\Util::ERROR);
		break;
	}
}
