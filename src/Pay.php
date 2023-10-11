<?php

namespace Yreborn\LaravelPay;

use Alipay\EasySDK\Kernel\Factory;
use Illuminate\Config\Repository;
use Alipay\EasySDK\Kernel\Config;
use WeChatPay\Builder;
use WeChatPay\Crypto\Rsa;
use WeChatPay\Util\PemUtil;


class Pay
{
    protected $config;
    protected $instance;

    /**
     * Upload constructor.
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config->get('pay');
    }

    public function pc($subject,$outTradeNo,$totalAmount,$returnUrl)
    {
        try {
            Factory::setOptions($this->getOptions());
            $result = Factory::payment()->Page()->pay($subject,$outTradeNo, $totalAmount, $returnUrl);
            return $result->body;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }


    public function wap($subject,$outTradeNo,$totalAmount,$returnUrl)
    {
        try {
            Factory::setOptions($this->getOptions());
            $result = Factory::payment()->Wap()->pay($subject,$outTradeNo, $totalAmount, $returnUrl,$returnUrl);
            return $result->body;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function refund($outTradeNo,$totalAmount)
    {
        try {
            Factory::setOptions($this->getOptions());
            $result = Factory::payment()->Common()->refund($outTradeNo, $totalAmount);
            if($result->msg != 'Success'){
                return '退款失败，'.$result->httpBody;
            }
            return $result;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function query($outTradeNo)
    {
        try {
            Factory::setOptions($this->getOptions());
            $result = Factory::payment()->Common()->query($outTradeNo);
            return $result->httpBody;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getOptions()
    {
        $options = new Config();
        $options->protocol = 'https';
        $options->gatewayHost = 'openapi.alipay.com';
        $options->signType = 'RSA2';
        $options->appId = $this->config['appId'];
        $options->merchantPrivateKey = $this->config['merchantPrivateKey'];
        $options->alipayPublicKey = $this->config['alipayPublicKey'];
        $options->notifyUrl = $this->config['notifyUrl'];
        return $options;
    }


    /**
     * wx nativePay pay
     * @param $out_trade_no
     * @param $description
     * @param $amount
     * @return mixed
     * @throws \Exception
     */
    public function nativePay($out_trade_no,$description,$amount)
    {
        try {
            $resp = $this->wxClient()
                ->chain('v3/pay/transactions/native')
                ->post(['json' => [
                    'mchid'        => $this->config['merchantId'],
                    'out_trade_no' => $out_trade_no,
                    'appid'        => $this->config['wxAppId'],
                    'description'  => $description,
                    'notify_url'   => $this->config['wxNotifyUrl'],
                    'amount'       => [
                        'total'    => $amount,
                        'currency' => 'CNY'
                    ],
                ]]);
            return json_decode($resp->getBody(),true);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }


    /**
     * wxpay query outTradeNo
     * @param $out_trade_no
     * @return mixed
     * @throws \Exception
     */
    public function outTradeNoQuery($out_trade_no)
    {
        try {
            $resp = $this->wxClient()
                ->chain('v3/pay/transactions/out-trade-no/'.$out_trade_no.'?mchid='.$this->config['merchantId'])
                ->get();
            return json_decode($resp->getBody(),true);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * wxpay query transactions
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function transactionsQuery($id)
    {
        try {
            $resp = $this->wxClient()
                ->chain('v3/pay/transactions/id/'.$id.'?mchid='.$this->config['merchantId'])
                ->get();
            return json_decode($resp->getBody(),true);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * wxpay clone out_trade_no
     * @param $out_trade_no
     * @return mixed
     * @throws \Exception
     */
    public function close($out_trade_no)
    {
        try {
            $resp = $this->wxClient()
                ->chain('v3/pay/transactions/out-trade-no/'.$out_trade_no.'/close')
                ->post(['json' => [
                    'mchid' => $this->config['merchantId']
                ]]);
            return json_decode($resp->getBody(),true);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * wxpay refunds
     * @param $out_trade_no
     * @param $transaction_id
     * @param $amount
     * @return mixed
     * @throws \Exception
     */
    public function refunds($out_trade_no,$transaction_id,$amount)
    {
        try {
            $resp = $this->wxClient()
                ->chain('/v3/refund/domestic/refunds')
                ->post(['json' => [
                    'transaction_id' => $transaction_id,
                    'out_trade_no' => $out_trade_no,
                    'out_refund_no' => $out_trade_no,
                    'amount'       => [
                        'refund'   => $amount,
                        'total'    => $amount,
                        'currency' => 'CNY'
                    ]
                ]]);
            return json_decode($resp->getBody(),true);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * wxpay clicnt commen function
     * @return \WeChatPay\BuilderChainable
     */
    public function wxClient()
    {
        $merchantPrivateKeyInstance = Rsa::from($this->config['merchantPrivateKeyFilePath'], Rsa::KEY_TYPE_PRIVATE);
        $platformPublicKeyInstance = Rsa::from($this->config['platformCertificateFilePath'], Rsa::KEY_TYPE_PUBLIC);
        $platformCertificateSerial = PemUtil::parseCertificateSerialNo($this->config['platformCertificateFilePath']);

        return Builder::factory([
            'mchid'      => $this->config['merchantId'],
            'serial'     => $this->config['merchantCertificateSerial'],
            'privateKey' => $merchantPrivateKeyInstance,
            'certs'      => [
                $platformCertificateSerial => $platformPublicKeyInstance,
            ],
        ]);
    }
}

