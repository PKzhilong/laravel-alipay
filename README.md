# laravel基于支付宝官方sdk整合到laravel的支付插件

## Installation
执行如下命令
```php
    composer require pkzhilong/laravel-alipay
```
## 注册服务提供者
```php
      \Laravel\AliPay\AliPayServeProvider::class,
```
## 使用
```php
1. 设置对应参数
     $return_url =  isset($this->ownType) ? 'team_alipay_success_redirect' : 'alipay_success_redirect'; //回调地址
     $notify_url =  isset($this->ownType) ? 'team_alipay_notify' : 'alipay_notify'; //异步通知地址
     $this->aliPay->bootstrapAliPay()->buildOrder([
         'total_amount' => $taskStage->cost, //交易额
         'subject' => '大宅世家资金托管', //交易描述
         'body'      => '大宅世家资金托管-1', //交易标题 
     ])->pagePay(
         route($return_url, ['taskStage_id' => $taskStage->id, 'out_trade_no' => $this->aliPay->out_trade_no]),
         route($notify_url, ['taskStage_id' => $taskStage->id, 'out_trade_no' => $this->aliPay->out_trade_no])
    );
  
```
>注意： 以上buildOrder中的参数值必须填写，



