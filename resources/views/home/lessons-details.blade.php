<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    @include('home.common.header')
</head>

<body class="main-layout">
    <div class="my-learning-page my-learning">
        <div class="container-fluid m-0 p-0">
            <div class="my-learning__top d-flex flex-wrap gap-20px justify-content-end ps-30px">
                <div class="d-flex align-items-center gap-15px">
                    @if ($isEnrolled)
                        <span class="text-white d-none d-xxl-block"> {{ $completedCount }} of {{ $totalLessons }} is
                            Completed</span>
                        <span class="d-block progress-value d-none d-xxl-block">
                            <span class="progress-value-level"
                                data-value-level="{{ $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100, 0) : 0 }}%"></span>
                        </span>
                    @endif
                    @php
                        $progressPercent = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100, 0) : 0;
                    @endphp

                    @if ($progressPercent >= 80)
                        <div style="display: flex; justify-content: flex-end;">
                            <form id="finishForm" action="{{ route('courses.finish', ['course' => $course]) }}"
                                method="POST" class="ms-auto">
                                @csrf
                                <button type="submit"
                                    class="action-button button-type-02 text-uppercase fw-medium transition-all courses-finish"
                                    data-form="finishForm" data-title="Are you sure?"
                                    data-html="Do you want to finish this course?<br>This action cannot be undone"
                                    data-confirm="Yes, finish it!">
                                    Finish Course
                                </button>
                            </form>
                        </div>
                    @endif
                    <div class="my-learning__toggleSidebar">
                        <i class="iconify fs-26 text-white toggleSidebar-icon" data-icon="ph:corners-out-bold"></i>
                    </div>
                    <a href="{{ route('courses.show', ['id' => $lesson->chapter->course->id]) }}"
                        class="my-learning__top_return d-flex align-items-center gap-20px transition-all">
                        <i class="iconify fs-26 text-white" data-icon="mingcute:close-fill"></i>
                    </a>
                </div>
            </div>
            <div class="my-learning__content d-flex">
                <div class="my-learning__content_box">
                    <div class="my-learning__content_sidebar">
                        <ul class="curriculum-sidebar">
                            @foreach ($chapters as $chapter)
                                <li class="curriculum-sidebar__items" id="curriculum-section-{{ $chapter->id }}"
                                    data-id="{{ $chapter->id }}">
                                    <div
                                        class="curriculum-sidebar__header d-flex align-items-center gap-20px justify-content-between cursor-pointer">
                                        <i class="iconify fs-16 down-icon" data-icon="ep:arrow-down-bold"></i>
                                        <h6 class="curriculum-sidebar__header_title text-uppercase fs-16 fw-semibold">
                                            {{ $chapter->title }}
                                        </h6>
                                        <span
                                            class="d-block fw-semibold curriculum-sidebar__header_quantity">{{ $chapter->lessons_count }}</span>
                                    </div>

                                    <ul class="curriculum-sidebar__content">
                                        @foreach ($chapter->lessons as $itemLesson)
                                            <li
                                                class="curriculum-sidebar__content_items d-flex flex-wrap gap-10px justify-content-between">
                                                @php
                                                    $isLessonOpen = $isEnrolled || $itemLesson->status === 0 || $isOwnerOrAdmin;
                                                    $isLessonInChapCompleted = in_array(
                                                        $itemLesson->id,
                                                        $completedLessons ?? [],
                                                    );
                                                @endphp
                                                @if ($isLessonOpen)
                                                    <a href="{{ route('lessons.show', ['course' => $itemLesson->chapter->course_id, 'lesson' => $itemLesson->id]) }}"
                                                        class="lesson-title d-flex gap-20px align-items-center {{ $itemLesson->id === $currentLessonId ? 'active' : '' }}"
                                                        data-lesson-type="lesson-text">
                                                        <i class="iconify fs-20 curriculum-sidebar__content_icon"
                                                            data-icon="ph:file"></i>
                                                        <p class="curriculum-sidebar__content_title">
                                                            {{ $itemLesson->title }}
                                                        </p>
                                                    </a>
                                                @else
                                                    <div class="lesson-title d-flex gap-20px align-items-center disabled text-muted"
                                                        style="pointer-events: none;">
                                                        <i class="iconify fs-20 curriculum-sidebar__content_icon"
                                                            data-icon="ph:file"></i>
                                                        <p class="curriculum-sidebar__content_title">
                                                            {{ $itemLesson->title }}
                                                        </p>
                                                    </div>
                                                @endif

                                                <div
                                                    class="curriculum-sidebar__content_info d-flex align-items-center gap-10px">
                                                    <span class="lesson-time fs-14">{{ $itemLesson->duration }}
                                                        minutes</span>
                                                    <span class="lesson-view">
                                                        @if ($isEnrolled && $isLessonInChapCompleted)
                                                            <i class="iconify fs-20 text-success"
                                                                data-icon="hugeicons:checkmark-badge-01"></i>
                                                        @elseif ($isLessonOpen)
                                                            <i class="iconify fs-20 eye_icon"
                                                                data-icon="lucide:eye"></i>
                                                        @else
                                                            <i class="iconify fs-20 lock_icon"
                                                                data-icon="ph:lock-simple-light"></i>
                                                        @endif
                                                    </span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="my-learning__content_body">
                    <div class="container p-0">
                        <div class="lesson-text data-lesson-type active" data-lesson-type="lesson-text">
                            <div class="lesson-text__wrapper">
                                <h1 class="fs-30 fw-bold mb-20px">{{ $lesson->title }}</h1>
                                @if (filled($videoURL))
                                    <video controls width="100%">
                                        <source src="{{ $videoURL }}" type="video/mp4">
                                    </video>
                                @endif
                                <p class="custom-paragraph">
                                    {!! $lesson->content !!}
                                </p>
                            </div>
                            @php
                                $isLessonCompleted = in_array($lesson->id, $completedLessons ?? []);
                            @endphp
                            <form
                                action="{{ route('lessons.complete', ['lesson' => $lesson->id, 'course' => $lesson->chapter->course_id]) }}"
                                id="completeForm" method="POST">
                                @csrf
                                @if ($isLessonCompleted)
                                    <span class="btn-complete button-type-02 transition-all mt-30px"
                                        style="background: #6c757d">Completed</span>
                                @else
                                    @if ($isEnrolled)
                                        <button type="submit"
                                            class="action-button btn-complete button-type-02 transition-all lesson-complete mt-30px"
                                            data-form="completeForm" data-title="Are you sure?"
                                            data-html="Do you want to finish this course?<br>This action cannot be undone"
                                            data-confirm="Yes, finish it!">
                                            Complete
                                        </button>
                                    @endif
                                @endif
                            </form>
                            <nav
                                class="my-learning-navigation d-flex flex-wrap align-items-center gap-30px justify-content-between mt-30px">
                                @if ($prevLesson)
                                    <a href="{{ route('lessons.show', ['course' => $prevLesson->chapter->course_id, 'lesson' => $prevLesson->id]) }}"
                                        class="my-learning-navigation__items">
                                        <div
                                            class="my-learning-navigation__pre transition-all d-flex align-items-center gap-5px">
                                            <i class="iconify fs-10" data-icon="teenyicons:left-solid"></i>
                                            <p>Prev</p>
                                        </div>
                                    </a>
                                @else
                                    <div class="my-learning-navigation__items opacity-50 cursor-default"
                                        style="pointer-events: none;">
                                        <div class="my-learning-navigation__pre d-flex align-items-center gap-5px">
                                            <i class="iconify fs-10" data-icon="teenyicons:left-solid"></i>
                                            <p>Prev</p>
                                        </div>
                                    </div>
                                @endif
                                @if ($nextLesson)
                                    <a href="{{ route('lessons.show', ['course' => $nextLesson->chapter->course_id, 'lesson' => $nextLesson->id]) }}"
                                        class="my-learning-navigation__items">
                                        <div
                                            class="my-learning-navigation__next transition-all d-flex align-items-center gap-5px">
                                            <p>Next</p>
                                            <i class="iconify fs-10" data-icon="teenyicons:right-solid"></i>
                                        </div>
                                    </a>
                                @else
                                    <div class="my-learning-navigation__items opacity-50 cursor-default"
                                        style="pointer-events: none;">
                                        <div class="my-learning-navigation__next d-flex align-items-center gap-5px">
                                            <p>Next</p>
                                            <i class="iconify fs-10" data-icon="teenyicons:right-solid"></i>
                                        </div>
                                    </div>
                                @endif
                            </nav>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM fully loaded');
    const userId = @json(auth()->id());
    const courseId = @json($course->id ?? null);
    const lessonId = @json($lesson->id ?? null);
    console.log('CourseID:', courseId, 'LessonID:', lessonId);
    const apiUrl = "{{ url('/api/log-activity') }}";

    let startTime = Date.now();
    let lastProgressSent = 0; // Last log progress send

    // Send log to server
    function sendLog(action, duration = null, progress = null) {
        fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                user_id: userId,
                course_id: courseId,
                lesson_id: lessonId,
                action_type: action,
                duration: duration,
                progress_percent: progress,
                device_info: navigator.userAgent,
            }),
        }).catch(error => console.error('Error logging:', error));
    }

    // When user start learning
    sendLog('view');

    // When user hide or close tab
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            const timeSpent = Math.floor((Date.now() - startTime) / 1000);
            sendLog('pause', timeSpent);
        }
    });

    // Track video viewing progress
    const video = document.querySelector('video');
    if (video) {
        // Send log every 30 seconds
        video.addEventListener('timeupdate', () => {
            const now = Date.now();
            const progress = Math.floor((video.currentTime / video.duration) * 100);

            if (now - lastProgressSent >= 30000) { // 30 seconds
                sendLog('progress', null, progress);
                lastProgressSent = now;
            }
        });

        // When video finish
        video.addEventListener('ended', () => {
            sendLog('complete', Math.floor(video.duration), 100);
        });
    }
});
</script>

</body>
@include('home.common.script')
<script>
    new WOW().init();
</script>

</html>
