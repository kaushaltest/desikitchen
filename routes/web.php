<?php

use App\Http\Controllers\Admin\Additionalmenu;
use App\Http\Controllers\Admin\Alacartemenu;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Daywisemenu;
use App\Http\Controllers\Admin\Neworder;
use App\Http\Controllers\Admin\Order;
use App\Http\Controllers\Admin\Partymenu;
use App\Http\Controllers\Admin\Subscription;
use App\Http\Controllers\Admin\Tables;
use App\Http\Controllers\Admin\Useraddress;
use App\Http\Controllers\Admin\Users;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Customer\Auth;
use App\Http\Controllers\Customer\Customerorder;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\Customeruseraddress;
use App\Http\Controllers\Customer\Menu;
use App\Http\Controllers\Customer\Subscription as CustomerSubscription;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    // ->middleware(['web', \App\Http\Middleware\RedirectIfAuthenticatedAdmin::class])
    ->group(function () {
        Route::get('/', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
    });
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['web', \App\Http\Middleware\IsAdmin::class])
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/get-info-card', [DashboardController::class, 'getInfoCardData'])->name('get-info-card');
        Route::middleware(['web', \App\Http\Middleware\IsSuperadmin::class])->group(function () {
            //day wise menu routes
            Route::get('/day-wise-menu', [Daywisemenu::class, 'index'])->name('daywisemenu');
            Route::get('/get-daywise-menu', [Daywisemenu::class, 'getDaywiseMenuData'], function () {})->name("get-daywise-menu");
            Route::post('/delete-daywisemenu', [Daywisemenu::class, 'deleteDayWiseMenu'], function () {})->name("delete-daywisemenu");
            Route::post('/add-update-daywise-menu', [Daywisemenu::class, 'addUpdateMenu'])->name('add-update-daywise-menu');
            Route::post('/menus/import', [Daywisemenu::class, 'import'])->name('menus.import');
            Route::get('/menus/export', [Daywisemenu::class, 'export'])->name('menus.export');


            //alacarte menu routes
            Route::get('/alacarte-menu', [Alacartemenu::class, 'index'])->name('alacartemenu');
            Route::get('/get-alacarte-menu', [Alacartemenu::class, 'getAlacarteMenuData'], function () {})->name("get-alacarte-menu");
            Route::post('/delete-alacartemenu', [Alacartemenu::class, 'deleteAlacarteMenu'], function () {})->name("delete-alacartemenu");
            Route::post('/add-update-alacarte-menu', [Alacartemenu::class, 'addUpdateMenu'])->name('add-update-alacarte-menu');
            Route::get('/get-category', [Alacartemenu::class, 'getCategory'])->name('get-category');
            Route::post('/add-category', [Alacartemenu::class, 'addCategory'])->name('add-category');
            Route::post('/import_alacarte', [Alacartemenu::class, 'import'])->name('import_alacarte');


            
            //party menu routes
            Route::get('/party-menu', [Partymenu::class, 'index'])->name('partymenu');
            Route::get('/get-party-menu', [Partymenu::class, 'getPartyMenuData'], function () {})->name("get-party-menu");
            Route::post('/delete-partymenu', [Partymenu::class, 'deletePartyMenu'], function () {})->name("delete-partymenu");
            Route::post('/add-update-party-menu', [Partymenu::class, 'addUpdateMenu'])->name('add-update-party-menu');

            //additional menu routes
            Route::get('/additional-menu', [Additionalmenu::class, 'index'])->name('additionalmenu');
            Route::get('/get-additional-menu', [Additionalmenu::class, 'getAdditionalMenuData'], function () {})->name("get-additional-menu");
            Route::post('/delete-additionalmenu', [Additionalmenu::class, 'deleteAdditionalMenu'], function () {})->name("delete-additionalmenu");
            Route::post('/add-update-additional-menu', [Additionalmenu::class, 'addUpdateMenu'])->name('add-update-additional-menu');
            Route::get('/order', [Order::class, 'index'])->name('order');

            Route::get('/get-order-list', [Order::class, 'getOrderListData'], function () {})->name("get-order-list");
            Route::post('/add-order', [Order::class, 'addOrder'])->name('add-order');

            Route::get('/checkmobileexist', [AdminLoginController::class, 'checkMobileExist'])->name('checkmobileexist');
            Route::post('/add-new-address', [Useraddress::class, 'addAddress'])->name('add-new-address');
            Route::post('/add-new-user', [Useraddress::class, 'addNewUser'])->name('add-new-user');
            //subscription
            Route::get('/subscription', [Subscription::class, 'index'])->name('subscription');
            Route::get('/get-all-subscription', [Subscription::class, 'getAllSubscription'], function () {})->name("get-all-subscription");
            Route::post('/delete-subscription', [Subscription::class, 'deleteSubscription'], function () {})->name("delete-subscription");
            Route::post('/add-update-subscription', [Subscription::class, 'addUpdateSubscription'])->name('add-update-subscription');

            //users
            Route::get('/users', [Users::class, 'index'])->name('users');
            Route::get('/get-all-users', [Users::class, 'getAllUser'], function () {})->name("get-all-users");
            Route::post('/delete-users', [Users::class, 'deleteUser'], function () {})->name("delete-users");
            Route::post('/add-update-users', [Users::class, 'addUpdateUser'])->name('add-update-users');

            //admin user
            Route::get('/admin-user', [Users::class, 'adminUser'])->name('admin-user');
            Route::get('/get-all-adminusers', [Users::class, 'getAllAdminUser'], function () {})->name("get-all-adminusers");
            Route::post('/delete-admin-users', [Users::class, 'deleteAdminUser'], function () {})->name("delete-admin-users");
            Route::post('/add-update-admin-users', [Users::class, 'addUpdateAdminUser'])->name('add-update-admin-users');
        });
        Route::get('/logout', [AdminLoginController::class, 'logout'])->name('logout');
        Route::get('/get-all-menulist', [Order::class, 'getAllMenuList'])->name('getallmenulist');

        Route::get('/new-order', [Neworder::class, 'index'])->name('neworder');
        Route::get('/get-today-order-list', [Order::class, 'getTodayOrderListData'], function () {})->name("get-today-order-list");
        Route::get('/checkmobileexist', [AdminLoginController::class, 'checkMobileExist'])->name('checkmobileexist');
        Route::post('/add-new-address', [Useraddress::class, 'addAddress'])->name('add-new-address');
        Route::post('/add-new-user', [Useraddress::class, 'addNewUser'])->name('add-new-user');
        Route::post('/add-order', [Order::class, 'addOrder'])->name('add-order');
        Route::post('/add-update-order', [Order::class, 'addUpdateOrder'])->name('add-update-order');
        //table
        Route::get('/tables', [Users::class, 'tables'])->name('tables');
        Route::get('/get-all-table', [Users::class, 'getAllTables'], function () {})->name("get-all-table");
        Route::post('/delete-admin-users', [Users::class, 'deleteTables'], function () {})->name("delete-admin-users");
        Route::post('/add-update-table', [Users::class, 'addUpdateTables'])->name('add-update-table');

        Route::get('/selecttable', [Tables::class, 'index'])->name('selecttable');
        Route::get('/tableorder', [Tables::class, 'tableOrder'])->name('tableorder');
        Route::post('/book-table', [Tables::class, 'bookTable'])->name('book-table');
        Route::post('/add-table-order', [Order::class, 'addTableOrder'])->name('add-table-order');
    });

