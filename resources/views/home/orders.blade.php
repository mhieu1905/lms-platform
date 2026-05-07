<!DOCTYPE html>
<html lang="vi">
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

@include('home.common.header')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/home/order.css') }}">
    <title>Check Out</title>
</head>

<body class="main-layout">
    @include('home.common.navbar')
    <section class="page-banner-title"
        style="background-image: url('{{ asset('assets/images/home/main/page-banner.jpg') }}')">
        <div class="container pt-80px pb-70px">
            <h1 class="page-banner-title_page fs-40 fw-bolder text-white"></h1>
        </div>
    </section>
    <section class="title-bar pt-20px pb-20px">
        <div class="container">
            <div class="row">
                <nav class="title-bar__nav">
                    <ul class="title-bar__nav_list">
                        <li class="title-bar__nav_items d-inline">
                            <a href="#" class="title-bar__nav transition-all">Home</a>
                        </li>
                        <li class="title-bar__nav_items d-inline">
                            <a href="#" class="title-bar__nav_before transition-all">CheckOut</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>
    <section class="checkout-page__order pt-20px pb-80px">
        <div class="checkout-container">
            <div class="order-summary-left">
                <h2>Your Order</h2>
                <div class="course-card">
                    <div class="course-image">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}">
                        <span class="course-badge">Best Seller</span>

                    </div>
                    <div class="course-info" title="{{ $product->title }}">
                        <div class="course-title">
                            {{ Str::limit($product->title, 40) }}
                        </div>
                        <div class="course-teacher">
                            <i class="mdi mdi-account"></i>
                            <span>Teacher: {{ $product->user->name ?? '' }}</span>
                        </div>
                        <div class="course-lessons">
                            <i class="mdi mdi-book-open-variant"></i>
                            <span>{{ $product->lessons_count ?? ''}} lessons</span>
                        </div>
                        <div class="course-price">
                            <i class="mdi mdi-cash"></i>
                            <span id="order-summary-amount"
                                data-price-vnd="{{ number_format($price * config('settings.currency.usd_to_vnd')) }}"
                                data-price-usd="{{ $price }}">
                                {{ $price }} USD
                            </span>

                        </div>
                        <button type="button" class="course-detail-btn"
                            style="margin-top:18px;display:flex;align-items:center;gap:6px;"
                            onclick="window.location.href='{{ route('courses.show', $product->id) }}'">
                            <i class="mdi mdi-arrow-right"></i> View Details
                        </button>
                    </div>
                </div>
            </div>
            <form action="{{ route('orders.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="price" value="{{ $price }}">
                <div class="checkout-form">
                    <h2>Order Summary</h2>
                    <div class="subtotal-box">
                        <h6 class="text-muted">Subtotal</h6>
                        <span id="subtotal-amount"
                            data-price-vnd="{{ number_format($price * config('settings.currency.usd_to_vnd')) }}"
                            data-price-usd="{{ $price }}">
                            {{ $price }} USD
                        </span>
                    </div>
                    <div class="subtotal-box">
                        <h6 class="text-muted">Quantity</h5>
                            <span>1</span>
                            <input type="hidden" name="quantity" value="1">
                    </div>
                    <div class="note-box">
                        <label for="note">Note to Admin</label>
                        <textarea id="note" name="note" placeholder="Write your note here..."></textarea>
                    </div>
                    <div class="payment-box">
                        <h3 class="payment-title">Payment Methods</h3>
                        <div class="payment-methods">
                            <div class="payment-method">
                                <input type="radio" id="sepay" name="payment" value="sepay" checked>
                                <label for="sepay">
                                    <div class="payment-icon"><img
                                            src="{{ asset('assets/images/home/sepay-logo.png') }}" alt=""></div>
                                    <span>Sepay</span>
                                </label>
                            </div>
                            <div class="payment-method">
                                <input type="radio" id="paypal-radio" name="payment" value="paypal">
                                <label for="paypal">
                                    <div class="payment-icon"><img
                                            src="{{ asset('assets/images/home/paypal-logo.png') }}" alt=""></div>
                                    <span>Paypal</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="price-breakdown">
                        <div class="price-row total">
                            <span>Total:</span>
                            <span id="total-amount"
                                data-price-vnd="{{ number_format($price * config('settings.currency.usd_to_vnd')) }}"
                                data-price-usd="{{ $price }}">
                                {{ $price }}
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="checkout-btn">
                        <span>PLACE ORDER</span>
                    </button>
                    <div id="paypal-button-container"></div>
                </div>
            </form>
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
    @include('home.scripts.orders-scripts')
</body>

</html>