<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <a class="navbar-brand brand-logo" href="{{ route('admin.dashboard') }}"><img src="{{ asset('assets/images/home/logo/logomt.png') }}" alt="MTedu"></a>
        <a class="navbar-brand brand-logo-mini" style="padding-left: 25px" href="{{ route('admin.dashboard') }}"><img
                src="{{ asset('assets/images/favicon.png') }}" alt="logo" /></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right">
            @auth
                <li class="nav-item nav-profile">
                    <a href="{{ route('home.index') }}">
                        <div class="nav-profile-text">
                            <p class="mb-1 text-black">Home Page</p>
                        </div>
                    </a>
                </li>

                <li class="nav-item nav-profile dropdown">
                    <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <div class="nav-profile-img">
                            <img src="{{ asset('assets/images/common/user_placeholder.png') }}" alt="image">
                            <span class="availability-status online"></span>
                        </div>
                        <div class="nav-profile-text">
                            <p class="mb-1 text-black">{{ Auth::user()->name }}</p>
                        </div>
                    </a>
                    <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                        <form action="{{ route('logout') }}" method="POST" id="logout_form" style="display: inline;">
                            @csrf
                        </form>
                        <a class="dropdown-item btn-logout" href="#">
                            <i class="mdi mdi-logout me-2 text-primary"></i> Signout
                        </a>
                    </div>
                </li>
            @endauth

        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>
