@extends('layouts.app')

@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
      <h2 class="page-title">Vận chuyển và Thanh toán</h2>
      <div class="checkout-steps">
        <a href="cart.html" class="checkout-steps__item active">
          <span class="checkout-steps__item-number">01</span>
          <span class="checkout-steps__item-title">
            <span>Giỏ Hàng</span>
            <em>Danh sách sản phẩm</em>
          </span>
        </a>
        <a href="checkout.html" class="checkout-steps__item active">
          <span class="checkout-steps__item-number">02</span>
          <span class="checkout-steps__item-title">
            <span>Vận chuyển và Thanh toán</span>
            <em>Kiểm tra danh sách sản phẩm</em>
          </span>
        </a>
        <a href="order-confirmation.html" class="checkout-steps__item">
          <span class="checkout-steps__item-number">03</span>
          <span class="checkout-steps__item-title">
            <span>Xác nhận</span>
            <em>Xem lại và gửi đơn hàng của bạn</em>
          </span>
        </a>
      </div>
      <form name="checkout-form" action="https://uomo-html.flexkitux.com/Demo3/shop_order_complete.html">
        <div class="checkout-form">
          <div class="billing-info__wrapper">
            <div class="row">
              <div class="col-6">
                <h4>CHI TIẾT VẬN CHUYỂN</h4>
              </div>
              <div class="col-6">
              </div>
            </div>

            <div class="row mt-5">
              <div class="col-md-6">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="name" required="">
                  <label for="name">Tên *</label>
                  <span class="text-danger"></span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="phone" required="">
                  <label for="phone">SĐT *</label>
                  <span class="text-danger"></span>
                </div>
              </div>
          
              <div class="col-md-4">
                <div class="form-floating mt-3 mb-3">
                  <input type="text" class="form-control" name="state" required="">
                  <label for="state">Địa Chỉ *</label>
                  <span class="text-danger"></span>
                </div>
              </div>
    
          <div class="checkout__totals-wrapper">
            <div class="sticky-content">
              <div class="checkout__totals">
                <h3>Đơn Hàng</h3>
                <table class="checkout-cart-items">
                  <thead>
                    <tr>
                      <th>Sản Phẩm</th>
                      <th align="right">Tổng Tiền</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        Zessi Dresses x 2
                      </td>
                      <td align="right">
                        $32.50
                      </td>
                    </tr>
                    <tr>
                      <td>
                        Kirby T-Shirt
                      </td>
                      <td align="right">
                        $29.90
                      </td>
                    </tr>
                  </tbody>
                </table>
                <table class="checkout-totals">
                  <tbody>
                    <tr>
                      <th>Tổng Tiền</th>
                      <td align="right">$62.40</td>
                    </tr>
                    <tr>
                      <th>Vận Chuyển</th>
                      <td align="right">Miễn Phí Vận Chuyển</td>
                    </tr>
                    <tr>
                      <th>Thuế</th>
                      <td align="right">$19</td>
                    </tr>
                    <tr>
                      <th>TOTAL</th>
                      <td align="right">$81.40</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="checkout__payment-methods">
                <div class="form-check">
                  <input class="form-check-input form-check-input_fill" type="radio" name="checkout_payment_method"
                    id="checkout_payment_method_1" checked>
                  <label class="form-check-label" for="checkout_payment_method_1">
                    Thanh Toán Ngân Hàng
                    <p class="option-detail">
                     
                    </p>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input form-check-input_fill" type="radio" name="checkout_payment_method"
                    id="checkout_payment_method_2">
                  <label class="form-check-label" for="checkout_payment_method_2">
                    MOMO
                    <p class="option-detail">
                     
                    </p>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input form-check-input_fill" type="radio" name="checkout_payment_method"
                    id="checkout_payment_method_3">
                  <label class="form-check-label" for="checkout_payment_method_3">
                    VNPAY
                    <p class="option-detail">
                      
                    </p>
                  </label>
                </div>
                
              </div>
              <button class="btn btn-primary btn-checkout">ĐẶT HÀNG</button>
            </div>
          </div>
        </div>
      </form>
    </section>
  </main>
@endsection