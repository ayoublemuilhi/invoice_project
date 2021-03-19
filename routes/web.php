<?php

use Illuminate\Support\Facades\Route;

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
############################ login page
Route::get('/', function () {
    return view('auth.login');
});

Auth::routes(['register' => false]);
########################## end login

Route::group(['middleware' => 'auth'],function (){
    Route::get('/home', 'HomeController@index')->name('home');

######################### Start Invoices
    Route::resource('invoices','InvoiceController');

    Route::post('Status_Update/{invoice}','InvoiceController@Status_Update')->name('Status_Update');
    Route::get('invoices_paid/','InvoiceController@Invoice_paid')->name('Invoice_paid');
    Route::get('invoices_unpaid/','InvoiceController@Invoice_unpaid')->name('Invoice_unpaid');
    Route::get('invoices_partial/','InvoiceController@Invoice_partial')->name('Invoice_partial');
    Route::get('Print_invoice/{invoice}','InvoiceController@Print_invoice');

    Route::get('invoice/export/', 'InvoiceController@export')->name('export');

######################### End Invoices

######################### Start Sections
    Route::resource('sections','SectionController');
######################### End Sections

######################### Start Products
    Route::resource('products','ProductController');

######################### End Products
    Route::get('/sections/{section}','InvoiceController@getProductsSection');//ajax

######################### Start InvoiceDetails
    Route::resource('invoiceDetail','InvoiceDetailController');
    Route::get('View_file/{file_name}', 'InvoiceDetailController@open_file');
    Route::get('download/{file_name}', 'InvoiceDetailController@get_file');

######################### End InvoiceDetails

######################### Start InvoiceAttachement
    Route::resource('InvoiceAttachement','InvoiceAttachementController');

######################### End InvoiceAttachement

######################### Start Archive
    Route::resource('archives','ArchiveController');

######################### End Archive

################################### role and user


        Route::resource('users','UserController');

    Route::resource('roles','RoleController');
});



