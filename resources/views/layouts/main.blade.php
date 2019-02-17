<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{asset('img/favicon.ico')}}" type="image/x-icon">
    <link rel="icon" href="{{asset('img/favicon.ico')}}" type="image/x-icon">
    <title>@yield('title')</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/switcher.css')}}" rel="stylesheet" type="text/css">
</head>
    <body>
        <div id="content" style="margin-bottom:5%; min-height:45em;">
            @yield('content')
        </div>
    </body>
    <footer class="mt-10" style="min-height:5em">
        <script src="{{asset('js/app.js')}}"></script>
        <script src="{{asset('js/switcher.js')}}"></script>
        <script>
            $(document).ready( function() {
                $.switcher();
            });
        </script>
    </footer>
</html>
