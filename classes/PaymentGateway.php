<?php

namespace Mkinternet\Tpayshopaholic\Classes;

use Response;

use Mkinternet\Tpayshopaholic\Classes\Tpaypaymentapi;
use Mkinternet\Tpayshopaholic\Classes\Tpaypaymentstatus;

use Lovata\OrdersShopaholic\Classes\Helper\AbstractPaymentGateway;

class PaymentGateway extends AbstractPaymentGateway
{
    const CODE = 'tpay';

    const NOTIFICATION_URL = '/mkinternet/tpayshopaholic/notification';
    const FORM_URL = '/mkinternet/tpayshopaholic/form';
    const SUCCESS_URL = '/mkinternet/tpayshopaholic/success';
    const FAIL_URL = '/mkinternet/tpayshopaholic/fail';

    const EVENT_SUCCESS_URL = 'shopaholic.payment.tpay.success.redirect_url';
    const EVENT_FAIL_URL = 'shopaholic.payment.tpay.fail.redirect_url';

    /** @var array - response from payment gateway */
    protected $arResponse = [];
    protected $arRequestData = [];
    protected $arAuthData = [];
    protected $sRedirectURL = '';
    protected $sMessage = '';

    protected $obResponse;

    /**
     * Get response array
     * @return array
     */
    public function getResponse(): array
    {
        return (array) $this->arResponse;
    }

    /**
     * Get redirect URL
     * @return string
     */
    public function getRedirectURL(): string
    {
        return $this->sRedirectURL;
    }

    /**
     * Get error message from payment gateway
     * @return string
     */
    public function getMessage(): string
    {
        return $this->sMessage;
    }

    public function processFormRequest($trid)
    {


        $order = \Lovata\OrdersShopaholic\Models\Order::getBySecretKey($trid)->first();

        if (empty($order)) {
            return 'Nieprawidłowy klucz';
        }

        $html = '<h1>Za chwilę zostaniesz przekierowany na stronę płatności</h1>' . $order->payment_data;

        return $html;
    }


    public function processAnswerRequest()
    {
        $log = '';

        $post = post();

        preg_match("/#([0-9-]+)/i", $post['tr_desc'], $order_number);

        if (!empty($order_number)) {


            $order = \Lovata\OrdersShopaholic\Models\Order::where('order_number', $order_number[1])->first();
            $this->initOrderObject($order->id);


            $notification = (new Tpaypaymentstatus(
                $this->getGatewayProperty('userid'),
                $this->getGatewayProperty('userpassword')
            ))->getTpayNotification();
        } else {
            return false;
        }


        if (!empty($notification)) {


            if (!empty($order)) {

                $order->transaction_id = $notification['tr_id'];
                $order->save();

                if ($notification['tr_amount'] == $notification['tr_paid']) {


                    if ($notification['tr_status'] == 'TRUE') {

                        $this->setSuccessStatus();

                        $log .= "Transaction success\n";
                    }

                    if ($notification['tr_status'] == 'CHARGEBACK') {

                        $this->setCancelStatus();

                        $log .= "Transaction  chargeback\n";
                    }
                } else {
                    $log .= "tr_amound != tr_paid\n";
                }

                $this->obOrder->payment_response = $notification;
                $this->obOrder->payment_data = '';
                $this->obOrder->save();
            } else {
                $log .= "Transaction " . $notification['tr_id'] . " not found\n";
            }
        }



        $log .= print_r($notification, true);

        \Log::info($log);
    }

    /**
     * @param $sOrderKey
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processSuccessURL($sOrderKey)
    {
        return \Redirect::to(\Cms\Classes\Page::url('order-complete-page'));
    }


    /**
     * @param $sOrderKey
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processFailURL($sOrderKey)
    {
        return \Redirect::to(\Cms\Classes\Page::url('order-fail-page'));
    }

    /**
     * Prepare data for request in payment gateway
     */
    protected function preparePurchaseData() {}

    /**
     * Validate request data
     * @return bool
     */
    protected function validatePurchaseData()
    {
        return true;
    }

    /**
     * Send request to payment gateway
     */
    protected function sendPurchaseData()
    {
        $this->preparePurchaseData();


        $paymentform = new Tpaypaymentapi(
            $this->getGatewayProperty('userid'),
            $this->getGatewayProperty('userpassword'),
            $this->getGatewayProperty('apikey'),
            $this->getGatewayProperty('apisecret'),
            $this->getGatewayProperty('resultemail')
        );

        $paymentform->config = [
            'amount' => $this->obOrder->total_price_value,
            'description' => '#' . $this->obOrder->order_number . ' ',
            'crc' => '100020003000',
            'return_url' => url('/') . self::SUCCESS_URL . '/' . $this->obOrder->secret_key,
            'result_url' => url('/') . self::NOTIFICATION_URL,
            'result_email' => $this->getGatewayProperty('resultemail'),
            'email' => $this->getOrderProperty('email'),
            'name' => $this->getOrderProperty('name') . ' ' .  $this->getOrderProperty('last_name'),
            'group' => 150,
            'accept_tos' => 1,

        ];

        //\Log::info(print_r($paymentform->config, true));
        $this->obResponse['htmlform'] = $paymentform->getTransactionFormApi();;
    }

    /**
     * Process response from payment gateway
     */
    protected function processPurchaseResponse()
    {

        if (empty($this->obResponse)) {
            return;
        }

        $this->sRedirectURL = url('/') . self::FORM_URL . '/' . $this->obOrder->secret_key;
        $this->bIsRedirect = true;


        $this->obOrder->payment_data = $this->obResponse['htmlform'];
        $this->obOrder->save();
    }

    /**
     * @return \Illuminate\Http\Response
     */
    protected function returnAccessDeniedResponse()
    {
        return Response::make('Access denied', 200);
    }
}
