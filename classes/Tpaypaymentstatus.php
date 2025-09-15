<?php namespace SSI\Events\Classes;

/*
 * Created by tpay.com
 */

use tpayLibs\src\_class_tpay\Notifications\BasicNotificationHandler;


class Tpaypaymentstatus extends BasicNotificationHandler{


    public function __construct($userid, $userpassword)
    {
		
		
		

        $this->merchantId = intVal($userid);
		$this->merchantSecret = $userpassword;


		
        parent::__construct();
    }

    public function getTpayNotification()
    {
        return $this->checkPayment();
    }
	
	
}

