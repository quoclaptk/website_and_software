<?php

namespace Modules\OnlinePayment;

class NganLuong
{
    public function __construct()
    {
        include 'nganluong/config.php';
        include 'nganluong/lib/nganluong.class.php';
    }

    public function orderProcessUrl($return_url, $cancel_url, $transaction_info, $order_code, $price, $currency, $quantity, $tax, $discount, $fee_cal, $fee_shipping, $order_description, $buyer_info, $affiliate_code)
    {
        $nl= new \NL_Checkout();
        $nl->nganluong_url = NGANLUONG_URL;
        $nl->merchant_site_code = MERCHANT_ID;
        $nl->secure_pass = MERCHANT_PASS;

        $url= $nl->buildCheckoutUrlExpand($return_url, RECEIVER, $transaction_info, $order_code, $price, $currency, $quantity, $tax, $discount, $fee_cal, $fee_shipping, $order_description, $buyer_info, $affiliate_code);
        //$url= $nl->buildCheckoutUrl($return_url, $receiver, $transaction_info, $order_code, $price);
        
        //echo $url; die;
        if ($order_code != "") {
            $url .='&cancel_url='. $cancel_url;
            //$url .='&option_payment=bank_online';
            
            return $url;
        }
    }
    
    public function verifyPayment($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text, $secure_code)
    {
        $nl= new \NL_Checkout();
        $nl->merchant_site_code = MERCHANT_ID;
        $nl->secure_pass = MERCHANT_PASS;

        $checkpay= $nl->verifyPaymentUrl($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text, $secure_code);
        
        if ($checkpay) {
            return true;
        } else {
            return false;
        }
    }
}
