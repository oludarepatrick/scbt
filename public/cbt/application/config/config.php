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
define('URL', 'https://grafton.schooldrive.com.ng/stud_dashboard/');

define('qLink', 'https://cbt.schooldriveng.com/');

/**
 * Configuration for: Database
 * This is the place where you define your database credentials, database type etc.
 

 */
define('DB_TYPE', 'mysql');
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'schooldr_gifton_cbt');
define('DB_USER', 'schooldr_grafton_user');
define('DB_PASS', 't[)OS!VW?GOa@#');

define('DB_TYPE_2', 'mysql');
define('DB_HOST_2', '127.0.0.1');
define('DB_NAME_2', 'schooldr_grafton');
define('DB_USER_2', 'schooldr_grafton_user');
define('DB_PASS_2', 't[)OS!VW?GOa@#');

