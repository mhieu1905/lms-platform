<!DOCTYPE html>
<html class="no-js" lang="en">

@include('home.common.header')

<body class="main-layout">
    @include('home.common.navbar')
    @php
        $activeSliders = $sliders->where('status', 1);
    @endphp
    @if ($activeSliders->count() > 0)
        <section class="main-banner position-relative">
            <div class="swiper edm-swiper" data-slides-per-view="1" data-button-next="edm-swiper-button-next-slmain"
                data-button-prev="edm-swiper-button-prev-slmain" data-loop="true">
                <div class="swiper-wrapper">
                    @foreach ($activeSliders as $slider)
                        <div class="swiper-slide main-banner__slide d-flex align-items-center position-relative"
                            style="background-image: url('{{ asset('storage/' . $slider->image) }}');">
                            <div class="inner--banner">
                                <div class="c-heading text-center">
                                    <div class="c-heading__middle">
                                        <h1 class="heading-3">
                                            {{ $slider->title }}
                                        </h1>
                                    </div>
                                    <div class="c-heading__bottom">
                                        <div class="c-heading__large-desc">
                                            <span>{{ $slider->subtitle }}</span>
                                        </div>
                                        <div class="promo-offer">
                                            @if($slider->regular_price || $slider->sale_price)
                                                <div class="promo-offer__prices">
                                                    @if($slider->regular_price && $slider->sale_price)
                                                        <strong class="text-strikethrough">{{ $slider->regular_price }}$</strong>
                                                        <strong>{{ $slider->sale_price }}$</strong>
                                                    @else
                                                        <strong>{{ $slider->regular_price }}$</strong>
                                                    @endif
                                                    <small>/month</small>
                                                </div>
                                            @endif
                                            @if ($slider->date_end)
                                                @if ($slider->date_end < now())
                                                    <div class="promo-offer__start">
                                                        <a href="" class="promo-offer__button js-button-offer" style="pointer-events: none; opacity: 0.7;">
                                                            <span class="font-medium">{{ $slider->button_text }}</span>
                                                            <lord-icon
                                                                src="{{ asset('assets/images/lordicon/ticket.json') }}" trigger="loop"></lord-icon>
                                                        </a>
                                                    </div>
                                                    <span class="promo-offer__timer">Offer Expired</span>
                                                @else    
                                                    <div class="promo-offer__start">
                                                        <a href="{{ $slider->button_link }}" class="promo-offer__button js-button-offer">
                                                            <span class="font-medium">{{ $slider->button_text }}</span>
                                                            <lord-icon
                                                                src="{{ asset('assets/images/lordicon/ticket.json') }}" trigger="loop"></lord-icon>
                                                        </a>
                                                    </div>
                                                    <div class="promo-offer__timer">
                                                        <span class="text-ico">
                                                            <span class="iconify" data-icon="mi:clock" data-width="22" data-height="22"></span>
                                                            This offer ends in
                                                            <span class="js-offer-countdown" data-date-end="{{ $slider->date_end }}"></span>
                                                        </span>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="promo-offer__start">
                                                    <a href="{{ $slider->button_link }}" class="promo-offer__button js-button-offer">
                                                        <span class="font-medium">{{ $slider->button_text }}</span>
                                                        <lord-icon
                                                            src="{{ asset('assets/images/lordicon/ticket.json') }}" trigger="loop">
                                                        </lord-icon>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="swiper-button-next swiper-nextBtn-style-02 edm-swiper-button-next-slmain transition-all"></div>
            <div class="swiper-button-prev swiper-preBtn-style-02 edm-swiper-button-prev-slmain transition-all"></div>

        </section>
    @endif

    <section id="content">
        <div class="block1a">
            <div class="inner">
                <div class="c-heading1">
                    <div class="text-center">
                        <div class="c-heading__middle">
                            <h1 class="heading-3 text-uppercase">Academy</h1>
                        </div>
                        <div class="c-heading__bottom">
                            <div class="c-heading__short-desc">
                                Shape your future in digital design and creative coding.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="inner" data-controller="grid">
            <div class="l-filters">
                <nav class="nav-filters">
                    <div class="nav-filters__overlay" data-action="click->grid#closeFilters"></div>
                    <div class="nav-filters__container" id="nav-filters__container">
                        <div class="nav-filters__left">
                            <ul class="nav-filters__list">
                                <li>
                                    <span class="nav-filters__item js-nav-filters-item"
                                        data-action="click->grid#toggleFilter" data-grid-target="filterItem">
                                        <span>Category</span>
                                    </span>
                                    <div class="nav-filters__dropdown" data-controller="searchable-filter">
                                        <div data-searchable-filter-target="filters">
                                            <ul class="nav-filters__sublist js-filter-section"
                                                data-searchable-filter-target="filters">
                                                @foreach($categories as $category)
                                                    <li class="js-filter-item">
                                                        <a class="nav-filters__subitem "
                                                            href="{{ route('home.index', array_merge(request()->query(), ['category' => $category->id])) }}">
                                                            <span class="js-filter-item-name">{{ $category->name }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <span class="nav-filters__item js-nav-filters-item"
                                        data-action="click->grid#toggleFilter" data-grid-target="filterItem">
                                        <span>Level</span>
                                    </span>
                                    <div class="nav-filters__dropdown" data-controller="searchable-filter">
                                        <div data-searchable-filter-target="filters">
                                            <ul class="nav-filters__sublist js-filter-section"
                                                data-searchable-filter-target="filters">
                                                @foreach($levels as $level)
                                                    <li class="js-filter-item">
                                                        <a class="nav-filters__subitem "
                                                            href="{{ route('home.index', array_merge(request()->query(), ['level' => $level->id])) }}">
                                                            <span class="js-filter-item-name">{{ $level->name }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </div>
                        <div class="nav-filters__right hidden-md">
                            <ul class="nav-filters__list">
                                <li>
                                    <span class="nav-filters__item" data-action="click->grid#toggleFilter"
                                        data-grid-target="filterItem">
                                        <span class="nav-filters__count">{{ count($activeFilters) }}</span>
                                    </span>
                                    <div class="nav-filters__dropdown">
                                        <ul class="nav-filters__sublist">
                                            @forelse($activeFilters as $key => $value)
                                                <li>
                                                    <a class="nav-filters__subitem is-active"
                                                    href="{{ route('home.index', array_merge(request()->except($key), [])) }}">
                                                        <span>{{ $value }}</span>
                                                    </a>
                                                </li>
                                            @empty
                                                <li>
                                                    <span style="margin-left: 10px;">No filter</span>
                                                </li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </li>
                                <li>
                                    <a href="{{ route('home.index') }}" class="nav-filters__item">
                                        <span style="margin-right: 5px;">Reset filters</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            width="20" height="20">
                                        <polyline points="1 4 1 10 7 10"></polyline>
                                        <polyline points="23 20 23 14 17 14"></polyline>
                                        <path d="M20.49 9A9 9 0 005 5.48L1 10"></path>
                                        <path d="M3.51 15A9 9 0 0019 18.52L23 14"></path>
                                        </svg>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <div class="block1b">
            <div class="inner">
                <div data-controller="infinite-scroll">
                    @if($courses->count() === 0)
                        <h2 class="text-muted text-center mt-5">
                            Currently, there are no courses.
                        </h2>
                    @endif
                    <ul id="courses-container" class="grid-academy grid-academy--s2 js-ajax-entries" data-infinite-scroll-target="entries">
                        @include('home.partials.course-cards', ['courses' => $courses])
                    </ul>
                    <div id="load-more-trigger"></div> <!-- div dummy -->

                    <div class="js-ajax-pagination js-infinite-pagination" data-infinite-scroll-target="pagination">
                        
                        <div class="c-load-more" id="load-more-container">
                            <a href="javascript:;" id="load-more-btn" class="button button--medium--rounded--extra-pad pagination__next">
                                <p class="button__loading-wrapper">
                                    <span class="button__text">Loading...</span>
                                    <p class="icon-spinner"><span class="button__spinner"></span></p>
                                </p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="main-why pt-80px pb-80px">
        <div class="container">
            <h4 class="fw-bolder text-center mb-5px">Why Choose Us?</h4>
            <p class="main-why__brief text-center mb-40px">A choice that makes the difference.</p>
            <div class="row gy-30px">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="main-why__items p-50px text-center wow fadeInLeft" data-wow-duration="1.5s">
                        <img src="{{ asset('assets/images/home/main/why-choose-01.png') }}" alt=""
                            class="mb-30px">
                        <h4 class="fs-18 text-capitalize fw-semibold mb-15px">Highly Experienced</h4>
                        <p class="main-why__brief text-center">Our team brings years of expertise, 
                        delivering reliable solutions and exceptional results for every client.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="main-why__items p-50px text-center wow fadeInUp" data-wow-duration="1.5s">
                        <img src="{{ asset('assets/images/home/main/why-choose-02.png') }}" alt=""
                            class="mb-30px">
                        <h4 class="fs-18 text-capitalize fw-semibold mb-15px">Question, Quiz & Course</h4>
                        <p class="main-why__brief text-center">Engage with interactive quizzes and 
                        comprehensive courses designed to boost your knowledge and skills.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="main-why__items p-50px text-center wow fadeInRight" data-wow-duration="1.5s">
                        <img src="{{ asset('assets/images/home/main/why-choose-03.png') }}" alt=""
                            class="mb-30px">
                        <h4 class="fs-18 text-capitalize fw-semibold mb-15px">Dedicated Support</h4>
                        <p class="main-why__brief text-center">Our support team is always ready to assist 
                        you, ensuring a smooth, hassle-free experience.</p>
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

    @include('home.auth.login')
    @include('home.auth.register')
    @include('home.common.script')
    
    {{-- Custom countdown script --}}
    {{-- <script src="{{ asset('assets/js/common/custom-countdown.js') }}"></script> --}}
</body>

</html>
