<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SaaS Public Routes — Pricing / Checkout / Payment
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'saas', 'namespace' => 'Saas', 'middleware' => ['web']], function () {

    Route::get('/pricing', 'PricingController@index')->name('saas.pricing');
    Route::get('/pricing/{slug}/checkout', 'PricingController@showCheckout')->name('saas.checkout');
    Route::post('/pricing/checkout', 'PricingController@processCheckout')->name('saas.checkout.process');
    Route::get('/payment/success', 'PricingController@paymentSuccess')->name('saas.payment.success');
    Route::get('/payment/{subscription}', 'PricingController@payment')->name('saas.payment');

});

/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix'     => 'superadmin',
    'namespace'  => 'SuperAdmin',
    'middleware' => ['web'],
    'as'         => 'superadmin.',
], function () {

    // Auth
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'Auth\LoginController@login');
    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

    Route::group(['middleware' => 'super_admin'], function () {

        Route::get('/', 'DashboardController@index')->name('dashboard');

        // Preview site as guest (opens homepage without super admin session)
        Route::get('/preview-site', 'DashboardController@previewSite')->name('preview.site');

        // Plans
        Route::group(['prefix' => 'plans', 'as' => 'plans.'], function () {
            Route::get('/', 'PlanController@index')->name('index');
            Route::get('/pricing', 'PlanController@pricing')->name('pricing');
            Route::get('/create', 'PlanController@create')->name('create');
            Route::post('/store', 'PlanController@store')->name('store');
            Route::get('/{id}/edit', 'PlanController@edit')->name('edit');
            Route::post('/{id}/update', 'PlanController@update')->name('update');
            Route::get('/{id}/toggle', 'PlanController@toggleStatus')->name('toggle');
        });

        // Organizations
        Route::group(['prefix' => 'organizations', 'as' => 'organizations.'], function () {
            Route::get('/', 'OrganizationController@index')->name('index');
            Route::get('/create', 'OrganizationController@create')->name('create');
            Route::post('/store', 'OrganizationController@store')->name('store');
            Route::get('/{id}', 'OrganizationController@show')->name('show');
            Route::get('/{id}/toggle-status', 'OrganizationController@toggleStatus')->name('toggleStatus');
            Route::post('/{id}/assign-plan', 'OrganizationController@assignPlan')->name('assignPlan');
            Route::get('/{id}/delete', 'OrganizationController@destroy')->name('destroy');
        });

    });
});
