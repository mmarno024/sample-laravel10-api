<?php

use App\Http\Controllers\API\Auth\AuthApiController;
use App\Http\Controllers\API\Emails\SendEmailController;
use App\Http\Controllers\API\Etc\SystemHistoryController;
use App\Http\Controllers\API\Lookup\LookupController;
use App\Http\Controllers\API\Master\Region\CityController;
use App\Http\Controllers\API\Master\Region\DistrictController;
use App\Http\Controllers\API\Master\Region\ProvinceController;
use App\Http\Controllers\API\Master\Region\SubdistrictController;
use App\Http\Controllers\API\Master\System\AccessKeyController;
use App\Http\Controllers\API\Master\System\MenuController;
use App\Http\Controllers\API\Master\System\MenuGroupController;
use App\Http\Controllers\API\Master\System\RoleAccessController;
use App\Http\Controllers\API\Popup\PopupController;
use App\Http\Controllers\API\Setting\ProfileController;
use App\Http\Controllers\API\Setting\SettingAccessController;
use App\Http\Controllers\API\Setting\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('user-login', [AuthApiController::class, 'userLogin']);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    // AUTH
    Route::post('logout', [AuthApiController::class, 'logout']);

    // MASTER->REGION
    Route::apiResource('province', ProvinceController::class);
    Route::apiResource('city', CityController::class);
    Route::apiResource('district', DistrictController::class);
    Route::apiResource('subdistrict', SubdistrictController::class);

    // MASTER->SYSTEM
    Route::apiResource('menu-group', MenuGroupController::class);
    Route::apiResource('menu', MenuController::class);
    Route::apiResource('access-key', AccessKeyController::class);
    Route::apiResource('role-access', RoleAccessController::class);

    // SETTING->PROFILE
    Route::apiResource('profile', ProfileController::class);
    Route::get('profile-history', [ProfileController::class, 'profileHistory']);

    // SETTING->USER
    Route::apiResource('users', UserController::class);
    Route::get('users-export-xls', [UserController::class, 'exportXlsUser']);
    Route::get('check-userid', [UserController::class, 'getCheckUserID']);
    Route::patch('reset-password/{user}', [UserController::class, 'resetPassword']);

    // SETTING->ACCESS
    Route::apiResource('setting-access', SettingAccessController::class);

    // LOOKUP-DATA
    Route::get('lookup-province', [LookupController::class, 'lookupProvince']);
    Route::get('lookup-city', [LookupController::class, 'lookupCity']);
    Route::get('lookup-district', [LookupController::class, 'lookupDistrict']);
    Route::get('lookup-subdistrict', [LookupController::class, 'lookupSubdistrict']);
    Route::get('lookup-waterhole-type', [LookupController::class, 'lookupWaterholeType']);
    Route::get('lookup-menu-group', [LookupController::class, 'lookupMenuGroup']);
    Route::get('lookup-menu', [LookupController::class, 'lookupMenu']);
    Route::get('lookup-access-key', [LookupController::class, 'lookupAccessKey']);
    Route::get('lookup-role-access', [LookupController::class, 'lookupRoleAccess']);
    Route::get('lookup-user', [LookupController::class, 'lookupUser']);
    Route::get('lookup-company', [LookupController::class, 'lookupCompany']);

    // POPUP-DATA
    Route::get('popup-user', [PopupController::class, 'popupUser']);
    Route::get('popup-role-access', [PopupController::class, 'popupRoleAccess']);

    // ACCESS-MENU
    Route::get('access-menu', [SettingAccessController::class, 'accessMenu']);
    Route::get('access-access-key', [SettingAccessController::class, 'accessAccessKey']);
    Route::get('access-allow-menu', [SettingAccessController::class, 'accessAllowMenu']);
    Route::get('access-allow-menu-route', [SettingAccessController::class, 'accessAllowMenuRoute']);

    // ETC-SYSTEM-HISTORY
    Route::apiResource('system-history', SystemHistoryController::class);
    Route::get('system-history-export-xls', [SystemHistoryController::class, 'exportXlsHistory']);

    // EMAIL
    Route::get('send-email/{id}', [SendEmailController::class, 'sendEmail']);
});
