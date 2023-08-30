<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>{{ env('APP_NAME') }} - @yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/hamko-logo.png') }}">
    {{-- custom local css cdn area start --}}
    @include('frontend.global.cdncss')
    {{-- custom local css cdn area end --}}
</head>
<body class="bg-gradient-primary">
    <!-- Begin Page Content -->
    @yield('auth_content')
    <!-- End Page Content -->
    {{-- custom local js cdn area start --}}
    @include('frontend.global.cdnjs')
    {{-- custom local js cdn area end --}}
</body>
</html>
