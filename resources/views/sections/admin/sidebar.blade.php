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
                {{--<li> <a class="has-arrow waves-effect waves-dark" href="index3.html#" aria-expanded="false"><i class="mdi mdi-bullseye"></i><span class="hide-menu">Apps</span></a>--}}
                    {{--<ul aria-expanded="false" class="collapse">--}}
                        {{--<li><a href="app-calendar.html">Calendar</a></li>--}}
                        {{--<li><a href="app-chat.html">Chat app</a></li>--}}
                        {{--<li><a href="app-ticket.html">Support Ticket</a></li>--}}
                        {{--<li><a href="app-contact.html">Contact / Employee</a></li>--}}
                        {{--<li><a href="app-contact2.html">Contact Grid</a></li>--}}
                        {{--<li><a href="app-contact-detail.html">Contact Detail</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                {{--<li> <a class="has-arrow waves-effect waves-dark" href="index3.html#" aria-expanded="false"><i class="mdi mdi-email"></i><span class="hide-menu">Inbox</span></a>--}}
                    {{--<ul aria-expanded="false" class="collapse">--}}
                        {{--<li><a href="app-email.html">Mailbox</a></li>--}}
                        {{--<li><a href="app-email-detail.html">Mailbox Detail</a></li>--}}
                        {{--<li><a href="app-compose.html">Compose Mail</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}

            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->