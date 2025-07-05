@extends('layouts.app')

@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="contact-us container">
      <div class="mw-930">
        <h2 class="page-title">LIÊN HỆ VỚI CHÚNG TÔI</h2>
      </div>
    </section>

    <hr class="mt-2 text-secondary " />
    <div class="mb-4 pb-4"></div>
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <section class="contact-us container">
      <div class="mw-930">
        <div class="contact-us__form">
          <form name="contact-us-form" class="needs-validation" novalidate="" action="{{ route('contact.store') }}" method="POST">
            @csrf
            <h3 class="mb-5">Liên hệ</h3>
            <div class="form-floating my-4">
              <input type="text" class="form-control" name="name" placeholder="Họ tên *" value="{{ Auth::check() ? Auth::user()->name : '' }}" required="">
              <label for="contact_us_name">Tên *</label>
              <span class="text-danger"></span>
            </div>
            <div class="form-floating my-4">
              <input type="text" class="form-control" name="phone" placeholder="Số điện thoại *" value="{{ Auth::check() ? Auth::user()->phone : '' }}" required="">
              <label for="contact_us_name">SĐT *</label>
              <span class="text-danger"></span>
            </div>
            <div class="form-floating my-4">
              <input type="email" class="form-control" name="email" placeholder="Email *" value="{{ Auth::check() ? Auth::user()->email : '' }}" required="">
              <label for="contact_us_name">Email *</label>
              <span class="text-danger"></span>
            </div>
            <div class="my-4">
              <textarea class="form-control form-control_gray" name="message" placeholder="Nội dung" cols="30"
                rows="8" required=""></textarea>
              <span class="text-danger"></span>
            </div>
            @if ($errors->any())
              <div class="alert alert-danger">
                <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
            <div class="my-4">
              <button type="submit" class="btn btn-primary">Xác Nhận</button>
            </div>
          </form>
        </div>
      </div>
    </section>
  </main>
 @endsection
