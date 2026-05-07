<footer class="section-footer main-footer footer-layout-1 pt-80px pb-50px">
    <div class="container">
        <div class="row">
            @if ($footersWithLogo->isNotEmpty())
                @foreach ($footersWithLogo as $footer)
                    <div class="col-12 col-lg-4 mb-30px mb-lg-0">
                        <a href="{{ route('home.index') }}"><img
                                src="{{ asset('assets/images/home/logo/' . $footer->content->logo) }}" alt="logo"
                                class="section-footer_logo mb-40px"></a>
                        @php
                            $items = $footer->content->items ?? [];
                        @endphp
                        @foreach ($items as $item)
                            <div class="footer-layout-1__info d-flex align-items-center gap-5px mb-15px">
                                <i class="iconify fs-20" data-icon="{{ $item->label }}"></i>
                                <span class="d-block">{{ $item->link ?? $item->text }}</span>
                            </div>
                        @endforeach
                        {{-- SOCIAL --}}
                        @if ($footersWithSocial->isNotEmpty())
                            @foreach ($footersWithSocial as $footer)
                                <ul class="d-flex align-items-center gap-15px">
                                    @php
                                        $items = $footer->content->items ?? [];
                                    @endphp
                                    @foreach ($items as $item)
                                        <li>
                                            <a href="{{ $item->link }}" class="transition-all"><i
                                                    class="iconify fs-24" data-icon="{{ $item->label }}"></i></a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endforeach
                        @else
                            <ul class="d-flex align-items-center gap-15px">
                                <li>
                                    <a href="#" class="transition-all"><i class="iconify fs-24"
                                            data-icon="circum:facebook"></i></a>
                                </li>
                                <li>
                                    <a href="#" class="transition-all"><i class="iconify fs-24"
                                            data-icon="ri:twitter-x-fill"></i></a>
                                </li>
                                <li>
                                    <a href="#" class="transition-all"><i class="iconify fs-24"
                                            data-icon="ph:pinterest-logo"></i></a>
                                </li>
                                <li>
                                    <a href="#" class="transition-all"><i class="iconify fs-24"
                                            data-icon="ph:instagram-logo"></i></a>
                                </li>
                            </ul>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="col-12 col-lg-4 mb-30px mb-lg-0">
                    <a href="#"><img src="{{ asset('assets/images/common/course_placeholder.png') }}"
                            alt="logo" class="section-footer_logo mb-40px"></a>
                    <div class="footer-layout-1__info d-flex align-items-center gap-5px mb-15px">
                        <i class="iconify fs-20" data-icon="iconoir:phone"></i>
                        <span class="d-block">800 388 80 90</span>
                    </div>
                    <div class="footer-layout-1__info d-flex align-items-center gap-5px mb-15px">
                        <i class="iconify fs-20" data-icon="akar-icons:location"></i>
                        <span class="d-block">58 Howard Street #2 San Francisco</span>
                    </div>
                    <div class="footer-layout-1__info d-flex align-items-center gap-5px mb-20px">
                        <i class="iconify fs-20" data-icon="tabler:mail-check"></i>
                        <span class="d-block"><a href="#" class="__cf_email__"
                                data-cfemail="bad9d5d4cedbd9cefadfdecfd7db94d9d5d7">[email&#160;protected]</a></span>
                    </div>
                    <ul class="d-flex align-items-center gap-15px">
                        <li>
                            <a href="#" class="transition-all"><i class="iconify fs-24"
                                    data-icon="circum:facebook"></i></a>
                        </li>
                        <li>
                            <a href="#" class="transition-all"><i class="iconify fs-24"
                                    data-icon="ri:twitter-x-fill"></i></a>
                        </li>
                        <li>
                            <a href="#" class="transition-all"><i class="iconify fs-24"
                                    data-icon="ph:pinterest-logo"></i></a>
                        </li>
                        <li>
                            <a href="#" class="transition-all"><i class="iconify fs-24"
                                    data-icon="ph:instagram-logo"></i></a>
                        </li>
                    </ul>
                </div>
            @endif
            @if ($footersWithTitle->isNotEmpty())
                @foreach ($footersWithTitle as $footer)
                    <div class="col-6 col-lg-2 mb-20px mb-lg-0">
                        <h6 class="footer__title fw-semibold text-white mb-40px">{{ $footer->content->title }}</h6>
                        <ul>
                            @php
                                $items = $footer->content->items ?? [];
                            @endphp

                            @foreach ($items as $item)
                                <li class="mb-10px">
                                    <a class="transition-all" href="{{ $item->link }}">{{ $item->label }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            @else
                <div class="col-6 col-lg-2 mb-20px mb-lg-0">
                    <h6 class="footer__title fw-semibold text-white mb-40px">Company</h6>
                    <ul>
                        <li class="mb-10px"><a class="transition-all" href="#">About</a></li>
                        <li class="mb-10px"><a class="transition-all" href="#">Blog</a></li>
                        <li class="mb-10px"><a class="transition-all" href="#">Contact</a></li>
                        <li class="mb-10px"><a class="transition-all" href="#">Become a Teacher</a>
                        </li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2 mb-20px mb-lg-0">
                    <h6 class="footer__title fw-semibold text-white mb-40px">Title 2</h6>
                    <ul>
                        <li class="mb-10px"><a class="transition-all" href="#">Item 1</a></li>
                        <li class="mb-10px"><a class="transition-all" href="#">Item 2</a></li>
                        <li class="mb-10px"><a class="transition-all" href="#">Item 3</a></li>
                        <li class="mb-10px"><a class="transition-all" href="#">Item ...</a>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
    </div>
</footer>

@if ($footersWithCopyright->isNotEmpty())
    @foreach ($footersWithCopyright as $footer)
        <footer class="footer-secondary pt-30px pb-30px">
            <div class="container">
                <div class="row">
                    <div
                        class="footer-layout-1__bottom d-md-flex flex-wrap align-items-center justify-content-center justify-content-md-between">
                        <p class="text-center mb-3 mb-md-0">{{ $footer->content->copyright }}</p>
                        <ul
                            class="d-flex align-items-center justify-content-center justify-content-md-between gap-3 gap-md-4">
                            @php
                                $items = $footer->content->items ?? [];
                            @endphp
                            @foreach ($items as $item)
                                <li><a class="transition-all" href="{{ $item->link }}">{{ $item->label }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    @endforeach
@else
    <footer class="footer-secondary pt-30px pb-30px">
        <div class="container">
            <div class="row">
                <div
                    class="footer-layout-1__bottom d-md-flex flex-wrap align-items-center justify-content-center justify-content-md-between">
                    <p class="text-center mb-3 mb-md-0">MTedu - Direct Course Selling & Live Learning Platform</p>
                </div>
            </div>
        </div>
    </footer>
@endif

@if(!Auth::check())
<section class="main-footer-fixed footer-end-fixed position-relative" style="background-image: url('{{ asset('assets/images/home/main/footer-bg.png') }}')">
    <div class="container">
        <div class="footer-end-fixed__content position-relative text-center">
            <h2 class="text-uppercase fw-semibold mb-15px">BECOME A TEACHER?</h2>
            <p class="fs-18 mb-25px">Join thousand of teachers and earn money hassle free!</p>
            <a href="{{ route('register-teacher.form') }}" class="d-block footer-end-fixed__button text-uppercase fs-14 fw-semibold transition-all">
                Get Started Now
            </a>
        </div>
    </div>
</section>
@endif

