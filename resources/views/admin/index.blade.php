<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ url('/css/main.css') }}">
    <link href="{{ url('css/ionicons.min.css') }}" rel="stylesheet"/>
    {{--<script type="text/javascript" src="{{ url('js/jquery.min.js') }}"></script>--}}
    <title>{{ config("app.name") }}</title>
</head>
<body>
<div id="app"></div>

<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script>
<script src="{{ mix('js/admin.js') }}"></script>
</body>
</html>