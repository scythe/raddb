<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Dashboard routes
Route::name('index')->get('/', 'DashboardController@index'); // Survey schedule
Route::name('dashboard.dashboard')
    ->get('/dashboard', 'DashboardController@teststatus');
// Untested machines
Route::name('dashboard.showUntested')
    ->get('/dashboard/showUntested', 'DashboardController@showUntested');
// Pending surveys
Route::name('dashboard.showPending')
    ->get('/dashboard/showPending', 'DashboardController@showPending');
// Survey schedule
Route::name('dashboard.showSchedule')
    ->get('/dashboard/showSchedule', 'DashboardController@showSchedule');
// Count of surveys per month
Route::name('dashboard.surveyGraph')
    ->get('/dashboard/surveyGraph', 'DashboardController@surveyGraph');

// Machine controller
// Listing of inactive machines
Route::name('machines.inactive')
    ->get('/machines/inactive', 'MachineController@showInactive');
// Listing of removed machines
Route::name('machines.removed')
    ->get('/machines/removed', 'MachineController@showRemoved');
Route::resource('machines', 'MachineController');

// Test equipment controller
Route::name('testequipment.showCalDates')
    ->get('/testequipment/caldates', 'TestEquipmentController@showCalDates');
Route::resource('testequipment', 'TestEquipmentController');

// Contacts controller - deprecated from previous version
// Route::resource('contacts', 'ContactController');

// Generator data controller - haven't decided whether to  keep this or not
Route::name('gendata.create')
    ->get('surveydata/generator/{surveyId}/create', 'GenDataController@create');
Route::resource('gendata', 'GenDataController',
    ['except' => ['create']]);

// Operational notes controller
Route::name('opnotes.createOpNoteFor')
    ->get('opnotes/{$id}/create', 'OpNoteController@create');
Route::resource('opnotes', 'OpNoteController');

// Recommendation controller
Route::name('recommendations.createRecFor')
    ->get('recommendations/{id?}/create', 'RecommendationController@create');
Route::resource('recommendations', 'RecommendationController');

// Test Date controller
Route::name('surveys.createSurveyFor')
    ->get('surveys/{id?}/create', 'TestDateController@create');
Route::resource('surveys', 'TestDateController');

// Survey report controller
Route::name('surveyreports.create')
    ->get('surveyreports/{id?}/create', 'SurveyReportController@create');
Route::resource('surveyreports', 'SurveyReportController');

// Service report controller
Route::resource('servicereports', 'ServiceReportController');

// Tube controller
Route::name('tubes.createTubeFor')->get('tubes/{id}/create', 'TubeController@create');
Route::resource('tubes', 'TubeController');

// Routes for managing the lookup tables
// Location controller
// Show index of machines grouped by location
Route::name('locations.showLocationIndex')
    ->get('locations/', 'LocationController@showLocationIndex');
// List of machines for a selected location(s)
Route::name('locations.showLocation')
    ->get('locations/{id}', 'LocationController@showLocation');
Route::resource('admin/locations', 'LocationController');

// Manufacturer controller
// Show index of machines grouped by manufacturer
Route::name('manufacturers.showManufacturerIndex')
    ->get('manufacturers/', 'ManufacturerController@showManufacturerIndex');
// List of machines for a selected modality/modalities
Route::name('manufacturers.showManufacturer')
    ->get('manufacturers/{id}', 'ManufacturerController@showManufacturer');
Route::resource('admin/manufacturers', 'ManufacturerController');

// Modality controller
// Show index of machines grouped by modality
Route::name('modalities.showModalityIndex')
    ->get('modalities/', 'ModalityController@showModalityIndex');
// List of machines for a selected modality/modalities
Route::name('modalities.showModality')
    ->get('modalities/{id}', 'ModalityController@showModality');
Route::resource('admin/modalities', 'ModalityController');

// Testers controller
Route::resource('admin/testers', 'TesterController');

// Test types controller
Route::resource('admin/testtypes', 'TestTypeController');

// Photos controller. Used to handle uploading and updating photos of machines.
Route::name('photos.create')
    ->get('photos/{id}/create', 'MachinePhotoController@create');
Route::resource('photos', 'MachinePhotoController',
    ['except' => ['create']]);

// Route for user management
Route::resource('users', 'UserController');

// Route for experiments and tests
Route::resource('test', 'TestController');

// Authentication routes
Auth::routes();
Route::name('home.index')->get('/home', 'HomeController@index');
