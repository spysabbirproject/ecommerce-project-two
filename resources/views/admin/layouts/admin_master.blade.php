@php
    $default_setting = App\Models\Default_setting::first();
@endphp

<!doctype html>
<html lang="en">

<head>
    <title>:: {{ env('APP_NAME') }} :: Admin :: @yield('title_bar')</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="description" content="{{ $default_setting->app_name }} Admin Template">
    <meta name="author" content="Spy IT Firm, www.spyitfirm.com">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{asset('uploads/default_photo')}}/{{$default_setting->favicon}}" type="image/x-icon">
    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="{{asset('admin')}}/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('admin')}}/vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('admin')}}/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">

    <link rel="stylesheet" href="{{asset('admin')}}/vendor/datatables/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="{{asset('admin')}}/vendor/datatables/css/buttons.dataTables.min.css"/>

    <link rel="stylesheet" href="{{asset('admin')}}/vendor/select2/css/select2.min.css"/>
    <link rel="stylesheet" href="{{asset('admin')}}/vendor/summernote/summernote-bs4.min.css"/>
    <link rel="stylesheet" href="{{asset('admin')}}/vendor/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css">
    <link rel="stylesheet" href="{{asset('admin')}}/vendor/chartist/css/chartist.min.css">
    <link rel="stylesheet" href="{{asset('admin')}}/vendor/chartist-plugin-tooltip/chartist-plugin-tooltip.css">
    <link rel="stylesheet" href="{{asset('admin')}}/vendor/toastr/toastr.min.css">
    <link rel="stylesheet" href="{{asset('admin')}}/vendor/morrisjs/morris.css" />

    <!-- MAIN CSS -->
    <link rel="stylesheet" href="{{asset('admin')}}/css/main.css">
    <link rel="stylesheet" href="{{asset('admin')}}/css/blog.css">
    <link rel="stylesheet" href="{{asset('admin')}}/css/color_skins.css">
</head>

