<!-- Preloader -->
<div class="preloader-it">
    <div class="loader-wrap">
        <div class="loader-logo">
            <img src="{{ asset('dashAssets/dist/img/logo-light.png') }}" alt="Orion Logistics" class="logo-img">
        </div>
        <div class="loader-progress">
            <div class="loader-bar"></div>
        </div>
    </div>
</div>
<!-- /Preloader -->

<!-- HK Wrapper -->
<div class="hk-wrapper hk-horizontal-nav">

    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-xl navbar-dark fixed-top hk-navbar">
        <a id="navbar_toggle_btn" class="navbar-toggle-btn nav-link-hover" href="javascript:void(0);"><i
                class="fa fa-bars"></i></a>
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <img class="brand-img d-inline-block" width="100" src="{{ asset('dashAssets/dist/img/logo-dark.png') }}"
                alt="brand" />
        </a>
        <ul class="navbar-nav hk-navbar-content">

            <li class="nav-item">
                <a id="settings_toggle_btn" class="nav-link nav-link-hover" href="javascript:void(0);"><i
                        class="fa fa-cog"></i></a>
            </li>
            <li class="nav-item dropdown dropdown-notifications">
                <a class="nav-link dropdown-toggle no-caret"
                    style="display:flex;align-items:center;justify-content:center;" href="#" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bell"></i><span
                        class="badge-wrap"><span
                            class="badge badge-primary badge-indicator badge-indicator-sm badge-pill pulse"></span></span></a>
                <div class="dropdown-menu dropdown-menu-right" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                    <h6 class="dropdown-header">Notifications <a href="javascript:void(0);" class="">View all</a></h6>
                    <div class="notifications-nicescroll-bar">
                        <a href="javascript:void(0);" class="dropdown-item">
                            <div class="media">
                                <div class="media-img-wrap">
                                    <div class="avatar avatar-sm">
                                        <img width="40" height="40" src="{{ asset('dashAssets/dist/img/avatar1.jpg') }}"
                                            alt="user" class="avatar-img rounded-circle">
                                    </div>
                                </div>
                                <div class="media-body">
                                    <div>
                                        <div class="notifications-text"><span class="text-dark text-capitalize">Evie
                                                Ono</span> accepted your invitation to join the team</div>
                                        <div class="notifications-time">12m</div>
                                    </div>
                                </div>
                            </div>
                        </a>

                    </div>
                </div>
            </li>
            <li class="nav-item dropdown dropdown-authentication"
                style="display:flex;align-items:center;justify-content:center;">
                <a class="nav-link dropdown-toggle no-caret"
                    style="display:flex;align-items:center;justify-content:center;" href="#" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media">
                        <div class="media-img-wrap">
                            <div class="avatar">
                                @if(Auth::user()->employee && Auth::user()->employee->image)
                                <img src="{{ asset(Auth::user()->employee->image) }}" alt="{{ Auth::user()->name }}"
                                    class="avatar-img rounded-circle">
                                @else
                                <img src="{{ asset('dashAssets/img/avatar-placeholder.png') }}"
                                    alt="{{ Auth::user()->name }}" class="avatar-img rounded-circle">
                                @endif

                            </div>
                            <span class="badge badge-success badge-indicator"></span>
                        </div>
                        <div class="media-body">
                            <span>{{ Auth::user()->name }}<i class="zmdi zmdi-chevron-down"></i></span>
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="profile.html"><i
                            class="dropdown-icon zmdi zmdi-account"></i><span>Profile</span></a>

                    <div class="dropdown-divider"></div>
                    {{-- <div class="sub-dropdown-menu show-on-hover">
                        <a href="#" class="dropdown-toggle dropdown-item no-caret"><i
                                class="zmdi zmdi-check text-success"></i>Online</a>
                        <div class="dropdown-menu open-left-side">
                            <a class="dropdown-item" href="#"><i
                                    class="dropdown-icon zmdi zmdi-check text-success"></i><span>Online</span></a>
                            <a class="dropdown-item" href="#"><i
                                    class="dropdown-icon zmdi zmdi-circle-o text-warning"></i><span>Busy</span></a>
                            <a class="dropdown-item" href="#"><i
                                    class="dropdown-icon zmdi zmdi-minus-circle-outline text-danger"></i><span>Offline</span></a>
                        </div>
                    </div> --}}
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                            class="dropdown-icon zmdi zmdi-power"></i><span>Log out</span></a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </nav>


    <!--Horizontal Nav-->
    <nav class="hk-nav hk-nav-light">
        <a href="javascript:void(0);" id="hk_nav_close" class="hk-nav-close"><i class="fa fa-times"></i></a>
        <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 100%;">
            <div class="nicescroll-bar">
                <div class="navbar-nav-wrap">
                    <ul class="navbar-nav flex-row">
                        <li class="nav-item {{ request()->is('*dashboard*') ? 'active' : '' }}">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse"
                                data-target="#dash_drp">
                                <i class="fa fa-line-chart"></i>
                                <span class="nav-link-text">Dashboard</span>
                            </a>
                            <ul id="dash_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        @if(auth()->user()->role == 'orionAdmin' || auth()->user()->role ==
                                        'orionManager')
                                        <li class="nav-item {{ request()->is('*users*') ? 'active' : '' }}">
                                            <a class="nav-link" href="{{ route('users.index') }}">Users</a>
                                        </li>

                                        <li class="nav-item {{ request()->is('*branches*') ? 'active' : '' }}">
                                            <a class="nav-link" href="{{ route('branches.index') }}">Branches</a>
                                        </li>
                                        <li class="nav-item {{ request()->is('*projects*') ? 'active' : '' }}">
                                            <a class="nav-link" href="{{ route('projects.index') }}">Projects</a>
                                        </li>
                                        <li class="nav-item {{ request()->is('*suppliers*') ? 'active' : '' }}">
                                            <a class="nav-link" href="{{ route('suppliers.index') }}">Suppliers</a>
                                        </li>
                                        <li class="nav-item {{ request()->is('*categories*') ? 'active' : '' }}">
                                            <a class="nav-link" href="{{ route('categories.index') }}">Categories</a>
                                        </li>
                                        <li class="nav-item {{ request()->is('*operators*') ? 'active' : '' }}">
                                            <a class="nav-link" href="{{ route('operators.index') }}">Operators</a>
                                        </li>
                                        <li class="nav-item {{ request()->is('*vehicles*') ? 'active' : '' }}">
                                            <a class="nav-link" href="{{ route('vehicles.index') }}">Vehicles</a>
                                        </li>
                                        <li class="nav-item {{ request()->is('*timesheet*') ? 'active' : '' }}">
                                            <a class="nav-link" href="{{ route('timesheet.index') }}">Timesheet
                                                Dailies</a>
                                        </li>
                                        <li class="nav-item {{ request()->is('*invoices*') ? 'active' : '' }}">
                                            <a class="nav-link" href="{{ route('invoices.index') }}">Invoices</a>
                                        </li>
                                        @endif
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link link-with-indicator" href="javascript:void(0);" data-toggle="collapse"
                                data-target="#app_drp">
                                <span class="feather-icon"><span
                                        class="badge badge-primary badge-indicator badge-indicator-sm badge-pill"></span><i
                                        data-feather="package"></i></span>
                                <span class="nav-link-text">Application</span>
                            </a>
                            <ul id="app_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="chats.html">Chat</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="calendar.html">Calendar</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="email.html">Email</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="file-manager.html">File Manager</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse"
                                data-target="#pages_drp">
                                <span class="feather-icon"><i data-feather="file-text"></i></span>
                                <span class="nav-link-text">Pages</span>
                            </a>
                            <ul id="pages_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse"
                                                data-target="#auth_drp">
                                                Authentication
                                            </a>
                                            <ul id="auth_drp" class="nav flex-column collapse collapse-level-2">
                                                <li class="nav-item">
                                                    <ul class="nav flex-column">
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="javascript:void(0);"
                                                                data-toggle="collapse" data-target="#signup_drp">
                                                                Sign Up
                                                            </a>
                                                            <ul id="signup_drp"
                                                                class="nav flex-column collapse collapse-level-2">
                                                                <li class="nav-item">
                                                                    <ul class="nav flex-column">
                                                                        <li class="nav-item">
                                                                            <a class="nav-link"
                                                                                href="signup.html">Cover</a>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <a class="nav-link"
                                                                                href="signup-simple.html">Simple</a>
                                                                        </li>
                                                                    </ul>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="javascript:void(0);"
                                                                data-toggle="collapse" data-target="#signin_drp">
                                                                Login
                                                            </a>
                                                            <ul id="signin_drp"
                                                                class="nav flex-column collapse collapse-level-2">
                                                                <li class="nav-item">
                                                                    <ul class="nav flex-column">
                                                                        <li class="nav-item">
                                                                            <a class="nav-link"
                                                                                href="login.html">Cover</a>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <a class="nav-link"
                                                                                href="login-simple.html">Simple</a>
                                                                        </li>
                                                                    </ul>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="javascript:void(0);"
                                                                data-toggle="collapse" data-target="#recover_drp">
                                                                Recover Pwd
                                                            </a>
                                                            <ul id="recover_drp"
                                                                class="nav flex-column collapse collapse-level-2">
                                                                <li class="nav-item">
                                                                    <ul class="nav flex-column">
                                                                        <li class="nav-item">
                                                                            <a class="nav-link"
                                                                                href="forgot-password.html">Forgot
                                                                                Password</a>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <a class="nav-link"
                                                                                href="reset-password.html">Reset
                                                                                Password</a>
                                                                        </li>
                                                                    </ul>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="lock-screen.html">Lock Screen</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="404.html">Error 404</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="maintenance.html">Maintenance</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>

                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="profile.html">Profile</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="invoice.html">Invoice</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="gallery.html">Gallery</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="activity.html">Activity</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="faq.html">Faq</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse"
                                data-target="#user_drp">
                                <span class="feather-icon"><i data-feather="desktop"></i></span>
                                <span class="nav-link-text">User Interface</span>
                            </a>
                            <ul id="user_drp" class="nav flex-column collapse collapse-level-1">
                                <li class="nav-item">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse"
                                                data-target="#Components_drp">
                                                Components
                                            </a>
                                            <ul id="Components_drp" class="nav flex-column collapse collapse-level-2">
                                                <li class="nav-item">
                                                    <ul class="nav flex-column">
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="alerts.html">Alerts</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="avatar.html">Avatar</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="badge.html">Badge</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="buttons.html">Buttons</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="cards.html">Cards</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="carousel.html">Carousel</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="collapse.html">Collapse</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="dropdowns.html">Dropdown</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="list-group.html">List Group</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="modal.html">Modal</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="nav.html">Nav</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="navbar.html">Navbar</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="nestable.html">Nestable</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="pagination.html">Pagination</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="popovers.html">Popovers</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="progress.html">Progress</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="tooltip.html">Tooltip</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse"
                                                data-target="#content_drp">
                                                Content
                                            </a>
                                            <ul id="content_drp" class="nav flex-column collapse collapse-level-2">
                                                <li class="nav-item">
                                                    <ul class="nav flex-column">
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="typography.html">Typography</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="images.html">Images</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="media-object.html">Media
                                                                Object</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>

                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse"
                                                data-target="#utilities_drp">
                                                Utilities
                                            </a>
                                            <ul id="utilities_drp" class="nav flex-column collapse collapse-level-2">
                                                <li class="nav-item">
                                                    <ul class="nav flex-column">
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="background.html">Background</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="border.html">Border</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="colors.html">Colors</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="embeds.html">Embeds</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="icons.html">Icons</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="shadow.html">Shadow</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="sizing.html">Sizing</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="spacing.html">Spacing</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>

                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse"
                                                data-target="#forms_drp">
                                                Forms
                                            </a>
                                            <ul id="forms_drp" class="nav flex-column collapse collapse-level-2">
                                                <li class="nav-item">
                                                    <ul class="nav flex-column">
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="form-element.html">Form
                                                                Elements</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="input-groups.html">Input
                                                                Groups</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="form-layout.html">Form Layout</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="form-mask.html">Form Mask</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="form-validation.html">Form
                                                                Validation</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="form-wizard.html">Form Wizard</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="file-upload.html">File Upload</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="editor.html">Editor</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>

                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse"
                                                data-target="#tables_drp">
                                                Tables
                                            </a>
                                            <ul id="tables_drp" class="nav flex-column collapse collapse-level-2">
                                                <li class="nav-item">
                                                    <ul class="nav flex-column">
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="basic-table.html">Basic Table</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="data-table.html">Data Table</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="responsive-table.html">Responsive
                                                                Table</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="editable-table.html">Editable
                                                                Table</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>

                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse"
                                                data-target="#charts_drp">
                                                Charts
                                            </a>
                                            <ul id="charts_drp" class="nav flex-column collapse collapse-level-2">
                                                <li class="nav-item">
                                                    <ul class="nav flex-column">
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="line-charts.html">Line Chart</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="area-charts.html">Area Chart</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="bar-charts.html">Bar Chart</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="pie-charts.html">Pie Chart</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="realtime-charts.html">Realtime
                                                                Chart</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="mini-charts.html">Mini Chart</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>

                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="javascript:void(0);" data-toggle="collapse"
                                                data-target="#maps_drp">
                                                Maps
                                            </a>
                                            <ul id="maps_drp" class="nav flex-column collapse collapse-level-2">
                                                <li class="nav-item">
                                                    <ul class="nav flex-column">
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="google-map.html">Google Map</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="vector-map.html">Vector Map</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>

                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="documentation.html">
                                <span class="feather-icon"><i class="fa fa-book"></i></span>
                                <span class="nav-link-text">Documentation</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link link-with-badge" href="#">
                                <span class="feather-icon"><i class="fa fa-eye"></i></span>
                                <span class="nav-link-text">Changelog</span>
                                <span class="badge badge-success badge-sm badge-pill">v 1.0</span>
                            </a>
                        </li> --}}
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div id="hk_nav_backdrop" class="hk-nav-backdrop"></div>
    <!--/Horizontal Nav-->

    <!-- Setting Panel -->
    <div class="hk-settings-panel">
        <div class="nicescroll-bar position-relative">
            <div class="settings-panel-wrap">
                <div class="settings-panel-head">
                    <img class="brand-img d-inline-block align-top" width="100"
                        src="{{ asset('dashAssets/dist/img/logo-light.png') }}" alt="brand" />
                    <a href="javascript:void(0);" id="settings_panel_close" class="settings-panel-close"><i
                            class="fa fa-times"></i></a>
                </div>
                <hr>

                <h6 class="mb-5">Style</h6>
                <p class="font-14">Menu comes in two modes: dark & light</p>
                <div class="button-list hk-nav-select mb-10">
                    <button type="button" id="nav_light_select"
                        class="btn btn-outline-primary btn-sm btn-wth-icon icon-wthot-bg"><span class="icon-label"><i
                                class="fa fa-sun-o"></i> </span><span class="btn-text">Light Mode</span></button>
                    <button type="button" id="nav_dark_select"
                        class="btn btn-outline-light btn-sm btn-wth-icon icon-wthot-bg"><span class="icon-label"><i
                                class="fa fa-moon-o"></i> </span><span class="btn-text">Dark Mode</span></button>
                </div>
                <hr>
                <h6 class="mb-5">Top Nav</h6>
                <p class="font-14">Choose your liked color mode</p>
                <div class="button-list hk-navbar-select mb-10">
                    <button type="button" id="navtop_light_select"
                        class="btn btn-outline-light btn-sm btn-wth-icon icon-wthot-bg"><span class="icon-label"><i
                                class="fa fa-sun-o"></i> </span><span class="btn-text">Light Mode</span></button>
                    <button type="button" id="navtop_dark_select"
                        class="btn btn-outline-primary btn-sm btn-wth-icon icon-wthot-bg"><span class="icon-label"><i
                                class="fa fa-moon-o"></i> </span><span class="btn-text">Dark Mode</span></button>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <h6>Scrollable Header</h6>
                    <div class="toggle toggle-sm toggle-simple toggle-light toggle-bg-primary scroll-nav-switch"></div>
                </div>
                <button id="reset_settings" class="btn btn-primary btn-block btn-reset mt-30">Reset</button>
            </div>
        </div>
        <img class="d-none" width="100" src="{{ asset('dashAssets/dist/img/logo-light.png') }}" alt="brand" />
        <img class="d-none" width="100" src="{{ asset('dashAssets/dist/img/logo-dark.png') }}" alt="brand" />
    </div>
    <!-- /Setting Panel -->

    <!-- Main Content -->
    <div class="hk-pg-wrapper">
        <!-- Container -->
        <div class="container mt-xl-50 mt-sm-30 mt-15">
            <!-- Title -->
            <div class="hk-pg-header">
                <div>
                    <h2 class="hk-pg-title font-weight-600">@yield('page_title', 'Dashboard')</h2>
                </div>
                <div class="d-flex mb-0 flex-wrap">
                    @hasSection('page_actions')
                    @yield('page_actions')
                    @else
                    <div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
                        <button type="button" class="btn btn-outline-primary">company</button>

                    </div>

                    @endif
                </div>
            </div>
            <!-- /Title -->