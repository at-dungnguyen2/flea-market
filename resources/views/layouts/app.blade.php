<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="/bower_resources/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/bower_resources/font-awesome/css/font-awesome.min.css" media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/myapp.css">
    @stack('stylesheet')
    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    <!-- jQuery -->
    <script src="/bower_resources/jquery/dist/jquery.min.js"></script>
  </head>
  <body>
    @include('layouts.parts.header')
    <div class="container">
      <div class="row margin-top-60">
        <div class="col-xs-12 col-sm-12 col-md-12">
          <header id="header">
            @yield('breadcrumb')
          </header>
        </div>
      </div>
      @yield('navigation-search')
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
          <div id="main">
              <!-- content -->
              @yield('errors-message')
              @yield('susscess-message')
              <div class="well well-sm">
                @yield('content')
              </div>
              <!-- end content -->
              @include('layouts.parts.footer')
          </div>
        </div>
      </div>
      <!-- sidebar -->
    </div>
    <!-- skel -->
    <script src="/bower_resources/skel/dist/skel.min.js"></script>
    <!-- util -->
    <script src="/bower_resources/util/util.min.js"></script>
    <!-- bootstrap -->
    <script src="/bower_resources/bootstrap/dist/js/bootstrap.min.js"></script>
    @stack('end-page-scripts')
    <script>
      window.Laravel = { csrfToken: '{{ csrf_token() }}' };
    </script>
    
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- main -->
    <script src="/js/app.js"></script>
  </body>
</html>
