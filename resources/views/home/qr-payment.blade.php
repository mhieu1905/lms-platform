<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    @include('home.common.header')
    <link rel="stylesheet" href="{{ asset('assets/css/home/qr-payments.css') }}">
</head>

<body class="main-layout">
    @include('home.common.navbar')
    <section class="page-banner-title"
        style="background-image: url('{{ asset('assets/images/common/main/page-banner.jpg') }}')">
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
                            <a href="index.html" class="title-bar__nav transition-all">Home</a>
                        </li>
                        <li class="title-bar__nav_items d-inline">
                            <a href="courses-category.html" class="title-bar__nav_before transition-all">Courses</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>
    <section class="container site-content">
        @if($payment->status !== 'expired')
            <div class="payment-timer">
                <div class="timer-row">
                    <span class="timer-label">Please complete your payment within:</span>
                    <div class="timer-wrapper">
                        <i class="mdi mdi-clock-outline"></i>
                        <span id="countdown"></span>
                    </div>
                </div>
                <div class="timer-bar">
                    <div class="timer-progress"></div>
                </div>
            </div>
        @endif
        <div class="main-content">
            <div class="qr-section">
                <div class="qr-title">Scan QR Code to Pay</div>
                <div class="qr-container">
                    <div class="qr-code">
                        @if($payment->status === 'expired')
                            <div style='color: red; font-weight: bold;' class='text-center'>
                                QR code expired.<br>
                                <a href='{{ route('orders.create', $product_id) }}' class='btn btn-danger mt-2'>Generate New
                                    QR</a>
                            </div>
                        @else
                        <img src="{{
                            strtr(config('settings.sepay.url_img_qr'), [
                                ':acc'      => config('settings.sepay.acc'),
                                ':bank'     => config('settings.sepay.bank'),
                                ':amount'   => number_format($total_to_vnd),
                                ':des'      => $code,
                                ':template' => config('settings.sepay.template'),
                            ])
                        }}" alt="QR Code" style="max-width: 100%; max-height: 100%;">
                        @endif
                    </div>
                </div>
            </div>
            <div class="bank-info">
                <div class="bank-header">Payment Details</div>
                <div class="bank-logo-section">
                    <div class="bank-left">
                        <img src="{{asset('assets/images/home/vpbank.jpg')}}" class="bank-logo" alt="">
                        <div class="bank-name">
                            <h3>VP</h3>
                            <div class="bank-subtitle">Bank Transfer</div>
                        </div>
                    </div>
                    <div class="account-owner">LE VIET MINH HIEU</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Account Number</div>
                    <div class="info-value">0393982345</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Amount</div>
                    <div class="info-value amount">{{number_format($total_to_vnd)}} VNĐ</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Transfer Note / Payment Reference</div>
                    <div class="info-value">{{$code}}</div>
                </div>
                <div class="total-section">
                    <div class="total-label">Total Amount</div>
                    <div class="total-amount">{{number_format($total_to_vnd)}} VNĐ</div>
                </div>
            </div>
        </div>
        <div id="success_pay_box">
            <div class="success-icon">
                <i class="mdi mdi-check"></i>
            </div>
            <h2 class="success-title">Payment Successful!</h2>
            @if ($type === 'courses')
                <p class="success-message">
                    Thank you for your purchase. Your payment has been processed successfully.<br>
                    You can now access your course.
                </p>
                <a href="{{ route('courses.show', $product_id) }}" class="success-btn">
                    <i class="mdi mdi-book-open-variant"></i>
                    Go to My Course
                </a>
            @elseif($type === 'events')
                <p class="success-message">
                    Thank you for your purchase. Your payment has been processed successfully.<br>
                    You can now access your event ticket.
                </p>
                <a href="{{ route('events.show', $product_id) }}" class="success-btn">
                <i class="mdi mdi-book-open-variant"></i>
                Go to My Event
            </a>
            @endif
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
    @include('home.scripts.qr-payments-scripts')
</body>

</html>