<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>@yield('title', 'Customer Service')</title>

        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/feather.css') }}" rel="stylesheet">
        <link href="{{ asset('css/themify-icons.css') }}" rel="stylesheet">
        <link href="{{ asset('css/vendor.bundle.base.css') }}" rel="stylesheet">
        <link href="{{ asset('css/materialdesignicons.min.css') }}" rel="stylesheet">
        
        <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/select2-bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('css/buttons.bootstrap4.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('css/theme.bootstrap_4.min.css')}}">
        <link rel="icon" href="{{asset('images/wgroup.png')}}" type="image/x-icon">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>

    <style>
        @font-face {
            font-family: "Material Design Icons";
            src: url("{{ asset('fonts/materialdesignicons-webfont.eot') }}");
            src: url("{{ asset('fonts/materialdesignicons-webfont.eot') }}") format("embedded-opentype"),
                url("{{ asset('fonts/materialdesignicons-webfont.woff2') }}") format("woff2"),
                url("{{ asset('fonts/materialdesignicons-webfont.woff') }}") format("woff"),
                url("{{ asset('fonts/materialdesignicons-webfont.ttf') }}") format("truetype");
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: "feather";
            src:  url("{{ asset('fonts/feather-webfont.eot') }}");
            src:  url("{{ asset('fonts/feather-webfont.eot') }}") format("embedded-opentype"),
                    url("{{ asset('fonts/feather-webfont.woff') }}") format("woff"),
                    url("{{ asset('fonts/feather-webfont.ttf') }}") format("truetype"),
                    url("{{ asset('fonts/feather-webfont.svg') }}") format("svg");
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'themify';
            src: 	url("{{ asset('fonts/themify.eot') }}");
            src:	url("{{ asset('fonts/themify.eot') }}") format('embedded-opentype'),
                    url("{{ asset('fonts/themify.woff') }}") format('woff'),
                    url("{{ asset('fonts/themify.ttf') }}") format('truetype'),
                    url("{{ asset('fonts/themify.svg') }}") format('svg');
            font-weight: normal;
            font-style: normal;
        }
        /* .body_cs {
            min-height: 100vh;
            background: url('images/Seaweed_farm.jpg');
            background-size: cover;
            box-shadow: inset 0 0 0 2000px #20222582;
        } */
        .header_h2 {
            font-weight: 600;
            color: #fdfdfe;
            text-shadow: 0px 0px 0px #248afd, 0px 0px 5px #0c0d0e, 0px 0px 5px #248afd, 0px 0px 10px #3c3e41;
        }
        .button {
            display: inline-block;
            background: #ffffff;
            border: 1px solid #428bca;
            text-transform: uppercase;
            border-radius: 15px;
            padding: 45px 35px;
            color: #428bca;
            box-shadow: 0px 17px 10px -10px rgba(0, 0, 0, 0.4);
            cursor: pointer;
            -webkit-transition: all ease-in-out 300ms;
            transition: all ease-in-out 300ms;
        }
        .button:hover {
            box-shadow: 0px 32px 15px -15px rgba(0, 0, 0, 0.2);
            -webkit-transform: translate(0px, -5px) scale(1.2);
            transform: translate(0px, -5px) scale(1.2);
        }
    </style>
    <body>
        <div id="loader" style="display:none;" class="loader"></div>
        <div class="body_cs">
            @yield('content')
        </div>
        @include('sweetalert::alert')
        <!-- Add common scripts here -->
        <script src="{{ asset('js/app.js') }}"></script>
        <script>
            function show() {
                document.getElementById("loader").style.display = "block";
            }
            function hide()
            {
                document.getElementById("loader").style.display = "none";
            }
            function logout() {
                event.preventDefault();
                document.getElementById('logout-form').submit();
            }
        </script>
        <script src="{{ asset('js/vendor.bundle.base.js') }}"></script>

        <script src="{{ asset('js/select2.min.js') }}"></script>
        <script src="{{ asset('js/select2.js') }}"></script>
        <script src="{{ asset('js/main.js') }}"></script>

        <script src="{{ asset('js/off-canvas.js') }}"></script>
        <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
        <script src="{{asset('js/sweetalert2.min.js')}}"></script>
        <script src="{{ asset('js/dashboard.js') }}"></script>
        @yield('scripts') <!-- For additional page-specific scripts -->
    </body>
</html>