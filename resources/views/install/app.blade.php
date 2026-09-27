<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maildoll - Email & SMS Marketing SaaS Application</title>
    <link href="{{ filePath('install/css/full.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ filePath('install/css/tailwind.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ filePath('install/css/style.css') }}" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="{{ maildoll() }}" type="image/x-icon"/>
    @notifyCss

</head>


@yield('css')

<body>

    @yield('content')



    @yield('scripts')


    <x-notify::notify />
    @notifyJs
</body>

</html>