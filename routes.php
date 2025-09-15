<?php

use Mkinternet\Tpayshopaholic\Classes\PaymentGateway;

Route::get(PaymentGateway::SUCCESS_URL.'/{slug}', function ($sOrderKey) {
    $obPaymentGateway = new PaymentGateway();
    return $obPaymentGateway->processSuccessURL($sOrderKey);
});

Route::get(PaymentGateway::FAIL_URL.'/{slug}', function ($sOrderKey) {
    $obPaymentGateway = new PaymentGateway();
    return $obPaymentGateway->processFailURL($sOrderKey);
});


Route::get(PaymentGateway::FORM_URL.'/{slug}', function ($trid) {

    $obPaymentGateway = new PaymentGateway();
    return $obPaymentGateway->processFormRequest($trid);
});

Route::get(PaymentGateway::NOTIFICATION_URL, function () {

    $obPaymentGateway = new PaymentGateway();
    return $obPaymentGateway->processAnswerRequest();
});