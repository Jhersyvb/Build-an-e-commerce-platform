<?php

Route::resource('categories', 'Categories\CategoryController');
Route::resource('products', 'Products\ProductController');
Route::resource('addresses', 'Addresses\AddressController');
Route::resource('countries', 'Countries\CountryController');
Route::resource('orders', 'Orders\OrderController');
Route::resource('payment-methods', 'PaymentMethods\PaymentMethodController');

Route::get('addresses/{address}/shipping', 'Addresses\AddressShippingController@action');

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', 'Auth\RegisterController@action');
    Route::post('login', 'Auth\LoginController@action');
    Route::get('me', 'Auth\MeController@action');
});

Route::resource('cart', 'Cart\CartController')->parameters([
    'cart' => 'productVariation',
]);
