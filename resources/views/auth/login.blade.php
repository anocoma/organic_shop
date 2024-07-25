@extends('layouts.auth')
@section('content')

    <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
        <div class="max-w-464-px mx-auto w-100">
            <div>
                <a href="#" class="mb-40 max-w-290-px">
                    <img src="{{ asset('img/lg4.png') }}" alt="">
                </a>
                <h4 class="mb-12">Đăng nhập</h4>
                <p class="mb-32 text-secondary-light text-lg">Chào mừng trở lại trang quản trị</p>
            </div>
            <form action="{{route('login')}}" method="POST">
                @csrf

                <div class="icon-field mb-16">
                    <span class="icon top-50 translate-middle-y">
                        <iconify-icon icon="mage:email"></iconify-icon>
                    </span>
                    <input type="email" name="email" value="{{old('email')}}"
                           class="@error('email') is-invalid @enderror form-control h-56-px bg-neutral-50 radius-12"
                           placeholder="Email">
                    @error('email') <p class="text-danger">{{$message}}</p> @enderror
                </div>
                <div class="position-relative mb-20">
                    <div class="icon-field">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                        </span>
                        <input name="password" type="password" class="form-control h-56-px bg-neutral-50 radius-12" id="your-password"
                               placeholder="Password">
                    </div>
                    <span
                        class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                        data-toggle="#your-password"></span>
                </div>
                <div class="">
                    <div class="d-flex justify-content-between gap-2">
                        <div class="form-check style-check d-flex align-items-center">
                            <input class="form-check-input border border-neutral-300" type="checkbox" value=""
                                   id="remeber">
                            <label class="form-check-label" for="remeber">Lưu đăng nhập </label>
                        </div>
                        <a href="javascript:void(0)" class="text-primary-600 fw-medium">Quên mật khẩu?</a>
                    </div>
                </div>

                <button type="submit" class="btn btn-success text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32"> Đăng
                    nhập
                </button>

                <div class="mt-32 center-border-horizontal text-center">
                    <span class="bg-base z-1 px-4">Hoặc</span>
                </div>
                <div class="mt-32 d-flex align-items-center gap-3">
                    <button type="button"
                            class="fw-semibold text-primary-light py-16 px-24 w-50 border radius-12 text-md d-flex align-items-center justify-content-center gap-12 line-height-1 bg-hover-primary-50">
                        <iconify-icon icon="ic:baseline-facebook"
                                      class="text-primary-600 text-xl line-height-1"></iconify-icon>
                        Facebook
                    </button>
                    <button type="button"
                            class="fw-semibold text-primary-light py-16 px-24 w-50 border radius-12 text-md d-flex align-items-center justify-content-center gap-12 line-height-1 bg-hover-primary-50">
                        <iconify-icon icon="logos:google-icon"
                                      class="text-primary-600 text-xl line-height-1"></iconify-icon>
                        Google
                    </button>
                </div>
                <div class="mt-32 text-center text-sm">
                    <p class="mb-0">Không có tài khoản? <a href="{{route('register')}}" class="text-primary-600 fw-semibold">Đăng ký</a></p>
                </div>

            </form>
        </div>
    </div>
@endsection
