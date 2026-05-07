<!DOCTYPE html>
<html class="no-js" lang="en">

@include('home.common.header')

<body class="main-layout">
    @include('home.common.navbar')
    <section class="page-banner-title" style="background-image: url('{{ asset('assets/images/home/main/page-banner.jpg') }}')">
        <div class="container pt-80px pb-70px">
            <h1 class="page-banner-title_page fs-40 fw-bolder text-white">Courses</h1>
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
                            <a href="{{ route('courses.index') }}" class="title-bar__nav_before transition-all pointer-events-none">
                                Courses </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>

    <section id="content">
    <div class="inner" data-controller="grid">
            <div class="l-filters">
                <nav class="nav-filters">
                    <div class="nav-filters__overlay" data-action="click->grid#closeFilters"></div>
                    <div class="nav-filters__container">
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
                                                            href="{{ route('courses.index', array_merge(request()->query(), ['category' => $category->id])) }}">
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
                                                            href="{{ route('courses.index', array_merge(request()->query(), ['level' => $level->id])) }}">
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
                                                    href="{{ route('courses.index', array_merge(request()->except($key), [])) }}">
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
                                    <a href="{{ route('courses.index') }}" class="nav-filters__item">
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
