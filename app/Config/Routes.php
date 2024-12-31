<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
   require SYSTEMPATH . 'Config/Routes.php';
}

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
$routes->get('/', 'Home::index');


/* =======================================================================
                                Auth Routes
   =====================================================================*/
$routes->group('admin', ['filter' => 'authGuard'], function ($routes) {
   $routes->post('trigger-reminders', 'Admin\ReminderController::triggerReminders');
});
$routes->cli('automated-reminder/sendAutomatedReminders', 'admin\AutomatedReminder::sendAutomatedReminders');

$routes->get('/user/google_login', 'Auth::google_login');
$routes->get('/user/google_callback', 'Auth::google_callback');
$routes->get('user/facebook_login', 'Auth::facebook_login');
$routes->get('user/facebook_callback', 'Auth::facebook_callback');

$routes->post('user/store', 'Auth::store');
$routes->post('user/login', 'Auth::login');
$routes->post('user/forget', 'Auth::forget');
$routes->post('user/updatepassword', 'Auth::updatepassword');
$routes->get('user/reset-password/(:any)/(:any)', 'Auth::resetpass_callback/$1/$2');
$routes->get('user/register', 'Auth::register_callback');
$routes->get('user/forgetpass', 'Auth::forget_callback');
$routes->get('logout', 'Auth::logout');
$routes->get('admin/profile', 'Auth::profile', ['filter' => 'authGuard']);
$routes->post('update/profile', 'Auth::update_profile');
$routes->get('user/verify_otp', 'Auth::verify_otp');
$routes->post('user/check_otp', 'Auth::check_otp');
$routes->post('user/resend_otp', 'Auth::resend_otp');




/* =======================================================================
                                Admin Routes
   =====================================================================*/
$routes->get('dashboard', 'Home::dashboard', ['filter' => 'authGuard']);
$routes->get('admin/appointment/view_all', 'admin\Appointment::view_all', ['filter' => 'authGuard']);

$routes->get('admin/terms/view_all', 'admin\Terms::terms', ['filter' => 'authGuard']);
$routes->get('patient/reports/view_all', 'patient\Reports::reports', ['filter' => 'authGuard']);
$routes->post('admin/report/send', 'Auth::sendReport');




//doctors
$routes->get('doctor/add', 'admin\Doctor::index', ['filter' => 'authGuard']);
$routes->get('doctor/all', 'admin\Doctor::view_all', ['filter' => 'authGuard']);
$routes->get('doctor/delete/(:num)', 'admin\Doctor::delete/$1');

$routes->post('admin/store/user', 'admin\Doctor::user_register_callback', ['filter' => 'authGuard']);
$routes->get('edit/user/(:num)', 'admin\Doctor::edit_callback/$1', ['filter' => 'authGuard']);
$routes->post('admin/update/user', 'admin\Doctor::update_user_callback', ['filter' => 'authGuard']);



//Patients
$routes->get('admin/pateint/add', 'admin\Patient::index', ['filter' => 'authGuard']);
$routes->get('admin/patient/all', 'admin\Patient::view_all', ['filter' => 'authGuard']);
$routes->get('patient/delete/(:num)', 'admin\Patient::deleted/$1');


$routes->get('admin/pet/add/(:num)', 'admin\Patient::add_pet/$1', ['filter' => 'authGuard']);
$routes->get('admin/pet/all/(:num)', 'admin\Patient::view_all_patent/$1', ['filter' => 'authGuard']);
$routes->post('admin/pet/add_new', 'admin\Patient::add_new_pet', ['filter' => 'authGuard']);
$routes->get('adminpet/delete/(:num)', 'admin\Patient::deleted/$1');





//Appointments
$routes->get('appointment/add', 'admin\Appointment::index', ['filter' => 'authGuard']);
$routes->get('appointment/send_reminder', 'admin\Appointment::send_reminder', ['filter' => 'authGuard']);
$routes->get('appointment/all', 'admin\Appointment::view_all', ['filter' => 'authGuard']);
$routes->get('appointment/start_appointment/(:num)', 'admin\Appointment::startAppointment/$1', ['filter' => 'authGuard']);
$routes->get('appointment/admin/edit/(:num)', 'admin\Appointment::edit_admin/$1');
$routes->post('appointment/get/pets', 'admin\Appointment::get_pets');
$routes->get('appointment/send_reminder/appointment/(:num)/(:num)', 'admin\Appointment::send_reminders/$1/$2');
$routes->post('appointment/save_appointment', 'admin\Appointment::save_appointment');



//Services


//Sales






//Pet Boarded

///confinement




//Medication





//invoice



//records



/* =======================================================================
                                Patient Routes
   =====================================================================*/

