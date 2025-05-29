<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('page_title', config('app.name', 'Laravel'))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('dashAssets/dist/img/logo-dark.png') }}">
    <link rel="icon" href="{{ asset('dashAssets/dist/img/logo-dark.png') }}" type="image/x-icon">

    <!-- Morris Charts CSS -->
    <link href="{{ asset('dashAssets/vendors/morris.js/morris.css') }}" rel="stylesheet" type="text/css" />

    <!-- Toggles CSS -->
    <link href="{{ asset('dashAssets/vendors/jquery-toggles/css/toggles.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('dashAssets/vendors/jquery-toggles/css/themes/toggles-light.css') }}" rel="stylesheet"
        type="text/css">

    <!-- Toastr CSS -->
    <link href="{{ asset('dashAssets/vendors/jquery-toast-plugin/dist/jquery.toast.min.css') }}" rel="stylesheet"
        type="text/css">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 Bootstrap 4 Theme -->
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link href="{{ asset('dashAssets/dist/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('dashAssets/dist/css/loader.css') }}" rel="stylesheet" type="text/css">

    <!-- Vite CSS and JS -->
    @vite(['resources/css/app.css']) <!-- Only CSS, JS is loaded via script tags at the bottom -->
    @livewireStyles

    <!-- Page Specific Styles -->
    @yield('head_styles')
    @yield('head_scripts')

    <!-- Modern Navigation Styles -->
