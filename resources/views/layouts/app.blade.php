<!doctype html>
<html lang="en">
@include('partials.head')

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        @include('partials.navbar')
        @include('partials.sidebar')
        <main class="app-main">
            @yield('content')
        </main>
        @include('partials.footer')
    </div>
        @include('partials.scripts')
        @stack('scripts')
</body>

</html>