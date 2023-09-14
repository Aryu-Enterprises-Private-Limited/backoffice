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
$routes->add('/' . ADMIN_PATH . '/lms/get-notes_details', 'Lms::get_notes_data', ['namespace' => 'App\Controllers\Admin']);

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


//candidates route





//job route
$routes->add('/' . ADMIN_PATH . '/job/list', 'Job::index', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/job/list_ajax', 'Job::list_ajax', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/job/add', 'Job::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/job/edit/(:segment)', 'Job::add_edit', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/job/update', 'Job::insertUpdate', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/job/change-status', 'Job::update_status', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/job/view/(:segment)', 'Job::view', ['namespace' => 'App\Controllers\Admin']);
$routes->add('/' . ADMIN_PATH . '/job/delete', 'Job::delete', ['namespace' => 'App\Controllers\Admin']);

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
