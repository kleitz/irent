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


/**
 * Auth routes
 */
Route::group(['namespace' => 'Auth'], function () {

    // Authentication Routes...
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::get('logout', 'LoginController@logout')->name('logout');

    // Registration Routes...
    if (config('auth.users.registration')) {
        Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'RegisterController@register');
        Route::post('addCaretaker', 'RegisterController@addCaretaker')->name('addCaretaker');
    }

    // Password Reset Routes...
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'ResetPasswordController@reset');

    // Confirmation Routes...
    if (config('auth.users.confirm_email')) {
        Route::get('confirm/{user_by_code}', 'ConfirmController@confirm')->name('confirm');
        Route::get('confirm/resend/{user_by_email}', 'ConfirmController@sendEmail')->name('confirm.send');
    }

    // Social Authentication Routes...
    Route::get('social/redirect/{provider}', 'SocialLoginController@redirect')->name('social.redirect');
    Route::get('social/login/{provider}', 'SocialLoginController@login')->name('social.login');
});

/**
 * Backend routes
 */
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin'], function () {

    // Dashboard
    Route::get('/', 'DashboardController@index')->name('dashboard');

    //Users
    Route::get('users', 'UserController@index')->name('users');
    Route::get('users/{user}', 'UserController@show')->name('users.show');
    Route::get('users/{user}/edit', 'UserController@edit')->name('users.edit');
    Route::put('users/{user}', 'UserController@update')->name('users.update');
    Route::delete('users/{user}', 'UserController@destroy')->name('users.destroy');
    Route::get('permissions', 'PermissionController@index')->name('permissions');
    Route::get('apartments', 'ApartmentController@index')->name('apartments');
    Route::post('apartmentstore', 'ApartmentController@store')->name('apartmentstore');
    Route::get('apartments/{apartment}/show', 'ApartmentController@show')->name('apartments.show');
    Route::get('apartments/{apartment}/edit', 'ApartmentController@edit')->name('apartment.edit');
    Route::put('apartments/{apartment}', 'ApartmentController@update')->name('apartments.update');
    Route::delete('apartments/{id}', 'ApartmentController@destroy')->name('apartment.delete');
    Route::post('housestore/{apartment_id}', 'HouseController@create')->name('housestore');
    Route::get('houses/{house}/show', 'HouseController@show')->name('houses.show');
    Route::get('house/{id}', 'HouseController@destroy')->name('houses.delete');
    Route::put('house/{apartment}', 'HouseController@update')->name('houses.update');
    Route::get('houses/{house}/edit', 'HouseController@edit')->name('house.edit');
    Route::get('permissions/{user}/repeat', 'PermissionController@repeat')->name('permissions.repeat');
    Route::get('dashboard/log-chart', 'DashboardController@getLogChartData')->name('dashboard.log.chart');
    Route::get('dashboard/registration-chart', 'DashboardController@getRegistrationChartData')->name('dashboard.registration.chart');
    Route::get('repairs/{apartment_id}/show', 'RepairController@index')->name('repairs.show');
    Route::post('repairs/{user_id}/{apartment_id}/create', 'RepairController@create')->name('repairstore');
    Route::get('payments/{apartment_id}/show', 'PaymentController@index')->name('payments.show');
    Route::post('payments/{user_id}/{apartment_id}/create', 'PaymentController@create')->name('paymentstore');
    Route::post('notifications/{user_id}/{apartment_id}/create', 'NotificationController@create')->name('notificationstore');
    Route::post('house/{house_id}/rent', 'HouseController@rentUser')->name('house.rent');
});


Route::get('/', 'HomeController@index');

/**
 * Membership
 */
Route::group(['as' => 'protection.'], function () {
    Route::get('membership', 'MembershipController@index')->name('membership')->middleware('protection:' . config('protection.membership.product_module_number') . ',protection.membership.failed');
    Route::get('membership/access-denied', 'MembershipController@failed')->name('membership.failed');
    Route::get('membership/clear-cache/', 'MembershipController@clearValidationCache')->name('membership.clear_validation_cache');
});

Route::get("admin/apartments/{id}/delete", [
    "as" => "admin.apartments.delete",
    "uses" => "Admin\ApartmentController@destroy"
]);

Route::post("admin/addCaretaker", [
    "as" => "admin.caretaker.add",
    "uses" => "CaretakerController@create"
]);

Route::get("user/{id}/delete", [
    "as" => "admin.user.delete",
    "uses" => "Admin\UserController@destroy"
]);