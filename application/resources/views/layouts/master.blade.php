<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>{{env('APP_NAME')}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="{{$themeAssets}}/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="{{$assets}}/plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{$assets}}/plugins/toaster/jquery.toast.css">
    <link rel="stylesheet" href="{{$assets}}/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="{{$assets}}/plugins/chosen/chosen.css">
    <link rel="stylesheet" href="{{$themeAssets}}/custom.min.css">
    <link rel="stylesheet" href="{{$assets}}/custom.css">

    @yield('custom-style')

  </head>
  <body>

  <!-- BEGAIN AJAXLOADER -->
  <div id="ajaxloader" class="hide">
      <div id="status">&nbsp;</div>
  </div>
  <!-- END AJAXLOADER -->

    <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a href="{{url('dashboard')}}" class="navbar-brand">{{env('APP_NAME')}}</a>
          <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
          @if (Auth::check())
          <ul class="nav navbar-nav">
            <li><a href="{{url('dashboard')}}">Dashboard</a></li>
            @if(Auth::user()->role == 'super-admin')
            <li><a href="{{url('company')}}">Company</a></li>
            @endif
            <li><a href="{{url('users')}}">Users</a></li>
            <li><a href="{{url('contacts')}}">Contacts</a></li>
            <li><a href="{{url('events')}}">Events</a></li>
            <li><a href="{{url('reminders')}}">Reminders</a></li>
          </ul>
          @endif

          <ul class="nav navbar-nav navbar-right">
            @if (Auth::check())
                <!--<li><a href="#" class="dropdown-toggle">SMS Credit: {{$smsCredit}}</a></li>-->
                <li><a href="#" class="dropdown-toggle">Last Login: {{session()->get('lastLogin')}}</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::user()->name }} ({{ Auth::user()->role }}) <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                      <li><a href="{{ url('/profile') }}"><i class="fa fa-btn fa-user"></i> Profile</a></li>
                      <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i> Logout</a></li>
                    </ul>
                </li>
            @endif
          </ul>
        </div>
      </div>
    </div>


    <div class="container">

      @yield('content')

      <footer>
        <div class="row">
          <div class="col-lg-12">
            <ul class="list-unstyled">
              <li class="pull-right">Copyright &copy; {{Carbon::now()->format('Y')}} by {!!env('APP_COPYRIGHT_NAME')!!}</li>
            </ul>
            <p>Made with <a href="#" rel="nofollow"><i style="color:red;" class="fa fa-heart-o"></i></a> | <small>{{app_build_info()}}</small></p>
          </div>
        </div>
      </footer>
    </div>
    <script src="{{$themeAssets}}/jquery.min.js"></script>
    <script src="{{$themeAssets}}/bootstrap.min.js"></script>
    <script src="{{$assets}}/plugins/toaster/jquery.toast.js"></script>
    <script src="{{$assets}}/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script src="{{$assets}}/plugins/chosen/chosen.jquery.min.js"></script>

    <!-- Theme's Custom Script-->
    <script src="{{$themeAssets}}/custom.js"></script>

    <!-- Custom Script-->
    <script src="{{$assets}}/custom.js"></script>

    @yield('custom-script')

    @if(session()->has('toast'))
        {{--*/
        $toast = session()->get('toast');
        $message = $toast['message'];
        $type = $toast['type'];
        /*--}}
        <script>
            toastMsg("{!! $message !!}","{{ $type }}");
        </script>
    @endif
  </body>
</html>
