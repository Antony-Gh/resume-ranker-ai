<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Resume Ranker AI')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#000000" />
    <meta name="description"
        content="Resume Ranker AI is an intelligent tool that analyzes and ranks resumes based on job descriptions using AI. Upload resumes, get instant insights, and optimize your hiring process effortlessly." />

    <!-- Favicon -->
    <link rel="icon" type="image/webp" sizes="32x32" href="{{ asset('images/logo.ico') }}" />

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('css/Font.css') }}">
    <link rel="stylesheet" href="{{ mix('css/Header.css') }}">
    <link rel="stylesheet" href="{{ mix('css/Footer.css') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

    @stack('styles')

    <!-- JavaScript -->
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>

<body>
    <div class="home">
        <!-- Header Section -->
        @include('partials.header')

        <!-- Main Content -->
        <div class="newscreenbody">
            <div class="content">
                @yield('content')
            </div>

            <!-- Footer Section -->
            @include('partials.footer')
        </div>
    </div>


    @stack('scripts')
</body>

</html>