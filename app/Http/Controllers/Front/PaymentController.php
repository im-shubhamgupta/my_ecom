<?php

// namespace App\Http\FrontControllers;
namespace App\Http\Controllers\Front;
// namespace App\Services;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Razorpay\Api\Api;

class PaymentController extends Controller
{
    public static function initiatePayment(Request $request)
    {
        // Razorpay credentials from .env
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        // Prepare order data
        $orderData = [
            'receipt'         => isset($request->input('receipt')) ? $request->input('receipt') : 'default_receipt',
            'amount'          => $request->input('amount') * 100, // Convert to paise
            'currency'        => 'INR',
            'payment_capture' => 1 // auto capture
        ];

        // Create an order
        $order = $api->order->create($orderData);
        print_r($order);

        return array('payment', ['order_id' => $order['id'], 'amount' => $orderData['amount']]);
    }

    public static function paymentCallback(Request $request)
    {
        // Handle Razorpay payment verification and status
        $signature = $request->input('razorpay_signature');
        $orderId = $request->input('razorpay_order_id');
        $paymentId = $request->input('razorpay_payment_id');

        // You can verify the payment signature here using Razorpay's SDK
        // and update the order status accordingly in your database

        return view('payment-success', ['payment_id' => $paymentId]);
    }
}