<style>
    :root {
        --primary-color: #6366f1;
        --primary-dark: #4f46e5;
        --secondary-color: #8b5cf6;
        --dark-bg: #0f172a;
        --dark-surface: #1e293b;
        --light-bg: #ffffff;
        --light-surface: #f8fafc;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --text-light: #ffffff;
        --border-color: #e2e8f0;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        background-color: var(--light-surface);
        color: var(--text-primary);
    }

    /* Modern Navigation Bar */
    .modern-navbar {
        background: var(--light-bg);
        box-shadow: var(--shadow-sm);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        transition: var(--transition);
    }

    .navbar-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 70px;
        position: relative; /* Ensure dropdowns are positioned correctly */
    }

    /* Logo Section */
    .navbar-brand {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
        font-weight: 600;
        font-size: 1.25rem;
        color: var(--text-primary);
    }

    .navbar-brand:hover {
        text-decoration: none;
        color: var(--text-primary);
    }

    .brand-logo {
        width: 40px;
        height: 40px;
        overflow: hidden;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    }

    .brand-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    /* Navigation Links */
    .nav-menu {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        list-style: none;
        margin: 0;
        padding: 0;
        position: relative; /* Ensure dropdowns are positioned relative to this */
    }

    .nav-item {
        position: relative;
    }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        color: var(--text-secondary);
        text-decoration: none;
        border-radius: 8px;
        transition: var(--transition);
        font-size: 0.95rem;
        font-weight: 500;
        white-space: nowrap; /* Prevent text wrapping */
    }

    .nav-link:hover {
        color: var(--primary-color);
        background-color: var(--light-surface);
        text-decoration: none;
    }

    .nav-link.active {
        color: var(--primary-color);
        background-color: #e0e7ff;
    }

    /* Dropdown Menu */
    .dropdown {
        position: relative;
    }

    .dropdown-toggle {
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .dropdown-toggle::after {
        content: "\f078";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        font-size: 0.75rem;
        transition: transform 0.3s ease;
    }

    .dropdown.active .dropdown-toggle::after {
        transform: rotate(180deg);
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        min-width: 220px;
        background: var(--light-bg);
        border-radius: 12px;
        box-shadow: var(--shadow-lg);
        padding: 0.5rem;
        margin-top: 0.5rem;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: var(--transition);
        border: none;
        z-index: 1050; /* Ensure dropdown appears above other elements */
    }

    .dropdown.active .dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        pointer-events: auto; /* Ensure menu is clickable */
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: var(--text-secondary);
        text-decoration: none;
        border-radius: 8px;
        transition: var(--transition);
        font-size: 0.9rem;
        white-space: nowrap; /* Prevent text wrapping */
    }

    .dropdown-item:hover {
        background-color: var(--light-surface);
        color: var(--primary-color);
        transform: translateX(4px);
        text-decoration: none;
    }

    .dropdown-item.active {
        background-color: #e0e7ff;
        color: var(--primary-color);
    }

    .dropdown-item i {
        min-width: 20px; /* Ensure icons align properly */
    }

    .dropdown-divider {
        height: 1px;
        background-color: var(--border-color);
        margin: 0.5rem 0;
    }

    /* Right Side Navigation */
    .navbar-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .icon-button {
        position: relative;
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: none;
        border-radius: 50%;
        color: var(--text-secondary);
        cursor: pointer;
        transition: var(--transition);
    }

    .icon-button:hover {
        background-color: var(--light-surface);
        color: var(--primary-color);
    }

    .notification-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 8px;
        height: 8px;
        background-color: #ef4444;
        border-radius: 50%;
        border: 2px solid var(--light-bg);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
        }
    }

    /* User Menu */
    .user-menu {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem;
        border-radius: 12px;
        cursor: pointer;
        transition: var(--transition);
    }

    .user-menu:hover {
        background-color: var(--light-surface);
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        overflow: hidden;
        position: relative;
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-avatar-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .user-status {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 10px;
        height: 10px;
        background-color: #10b981;
        border-radius: 50%;
        border: 2px solid var(--light-bg);
    }

    .user-info {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .user-name {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .user-role {
        font-size: 0.75rem;
        color: var(--text-secondary);
        text-transform: capitalize;
    }

    /* Mobile Menu Toggle */
    .mobile-menu-toggle {
        display: none;
        width: 42px;
        height: 42px;
        background: transparent;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        position: relative;
        transition: var(--transition);
    }

    .mobile-menu-toggle:hover {
        background-color: var(--light-surface);
    }

    .hamburger {
        width: 20px;
        height: 2px;
        background-color: var(--text-primary);
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        transition: var(--transition);
    }

    .hamburger::before,
    .hamburger::after {
        content: '';
        width: 20px;
        height: 2px;
        background-color: var(--text-primary);
        position: absolute;
        left: 0;
        transition: var(--transition);
    }

    .hamburger::before {
        top: -6px;
    }

    .hamburger::after {
        bottom: -6px;
    }

    .mobile-menu-toggle.active .hamburger {
        background-color: transparent;
    }

    .mobile-menu-toggle.active .hamburger::before {
        transform: rotate(45deg);
        top: 0;
    }

    .mobile-menu-toggle.active .hamburger::after {
        transform: rotate(-45deg);
        bottom: 0;
    }

    /* Mobile Navigation */
    .mobile-nav {
        position: fixed;
        top: 70px;
        left: -100%;
        width: 300px;
        height: calc(100vh - 70px);
        background-color: var(--light-bg);
        box-shadow: var(--shadow-lg);
        transition: var(--transition);
        overflow-y: auto;
        z-index: 999;
    }

    .mobile-nav.active {
        left: 0;
    }

    .mobile-nav-content {
        padding: 1.5rem;
    }

    .mobile-nav-menu {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin: 0;
        padding: 0;
    }

    .mobile-dropdown .dropdown-menu {
        position: static;
        opacity: 1;
        visibility: visible;
        transform: none;
        box-shadow: none;
        background-color: var(--light-surface);
        margin-top: 0.5rem;
        display: none;
    }

    .mobile-dropdown.active .dropdown-menu {
        display: block;
    }

    /* Overlay */
    .nav-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        opacity: 0;
        visibility: hidden;
        transition: var(--transition);
        z-index: 998;
    }

    .nav-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    /* Settings Panel Modern */
    .hk-settings-panel {
        position: fixed;
        top: 0;
        right: -400px;
        width: 400px;
        height: 100vh;
        background-color: var(--light-bg);
        box-shadow: var(--shadow-lg);
        transition: var(--transition);
        z-index: 1001;
        overflow-y: auto;
    }

    .hk-settings-panel.active {
        right: 0;
    }

    .settings-panel-wrap {
        padding: 2rem;
    }

    .settings-panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
    }

    .settings-panel-close {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background-color: var(--light-surface);
        color: var(--text-secondary);
        transition: var(--transition);
        cursor: pointer;
        text-decoration: none;
    }

    .settings-panel-close:hover {
        background-color: var(--primary-color);
        color: white;
        text-decoration: none;
    }

    /* Page Content */
    .hk-pg-wrapper {
        margin-top: 70px;
        min-height: calc(100vh - 70px);
        background-color: var(--light-surface);
    }

    .hk-pg-header {
        padding: 2rem 0 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .hk-pg-title {
        font-size: 1.875rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    /* Dark Mode Support */
    body.dark-mode {
        --light-bg: #0f172a;
        --light-surface: #1e293b;
        --text-primary: #f1f5f9;
        --text-secondary: #94a3b8;
        --border-color: #334155;
    }

    body.dark-mode .brand-logo {
        box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
    }

    body.dark-mode .dropdown-menu {
        border: 1px solid var(--border-color);
    }

    body.dark-mode .notification-badge,
    body.dark-mode .user-status {
        border-color: var(--light-bg);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .nav-menu {
            display: none;
        }

        .mobile-menu-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .navbar-actions .user-info {
            display: none;
        }

        .navbar-container {
            padding: 0 1rem;
        }

        .hk-settings-panel {
            width: 100%;
            right: -100%;
        }

        .hk-pg-header {
            padding: 1.5rem 0 1rem;
        }

        .hk-pg-title {
            font-size: 1.5rem;
        }
    }

    /* Footer Modern */
    .hk-footer-wrap {
        background-color: var(--light-bg);
        padding: 2rem 0;
        margin-top: 3rem;
        border-top: 1px solid var(--border-color);
    }

    .footer {
        color: var(--text-secondary);
    }

    .footer a {
        color: var(--primary-color);
        text-decoration: none;
        transition: var(--transition);
    }

    .footer a:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    /* Social Icons */
    .social-links {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .social-link {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background-color: var(--light-surface);
        color: var(--text-secondary);
        transition: var(--transition);
        text-decoration: none;
    }

    .social-link:hover {
        background-color: var(--primary-color);
        color: white;
        transform: translateY(-2px);
        text-decoration: none;
    }

    /* Modern Loader Styles - Bigger, Heart Pulse, No Rotation */
    .modern-loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: var(--light-bg);
        z-index: 2000;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity 0.4s;
    }
    .modern-loader-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 2rem;
    }
    .modern-loader-logo {
        position: relative;
        width: 340px;
        height: 340px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .modern-loader-logo img {
        width: 310px;
        height: 310px;
        object-fit: contain;
        border-radius: 24px;
        box-shadow: 0 0 32px 0 var(--primary-color);
        z-index: 2;
    }
    .modern-loader-glow {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 140px;
        height: 140px;
        {{--  background: radial-gradient(circle, var(--primary-color) 0%, transparent 70%);  --}}
        border-radius: 50%;
        transform: translate(-50%, -50%);
        filter: blur(16px);
        opacity: 0.7;
        z-index: 1;
        animation: heartPulse 1.2s infinite cubic-bezier(0.4,0,0.2,1);
    }
    @keyframes heartPulse {
        0% { transform: translate(-50%, -50%) scale(1); opacity: 0.7; }
        10% { transform: translate(-50%, -50%) scale(1.08); opacity: 1; }
        20% { transform: translate(-50%, -50%) scale(1.15); opacity: 1; }
        30% { transform: translate(-50%, -50%) scale(1.08); opacity: 0.9; }
        40% { transform: translate(-50%, -50%) scale(1); opacity: 0.7; }
        100% { transform: translate(-50%, -50%) scale(1); opacity: 0.7; }
    }
    .modern-loader-progress {
        width: 120px;
        height: 6px;
        background: var(--light-surface);
        border-radius: 3px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(99,102,241,0.08);
    }
    .modern-loader-bar {
        width: 0%;
        height: 100%;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        border-radius: 3px;
        animation: loaderBarAnim 1.8s cubic-bezier(0.4,0,0.2,1) infinite;
    }
    @keyframes loaderBarAnim {
        0% { width: 0%; }
        50% { width: 100%; }
        100% { width: 0%; }
    }
</style>

<style>
@media (min-width: 769px) {
    .dropdown.active > .dropdown-menu {
        opacity: 1 !important;
        visibility: visible !important;
        transform: translateY(0) !important;
        display: block !important;
        z-index: 1050;
    }
}
</style>
</head>

<body>
    <!-- Preloader -->
    <div class="preloader-it modern-loader">
        <div class="modern-loader-content">
            <div class="modern-loader-logo">
                <img src="{{ asset('dashAssets/dist/img/logo-light.png') }}" alt="Orion Logistics" class="logo-img">
                <span class="modern-loader-glow"></span>
            </div>
            <div class="modern-loader-progress">
                <div class="modern-loader-bar"></div>
            </div>
        </div>
    </div>
    <!-- /Preloader -->

    <!-- HK Wrapper -->
    <div class="hk-wrapper">
        <!-- Modern Navigation Bar -->
        <nav class="modern-navbar">
            <div class="navbar-container">
                <!-- Logo and Brand -->
                <a href="{{ route('dashboard') }}" class="navbar-brand">
                    <div class="brand-logo">
                        <img src="{{ asset('dashAssets/dist/img/logo-dark.png') }}" alt="Orion">
                    </div>
                    <span>Logistics</span>
                </a>

                <!-- Desktop Navigation Menu -->
                <ul class="nav-menu">
                    @if(auth()->user()->role == 'orionAdmin' || auth()->user()->role == 'orionManager')
                    <li class="nav-item dropdown" id="mainDashboardDropdown">
                        <a href="#" class="nav-link dropdown-toggle" onclick="toggleDropdown(event)">
                            <i class="fas fa-chart-line"></i>
                            <span>Dashboard</span>
                        </a>
                        <div class="dropdown-menu" id="dashboardDropdown">
                            <a href="{{ route('users.index') }}" class="dropdown-item {{ request()->is('*users*') ? 'active' : '' }}">
                                <i class="fas fa-users"></i>
                                <span>Users</span>
                            </a>
                            <a href="{{ route('branches.index') }}" class="dropdown-item {{ request()->is('*branches*') ? 'active' : '' }}">
                                <i class="fas fa-code-branch"></i>
                                <span>Branches</span>
                            </a>
                            <a href="{{ route('projects.index') }}" class="dropdown-item {{ request()->is('*projects*') ? 'active' : '' }}">
                                <i class="fas fa-project-diagram"></i>
                                <span>Projects</span>
                            </a>
                            <a href="{{ route('suppliers.index') }}" class="dropdown-item {{ request()->is('*suppliers*') ? 'active' : '' }}">
                                <i class="fas fa-truck"></i>
                                <span>Suppliers</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('categories.index') }}" class="dropdown-item {{ request()->is('*categories*') ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <span>Categories</span>
                            </a>
                            <a href="{{ route('operators.index') }}" class="dropdown-item {{ request()->is('*operators*') ? 'active' : '' }}">
                                <i class="fas fa-user-cog"></i>
                                <span>Operators</span>
                            </a>
                            <a href="{{ route('vehicles.index') }}" class="dropdown-item {{ request()->is('*vehicles*') ? 'active' : '' }}">
                                <i class="fas fa-car"></i>
                                <span>Vehicles</span>
                            </a>
                            <a href="{{ route('timesheet.index') }}" class="dropdown-item {{ request()->is('*timesheet*') ? 'active' : '' }}">
                                <i class="fas fa-clock"></i>
                                <span>Timesheet Dailies</span>
                            </a>
                        </div>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('analytics.index') }}" class="nav-link {{ request()->routeIs('analytics.index') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Analytics</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                            <i class="fas fa-file-alt"></i>
                            <span>Reports</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('invoices.index') }}" class="nav-link">
                            <i class="fas fa-file-invoice"></i>
                            <span>Invoices</span>
                        </a>
                    </li>
                </ul>

                <!-- Right Side Actions -->
                <div class="navbar-actions">
                    <!-- Settings Button -->
                    <button class="icon-button" id="settings_toggle_btn">
                        <i class="fas fa-cog"></i>
                    </button>

                    <!-- Notifications -->
                    <div class="dropdown">
                        <button class="icon-button" onclick="toggleDropdown(event)">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge"></span>
                        </button>
                        <div class="dropdown-menu" id="notificationDropdown" style="right: 0; left: auto; width: 320px;">
                            <div style="padding: 1rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: between; align-items: center;">
                                <h6 style="margin: 0; font-weight: 600;">Notifications</h6>
                                <a href="#" style="font-size: 0.875rem; color: var(--primary-color);">View all</a>
                            </div>
                            <div style="max-height: 300px; overflow-y: auto;">
                                <a href="#" class="dropdown-item" style="padding: 1rem; border-bottom: 1px solid var(--border-color);">
                                    <div style="display: flex; gap: 0.75rem;">
                                        <div class="user-avatar" style="width: 40px; height: 40px;">
                                            <div class="user-avatar-placeholder">EO</div>
                                        </div>
                                        <div style="flex: 1;">
                                            <div style="font-size: 0.875rem; color: var(--text-primary); margin-bottom: 0.25rem;">
                                                <strong>Evie Ono</strong> accepted your invitation to join the team
                                            </div>
                                            <div style="font-size: 0.75rem; color: var(--text-secondary);">12m ago</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="dropdown">
                        <div class="user-menu" onclick="toggleDropdown(event)">
                            <div class="user-avatar">
                                @if(Auth::user()->employee && Auth::user()->employee->image)
                                    <img src="{{ asset(Auth::user()->employee->image) }}" alt="{{ Auth::user()->name }}">
                                @else
                                    <div class="user-avatar-placeholder">
                                        {{ substr(Auth::user()->name, 0, 2) }}
                                    </div>
                                @endif
                                <span class="user-status"></span>
                            </div>
                            <div class="user-info">
                                <span class="user-name">{{ Auth::user()->name }}</span>
                                <span class="user-role">{{ Auth::user()->role }}</span>
                            </div>
                            <i class="fas fa-chevron-down" style="font-size: 0.75rem; color: var(--text-secondary);"></i>
                        </div>
                        <div class="dropdown-menu" id="userDropdown" style="right: 0; left: auto;">
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-user"></i>
                                <span>Profile</span>
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-cog"></i>
                                <span>Settings</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logout') }}" class="dropdown-item"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Log out</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>

                    <!-- Mobile Menu Toggle -->
                    <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                        <span class="hamburger"></span>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Mobile Navigation -->
        <div class="mobile-nav" id="mobileNav">
            <div class="mobile-nav-content">
                <ul class="mobile-nav-menu">
                    @if(auth()->user()->role == 'orionAdmin' || auth()->user()->role == 'orionManager')
                    <li class="nav-item mobile-dropdown">
                        <a href="#" class="nav-link dropdown-toggle" onclick="toggleMobileDropdown(event)">
                            <i class="fas fa-chart-line"></i>
                            <span>Dashboard</span>
                        </a>
                        <div class="dropdown-menu" id="mobileDashboard">
                            <a href="{{ route('users.index') }}" class="dropdown-item {{ request()->is('*users*') ? 'active' : '' }}">
                                <i class="fas fa-users"></i>
                                <span>Users</span>
                            </a>
                            <a href="{{ route('branches.index') }}" class="dropdown-item {{ request()->is('*branches*') ? 'active' : '' }}">
                                <i class="fas fa-code-branch"></i>
                                <span>Branches</span>
                            </a>
                            <a href="{{ route('projects.index') }}" class="dropdown-item {{ request()->is('*projects*') ? 'active' : '' }}">
                                <i class="fas fa-project-diagram"></i>
                                <span>Projects</span>
                            </a>
                            <a href="{{ route('suppliers.index') }}" class="dropdown-item {{ request()->is('*suppliers*') ? 'active' : '' }}">
                                <i class="fas fa-truck"></i>
                                <span>Suppliers</span>
                            </a>
                            <a href="{{ route('categories.index') }}" class="dropdown-item {{ request()->is('*categories*') ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <span>Categories</span>
                            </a>
                            <a href="{{ route('operators.index') }}" class="dropdown-item {{ request()->is('*operators*') ? 'active' : '' }}">
                                <i class="fas fa-user-cog"></i>
                                <span>Operators</span>
                            </a>
                            <a href="{{ route('vehicles.index') }}" class="dropdown-item {{ request()->is('*vehicles*') ? 'active' : '' }}">
                                <i class="fas fa-car"></i>
                                <span>Vehicles</span>
                            </a>
                            <a href="{{ route('timesheet.index') }}" class="dropdown-item {{ request()->is('*timesheet*') ? 'active' : '' }}">
                                <i class="fas fa-clock"></i>
                                <span>Timesheet Dailies</span>
                            </a>
                        </div>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('analytics.index') }}" class="nav-link {{ request()->routeIs('analytics.index') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Analytics</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                            <i class="fas fa-file-alt"></i>
                            <span>Reports</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('invoices.index') }}" class="nav-link">
                            <i class="fas fa-file-invoice"></i>
                            <span>Invoices</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Overlay -->
        <div class="nav-overlay" id="navOverlay" onclick="closeMobileMenu()"></div>

        <!-- Setting Panel -->
        <div class="hk-settings-panel" id="settingsPanel">
            <div class="settings-panel-wrap">
                <div class="settings-panel-head">
                    <img width="100" src="{{ asset('dashAssets/dist/img/logo-light.png') }}" alt="brand" />
                    <a href="javascript:void(0);" id="settings_panel_close" class="settings-panel-close">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
                <hr>
                <h6 class="mb-3">Theme</h6>
                <p class="font-14 text-secondary">Choose your preferred theme</p>
                <div class="d-flex gap-2 mb-4">
                    <button type="button" id="theme_light_select" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-sun"></i> Light Mode
                    </button>
                    <button type="button" id="theme_dark_select" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-moon"></i> Dark Mode
                    </button>
                </div>
                <hr>
                <h6 class="mb-3">Navigation Style</h6>
                <p class="font-14 text-secondary">Select navigation appearance</p>
                <div class="d-flex gap-2 mb-4">
                    <button type="button" id="nav_light_select" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-sun"></i> Light Nav
                    </button>
                    <button type="button" id="nav_dark_select" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-moon"></i> Dark Nav
                    </button>
                </div>
                <hr>
                <button id="reset_settings" class="btn btn-primary btn-block mt-4">Reset Settings</button>
            </div>
        </div>
        <!-- Main Content -->
        <div class="hk-pg-wrapper">
            <!-- Container -->
            <div class="container mt-xl-50 mt-sm-30 mt-15">
                <!-- Title -->
                <div class="hk-pg-header">
                    <div>
                        <h2 class="hk-pg-title">@yield('page_title', 'Dashboard')</h2>
                    </div>
                    <div class="d-flex mb-0 flex-wrap">
                        @hasSection('page_actions')
                            @yield('page_actions')
                        @else
                            <div class="btn-group btn-group-sm btn-group-rounded mb-15 mr-15" role="group">
                                <button type="button" class="btn btn-outline-primary">Company</button>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- /Title -->

                <!-- Page Content -->
                <div class="row">
                    <div class="col-xl-12">
                        @yield('content')
                    </div>
                </div>
                <!-- /Page Content -->

            </div>
            <!-- /Container -->

            {{--  <!-- Footer -->
            <div class="hk-footer-wrap container modern-footer">
                <footer class="footer py-4">
                    <div class="row align-items-center g-4 flex-column flex-md-row">
                        <div class="col-md-4 col-12 d-flex align-items-center justify-content-center justify-content-md-start mb-3 mb-md-0">
                            <a href="/" class="footer-brand d-flex align-items-center gap-2 text-decoration-none">
                                <div class="brand-logo" style="width: 48px; height: 48px;">
                                    <img src="{{ asset('dashAssets/dist/img/logo-dark.png') }}" alt="Orion" style="width:100%;height:100%;object-fit:contain;">
                                </div>
                                <span style="font-weight:700;font-size:1.25rem;color:var(--primary-color);">Orion Logistics</span>
                            </a>
                        </div>
                        <div class="col-md-4 col-12 d-flex justify-content-center mb-3 mb-md-0">
                            <ul class="footer-nav d-flex gap-4 list-unstyled mb-0">
                                <li><a href="/" class="footer-link">Home</a></li>
                                <li><a href="https://www.orioncc.com" class="footer-link" target="_blank">About</a></li>
                                <li><a href="#" class="footer-link">Contact</a></li>
                                <li><a href="#" class="footer-link">Careers</a></li>
                            </ul>
                        </div>
                        <div class="col-md-4 col-12 d-flex flex-column align-items-center align-items-md-end">
                            <div class="social-links d-flex gap-2 mb-2">
                                <a href="https://www.facebook.com/orioncontractingcompany" class="social-link" target="_blank" aria-label="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://www.linkedin.com/company/orion-contracting-company-llc" class="social-link" target="_blank" aria-label="LinkedIn">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="https://www.youtube.com/@orioncontracting9881" class="social-link" target="_blank" aria-label="YouTube">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </div>
                            <small class="text-secondary">Designed by <a href="https://www.orioncc.com" target="_blank" style="color:var(--primary-color);text-decoration:none;">Orion IT Department</a> © 2025</small>
                        </div>
                    </div>
                </footer>
            </div>
            <!-- /Footer -->  --}}
        </div>
        <!-- /Main Content -->
    </div>
    <!-- /HK Wrapper -->

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset('dashAssets/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('dashAssets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <!-- Slimscroll JavaScript -->
    <script src="{{ asset('dashAssets/dist/js/jquery.slimscroll.js') }}"></script>

    <!-- FeatherIcons JavaScript -->
    <script src="{{ asset('dashAssets/dist/js/feather.min.js') }}"></script>

    <!-- Toggles JavaScript -->
    <script src="{{ asset('dashAssets/vendors/jquery-toggles/toggles.min.js') }}"></script>
    <script src="{{ asset('dashAssets/dist/js/toggle-data.js') }}"></script>

    <!-- Toastr JS -->
    <script src="{{ asset('dashAssets/vendors/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>
    <script src="{{ asset('dashAssets/dist/js/toast-init.js') }}"></script>

    <!-- Counter Animation JavaScript -->
    <script src="{{ asset('dashAssets/vendors/waypoints/lib/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('dashAssets/vendors/jquery.counterup/jquery.counterup.min.js') }}"></script>

    <!-- Morris Charts JavaScript -->
    <script src="{{ asset('dashAssets/vendors/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('dashAssets/vendors/morris.js/morris.min.js') }}"></script>

    <!-- Easy pie chart JS -->
    <script src="{{ asset('dashAssets/vendors/easy-pie-chart/dist/jquery.easypiechart.min.js') }}"></script>

    <!-- Flot Charts JavaScript -->
    <script src="{{ asset('dashAssets/vendors/flot/excanvas.min.js') }}"></script>
    <script src="{{ asset('dashAssets/vendors/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('dashAssets/vendors/flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('dashAssets/vendors/flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('dashAssets/vendors/flot/jquery.flot.time.js') }}"></script>
    <script src="{{ asset('dashAssets/vendors/flot/jquery.flot.stack.js') }}"></script>
    <script src="{{ asset('dashAssets/vendors/flot/jquery.flot.crosshair.js') }}"></script>
    <script src="{{ asset('dashAssets/vendors/jquery.flot.tooltip/js/jquery.flot.tooltip.min.js') }}"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- EChartJS JavaScript -->
    <script src="{{ asset('dashAssets/vendors/echarts/dist/echarts-en.min.js') }}"></script>

    <!-- Init JavaScript -->
    <script src="{{ asset('dashAssets/dist/js/init.js') }}"></script>
    <script src="{{ asset('dashAssets/dist/js/dashboard2-data.js') }}"></script>

    <script>
        // Toggle dropdown menus
        function toggleDropdown(event) {
            event.preventDefault();
            event.stopPropagation();

            // Find the closest .dropdown parent
            const dropdown = event.target.closest('.dropdown');
            if (!dropdown) return;

            // Toggle only the clicked dropdown
            const isActive = dropdown.classList.contains('active');
            document.querySelectorAll('.dropdown').forEach(d => d.classList.remove('active'));
            if (!isActive) {
                dropdown.classList.add('active');
            }
        }

        // Toggle mobile dropdown
        function toggleMobileDropdown(event) {
            event.preventDefault();
            const dropdown = event.target.closest('.mobile-dropdown');
            dropdown.classList.toggle('active');
        }

        // Toggle mobile menu
        function toggleMobileMenu() {
            const mobileNav = document.getElementById('mobileNav');
            const overlay = document.getElementById('navOverlay');
            const toggle = document.querySelector('.mobile-menu-toggle');

            mobileNav.classList.toggle('active');
            overlay.classList.toggle('active');
            toggle.classList.toggle('active');
        }

        // Close mobile menu
        function closeMobileMenu() {
            const mobileNav = document.getElementById('mobileNav');
            const overlay = document.getElementById('navOverlay');
            const toggle = document.querySelector('.mobile-menu-toggle');

            mobileNav.classList.remove('active');
            overlay.classList.remove('active');
            toggle.classList.remove('active');
        }

        // Settings panel toggle
        document.getElementById('settings_toggle_btn').addEventListener('click', function() {
            document.getElementById('settingsPanel').classList.add('active');
        });

        document.getElementById('settings_panel_close').addEventListener('click', function() {
            document.getElementById('settingsPanel').classList.remove('active');
        });

        // Theme switcher
        document.getElementById('theme_light_select').addEventListener('click', function() {
            document.body.classList.remove('dark-mode');
            localStorage.setItem('theme', 'light');
        });

        document.getElementById('theme_dark_select').addEventListener('click', function() {
            document.body.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark');
        });

        // Load saved theme
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown').forEach(dropdown => {
                    dropdown.classList.remove('active');
                });
            }
        });

        // Add scroll effect to navbar
        let lastScroll = 0;
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.modern-navbar');
            const currentScroll = window.pageYOffset;

            if (currentScroll > 50) {
                navbar.style.boxShadow = 'var(--shadow-md)';
            } else {
                navbar.style.boxShadow = 'var(--shadow-sm)';
            }

            lastScroll = currentScroll;
        });

        // Close mobile menu on resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                closeMobileMenu();
            }
        });


    </script>

    <!-- Page Specific Scripts -->
    @push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2-livewire').select2({
                dropdownParent: $('body'),
                width: '100%'
            });
        });
    </script>
    @endpush
    {{-- End Page Specific Scripts section --}}

    @stack('modals')
    @stack('scripts') {{-- This is the primary stack for page-specific scripts --}}
    @livewireScripts
</body>

</html>
