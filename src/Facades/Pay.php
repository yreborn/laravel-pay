<?php
namespace Yreborn\LaravelPay\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array pc($subject,$outTradeNo,$totalAmount,$returnUrl)
 * @method static array wap($subject,$outTradeNo,$totalAmount,$returnUrl)
 * @method static array refund($outTradeNo,$totalAmount)
 * @method static array query($outTradeNo)
 *
 * @method static array nativePay($out_trade_no,$description,$amount)
 * @method static array outTradeNoQuery($out_trade_no)
 * @method static array transactionsQuery($id)
 * @method static array close($out_trade_no)
 * @method static array refunds($out_trade_no,$transaction_id,$amount)
 * Class Pay
 * @package Yreborn\LaravelPay\Facades
 */
class Pay extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'pay';
    }

}