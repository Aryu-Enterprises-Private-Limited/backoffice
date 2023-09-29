<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

//Employee Setup route
$routes->add('/' . EMPLOYEE_PATH . '/logout', 'Employee_setup::logout', ['namespace' => 'App\Controllers\Employee']);
$routes->add('/' . ADMIN_PATH . '/logout', 'Admin::logout', ['namespace' => 'App\Controllers\Admin']);


$routes->get('/', 'Employee_setup::index', ['namespace' => 'App\Controllers\Employee']);
$routes->post('/' . EMPLOYEE_PATH . '/do-login', 'Employee_setup::do_login', ['namespace' => 'App\Controllers\Employee']);
$routes->get('/' . EMPLOYEE_PATH . '/dashboard', 'Employee_setup::dashboard', ['namespace' => 'App\Controllers\Employee']);


$routes->get('/' . EMPLOYEE_PATH . '/attendance', 'Attendance::index', ['namespace' => 'App\Controllers\Employee']);
$routes->add('/' . EMPLOYEE_PATH . '/attendance/update', 'Attendance::insertUpdate', ['namespace' => 'App\Controllers\Employee']);
//$routes->add('/' . EMPLOYEE_PATH . '/attendance/list', 'Attendance::list',['namespace' => 'App\Controllers\Employee']);
$routes->add('/' . EMPLOYEE_PATH . '/attendance/list_ajax', 'Attendance::list_ajax', ['namespace' => 'App\Controllers\Employee']);



//client protal route
// $routes->get('/'. CLIENT_PATH , 'Client::login',['namespace' => 'App\Controllers\Client']);




//admin protal route
// $routes->add('/' . ADMIN_PATH . '/login', 'Admin::login', ['namespace' => 'App\Controllers\Admin']);
$routes->get('/' . ADMIN_PATH, 'Admin::login', ['namespace' => 'App\Controllers\Admin']);
$routes->post('/' . ADMIN_PATH . '/do-login', 'Admin::do_login', ['namespace' => 'App\Controllers\Admin']);
$routes->get('/' . ADMIN_PATH . '/dashboard', 'Admin::dashboard', ['namespace' => 'App\Controllers\Admin']);

//LMS Route
$routes->add('/' . ADMIN_PATH . '/lms/list', 'Lms::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/lms/list_ajax', 'Lms::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/lms/add', 'Lms::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/lms/edit/(:segment)', 'Lms::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/lms/update', 'Lms::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/lms/change-status', 'Lms::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/lms/view/(:segment)', 'Lms::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/lms/delete', 'Lms::delete', ['namespace' => 'App\Controllers\Admin']);


// CRM Route
$routes->add('/' . ADMIN_PATH . '/crm/list', 'Crm::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/crm/list_ajax', 'Crm::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/crm/add', 'Crm::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/crm/edit/(:segment)', 'Crm::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/crm/update', 'Crm::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/crm/change-status', 'Crm::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/crm/view/(:segment)', 'Crm::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/crm/delete', 'Crm::delete', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/crm/view_doc/(:segment)', 'Crm::showFile', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/crm/get-notes_details', 'Crm::get_notes_data', ['namespace' => 'App\Controllers\Admin']);

