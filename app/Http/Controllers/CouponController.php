<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function coupons()
    {
        $coupons = Coupon::orderBy('id', 'asc')->paginate(10);
        return view('admin.coupons', compact('coupons'));
    }

    public function add_coupon()
    {

        return view('admin.coupon-add');
    }

    public function coupon_store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0',
            'quantity'=> 'required|integer|min:1',
        ]);
        $coupon = new Coupon();
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->quantity = $request->quantity;
        $coupon->save();

        return redirect()->route('admin.coupons')->with('status', 'Coupon đã được thêm thành công!');
    }

    public function edit_coupon($id)
    {
        $coupon = Coupon::find($id);
        return view('admin.coupon-edit', compact('coupon'));
    }

    public function update_coupon(Request $request, $id)
    {

        $request->validate([
            'code' => 'string|required', //'string| unique:coupons,code,' //. $request->id,//['required',Rule::unique('coupons','code')->ignore($id)],
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'quantity' => 'required',
        ]);

        $coupon = Coupon::find($id);
        $data = $request->all();
        $status = $coupon->fill($data)->save();
        if ($status) {
            request()->session()->flash('success', 'Cập nhật mã thành công');
        } else {
            request()->session()->flash('error', 'Vui lòng thử lại !!!');
        }

        // $coupon->code = $request->code;
        // $coupon->type = $request->type;
        // $coupon->value = $request->value;
        // $coupon->status = $request->status;
        // $coupon->save();

        return redirect()->route('admin.coupons');//->with('status', 'Coupon đã được cập nhật thành công!');
    }

    public function delete_coupon($id)
    {
        // $coupon = Coupon::find($id);
        // $coupon->delete();

        $coupon = Coupon::findOrFail($id);
        if ($coupon) {
            $status = $coupon->delete();
            if ($status) {
                request()->session()->flash('success', 'Xóa mã thành công');
            } else {
                request()->session()->flash('error', 'Lỗi, vui lòng thử lại!!');
            }
            return redirect()->route('admin.coupons');
        } else {
            request()->session()->flash('error', 'Không tìm thấy mã giảm giá');
            return redirect()->back();
        }

    }
}