//Pets
$routes->get('pet/add', 'patient\Pet::index', ['filter' => 'authGuard']);
$routes->get('pet/all', 'patient\Pet::view_all', ['filter' => 'authGuard']);
$routes->get('pet/all', 'patient\Pet:view_all_patient');
$routes->get('pet/edit/(:num)', 'patient\Pet::edit/$1');
$routes->get('pet/delete/(:num)', 'patient\Pet::delete/$1');
$routes->post('pet/store', 'patient\Pet::store');
$routes->post('pet/update', 'patient\Pet::update');


//Appointment

$routes->get('appointment/add_new', 'patient\Appointment::add_appointment', ['filter' => 'authGuard']);
$routes->get('appointment/view_all', 'admin\Appointment::all_appointment', ['filter' => 'authGuard']);
$routes->get('appointment/edit/(:num)', 'admin\Appointment::edit/$1');
$routes->get('appointment/delete/(:num)', 'admin\Appointment::delete/$1');
$routes->post('appointment/getSlots', 'admin\Appointment::get_slots');
$routes->post('appointment/store', 'admin\Appointment::store');
$routes->post('appointment/update', 'admin\Appointment::update');

// Slot
// Fetch available slots for a specific date
$routes->post('appointment/getDateSlots', 'admin\Appointment::get_date_slots');

// Fetch all dates that have available slots
$routes->post('appointment/getAvailableDates', 'admin\Appointment::get_available_dates');



//records
$routes->get('patient/all', 'patient\Records::view_all', ['filter' => 'authGuard']);








/* =======================================================================
                                Doctor Routes
   =====================================================================*/


$routes->get('calendar/add', 'doctor\Calendar::index', ['filter' => 'authGuard']);

//timeslot

$routes->get('slot/add', 'doctor\slotController::index', ['filter' => 'authGuard']);
$routes->get('slot/all', 'doctor\slotController::view_all', ['filter' => 'authGuard']);
$routes->post('slot/delete/(:num)', 'doctor\slotController::delete/$1');
$routes->post('slot/store', 'doctor\slotController::store');
$routes->post('slot/update', 'doctor\slotController::update');

//Inventory



//Patient
$routes->get('doctor/patient/add', 'doctor\Patient::index', ['filter' => 'authGuard']);
$routes->get('doctor/patient/all', 'doctor\Patient::view_all', ['filter' => 'authGuard']); // For viewing all patients
$routes->get('doctor/patient/view/(:num)', 'doctor\Patient::view_all_patient/$1'); // For viewing all pets of a patient
$routes->get('patient/delete/(:num)', 'doctor\Patient::deleted/$1');


$routes->get('doctor/pet/add/(:num)', 'doctor\Patient::add_pet/$1', ['filter' => 'authGuard']);
$routes->get('doctor/pet/all/(:num)', 'doctor\Patient::view_all_patient/$1', ['filter' => 'authGuard']);
$routes->post('doctor/pet/add_new', 'doctor\Patient::add_new_pet', ['filter' => 'authGuard']);
$routes->get('doctor/pet/delete/(:num)', 'doctor\Patient::deleted/$1');
$routes->post('doctor/pet/update_status/(:num)', 'doctor\Patient::update_status/$1');






//reports
$routes->get('patient/reports/all', 'admin\Reports::all_patient_reports', ['filter' => 'authGuard']);
$routes->get('doctor/medicne/all', 'admin\Medication::view_all_medicine', ['filter' => 'authGuard']);

//Services
$routes->get('service/add', 'doctor\Services::index', ['filter' => 'authGuard']);
$routes->get('service/all', 'doctor\Services::view_all', ['filter' => 'authGuard']);
$routes->get('service/edit/(:num)', 'doctor\Services::edit/$1');
$routes->get('service/delete/(:num)', 'doctor\Services::delete/$1');
$routes->post('service/store', 'doctor\Services::store');
$routes->post('service/update', 'doctor\Services::update');


//Medication
$routes->get('medicne/add', 'doctor\Medication::index', ['filter' => 'authGuard']);
$routes->get('medicne/all', 'doctor\Medication::view_all', ['filter' => 'authGuard']);
$routes->get('medicne/edit/(:num)', 'doctor\Medication::edit/$1');
$routes->get('medicne/delete/(:num)', 'doctor\Medication::delete/$1');
$routes->post('medicne/store', 'doctor\Medication::store');
$routes->post('medicine/update', 'doctor\Medication::update');
$routes->post('medicne/delete_exp', 'doctor\Medication::delete_exp');
$routes->post('medicne/delete_low', 'doctor\Medication::delete_low');
$routes->post('medicine/restock', 'doctor\Medication::restock');
$routes->post('medicne/download_report', 'doctor\Medication::download_report');





