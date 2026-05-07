<!DOCTYPE html>
<html class="no-js" lang="en">
@include('home.common.header')
<body class="main-layout">
    @include('home.common.navbar')
    <section class="page-banner-title" style="background-image: url('{{ asset('assets/images/home/main/page-banner.jpg') }}'); padding-top: 97px">
        <div class="container pt-80px pb-70px">
            <h1 class="page-banner-title_page fs-40 fw-bolder text-white">Account</h1>
        </div>
    </section>
    <section class="lost-password mt-60px mb-80px">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="lost-password__wrapper">
                        <h6 class="fw-semibold mb-30px">Get Your Password</h6>
                        <p class="mb-50px">Lost your password? Please enter your email address. You will receive a link to create a new password via email.</p>
                        <form action="{{ route('password.email') }}" method="POST" id="form_forgot">
                            @csrf
                            <input type="text" name="email" id="email_forgot" class="transition-all mb-20px" placeholder="Enter your email" value="{{ old('email') }}">
                            <small class="text-danger error-message mb-3" id="error-email_forgot"></small>
                            @error('email')
                                <small class="text-danger error-message mb-3" id="hidden-msg-email_forgot">{{ $message }}</small>
                            @enderror
                            <button type="submit" class="button-type-02 transition-all fw-medium text-uppercase">Reset password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('home.common.footer')
    <div class="scroll-progress d-none">
        <a href="" class="scroll-progress__link">
            <span class="iconify fs-18" data-icon="grommet-icons:up"></span>
            <span class="scroll-progress__line scroll-progress__main">
                <span id="scr-progress" class=""></span>
            </span>
        </a>
    </div>

    <div class="search-wrapper">
        <div class="search-overlay"></div>
        <div class="search-popup">
            <form action="#" method="GET">
                <input type="text" id="search-input" name="search-query" placeholder="Search courses...">
                <button type="submit">
                    <i class="iconify fs-22 text-white eye-on search-popup_icon" data-icon="iconamoon:search"></i>
                </button>
            </form>
        </div>
    </div>
    @include('home.auth.login')
    @include('home.auth.register')
    @include('home.common.script')

</body>

</html>
