<?php

/**
 * Configuration
 *
 * For more info about constants please @see http://php.net/manual/en/function.define.php
 * If you want to know why we use "define" instead of "const" @see http://stackoverflow.com/q/2447791/1114320
 */

/**
 * Configuration for: Error reporting
 * Useful to show every little problem during development, but only show hard errors in production
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);

/**
 * Configuration for: Project URL
 * Put your URL here, for local development "127.0.0.1" or "localhost" (plus sub-folder) is fine
 */
define('URL', 'http://127.0.0.1:8000/cbt/');

define('qLink', 'http://127.0.0.1:8000/');

/**
 * Configuration for: Database
 * This is the place where you define your database credentials, database type etc.
 

 */
define('DB_TYPE', 'mysql');
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'graftp_cbt'); //note that you need to change this to live server db before pushing
define('DB_USER', 'root');
define('DB_PASS', ''); //update the live server password or get rid of the config file before pushing

define('DB_TYPE_2', 'mysql');
define('DB_HOST_2', '127.0.0.1');
define('DB_NAME_2', 'gtpark'); //gtpark
define('DB_USER_2', 'root');
define('DB_PASS_2', '');

