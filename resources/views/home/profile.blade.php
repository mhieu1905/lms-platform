<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    @include('home.common.header')
    <link rel="stylesheet" href="{{ asset('assets/css/home/profile.css') }}">
</head>

<body class="main-layout">
    @include('home.common.navbar')
    <section class="page-banner-title"
        style="background-image: url('{{ asset('assets/images/home/main/page-banner.jpg') }}')">
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
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="User Avatar"
                                    class="rounded-circle">
                            @else
                                <img src="{{ asset('assets/images/common/user_placeholder.png') }}"
                                    alt="User Avatar"
                                    class="rounded-circle">
                            @endif
                        </div>
                        <h5 class="mb-2 mt-2 fw-bold">{{ Auth::user()->name }}</h5>
                    </div>
                    <div id="profile-nav" class="profile-nav">
                        <nav class="nav flex-column">
                            <a class="nav-link active" href="{{ route('profile.index') }}" style="color: white">
                                <i class="mdi mdi-book-open-variant me-2"></i> My Courses
                            </a>
                            <a class="nav-link" href="{{ route('profile.details') }}">
                                <i class="mdi mdi-account me-2"></i> Profile
                            </a>
                            <a class="nav-link" href="{{ route('change.password.form') }}">
                                <i class="mdi mdi-account me-2"></i> Change Password
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link"
                                    style="padding-top: 12px;padding-left: 15px;">
                                    <i class="mdi mdi-logout me-2"></i> SignOut
                                </button>
                            </form>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-9 mb-5 site-content">
                <div class="row g-3 mb-4 mt-4 justify-content-center">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="stat-box">
                                <div class="icon-circle icon-green">
                                    <i class="mdi mdi-book-open-page-variant fs-3"></i>
                                </div>
                                <div>
                                    <div class="stat-title">Enrolled Courses</div>
                                    <div class="stat-value">{{ $enrolledCourseCount }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="stat-box">
                                <div class="icon-circle icon-purple">
                                    <i class="mdi mdi-timer-sand fs-3"></i>
                                </div>
                                <div>
                                    <div class="stat-title">In Progress Courses</div>
                                    <div class="stat-value">{{ $progresCrouseCount }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="stat-box">
                                <div class="icon-circle icon-blue">
                                    <i class="mdi mdi-certificate fs-3"></i>
                                </div>
                                <div>
                                    <div class="stat-title">Finished Courses</div>
                                    <div class="stat-value">{{ $completedCourseCount }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="courses-details__tab d-flex align-items-center">
                    <!-- All -->
                    <button
                        class="courses-details__tab_btn d-flex align-items-center justify-content-center gap-5px flex-fill active"
                        data-tab="tab-all">
                        <span><i class="iconify fs-15 courses-details__tab_icon"
                                data-icon="mdi:book-open-page-variant"></i></span>
                        <span class="d-none d-md-block">All</span>
                    </button>

                    <!-- Progress -->
                    <button
                        class="courses-details__tab_btn d-flex align-items-center justify-content-center gap-5px flex-fill"
                        data-tab="tab-progress">
                        <span><i class="iconify fs-15 courses-details__tab_icon"
                                data-icon="mdi:progress-clock"></i></span>
                        <span class="d-none d-md-block">Progress</span>
                    </button>

                    <!-- Finish -->
                    <button
                        class="courses-details__tab_btn d-flex align-items-center justify-content-center gap-5px flex-fill"
                        data-tab="tab-finish">
                        <span><i class="iconify fs-15 courses-details__tab_icon"
                                data-icon="mdi:check-circle-outline"></i></span>
                        <span class="d-none d-md-block">Finish</span>
                    </button>
                </div>


                <!-- Tab Content -->
                <div class="courses-details__tab_content p-0 active" id="tab-all" style="border: none;">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 170px;">Image</th>
                                    <th>Name</th>
                                    <th>Result</th>
                                    <th>Expiration time</th>
                                    <th>End time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($enrolledCourse->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            <h1>EMPTY COURSE</h1>
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($enrolledCourse as $course)
                                        <tr>
                                            <td class="image-cell">
                                                @if ($course->image)
                                                    <a href="{{ route('courses.show', $course->id) }}">
                                                        <img src="{{ asset('storage/' . $course->image) }}"
                                                            alt="{{ $course->title }}" />
                                                    </a>
                                                @else
                                                    <a href="{{ route('courses.show', $course->id) }}">
                                                        <img src="{{ asset('assets/images/common/course_placeholder.png') }}"
                                                            class="img-fluid fill-cell-img"
                                                            alt="{{ $course->title }}" />
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('courses.show', $course->id) }}"
                                                    title="{{ $course->title }}" class="course-title fw-medium">
                                                    {{ Str::limit($course->title, 35) }}
                                                </a>
                                            </td>
                                            <td class="text-center"><span
                                                    class="badge bg-success text-center">{{ $progressMap[$course->id] ?? 0 }}%</span>
                                            </td>
                                            <td>{{ $expirationTime }}</td>
                                            <td> {{ isset($completionTimeMap[$course->id]) ? $completionTimeMap[$course->id] : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="courses-details__tab_content p-0" id="tab-finish" style="border: none;">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 170px;">Image</th>
                                    <th>Name</th>
                                    <th>Result</th>
                                    <th>Expiration time</th>
                                    <th>End time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($completedCourse->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            <h1>EMPTY COURSE</h1>
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($completedCourse as $course)
                                        <tr>
                                            <td class="image-cell">
                                                @if ($course->image)
                                                    <a href="{{ route('courses.show', $course->id) }}">
                                                        <img src="{{ asset('storage/' . $course->image) }}"
                                                            alt="{{ $course->title }}" />
                                                    </a>
                                                @else
                                                    <a href="{{ route('courses.show', $course->id) }}">
                                                        <img src="{{ asset('assets/images/common/course_placeholder.png') }}"
                                                            class="img-fluid fill-cell-img"
                                                            alt="{{ $course->title }}" />
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('courses.show', $course->id) }}"
                                                    title="{{ $course->title }}" class="course-title fw-medium">
                                                    {{ Str::limit($course->title, 35) }}
                                                </a>
                                            </td>
                                            <td class="text-center"><span
                                                    class="badge bg-success text-center">{{ $progressMap[$course->id] ?? 0 }}%</span>
                                            </td>
                                            <td>{{ $expirationTime }}</td>
                                            <td> {{ isset($completionTimeMap[$course->id]) ? $completionTimeMap[$course->id] : '-' }}
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="courses-details__tab_content p-0" id="tab-progress" style="border: none;">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 170px;">Image</th>
                                    <th>Name</th>
                                    <th>Result</th>
                                    <th>Expiration time</th>
                                    <th>End time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($inProgressCourses->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            <h1>EMPTY COURSE</h1>
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($inProgressCourses as $course)
                                        <tr>
                                            <td class="image-cell">
                                                @if ($course->image)
                                                    <a href="{{ route('courses.show', $course->id) }}">
                                                        <img src="{{ asset('storage/' . $course->image) }}"
                                                            alt="{{ $course->title }}" />
                                                    </a>
                                                @else
                                                    <a href="{{ route('courses.show', $course->id) }}">
                                                        <img src="{{ asset('assets/images/common/course_placeholder.png') }}"
                                                            class="img-fluid fill-cell-img"
                                                            alt="{{ $course->title }}" />
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('courses.show', $course->id) }}"
                                                    title="{{ $course->title }}" class="course-title fw-medium">
                                                    {{ Str::limit($course->title, 35) }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-success">{{ $progressMap[$course->id] ?? 0 }}%</span>
                                            </td>
                                            <td>{{ $expirationTime }}</td>
                                            <td> {{ isset($completionTimeMap[$course->id]) ? $completionTimeMap[$course->id] : '-' }}
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
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