//employee route
$routes->add('/' . ADMIN_PATH . '/employee/list', 'Employee::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/employee/list_ajax', 'Employee::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/employee/add', 'Employee::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/employee/edit/(:segment)', 'Employee::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/employee/form_valid', 'Employee::form_validation', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/employee/change-status', 'Employee::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/employee/view/(:segment)', 'Employee::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/employee/delete', 'Employee::delete', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/employee/view_doc/(:segment)', 'Employee::showFile', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/employee/get_dept_opt_ajax', 'Employee::get_dept_opt_ajax', ['namespace' => 'App\Controllers\Admin']);


//attendance route
$routes->add('/' . ADMIN_PATH . '/attendance/list', 'Attendance::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/attendance/list_ajax', 'Attendance::list_ajax', ['namespace' => 'App\Controllers\Admin']);

//client route
$routes->add('/' . ADMIN_PATH . '/client/list', 'Client::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/client/list_ajax', 'Client::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/client/add', 'Client::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/client/edit/(:segment)', 'Client::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/client/update', 'Client::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/client/change-status', 'Client::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/client/view/(:segment)', 'Client::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/client/delete', 'Client::delete', ['namespace' => 'App\Controllers\Admin']);

//report route
$routes->add('/' . ADMIN_PATH . '/report/list', 'Report::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/report/list_ajax', 'Report::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/report/monthly_list', 'Report::monthly_list_ajax', ['namespace' => 'App\Controllers\Admin']);

//in-voice route
 $routes->add('/' . ADMIN_PATH . '/invoice', 'Invoice::index', ['namespace' => 'App\Controllers\Admin']);
//   $routes->add('/' . ADMIN_PATH . '/invoice/(:any)?', 'Invoice::index/$1', ['namespace' => 'App\Controllers\Admin']);
//$routes->add('/' . ADMIN_PATH . '/invoice/gen_invoice', 'Invoice::invoice_generate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/invoice/gen_invoice', 'Invoice::insertUpdate_preview', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/preview_invoice/(:segment)', 'Invoice::preview_pdf/$1', ['namespace' => 'App\Controllers\Admin']);

//role route
$routes->add('/' . ADMIN_PATH . '/role/list', 'Role::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/role/list_ajax', 'Role::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/role/add', 'Role::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/role/edit/(:segment)', 'Role::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/role/update', 'Role::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/role/change-status', 'Role::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/role/view/(:segment)', 'Role::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/role/delete', 'Role::delete', ['namespace' => 'App\Controllers\Admin']);




//department route
$routes->add('/' . ADMIN_PATH . '/department/list', 'Department::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/department/list_ajax', 'Department::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/department/add', 'Department::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/department/edit/(:segment)', 'Department::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/department/update', 'Department::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/department/change-status', 'Department::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/department/view/(:segment)', 'Department::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/department/delete', 'Department::delete', ['namespace' => 'App\Controllers\Admin']);


//schedule route
$routes->add('/' . ADMIN_PATH . '/schedule/list', 'Schedule::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/schedule/add', 'Schedule::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/schedule/update', 'Schedule::insertUpdate', ['namespace' => 'App\Controllers\Admin']);


//job route
$routes->add('/' . ADMIN_PATH . '/job/list', 'Job::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/job/list_ajax', 'Job::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/job/add', 'Job::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/job/edit/(:segment)', 'Job::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/job/update', 'Job::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/job/change-status', 'Job::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/job/view/(:segment)', 'Job::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/job/delete', 'Job::delete', ['namespace' => 'App\Controllers\Admin']);


//job type route
$routes->add('/' . ADMIN_PATH . '/job_type/list', 'Jobtype::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/job_type/list_ajax', 'Jobtype::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/job_type/add', 'Jobtype::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/job_type/edit/(:segment)', 'Jobtype::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/job_type/update', 'Jobtype::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/job_type/change-status', 'Jobtype::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/job_type/view/(:segment)', 'Jobtype::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/job_type/delete', 'Jobtype::delete', ['namespace' => 'App\Controllers\Admin']);

//application route
$routes->add('/' . ADMIN_PATH . '/application/list', 'Application::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/application/list_ajax', 'Application::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/application/add', 'Application::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/application/edit/(:segment)', 'Application::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/application/update', 'Application::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/application/change-status', 'Application::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/application/view/(:segment)', 'Application::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/application/delete', 'Application::delete', ['namespace' => 'App\Controllers\Admin']);

//interview route
$routes->add('/' . ADMIN_PATH . '/interview/list', 'Interview::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/interview/list_ajax', 'Interview::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/interview/add', 'Interview::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/interview/edit/(:segment)', 'Interview::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/interview/update', 'Interview::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/interview/change-status', 'Interview::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/interview/view/(:segment)', 'Interview::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/interview/delete', 'Interview::delete', ['namespace' => 'App\Controllers\Admin']);


//stage route
$routes->add('/' . ADMIN_PATH . '/stage/list', 'Stage::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/stage/list_ajax', 'Stage::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/stage/add', 'Stage::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/stage/edit/(:segment)', 'Stage::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/stage/update', 'Stage::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/stage/change-status', 'Stage::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/stage/view/(:segment)', 'Stage::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/stage/delete', 'Stage::delete', ['namespace' => 'App\Controllers\Admin']);


//reason rejection route
$routes->add('/' . ADMIN_PATH . '/reason_rejection/list', 'Reason_rejection::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/reason_rejection/list_ajax', 'Reason_rejection::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/reason_rejection/add', 'Reason_rejection::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/reason_rejection/edit/(:segment)', 'Reason_rejection::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/reason_rejection/update', 'Reason_rejection::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/reason_rejection/change-status', 'Reason_rejection::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/reason_rejection/view/(:segment)', 'Reason_rejection::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/reason_rejection/delete', 'Reason_rejection::delete', ['namespace' => 'App\Controllers\Admin']);


//candidates route
$routes->add('/' . ADMIN_PATH . '/candidates/list', 'Candidate::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/candidates/list_ajax', 'Candidate::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/candidates/add', 'Candidate::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/candidates/edit/(:segment)', 'Candidate::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/candidates/update', 'Candidate::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/candidates/change-status', 'Candidate::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/candidates/view/(:segment)', 'Candidate::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/candidates/delete', 'Candidate::delete', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/candidates/view_doc/(:segment)', 'Candidate::showFile', ['namespace' => 'App\Controllers\Admin']);

//pay route
$routes->add('/' . ADMIN_PATH . '/pay/list', 'Pay::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/pay/list_ajax', 'Pay::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/pay/add', 'Pay::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/pay/edit/(:segment)', 'Pay::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/pay/update', 'Pay::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/pay/change-status', 'Pay::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/pay/view/(:segment)', 'Pay::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/pay/delete', 'Pay::delete', ['namespace' => 'App\Controllers\Admin']);

//public_holiday route
$routes->add('/' . ADMIN_PATH . '/public_holiday/list', 'Public_holiday::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/public_holiday/list_ajax', 'Public_holiday::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/public_holiday/add', 'Public_holiday::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/public_holiday/edit/(:segment)', 'Public_holiday::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/public_holiday/update', 'Public_holiday::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/public_holiday/change-status', 'Public_holiday::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/public_holiday/view/(:segment)', 'Public_holiday::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/public_holiday/delete', 'Public_holiday::delete', ['namespace' => 'App\Controllers\Admin']);

//interview task route
$routes->add('/' . ADMIN_PATH . '/interview_task/list', 'Interview_task::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/interview_task/list_ajax', 'Interview_task::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/interview_task/add', 'Interview_task::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/interview_task/edit/(:segment)', 'Interview_task::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/interview_task/update', 'Interview_task::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/interview_task/change-status', 'Interview_task::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/interview_task/view/(:segment)', 'Interview_task::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/interview_task/delete', 'Interview_task::delete', ['namespace' => 'App\Controllers\Admin']);

//emp.bank_info route
$routes->add('/' . ADMIN_PATH . '/emp_bank_info/list', 'Employee_bank_info::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/emp_bank_info/list_ajax', 'Employee_bank_info::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/emp_bank_info/add', 'Employee_bank_info::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/emp_bank_info/edit/(:segment)', 'Employee_bank_info::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/emp_bank_info/update', 'Employee_bank_info::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/emp_bank_info/change-status', 'Employee_bank_info::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/emp_bank_info/view/(:segment)', 'Employee_bank_info::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/emp_bank_info/delete', 'Employee_bank_info::delete', ['namespace' => 'App\Controllers\Admin']);

//category route
$routes->add('/' . ADMIN_PATH . '/category/list', 'Category::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/category/list_ajax', 'Category::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/category/add', 'Category::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/category/edit/(:segment)', 'Category::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/category/update', 'Category::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/category/change-status', 'Category::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/category/view/(:segment)', 'Category::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/category/delete', 'Category::delete', ['namespace' => 'App\Controllers\Admin']);

//reminder alerts
$routes->add('/' . ADMIN_PATH . '/reminder_alert/list', 'Reminder_alert::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/reminder_alert/list_ajax', 'Reminder_alert::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/reminder_alert/add', 'Reminder_alert::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/reminder_alert/edit/(:segment)', 'Reminder_alert::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/reminder_alert/update', 'Reminder_alert::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/reminder_alert/change-status', 'Reminder_alert::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/reminder_alert/view/(:segment)', 'Reminder_alert::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/reminder_alert/delete', 'Reminder_alert::delete', ['namespace' => 'App\Controllers\Admin']);

//expenses alerts
$routes->add('/' . ADMIN_PATH . '/expense/list', 'Expense::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/expense/list_ajax', 'Expense::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/expense/add', 'Expense::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/expense/edit/(:segment)', 'Expense::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/expense/update', 'Expense::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/expense/change-status', 'Expense::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/expense/view/(:segment)', 'Expense::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/expense/delete', 'Expense::delete', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/expense/alert_close', 'Expense::alert_close_ajax', ['namespace' => 'App\Controllers\Admin']);


//income alerts
$routes->add('/' . ADMIN_PATH . '/income/list', 'Income::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/income/list_ajax', 'Income::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/income/add', 'Income::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/income/edit/(:segment)', 'Income::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/income/update', 'Income::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/income/change-status', 'Income::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/income/view/(:segment)', 'Income::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/income/delete', 'Income::delete', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/income/alert_close', 'Income::alert_close_ajax', ['namespace' => 'App\Controllers\Admin']);

//billed acc alerts
$routes->add('/' . ADMIN_PATH . '/billed_acc/list', 'Billed_account::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/billed_acc/list_ajax', 'Billed_account::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/billed_acc/add', 'Billed_account::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/billed_acc/edit/(:segment)', 'Billed_account::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/billed_acc/update', 'Billed_account::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/billed_acc/change-status', 'Billed_account::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/billed_acc/view/(:segment)', 'Billed_account::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/billed_acc/delete', 'Billed_account::delete', ['namespace' => 'App\Controllers\Admin']);

//gst route
$routes->add('/' . ADMIN_PATH . '/gst/list', 'Gst::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/gst/list_ajax', 'Gst::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/gst/add', 'Gst::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/gst/edit/(:segment)', 'Gst::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/gst/update', 'Gst::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/gst/change-status', 'Gst::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/gst/view/(:segment)', 'Gst::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/gst/delete', 'Gst::delete', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/gst/view_doc/(:segment)', 'Gst::showFile', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/gst/alert_close', 'Gst::alert_close_ajax', ['namespace' => 'App\Controllers\Admin']);

//itr route
$routes->add('/' . ADMIN_PATH . '/itr/list', 'Itr::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/itr/list_ajax', 'Itr::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/itr/add', 'Itr::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/itr/edit/(:segment)', 'Itr::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/itr/update', 'Itr::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/itr/change-status', 'Itr::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/itr/view/(:segment)', 'Itr::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/itr/delete', 'Itr::delete', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/itr/view_doc/(:segment)', 'Itr::showFile', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/itr/alert_close', 'Itr::alert_close_ajax', ['namespace' => 'App\Controllers\Admin']);

//pf route 
$routes->add('/' . ADMIN_PATH . '/pf/list', 'Provident_fund::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/pf/list_ajax', 'Provident_fund::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/pf/add', 'Provident_fund::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/pf/edit/(:segment)', 'Provident_fund::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/pf/update', 'Provident_fund::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/pf/change-status', 'Provident_fund::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/pf/view/(:segment)', 'Provident_fund::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/pf/delete', 'Provident_fund::delete', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/pf/view_doc/(:segment)', 'Provident_fund::showFile', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/pf/alert_close', 'Provident_fund::alert_close_ajax', ['namespace' => 'App\Controllers\Admin']);

//tds route 
$routes->add('/' . ADMIN_PATH . '/tds/list', 'Tds::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/tds/list_ajax', 'Tds::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/tds/add', 'Tds::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/tds/edit/(:segment)', 'Tds::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/tds/update', 'Tds::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/tds/change-status', 'Tds::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/tds/view/(:segment)', 'Tds::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/tds/delete', 'Tds::delete', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/tds/view_doc/(:segment)', 'Tds::showFile', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/tds/alert_close', 'Tds::alert_close_ajax', ['namespace' => 'App\Controllers\Admin']);


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
