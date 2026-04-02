<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Delivery\Http\Controllers\DeliveryController;
use Modules\Delivery\Http\Controllers\DeliveryTimeController;
use Modules\Delivery\Http\Controllers\ShippingController;
use Modules\Role\Enums\Permission;

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

// Route::group([], function () {
//     Route::resource('delivery', DeliveryController::class)->names('delivery');
// });

Route::apiResource('delivery-times', DeliveryTimeController::class, [
    'only' => ['index', 'show'],
]);

/**
 * *****************************************
 * Authorized Route for Super Admin only
 * *****************************************
 */
Route::group(['middleware' => ['permission:'.Permission::SuperAdmin->value, 'auth:sanctum']], function (): void {
    Route::apiResource('delivery-times', DeliveryTimeController::class, [
        'only' => ['store', 'update', 'destroy'],
    ]);

    Route::apiResource('shippings', ShippingController::class);
});
