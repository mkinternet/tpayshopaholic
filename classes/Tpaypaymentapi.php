<?php namespace Mkinternet\Tpayshopaholic\Classes;

/*
 * Created by tpay.com
 */

use Redirect;


use tpayLibs\src\_class_tpay\Utilities\TException;
use tpayLibs\src\_class_tpay\TransactionApi;


class Tpaypaymentapi extends TransactionApi{


	public $config;

    public function __construct($userid, $userpassword, $apikey, $apisecret, $resultemail)
    {

//dd($apisecret);

        $this->merchantId = intVal($userid);
		$this->merchantSecret = $userpassword;

		$this->trApiKey = $apikey;
        $this->trApiPass = $apisecret;		
		
        $this->config = [
            'amount' => 999,
            'description' => 'Transaction description',
            'crc' => '100020003000',
            'return_url' => url('/'),
            'result_url' => url('/').'/mkinternet/tpayshopaholic/notification?transaction_confirmation',
            'result_email' => $resultemail,
            'email' => '',
            'name' => '',
            'group' => isset($_POST['group']) ? (int)$_POST['group'] : 150,
            'accept_tos' => 1,
        ];	

		
        parent::__construct();
    }


	public function getTransaction()
    {
        /**
         * Get info about transaction
         */

        $transactionId = $this->trId;

        try {
            $transaction = $this->setTransactionID($transactionId)->get();
            print_r($transaction);
        } catch (TException $e) {
            var_dump($e);
        }

    }

    public function getDataForTpay()
    {

        try {
            $res = $this->create($this->config);
		
			
			return $res;

        } catch (TException $e) {
            var_dump($e);
        }


        /*
         * This method return HTML form
         */

    }


    public function getTransactionFormApi()
    {

        // This method return HTML form
        return $this->getTransactionForm($this->config, true);
    }

}

//(new TpayBasicExample())->getDataForTpay();
