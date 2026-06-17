{{-- resources/views/layouts/navbar-register.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <link rel="icon"
          type="image/png"
          sizes="64x64"
          href="{{ asset('icons8-skyatlas-48.png') }}">

    {{-- FONT AWESOME --}}
    <link rel="stylesheet"
          href="{{ asset('adminlte') }}/plugins/fontawesome-f72/css/all.min.css">

    {{-- JQUERY --}}
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>

    {{-- TOM SELECT --}}
    <link rel="stylesheet" href="{{ asset('css/tom-select.css') }}">

    <script src="{{ asset('js/tom-select.js') }}"></script>

    {{-- FONT --}}
    <link rel="stylesheet" href="{{ asset('css/font-inter.css') }}">

    <style>
        
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Inter',sans-serif;
            background:#0b141a;
            overflow:hidden;
        }

        a{
            text-decoration:none;
        }

        /* =====================================
            LAYOUT
        ===================================== */

        .wa-layout{
            display:flex;
            height:100vh;
            overflow:hidden;
        }

        /* =====================================
            SIDEBAR
        ===================================== */

        .wa-sidebar{
            width:72px;

            background:#202c33;

            border-right:
                1px solid rgba(255,255,255,.05);

            display:flex;
            flex-direction:column;
            justify-content:space-between;

            padding:14px 0;
        }

        .wa-sidebar-top,
        .wa-sidebar-bottom{
            display:flex;
            flex-direction:column;
            align-items:center;
            gap:10px;
        }

        .wa-side-btn{
            width:48px;
            height:48px;

            border-radius:14px;

            display:flex;
            align-items:center;
            justify-content:center;

            color:#aebac1;
            font-size:20px;

            position:relative;

            transition:.25s;
        }

        .wa-side-btn:hover{
            background:rgba(255,255,255,.08);
            color:white;
        }

        .wa-side-btn.active{
            background:#00a884;
            color:white;
        }

        /* =====================================
            TOOLTIP
        ===================================== */

        .wa-tooltip{
            position:absolute;

            left:65px;
            top:50%;

            transform:
                translateY(-50%)
                translateX(-10px);

            background:#233138;

            color:white;

            padding:8px 14px;

            border-radius:10px;

            font-size:13px;
            font-weight:500;

            white-space:nowrap;

            opacity:0;
            visibility:hidden;

            transition:.25s;

            pointer-events:none;

            z-index:9999;

            box-shadow:
                0 10px 30px rgba(0,0,0,.3);
        }

        .wa-tooltip::before{
            content:"";

            position:absolute;

            left:-5px;
            top:50%;

            width:10px;
            height:10px;

            background:#233138;

            transform:
                translateY(-50%)
                rotate(45deg);
        }

        .wa-side-btn:hover .wa-tooltip{
            opacity:1;
            visibility:visible;

            transform:
                translateY(-50%)
                translateX(0);
        }

        /* =====================================
            MAIN
        ===================================== */

        .wa-main{
            flex:1;

            display:flex;
            flex-direction:column;

            overflow:hidden;
        }

        /* =====================================
            NAVBAR
        ===================================== */

        .wa-navbar{
            height:72px;

            background:#202c33;

            border-bottom:
                1px solid rgba(255,255,255,.05);

            display:flex;
            align-items:center;
            justify-content:space-between;

            padding:0 24px;

            flex-shrink:0;
        }

        .wa-title{
            color:white;
            font-size:24px;
            font-weight:700;
        }

        /* =====================================
            NAVBAR BRAND
        ===================================== */

        .wa-navbar-left{
            display:flex;
            align-items:center;
        }

        .wa-logo{
            display:flex;
            align-items:center;
            justify-content:center;

            padding-right:14px;
            margin-right:14px;

            border-right:
                1px solid rgba(255,255,255,.12);

            transition:.25s;
        }

        .wa-logo img{
            height:38px;
            width:auto;

            object-fit:contain;

            transition:.25s;
        }

        .wa-logo:hover{
            border-right:
                1px solid rgba(0,168,132,.45);

            transform:translateY(-1px);
        }

        .wa-logo:hover img{
            transform:scale(1.05);
            filter:
                drop-shadow(0 0 10px rgba(0,168,132,.25));
        }

        .wa-title{
            color:white;
            font-size:24px;
            font-weight:700;

            letter-spacing:-0.3px;
        }

        /* =====================================
            CONTENT
        ===================================== */

        .wa-content{
            flex:1;

            overflow:auto;

            padding:20px;

            background:#0b141a;
        }

        /* =====================================
            CARD
        ===================================== */

        .page-card{
            background:#202c33;

            border-radius:22px;

            padding:24px;

            border:
                1px solid rgba(255,255,255,.05);

            color:white;
        }

        .page-title{
            font-size:26px;
            font-weight:700;

            margin-bottom:24px;
        }

        /* =====================================
            FORM
        ===================================== */

        .form-group{
            margin-bottom:20px;
        }

        .form-label{
            display:block;

            margin-bottom:8px;

            color:#d1d7db;

            font-size:14px;
            font-weight:600;
        }

        .form-control{
            width:100%;

            height:48px;

            border:none;
            outline:none;

            border-radius:14px;

            background:#2a3942;

            color:white;

            padding:0 16px;

            font-size:14px;
        }

        .form-control::placeholder{
            color:#8696a0;
        }

        textarea.form-control{
            height:120px;
            resize:none;

            padding-top:14px;
        }

        /* =====================================
            BUTTON
        ===================================== */

        .btn-submit{
            height:46px;

            border:none;

            border-radius:14px;

            background:#00a884;

            color:white;

            padding:0 22px;

            font-size:14px;
            font-weight:600;

            cursor:pointer;

            transition:.25s;
        }

        .btn-submit:hover{
            opacity:.9;
        }

        /* =====================================
            LOADING
        ===================================== */

        .loading-box{
            height:300px;

            display:flex;
            align-items:center;
            justify-content:center;

            color:#aebac1;

            font-size:18px;
        }

        /* =====================================
            SCROLLBAR
        ===================================== */

        ::-webkit-scrollbar{
            width:8px;
            height:8px;
        }

        ::-webkit-scrollbar-thumb{
            background:#2a3942;
            border-radius:20px;
        }

        /* =====================================
            TOM SELECT
        ===================================== */

        .ts-wrapper{
            width:100%;
        }

        .ts-wrapper.single .ts-control{

            background:#2a3942 !important;

            border:none !important;

            min-height:48px;

            border-radius:14px !important;

            padding:0 14px !important;

            display:flex;
            align-items:center;

            box-shadow:none !important;

            transition:.2s;
        }

        .ts-wrapper.focus .ts-control{
            box-shadow:
                0 0 0 2px rgba(0,168,132,.25) !important;
        }

        .ts-wrapper.single .ts-control input{

            color:white !important;

            font-size:14px !important;

            background:transparent !important;
        }

        .ts-wrapper.single .ts-control input::placeholder{
            color:#8696a0 !important;
        }

        .ts-wrapper.single .ts-control .item{
            color:white !important;
        }

        .ts-dropdown{

            background:#233138 !important;

            border:none !important;

            border-radius:14px !important;

            overflow:hidden;

            margin-top:8px !important;

            box-shadow:
                0 10px 30px rgba(0,0,0,.35);
        }

        .ts-dropdown .option{

            padding:12px 14px !important;

            color:white !important;

            transition:.2s;
        }

        .ts-dropdown .option:hover{

            background:#00a884 !important;

            color:white !important;

            padding-left:18px !important;
        }

        .ts-dropdown .active{

            background:#00a884 !important;

            color:white !important;
        }

        /* =====================================
            MOBILE
        ===================================== */

        @media(max-width:768px){

            .wa-sidebar{
                width:62px;
            }

            .wa-navbar{
                padding:0 12px;
            }

            .wa-title{
                font-size:18px;
            }

        }

    </style>

</head>

<body>
@include('sweetalert::alert')
<div class="wa-layout">

    {{-- SIDEBAR --}}
    <aside class="wa-sidebar">

        <div class="wa-sidebar-top">

            {{-- INPUT --}}
            <a href="javascript:void(0)"
               class="wa-side-btn menu-link active"
               data-page="input">

                <i class="fa-solid fa-pen-to-square"></i>

                <span class="wa-tooltip">
                    Input Sertim
                </span>

            </a>

            {{-- REPORT --}}
            <a href="javascript:void(0)"
               class="wa-side-btn menu-link"
               data-page="report">

                <i class="fa-regular fa-newspaper"></i>

                <span class="wa-tooltip">
                    Report
                </span>

            </a>

        </div>

        <div class="wa-sidebar-bottom">

            <a href="/"
               class="wa-side-btn">

                <i class="fas fa-door-open"></i>

                <span class="wa-tooltip">
                    Kembali
                </span>

            </a>

        </div>

    </aside>

    {{-- MAIN --}}
    <main class="wa-main">

        {{-- NAVBAR --}}
        <nav class="wa-navbar">

            <div class="wa-navbar-left">

                <div class="wa-logo">
                    <img src="{{ asset('igr.png') }}" alt="IGR Logo">
                </div>

                <div class="wa-title">
                    Register Sertim DSPB
                </div>

            </div>

        </nav>

        {{-- CONTENT --}}
        <section class="wa-content"
                 id="main-content">

        </section>

    </main>

</div>

<script>
    $(document).ready(function () {

        // Load default page (input)
        $('#main-content').load('/register-dspb/load-page/input');

        // Menu click
        $('.menu-link').on('click', function () {

            $('.menu-link').removeClass('active');
            $(this).addClass('active');

            let page = $(this).data('page');

            $('#main-content').html(`
                <div class="loading-box">
                    <i class="fas fa-spinner fa-spin"></i>
                    &nbsp;
                    Loading...
                </div>
            `);

            // Load halaman dengan callback
            $('#main-content').load('/register-dspb/load-page/' + page, function() {
                // Jika halaman yang dimuat adalah 'report', panggil initPagination
                if (page === 'report') {
                    if (typeof initPagination === 'function') {
                        initPagination();
                    }
                }
            });

        });

    });
</script>

</body>
</html>