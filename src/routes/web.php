<?php

Route::group(['middleware' => ['web']], function () {
    
    /* REDSYS */
    Route::get('redsys/payment/response/successful',        '\Syscover\Market\Controllers\RedsysController@successful')->name('pulsar.market.redsys_payment_successful');
    Route::get('redsys/payment/response/failure',           '\Syscover\Market\Controllers\RedsysController@error')->name('pulsar.market.redsys_payment_error');

    /* PAYPAL */
    Route::get('paypal/payment/response/successful',        '\Syscover\Market\Controllers\PayPalController@successful')->name('pulsar.market.paypal_payment_successful');
    Route::get('paypal/payment/response/failure/{id}',      '\Syscover\Market\Controllers\PayPalController@error')->name('pulsar.market.paypal_payment_error');

    /* STRIPE */
    // Route::POST('stripe/payment',                           '\Syscover\Market\Controllers\StripeController@managePayment')->name('pulsar.market.manage_stripe_payment');
    // Route::get('stripe/payment/response/successful',        '\Syscover\Market\Controllers\StripeController@successful')->name('pulsar.market.stripe_payment_successful');

});