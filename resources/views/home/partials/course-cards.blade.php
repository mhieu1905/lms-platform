@foreach($courses as $course)
<li class="grid-academy__item">
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
                            @elseif (isset($course->sale_price) && $course->sale_price == 0)
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
</li>
@endforeach