//Records
    // $routes->get('records/add', 'doctor\Records::index', ['filter'=>'authGuard']);
    $routes->get('records/all', 'doctor\Records::view_all', ['filter' => 'authGuard']);
    $routes->get('records/pets/(:num)', 'doctor\Records::view_all_patient/$1'); 
    $routes->post('records/delete/(:num)', 'doctor\Records::delete/$1');
    $routes->get('records/card/(:num)', 'doctor\Records::card/$1', ['filter' => 'authGuard']);
    $routes->post('records/save', 'doctor\Records::save', ['filter' => 'authGuard']);
    $routes->post('records/saveinfo', 'doctor\Records::saveinfo', ['filter' => 'authGuard']);
    $routes->get('records/table/(:num)', 'doctor\Records::table/$1', ['filter' => 'authGuard']);
    $routes->get('records/view/(:num)', 'doctor\Records::view/$1', ['filter' => 'authGuard']);
    $routes->get('records/delete/(:num)', 'doctor\Records::delete/$1');
    $routes->get('records/vaccine/(:num)', 'doctor\Records::vaccinePDF/$1', ['filter' => 'authGuard']);
    $routes->get('records/deworming/(:num)', 'doctor\Records::dewormingPDF/$1', ['filter' => 'authGuard']);
    $routes->get('records/parasite/(:num)', 'doctor\Records::parasitePDF/$1', ['filter' => 'authGuard']);
    $routes->get('records/full_history/(:num)', 'doctor\Records::fullPetHistoryPDF/$1', ['filter' => 'authGuard']);




//Confinement
$routes->get('confinement/add', 'doctor\Confinement::index', ['filter' => 'authGuard']);
$routes->post('confinement/save', 'doctor\Confinement::save', ['filter' => 'authGuard']);
$routes->get('confinement/edit/(:num)', 'doctor\Confinement::edit/$1');
$routes->post('confinement/update', 'doctor\Confinement::update');
$routes->get('confinement/all', 'doctor\Confinement::view_all', ['filter' => 'authGuard']);
$routes->get('confinement/delete/(:num)', 'doctor\Confinement::delete/$1');
$routes->get('doctor/followup_confinement/(:num)', 'doctor\Confinement::followup_confinement/$1', ['filter' => 'authGuard']);
$routes->post('doctor/send_followup_email', 'doctor\Confinement::send_followup_email', ['filter' => 'authGuard']);

//Boarding
$routes->get('board/add', 'doctor\PetBoarded::index', ['filter' => 'authGuard']);
$routes->get('board/all', 'doctor\PetBoarded::view_all', ['filter' => 'authGuard']);
$routes->post('board/getPet/', 'doctor\PetBoarded::get_pet');
$routes->get('board/edit/(:num)', 'doctor\PetBoarded::edit/$1');
$routes->get('board/delete/(:num)', 'doctor\PetBoarded::delete/$1');
$routes->post('board/store', 'doctor\PetBoarded::store');
$routes->post('board/update', 'doctor\PetBoarded::update');

//Sales
$routes->get('sales/add', 'doctor\Sales::index', ['filter' => 'authGuard']);
$routes->get('sales/all', 'doctor\Sales::view_all', ['filter' => 'authGuard']);
$routes->post('sales/generateMonthlyReport', 'doctor\Sales::generateMonthlyReport');
$routes->get('sales/view_services', 'doctor\Sales::view_services', ['filter' => 'authGuard']);
$routes->post('sales/generateMonthlyReportServices', 'doctor\Sales::generateMonthlyReportServices');
$routes->get('sales/downloadExcel', 'doctor\Sales::downloadExcel');
$routes->get('sales/downloadExcelServices', 'doctor\Sales::downloadExcelServices');

//Invoice
$routes->get('invoice/add', 'doctor\Invoice::index', ['filter' => 'authGuard']);
$routes->get('invoice/all', 'doctor\Invoice::view_all', ['filter' => 'authGuard']);
$routes->post('invoice/service/price', 'doctor\Invoice::get_service_price');
$routes->post('invoice/medicine/price', 'doctor\Invoice::get_medicine_price');
$routes->post('invoice/getMedicinesByCategory', 'doctor\Invoice::getMedicinesByCategory');

$routes->post('invoice/store', 'doctor\Invoice::store');
$routes->get('invoice/delete/(:num)', 'doctor\Invoice::delete/$1');
$routes->get('billing/report/print/(:num)', 'doctor\Invoice::billing_report_print/$1');
$routes->get('patient/invoice', 'doctor\Invoice::patient_all', ['filter' => 'authGuard']);







/* =======================================================================
                                Reports Routes
   =====================================================================*/

$routes->post('report/pateint/store', 'admin\Reports::add_patient_report');
$routes->post('report/pateint/update', 'admin\Reports::update_patient_report');
$routes->get('patient/report/print/(:num)', 'admin\Reports::patient_report_print/$1');


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