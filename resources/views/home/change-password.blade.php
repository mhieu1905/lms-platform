<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    @include('home.common.header')
    <link rel="stylesheet" href="{{ asset('assets/css/home/profile.css') }}">
</head>

<body class="main-layout">
    @include('home.common.navbar')
    <section class="page-banner-title" style="background-image: url('{{ asset('assets/images/home/main/page-banner.jpg') }}')">
        <div class="container pt-80px pb-70px">
            <h1 class="page-banner-title_page fs-40 fw-bolder text-white">Profile</h1>
        </div>
    </section>
    <section class="title-bar pt-20px pb-20px">
        <div class="container">
            <div class="row">
                <nav class="title-bar__nav">
                    <ul class="title-bar__nav_list">
                        <li class="title-bar__nav_items d-inline">
                            <a href="{{ route('home.index') }}" class="title-bar__nav transition-all">Home</a>
                        </li>
                        <li class="title-bar__nav_items d-inline">
                            <a href="{{ route('profile.index') }}" class="title-bar__nav_before transition-all">Profile</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>
    <div class="container site-content">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-md-3">
                <div class="sidebar mt-5 justify-content-start">
                    <div class="d-flex align-items-center justify-content-start text-center">
                        <div class="mt-1 user-avatar mb-2">
                            @if ($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="User Avatar" class="rounded-circle">
                            @else
                            <img src="{{ asset('assets/images/common/user_placeholder.png') }}" alt="User Avatar" class="rounded-circle">
                            @endif
                        </div>
                        <h5 class="mb-2 mt-2 fw-bold">{{ Auth::user()->name }}</h5>
                    </div>
                    <div id="profile-nav" class="profile-nav">
                        <nav class="nav flex-column">
                            <a class="nav-link" href="{{ route('profile.index') }}">
                                <i class="mdi mdi-book-open-variant me-2"></i> My Courses
                            </a>
                            <a class="nav-link" href="{{ route('profile.index') }}">
                                <i class="mdi mdi-account me-2"></i> Profile
                            </a>
                            <a class="nav-link active" href="{{ route('change.password.form') }}" style="color: white">
                                <i class="mdi mdi-account me-2"></i> Change Password
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link" style="padding-top: 12px;padding-left: 15px;">
                                    <i class="mdi mdi-logout me-2"></i> SignOut
                                </button>
                            </form>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-9 mb-5 site-content">
                <form action="{{ route('change.password') }}" method="POST" id="form_change_password" class="mt-5">
                    @csrf
                    @method('PATCH')
                    <h3 class="mb-4">Change Password</h3>
                    <div class="group-field-message">
                        <div class="password-container-change-password">
                            <input type="password" class="form-control transition-all" name="password_old" id="password_old" placeholder="Your Old Password">
                            <span id="pw-show-hide-old-password-change-password" class="pw-show-hide-old-password-change-password">
                                <i class="iconify fs-18 eye-on" data-icon="fa-solid:eye"></i>
                                <i class="iconify fs-20 eye-off" data-icon="ion:eye-off"></i>
                            </span>
                        </div>
                        <small class="text-danger error-message mt-2" id="error-old_password_change"></small>
                        <small class="text-danger error-message mt-4" id="hidden-msg-old_password_change">{{ $errors->change_password->first('password_old') }}</small>
                    </div>
                    <div class="group-field-message">
                        <div class="password-container-change-password">
                            <input type="password" class="form-control transition-all" name="password" id="password_change" placeholder="Your New Password">
                            <span id="pw-show-hide-change-password" class="pw-show-hide-change-password">
                                <i class="iconify fs-18 eye-on" data-icon="fa-solid:eye"></i>
                                <i class="iconify fs-20 eye-off" data-icon="ion:eye-off"></i>
                            </span>
                        </div>
                        <small class="text-danger error-message mt-2" id="error-password_change"></small>
                        <small class="text-danger error-message mt-4" id="hidden-msg-password_change">{{ $errors->change_password->first('password') }}</small>
                        {{-- Tooltip password --}}
                        <div id="password-tooltip-change" class="mt-4" style="display: none; text-align: left; font-size: 12px; color: #6c757d;">
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
                        <div class="password-container-change-password">
                            <input type="password" class="form-control transition-all" name="password_confirmation" id="password_confirmation_change" placeholder="Password Confirm">
                            <span id="pw-show-hide-confirm-change-password" class="pw-show-hide-confirm-change-password">
                                <i class="iconify fs-18 eye-on" data-icon="fa-solid:eye"></i>
                                <i class="iconify fs-20 eye-off" data-icon="ion:eye-off"></i>
                            </span>
                        </div>
                        <small class="text-danger error-message mt-2" id="error-password_confirmation_change"></small>
                        <small class="text-danger error-message mt-4" id="hidden-msg-password_confirm_change">{{ $errors->change_password->first('password_confirmation') }}</small>
                    </div>

                    <button type="submit" class="button-type-01 transition-all fw-medium text-uppercase mt-5" style="border-radius: 10%;">Submit</button>
                </form>
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
