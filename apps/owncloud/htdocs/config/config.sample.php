<?php

/* Only enable this for local development and not in productive environments */
/* This will disable the minifier and outputs some additional debug informations */
define("DEBUG", true);

$CONFIG = array(
/* Flag to indicate ownCloud is successfully installed (true = installed) */
"installed" => false,

/* Type of database, can be sqlite, mysql or pgsql */
"dbtype" => "sqlite",

/* Name of the ownCloud database */
"dbname" => "owncloud",

/* User to access the ownCloud database */
"dbuser" => "",

/* Password to access the ownCloud database */
"dbpassword" => "",

/* Host running the ownCloud database */
"dbhost" => "",

/* Prefix for the ownCloud tables in the database */
"dbtableprefix" => "",

/* Define the salt used to hash the user passwords. All your user passwords are lost if you lose this string. */
"passwordsalt" => "",

/* Force use of HTTPS connection (true = use HTTPS) */
"forcessl" => false,

/* Blacklist a specific file and disallow the upload of files with this name - WARNING: USE THIS ONLY IF YOU KNOW WHAT YOU ARE DOING. */
"blacklisted_files" => array('.htaccess'),

/* The automatic hostname detection of ownCloud can fail in certain reverse proxy situations. This option allows to manually override the automatic detection. You can also add a port. For example "www.example.com:88" */
"overwritehost" => "",

/* The automatic protocol detection of ownCloud can fail in certain reverse proxy situations. This option allows to manually override the protocol detection. For example "https" */
"overwriteprotocol" => "",

/* The automatic webroot detection of ownCloud can fail in certain reverse proxy situations. This option allows to manually override the automatic detection. For example "/domain.tld/ownCloud" */
"overwritewebroot" => "",

/* The automatic detection of ownCloud can fail in certain reverse proxy situations. This option allows to define a manually override condition as regular expression for the remote ip address. For example "^10\.0\.0\.[1-3]$" */
"overwritecondaddr" => "",

/* A proxy to use to connect to the internet. For example "myproxy.org:88" */
"proxy" => "",

/* The optional authentication for the proxy to use to connect to the internet. The format is: [username]:[password] */
"proxyuserpwd" => "",

/* List of trusted domains, to prevent host header poisoning ownCloud is only using these Host headers */
'trusted_domains' => array('demo.owncloud.org', 'otherdomain.owncloud.org'),

/* Theme to use for ownCloud */
"theme" => "",

/* Optional ownCloud default language - overrides automatic language detection on public pages like login or shared items. This has no effect on the user's language preference configured under "personal -> language" once they have logged in */
"default_language" => "en",

/* Path to the parent directory of the 3rdparty directory */
"3rdpartyroot" => "",

/* URL to the parent directory of the 3rdparty directory, as seen by the browser */
"3rdpartyurl" => "",

/* Default app to load on login */
"defaultapp" => "files",

/* Enable the help menu item in the settings */
"knowledgebaseenabled" => true,

/* Enable installing apps from the appstore */
"appstoreenabled" => true,

/* URL of the appstore to use, server should understand OCS */
"appstoreurl" => "http://api.apps.owncloud.com/v1",

/* Domain name used by ownCloud for the sender mail address, e.g. no-reply@example.com */
"mail_domain" => "example.com",

/* Enable SMTP class debugging */
"mail_smtpdebug" => false,

/* Mode to use for sending mail, can be sendmail, smtp, qmail or php, see PHPMailer docs */
"mail_smtpmode" => "sendmail",

/* Host to use for sending mail, depends on mail_smtpmode if this is used */
"mail_smtphost" => "127.0.0.1",

/* Port to use for sending mail, depends on mail_smtpmode if this is used */
"mail_smtpport" => 25,

/* SMTP server timeout in seconds for sending mail, depends on mail_smtpmode if this is used */
"mail_smtptimeout" => 10,

/* SMTP connection prefix or sending mail, depends on mail_smtpmode if this is used.
   Can be '', ssl or tls */
"mail_smtpsecure" => "",

/* authentication needed to send mail, depends on mail_smtpmode if this is used
 * (false = disable authentication)
 */
"mail_smtpauth" => false,

/* authentication type needed to send mail, depends on mail_smtpmode if this is used
 * Can be LOGIN (default), PLAIN or NTLM */
"mail_smtpauthtype" => "LOGIN",

/* Username to use for sendmail mail, depends on mail_smtpauth if this is used */
"mail_smtpname" => "",

/* Password to use for sendmail mail, depends on mail_smtpauth if this is used */
"mail_smtppassword" => "",

/* memcached hostname and port (Only used when xCache, APC and APCu are absent.) */
"memcached_server" => array('localhost', 11211),

/* How long should ownCloud keep deleted files in the trash bin, default value:  30 days */
'trashbin_retention_obligation' => 30,

/* Disable/Enable auto expire for the trash bin, by default auto expire is enabled */
'trashbin_auto_expire' => true,

/* allow user to change his display name, if it is supported by the back-end */
'allow_user_to_change_display_name' => true,

/* Check 3rdparty apps for malicious code fragments */
"appcodechecker" => "",

/* Check if ownCloud is up to date */
"updatechecker" => true,

/* Are we connected to the internet or are we running in a closed network? */
"has_internet_connection" => true,

/* Check if the ownCloud WebDAV server is working correctly. Can be disabled if not needed in special situations*/
"check_for_working_webdav" => true,

/* Check if .htaccess protection of data is working correctly. Can be disabled if not needed in special situations*/
"check_for_working_htaccess" => true,

/* Place to log to, can be owncloud and syslog (owncloud is log menu item in admin menu) */
"log_type" => "owncloud",

/* File for the owncloud logger to log to, (default is ownloud.log in the data dir) */
"logfile" => "",

/* Loglevel to start logging at. 0=DEBUG, 1=INFO, 2=WARN, 3=ERROR (default is WARN) */
"loglevel" => "",

/* date format to be used while writing to the owncloud logfile */
'logdateformat' => 'F d, Y H:i:s',

/* timezone used while writing to the owncloud logfile (default: UTC) */
'logtimezone' => 'Europe/Berlin',

/* Append all database queries and parameters to the log file.
 (watch out, this option can increase the size of your log file)*/
"log_query" => false,

/* Enable or disable the logging of IP addresses in case of webform auth failures */
"log_authfailip" => false,

/*
 * Configure the size in bytes log rotation should happen, 0 or false disables the rotation.
 * This rotates the current owncloud logfile to a new name, this way the total log usage
 * will stay limited and older entries are available for a while longer. The
 * total disk usage is twice the configured size.
 * WARNING: When you use this, the log entries will eventually be lost.
 */
'log_rotate_size' => false, // 104857600, // 100 MiB

/* Lifetime of the remember login cookie, default is 15 days */
"remember_login_cookie_lifetime" => 60*60*24*15,

/* Life time of a session after inactivity */
"session_lifetime" => 60 * 60 * 24,

/*
 * Enable/disable session keep alive when a user is logged in in the Web UI.
 * This is achieved by sending a "heartbeat" to the server to prevent
 * the session timing out.
 */
"session_keepalive" => true,

/* Custom CSP policy, changing this will overwrite the standard policy */
"custom_csp_policy" => "default-src 'self'; script-src 'self' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; frame-src *; img-src *; font-src 'self' data:; media-src *",

/* Enable/disable X-Frame-Restriction */
/* HIGH SECURITY RISK IF DISABLED*/
"xframe_restriction" => true,

/* The directory where the user data is stored, default to data in the owncloud
 * directory. The sqlite database is also stored here, when sqlite is used.
 */
// "datadirectory" => "",

/* Enable maintenance mode to disable ownCloud
   If you want to prevent users to login to ownCloud before you start doing some maintenance work,
   you need to set the value of the maintenance parameter to true.
   Please keep in mind that users who are already logged-in are kicked out of ownCloud instantly.
*/
"maintenance" => false,

"apps_paths" => array(

/* Set an array of path for your apps directories
 key 'path' is for the fs path and the key 'url' is for the http path to your
 applications paths. 'writable' indicates whether the user can install apps in this folder.
 You must have at least 1 app folder writable or you must set the parameter 'appstoreenabled' to false
*/
	array(
		'path'=> '/var/www/owncloud/apps',
		'url' => '/apps',
		'writable' => true,
	),
),
'user_backends'=>array(
	array(
		'class'=>'OC_User_IMAP',
		'arguments'=>array('{imap.gmail.com:993/imap/ssl}INBOX')
	)
),
//links to custom clients
'customclient_desktop' => '', //http://owncloud.org/sync-clients/
'customclient_android' => '', //https://play.google.com/store/apps/details?id=com.owncloud.android
'customclient_ios' => '', //https://itunes.apple.com/us/app/owncloud/id543672169?mt=8

// PREVIEW
'enable_previews' => true,
/* the max width of a generated preview, if value is null, there is no limit */
'preview_max_x' => null,
/* the max height of a generated preview, if value is null, there is no limit */
'preview_max_y' => null,
/* the max factor to scale a preview, default is set to 10 */
'preview_max_scale_factor' => 10,
/* custom path for libreoffice / openoffice binary */
'preview_libreoffice_path' => '/usr/bin/libreoffice',
/* cl parameters for libreoffice / openoffice */
'preview_office_cl_parameters' => '',

/* whether avatars should be enabled */
'enable_avatars' => true,

// Extra SSL options to be used for configuration
'openssl' => array(
	//'config' => '/absolute/location/of/openssl.cnf',
),

/* whether usage of the instance should be restricted to admin users only */
'singleuser' => false,
);
