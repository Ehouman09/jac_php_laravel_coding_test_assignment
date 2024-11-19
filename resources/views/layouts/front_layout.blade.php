<!DOCTYPE html>
<head  lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Jean Yves Ehouman"> 

    @hasSection('title')
        <title> {{ config('app.name') }} | @yield('title') </title>
    @else
        <title>{{ config('app.name') }} | PHP Laravel Library Management App </title>
    @endif 
 

    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/vendor/fonts/circular-std/style.css">
    <link rel="stylesheet" href="/assets/libs/css/style.css">
    <link rel="stylesheet" href="/assets/libs/css/main.css">
    <link rel="stylesheet" href="/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
 

</head>

<body>
 
 
    <!--================ Cntent ================-->
    <div class="splash-container">
        @include('includes.flash_messages')
        @yield('content')
    </div>
    <!--================ End of Cntent ================-->
 
   <!-- JavaScript -->
   <script src="/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
   <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>


</body>



</html>
