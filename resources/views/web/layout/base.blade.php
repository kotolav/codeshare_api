<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
   @include('web.layout.sections.head')
</head>
<body>
<div class="content">
   <header class="content__header header">
      @include('web.layout.parts.header')
   </header>

   <main class="content__main main">
      @include('web.layout.sections.main')
   </main>

   <footer class="content__footer footer">
      @include('web.layout.sections.footer')
   </footer>
</div>

@stack('bottom-css')

<script src="{{ mix('/js/app.js') }}"></script>
@stack('bottom-scripts')
</body>

</html>
