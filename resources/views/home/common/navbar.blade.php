
<body class="" data-page-viewed="">
    <div class="eu-location" data-eu="0"></div>
    <div class="wrapper">
        <div class="marquee-top marquee-top--gray">
            <div class="marquee-top__wrapper" data-controller="header-marquee">
                <a href="/courses" class="item-link" aria-label="Courses"></a>
                <div class="marquee-top__item">
                    <strong>View Our Course</strong>
                    <svg class="marquee-top__ico" viewBox="0 0 100 125" width="20">
                        <path
                            d="M50,97.5c-26.1915607,0-47.5-21.3084412-47.5-47.5S23.8084393,2.5,50,2.5S97.5,23.8084393,97.5,50S76.1915588,97.5,50,97.5z   M50,7.0238094C26.3030148,7.0238094,7.0238104,26.3030128,7.0238104,50S26.3030148,92.9761887,50,92.9761887  S92.9761887,73.6969833,92.9761887,50S73.6969833,7.0238094,50,7.0238094z" />
                        <g>
                            <ellipse cx="35.2381935" cy="34.3672791" rx="4.7493854" ry="6.5021348" />
                        </g>
                        <g>
                            <ellipse cx="64.7618027" cy="34.3672791" rx="4.7493854" ry="6.5021348" />
                        </g>
                        <path
                            d="M50,80.4100494c-14.6896172,0-26.6402111-11.4621811-26.6402111-25.5509796  c0-1.2492523,1.0131454-2.2619057,2.2619038-2.2619057c1.2487602,0,2.2619057,1.0126534,2.2619057,2.2619057  c0,11.594223,9.9213562,21.0271759,22.1164017,21.0271759s22.1164017-9.4329529,22.1164017-21.0271759  c0-1.2492523,1.0131454-2.2619057,2.2619019-2.2619057c1.248764,0,2.2619095,1.0126534,2.2619095,2.2619057  C76.640213,68.9478683,64.689621,80.4100494,50,80.4100494z" />
                    </svg>
                    <span>Upgrade your skills – anytime, anywhere with MTEdu</span>
                </div>
            </div>
        </div>
        <header id="header" data-controller="search" data-search-url-value="tv_search_inspiration"
            data-search-selected-type-value="inspiration">
            <div class="inner">
                <div class="c-header-main">
                    <div class="header-main" data-search-target="headerMain">
                        <div class="header-main__overlay " data-search-target="overlay"
                            data-action="click->search#close"></div>
                        <div class="header-main__container justify-content-space-between">
                            <div class="header-main__hamburger" data-search-target="hamburger"
                                data-action="click->search#toggleMobile">
                                <svg class="ico-svg" viewBox="0 0 20 20" width="16">
                                    <path d="M2,4h16v2H2V4z M2,9h16v2H2V9z M2,14h16v2H2V14z"></path>
                                </svg>
                            </div>
                            <a href="/" class="header-main__logo" aria-label="MTedu">
                                <img src="{{ asset('assets/images/home/logo/logomt.png') }}" width="120" height="20" alt="MTedu">
                            </a>
                            <nav class="nav-header-main" data-search-target="navHeaderMain">
                                <ul class="nav-header-main__list">
                                    <li class="nav-header-main__item">
                                        <a class="nav-header-main__link" href="{{ '/courses' }}">Courses
                                        </a>
                                    </li>
                                    <li class="nav-header-main__item">
                                        <a class="nav-header-main__link" href="/events">
                                            Event
                                            <span class="budget-tag budget-tag--small--solid--black anim-shiny">
                                                <span>New</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-header-main__item">
                                        <a class="nav-header-main__link" href="/news/">Blog
                                        </a>
                                    </li>
                                    @guest
                                        <li class="nav-header-main__item">
                                            <a class="nav-header-main__link" href="/register-teacher/">Become a teacher
                                            </a>
                                        </li>
                                    @endguest
                                </ul>
                            </nav>
                            <div class="header-main__right">
                                @guest
                                    <div class="header-main__user header-main__bts ">
                                            <strong class=" button button--small--rounded handle-login header-main__link hidden-sm" >Login</strong>
                                            <strong class=" button button--small--outline--rounded handle-register header-main__link hidden-sm" >Sign Up</strong>
                                            <span class="header-main__ico">
                                                <svg class="ico-svg handle-login" viewBox="0 0 20 20" width="20" fill="currentColor">
                                                    <path d="M10 2C7.79 2 6 3.79 6 6s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 6c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm0 3c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                                </svg>

                                          </span>
                                    </div>
                                @endguest
                                @auth
                                @php
                                    $user = Auth::user();
                                    $roles = $user->roles->pluck('name')->toArray();
                                @endphp
                                
                                <div class="bt-dropdown-user">
                                    <figure id="js-user-details" data-user="{&quot;username&quot;:&quot;minh-hieu-3&quot;,&quot;id&quot;:2717705,&quot;displayName&quot;:&quot;minh-hieu-3&quot;}" class="circle-avatar">
                                            <a class="bt-dropdown-user__link" href="" onclick="return false;" aria-label="User menu">
                                                @if ($user->avatar)
                                                    <img class="circle-avatar__img" src="{{ asset('storage/' . $user->avatar) }}" width="32" height="32" alt="">

                                                @else
                                                    <img class="circle-avatar__img" src="{{ asset('assets/images/common/user_placeholder.png') }}" width="32" height="32" alt="">
                                                @endif
                                            </a>
                                    </figure>
                                    <div class="dropdown-user">
                                        <ul class="dropdown-user__list">
                                            @if (in_array('admin', $roles))
                                                 <li>
                                                    <a class="dropdown-user__link" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>   
                                                </li>
                                            @elseif(in_array('teacher', $roles))
                                                <li>
                                                    <a class="dropdown-user__link" href="{{ route('admin.courses.index') }}">Teacher Dashboard</a>
                                                </li>   
                                                @else
                                                <li>
                                                    <a class="dropdown-user__link" href="/profile/my-courses">Dashboard</a>
                                                </li>
                                            @endif
                                        </ul>
                                        <ul class="dropdown-user__list">
                                            <li>
                                                <a class="dropdown-user__link" href="/profile/details">Profile</a>
                                            </li>
                                           
                                            <li>
                                                <a class="dropdown-user__link" href="/courses">
                                                    Courses
                                                </a>
                                            </li>
                                        </ul>
                                        <ul class="dropdown-user__list">
                                            <li>
                                                <form action="{{ route('logout') }}" method="POST" id="logout_form" style="display: inline;">
                                                    @csrf
                                                </form>
                                                <a class="dropdown-user__link btn-logout" href="">
                                                    Logout
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div> 
                                <div class="user-name">
                                  Hello: {{ $user->name }} 
                                </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    </div>
</body>
</html>