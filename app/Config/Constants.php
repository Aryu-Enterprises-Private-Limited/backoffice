<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2_592_000);
defined('YEAR')   || define('YEAR', 31_536_000);
defined('DECADE') || define('DECADE', 315_360_000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
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
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0);        // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1);          // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3);         // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4);   // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5);  // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7);     // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8);       // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9);      // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125);    // highest automatically-assigned error code



defined('ADMIN_PATH')          || define('ADMIN_PATH', 'admin');
defined('EMPLOYEE_PATH')          || define('EMPLOYEE_PATH', 'employee');
// defined('CLIENT_PATH')          || define('CLIENT_PATH', 'client');



defined('APP_NAME')            || define('APP_NAME', 'Aryu');
defined('INVOICE_NO')            || define('INVOICE_NO', 'AYE');


//uploads file path
defined('CRM_DOC_PATH')  || define('CRM_DOC_PATH', './uploads/crm_doc');
defined('EMPLOYEE_RESUME_DOC_PATH')  || define('EMPLOYEE_RESUME_DOC_PATH', './uploads/employee_cv_doc');
defined('CANDIDATES_RESUME_DOC_PATH')  || define('CANDIDATES_RESUME_DOC_PATH', './uploads/candidates_cv_doc');


//database table name
defined('ADMIN_USERS')                  || define('ADMIN_USERS', 'admin');
defined('LMS')                  || define('LMS', 'lms');
defined('NOTES')                  || define('NOTES', 'notes');
defined('CRM')                  || define('CRM', 'crm');
defined('EMPLOYEE_DETAILS')          || define('EMPLOYEE_DETAILS', 'employee_details');
defined('EMPLOYEE_ATTENDANCE')          || define('EMPLOYEE_ATTENDANCE', 'employee_attendance');
defined('CLIENT_DETAILS')          || define('CLIENT_DETAILS', 'client_details');
defined('INVOICE_DETAILS')          || define('INVOICE_DETAILS', 'invoice_details');
defined('EMPLOYEE_ROLE')          || define('EMPLOYEE_ROLE', 'employee_role');
defined('DEPARTMENT_DETAILS')          || define('DEPARTMENT_DETAILS', 'department_details');
defined('SCHEDULE_HOURS')          || define('SCHEDULE_HOURS', 'schedule_hours');
defined('JOBS')          || define('JOBS', 'jobs_opening');
defined('APPLICATION_STATUS')          || define('APPLICATION_STATUS', 'application_status');
defined('INTERVIEW_STATUS')          || define('INTERVIEW_STATUS', 'interview_status');
defined('STAGE')          || define('STAGE', 'stage');
defined('REASON_REJECTION')          || define('REASON_REJECTION', 'reason_rejection');
defined('CANDIDATES_DETAILS')          || define('CANDIDATES_DETAILS', 'candidates_details');

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_LOW instead.
 */
define('EVENT_PRIORITY_LOW', 200);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_NORMAL instead.
 */
define('EVENT_PRIORITY_NORMAL', 100);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_HIGH instead.
 */
define('EVENT_PRIORITY_HIGH', 10);
