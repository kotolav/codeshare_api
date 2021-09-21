<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>@yield('title')</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&amp;display=swap" rel="stylesheet">
<link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="{{ mix('/css/app.css') }}">

@stack('head-css')
@stack('head-scripts')
