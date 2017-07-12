<?php
/**
 * Created by PhpStorm.
 * User: xingzhilong
 * Date: 2017/7/1
 * Time: 上午8:28
 */

namespace Laravel\WeChatPay;


use Illuminate\Support\Facades\Facade;

class AliPayFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'alipay';
    }
}