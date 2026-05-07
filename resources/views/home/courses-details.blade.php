<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    @include('home.common.header')
</head>
<body class="main-layout">
    @include('home.common.navbar')
    <section class="page-banner-title"
        style="background-image: url('{{ asset('assets/images/home/main/page-banner.jpg') }}')">
        <div class="container pt-80px pb-70px">
            <h1 class="page-banner-title_page fs-40 fw-bolder text-white">Teaching Online</h1>
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
                            <a href="{{ route('courses.index') }}" class="title-bar__nav_before transition-all">Courses</a>
                        </li>
                        <li class="title-bar__nav_items d-inline">
                            <a href="#"
                                class="title-bar__nav_before transition-all">{{ $course->category->name ??  'N/A'}}</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>

    <section class="courses-details pt-60px pb-80px">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-9 mb-30px mb-lg-0">
                    <div class="courses-details__inner">
                        <h1 class="fs-30 fw-semibold mb-30px">{{ $course->title }}</h1>
                        <div class="courses-details__inner_wrap d-flex flex-wrap justify-content-between mb-30px">
                            <ul class="courses-details__inner_top d-flex flex-wrap gy-20px">
                                <div class="d-flex flex-wrap align-items-center w-100 gap-3">
                                    <li class="courses-details__inner_author d-flex align-items-center mb-2">
                                        @if ($course->user->avatar)
                                            <img src="{{ asset('storage/' . $course->user->avatar) }}" alt="">
                                        @else
                                            <img src="{{ asset('assets/images/common/user_placeholder.png') }}"
                                                alt="">
                                        @endif
                                        <div class="ms-2">
                                            <span class="fs-12 fw-semibold">Teacher</span>
                                            <p class="fs-15 mb-0">
                                                <p>{{ $course->user->name ?? 'No Instructor' }}</p>
                                            </p>
                                        </div>
                                    </li>

                                    <li class="courses-details__inner_category">
                                        <span class="fs-12 fw-semibold d-block">Categories</span>
                                        <p class="fs-15 mb-0">
                                            <a href="courses-list.html" title="{{ $course->category->name ?? 'N/A' }}"
                                                style="display: inline-block; max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                {{ $course->category->name ?? 'N/A' }}
                                            </a>

                                        </p>
                                    </li>
                                    @php
                                        $progressPercent =
                                            $course->lessons_count > 0
                                                ? round(($completedCount / $course->lessons_count) * 100, 0)
                                                : 0;
                                    @endphp
                                    @if ($isEnrolled)
                                        <li class="courses-details__inner_prc">
                                            <span class="fs-12 fw-semibold d-flex align-items-center gap-10px mb-5px">
                                                <span>Course results</span>
                                                <span>{{ $progressPercent }}%</span>
                                            </span>

                                            <span class="d-block progress-value">
                                                <span class="progress-value-level"
                                                    data-value-level="{{ $progressPercent }}%"></span>
                                                <span class="lp-passing-conditional position-absolute top-0 bg-dark"
                                                    data-bs-toggle="tooltip"
                                                    title="Require 80% completed lessons per the total number of lessons."
                                                    style="left: 80%; width: 2px; height: 12px; transform: translateY(-2px);">
                                                </span>
                                            </span>
                                            <span class="fs-12 d-block progress-graduation">In Progress</span>
                                        </li>
                                    @endif
                                </div>
                            </ul>

                            @if ($isEnrolled)
                                <div class="d-flex gap-10px">
                                    @if ($course->lessons->isEmpty())
                                        <button type="button"
                                            class="button-type-02 text-uppercase fw-medium transition-all bg-secondary text-white"
                                            disabled>
                                            No Lessons Available
                                        </button>
                                    @else
                                        @if ($progressPercent < 100 && $nextLessonToContinue && !$isCourseCompleted)
                                            <form
                                                action="{{ route('lessons.show', ['course' => $course->id, 'lesson' => $nextLessonToContinue->id]) }}"
                                                method="GET">
                                                <button
                                                    class="button-type-02 text-uppercase fw-medium transition-all courses-finish">
                                                    Continue
                                                </button>
                                            </form>
                                        @endif

                                        @if ($progressPercent >= 80 && !$isCourseCompleted)
                                            <form id="finishForm"
                                                action="{{ route('courses.finish', ['course' => $course]) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="action-button button-type-02 text-uppercase fw-medium transition-all courses-finish"
                                                    data-form="finishForm" data-title="Are you sure?"
                                                    data-html="Do you want to finish this course?<br>This action cannot be undone"
                                                    data-confirm="Yes, finish it!">
                                                    Finish Course
                                                </button>
                                            </form>
                                        @endif

                                        @if ($isCourseCompleted)
                                            <form id="retakeForm"
                                                action="{{ route('courses.retake', ['course' => $course]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="action-button button-type-02 text-uppercase fw-medium transition-all courses-retake"
                                                    data-form="retakeForm" data-title="Are you sure?"
                                                    data-html="Do you want to retake this course?<br>This action cannot be undone"
                                                    data-confirm="Yes!">
                                                    Retake Course
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            @else
                                <div class="gap-10px">
                                    @auth
                                        @if (($course->sale_price !== null && $course->sale_price == 0) || $course->regular_price == 0 || $isOwnerOrAdmin)
                                            <form id="enrollForm" action="{{ route('courses.enroll-free', $course->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="action-button button-type-02 text-uppercase fw-medium transition-all courses-Buy"
                                                    data-form="enrollForm" data-title="Are you sure?"
                                                    data-html="Do you want to start this course?<br>This action cannot be undone"
                                                    data-confirm="Yes!">
                                                    Start
                                                </button>
                                            </form>
                                        @else
                                            <form id="enrollForm" action="{{ route('orders.create', ['type' => 'courses','id' => $course->id]) }}"
                                                method="GET">
                                                <a href="{{ route('orders.create', ['type' => 'courses','id' => $course->id]) }}"
                                                    class="button-type-02 text-uppercase fw-medium transition-all courses-Buy">
                                                    Buy Now
                                                </a>
                                            </form>
                                        @endif
                                    @endauth
                                    @guest
                                        <button
                                            class="handle-login button-type-02 text-uppercase fw-medium transition-all courses-Buy">
                                            Buy Now
                                        </button>
                                    @endguest
                                </div>
                            @endif
                        </div>

                        @if (filled($videoURL))
                            <div class="courses-details__video mb-30px">
                                <video width="100%" height="auto" style="border: 2px solid #222;" controls loop
                                    muted playsinline>
                                    <source src="{{ asset($videoURL) }}" type="video/mp4">
                                </video>
                            </div>
                        @endif
                        <div class="courses-details__content mb-30px">
                            <div class="courses-details__tab d-flex align-items-center">
                                <button
                                    class="courses-details__tab_btn d-flex align-items-center justify-content-center gap-5px active"
                                    data-tab="description">
                                    <span><i class="iconify fs-15 courses-details__tab_icon"
                                            data-icon="ic:baseline-bookmark"></i></span>
                                    <span class="d-none d-md-block">Overview</span>
                                </button>
                                <button
                                    class="courses-details__tab_btn d-flex align-items-center justify-content-center gap-5px"
                                    data-tab="curriculum">
                                    <span><i class="iconify fs-15 courses-details__tab_icon"
                                            data-icon="fa6-solid:bars"></i></span>
                                    <span class="d-none d-md-block">Curriculum</span>
                                </button>
                            </div>
                            <div class="courses-details__tab_content active" id="description">
                                <div class="d-flex flex-wrap">
                                    <div class="courses-details__description">
                                        <h6 class="text-uppercase fw-semibold mb-20px">Courses Description</h6>
                                        <div class="mb-20px">
                                            {!! $course->description !!}
                                        </div>
                                    </div>
                                    <div class="courses-details__features">
                                        <h6 class="fs-16 text-uppercase fw-semibold mb-20px">Courses Features
                                        </h6>
                                        <ul class="courses-details__features_list">
                                            <li
                                                class="courses-details__features_items d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-10px">
                                                    <i class="iconify fs-18 features-icon" data-icon="bi:book"></i>
                                                    <span>Lectures</span>
                                                </div>
                                                <span class="fs-14 fw-semibold">{{ $course->lessons_count }}</span>
                                            </li>
                                            <li
                                                class="courses-details__features_items d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-10px">
                                                    <i class="iconify fs-18 features-icon"
                                                        data-icon="lets-icons:time"></i>
                                                    <span>Duration</span>
                                                </div>
                                                <span class="fs-14 fw-semibold">{{ $course->duration }} Month</span>
                                            </li>
                                            <li
                                                class="courses-details__features_items d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-10px">
                                                    <i class="iconify fs-18 features-icon"
                                                        data-icon="cil:level-up"></i>
                                                    <span>Skill level</span>
                                                </div>
                                                <span class="fs-14 fw-semibold">{{ $course->level->name }}</span>
                                            </li>
                                            <li
                                                class="courses-details__features_items d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-10px">
                                                    <i class="iconify fs-18 features-icon"
                                                        data-icon="cil:language"></i>
                                                    <span>Language</span>
                                                </div>
                                                <span class="fs-14 fw-semibold">{{ $course->language }}</span>
                                            </li>
                                            <li
                                                class="courses-details__features_items d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-10px">
                                                    <i class="iconify fs-18 features-icon" data-icon="ph:student"></i>
                                                    <span>Students</span>
                                                </div>
                                                <span class="fs-14 fw-semibold">{{ $enrolledUserCount }}</span>
                                            </li>
                                            {{-- TO DO --}}
                                            {{-- <li
                                                class="courses-details__features_items d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-10px">
                                                    <i class="iconify fs-18 features-icon"
                                                        data-icon="ic:outline-class"></i>
                                                    <span>Assessments</span>
                                                </div>
                                                <span class="fs-14 fw-semibold">XXX</span>
                                            </li> --}}
                                            {{-- TO DO --}}
                                            {{-- <button type="button"
                                                class="button-type-02 transition-all text-uppercase w-100">
                                                <i class="iconify fs-18 heart-icon" data-icon="mdi:heart"></i>
                                                <span>Add to wishlist</span>
                                            </button> --}}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="courses-details__tab_content" id="curriculum">
                                <div class="courses-details__curriculum">
                                    <ul class="curriculum-section">
                                        @forelse ($chapters as $chapter)
                                            <li class="curriculum-section__items"
                                                id="curriculum-section-{{ $chapter->id }}"
                                                data-id="{{ $chapter->id }}">
                                                <div
                                                    class="curriculum-section__header d-flex align-items-center gap-20px justify-content-between">
                                                    <i class="iconify fs-20 down-icon cursor-pointer"
                                                        data-icon="ep:arrow-down-bold"></i>
                                                    <h6
                                                        class="curriculum-section__header_title fw-semibold cursor-pointer">
                                                        {{ $chapter->title }}
                                                    </h6>
                                                    <span
                                                        class="d-block fw-semibold curriculum-section__header_quantity">{{ $chapter->lessons_count }}</span>
                                                </div>
                                                <ul class="curriculum-section__content">
                                                    @forelse ($chapter->lessons as $lesson)
                                                        <li
                                                            class="curriculum-section__content_items d-flex flex-wrap gap-20px justify-content-between">
                                                            @php
                                                                $isLessonOpen = $isEnrolled || $lesson->status === 0 || $isOwnerOrAdmin;
                                                                $isLessonCompleted = in_array(
                                                                    $lesson->id,
                                                                    $completedLessons ?? [],
                                                                );
                                                            @endphp
                                                            @if ($isLessonOpen)
                                                                <a href="{{ route('lessons.show', ['course' => $course->id, 'lesson' => $lesson->id]) }}"
                                                                    class="d-flex gap-20px align-items-center">
                                                                    <i class="iconify fs-20 curriculum-section__content_icon"
                                                                        data-icon="la:file-invoice"></i>
                                                                    <p class="curriculum-section__content_title fs-16">
                                                                        {{ $lesson->title }}
                                                                    </p>
                                                                </a>
                                                            @else
                                                                <div class="lesson-title d-flex gap-20px align-items-center disabled text-muted"
                                                                    style="pointer-events: none;">
                                                                    <i class="iconify fs-20 curriculum-sidebar__content_icon"
                                                                        data-icon="ph:file"></i>
                                                                    <p class="curriculum-sidebar__content_title">
                                                                        {{ $lesson->title }}
                                                                    </p>
                                                                </div>
                                                            @endif
                                                            <div
                                                                class="curriculum-section__content_info d-flex align-items-center gap-10px">
                                                                <span class="fs-14">{{ $lesson->duration }}
                                                                    minutes</span>
                                                                <a href="#" onclick="return false;">
                                                                    @if ($isEnrolled && $isLessonCompleted)
                                                                        <i class="iconify fs-20 text-success"
                                                                            data-icon="hugeicons:checkmark-badge-01"></i>
                                                                    @elseif ($isLessonOpen)
                                                                        <i class="iconify fs-20 eye_icon"
                                                                            data-icon="lucide:eye"></i>
                                                                    @else
                                                                        <i class="iconify fs-20 lock_icon"
                                                                            data-icon="ph:lock-simple-light"></i>
                                                                    @endif
                                                                </a>
                                                            </div>
                                                        </li>
                                                    @empty
                                                        <li class="text-muted px-3 py-2">No lessons available</li>
                                                    @endforelse
                                                </ul>
                                            </li>
                                        @empty
                                            <li class="text-muted px-3 py-2">No lessons available</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                            <div class="courses-details__tab_content" id="instructor">
                                <div class="courses-details__instructor border-radius-10px p-30px">
                                    <div class="courses-details__instructor_box d-flex gap-30px">
                                        @if ($course->user->avatar)
                                            <p><img
                                                src="{{ asset('storage/' . $course->user->avatar) }}"
                                                alt="" class="mb-2 d-sm-0"></p>
                                        @else
                                            <p><img
                                                src="{{ asset('assets/images/common/user_placeholder.png') }}"
                                                alt="" class="mb-2 d-sm-0"></p>
                                        @endif
                                        <div class="courses-details__instructor_wrapper">
                                            <a href="instructors-details.html">
                                                <h5 class="fw-semibold fs-14 mb-5px">
                                                    {{ $course->user->name ?? 'No Instructor' }}
                                                </h5>
                                            </a>
                                            <span class="d-block fs-14 mb-10px">Professor</span>
                                            <p class="fs-14">Lorem ipsum dolor sit amet. Qui
                                                incidunt dolores non
                                                similique ducimus et
                                                debitis molestiae. Et autem quia eum
                                                reprehenderit
                                                voluptates est
                                                reprehenderit illo est enim perferendis est
                                                neque
                                                sunt. Nam amet sunt
                                                aut vero mollitia ut ipsum corporis vel facere
                                                eius
                                                et quia aspernatur
                                                qui fugiat repudiandae. Et officiis inventore et
                                                quis enim ut quaerat
                                                corporis sed reprehenderit odit sit saepe
                                                distinctio
                                                et accusantium
                                                repellendus ea enim harum.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="courses-details__tab_content" id="review">
                                <div class="courses-details__review">
                                    <h6 class="fs-16 fw-bold text-uppercase text-center text-sm-start mb-20px">Reviews
                                    </h6>
                                    <div class="d-md-flex">
                                        <div class="courses-details__review_average">
                                            <p class="mb-10px text-center text-sm-start">Average Rating</p>
                                            <div class="courses-details__review_result1 text-center">
                                                <h4 class="fw-medium mb-30px">5</h4>
                                                <div class="result1-star">
                                                    <i class="iconify fs-18 star-color" data-icon="ph:star-fill"></i>
                                                    <i class="iconify fs-18 star-color" data-icon="ph:star-fill"></i>
                                                    <i class="iconify fs-18 star-color" data-icon="ph:star-fill"></i>
                                                    <i class="iconify fs-18 star-color" data-icon="ph:star-fill"></i>
                                                    <i class="iconify fs-18 star-color" data-icon="ph:star-fill"></i>
                                                </div>
                                                <p class="result1-amount">1 rating</p>
                                            </div>
                                        </div>
                                        <div class="courses-details__review_detailed">
                                            <p class="mb-10px text-center text-sm-start">Detailed Rating</p>
                                            <div class="courses-details__review_result2">
                                                <div class="d-flex align-items-center mb-10px">
                                                    <p class="result2-amount fw-semibold">5</p>
                                                    <div class="result2-bar">
                                                        <div class="result2-bar__check"></div>
                                                    </div>
                                                    <p class="result2-ratio">100%</p>
                                                </div>
                                                <div class="d-flex align-items-center mb-10px">
                                                    <p class="result2-amount fw-semibold">4</p>
                                                    <div class="result2-bar"></div>
                                                    <p class="result2-ratio">0%</p>
                                                </div>
                                                <div class="d-flex align-items-center mb-10px">
                                                    <p class="result2-amount fw-semibold">3</p>
                                                    <div class="result2-bar"></div>
                                                    <p class="result2-ratio">0%</p>
                                                </div>
                                                <div class="d-flex align-items-center mb-10px">
                                                    <p class="result2-amount fw-semibold">2</p>
                                                    <div class="result2-bar"></div>
                                                    <p class="result2-ratio">0%</p>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <p class="result2-amount fw-semibold">1</p>
                                                    <div class="result2-bar"></div>
                                                    <p class="result2-ratio">0%</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button
                                        class="courses-details__review_write write-a-review button-type-02 transition-all mt-60px mb-30px">Write
                                        A Review</button>
                                    <div class="write-a-review_wrapper">
                                        <div class="write-a-review_popup p-20px">
                                            <div class="d-flex align-items-center justify-content-between mb-20px">
                                                <h6 class="fw-semibold">Write A Review</h6>
                                                <div class="write-a-review_close">
                                                    <i class="iconify fs-22 cursor-pointer"
                                                        data-icon="material-symbols:close"></i>
                                                </div>
                                            </div>
                                            <form action="#" method="post" class="write-a-review_form">
                                                <div class="form-group mb-15px">
                                                    <label for="title" class="d-block fw-semibold mb-5px">Title
                                                        <span class="write-a-review_rq fw-semibold">*</span>
                                                    </label>
                                                    <input type="text" id="title" name="title"
                                                        class="" required>
                                                </div>
                                                <div class="form-group mb-15px">
                                                    <label for="content" class="d-block fw-semibold mb-5px">Content
                                                        <span class="write-a-review_rq fw-semibold">*</span>
                                                    </label>
                                                    <textarea id="content" name="content" rows="3" class="" required></textarea>
                                                </div>
                                                <div class="form-group mb-15px">
                                                    <label for="content" class="d-block fw-semibold mb-5px">Rating
                                                        <span class="write-a-review_rq fw-semibold">*</span>
                                                    </label>
                                                    <label class="d-flex align-items-center gap-5px mb-10px"
                                                        id="your-rating">
                                                        <span class="star-wrapper" data-index="1">
                                                            <i class="iconify star cursor-pointer"
                                                                data-icon="iconoir:star-solid"></i>
                                                        </span>
                                                        <span class="star-wrapper" data-index="2">
                                                            <i class="iconify star cursor-pointer"
                                                                data-icon="iconoir:star-solid"></i>
                                                        </span>
                                                        <span class="star-wrapper" data-index="3">
                                                            <i class="iconify star cursor-pointer"
                                                                data-icon="iconoir:star-solid"></i>
                                                        </span>
                                                        <span class="star-wrapper" data-index="4">
                                                            <i class="iconify star cursor-pointer"
                                                                data-icon="iconoir:star-solid"></i>
                                                        </span>
                                                        <span class="star-wrapper" data-index="5">
                                                            <i class="iconify star cursor-pointer"
                                                                data-icon="iconoir:star-solid"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="d-flex align-items-center gap-15px">
                                                    <button type="submit"
                                                        class="write-a-review_add button-type-02 transition-all fw-semibold">Add
                                                        Review</button>
                                                    <button type="submit"
                                                        class="write-a-review_cancel fw-semibold transition-all">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="courses-details__yml mb-40px">
                            <h5 class="fw-semibold text-uppercase mb-30px">You May Like</h5>
                            <div class="position-relative swiper-style-2">
                                <div class="swiper edm-swiper" data-slides-per-view="1" data-space-between="30"
                                    data-pagination="edm-data-pagination-ymlmain" data-loop="false"
                                    data-centered-slides="true"
                                    data-breakpoints='{"1024": {"slidesPerView": 3, "spaceBetween": 30}, "768": {"slidesPerView": 2, "spaceBetween": 30}, "576": {"slidesPerView": 2, "spaceBetween": 30}}'>
                                    <div class="swiper-wrapper">
                                        @foreach ($youMayLike as $course)
                                            <div class="swiper-slide">
                                                <div class="card-academy">
                                                    <figure class="card-academy__figure">
                                                        <a href="{{ route('courses.show', ['id' => $course->id]) }}" aria-label="Learn UI Design with Figma from Scratch">
                                                            <img src="{{ asset('storage/' . $course->image) }}" srcset="{{ asset('storage/' . $course->image) }} 1x,
                                                                    {{ asset('storage/' . $course->image) }} 2x" alt="{{ $course->title }}" class="card-academy__media" loading="lazy">
                                                        </a>
                                                    </figure>
                                                    <div class="card-academy__info">
                                                        <div class="card-academy__header">
                                                            <div class="card-academy__row1">
                                                                <h3 class="card-academy__title" title="{{ $course->title }}"><a href="{{ route('courses.show', ['id' => $course->id]) }}">{{ $course->title }}</a></h3>
                                                            </div>
                                                        </div>
                                                        <div class="card-academy__footer">
                                                            <div class="card-academy__row1">
                                                                <div class="card-academy__by">
                                                                    <small>By</small>
                                                                    <strong title="{{ $course->user->name }}">{{ $course->user->name }}</strong>
                                                                </div>
                                                                <div class="box-price">
                                                                    @if ($course->sale_price > 0)
                                                                        <div class="box-price__off">
                                                                            <span class="text-strikethrough1 box-price__old">${{ number_format($course->regular_price, 2, '.', ',') }}</span>
                                                                        </div>

                                                                        <div class="box-price__total">
                                                                            <strong>{{ number_format($course->sale_price, 2, '.', ',') }}</strong><sup>USD</sup>
                                                                        </div>
                                                                        @elseif ($course->sale_price === 0)
                                                                        <div class="box-price__total">
                                                                            <strong>Free</strong>
                                                                        </div>
                                                                        @else 
                                                                        <div class="box-price__total">
                                                                            <strong>{{ number_format($course->regular_price, 2, '.', ',') }}</strong><sup>USD</sup>
                                                                        </div>
                                                                        @endif
                                                                </div>
                                                            </div>
                                                            <div class="card-academy__row1">
                                                                <div class="card-academy__subrow">
                                                                    <div class="box-score">
                                                                        <div class="box-score__info" style="text-align: left">
                                                                            <div class="d-flex align-items-center gap-10px">
                                                                                <span class="courses-layout-1__lesson d-flex align-items-center gap-5px fw-bold">
                                                                                    <i class="iconify fs-16" data-icon="majesticons:list-box-line"></i>
                                                                                    <span>{{ $course->lessons_count }}</span>
                                                                                </span>
                                                                                <span class="courses-layout-1__student d-flex align-items-center gap-5px fw-bold">
                                                                                    <i class="iconify fs-20" data-icon="fluent:people-48-regular"></i>
                                                                                    <span>{{ $course->enrolled_users_count }}</span>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="arror-icon-div">
                                                                    <a href="{{ route('courses.show', ['id' => $course->id]) }}" aria-label="View Course">
                                                                        <lord-icon src="{{ asset('assets/images/lordicon/arrow-right.json') }}" trigger="hover"></lord-icon>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="swiper-pagination edm-data-pagination-ymlmain transition-all"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <aside class="col-12 col-lg-3">
                    <div class="courses-details__sidebar">
                        <div class="courses-page__courses-cate mb-30px">
                            <h6 class="fs-18 fw-semibold text-uppercase mb-15px">All Course</h6>
                            @foreach ($categories as $category)
                                <ul class="">
                                    <li class="d-flex align-items-center gap-10px justify-content-between mb-10px">
                                        <p class="transition-all">{{ $category->name }}</p>
                                        <span class="">{{ $category->courses_count }}</span>
                                    </li>
                            @endforeach
                            </ul>
                        </div>
                        <div class="courses-page__latest mb-30px">
                            <h6 class="fs-18 text-uppercase fw-semibold mb-20px">Latest Courses</h6>
                            @foreach ($latestCourses as $course)
                                <div class="latest-courses d-flex gap-20px mb-20px">
                                    <div class="latest-courses_thumb text-center">
                                        <a href="{{ route('courses.show', ['id' => $course->id]) }}">
                                            @if ($course->image)
                                                <img src="{{ asset('storage/' . $course->image) }} " alt="">
                                            @else
                                                <img src="{{ asset('assets/images/common/course_placeholder.png') }}"
                                                    alt="Course Image" class="img-courses">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="latest-courses_content">
                                        <h6 class="fs-15 fw-semibold line-clamp-2 mb-10px">

                                            <a href="{{ route('courses.show', ['id' => $course->id]) }}"
                                                title = "{{ $course->title }}" class="transition-all fw-semibold line-clamp-1">
                                                {{ $course->title }}
                                            </a>
                                        </h6>
                                        @if (!empty($course->sale_price))
                                            <p class="latest-courses_price fw-bolder price-status">
                                                ${{ round($course->sale_price, 2) }}
                                            </p>
                                        @else
                                            @if ($course->regular_price > 0)
                                                <p class="latest-courses_price fw-bolder price-status">
                                                    ${{ round($course->regular_price, 2) }}
                                                </p>
                                            @else
                                                <p class="latest-courses_price fw-bolder free-status">
                                                    FREE
                                                </p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
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
