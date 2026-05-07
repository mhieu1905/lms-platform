<!DOCTYPE html>
<html class="no-js" lang="en">

@include('home.common.header')

<body class="main-layout">
    @include('home.common.navbar')
    <section class="page-banner-title" style="background-image: url('{{ asset('assets/images/home/main/page-banner.jpg') }}')">
        <div class="container pt-80px pb-70px">
            <h1 class="page-banner-title_page fs-40 fw-bolder text-white">Events</h1>
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
                            <a href="{{ route('events.index') }}" class="title-bar__nav_before transition-all">Events</a>
                        </li>
                        <li class="title-bar__nav_items d-inline">
                            <a href="#" class="title-bar__nav_before transition-all pointer-events-none">
                                {{ Str::limit($event->title, 70) }} </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>
    <section class="events-details mt-40px mb-80px">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-9 mb-30px mb-lg-0">
                    <h1 class="fs-30 fw-semibold mb-40px">{{ $event->title }}</h1>
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ generateAlt($event->title) }}" class="mb-30px">
                    <div class="events-details__wrapper d-flex flex-wrap">
                        <div class="events-details__wrapper_content">
                            <h6 class="fw-semibold mb-20px">Event Description</h6>
                            <p class="mb-20px">{!! $event->description !!}</p>
                            <h6 class="fw-semibold mb-20px mt-4">Event Content</h6>
                            @php
                            $contents = safe_json_decode($event->content);
                            @endphp
                            <ul class="events-details__ul mb-50px">
                                @foreach($contents as $content)
                                <li>
                                    {{ $content }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="events-details__wrapper_info">
                            <div class="events-details__wrapper_info_start">
                                <span class="d-flex align-items-center gap-10px">
                                    <i class="iconify fs-20 events-details__wrapper_info_icon" data-icon="lets-icons:time"></i>
                                    <span class="fw-bolder fs-15">Start Time</span>
                                </span>
                                <p class="fs-14">
                                    <time datetime="{{ $event->start_time->format('Y-m-d\TH:i') }}">{{ $event->start_time->format('g:i a') }}</time>
                                </p>
                                <p class="fs-14">
                                    <time datetime="{{ $event->start_time->format('Y-m-d') }}">{{ $event->start_time->format('d/m/Y') }}</time>
                                </p>
                            </div>
                            <div class="events-details__wrapper_info_finish">
                                <span class="d-flex align-items-center gap-10px">
                                    <i class="iconify fs-20 events-details__wrapper_info_icon" data-icon="ic:baseline-flag"></i>
                                    <span class="fw-bolder fs-15">Finish Time</span>
                                </span>
                                <p class="fs-14">
                                    <time datetime="{{ $event->finish_time->format('Y-m-d\TH:i') }}">{{ $event->finish_time->format('g:i a') }}</time>
                                </p>
                                <p class="fs-14">
                                    <time datetime="{{ $event->finish_time->format('Y-m-d') }}">{{ $event->finish_time->format('d/m/Y') }}</time>
                                </p>
                            </div>
                            <div class="events-details__wrapper_info_address">
                                <span class="d-flex align-items-center gap-10px">
                                    <i class="iconify fs-20 events-details__wrapper_info_icon" data-icon="akar-icons:location"></i>
                                    <span class="fw-bolder fs-15">Address</span>
                                </span>
                                <p class="fs-14">{{ $event->address }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @if($canBook)
                <aside class="col-12 col-lg-3">
                    <div class="form-buy-ticket">
                        <h6 class="fw-semibold text-center text-white">Buy Ticket</h6>
                        <form action="{{ route('orders.create', ['type' => 'events', 'id' => $event->id]) }}" method="GET">
                            @csrf
                            <ul>
                                <li class="form-buy-ticket__items d-flex align-items-center justify-content-between fs-15">
                                    <span>Total Slots</span>
                                    <span class="form-buy-ticket__total fw-bolder">{{ $event->total_slots }}</span>
                                </li>
                                <li class="form-buy-ticket__items d-flex align-items-center justify-content-between fs-15">
                                    <span>Booked Slots</span>
                                    <span class="form-buy-ticket__booked fw-bolder">{{ $event->booked_slots }}</span>
                                </li>
                                <li class="form-buy-ticket__items d-flex align-items-center justify-content-between fs-15">
                                    <span>Cost</span>
                                    @if($event->cost == 0)
                                    <span class="form-buy-ticket__cost fw-bolder fs-18">Free</span>
                                    @else
                                    <span class="form-buy-ticket__cost2 fw-bolder fs-18">${{ $event->cost }}/Slot</span>
                                    @endif
                                </li>
                            </ul>
                            @if(!Auth::check())
                            <a href="#" class="form-buy-ticket__btn fs-14 ms-25px fw-semibold text-capitalize transition-all handle-login">
                                Log In Now
                            </a>
                            <p class="form-buy-ticket__brief text-center">You must login to our site to book this event!
                            </p>
                            @elseif($hasBooked)
                            <span class="form-buy-ticket__message-green text-center d-block fw-bolder fs-18 mb-0">You've booked this event</span>
                            @elseif($remainingSlots <= 0) <span class="form-buy-ticket__message-orange text-center d-block fw-bolder fs-18 mb-0">Event slots are full</span>
                                @else
                                <input type="submit" class="form-buy-ticket__btn fs-14" value="Book Now">
                                @endif
                        </form>
                    </div>
                </aside>
                @else
                <aside class="col-12 col-lg-3">
                    <div class="form-buy-ticket">
                        <p class="text-danger fw-bold fs-19">This event has already ended.</p>
                    </div>
                </aside>
                @endif
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
