<?php
/**
 * Created by PhpStorm.
 * User: xingzhilong
 * Date: 2017/7/7
 * Time: 上午11:18
 */
namespace Laravel\AliPay;

use Laravel\AliPay\BuilderQuery\AlipayFundTransToaccountTransferContentBuilder;
use Laravel\AliPay\BuilderQuery\AlipayTradePagePayContentBuilder;
use Laravel\AliPay\BuilderQuery\AlipayTradeRefundContentBuilder;

class AliPay
{
    private $config;
    private $orderData;//下单数据实例
    private $orderRefundData;//退款数据实例
    public $orderWithdrawalData; //提现数据实例

    //支付宝网关地址
    public $gateway_url;

    //支付宝公钥
    public $alipay_public_key;

    //商户私钥
    public $private_key;

    //应用id
    public $appid;

    //编码格式
    public $charset = "UTF-8";

    public $token = NULL;

    //返回数据格式
    public $format = "json";

    //签名方式
    public $signtype = "RSA2";

    //订单号
    public $out_trade_no;
    //合作伙伴id（支付宝收款方唯一id）
    public $seller_id ;


    public function bootstrapAliPay(array $config = [])
    {
        $this->config = array_merge(config('pkxing-alipay'), $config);
        $this->appid = $this->config['app_id'];
        $this->alipay_public_key = $this->config['alipay_public_key'];
        $this->private_key = file_get_contents($this->config['merchant_private_key']);
        $this->gateway_url = $this->config['gatewayUrl'];
        $this->charset = $this->config['charset'];
        $this->signtype = $this->config['sign_type'];
        $this->seller_id = $this->config['seller_id'];
        if(empty($this->appid)||trim($this->appid)==""){
            throw new \Exception("appid should not be NULL!");
        }
        if(empty($this->private_key)||trim($this->private_key)==""){
            throw new \Exception("private_key should not be NULL!");
        }
        if(empty($this->alipay_public_key)||trim($this->alipay_public_key)==""){
            throw new \Exception("alipay_public_key should not be NULL!");
        }
        if(empty($this->charset)||trim($this->charset)==""){
            throw new \Exception("charset should not be NULL!");
        }
        if(empty($this->gateway_url)||trim($this->gateway_url)==""){
            throw new \Exception("gateway_url should not be NULL!");
        }
        $this->orderData = new AlipayTradePagePayContentBuilder();
        $this->orderRefundData = new AlipayTradeRefundContentBuilder();
        $this->orderWithdrawalData = new AlipayFundTransToaccountTransferContentBuilder();
        return $this;
    }

    /**
     * 统一下单支付下单支付业务参数
     * @param array $tradeArray
     * @return $this
     */
    public function buildOrder(array $tradeArray = [])
    {
       $this->out_trade_no = $this->generateOutTradeNo(32);
        $alipayArray = [
            'out_trade_no' =>  $this->out_trade_no,
        ];
        $alipayArray = array_merge($alipayArray, $tradeArray);
        if (count($alipayArray)) {
            foreach ($alipayArray as $key => $item) {
                $keyEnd = str_replace('_', ' ', $key);
                $keyEnd = ucwords($keyEnd);
                $keyEnd = 'set' . str_replace(' ', '', $keyEnd);
                $this->orderData->$keyEnd($item);
            }
        }
        return $this;
    }

    /**
     * 统一退款业务参数
     * @param array $tradeArray
     * @return $this
     */
    public function builderRefundOrder(array $tradeArray = [])
    {
        if (count($tradeArray)) {
            foreach ($tradeArray as $key => $item) {
                $keyEnd = str_replace('_', ' ', $key);
                $keyEnd = ucwords($keyEnd);
                $keyEnd = 'set' . str_replace(' ', '', $keyEnd);
//                \Log::info($keyEnd);
                $this->orderRefundData->$keyEnd($item);
            }
        }
        return $this;
    }

    public function buildWithdrawalOrder(array $withdrawalArray = [])
    {
        $withdrawalArray['out_biz_no'] = $this->generateOutTradeNo(32);
        $withdrawalArray['payee_type'] = '';
        if (count($withdrawalArray)) {
            foreach ($withdrawalArray as $key => $item) {
                $keyEnd = str_replace('_', ' ', $key);
                $keyEnd = ucwords($keyEnd);
                $keyEnd = 'set' . str_replace(' ', '', $keyEnd);
                $this->orderWithdrawalData->$keyEnd($item);
            }
        }
        return $this;
    }

    /**
     * 调用支付接口
     * @param $return_url
     * @param $notify_url
     * @return array
     */
    public function pagePay($return_url, $notify_url)
    {
        $request = new \AlipayTradePagePayRequest();
        $request->setReturnUrl($return_url);
        $request->setNotifyUrl($notify_url);
        $request->setBizContent($this->orderData->getBizContent());

        //调用支付api
        $response = $this->aopclientRequestExecute($request, true);
        return $response;

    }


    /**
     * alipay.trade.refund (统一收单交易退款接口)
     * @return array
     */
    public function refund(){
        $biz_content=$this->orderRefundData->getBizContent();
        //打印业务参数
        $request = new \AlipayTradeRefundRequest();
        $request->setBizContent ($biz_content);

        $response = $this->aopclientRequestExecute ($request, false);
        $response = $response->alipay_trade_refund_response;
        return $response;
    }

    /**
     * alipay.fund.trans.toaccount.transfer(单笔转账到支付宝账户接口)
     * @return array
     */
    public function transfer()
    {
        $biz_content = $this->orderWithdrawalData->getBizContent();
        $request = new \AlipayFundTransToaccountTransferRequest();
        $request->setBizContent($biz_content);

        $response = $this->aopclientRequestExecute($request, false);
        return $response;
    }

    /**
     * sdkClient
     * @param \AlipayTradePayRequest $request 接口请求参数对象。
     * @param bool $isPage  是否是页面接口，电脑网站支付是页面表单接口。
     * @return array $response 支付宝返回的信息
     */
    function aopclientRequestExecute($request, bool $isPage=false)
    {
        $aop = new \AopClient();
        $aop->gatewayUrl = $this->gateway_url;
        $aop->appId = $this->appid;
        $aop->rsaPrivateKey =  $this->private_key;
        $aop->alipayrsaPublicKey = $this->alipay_public_key;
        $aop->apiVersion ="1.0";
        $aop->postCharset = $this->charset;
        $aop->format= $this->format;
        $aop->signType=$this->signtype;

        // 开启页面信息输出
        $aop->debugInfo=true;
        if($isPage)
        {
            $result = $aop->pageExecute($request,"post");
            echo $result;
        }
        else
        {
            $result = $aop->Execute($request);
        }

        return $result;
    }

    /**
     * 随机生成指定长度字符串
     * @param int $length
     * @return string
     */
    public function generateOutTradeNo(int $length)
    {
        $range = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));

        $orderNum = '';
        while ($length) {
            $orderNum .= $range[mt_rand(0, 61)];
            $length--;
        }
        return $orderNum;
    }

}