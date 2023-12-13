<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CardsController;
use App\Http\Controllers\Api\OrdersController;
use App\Http\Controllers\Api\FavouriteController;
use App\Http\Controllers\Api\users\AuthController;
use App\Http\Controllers\Api\users\UserController;
use App\Http\Controllers\Api\DepartmentsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| 
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

 Route::middleware('auth:api')->get('/user', function (Request $request) {
    
    return $request->user();
});


// ! all routes / api here must be authentcated or not
Route::group(['middleware' => ['api','checkvalidation','changelanguage']], function () {
   
    //// ? return all orders ////
    Route::POST('get-main-orders', [OrdersController::class, 'index'])->middleware('auth.guard:api')->name('index.orders');
    //// ? return one orders by id ////
    Route::POST('get-one-orders-byid/{id}', [OrdersController::class, 'getorders'])->middleware('auth.guard:api')->name('get.one.orders');
    // todo group user to login & logout & register //

    Route::group(['prefix' =>'users','namespace' => 'users'], function () {
    Route::POST('login', [AuthController::class, 'login'])->name('login');
    Route::POST('register', [AuthController::class, 'register'])->name('regist');
    Route::POST('update-customer/{id}', [AuthController::class, 'update'])->middleware('auth.guard:api');
    /*
     todo Invalidate Tocken Security Site
     todo  Brocken Access Controller Users enumeration
     */
    Route::POST('logout',[AuthController::class, 'logout'])->middleware('auth.guard:api')->name('logout');
    //// ? return profile information ////
    Route::POST('profile',[AuthController::class, 'profile'])->middleware('auth.guard:api')->name('profile');

    });

    //! return image post or user or machine // 
    Route::group(['prefix' =>'images','namespace' => 'users'], function () {

        Route::get('imageusers/{service}',[AuthController::class, 'imagesuser']);
        Route::get('imageord/{service}',[AuthController::class, 'imagesord']);
        Route::get('imagedep/{service}',[AuthController::class, 'imagesdep']);
    
    });

    // ! orders //
    // todo Restore_view Orders //
    Route::POST('restore_view_ord',[OrdersController::class,'restore_view'])->middleware('auth.guard:api');
    // todo Restore Orders //
    Route::POST('restore-orders/{id}',[OrdersController::class,'restore'])->middleware('auth.guard:api');
    //// ? autocompleteSearch Orders //// 
    Route::POST('autocompleteSearch-ord', [OrdersController::class, 'autocompleteSearch'])->name('autocompleteSearch')->middleware('auth.guard:api');
    // ! users //
    ////? Delete Users ////
    Route::POST('delete-users/{id}', [UserController::class, 'destroy'])->middleware('auth.guard:api');
    ////? settings Users ////
    Route::POST('setting', [UserController::class, 'setting'])->middleware('auth.guard:api');
    ////? autocompleteSearch Users ////
    Route::POST('autocompleteSearch-users', [UserController::class, 'autocompleteSearch'])->middleware('auth.guard:api');
    ////? update Orders  ////
    Route::POST('update-order/{id}', [OrdersController::class, 'update'])->middleware('auth.guard:api');  
});



// ! api here must be authentcated & Role = Admin //
Route::group(['middleware' => ['api','checkvalidation','changelanguage','auth.guard:api','check.admin-role']], function () {
    //// ? controller with rescource ////
    Route::resource('departments', DepartmentsController::class);
    //// ? OrdersControler ////
    Route::POST('offers', [OrdersController::class, 'offers'])->name('offers.categories');
    Route::POST('orders/{id}',[OrdersController::class,'destroy'])->name('destroy.orders');
    //// ? Restore View Departments ////
    Route::POST('restore_view_dep',[DepartmentsController::class,'restore_view']);
    //// ? Restore Departments //// 
    Route::POST('departments_restore/{id}', [DepartmentsController::class, 'restore'])->name('departments.restore');
    //// ? autocompleteSearch Departments //// 
    Route::POST('autocompleteSearch-dep', [DepartmentsController::class, 'autocompleteSearch'])->name('autocompleteSearch');
    //// ? search_departments  //// 
    Route::POST('search_departments', [DepartmentsController::class, 'search_departments'])->name('search_departments'); 
    ////? Create A new Admin ////
    Route::POST('create-admins', [UserController::class, 'store']);
    ////? Show all Users ////
    Route::POST('show-customer', [UserController::class, 'index']);
    ////? Show All Admins ////
    Route::POST('show-admins', [UserController::class, 'admin']);
    ////? Show one user ////
    Route::POST('show-user/{id}', [UserController::class, 'show']);
    ////? update Users  ////
    Route::POST('update-user/{id}', [UserController::class, 'update']);   
    
});

// ! all images //
Route::group(['middleware' => ['api','checkvalidation','auth.guard:api','check.customer-role']], function () {

Route::group(['prefix' =>'images','namespace' => 'users'], function () {
    
    // todo return image post | users | machine //
    Route::POST('/srnz/profilephoto',[AuthController::class, 'imagesuser']);
    Route::POST('/srnz/departments',[AuthController::class, 'depimage']);
    Route::POST('/srnz/orders',[AuthController::class, 'ordimage']);
        
//! ////////////////////////////////////////////////////////////////////////////////////////

    // todo return image post | users | machine //
    Route::get('/srnzimageusers/{service}',[AuthController::class, 'imagesuser']);
    Route::get('/srnzimagedep/{service}',[AuthController::class, 'imagesdep']);
    Route::get('/srnzimageord/{service}',[AuthController::class, 'imagesord']);

    });
});

// ! api here must be authentcated & Role = Customer //
Route::group(['middleware' => ['api','checkvalidation','changelanguage','auth.guard:api','check.customer-role']], function () {
    //// ? controller with rescource ////
    Route::resource('favourite',FavouriteController::class);
    Route::resource('cards',CardsController::class);
    //// ? Delete All Orders in my cards ////
    Route::POST('destroy-all-mycards',[CardsController::class,'deleteall']);
    //// ? Delete All  My Favourites ////
    Route::POST('destroy-all-myfavourite',[FavouriteController::class,'deleteall']);
    //// ? OrdersController ////
    Route::POST('destroy-myorders/{id}',[OrdersController::class,'destroymyorders']);
    Route::POST('Add-myorders',[OrdersController::class,'store']);

});


             


















     /* // controller with rescource
    Route::resource('/departments', DepartmentsController::class);
    Route::resource('/orders', OrdersController::class);
    Route::resource('/users', UsersController::class);
    //users-Admin
    Route::get('/users-admin', [UsersController::class, 'admin'])->name('users.admin');
    //restore-departments
    Route::get('/departments-restore', [DepartmentsController::class, 'restore_index'])->name('departments.restore.index');
    Route::get('/departments/restore/do', [DepartmentsController::class, 'restore'])->name('departments.restore');
    //restore-orders
    Route::get('/orders-restore', [OrdersController::class, 'restore_index'])->name('orders.restore.index');
    Route::get('/orders/restore/do', [OrdersController::class, 'restore'])->name('orders.restore');
    //autocompleteSearch-departments
    Route::get('/autocomplete-search-departments', [DepartmentsController::class, 'autocompleteSearch']);
    //search-departments
    Route::POST('/search-departments', [DepartmentsController::class, 'search_departments'])->name('departments.search');
    //autocompleteSearch-users
    Route::get('/autocomplete-search-users', [UsersController::class, 'autocompleteSearch']);
    //search-users
    Route::POST('/search-users', [UsersController::class, 'search_users'])->name('users.search');*/