<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CalendarDateController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

});

Route::get('admin/my_company', [CustomerController::class, 'my_company']);
Route::get('admin/my_orders', [CustomerController::class, 'my_orders']);
Route::get('admin/my_products', [CustomerController::class, 'my_products'])->name('admin.my_products');
Route::get('admin/approval/{id?}', [CustomerController::class, 'approval'])->name('admin.approval');
Route::get('admin/rejection/{id?}', [CustomerController::class, 'rejection'])->name('admin.rejection');
Route::get('admin/edit_product/{id?}', [CustomerController::class, 'edit_product'])->name('admin.edit_product');
Route::Post('admin/edit_products/{id?}', [CustomerController::class, 'edit_products'])->name('admin.edit_products');
Route::delete('admin/remove_products/{id?}', [CustomerController::class, 'remove_products'])->name('admin.remove_products');


Route::get('clients', [CustomerController::class, 'clients'])->name('clients');
Route::get('edit_client/{id?}', [CustomerController::class, 'edit_client'])->name('edit_client');
Route::Post('edit_clients/{id?}', [CustomerController::class, 'edit_clients'])->name('edit_clients');
Route::delete('remove_clients/{id?}', [CustomerController::class, 'remove_clients'])->name('remove_clients');


Route::get('check_card/{q?}/{type?}', [CustomerController::class, 'check_card'])->name('check_card');
Route::get('check_card_no/{q?}/{type?}', [CustomerController::class, 'check_card_no'])->name('check_card_no');
Route::get('card_service/{q?}', [CustomerController::class, 'card_service'])->name('card_service');
Route::get('submit_service/{client?}/{services?}/{q?}', [CustomerController::class, 'submit_service'])->name('submit_service');

Route::get('report/card/{q?}', [CustomerController::class, 'card'])->name('card');
Route::get('generatePDF_card/{q?}', [CustomerController::class, 'generatePDF_card'])->name('generatePDF_card');
Route::get('generatePDF_card_info/{q?}', [CustomerController::class, 'generatePDF_card_info'])->name('generatePDF_card_info');
Route::get('generatePDF_card_order/{q?}', [CustomerController::class, 'generatePDF_card_order'])->name('generatePDF_card_order');
Route::get('cards_from_to/{from?}/{to?}/{type?}/{pdf_download?}', [CustomerController::class, 'cards_from_to'])->name('cards_from_to');
//report services
Route::get('report/service/{q?}', [CustomerController::class, 'service'])->name('service');
Route::get('report/check_service/{from?}/{to?}/{type?}/{pdf_download?}', [CustomerController::class, 'check_service'])->name('check_service');
//report users
Route::get('report/user/{q?}', [CustomerController::class, 'user'])->name('user');
Route::get('report/check_user_total/{from?}/{to?}/{type?}', [CustomerController::class, 'check_user_total'])->name('check_user_total');
Route::get('report/check_user/{from?}/{to?}/{type?}/{pdf_download?}', [CustomerController::class, 'check_user'])->name('check_user');
//report hospital
Route::get('report/hospital/{q?}', [CustomerController::class, 'hospital'])->name('hospital');
Route::get('report/check_hospital_total/{from?}/{to?}/{type?}', [CustomerController::class, 'check_hospital_total'])->name('check_hospital_total');
Route::get('report/check_hospital/{from?}/{to?}/{type?}/{pdf_download?}', [CustomerController::class, 'check_hospital'])->name('check_hospital');
//report doctor
Route::get('report/doctor/{q?}', [CustomerController::class, 'doctor'])->name('doctor');
Route::get('report/check_doctor_total/{from?}/{to?}/{type?}', [CustomerController::class, 'check_doctor_total'])->name('check_doctor_total');
Route::get('report/check_doctor/{from?}/{to?}/{type?}/{pdf_download?}', [CustomerController::class, 'check_doctor'])->name('check_doctor');


//Calendar
Route::get('calendar/action', [CalendarDateController::class, 'action'])->name('action');
Route::get('calendar/{q?}', [CalendarDateController::class, 'calendar'])->name('calendar');
Route::get('all_calendar/{q?}', [CalendarDateController::class, 'all_calendar'])->name('all_calendar');
Route::post('action', [CalendarDateController::class, 'action'])->name('action');

Route::get('all_clinet/{card_type?}', [CalendarDateController::class, 'all_clinet'])->name('all_clinet');
Route::get('all_card', [CalendarDateController::class, 'all_card'])->name('all_card');
Route::get('all_services/{id?}', [CalendarDateController::class, 'all_services'])->name('all_services');


