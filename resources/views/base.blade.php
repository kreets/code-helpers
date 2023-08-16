
<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Ciber">
    <title>CodeHelpers by Ciber</title>
    <link href="{{ vite_asset('/js/app.css') }}" rel="stylesheet">

    <meta name="theme-color" content="#424345">
</head>
<body class="d-flex flex-column h-100" id="app" style="background-color: #424345">

<header>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">CodeHelpers</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <navigator-component></navigator-component>
            </div>
        </div>
    </nav>
</header>

<!-- Begin page content -->
<main class="flex-shrink-0 mt-4 pt-5">
    <div class="container-fluid">
        @yield('content')
    </div>
</main>

<footer class="footer mt-2 py-3 bg-dark">
    <div class="container">
        <span class="text-white">Created by Ciber. 2023</span>
    </div>
</footer>
<script src="{{ vite_asset('/js/app.js') }}"></script>

</body>
</html>
