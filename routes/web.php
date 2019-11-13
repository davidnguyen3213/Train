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

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::group(['namespace' => 'Admin'], function() {
    Route::get('/', 'EmployeeController@index')->name('employees.index');

    // Authentication Routes...
    Route::group(['namespace' => 'Auth'], function() {
        Route::get('login', 'LoginAdminController@showLoginForm')->name('login');
        Route::post('login', 'LoginAdminController@login');
        Route::any('logout', 'LoginAdminController@logout')->name('logout');

    });

    Route::group(['prefix' => 'admin'], function () {
        // employees routes
        Route::get('/employee', 'EmployeeController@index')->name('employee.index');
        Route::post('/employee', 'EmployeeController@index')->name('employee.index');
        Route::post('/employee/store', 'EmployeeController@store')->name('employee.store');
//        Route::post('/employee/delete/{employee_id}', 'EmployeeController@delete')->name('employee.delete');
        Route::get('/employee/edit/{employee_id}', 'EmployeeController@showEditForm')->name('employee.edit');
        Route::get('/employee/getDepartmentsByEmployerId/{employer_id}', 'EmployeeController@getDepartmentsByEmployerId')->name('employee.getDepartmentsByEmployerId');

        // employer routes
        Route::get('/employer', 'EmployerController@index')->name('employer.index');
        Route::post('/employer', 'EmployerController@index')->name('employer.index');
        Route::post('/employer/store', 'EmployerController@store')->name('employer.store');
        // Route::post('/employer/delete/{employer_id}', 'EmployerController@delete')->name('employer.delete');
        // Route::get('/employer/edit/{employer_id}', 'EmployerController@showEditForm')->name('employer.edit');

        // employee payroll routes
        Route::get('/employee-payroll/{employee_id}', 'EmployeePayrollController@index')->name('employee-payroll.index');
        Route::get('/employee-payroll', 'EmployeePayrollController@index')->name('employee-payroll.index');
        Route::post('/employee-payroll/{employee_id}', 'EmployeePayrollController@index')->name('employee-payroll.index');
        Route::post('/employee-payroll/store/{employee_id}', 'EmployeePayrollController@store')->name('employee-payroll.store');
        Route::post('/employee-payroll/update/{employer_id}', 'EmployeePayrollController@update')->name('employee-payroll.update');

        // department routes
        Route::get('/employer/{id}/department', 'DepartmentController@index')->name('department.index');
        Route::post('/employer/{id}/department', 'DepartmentController@index')->name('department.index');
        Route::post('/employer/{id}/department/store', 'DepartmentController@store')->name('department.store');
//        Route::post('/employer/delete/{employer_id}', 'EmployerController@delete')->name('employer.delete');
//        Route::get('/employer/edit/{employer_id}', 'EmployerController@showEditForm')->name('employer.edit');


    });
});
