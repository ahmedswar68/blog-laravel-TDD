<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
  <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
        type="text/css"/>

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <script>
    window.App = {!! json_encode(['user'=>Auth::user(),'signedIn'=>Auth::check()]) !!}
  </script>
  <style>
    body {
      padding-bottom: 100px;
    }

    .level {
      display: flex;
      align-items: center;
    }

    .level-item {
      margin-right: 1em;
    }

    .flex {
      flex: 1;
    }

    [v-cloak] {
      display: none
    }
    .ml-a { margin-left: auto; }
    .ais-highlight > em { background: yellow; font-style: normal; }
  </style>
  @yield('head')
</head>
<body>
<div id="app">
  @include ('layouts.nav')

  <main class="py-4">
    @yield('content')
  </main>

  <flash message="{{ session('flash') }}"></flash>
</div>
@yield('scripts')
</body>
</html>
