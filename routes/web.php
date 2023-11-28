<?php

Route::view('/', 'welcome');
Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // User Alerts
    Route::delete('user-alerts/destroy', 'UserAlertsController@massDestroy')->name('user-alerts.massDestroy');
    Route::get('user-alerts/read', 'UserAlertsController@read');
    Route::resource('user-alerts', 'UserAlertsController', ['except' => ['edit', 'update']]);

    // Sopir
    Route::delete('sopirs/destroy', 'SopirController@massDestroy')->name('sopirs.massDestroy');
    Route::post('sopirs/parse-csv-import', 'SopirController@parseCsvImport')->name('sopirs.parseCsvImport');
    Route::post('sopirs/process-csv-import', 'SopirController@processCsvImport')->name('sopirs.processCsvImport');
    Route::resource('sopirs', 'SopirController');

    // Kendaraan
    Route::delete('kendaraans/destroy', 'KendaraanController@massDestroy')->name('kendaraans.massDestroy');
    Route::post('kendaraans/media', 'KendaraanController@storeMedia')->name('kendaraans.storeMedia');
    Route::post('kendaraans/ckmedia', 'KendaraanController@storeCKEditorImages')->name('kendaraans.storeCKEditorImages');
    Route::post('kendaraans/parse-csv-import', 'KendaraanController@parseCsvImport')->name('kendaraans.parseCsvImport');
    Route::post('kendaraans/process-csv-import', 'KendaraanController@processCsvImport')->name('kendaraans.processCsvImport');
    Route::resource('kendaraans', 'KendaraanController');

    // Pinjam
    Route::delete('pinjams/destroy', 'PinjamController@massDestroy')->name('pinjams.massDestroy');
    Route::post('pinjams/media', 'PinjamController@storeMedia')->name('pinjams.storeMedia');
    Route::post('pinjams/ckmedia', 'PinjamController@storeCKEditorImages')->name('pinjams.storeCKEditorImages');
    Route::post('pinjams/parse-csv-import', 'PinjamController@parseCsvImport')->name('pinjams.parseCsvImport');
    Route::post('pinjams/process-csv-import', 'PinjamController@processCsvImport')->name('pinjams.processCsvImport');
    Route::resource('pinjams', 'PinjamController');

    // Log Pinjam
    Route::delete('log-pinjams/destroy', 'LogPinjamController@massDestroy')->name('log-pinjams.massDestroy');
    Route::resource('log-pinjams', 'LogPinjamController');

    Route::get('system-calendar', 'SystemCalendarController@index')->name('systemCalendar');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
Route::group(['as' => 'frontend.', 'namespace' => 'Frontend', 'middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // User Alerts
    Route::delete('user-alerts/destroy', 'UserAlertsController@massDestroy')->name('user-alerts.massDestroy');
    Route::resource('user-alerts', 'UserAlertsController', ['except' => ['edit', 'update']]);

    // Sopir
    Route::delete('sopirs/destroy', 'SopirController@massDestroy')->name('sopirs.massDestroy');
    Route::resource('sopirs', 'SopirController');

    // Kendaraan
    Route::delete('kendaraans/destroy', 'KendaraanController@massDestroy')->name('kendaraans.massDestroy');
    Route::post('kendaraans/media', 'KendaraanController@storeMedia')->name('kendaraans.storeMedia');
    Route::post('kendaraans/ckmedia', 'KendaraanController@storeCKEditorImages')->name('kendaraans.storeCKEditorImages');
    Route::resource('kendaraans', 'KendaraanController');

    // Pinjam
    Route::delete('pinjams/destroy', 'PinjamController@massDestroy')->name('pinjams.massDestroy');
    Route::post('pinjams/media', 'PinjamController@storeMedia')->name('pinjams.storeMedia');
    Route::post('pinjams/ckmedia', 'PinjamController@storeCKEditorImages')->name('pinjams.storeCKEditorImages');
    Route::resource('pinjams', 'PinjamController');

    // Log Pinjam
    Route::delete('log-pinjams/destroy', 'LogPinjamController@massDestroy')->name('log-pinjams.massDestroy');
    Route::resource('log-pinjams', 'LogPinjamController');

    Route::get('frontend/profile', 'ProfileController@index')->name('profile.index');
    Route::post('frontend/profile', 'ProfileController@update')->name('profile.update');
    Route::post('frontend/profile/destroy', 'ProfileController@destroy')->name('profile.destroy');
    Route::post('frontend/profile/password', 'ProfileController@password')->name('profile.password');
});
