<?php
/**
 * Created by PhpStorm.
 * User: xingzhilong
 * Date: 2017/7/17
 * Time: 下午2:19
 */

namespace Laravel\AliPay\BuilderQuery;

class AlipayFundTransToaccountTransferContentBuilder
{

    // 商户订单号.
    private $outBizNo;

    // 支付宝登录号，支持邮箱和手机号格式。
    private $payeeType = 'ALIPAY_LOGONID';

    // 提现账号
    private $payeeAccount;

    //提现金额
    private $amount;

    //返款方姓名
    private $payerShowName;

    //收款方真是姓名
    private $payerRealName;

    //转账备注
    private $remark;


    private $bizContentarr = array();

    private $bizContent = NULL;

    public function getBizContent()
    {
        if(!empty($this->bizContentarr)){
            $this->bizContent = json_encode($this->bizContentarr,JSON_UNESCAPED_UNICODE);
        }
        return $this->bizContent;
    }

    public function getOutBizNo()
    {
        return $this->outBizNo;
    }

    public function setOutBizNo($outBizNo)
    {
        $this->outBizNo = $outBizNo;
        $this->bizContentarr['out_biz_no'] = $outBizNo;
    }

    public function getPayeeType()
    {
        return $this->payeeType;
    }

    public function setPayeeType($payeeType = '')
    {
        if($payeeType) {
            $this->payeeType = $payeeType;
        }
        $this->bizContentarr['payee_type'] = $this->payeeType;
    }

    public function getPayeeAccount()
    {
        return $this->payeeAccount;
    }

    public function setPayeeAccount($payeeAccount)
    {
        $this->payeeAccount = $payeeAccount;
        $this->bizContentarr['payee_account'] = $this->payeeAccount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        $this->bizContentarr['amount'] = $amount;
    }

    /**
     * @return mixed
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * @param mixed $remark
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;
        $this->bizContentarr['remark'] = $remark;
    }

    /**
     * @return mixed
     */
    public function getPayerShowName()
    {
        return $this->payerShowName;
    }

    /**
     * @param mixed $payerShowName
     */
    public function setPayerShowName($payerShowName)
    {
        $this->payerShowName = $payerShowName;
        $this->bizContentarr['payer_show_name'] = $payerShowName;
    }

    /**
     * @return mixed
     */
    public function getPayerRealName()
    {
        return $this->payerRealName;
    }

    /**
     * @param mixed $payerRealName
     */
    public function setPayerRealName($payerRealName)
    {
        $this->payerRealName = $payerRealName;
        $this->bizContentarr['payee_real_name'] = $payerRealName;
    }


}

?>