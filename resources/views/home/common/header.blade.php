<head>
    <title>MTedu</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="MTedu">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <!-- Favicon icons -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/css/home/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/home/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/home/lightbox.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/home/nouislider.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/home/style.css?v=5.6') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/home/main.css?v=5.6') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/common/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/home/course-index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/home/navbar.css') }}">

    <!-- Wow.js -->
    <link rel="stylesheet" href="{{ asset('assets/css/home/animate.css') }}">

    <!-- Bootstrap 5 JS (bundle includes Popper) -->
    <script src="{{ asset('assets/js/common/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/common/sweetalert2@11.js') }}"></script>
    @include('home.chatbot')

</head>