<body class="theme-cyan">

    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="m-t-30"><img src="{{asset('admin')}}/images/icon-light.svg" width="48" height="48"
                    alt="{{ $default_setting->app_name }}"></div>
            <p>Please wait...</p>
        </div>
    </div>

    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>

    <div id="wrapper">
        <nav class="navbar navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-left">
                    <div class="navbar-btn">
                        <a href="index.html"><img src="{{asset('admin')}}/images/icon-light.svg" alt="{{ $default_setting->app_name }} Logo"
                                class="img-fluid logo"></a>
                        <button type="button" class="btn-toggle-offcanvas"><i
                                class="lnr lnr-menu fa fa-bars"></i></button>
                    </div>
                    <a href="javascript:void(0);" class="icon-menu btn-toggle-fullwidth bg-dark"><i
                            class="fa fa-arrow-left"></i></a>
                </div>

                <div class="navbar-right">
                    <div id="navbar-menu">
                        <ul class="nav navbar-nav">
                            <li class="dropdown dropdown-animated scale-left bg-info">
                                @php
                                    $messages = App\Models\Contact_message::where('status', 'Unread')->get()
                                @endphp
                                <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown">
                                    <i class="icon-envelope"></i>
                                    @if ($messages->count() != 0)
                                    <span class="notification-dot"></span>
                                    @endif
                                </a>
                                <ul class="dropdown-menu right_chat email">
                                    @forelse ($messages as $message)
                                    <li class="border p-2">
                                        <a href="javascript:void(0);">
                                            <div class="media">
                                                <div class="media-body">
                                                    <span class="name">{{ $message->full_name }} <small class="float-right">{{ $message->created_at }}</small></span>
                                                    <span class="message">{{ $message->subject }}</span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    @empty
                                    <li>
                                        <div class="alert alert-warning" role="alert">
                                            <span>New Message Not Found.</span>
                                        </div>
                                    </li>
                                    @endforelse
                                </ul>
                            </li>
                            <li class="dropdown dropdown-animated scale-left bg-info">
                                <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown">
                                    <i class="icon-bell"></i>
                                    <span class="notification-dot"></span>
                                </a>
                                <ul class="dropdown-menu feeds_widget">
                                    <li class="header">Daily Notifications</li>
                                    @php
                                        $order_summery = App\Models\Order_summery::all()
                                    @endphp
                                    @if ($order_summery->where('order_status', 'Delivered')->where('created_at', '>', Carbon\Carbon::now()->subDays(1))->count() != 0)
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="feeds-left"><i class="fa fa-thumbs-o-up text-success"></i></div>
                                            <div class="feeds-body">
                                                <h4 class="title text-success">({{ $order_summery->where('order_status', 'Delivered')->where('created_at', '>', Carbon\Carbon::now()->subDays(1))->count() }}) Orders Delivered<small
                                                        class="float-right text-muted">Today</small></h4>
                                                <small>It will give a smart finishing to your site</small>
                                            </div>
                                        </a>
                                    </li>
                                    @endif
                                    @if (App\Models\User::where('created_at', '>', Carbon\Carbon::now()->subDays(1))->count() != 0)
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="feeds-left"><i class="fa fa-user"></i></div>
                                            <div class="feeds-body">
                                                <h4 class="title">({{ App\Models\User::where('created_at', '>', Carbon\Carbon::now()->subDays(1))->count() }}) New User<small
                                                        class="float-right text-muted">Today</small></h4>
                                                <small>I feel great! Thanks team</small>
                                            </div>
                                        </a>
                                    </li>
                                    @endif
                                    @if ($order_summery->where('order_status', 'Cancel')->where('created_at', '>', Carbon\Carbon::now()->subDays(1))->count() != 0)
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="feeds-left"><i class="fa fa-question-circle text-warning"></i>
                                            </div>
                                            <div class="feeds-body">
                                                <h4 class="title text-warning">({{ $order_summery->where('order_status', 'Cancel')->where('created_at', '>', Carbon\Carbon::now()->subDays(1))->count() }}) Orders Cancel <small
                                                        class="float-right text-muted">Today</small></h4>
                                                <small>Your connection is not private</small>
                                            </div>
                                        </a>
                                    </li>
                                    @endif
                                    @if (App\Models\Order_return::where('return_status', 'Return Request')->where('created_at', '>', Carbon\Carbon::now()->subDays(1))->count() != 0)
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="feeds-left"><i class="fa fa-check text-danger"></i></div>
                                            <div class="feeds-body">
                                                <h4 class="title text-danger">({{ App\Models\Order_return::where('return_status', 'Return Request')->where('created_at', '>', Carbon\Carbon::now()->subDays(1))->count() }}) Orders Return <small
                                                        class="float-right text-muted">11:05</small></h4>
                                                <small>WE have fix all Design bug with Responsive</small>
                                            </div>
                                        </a>
                                    </li>
                                    @endif
                                    @if ($order_summery->where('order_status', 'Panding')->where('created_at', '>', Carbon\Carbon::now()->subDays(1))->count() != 0)
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="feeds-left"><i class="fa fa-shopping-basket"></i></div>
                                            <div class="feeds-body">
                                                <h4 class="title">({{ $order_summery->where('order_status', 'Panding')->where('created_at', '>', Carbon\Carbon::now()->subDays(1))->count() }}) New Orders <small
                                                        class="float-right text-muted">Today</small></h4>
                                                <small>You received a new oder.</small>
                                            </div>
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </li>
                            <li class="bg-danger"><a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="icon-menu"><i class="icon-power"></i></a></li>
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <div id="left-sidebar" class="sidebar">
            <div class="navbar-brand">
                <a href="{{route('admin.dashboard')}}"><img src="{{asset('admin')}}/images/icon-light.svg" alt="{{ $default_setting->app_name }} Logo"
                        class="img-fluid logo"><span>{{ $default_setting->app_name }}</span></a>
                <button type="button" class="btn-toggle-offcanvas btn btn-sm btn-default float-right"><i
                        class="lnr lnr-menu fa fa-chevron-circle-left"></i></button>
            </div>
            <div class="sidebar-scroll">
                <div class="user-account">
                    <div class="user_div">
                        <img src="{{asset('uploads/profile_photo')}}/{{Auth::guard('admin')->user()->profile_photo}}" class="user-photo" alt="User Profile Picture">
                    </div>
                    <div class="dropdown">
                        <span>Welcome,</span>
                        <a href="{{ route('admin.profile') }}" class="user-name">
                            <strong>{{Auth::guard('admin')->user()->name}}</strong>
                            <br>
                            <span class="badge badge-info">{{Auth::guard('admin')->user()->role}}</span>
                        </a>
                    </div>
                </div>
                @include('admin.layouts.navigation')
            </div>
        </div>

        <div id="main-content">
            <div class="block-header">
                <div class="row clearfix">
                    <div class="col-md-6 col-sm-12">
                        <h2>@yield('title_bar')</h2>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="icon-home"></i></a></li>
                            <li class="breadcrumb-item active">@yield('title_bar')</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                @yield('body_content')
            </div>
        </div>
    </div>

    <!-- Javascript -->
    <script src="{{asset('admin')}}/bundles/libscripts.bundle.js"></script>
    <script src="{{asset('admin')}}/bundles/vendorscripts.bundle.js"></script>
    <script src="{{asset('admin')}}/bundles/knob.bundle.js"></script><!-- Jquery Knob-->
    <script src="{{asset('admin')}}/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="{{asset('admin')}}/bundles/chartist.bundle.js"></script>
    <script src="{{asset('admin')}}/vendor/toastr/toastr.js"></script>
    <script src="{{asset('admin')}}/bundles/morrisscripts.bundle.js"></script><!-- Morris Plugin Js -->

    <script src="{{asset('admin')}}/bundles/mainscripts.bundle.js"></script>

    <script src="{{asset('admin')}}/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('admin')}}/vendor/datatables/js/dataTables.buttons.min.js"></script>
    <script src="{{asset('admin')}}/vendor/datatables/js/jszip.min.js"></script>
    <script src="{{asset('admin')}}/vendor/datatables/js/pdfmake.min.js"></script>
    <script src="{{asset('admin')}}/vendor/datatables/js/vfs_fonts.js"></script>
    <script src="{{asset('admin')}}/vendor/datatables/js/buttons.html5.min.js"></script>
    <script src="{{asset('admin')}}/vendor/datatables/js/buttons.print.min.js"></script>

    <script src="{{asset('admin')}}/vendor/printThis/printThis.js"></script>

    <script src="{{asset('admin')}}/vendor/select2/js/select2.min.js"></script>
    <script src="{{asset('admin')}}/vendor/summernote/summernote-bs4.min.js"></script>
    <script src="{{asset('admin')}}/vendor/sweetalert2/sweetalert2@11.js"></script>

    <!-- Custom Javascript -->
    @yield('custom_script')

    @include('admin.layouts.alert')

</body>
</html>
