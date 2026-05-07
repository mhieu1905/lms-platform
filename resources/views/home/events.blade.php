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
                            <a href="#" class="title-bar__nav_before transition-all pointer-events-none">
                                Events </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>
    <section class="events-page events-layout-1 mt-40px mb-80px">
        <div class="container">
            <div class="events-page__tab d-flex flex-wrap align-items-center gap-50px mb-15px">
                <button class="events-page__tab_btn active" data-tab="happening">Happening</button>
                <button class="events-page__tab_btn" data-tab="upcoming">Upcoming</button>
                <button class="events-page__tab_btn" data-tab="expired">Expired</button>
            </div>
            {{-- Happening Events --}}
            <div class="events-page__tab_content active" id="happening">
                @forelse ($eventsHappening as $event)
                <div class="events-layout-1__items d-flex">
                    <div class="events-layout-1__time">
                        <p class="events-layout-1__time-date fw-bolder mb-5px">{{ $event->start_time->format('d') }}</p>
                        <p class="events-layout-1__time-month fs-16">{{ $event->start_time->format('F') }}</p>
                    </div>
                    <div class="events-layout-1__content pe-40px ps-40px">
                        <h4 class="fs-20 fw-semibold line-clamp-2 mb-10px">
                            <a href="{{ route('events.show', ['id' => $event->id]) }}" class="transition-all">{{ Str::limit($event->title, 50) }}</a>
                        </h4>
                        <div class="events-layout-1__content-info d-flex flex-wrap align-items-center gap-10px mb-10px">
                            <div class="d-flex align-items-center gap-5px fs-14">
                                <i class="iconify fs-18" data-icon="lets-icons:time"></i>
                                <span>{{ $event->start_time->format('g:i a') }}</span>
                                <i class="iconify fs-18" data-icon="lets-icons:calendar"></i>
                                <span>{{ $event->start_time->format('d/m/Y') }}</span>
                                <span> - </span>
                                <i class="iconify fs-18" data-icon="lets-icons:time"></i>
                                <span>{{ $event->finish_time->format('g:i a') }}</span>
                                <i class="iconify fs-18" data-icon="lets-icons:calendar"></i>
                                <span>{{ $event->finish_time->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="events-layout-1__content-info d-flex flex-wrap align-items-center gap-10px mb-10px">
                            <div class="events-layout-1__location d-flex align-items-center fs-14 gap-5px">
                                <i class="iconify fs-18" data-icon="akar-icons:location"></i>
                                <span class="d-block">{{ Str::limit($event->address, 30) }}</span>
                            </div>
                        </div>
                        <p class="events-layout-1__content-brief">{!! Str::limit($event->description, 300) !!}</p>
                    </div>
                    <div class="events-layout-1__image">
                        <img src="{{asset('storage/' . $event->image)}}" alt="{{ generateAlt($event->title) }}">
                    </div>
                </div>
                @empty
                <h3 class="text-muted text-center mt-5">
                    There are no happening events.
                </h3>
                @endforelse
            </div>

            {{-- Upcoming Events --}}
            <div class="events-page__tab_content" id="upcoming">
                @forelse ($eventsUpcoming as $event)
                <div class="events-layout-1__items d-flex">
                    <div class="events-layout-1__time">
                        <p class="events-layout-1__time-date fw-bolder mb-5px">{{ $event->start_time->format('d') }}</p>
                        <p class="events-layout-1__time-month fs-16">{{ $event->start_time->format('F') }}</p>
                    </div>
                    <div class="events-layout-1__content pe-40px ps-40px">
                        <h4 class="fs-20 fw-semibold line-clamp-2 mb-10px">
                            <a href="{{ route('events.show', ['id' => $event->id]) }}" class="transition-all">{{ Str::limit($event->title, 50) }}</a>
                        </h4>
                        <div class="events-layout-1__content-info d-flex flex-wrap align-items-center gap-10px mb-10px">
                            <div class="d-flex align-items-center gap-5px fs-14">
                                <i class="iconify fs-18" data-icon="lets-icons:time"></i>
                                <span>{{ $event->start_time->format('g:i a') }}</span>
                                <i class="iconify fs-18" data-icon="lets-icons:calendar"></i>
                                <span>{{ $event->start_time->format('d/m/Y') }}</span>
                                <span> - </span>
                                <i class="iconify fs-18" data-icon="lets-icons:time"></i>
                                <span>{{ $event->finish_time->format('g:i a') }}</span>
                                <i class="iconify fs-18" data-icon="lets-icons:calendar"></i>
                                <span>{{ $event->finish_time->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="events-layout-1__content-info d-flex flex-wrap align-items-center gap-10px mb-10px">
                            <div class="events-layout-1__location d-flex align-items-center fs-14 gap-5px">
                                <i class="iconify fs-18" data-icon="akar-icons:location"></i>
                                <span class="d-block">{{ Str::limit($event->address, 30) }}</span>
                            </div>
                        </div>
                        <p class="events-layout-1__content-brief">{!! Str::limit($event->description, 300) !!}</p>
                    </div>
                    <div class="events-layout-1__image">
                        <img src="{{asset('storage/' . $event->image)}}" alt="{{ generateAlt($event->title) }}">
                    </div>
                </div>
                @empty
                <h3 class="text-muted text-center mt-5">
                    There are no upcoming events.
                </h3>
                @endforelse
            </div>

            {{-- Expired Events --}}
            <div class="events-page__tab_content" id="expired">
                @forelse ($eventExpired as $event)
                <div class="events-layout-1__items d-flex">
                    <div class="events-layout-1__time">
                        <p class="events-layout-1__time-date fw-bolder mb-5px">{{ $event->start_time->format('d') }}</p>
                        <p class="events-layout-1__time-month fs-16">{{ $event->start_time->format('F') }}</p>
                    </div>
                    <div class="events-layout-1__content pe-40px ps-40px">
                        <h4 class="fs-20 fw-semibold line-clamp-2 mb-10px">
                            <a href="{{ route('events.show', ['id' => $event->id]) }}" class="transition-all">{{ Str::limit($event->title, 50) }}</a>
                        </h4>
                        <div class="events-layout-1__content-info d-flex flex-wrap align-items-center gap-10px mb-10px">
                            <div class="d-flex align-items-center gap-5px fs-14">
                                <i class="iconify fs-18" data-icon="lets-icons:time"></i>
                                <span>{{ $event->start_time->format('g:i a') }}</span>
                                <i class="iconify fs-18" data-icon="lets-icons:calendar"></i>
                                <span>{{ $event->start_time->format('d/m/Y') }}</span>
                                <span> - </span>
                                <i class="iconify fs-18" data-icon="lets-icons:time"></i>
                                <span>{{ $event->finish_time->format('g:i a') }}</span>
                                <i class="iconify fs-18" data-icon="lets-icons:calendar"></i>
                                <span>{{ $event->finish_time->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="events-layout-1__content-info d-flex flex-wrap align-items-center gap-10px mb-10px">
                            <div class="events-layout-1__location d-flex align-items-center fs-14 gap-5px">
                                <i class="iconify fs-18" data-icon="akar-icons:location"></i>
                                <span class="d-block">{{ Str::limit($event->address, 30) }}</span>
                            </div>
                        </div>
                        <p class="events-layout-1__content-brief">{!! Str::limit($event->description, 300) !!}</p>
                    </div>
                    <div class="events-layout-1__image">
                        <img src="{{asset('storage/' . $event->image)}}" alt="{{ generateAlt($event->title) }}">
                    </div>
                </div>
                @empty
                <h3 class="text-muted text-center mt-5">
                    There are no expired events.
                </h3>
                @endforelse
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
