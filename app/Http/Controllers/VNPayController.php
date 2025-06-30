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

    public function createPayment()
    {
        $order = session('order_data');
        if (!$order) return redirect()->route('checkout')->with('error', 'Không tìm thấy thông tin đơn hàng.');

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpay.return');
        $vnp_TmnCode = "S6RMUB02";
        $vnp_HashSecret = "3R1YUK6L2EVEHT36KDR7S5K25OTXI7M9";

        $vnp_TxnRef = uniqid();
        $vnp_OrderInfo = "Thanh toán đơn hàng tại Laravel";
        $vnp_Amount = $order['total'] * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => now()->format('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        ];



        $query = http_build_query($inputData);



        ksort($inputData);

        $hashdata = '';
        foreach ($inputData as $key => $value) {
            $hashdata .= $key . '=' . $value . '&';
        }
        $hashdata = rtrim($hashdata, '&'); // xoá dấu & cuối

        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnpUrl = $vnp_Url . "?" . $query . '&vnp_SecureHash=' . $vnpSecureHash;


        return redirect()->away($vnpUrl);
    }
    public function return(Request $request)
    {
        $order = session('order_data');

        if (!$order) {
            return redirect()->route('user.checkout')->with('error', 'Không tìm thấy phiên đơn hàng.');
        }

        if ($request->vnp_ResponseCode == '00') {
            try {
                DB::beginTransaction();

                $saved = Order::create([
                    'id_user'         => Auth::id() ?? 'guest',
                    'id_payment'      => 'PMT' . now()->timestamp,
                    'id_shipping'     => 'SHIP' . now()->timestamp,
                    'order_date'      => now(),
                    'suptotal'        => $order['subtotal'],
                    'payment_method'  => 'Chuyển Khoản',
                    'payment_status'  => 'Đã Thanh Toán',
                    'name'            => $order['customer']['name'],
                    'phone'           => $order['customer']['phone'],
                    'address'         => $order['customer']['address'],
                    'email'           => $order['customer']['email'] ?? null,
                    'note'            => $order['customer']['note'],
                    'coupon_code'     => $order['customer']['coupon_code'],
                    'coupon_discount' => $order['discount'],
                    'shipping_fee'    => $order['shipping'],
                    'total'           => $order['total'],
                    'status'          => 'Chờ Xác Nhận',
                ]);

                foreach ($order['cart'] as $id => $item) {
                    OrderDetail::create([
                        'order_id'          => $saved->id,
                        'product_detail_id' => $id,
                        'product_name'      => $item['product_name'],
                        'size'              => $item['size'],
                        'color'             => $item['color'],
                        'price'             => $item['price'],
                        'image'             => $item['image'] ?? null,
                        'quantity'          => $item['quantity'],
                    ]);
                }

                DB::commit();
                session()->forget(['order_data', 'cart']);

                return redirect()->route('user.checkout')->with('success', '🎉 Đơn hàng đã được thanh toán qua VNPay!');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('user.checkout')->with('error', 'Lỗi khi lưu đơn hàng: ' . $e->getMessage());
            }
        }

        return redirect()->route('user.checkout')->with('error', 'Thanh toán thất bại hoặc bị huỷ.');
    }
    public function cancel()
    {
        session()->forget('order_data');
        return redirect('/')->with('error', 'Thanh toán đã bị hủy.');
    }
}