Route::name('customer.')
    ->group(function () {
        Route::get('/', [CustomerDashboardController::class, 'index']);
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
        Route::view('/about', 'customer.about')->name('about');
        Route::view('/contact', 'customer.contact')->name('contact');
        Route::view('/service', 'customer.service')->name('service');
        Route::view('/menu', 'customer.menu')->name('menu');
        Route::view('/cart', 'customer.cart')->name('cart');
        Route::get('/get-menu', [Menu::class, 'getAllMenu'])->name('get-menu');

        //login
        Route::post('/check-mobile-exist', [Auth::class, 'checkMobileExist'])->name('check-mobile-exist');
        Route::post('/sign-in', [Auth::class, 'signIn'])->name('sign-in');
        Route::post('/verify-otp', [Auth::class, 'verifyOTP'])->name('verify-otp');
        Route::post('/update-user', [Auth::class, 'updateUser'])->name('update-user');
        Route::post('/guest-verify-otp', [Auth::class, 'verifyGuestOTP'])->name('guest-verify-otp');
        Route::post('/verify-user', [Auth::class, 'sessionExists'])->name('verify-user');
        Route::get('/is-user-loggedin', [Auth::class, 'custSessionExists'])->name('is-user-loggedin');

        
        Route::post('/get-user-address', [Auth::class, 'getUserAddress'])->name('get-user-address');
        Route::post('/add-order', [Customerorder::class, 'addOrder'])->name('add-order');
        Route::post('/add-new-address', [Customeruseraddress::class, 'addAddress'])->name('add-new-address');
        Route::get('/order', [Customerorder::class, 'index'])->name('order');
        Route::get('/logout', [Customerorder::class, 'logout'])->name('logout');

        //register
        Route::post('/register-user-mobile', [Auth::class, 'checkRegisterMobileExist'])->name('register-user-mobile');
        Route::post('/verify-register-otp', [Auth::class, 'verifyRegisterOTP'])->name('verify-register-otp');

        //subscription
        Route::get('/subscription', [CustomerSubscription::class, 'index'])->name('subscription');
        Route::post('/buy-subscription', [CustomerSubscription::class, 'buySubscription'])->name('buy-subscription');

        Route::get('/profile', [CustomerSubscription::class, 'profile'])->name('profile');
        Route::get('/check-subscription', [CustomerSubscription::class, 'checkSubscription'])->name('check-subscription');

        // More customer routes here
        // Route::middleware('auth')->group(function () {
        //     Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
        // });
    });
