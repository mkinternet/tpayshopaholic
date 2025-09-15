<?php namespace Mkinternet\Tpayshopaholic\Classes;

use Lovata\Toolbox\Classes\Event\AbstractBackendFieldHandler;

use Lovata\OrdersShopaholic\Models\PaymentMethod;
use Lovata\OrdersShopaholic\Controllers\PaymentMethods;

use Mkinternet\Tpayshopaholic\Classes\PaymentGateway;


class ExtendPaymentMethodFields extends AbstractBackendFieldHandler
{
    /**
     * Extend backend fields
     * @param \Backend\Widgets\Form $obWidget
     */
    protected function extendFields($obWidget)
    {


        if ($obWidget->model->gateway_id != PaymentGateway::CODE) {
            return;
        }


        $obWidget->addTabFields([
            'gateway_property.section' => [
                'label'       => 'Ustawienia tpay',
                'tab'         => 'lovata.ordersshopaholic::lang.tab.gateway',
                'type'        => 'section',

            ],            

            'gateway_property[userid]' => [
                'label'       => 'ID sprzedawcy',
                'tab'         => 'lovata.ordersshopaholic::lang.tab.gateway',
                'required'    => 'true',
                'span'        => 'auto'
            ],

            'gateway_property[userpassword]' => [
                'label'       => 'Hasło sprzedawcy',
                'tab'         => 'lovata.ordersshopaholic::lang.tab.gateway',
                'required'    => 'true',
                'span'        => 'auto'
            ],

            'gateway_property[apikey]' => [
                'label'       => 'Klucz API',
                'tab'         => 'lovata.ordersshopaholic::lang.tab.gateway',
                'required'    => 'true',
                'span'        => 'auto'
            ],

            'gateway_property[apisecret]' => [
                'label'       => 'Hasło API',
                'tab'         => 'lovata.ordersshopaholic::lang.tab.gateway',
                'required'    => 'true',
                'span'        => 'auto'
            ],
            'gateway_property[resultemail]' => [
                'label'       => 'Email do powiadomień',
                'tab'         => 'lovata.ordersshopaholic::lang.tab.gateway',
                'required'    => 'true',
                'span'        => 'auto'
            ],
        ]);
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass() : string
    {
        return PaymentMethod::class;
    }
    /**
     * Get controller class name
     * @return string
     */
    protected function getControllerClass() : string
    {
        return PaymentMethods::class;
    }
}