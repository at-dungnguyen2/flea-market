<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="/images/small-logo-01.png">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="/bower_resources/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/bower_resources/font-awesome/css/font-awesome.min.css" media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/mystyle.css">
    <script src="/bower_resources/jquery/dist/jquery.min.js"></script>
    @stack('stylesheet')
    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
  </head>
  <body>
    <!-- HEADER -->
    @include('layouts.parts.header')
    
    <!-- NAVBAR -->

    <div id="wrapper-navbar">
  		<div class="navbar object">
    		<div id="wrapper-sorting">
          @yield('breadcrumb')
        </div>
        <div id="wrapper-bouton-icon">
        	<div id="bouton-ai"><img src="/images/icon-ai.svg" alt="illustrator" title="Illustrator" height="28" width="28"></div>
        	<div id="bouton-psd"><img src="/images/icon-psd.svg" alt="photoshop" title="Photoshop" height="28" width="28"></div>
        	<div id="bouton-theme"><img src="/images/icon-themes.svg" alt="theme" title="Theme" height="28" width="28"></div>
        	<div id="bouton-font"><img src="/images/icon-font.svg" alt="font" title="Font" height="28" width="28"></div>
        	<div id="bouton-photo"><img src="/images/icon-photo.svg" alt="photo" title="Photo" height="28" width="28"></div>
        	<div id="bouton-premium"><img src="/images/icon-premium.svg" alt="premium" title="Premium" height="28" width="28"></div>
  			</div>
    	</div>
    </div>

    <!-- MENU -->	

  	<div id="main-container-menu" class="object">
    	<div class="container-menu">
        <div id="main-cross">
        	<div id="cross-menu"></div>
        </div>
            
        <div id="main-small-logo">
        	<div class="small-logo"></div>
        </div>
        
        <div id="main-premium-ressource">
            <div class="premium-ressource"><a href="#">Home</a></div>
        </div>
        
        <div id="main-themes">
            <div class="themes"><a href="#">Post</a></div>
        </div>
        @if (Auth::guest())
        <div id="main-psd">
            <div class="psd"><a href="#">Login</a></div>
        </div>
            
        <div id="main-ai">
            <div class="ai"><a href="#">Register</a></div>
        </div>
        @else
        
        <div id="main-font">
            <div class="font"><a href="#">Free fonts</a></div>
        </div>
        
        <div id="main-photo">
            <div class="photo"><a href="#">Free stock photos</a></div>
        </div>
            
        </div>
        @endif
    </div>


    <!-- PORTFOLIO -->

  	<div id="wrapper-container">
      @yield('errors-message')
      @yield('susscess-message')
      @yield('content')
      
              
    	<!-- <div id="wrapper-thank">
        	<div class="thank">
            	<div class="thank-text">pl<span style="letter-spacing:-5px;">a</span>tz</div>
        	</div>
    	</div> -->
  	    
      @include('layouts.parts.footer')
    </div>

    <!-- SCRIPT -->
  	<script type="text/javascript" src="/bower_resources/fastclick/lib/fastclick.js"></script>
  	<script type="text/javascript" src="/bower_resources/jquery-color-animation/jquery.animate-colors.js"></script>
  	<script type="text/javascript" src="/bower_resources/jquery-shadow-animation/jquery.animate-shadow-min.js"></script>    
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- main -->
  	<script type="text/javascript" src="/js/main.js"></script>
    <script type="text/javascript">
    </script>
    <script type="text/javascript" src="/js/mymain.js"></script>
    
    @stack('end-page-scripts')
  </body>
</html>
