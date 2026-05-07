<!DOCTYPE html>
<html class="no-js" lang="en">

@include('home.common.header')

<body class="main-layout">
    @include('home.common.navbar')
    <section class="page-banner-title" style="background-image: url('{{ asset('assets/images/home/main/page-banner.jpg') }}')">
        <div class="container pt-80px pb-70px">
            <h1 class="page-banner-title_page fs-40 fw-bolder text-white">News</h1>
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
                            <a href="#" class="title-bar__nav_before transition-all pointer-events-none">
                                News </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>
    <section class="blog-page pt-40px pb-80px">
        <div class="container">
            <div class="row">

                <div class="col-12 col-lg-9 mb-30px mb-lg-0">
                    @forelse($news as $item)
                    <div class="blog-page__large mb-60px">
                        <a href="{{ route('news.show', $item->id) }}" class="d-block mb-30px">
                            @if ($item->image)
                            <img src="{{ asset('storage/uploads/news/' . $item->image) }}" alt="News Image">
                            @else
                            <img src="{{ asset('assets/images/common/news_placeholder.png') }}" alt="News Image">
                            @endif
                        </a>
                        <div class="blog-page__large_wrap mb-25px">
                            <div class="blog-page__large_date pe-20px">
                                <span class="date-number d-block fw-bolder">{{ $item->date->format('d') }}</span>
                                <h6 class="date-month d-block fs-13 text-uppercase fw-bolder">{{ $item->date->format('F') }}</h6>
                            </div>
                            <div class="blog-page__large_info">
                                <h6 class="blog-page__large_title line-clamp-2 fw-semibold mb-15px">
                                    <a href="{{ route('news.show', $item->id) }}" class="transition-all">{{ Str::limit($item->title, 40) }}</a>
                                </h6>
                                <ul>
                                    <li class="date-author">
                                        <span class="fs-13 d-block">Posted by</span>
                                        <h6 class="fs-15 fw-semibold">
                                            {{ $item->user->name }}
                                        </h6>
                                    </li>
                                    <li class="date-category">
                                        <span class="fs-13 d-block">Categories</span>
                                        <h6 class="fs-15 fw-semibold">
                                            {{ $item->category->name ?? 'N/A' }}
                                        </h6>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="blog-page__large_desc mb-20px">
                            <p class="line-clamp-2">{!! Str::limit($item->description, 200) !!}</p>
                        </div>
                        <a href="{{ route('news.show', $item->id) }}" class="button-type-02 text-uppercase fw-semibold transition-all">Read More</a>
                    </div>
                    @empty
                    <h3 class="text-muted text-center">
                        There are no news here.
                    </h3>
                    @endforelse
                    {{ $news->links('vendor.pagination.custom') }}

                </div>

                <aside class="col-12 col-lg-3">
                    <div class="blog-page__sidebar">

                        <div class="blog-page__popularblog-page__popular mb-30px">
                            <h6 class="fs-18 text-uppercase fw-semibold mb-20px">Popular Courses</h6>
                            @forelse($popularCourses as $course)
                            <div class="popular-courses d-flex gap-20px mb-20px">
                                <div class="popular-courses_thumb">
                                    <a href="{{ route('courses.show', ['id' => $course->id]) }}">
                                        @if ($course->image)
                                        <img src="{{ asset('storage/' . $course->image) }} " style="height: 70px;" alt="" class="img-courses">
                                        @else
                                        <img src="{{ asset('assets/images/common/course_placeholder.png') }}" style="height: 70px;" alt="Course Image" class="img-courses">
                                        @endif
                                    </a>
                                </div>
                                <div class="popular-courses_content">
                                    <h6 class="fs-15 fw-semibold line-clamp-2 mb-10px">
                                        <a href="{{ route('courses.show', ['id' => $course->id]) }}" class="transition-all fw-semibold">{{ Str::limit($course->title, 25) }}</a>
                                    </h6>
                                    @if ($course->sale_price && $course->sale_price > 0)
                                        <p class="courses-layout-1__price fw-semibold mb-0">
                                            ${{ round($course->sale_price, 2) }}
                                        </p>
                                    @elseif ($course->regular_price > 0)
                                        <p class="courses-layout-1__price fw-semibold mb-0">
                                            ${{ round($course->regular_price, 2) }}
                                        </p>
                                    @else
                                        <p class="popular-courses_price fw-bolder free-status">Free</p>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <h3 class="text-muted">There are no course here.</h3>
                            @endforelse
                        </div>
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

    @include('home.auth.login')
    @include('home.auth.register')
    @include('home.common.script')
</body>

</html>
