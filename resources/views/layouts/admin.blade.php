<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Pierotucci') }}</title>

    <!--
    <link href="/styles/kendo.common.min.css" rel="stylesheet" />
    <link href="/styles/kendo.default.min.css" rel="stylesheet" />
    <link href="/styles/kendo.dataviz.min.css" rel="stylesheet" />
    <link href="/styles/kendo.dataviz.default.min.css" rel="stylesheet" />
    -->
    <link rel="stylesheet" href="http://kendo.cdn.telerik.com/2018.2.620/styles/kendo.common.min.css" />
    <link rel="stylesheet" href="http://kendo.cdn.telerik.com/2018.2.620/styles/kendo.blueopal.min.css" />
    
    <link href="/app/css/extend.default.css" rel="stylesheet" type="text/css" />
    
    <link href="/app/css/style.css?_=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href="/app/css/flaticon.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="/js/jquery.min.js"></script>
    
    <!--
    <script type="text/javascript" src="/js/kendo.all.min.js"></script>
    -->
    <script src="http://kendo.cdn.telerik.com/2018.2.620/js/kendo.all.min.js"></script>
    
    <script class="culture" type="text/javascript"></script>
    <script class="messages" type="text/javascript"></script>
    <script class="language" type="text/javascript"></script>

    <script src="/js/jszip.min.js"></script>
    <script src="/js/pako_deflate.min.js"></script>    
    <script type="text/javascript" src="/app/jquery.jsonp.js"></script>
    <script type="text/javascript" src="/app/jquery.serializeobject.js"></script>
    <script type="text/javascript" src="/app/kendo.console.plugin.js"></script>
    <script type="text/javascript" src="/app/kendo.themechooser.plugin.js"></script>
    <script type="text/javascript" src="/app/Application.js?_=<?php echo time(); ?>"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCb8ZTEbZCKakc3SsiqreaZnQWdBcvKrkk" async defer></script>
</head>
<body class="k-content">
    @yield('content')
</body>
</html>

