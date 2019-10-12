<?php $route = \Request()->route()->getName(); ?>
<div class="ecaps-sidemenu-area">

    <!-- Desktop Logo -->
    <div class="ecaps-logo">
        <a href="{{url('/admin')}}">
            <img class="desktop-logo" src="{{ asset('/core-assets/logo/logo.png') }}" alt="Desktop Logo">
                <img class="small-logo" src="{{ asset('/core-assets/logo/logo.png') }}" alt="Mobile Logo">
        </a>
    </div>

    <!-- Side Nav -->
    <div class="ecaps-sidenav" id="ecapsSideNav">
        <!-- Side Menu Area -->
        <div class="side-menu-area">
            <!-- Sidebar Menu -->
            <nav>
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="sidemenu-user-profile d-flex align-items-center">
                        <div class="user-thumbnail">
                            <img src="{{ auth()->guard('admin')->user()->avatar ? asset(auth()->guard('admin')->user()->avatar) : asset('/core-assets/defaults/avatar.jpg') }}" alt="">
                        </div>
                        <div class="user-content">
                            <h6>{{ auth()->guard('admin')->user()->name }}</h6>
                        </div>
                    </li>
                    <li class="{{ $route === 'home' ? 'active' : '' }}"><a href="{{ route('home') }}"><i class="icon_lifesaver"></i> <span>Dashboard</span></a></li>
                    <li><a href="{{ route('admin users') }}"><i class="icon_contacts"></i> <span>Admin Users</span></a></li>
                    <li><a href="{{ route('all transactions') }}"><i class="icon_briefcase"></i> <span>Transactions</span></a></li>


                    <li class="treeview {{ $route === 'business payout' || $route === 'store payout' || $route === 'delivery payout' ? 'active' : '' }}">
                        <a href="javascript:void(0)"><i class="icon_cart"></i> <span>Payout Overview</span> <i class="fa fa-angle-right"></i></a>
                        <ul class="treeview-menu">
                                <li><a href="{{ route('business payout') }}">Business Payout</a></li>
                                <li><a href="{{ route('store payout') }}">Store Payout</a></li>
                        </ul>
                    </li>


                    <li class="treeview {{ $route === 'admin new orders' || $route === 'admin confirmed orders'  || $route === 'admin delivered orders' || $route === 'admin dispatched orders' ? 'active' : '' }}">
                        <a href="javascript:void(0)"><i class="icon_cart_alt"></i> <span>Manage Orders</span> <i class="fa fa-angle-right"></i></a>
                        <ul class="treeview-menu">
                                <li><a href="{{ route('admin new orders') }}">All Batch Orders</a></li>
                                <li><a href="{{ route('admin dispatched orders') }}">Dispatched Orders</a></li>
                                <li><a href="{{ route('admin delivered orders') }}">Confirmed Orders</a></li>
                        </ul>
                    </li>
                    <li class="treeview {{ $route === 'platform mentors' || $route === 'platform students' || $route === 'platform graduates' || $route === 'platform businesses' ? 'active' : '' }}">
                        <a href="javascript:void(0)"><i class="icon_cone_alt"></i> <span>Platform Users</span> <i class="fa fa-angle-right"></i></a>
                        <ul class="treeview-menu">
                                <li><a href="{{ route('platform mentors') }}">Mentors</a></li>
                                <li><a href="{{ route('platform students') }}">Students</a></li>
                                <li><a href="{{ route('platform graduates') }}">Graduates</a></li>
                                <li><a href="{{ route('platform businesses') }}">Businesses</a></li>
                        </ul>
                    </li>

                    <li class="treeview {{ $route === 'verification requests' ? 'active' : '' }}">
                        <a href="javascript:void(0)"><i class="icon_documents_alt"></i> <span>Verification</span> <i class="fa fa-angle-right"></i></a>
                        <ul class="treeview-menu">
                                <li><a href="{{ route('verification requests') }}">Requests</a></li>
                        </ul>
                    </li>

                    <li class="treeview {{ $route === 'create vocal profile' || $route === 'vocals view'? 'active' : '' }}">
                        <a href="javascript:void(0)"><i class="icon_tags_alt"></i> <span>Platform Focals</span> <i class="fa fa-angle-right"></i></a>
                        <ul class="treeview-menu">
                                <li><a href="{{ route('create vocal profile') }}">Create Focal Profile</a></li>
                                <li><a href="{{ route('vocals view') }}">View Focal Referrals</a></li>
                        </ul>
                    </li>

                    <li class="treeview {{ $route === 'compose' ? 'active' : '' }}">
                        <a href="javascript:void(0)"><i class="icon_mail_alt"></i> <span>Mail Manager</span> <i class="fa fa-angle-right"></i></a>
                        <ul class="treeview-menu">
                                <li><a href="{{ route('compose') }}">Compose</a></li>
                        </ul>
                    </li>

                    <li class="treeview {{ $route === 'help tips' || $route === 'adverts' ? 'active' : '' }}">
                        <a href="javascript:void(0)"><i class="icon_table"></i> <span>Manage Content</span> <i class="fa fa-angle-right"></i></a>
                        <ul class="treeview-menu">
                                <li><a href="{{ route('help tips') }}">Help Tips</a></li>
                                <li><a href="{{ route('adverts') }}">Manage Adverts</a></li>
                        </ul>
                    </li>

                    <li class="treeview {{ $route === 'help tips' || $route === 'adverts' ? 'active' : '' }}">
                        <a href="javascript:void(0)"><i class="icon_cog"></i> <span>General Settings</span> <i class="fa fa-angle-right"></i></a>
                        <ul class="treeview-menu">
                                <li><a href="{{ route('site data') }}">Site Data</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
