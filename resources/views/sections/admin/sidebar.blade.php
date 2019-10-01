<?php $route = \Request()->route()->getName(); ?>

<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-small-cap">Main</li>
                <li class="{{ $route === 'home' ? 'active' : '' }}"> <a class="waves-effect waves-dark" href="{{ route('home') }}"><i class="mdi mdi-gauge"></i>Dashboard </a></li>

                <li> <a class="has-arrow waves-effect waves-dark" href="javascript:;" aria-expanded="false"><i class="mdi mdi-shopping"></i><span class="hide-menu">Manage Orders</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ route('admin new orders') }}">New Orders</a></li>
                        <li><a href="{{ route('admin confirmed orders') }}">Confirmed Orders</a></li>
                        {{-- <li><a href="{{ route('admin delivered orders') }}">Delivered Orders</a></li> --}}
                        {{-- <li><a href="{{ route('admin cancelled orders') }}">Cancelled Orders</a></li> --}}
                    </ul>
                </li>

                <li> <a class="has-arrow waves-effect waves-dark" href="javascript:;" aria-expanded="false"><i class="mdi mdi-account-box"></i><span class="hide-menu">Admin Users</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ route('admin users') }}">Manage Users</a></li>

                    </ul>
                </li>
                <li> <a class="has-arrow waves-effect waves-dark" href="javascript:;" aria-expanded="false"><i class="mdi mdi-account-multiple"></i><span class="hide-menu">Platform Users</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ route('platform mentors') }}">Mentors</a></li>
                        <li><a href="{{ route('platform students') }}">Students</a></li>
                        <li><a href="{{ route('platform graduates') }}">Graduates</a></li>
                        <li><a href="{{ route('platform businesses') }}">Businesses</a></li>
                    </ul>
                </li>

                <li> <a class="has-arrow waves-effect waves-dark" href="javascript:;" aria-expanded="false"><i class="mdi mdi-book-multiple"></i><span class="hide-menu">Verification</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ route('verification requests') }}">Requests</a></li>
                    </ul>
                </li>

                <li> <a class="has-arrow waves-effect waves-dark" href="javascript:;" aria-expanded="false"><i class="mdi mdi-library"></i><span class="hide-menu">Platform Focals</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ route('create vocal profile') }}">Create Focal Profile</a></li>
                        <li><a href="{{ route('vocals view') }}">View Focal Referrals</a></li>
                    </ul>
                </li>

                <li> <a class="has-arrow waves-effect waves-dark" href="javascript:;" aria-expanded="false"><i class="mdi mdi-mailbox"></i><span class="hide-menu">Mail Manager</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ route('compose') }}">Compose</a></li>
                    </ul>
                </li>

                <li> <a class="has-arrow waves-effect waves-dark" href="javascript:;" aria-expanded="false"><i class="mdi mdi-bookmark-outline"></i><span class="hide-menu">Manage Contents</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ route('help tips') }}">Help Tips</a></li>
                        <li><a href="{{ route('adverts') }}">Manage Adverts</a></li>
                    </ul>
                </li>

            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
