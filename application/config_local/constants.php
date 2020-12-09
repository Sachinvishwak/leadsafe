<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


/*Application development contants*/
//keys (session, third party API keys like google, stripe etc)  

/* Firebase API key for notifications */
define('GOOGLE_API_KEY','AIzaSyDjhKBJtoevmCuR5iD1El6cuDHTMByw9Co'); 
/* Firebase API key for notifications */
define('NOTIFICATION_KEY',''); 

/* session keys */
define('USER_SESS_KEY', 'app_user_sess'); 
define('ADMIN_USER_SESS_KEY', 'app_admin_user_sess');

//DB tables
define('USERS', 'users');
define('ADMIN', 'admin');
define('NOTIFICATIONS', 'notifications');

//uploads path
define('USER_AVATAR_PATH', 'uploads/users/'); //user avatar
define('USER_DEFAULT_AVATAR', 'backend_assets/img/avatars/1.png'); //user placeholder image
//uploads path
define('ADMIN_AVATAR_PATH', 'uploads/admin/'); //user avatar
define('ADMIN_DEFAULT_AVATAR', 'backend_assets/img/avatars/1.png'); //user placeholder image

//Title, Site name, Copyright etc
define('SITE_NAME','Bild IT'); //your project name
define('COPYRIGHT',' &copy; ' . date('Y'). ', Bild IT. All rights reserved.');
define('INFO_EMAIL','info@project.com'); //your project name

//ios and android down link
define('ios', 'http://18.191.246.188/com.bildit.apk');
define('android', 'http://18.191.246.188/com.bildit.apk');
define('iosimage','backend_assets/img/forios.png');
define('androidimage','backend_assets/img/forandroid.png');

//common messages
define('UNKNOWN_ERR', 'Something went wrong. Please try again');
define('FAIL', 'fail');
define('SUCCESS', 'success');
define('BAD_REQUEST',	'400');

//Asset path (In place of "APP_" you can use your own project specific prefix)
/* Frontend */
define('APP_ASSETS_JS', 'frontend_assets/js/');
define('APP_ASSETS_CSS', 'frontend_assets/css/');
define('APP_ASSETS_PLUGIN', 'frontend_assets/plugins/');
define('APP_ASSETS_IMG', 'frontend_assets/img/');

/* Admin */
define('APP_ADMIN_ASSETS_JS', 'backend_assets/js/');
define('APP_ADMIN_ASSETS_CSS', 'backend_assets/css/');
define('APP_ADMIN_ASSETS_PLUGIN', 'backend_assets/plugins/');
define('APP_ADMIN_ASSETS_IMG', 'backend_assets/img/');
define('BUCKETNAME', 'cg-robinsons');
define('S3JOBS_URL', 'https://cg-robinsons.s3.us-east-2.amazonaws.com/jobs/');
define('S3VEHICLE_URL', 'https://cg-robinsons.s3.us-east-2.amazonaws.com/vehicles/');