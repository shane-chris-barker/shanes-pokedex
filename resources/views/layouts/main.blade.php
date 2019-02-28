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
</head>
    <body>
        <div id="content" class="mb-4">
            @yield('content')
        </div>
    </body>
    <footer>
        <div class="col-md-4 offset-md-4 col-12">
            <div class="col-6 offset-3">
                <img src="{{asset('img/logo.png')}}" class="img-fluid"/>
            </div>
            <div class="text-center">
                <p>
                    This application is in no way endorsed or associated with Niantic, Nintendo or any other official
                    Pokemon entity and we do not own any copyright related to Pokemon.
                </p>
            </div>
        </div>
        <script src="{{asset('js/app.js')}}"></script>
        <script src="{{asset('js/switcher.js')}}"></script>
        <script>
            $(document).ready( function() {
                $.switcher();
            });
        </script>
    </footer>
</html>
