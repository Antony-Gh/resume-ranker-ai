<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')

    <!-- Custom CSS -->
    @stack('styles')

    <!-- JavaScript -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="{{ mix('js/bootstrap.js') }}" defer></script>
</head>

<body>
    <div class="home">
        <!-- Header Section -->
        @include('partials.header-login')

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