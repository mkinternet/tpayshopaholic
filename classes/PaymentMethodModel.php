<?php namespace Mkinternet\Tpayshopaholic\Classes;

use Lovata\OrdersShopaholic\Models\PaymentMethod;
use Mkinternet\Tpayshopaholic\Classes\PaymentGateway;

class PaymentMethodModel
{
    /**
     * Add event listeners
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        PaymentMethod::extend(function ($obElement) {

            $obElement->addGatewayClass(PaymentGateway::CODE, PaymentGateway::class);
           
            $obElement->bindEvent('model.beforeValidate', function () use ($obElement) {
                $this->addValidationRules($obElement);
            });
        });


        $obEvent->listen(PaymentMethod::EVENT_GET_GATEWAY_LIST, function () {
            $arPaymentMethodList = [
                PaymentGateway::CODE => 'Tpay',
            ];
            return $arPaymentMethodList;
        });
    }
    
    /**
     * Add custom validartion rules and validation messages
     * @param PaymentMethod $obElement
     */
    protected function addValidationRules($obElement)
    {
        if ($obElement->gateway_id != PaymentGateway::CODE || $obElement->getOriginal('gateway_id') != PaymentGateway::CODE) {
            return;
        }

        $arRules = [
            'gateway_property.userid'    => 'required',
            'gateway_property.userpassword'    => 'required',
            'gateway_property.apikey'    => 'required',
            'gateway_property.apisecret'    => 'required',
            'gateway_property.resultemail'    => 'required',
        ];
        $obElement->rules = array_merge($obElement->rules, $arRules);

        $arAttributeNames = [
            'gateway_property.userid'     => 'ID użytkownika jest wymagane',
            'gateway_property.userpassword'     => 'Hasło użytkownika jest wymagane',
            'gateway_property.apikey'     => 'Klucz api jest wymagany',
            'gateway_property.apisecret'     => 'Hasło api tpay jest wymagane',
            'gateway_property.resultemail'     => 'Hasło api tpay jest wymagane',
        ];
        $obElement->attributeNames = array_merge($obElement->attributeNames, $arAttributeNames);
    }
}