<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('is_exist_email', 'Api\EmployeesController@checkExistEmail');
Route::post('is_valid_account', 'Api\EmployeesController@checkValidAccount');
Route::post('is_active_account', 'Api\EmployeesController@activeAccount');
Route::post('is_activated_email', 'Api\EmployeesController@checkActivatedEmail');
Route::post('check_verification_code', 'Api\EmployeesController@checkVerificationCode');
Route::post('update_pincode', 'Api\EmployeesController@updatePinCode');
Route::post('register_account', 'Api\EmployeesController@register');
Route::post('login', 'Api\EmployeesController@login');

//Api Employees
Route::middleware('auth:api')->group(function () {
    Route::post('payment_employee', 'Api\EmployeesController@requestsForAdvances');
    Route::post('payroll_confirm', 'Api\EmployeesController@payrollConfirm');
});
