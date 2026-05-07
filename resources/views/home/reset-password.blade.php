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
                        <h6 class="fw-semibold mb-30px">Reset Your Password</h6>
                        @if (session('status'))
                        <p style="color:green">{{ session('status') }}</p>
                        @endif
                        <form action="{{ route('password.update') }}" method="POST" id="form_reset-password">
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ $email }}">
                            @csrf
                            <div class="group-field-message">
                                <div class="password-container-reset-password">
                                    <input type="password" class="transition-all" name="password" id="password_reset" placeholder="Your New Password">
                                    <span id="pw-show-hide-reset-password" class="pw-show-hide-reset-password">
                                        <i class="iconify fs-18 eye-on" data-icon="fa-solid:eye"></i>
                                        <i class="iconify fs-20 eye-off" data-icon="ion:eye-off"></i>
                                    </span>
                                </div>
                                <small class="text-danger error-message mt-2" id="error-password_reset"></small>
                                <small class="text-danger error-message mt-2" id="hidden-msg-password_reset">{{ $errors->first('password') }}</small>
                                {{-- Tooltip password --}}
                                <div id="password-tooltip_reset" class="mt-3" style="display: none; text-align: left; font-size: 12px; color: #6c757d;">
                                    <strong>Password must contain:</strong>
                                    <ul style="margin: 5px 0 0 15px; padding-left: 0;">
                                        <li>At least 8 characters</li>
                                        <li>1 uppercase & 1 lowercase letter</li>
                                        <li>1 number</li>
                                        <li>1 special character (!@#$...)</li>
                                        <li>No spaces</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="group-field-message">
                                <div class="password-container-reset-password">
                                    <input type="password" class="transition-all" name="password_confirmation" id="password_confirmation_reset" placeholder="Password Confirm">
                                    <span id="pw-show-hide-confirm-reset-password" class="pw-show-hide-confirm-reset-password">
                                        <i class="iconify fs-18 eye-on" data-icon="fa-solid:eye"></i>
                                        <i class="iconify fs-20 eye-off" data-icon="ion:eye-off"></i>
                                    </span>
                                </div>
                                <small class="text-danger error-message mt-2" id="error-password_confirmation_reset"></small>
                                <small class="text-danger error-message mt-2" id="hidden-msg-password_confirm_reset">{{ $errors->first('password_confirmation') }}</small>
                            </div>
                            <button type="submit" id="submitBtn_reset" class="button-type-02 transition-all fw-medium text-uppercase">Reset password</button>
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
