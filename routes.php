<?php

use Mkinternet\Tpayshopaholic\Classes\PaymentGateway;

Route::get(PaymentGateway::SUCCESS_URL.'/{slug}', function ($trid) {
    $paymentgateway = new PaymentGateway();
    return $paymentgateway->processSuccessURL($trid);
});

Route::get(PaymentGateway::FAIL_URL.'/{slug}', function ($trid) {
    $paymentgateway = new PaymentGateway();
    return $paymentgateway->processFailURL($trid);
});


Route::get(PaymentGateway::FORM_URL.'/{slug}', function ($trid) {

    $paymentgateway = new PaymentGateway();
    return $paymentgateway->processFormRequest($trid);
});

Route::post(PaymentGateway::NOTIFICATION_URL, function () {
    $paymentgateway = new PaymentGateway();
    return $paymentgateway->processAnswerRequest();
});

Route::get('testalert', function () {
     return \Redirect::to(\Cms\Classes\Page::url('order-complete-page', ['slug' => '123']));
});


