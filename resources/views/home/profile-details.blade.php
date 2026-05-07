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
                            <a class="nav-link active" href="{{ route('profile.index') }}" style="color: white">
                                <i class="mdi mdi-account me-2"></i> Profile
                            </a>
                            <a class="nav-link" href="{{ route('change.password.form') }}">
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
                <form action="{{ route('profile.update') }}" enctype="multipart/form-data" method="POST" id="form_edit_profile" class="mt-5">
                    @csrf
                    @method('PUT')
                    <div class="group-field-message text-start">
                        <label for="name_profile">Full Name <span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" name="name" id="name_profile" value="{{ old('name', $user->name) }}">
                        <small class="text-danger error-message mt-1" id="error-name_profile"></small>
                        <small class="text-danger error-message mt-4" id="hidden-msg-name_profile">{{ $errors->profile_edit->first('name') }}</small>
                    </div>

                    <div class="group-field-message text-start">
                        <label for="phone_profile">Phone Number</label>
                        <input type="text" class="form-control" name="phone" id="phone_profile" value="{{ old('phone', $user->phone ?? '') }}">
                        <small class="text-danger error-message mt-1" id="error-phone_profile"></small>
                        <small class="text-danger error-message mt-4" id="hidden-msg-phone_profile">{{ $errors->profile_edit->first('phone') }}</small>
                    </div>

                    <div class="group-field-message text-start">
                        <label for="address_profile">Address</label>
                        <input type="text" class="form-control" name="address" id="address_profile" value="{{ old('address', $user->address ?? '') }}">
                        <small class="text-danger error-message mt-1" id="error-address_profile"></small>
                        <small class="text-danger error-message mt-4" id="hidden-msg-address_profile">{{ $errors->profile_edit->first('address') }}</small>
                    </div>
                    <div class="form-group">
                        <label for="image">Avatar</label>

                        {{-- Old image --}}
                        @if($user->avatar)
                        <div class="mb-2 img-edit-avatar" id="old-image-wrapper">
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="User Avatar" id="old-image">
                        </div>
                        @else
                        <div class="mb-2 img-edit-avatar" id="old-image-wrapper">
                            <img src="{{ asset('assets/images/common/user_placeholder.png') }}" alt="User Avatar" id="old-image">
                        </div>
                        @endif

                        {{-- Preview image --}}
                        <div class="mb-2 img-edit-avatar" id="new-image-preview" style="display: none;">
                            <img src="" alt="New Image Preview" id="preview-image">
                        </div>

                        <input type="file" id="avatar" name="avatar" class="form-control" accept=".jpeg,.jpg,.png,.gif,.webp"">
                        <small class=" text-danger error-message mb-3" id="error-avatar"></small>
                        <small class="text-danger error-message mt-4" id="hidden-msg-avatar">{{ $errors->profile_edit->first('avatar') }}</small>
                    </div>

                    <button type="submit" class="button-type-02 transition-all fw-medium text-uppercase mt-5" style="border-radius: 10%;">Update</button>
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
