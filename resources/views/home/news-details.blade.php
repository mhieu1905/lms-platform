<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    @include('home.common.header')
</head>

<body class="main-layout">
    @include('home.common.navbar')
    <section class="page-banner-title"
        style="background-image: url('{{ asset('assets/images/common/main/page-banner.jpg') }}')">
        <div class="container pt-80px pb-70px">
            <h1 class="page-banner-title_page fs-40 fw-bolder text-white">{{ $news->category->name }}</h1>
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
                            <a href="{{ route('news.index') }}"
                                class="title-bar__nav_before transition-all">News</a>
                        </li>
                        <li class="title-bar__nav_items d-inline">
                            <a href="#" class="title-bar__nav_before transition-all">{{ $news->title }}</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>

    <section class="blog-single-simple pt-20px pb-80px">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-9 mb-30px mb-lg-0">
                    <div class="blog-single-simple__wrapper">
                        @if (!empty($news->image))
                            <img src="{{ asset('storage/uploads/news/' . $news->image) }}" alt=""
                                style="width: 850px; height: 490px;" class="mb-30px">
                        @else
                            <img src="{{ asset('assets/images/common/course_placeholder.png') }}" alt=""
                                style="width: 850px; height: 490px;" class="mb-30px">
                        @endif
                        <h1 class="fs-30 fw-bolder mb-20px">{{ $news->title }}</h1>
                        <div class="blog-page__large_info mb-20px">
                            <ul>
                                <li class="date-author">
                                    <span class="fs-13 d-block">Posted by</span>
                                    <h6 class="fs-15 fw-semibold">
                                        <p>{{ $news->user->name }}</p>
                                    </h6>
                                </li>
                                <li class="date-category">
                                    <span class="fs-13 d-block">Categories</span>
                                    <h6 class="fs-15 fw-semibold">
                                        <p>{{ $news->category->name ?? 'N/A' }}</p>
                                    </h6>
                                </li>
                                <li class="date-date">
                                    <span class="fs-13 d-block">Date</span>
                                    <h6 class="fs-15 fw-semibold">
                                        <p>{{ $news->date->format('d/m/Y') }}</p>
                                    </h6>
                                </li>

                            </ul>
                        </div>
                        @if (!empty($news->description))
                            <div class="blog-single-simple__brief mb-50px">
                                {!! $news->description !!}
                            </div>
                        @else
                            <h5 class="text-muted">EMPTY DESCRIPTION</h5>
                        @endif
                        <nav
                            class="blog-single-simple__navigation d-flex align-items-center justify-content-between mt-30px mb-50px">
                            @if (isset($prevPost))
                                <a href="{{ route('news.show', $prevPost->id) }}"
                                    class="blog-single-simple__navigation_items transition-all d-flex align-items-center gap-10px">
                                    <div class="blog-single-simple__navigation_pre">
                                        <p class="fs-15 mb-10px">Previous Post</p>
                                        <h6 class="mb-10px">{{ Str::limit($prevPost->title, 50) }}</h6>
                                        <p class="fs-13">{{ $prevPost->date->format('d/m/Y') }}</p>
                                    </div>
                                </a>
                            @else
                                <a href="#" style="pointer-events: none;" onclick="event.preventDefault()"
                                    class="blog-single-simple__navigation_items transition-all d-flex align-items-center gap-10px">
                                    <div class="blog-single-simple__navigation_pre">
                                        <p class="fs-15 mb-10px">Previous Post</p>
                                        <h6 class="mb-10px text-muted">This is oldest post.</h6>
                                        <p class="fs-13"></p>
                                    </div>
                                </a>
                            @endif

                            @if (isset($nextPost))
                                <a href="{{ route('news.show', $nextPost->id) }}"
                                    class="blog-single-simple__navigation_items transition-all d-flex align-items-center justify-content-end gap-10px">
                                    <div class="blog-single-simple__navigation_next">
                                        <p class="fs-15 mb-10px">Next Post</p>
                                        <h6 class="mb-10px">{{ $nextPost->title }}</h6>
                                        <p class="fs-13">{{ $nextPost->date->format('d/m/Y') }}</p>
                                    </div>
                                </a>
                            @else
                                <a href="#" style="pointer-events: none;" onclick="event.preventDefault()"
                                    class="blog-single-simple__navigation_items transition-all d-flex align-items-center justify-content-end gap-10px">
                                    <div class="blog-single-simple__navigation_next">
                                        <p class="fs-15 mb-10px">Next Post</p>
                                        <h6 class="mb-10px text-muted">This is latest post.</h6>
                                        <p class="fs-13"></p>
                                    </div>
                                </a>
                            @endif
                        </nav>
                        @if (isset($youMayLike))
                            <div class="blog-ymal">
                                <h5 class="fw-semibold mb-30px">You may also like</h5>
                                <div class="row gy-30px">
                                    @foreach ($youMayLike as $item)
                                        <div class="col-12 col-sm-6 col-md-4">
                                            <article class="blog-ymal_items">
                                                <a href="{{ route('news.show', $item->id) }}">
                                                    <img src="{{ asset('storage/uploads/news/' . $item->image) }}"
                                                        alt="">
                                                </a>
                                                <div class="blog-ymal_content pt-20px">
                                                    <h6 class="fw-semibold text-capitalize line-clamp-2 mb-10px">
                                                        <a href="{{ route('news.show', $item->id) }}"
                                                            class="fs-18 transition-all">
                                                            {{ $item->title }}
                                                        </a>
                                                    </h6>
                                                    <p class="blog-ymal_date fs-13">
                                                        {{ $item->date }}
                                                    </p>
                                                </div>
                                            </article>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <aside class="col-12 col-lg-3">
                    <div class="blog-page__sidebar">
                        @if (isset($popularCourses))
                            <div class="blog-page__popular mb-30px">
                                <h6 class="fs-18 text-uppercase fw-semibold mb-20px">Popular Courses</h6>
                                @foreach ($popularCourses as $course)
                                    <div class="popular-courses d-flex gap-20px mb-20px">
                                        <div class="popular-courses_thumb">
                                            @if (!empty($course->image))
                                                <a href="{{ route('courses.show', $course->id) }}">
                                                    <img src="{{ asset('storage/' . $course->image) }} " style="height: 70px;">
                                                </a>
                                            @else
                                                <a href="{{ route('courses.show', $course->id) }}">
                                                    <img
                                                        src="{{ asset('assets/images/common/course_placeholder.png') }} " style="height: 70px;">
                                                </a>
                                            @endif
                                        </div>
                                        <div class="popular-courses_content">
                                            <h6 class="fs-15 fw-semibold line-clamp-2 mb-10px">
                                                <a href="{{ route('courses.show', $course->id) }}"
                                                    class="transition-all fw-semibold">{{ $course->title }}</a>
                                            </h6>
                                            @if (!empty($course->sale_price))
                                                <p class="popular-courses_price fw-bolder price-status">
                                                    ${{ round($course->sale_price, 2) }}
                                                </p>
                                            @else
                                                @if ($course->regular_price > 0)
                                                    <p class="popular-courses_price fw-bolder price-status">
                                                        ${{ round($course->regular_price, 2) }}
                                                    </p>
                                                @else
                                                    <p class="popular-courses_price fw-bolder free-status">
                                                        FREE
                                                    </p>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($latestNews))
                            <div class="blog-page__latest mb-30px">
                                <h6 class="fs-18 text-uppercase fw-semibold mb-20px">Latest Posts</h6>
                                @foreach ($latestNews as $item)
                                    <div class="latest-posts d-flex gap-20px mb-20px">
                                        <div class="latest-posts_thumb">
                                            <a href="{{ route('news.show', $item->id) }}">
                                                @if (empty($item->iamge))
                                                    <img src="{{ asset('storage/uploads/news/' . $item->image) }}" style="height: 70px;"
                                                        alt="">
                                                @else
                                                    <img src="{{ asset('assets/images/common/course_placeholder.png ') }}"  style="height: 70px;"
                                                        alt="">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="latest-posts_content">
                                            <h6 class="fs-15 fw-semibold line-clamp-2 mb-10px">
                                                <a href="{{ route('news.show', $item->id) }}"
                                                    class="transition-all fw-semibold">{{ $item->title }}</a>
                                            </h6>
                                            <p class="latest-posts_date fs-13">{{ $item->date->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </aside>
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
