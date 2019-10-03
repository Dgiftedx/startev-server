
 <!-- Top Header Area -->
 <header class="top-header-area d-flex align-items-center justify-content-between">
        <div class="left-side-content-area d-flex align-items-center">
            <!-- Mobile Logo -->
            <div class="mobile-logo mr-3 mr-sm-4">
                <a href="{{ url('/admin') }}"><img src="{{ asset('/core-assets/logo/logo_small.png') }}" alt="Mobile Logo"></a>
            </div>

            <!-- Triggers -->
            <div class="ecaps-triggers mr-1 mr-sm-3">
                <div class="menu-collasped" id="menuCollasped">
                    <i class="zmdi zmdi-menu"></i>
                </div>
                <div class="mobile-menu-open" id="mobileMenuOpen">
                    <i class="zmdi zmdi-menu"></i>
                </div>
            </div>

            <!-- Left Side Nav -->
            <ul class="left-side-navbar d-flex align-items-center">
                <li class="hide-phone app-search mr-15">
                    <form role="search" class=""><input type="text" placeholder="Search..." class="form-control"> <button type="submit" class="mr-0"><i class="fa fa-search"></i></button></form>
                </li>
            </ul>
        </div>

        <div class="right-side-navbar d-flex align-items-center justify-content-end">
            <!-- Mobile Trigger -->
            <div class="right-side-trigger" id="rightSideTrigger">
                <i class="ti-align-left"></i>
            </div>

            <!-- Top Bar Nav -->
            <ul class="right-side-content d-flex align-items-center">
                <!-- Full Screen Mode -->
                <li class="full-screen-mode ml-1">
                    <a href="javascript:" id="fullScreenMode"><i class="zmdi zmdi-fullscreen"></i></a>
                </li>

{{--                <li class="nav-item dropdown">--}}
{{--                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon_globe-2" aria-hidden="true"></i></button>--}}

{{--                    <div class="dropdown-menu dropdown-menu-right">--}}
{{--                        <!-- Top Message Area -->--}}
{{--                        <div class="top-message-area">--}}
{{--                            <!-- Heading -->--}}
{{--                            <div class="top-message-heading">--}}
{{--                                <div class="heading-title">--}}
{{--                                    <h6>Messages</h6>--}}
{{--                                </div>--}}
{{--                                <span>1 New</span>--}}
{{--                            </div>--}}
{{--                            <div class="message-box" id="messageBox">--}}
{{--                                <a href="#" class="dropdown-item">--}}
{{--                                    <i class="zmdi zmdi-email-open"></i>--}}
{{--                                    <span class="message-text">--}}
{{--                                        <span>6-hour video course on Angular</span>--}}
{{--                                        <span>3 min ago</span>--}}
{{--                                    </span>--}}
{{--                                </a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </li>--}}

{{--                <li class="nav-item dropdown">--}}
{{--                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon_lightbulb_alt" aria-hidden="true"></i> <span class="active-status"></span></button>--}}
{{--                    <div class="dropdown-menu dropdown-menu-right">--}}
{{--                        <!-- Top Notifications Area -->--}}
{{--                        <div class="top-notifications-area">--}}
{{--                            <!-- Heading -->--}}
{{--                            <div class="notifications-heading">--}}
{{--                                <div class="heading-title">--}}
{{--                                    <h6>Notifications</h6>--}}
{{--                                </div>--}}
{{--                                <span>2 New</span>--}}
{{--                            </div>--}}

{{--                            <div class="notifications-box" id="notificationsBox">--}}
{{--                                <a href="#" class="dropdown-item"><i class="ti-face-smile bg-success"></i><span>We've got something for you!</span></a>--}}
{{--                                <a href="#" class="dropdown-item"><i class="zmdi zmdi-notifications-active bg-danger"></i><span>Domain names expiring on Tuesday</span></a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </li>--}}

                <li class="nav-item dropdown">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ auth()->guard('admin')->user()->avatar ? asset(auth()->guard('admin')->user()->avatar) : asset('/core-assets/defaults/avatar.jpg') }}" alt=""/>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <!-- User Profile Area -->
                        <div class="user-profile-area">
                            <div class="user-profile-heading">
                                <!-- Thumb -->
                                <div class="profile-thumbnail">
                                        <img src="{{ auth()->guard('admin')->user()->avatar ? asset(auth()->guard('admin')->user()->avatar) : asset('/core-assets/defaults/avatar.jpg') }}" alt=""/>
                                </div>
                                <!-- Profile Text -->
                                <div class="profile-text">
                                    <h6>{{ auth()->guard('admin')->user()->name }}</h6>
                                    <span>{{ auth()->guard('admin')->user()->email }}</span>
                                </div>
                            </div>
                            <a href="{{ route('profile') }}" class="dropdown-item"><i class="ti-user text-default" aria-hidden="true"></i> My profile</a>
                            <a href="#" class="dropdown-item"><i class="ti-settings text-default" aria-hidden="true"></i> General settings</a>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();" class="dropdown-item">
                            <i class="ti-unlink text-warning" aria-hidden="true"></i> Sign-out</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </header>
