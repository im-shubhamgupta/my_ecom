<?php

namespace App\Services;
// namespace App\Http\Controllers\Controller;

// use App\Http\Controllers\Controller;
use Razorpay\Api\Api;
//RazorpayService
class PaymentService// extends Controller
{
    protected $razorpay;

    public function __construct()
    {
        // Initialize the Razorpay API client
        $this->razorpay = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
    }

    /**
     * Create an order in Razorpay.
     *
     * @param int $amount
     * @param string $currency
     * @param string $receipt
     * @return \Razorpay\Api\Order
     */
    public function createOrder(int $amount, string $currency = 'INR', string $receipt = 'receipt_001')
    {
        ini_set('display_errors',1);
        $order_data = array(
            'amount' => $amount,
            'currency' => $currency,
            'receipt' => $receipt
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($order_data));

        // Razorpay credentials
        curl_setopt($ch, CURLOPT_USERPWD, env('RAZORPAY_KEY') . ':' . env('RAZORPAY_SECRET'));

        // Set SSL verification to use cacert.pem
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_CAINFO, 'F:/xampp software/php/extras/ssl/cacert.pem');  // Path to cacert.pem

        $response = curl_exec($ch);
        echo "response:";
        echoPrint($response);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $response;
        die(78989);
        /*return $this->razorpay->order->create([
            'amount' => $amount, // Amount in paise (100 paise = 1 INR)
            'currency' => $currency,
            'receipt' => $receipt,
            ],
            ['verify' => 'F:/xampp software/php/extras/ssl/cacert.pem']
        );*/
    }

    /**
     * Verify the payment signature.
     *
     * @param array $attributes
     * @return bool
     * @throws \Razorpay\Api\Errors\SignatureVerificationError
     */
    public function verifySignature(array $attributes)
    {
        return $this->razorpay->utility->verifyPaymentSignature($attributes);
    }
}
