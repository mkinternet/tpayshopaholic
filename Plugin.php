<?php namespace Mkinternet\Tpayshopaholic;


use Event;
use Cms\Classes\Page;
use System\Classes\PluginBase;


use Mkinternet\Tpayshopaholic\Classes\ExtendPaymentMethodFields;
use Mkinternet\Tpayshopaholic\Classes\PaymentMethodModel;
use Mkinternet\Tpayshopaholic\Classes\PaymentGateway;

/**
 * Tpayshopaholic Plugin Information File
 */
class Plugin extends PluginBase
{
    public $require = [
        'Lovata.Toolbox',
        'Lovata.Shopaholic',
        'Lovata.OrdersShopaholic',
    ];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Tpayshopaholic',
            'description' => 'No description provided yet...',
            'author'      => 'Mkinternet',
            'icon'        => 'icon-leaf'
        ];
    }


    /**
     * Boot method, called right before the request route.
     *
     * @return void
     */
    public function boot()
    {
        Event::subscribe(ExtendPaymentMethodFields::class);
        Event::subscribe(PaymentMethodModel::class);

        Event::listen(PaymentGateway::EVENT_SUCCESS_URL, function ($obOrder) {
            return Page::url('order-complete-page', ['slug' => $obOrder->secret_key]);
        });

        Event::listen(PaymentGateway::EVENT_FAIL_URL, function ($obOrder) {
            return Page::url('order-failed-page', ['slug' => $obOrder->secret_key]);
        });

    }


}
