<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderDetail;


class VNPayController extends Controller
{
    /**
     * Create a payment request to VNPay.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['return', 'cancel']);
    }

    public function createPayment(Request $request)
    {
        $order = session('order_data');
        if (!$order) {
            return redirect('/')->with('error', 'Không tìm thấy thông tin đơn hàng.');
        }

        $vnp_Url        = env('VNP_URL');
        $vnp_Returnurl  = env('VNP_RETURN_URL');
        $vnp_TmnCode    = env('VNP_TMN_CODE');
        $vnp_HashSecret = env('VNP_HASH_SECRET');

        $vnp_TxnRef     = time();
        $vnp_Amount     = $order['total'] * 100;

        $inputData = [
            'vnp_Version'    => '2.1.0',
            'vnp_TmnCode'    => $vnp_TmnCode,
            'vnp_Amount'     => $vnp_Amount,
            'vnp_Command'    => 'pay',
            'vnp_CreateDate' => now()->format('YmdHis'),
            'vnp_CurrCode'   => 'VND',
            'vnp_IpAddr'     => $request->ip(),
            'vnp_Locale'     => 'vn',
            'vnp_OrderInfo'  => 'Thanh toán đơn hàng #' . $vnp_TxnRef,
            'vnp_OrderType'  => 'other',
            'vnp_ReturnUrl'  => $vnp_Returnurl,
            'vnp_TxnRef'     => $vnp_TxnRef,
        ];

        ksort($input);
        $query = http_build_query($input);

        $vnp_SecureHash = hash_hmac('sha512', urldecode($query), $vnp_HashSecret);
        $paymentUrl = $vnp_Url . '?' . $query . '&vnp_SecureHash=' . $vnp_SecureHash;

        return redirect()->away($paymentUrl);
    }


    public function return(Request $request)
    {
        $vnp_HashSecret = env('VNP_HASH_SECRET');
        $data = $request->all();
        $receivedHash = $data['vnp_SecureHash'] ?? '';

        unset($data['vnp_SecureHash'], $data['vnp_SecureHashType']);
        ksort($data);
        $calcHash = hash_hmac('sha512', urldecode(http_build_query($data)), $vnp_HashSecret);

        if ($receivedHash === $calcHash && $request->vnp_ResponseCode == '00') {
            $order = session('order_data');
            if (!$order) return view('payment.failed')->with('error', 'Không tìm thấy phiên đơn hàng.');

            try {
                DB::beginTransaction();

                $saved = Order::create([
                    'id_user' => Auth::id() ?? 'guest',
                    'id_payment' => 'PMT' . time(),
                    'id_shipping' => 'SHIP' . time(),
                    'order_date' => now(),
                    'suptotal' => $order['subtotal'],
                    'payment_method' => 'VNPAY',
                    'payment_status' => 'Đã Thanh Toán',
                    'name' => $order['customer']['name'],
                    'phone' => $order['customer']['phone'],
                    'address' => $order['customer']['address'],
                    'email' => $order['customer']['email'],
                    'note' => $order['customer']['note'],
                    'coupon_code' => $order['customer']['coupon_code'],
                    'coupon_discount' => $order['discount'],
                    'shipping_fee' => $order['shipping'],
                    'total' => $order['total'],
                    'status' => 'Chờ Xác Nhận',
                ]);

                foreach ($order['cart'] as $id => $item) {
                    OrderDetail::create([
                        'order_id' => $saved->id,
                        'product_detail_id' => $id,
                        'product_name' => $item['product_name'],
                        'size' => $item['size'],
                        'color' => $item['color'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                    ]);
                }

                DB::commit();
                session()->forget(['order_data', 'cart']);

                return view('payment.success', ['orderId' => $saved->id_payment]);
            } catch (\Exception $e) {
                DB::rollBack();
                return view('payment.failed')->with('error', $e->getMessage());
            }
        }

        return view('payment.failed')->with('error', 'Xác thực không thành công hoặc bị hủy.');
    }
    public function cancel()
    {
        session()->forget('order_data');
        return redirect('/')->with('error', 'Thanh toán đã bị hủy.');
    }
}
