<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('assets/plugins/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" >
    <link href="{{ asset('assets/plugins/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/fontawesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/nprogress/nprogress.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/jqvMap/jqvmap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/pNotify/pnotify.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/pNotify/pnotify.buttons.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/emoji-picker/lib/css/emoji.css') }}">
</head>
<body class="nav-md">
    <div id="app">
        <div class="container body">
          <div class="main_container">
            <div class="col-md-3 left_col menu_fixed">
              <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                  <a href="{{ url('/') }}" class="site_title"
                    ><img src="{{asset('assets/images/logo.png')}}" class="logoImg"><span>{{config('app.name', 'Laravel') }}</span></a
                  >
                </div>

                <div class="clearfix"></div>

                <!-- menu profile quick info -->
                <div class="profile clearfix">
                  <div class="profile_pic">
                    <img
                      src="{{asset('assets/images/img.jpg')}}"
                      alt="..."
                      class="img-circle profile_img"
                    />
                  </div>
                  <div class="profile_info">
                    <span>Welcome,</span>
                    <h2>John Doe</h2>
                  </div>
                </div>
                <!-- /menu profile quick info -->

                <!-- sidebar menu -->
                <div
                  id="sidebar-menu"
                  class="main_menu_side hidden-print main_menu"
                >
                  <div class="menu_section">
                    <ul class="nav side-menu">
                      <li>
                        <a href="{{ route('users.index') }}"><i class="fa fa-users"></i> Users</a>
                      </li>
                      <li>
                        <a href="{{ route('checkins.index') }}"><i class="fa fa-users"></i> Checkins</a>
                      </li>
                      <li>
                        <a href="{{ route('countries.index') }}"><i class="fa fa-flag-checkered"></i>Countries</a>
                      </li>
                      <li>
                        <a href="{{ route('locations.index') }}"><i class="fa fa-map-marker"></i>Locations</a>
                      </li>
                      <li>
                        <a href="{{ route('companies.index') }}"><i class="fa fa-building-o"></i>Companies</a>
                      </li>
                      <li>
                        <a href="{{ route('departments.index') }}"><i class="fa fa-building-o"></i>Departments</a>
                      </li>
                      <li>
                        <a href="{{ route('branches.index') }}"><i class="fa fa-building-o"></i>Branches</a>
                      </li>
                      <li>
                        <a href="{{ route('health-centers.index') }}"><i class="fa fa-hospital-o"></i>Health Centers </a>
                      </li>
                      <li>
                        <a href="{{ route('s1-options.index') }}"><i class="fa fa-question"></i> Health Status Options</a>
                      </li>
                      <li>
                        <a href="{{ route('questions.index') }}"><i class="fa fa-question"></i>Questions</a>
                      </li>
                      <li>
                        <a href="{{ route('news.index') }}"><i class="fa fa-newspaper-o"></i> News </a>
                      </li>
                      <li>
                        <a href="{{ route('resources.index') }}"><i class="fa fa-newspaper-o"></i> Resources </a>
                      </li>
                      <li>
                        <a href="{{ route('notifications.index') }}"><i class="fa fa-bell-o"></i> Notifications </a>
                      </li>
                      <li>
                        <a href="{{ route('settings.index') }}"><i class="fa fa-bell-o"></i> App Settings </a>
                      </li>
                    </ul>
                  </div>
                </div>
                <!-- /sidebar menu -->
              </div>
            </div>

            <!-- top navigation -->
            <div class="top_nav">
              <div class="nav_menu">
                <div class="nav toggle">
                  <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                </div>
                <nav class="nav navbar-nav">
                  <ul class=" navbar-right">
                    <li class="nav-item dropdown open" style="padding-left: 15px;">
                      <a
                        href="javascript:;"
                        class="user-profile dropdown-toggle"
                        aria-haspopup="true"
                        id="navbarDropdown"
                        data-toggle="dropdown"
                        aria-expanded="false"
                      >
                        <img src="{{asset('assets/images/img.jpg')}}" alt="" />John Doe
                      </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();"><i class="fa fa-sign-out pull-right"></i>
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                  </ul>
                </nav>
              </div>
            </div>
            <!-- /top navigation -->

            <main class="py-4">
              @yield('content')
            </main>

            <!-- footer content -->
            <footer>
              <div class="pull-right">
                {{ config('app.name', 'Laravel') }}
              </div>
              <div class="clearfix"></div>
            </footer>
            <!-- /footer content -->
          </div>
        </div>
    </div>
    <script src="{{ asset('assets/plugins/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/nprogress/nprogress.js') }}"></script>

    <script src="{{ asset('assets/plugins/jqvMap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jqvMap/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('assets/plugins/jqvMap/jquery.vmap.sampledata.js') }}"></script>
    <script src="{{ asset('assets/plugins/pNotify/pnotify.js') }}"></script>
    <script src="{{ asset('assets/plugins/pNotify/pnotify.buttons.js') }}"></script>
    <script src="{{ asset('assets/plugins/ckeditor5/ckeditor.js') }}"></script>
    <!-- for emoji-picker -->
    <script src="{{ asset('assets/plugins/emoji-picker/lib/js/config.js') }}"></script>
    <script src="{{ asset('assets/plugins/emoji-picker/lib/js/util.js') }}"></script>
    <script src="{{ asset('assets/plugins/emoji-picker/lib/js/jquery.emojiarea.js') }}"></script>
    <script src="{{ asset('assets/plugins/emoji-picker/lib/js/emoji-picker.js') }}"></script>
    <!-- Custom Theme Scripts -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    @yield('scripts')
</body>
</html>
