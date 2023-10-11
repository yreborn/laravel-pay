
当前插件集成了微信支付，支付宝支付开箱即用,支持laravel 9版本以上.

下载方式:

    
    1、通过composer下载:composer require yreborn/laravel-pay

    2、在composer.json 新增 "yreborn/laravel-pay": "dev-main"，在命令行使用composer install进行安装


1、创建config/pay.php 配置文件

    <?php
    
     //应用ID,您的APPID。
    'appId' => "",

    //商户私钥
    'merchantPrivateKey' => "",

    //异步通知地址
    'notifyUrl' => "",

    //同步跳转
    'return_url' => "",

    //签名方式
    'signType'=>"RSA2",

    //支付宝网关
    'gatewayHost' => "https://openapi.alipay.com/gateway.do",

    //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    'alipayPublicKey' => "",

    
    //微信商户号
    'merchantId' => '',
    //密钥序列号
    'merchantCertificateSerial' => '',
    //api私钥文件
    'merchantPrivateKeyFilePath' => '',
    //微信平台公钥
    'platformCertificateFilePath' => '',
    //公众号appid
    'wxAppId' => '',
    //微信回调地址
    'wxNotifyUrl' => '',



2、在config/app目录加载插件

        'providers' => [
            Yreborn\LaravelUpload\PayServiceProvider::class
        ],
        'aliases' => [
            'Pay' => Yreborn\LaravelUpload\Facades\Pay::class
        ],



3、在控制器使用


        <?php
        
        namespace App\Http\Controllers;
        
        use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
        use Illuminate\Foundation\Validation\ValidatesRequests;
        use Illuminate\Http\Request;
        use Illuminate\Routing\Controller as BaseController;
        use Illuminate\Support\Facades\Storage;
        use Yreborn\LaravelUpload\Facades\Upload;
        
        class IndexController extends Controller
        {
        
            public function index(Request $request)
            {
                 //支付宝支付  商品名称，商户订单，金额，回调地址
                //        $data = Pay::pc('测试商品','88888999','0.01','http://pay.com/return_url.php'); //pc支付
                //        $data = Pay::wap('测试商品','88888999','0.01','http://pay.com/return_url.php'); //wap支付
                //        $data = Pay::refund('88888999','0.01'); //退款
                //        $data = Pay::query('88888999'); //查询
                
                //微信支付  商户订单，金额，商品名称， 微信支付订单
                //        $data = Pay::nativePay('2141asdasz11','测试商品',1); //nativePay 下单支付
                //        return QrCode::generate($data['code_url']); //生成的二维码
                
                //        $data = Pay::outTradeNoQuery('2141asdasz'); //商户订单查询
                //        $data = Pay::transactionsQuery('4200002033202310112002780543');//微信订单订单查询
                //        $data = Pay::close('2141asdasz11'); //关闭订单
                //        $data = Pay::refunds('2141asdasz','4200002033202310112002780543',1); //订单退款

                dd($data);
            }
        }

        