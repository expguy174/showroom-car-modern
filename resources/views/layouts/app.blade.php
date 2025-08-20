<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    @yield('meta')
    @stack('head')
    <link rel="canonical" href="{{ url()->current() }}" />
    @if(app()->environment('production'))
    <meta name="robots" content="index, follow">
    @else
    <meta name="robots" content="noindex, nofollow">
    @endif
    <meta name="description" content="Showroom ô tô – kho xe cập nhật, lái thử, tài chính, bảo dưỡng, phụ kiện.">
    <meta property="og:title" content="@yield('title', 'AutoLux Showroom')">
    <meta property="og:description" content="Khám phá mẫu xe mới nhất, đặt lái thử và nhận báo giá nhanh.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Prevent nav overlap: allow center menu to shrink gracefully */
        @media (min-width: 1024px){
            #main-nav .center-menu{ min-width: 0; }
        }

        /* Image optimization */
        .lazy-image {
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        
        .lazy-image.loaded {
            opacity: 1;
        }
        
        .image-placeholder {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Image error handling */
        .image-error {
            background-color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 0.875rem;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Smooth transitions - chỉ áp dụng cho các element cụ thể */
        .transition-smooth {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        /* Dropdown wrapper styling */
        .car-dropdown-wrapper, .profile-dropdown-wrapper {
            position: relative;
            display: inline-block;
        }
        
        /* Custom hover behavior for dropdowns */
        .car-dropdown-wrapper:hover .car-dropdown-menu,
        .profile-dropdown-wrapper:hover .profile-dropdown-menu {
            display: block !important;
            opacity: 1 !important;
        }
        
        /* Add a dropdown bridge to cover the gap */
        .dropdown-bridge {
            position: absolute;
            height: 10px;
            width: 100%;
            top: 100%;
            left: 0;
            background-color: transparent;
        }
        
        /* Position the dropdown menu directly below the button */
        .car-dropdown-menu, .profile-dropdown-menu {
            margin-top: 0 !important;
            top: calc(100% + 6px);
        }

        /* Toast Notification Styles */
        #toast-container {
            z-index: 9999;
        }

        #toast-container > div {
            transform: translateX(100%);
            opacity: 0;
            margin-bottom: 0.5rem;
            min-width: 300px;
            max-width: 400px;
        }

        #toast-container > div.show {
            transform: translateX(0);
            opacity: 1;
        }

        /* Toast animation */
        .toast-enter {
            transform: translateX(100%);
            opacity: 0;
        }

        .toast-enter-active {
            transform: translateX(0);
            opacity: 1;
            transition: all 0.3s ease-in-out;
        }

        .toast-exit {
            transform: translateX(0);
            opacity: 1;
        }

        .toast-exit-active {
            transform: translateX(100%);
            opacity: 0;
            transition: all 0.3s ease-in-out;
        }

        /* Grid Layout Fixes */
        .grid {
            display: grid !important;
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        @media (min-width: 640px) {
            .sm\:grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 768px) {
            .md\:grid-cols-3 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .lg\:grid-cols-4 {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        /* Ensure cards don't break layout */
        .grid > div {
            min-width: 0;
            width: 100%;
        }

        /* Force grid display */
        .grid.grid-cols-1.sm\:grid-cols-2.md\:grid-cols-3.lg\:grid-cols-4 {
            display: grid !important;
            grid-template-columns: repeat(1, 1fr);
        }

        @media (min-width: 640px) {
            .grid.grid-cols-1.sm\:grid-cols-2.md\:grid-cols-3.lg\:grid-cols-4 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 768px) {
            .grid.grid-cols-1.sm\:grid-cols-2.md\:grid-cols-3.lg\:grid-cols-4 {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .grid.grid-cols-1.sm\:grid-cols-2.md\:grid-cols-3.lg\:grid-cols-4 {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        /* Back to Top Button Styles */
        #back-to-top {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Custom hover for navigation links */
        .nav-link-cars:hover {
            color: #2563eb !important;
        }
        
        .nav-link-cars:hover span {
            color: #2563eb !important;
        }

        .nav-link-accessories:hover {
            color: #059669 !important;
        }
        
        .nav-link-accessories:hover span {
            color: #059669 !important;
        }

        .nav-link-blogs:hover {
            color: #9333ea !important;
        }
        
        .nav-link-blogs:hover span {
            color: #9333ea !important;
        }

        .nav-link-test-drives:hover {
            color: #ea580c !important;
        }
        
        .nav-link-test-drives:hover span {
            color: #ea580c !important;
        }

        .nav-link-about:hover {
            color: #374151 !important;
        }
        
        .nav-link-about:hover span {
            color: #374151 !important;
        }

        .nav-link-contact:hover {
            color: #dc2626 !important;
        }
        
        .nav-link-contact:hover span {
            color: #dc2626 !important;
        }

        /* Modern Simple Dropdown Design */
        .dropdown-group {
            position: relative;
        }

        .dropdown-group button {
            position: relative;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .dropdown-group button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-color: rgba(59, 130, 246, 0.2);
        }

        /* Unified hover for all dropdown buttons (indigo theme) */
        .dropdown-group button:hover {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
        }

        /* Active state when dropdown is open */
        .dropdown-group:hover button {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
        }

        /* Modern Dropdown Menu */
        .dropdown-menu {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 
                0 20px 25px -5px rgba(0, 0, 0, 0.1),
                0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-radius: 12px;
            padding: 4px;
            transform: translateY(-8px);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .dropdown-group:hover .dropdown-menu {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }

        /* Dropdown Menu Items hover unify */
        .dropdown-menu a {
            position: relative;
            border-radius: 8px;
            transition: all 0.2s ease;
            background: transparent;
        }

        .dropdown-menu a:hover {
            background: rgba(59, 130, 246, 0.08);
            transform: translateY(-1px);
        }

        .dropdown-menu a:hover *,
        .dropdown-menu a:hover i.fas {
            color: #1d4ed8 !important;
        }

        /* Chevron animation */
        .dropdown-group button .fa-chevron-down {
            transition: transform 0.3s ease;
        }

        .dropdown-group:hover button .fa-chevron-down {
            transform: rotate(180deg);
        }
        
        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }

        /* Modern label styling for nav */
        .nav-label {
            letter-spacing: 0.02em;
            font-weight: 600;
        }

        @media (min-width: 1024px) {
            .nav-label { font-size: 0.85rem; }
        }

        /* Responsive optimization: hide labels at lg to save space, show at xl */
        @media (min-width: 1024px) and (max-width: 1279.98px) {
            .nav-label { display: none; }
            #main-nav .center-menu { gap: 0.125rem; }
            #desktop-search-input { width: 8rem; }
            #desktop-search-input:focus { width: 14rem; }
        }

        /* Specific hover effects for different items */
        .dropdown-menu a[href*="cars"]:hover {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }

        .dropdown-menu a[href*="cars"]:hover .fas {
            color: white;
        }

        .dropdown-menu a[href*="accessories"]:hover {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }

        .dropdown-menu a[href*="accessories"]:hover .fas {
            color: white;
        }

        .dropdown-menu a[href*="test_drives"]:hover {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(249, 115, 22, 0.3);
        }

        .dropdown-menu a[href*="test_drives"]:hover .fas {
            color: white;
        }

        .dropdown-menu a[href*="blogs"]:hover {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
        }

        .dropdown-menu a[href*="blogs"]:hover .fas {
            color: white;
        }

        .dropdown-menu a[href*="about"]:hover {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(107, 114, 128, 0.3);
        }

        .dropdown-menu a[href*="about"]:hover .fas {
            color: white;
        }

        .dropdown-menu a[href*="contact"]:hover {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
        }

        .dropdown-menu a[href*="contact"]:hover .fas {
            color: white;
        }

        /* Override default hover styles */
        .dropdown-menu a:hover {
            background: none !important;
            color: inherit !important;
            box-shadow: none !important;
        }

        .dropdown-menu a:hover .fas {
            color: inherit !important;
        }

        /* Mobile Dropdown Styles */
        .mobile-dropdown-btn {
            position: relative;
            overflow: hidden;
        }
        /* Drawer sizing helpers */
        .drawer-panel { will-change: transform; }

        .mobile-dropdown-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
            transition: left 0.5s;
        }

        .mobile-dropdown-btn:hover::before {
            left: 100%;
        }

        .mobile-dropdown-content {
            display: none;
            transition: all 0.3s ease-in-out;
        }

        .mobile-dropdown-content.show {
            display: block;
        }

        .mobile-dropdown-btn.active i.fa-chevron-down {
            transform: rotate(180deg);
        }

        .mobile-dropdown-btn.active {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
        }

        .mobile-dropdown-btn.active i {
            color: white;
        }

        .mobile-dropdown-btn.active:hover {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white !important;
        }

        .mobile-dropdown-btn.active:hover i {
            color: white !important;
        }

        /* Mobile Search Animation */
        #mobile-search {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }

        #mobile-search.show {
            max-height: 100px;
        }

        /* Mobile Menu Animation (handled inside nav component via drawer-panel translateX) */

        /* Mobile Menu Button Animation */
        /* removed legacy menu-btn styles */

        /* Mobile Responsive Improvements */
        @media (max-width: 1024px) {
            .mobile-dropdown-content {
                background: rgba(249, 250, 251, 0.8);
                backdrop-filter: blur(10px);
                border-radius: 12px;
                margin: 8px 0 8px 16px;
                padding: 8px;
            }

            /* Tablet/Mobile Icon Hover Effects */
            .lg\:hidden a:hover i,
            .lg\:hidden button:hover i {
                color: #3b82f6 !important;
            }

            .lg\:hidden a:hover {
                color: #3b82f6 !important;
            }

            /* Mobile Search Bar Fix */
            #mobile-search .relative {
                position: relative;
            }

            #mobile-search button {
                position: absolute;
                right: 4px;
                top: 50%;
                transform: translateY(-50%);
                margin: 0;
            }

            #mobile-search input, #mobile-menu-sheet {
                padding-right: 60px;
            }

        /* Compact search width transition */
        #desktop-search-input { transition: width .2s ease; }
        @media (min-width: 1024px) and (max-width: 1279.98px) {
            #desktop-search-input { width: 9rem; }
        }
        }

        @media (max-width: 640px) {
            .mobile-dropdown-content {
                margin: 4px 0 4px 8px;
                padding: 4px;
            }

            /* Ensure menu button is always visible */
            /* removed legacy menu-btn sizing */

            /* Search bar responsive adjustments */
            .max-w-xs {
                max-width: 200px;
            }
        }

        @media (max-width: 480px) {
            /* Extra small screen adjustments */
            .max-w-xs {
                max-width: 160px;
            }

            /* Reduce button padding on very small screens */
            .px-4 {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
        }

        @media (max-width: 360px) {
            /* Very small screen adjustments */
            .max-w-xs {
                max-width: 120px;
            }

            /* Further reduce button padding */
            .px-4 {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
        }

        /* Wishlist hover effect */
        .js-wishlist-toggle {
            transition: all 0.3s ease-in-out;
            position: relative;
        }
        
        #car-variant-page .js-wishlist-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,.12);
        }
        #car-variant-page .js-wishlist-toggle i,
        #car-variant-page .add-to-cart-btn i {
            transition: transform .3s ease;
        }

        /* Unified CTA buttons for car-variant page */
        #car-variant-page .action-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
            padding: .9rem 1.25rem; border-radius: 1rem; font-weight: 700; line-height: 1;
            transition: transform .25s ease, box-shadow .25s ease, background-color .25s ease, color .25s ease, border-color .25s ease;
            box-shadow: 0 6px 16px rgba(17, 24, 39, .08);
            will-change: transform;
        }
        #car-variant-page .action-btn i { font-size: 1.125rem; transition: transform .25s ease; }
        @media (min-width: 768px) { #car-variant-page .action-btn { padding: 1rem 1.25rem; } #car-variant-page .action-btn i { font-size: 1.25rem; } }
        #car-variant-page .action-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 24px rgba(17,24,39,.12); }
        #car-variant-page .action-btn:active { transform: translateY(0); box-shadow: 0 6px 16px rgba(17,24,39,.08); }
        #car-variant-page .action-btn i { transform: translateZ(0); }
        #car-variant-page .action-btn:hover i { transform: scale(1.1); }

        #car-variant-page .action-primary {
            color: #fff; background-image: linear-gradient(90deg, #4f46e5 0%, #4338ca 100%);
        }
        #car-variant-page .action-primary:hover { background-image: linear-gradient(90deg, #4338ca 0%, #3730a3 100%); }
        #car-variant-page .action-primary:focus-visible { outline: 2px solid #a5b4fc; outline-offset: 2px; }

        #car-variant-page .action-ghost {
            background: #fff; color: #111827; border: 2px solid #e5e7eb;
        }
        #car-variant-page .action-ghost:hover { color: #3730a3; border-color: #c7d2fe; }
        #car-variant-page .action-ghost:focus-visible { outline: 2px solid #a5b4fc; outline-offset: 2px; }
        
        /* Unified CTA buttons for accessory page */
        .accessory-show-page .action-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
            padding: .9rem 1.25rem; border-radius: 1rem; font-weight: 700; line-height: 1;
            transition: transform .25s ease, box-shadow .25s ease, background-color .25s ease, color .25s ease, border-color .25s ease;
            box-shadow: 0 6px 16px rgba(17, 24, 39, .08);
            will-change: transform;
        }
        .accessory-show-page .action-btn i { font-size: 1.125rem; transition: transform .25s ease; }
        @media (min-width: 768px) { .accessory-show-page .action-btn { padding: 1rem 1.25rem; } .accessory-show-page .action-btn i { font-size: 1.25rem; } }
        .accessory-show-page .action-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 24px rgba(17,24,39,.12); }
        .accessory-show-page .action-btn:active { transform: translateY(0); box-shadow: 0 6px 16px rgba(17,24,39,.08); }
        .accessory-show-page .action-btn i { transform: translateZ(0); }
        .accessory-show-page .action-btn:hover i { transform: scale(1.1); }

        .accessory-show-page .action-primary {
            color: #fff; background-image: linear-gradient(90deg, #4f46e5 0%, #4338ca 100%);
        }
        .accessory-show-page .action-primary:hover { background-image: linear-gradient(90deg, #4338ca 0%, #3730a3 100%); }
        .accessory-show-page .action-primary:focus-visible { outline: 2px solid #a5b4fc; outline-offset: 2px; }

        .accessory-show-page .action-ghost {
            background: #fff; color: #111827; border: 2px solid #e5e7eb;
        }
        .accessory-show-page .action-ghost:hover { color: #3730a3; border-color: #c7d2fe; }
        .accessory-show-page .action-ghost:focus-visible { outline: 2px solid #a5b4fc; outline-offset: 2px; }
        
        .js-wishlist-toggle.processing {
            pointer-events: none;
            opacity: 0.7;
        }
        
        .js-wishlist-toggle i.fa-heart {
            transition: color 0.3s ease-in-out;
        }

        /* Navigation count badges */
        #wishlist-count-badge,
        #cart-count-badge,
        #wishlist-count-badge-mobile,
        #cart-count-badge-mobile {
            transition: all 0.3s ease-in-out;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            line-height: 1 !important;
        }
        
        /* Show badges when not hidden */
        #wishlist-count-badge:not(.hidden),
        #cart-count-badge:not(.hidden),
        #wishlist-count-badge-mobile:not(.hidden),
        #cart-count-badge-mobile:not(.hidden) {
            display: flex !important;
        }
        
        /* Hide badges when hidden class is present */
        #wishlist-count-badge.hidden,
        #cart-count-badge.hidden,
        #wishlist-count-badge-mobile.hidden,
        #cart-count-badge-mobile.hidden {
            display: none !important;
        }
        
        #wishlist-count-badge:not(.hidden),
        #cart-count-badge:not(.hidden),
        #wishlist-count-badge-mobile:not(.hidden),
        #cart-count-badge-mobile:not(.hidden) {
            animation: countPulse 0.6s ease-in-out;
        }
        
        @keyframes countPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        
        /* removed legacy mobile menu styles */
        

        /* Remove focus outline from menu button */
        /* removed legacy menu-btn focus styles */

        /* Count badges in mobile menu */
        /* removed legacy mobile drawer hover styles */

        /* Compare bar slide-up animation */
        #compare-bar.compare-hidden {
            opacity: 0;
            transform: translate(-50%, 20px);
            transition: all .25s ease;
            pointer-events: none;
        }
        #compare-bar.compare-visible {
            opacity: 1;
            transform: translate(-50%, 0);
            pointer-events: auto;
        }

        /* Compare Modal modern styles */
        .cmp-modal .modal-panel {
            animation: cmpFadeIn .18s ease-out;
        }
        .cmp-modal .modal-panel.cmp-open {
            animation: cmpZoomIn .22s cubic-bezier(.2,.8,.2,1);
        }
        @keyframes cmpFadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes cmpZoomIn {
            from { opacity: 0; transform: translateY(10px) scale(.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        /* Sticky header shadow when scrolled */
        .cmp-table thead.sticky {
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
        }
        /* Scroll snap for better mobile reading */
        .cmp-scroll {
            scroll-snap-type: x mandatory;
        }
        .cmp-snap {
            scroll-snap-align: start;
        }
    </style>
    {{-- Preload main CSS to reduce FOUC and load critical styles first --}}
    @php try { $appCss = Vite::asset('resources/css/app.css'); } catch (\Throwable $e) { $appCss = null; } @endphp
    @if($appCss)
        <link rel="preload" href="{{ $appCss }}" as="style" />
    @endif
    {{-- Critical, tiny CSS first (only if built in manifest) --}}
    @php $hasCritical=false; try { Vite::asset('resources/css/critical.css'); $hasCritical=true; } catch (\Throwable $e) { $hasCritical=false; } @endphp
    @if($hasCritical)
        @vite(['resources/css/critical.css'])
    @endif
    {{-- Main bundle --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    
</head>

<body class="bg-white text-gray-900">

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <!-- Message Container -->
    <div id="message-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <!-- Back to Top Button -->
    <button id="back-to-top" 
            class="fixed bottom-6 right-6 z-40 w-12 h-12 bg-indigo-600 text-white rounded-full shadow-lg hover:bg-indigo-700 transition-all duration-300 opacity-0 invisible transform translate-y-10 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            aria-label="Trở lại đầu trang">
        <i class="fas fa-chevron-up text-lg"></i>
    </button>

    <!-- New Navigation Component -->
    @include('components.nav')

    <!-- Global Compare FAB -->
    <button id="compare-fab" class="hidden fixed right-4 bottom-16 z-40 px-4 py-3 rounded-full shadow-lg bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-purple-400">
        <i class="fas fa-balance-scale mr-2"></i>
        <span>So sánh (<span id="compare-fab-count">0</span>)</span>
    </button>

    <!-- Compare Modal -->
    <div id="compare-modal" class="cmp-modal fixed inset-0 z-[10000] bg-black/60 hidden" style="display:none; align-items:center; justify-content:center;" role="dialog" aria-modal="true" aria-labelledby="compare-modal-title">
        <div class="modal-panel cmp-open bg-white/95 backdrop-blur-xl rounded-xl sm:rounded-2xl shadow-[0_20px_60px_rgba(0,0,0,.25)] w-full max-w-full md:max-w-5xl lg:max-w-6xl p-4 md:p-6 relative border border-white/40">
          <button type="button" class="absolute top-3 right-3 text-gray-400 hover:text-red-500" id="compare-modal-close" aria-label="Đóng so sánh"><i class="fas fa-times text-xl"></i></button>
            <div class="mb-4 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow"><i class="fas fa-balance-scale"></i></div>
              <div>
                <h3 id="compare-modal-title" class="text-xl md:text-2xl font-bold text-gray-900 leading-tight">So sánh nhanh</h3>
                <p class="text-xs md:text-sm text-gray-500">Chọn tối đa 4 mẫu để so sánh các thông số chính</p>
              </div>
                    </div>
            <div class="flex items-center gap-2 ml-auto">
              <label for="compare-toggle-diff" class="inline-flex items-center gap-2 text-xs md:text-sm text-gray-600 select-none cursor-pointer">
                <input type="checkbox" id="compare-toggle-diff" class="w-4 h-4 text-indigo-600 rounded border-gray-300">
                <span>Chỉ hiển thị khác nhau</span>
              </label>
              <button id="compare-clear-all-mobile" class="sm:hidden inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-red-600 hover:text-white hover:bg-red-600 text-xs font-semibold border border-red-200">
                <i class="fas fa-trash"></i>
                Xóa tất cả
              </button>
            </div>

          </div>
          <div id="compare-modal-body" class="cmp-scroll min-h-[160px] max-h-[70vh] overflow-auto rounded-xl border border-gray-100 bg-white touch-pan-x">
            <div class="flex items-center gap-3 text-gray-500 p-6"><i class="fas fa-spinner fa-spin"></i><span>Đang tải...</span></div>
          </div>
          
        </div>
      </div>
                    </div>

    

    <main id="main-content" class="min-h-screen">
        @yield('content')
    </main>
    {{-- Footer --}}
    @include('components.footer')

    

    @stack('scripts')
    @auth
    <script>
      (function(){
        async function fetchNotifs(){
          const list = document.getElementById('notif-menu-list');
          if (!list) return;
          try {
            const res = await fetch('{{ route('notifications.index') }}', { headers: { 'X-Requested-With':'XMLHttpRequest' }});
            const data = await res.json();
            const items = (data && data.data && data.data.data) ? data.data.data : [];
            if (!items.length){
              list.innerHTML = '<div class="p-4 text-sm text-gray-500">Chưa có thông báo</div>';
              return;
            }
            list.innerHTML = items.map(n => `
              <div class="px-4 py-3 flex items-start gap-3 hover:bg-gray-50">
                <div class="w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center"><i class="${(n.icon || 'fas fa-bell')} ${n.color || 'text-amber-600'}"></i></div>
                <div class="min-w-0 flex-1">
                  <div class="text-sm font-semibold text-gray-800 truncate">${n.title || ''}</div>
                  <div class="text-xs text-gray-500 truncate">${n.message || ''}</div>
                  <div class="text-[11px] text-gray-400 mt-1">${(new Date(n.created_at)).toLocaleString('vi-VN')}</div>
                </div>
                ${n.is_read ? '' : '<span class="mt-1 inline-block w-2 h-2 rounded-full bg-amber-500"></span>'}
              </div>
            `).join('');
          } catch (e){ list.innerHTML = '<div class="p-4 text-sm text-gray-500">Không tải được thông báo</div>'; }
        }
        async function markAll(){
          try {
            await fetch('{{ route('notifications.read-all') }}', { method:'POST', headers: { 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }});
            const badge = document.getElementById('notif-count-badge'); if (badge) badge.classList.add('hidden');
            const badgeMobile = document.getElementById('notif-count-badge-mobile'); if (badgeMobile) badgeMobile.classList.add('hidden');
            fetchNotifs();
          } catch {}
        }
        document.addEventListener('click', function(e){
          if (e.target && e.target.id === 'notif-mark-all'){ e.preventDefault(); markAll(); }
        });
        // Lazy load when opening dropdown
        document.addEventListener('mouseover', function(e){
          const t = e.target.closest('[data-dropdown="notifications"]');
          if (t) fetchNotifs();
        });
      })();
    </script>
    @endauth
    <script>
      // Realtime update shipping method & totals on checkout
      (function(){
        function formatVND(n){ try { return (Number(n)||0).toLocaleString('vi-VN') + ' đ'; } catch { return n + ' đ'; } }
        function updateTotals(){
          var subtotalEl = document.getElementById('subtotal-amount');
          var taxEl = document.getElementById('tax-amount');
          var shipEl = document.getElementById('shipping-fee-amount');
          var grandEl = document.getElementById('grand-total-amount');
          if (!subtotalEl || !taxEl || !shipEl || !grandEl) return;
          var subtotal = Number(subtotalEl.getAttribute('data-subtotal')||0);
          var tax = Math.round(subtotal * 0.1);
          taxEl.setAttribute('data-tax', String(tax));
          taxEl.textContent = formatVND(tax);
          var method = document.getElementById('shipping_method_input')?.value || 'standard';
          var fee = method === 'express' ? Number(shipEl.getAttribute('data-express')||50000) : Number(shipEl.getAttribute('data-standard')||30000);
          shipEl.textContent = formatVND(fee);
          var grand = subtotal + tax + fee;
          grandEl.setAttribute('data-grand', String(grand));
          grandEl.textContent = formatVND(grand);
        }
        document.addEventListener('change', function(e){
          var radio = e.target && e.target.closest('input[name="shipping_method_choice"]');
          if (!radio) return;
          var hidden = document.getElementById('shipping_method_input');
          if (hidden){ hidden.value = radio.value; }
          updateTotals();
        });
        document.addEventListener('DOMContentLoaded', updateTotals);
      })();
    </script>
    <script>
      // Toggle inline address inputs on checkout
      (function(){
        document.addEventListener('click', function(e){
          const btnBilling = e.target.closest('#btn-add-billing');
          if (btnBilling){
            e.preventDefault();
            const el = document.getElementById('billing-inline');
            if (el) el.classList.toggle('hidden');
          }
          const btnShipping = e.target.closest('#btn-add-shipping');
          if (btnShipping){
            e.preventDefault();
            const el = document.getElementById('shipping-inline');
            if (el) el.classList.toggle('hidden');
          }
        });
      })();
    </script>
    <script>
        // Báo giá đã tạm thời vô hiệu hóa theo yêu cầu — bỏ handler

    // Open select-unit modal and deposit for a specific inventory unit
    document.addEventListener('click', async function(e){
      const openBtn = e.target.closest('.js-open-select-unit');
      if (!openBtn) return;
      e.preventDefault();
      const variantId = openBtn.getAttribute('data-variant-id');
      if (!variantId) return;
      try {
        const res = await fetch(`/api/v1/inventory/by-variant?variant_id=${encodeURIComponent(variantId)}`, { headers: { 'X-Requested-With':'XMLHttpRequest' } });
        const data = await res.json();
        if (!(data && data.success)) { showMessage('Không thể tải xe trong kho','error'); return; }
        const units = Array.isArray(data.data) ? data.data : [];
        if (units.length === 0) { showMessage('Hết hàng. Bạn có thể Đặt trước hoặc Nhận tư vấn.','info'); return; }

        // Render lightweight picker modal
        const html = `
          <div id="unit-picker" class="fixed inset-0 z-[11000] bg-black/50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl p-4">
              <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-bold">Chọn xe trong kho</h3>
                <button class="text-gray-400 hover:text-red-500" data-close-unit-picker><i class="fas fa-times"></i></button>
              </div>
              <div class="max-h-[60vh] overflow-auto border rounded-lg">
                <table class="min-w-full text-sm">
                  <thead class="bg-gray-50"><tr><th class="p-2 text-left">VIN</th><th class="p-2 text-left">Màu</th><th class="p-2 text-left">Showroom</th><th class="p-2 text-left">Giá</th><th class="p-2"></th></tr></thead>
                  <tbody>
                    ${units.map(u => `<tr class="border-t"><td class="p-2">${u.vin || '-'}</td><td class="p-2">${u.color || '-'}</td><td class="p-2">${u.showroom || '-'}</td><td class="p-2 text-indigo-600 font-semibold">${(u.selling_price||0).toLocaleString('vi-VN')}₫</td><td class="p-2"><button class="px-3 py-1.5 text-sm rounded bg-emerald-600 text-white hover:bg-emerald-700" data-pick-unit="${u.id}">Đặt cọc</button></td></tr>`).join('')}
                  </tbody>
                </table>
              </div>
            </div>
          </div>`;
        document.body.insertAdjacentHTML('beforeend', html);
      } catch { showMessage('Không thể tải xe trong kho','error'); }
    });

    // Close and pick actions for unit picker
    document.addEventListener('click', async function(e){
      if (e.target.closest('[data-close-unit-picker]')){
        const picker = document.getElementById('unit-picker'); if (picker) picker.remove();
        return;
      }
      const pickBtn = e.target.closest('[data-pick-unit]');
      if (!pickBtn) return;
      e.preventDefault();
      const unitId = pickBtn.getAttribute('data-pick-unit');
      if (!unitId) return;
      // Redirect to deposit flow (placeholder; integrate with your existing deposit route)
      try {
        // Example: open quote modal in deposit mode and attach selected inventory_id
        openQuoteModal(null, 'deposit');
        const form = document.getElementById('quote-form');
        if (form){
          const invHidden = document.createElement('input');
          invHidden.type = 'hidden'; invHidden.name = 'inventory_id'; invHidden.value = unitId;
          form.appendChild(invHidden);
        }
        showMessage('Đã chọn xe kho. Vui lòng nhập thông tin để đặt cọc.','success');
        const picker = document.getElementById('unit-picker'); if (picker) picker.remove();
      } catch { showMessage('Không thể mở đặt cọc','error'); }
    });

    // Minimal compare state (localStorage) to control FAB visibility only
    (function() {
      const STORAGE_KEY = 'compare.variants';
      const MAX_ITEMS = 4;

      function readCompare(){
        try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) || []; } catch { return []; }
      }
      function saveCompare(list){
        try {
          localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
          // also set a cookie so server-rendered cards can mark active state on first paint
          document.cookie = `compare_variants=${encodeURIComponent(JSON.stringify(list))}; path=/; max-age=${60*60*24*7}`;
        } catch {}
      }
      function updateCompareFab(){
        const list = readCompare();
        const fab = document.getElementById('compare-fab');
        const countEl = document.getElementById('compare-fab-count');
        if (!fab || !countEl) return;
        countEl.textContent = String(list.length);
        if (list.length > 0) { fab.classList.remove('hidden'); }
        else { fab.classList.add('hidden'); }
        // Update compare buttons appearance
        document.querySelectorAll('.js-compare-toggle').forEach(btn => {
          const id = parseInt(btn.getAttribute('data-variant-id'), 10);
          const icon = btn.querySelector('i');
          const active = list.includes(id);
          btn.classList.toggle('border-indigo-300', active);
          btn.classList.toggle('border-gray-200', !active);
          if (icon){
            icon.classList.toggle('text-indigo-600', active);
            icon.classList.toggle('text-gray-700', !active);
          }
        });
      }

      // Toggle add/remove on card compare buttons
      document.addEventListener('click', function(e){
        const btn = e.target.closest('.js-compare-toggle');
        if(!btn) return;
        e.preventDefault();
        const idStr = btn.getAttribute('data-variant-id');
        const id = parseInt(idStr, 10);
        if(!id) return;
        let list = readCompare();
        if (list.includes(id)) {
          list = list.filter(x => x !== id);
          showMessage('Đã bỏ khỏi danh sách so sánh', 'info');
        } else {
          if (list.length >= MAX_ITEMS) {
            showMessage(`Chỉ so sánh tối đa ${MAX_ITEMS} mẫu. Hãy bỏ bớt để thêm mới.`, 'warning');
          } else {
            list.push(id);
            showMessage('Đã thêm vào danh sách so sánh', 'success');
          }
        }
        saveCompare(list);
        updateCompareFab();
      });

      // FAB click -> open modal with quick compare table (client-side only)
      document.addEventListener('click', function(e){
        const fab = e.target.closest('#compare-fab');
        if(!fab) return;
        e.preventDefault();
        openCompareModal();
      });

      function openCompareModal(){
        const modal = document.getElementById('compare-modal');
        const body = document.getElementById('compare-modal-body');
        if (!modal || !body) return;
        modal.style.display = 'flex';
        modal.classList.remove('hidden');
        // Use flex centering via utility classes; avoid inline display override to prevent stacking white layer
        body.innerHTML = '<div class="flex items-center gap-2 text-gray-500"><i class="fas fa-spinner fa-spin"></i><span>Đang tải...</span></div>';
        const diffToggle = document.getElementById('compare-toggle-diff');
        if (diffToggle) {
          try { diffToggle.checked = JSON.parse(localStorage.getItem('compare.showDiffOnly') || 'false'); } catch { diffToggle.checked = false; }
        }
        renderCompareContent();
      }

      function closeCompareModal(){
        const modal = document.getElementById('compare-modal');
        if (modal) { modal.classList.add('hidden'); modal.style.display = 'none'; }
      }

      document.addEventListener('click', function(e){
        if (e.target.closest('#compare-modal-close')) { e.preventDefault(); closeCompareModal(); }
        if (e.target.closest('#compare-clear-all') || e.target.closest('#compare-clear-all-mobile')){
          e.preventDefault();
          saveCompare([]);
          // Immediately hide clear buttons to avoid flicker before render
          const cb = document.getElementById('compare-clear-all');
          const cbm = document.getElementById('compare-clear-all-mobile');
          if (cb) { cb.classList.add('hidden'); cb.classList.add('sm:hidden'); cb.setAttribute('aria-hidden','true'); }
          if (cbm) cbm.classList.add('hidden');
          renderCompareContent();
          updateCompareFab();
        }
        // Remove item from modal list
        const removeBtn = e.target.closest('[data-compare-remove]');
        if (removeBtn){
          const id = parseInt(removeBtn.getAttribute('data-compare-remove'), 10);
          if (id){
            let list = readCompare().filter(x => x !== id);
            saveCompare(list);
            renderCompareContent();
            updateCompareFab();
          }
        }
      });

      function renderCompareContent(){
        const body = document.getElementById('compare-modal-body');
        const list = readCompare();
        if (!body) return;
        const clearBtn = document.getElementById('compare-clear-all');
        const clearBtnMobile = document.getElementById('compare-clear-all-mobile');
        const diffOnly = (()=>{ try { return JSON.parse(localStorage.getItem('compare.showDiffOnly')||'false'); } catch { return false; } })();
        const hideClear = (hide) => {
          if (clearBtn) { clearBtn.classList.toggle('hidden', hide); clearBtn.classList.toggle('sm:hidden', hide); clearBtn.setAttribute('aria-hidden', hide ? 'true' : 'false'); }
          if (clearBtnMobile) clearBtnMobile.classList.toggle('hidden', hide);
        };
        if (!list.length){
          hideClear(true);
          body.innerHTML = '<div class="text-center text-gray-500 py-10"><i class="fas fa-balance-scale text-2xl mb-2"></i><div>Chưa có mẫu nào trong danh sách so sánh</div></div>';
          return;
        }
        hideClear(false);

        // Fetch minimal variant info via API v1/variants/{id}
        Promise.all(list.map(id => fetch(`/api/v1/variants/${id}`).then(r=>r.json()).catch(()=>null)))
          .then(responses => {
            const items = responses
              .map(r => (r && r.success && r.data) ? r.data : null)
              .filter(Boolean);
            if (!items.length){
              body.innerHTML = '<div class="text-center text-gray-500 py-10">Không tải được dữ liệu so sánh</div>';
              return;
            }
            const headers = items.map(v => {
              const firstImg = (Array.isArray(v.images) && v.images.length > 0) ? (v.images.find(i=>i?.is_main) || v.images[0]) : null;
              const img = firstImg && (firstImg.image_url || firstImg.image_path || firstImg.path) ? (firstImg.image_url || firstImg.image_path || firstImg.path) : '';
              const fallback = 'https://via.placeholder.com/300x200/eeeeee/999999?text=No+Image';
              const src = img || fallback;
              const imgTag = `<img src="${src}" alt="Ảnh xe" class="w-28 h-20 md:w-32 md:h-24 object-cover rounded-xl shadow" onerror="this.onerror=null;this.src='${fallback}';">`;
              const brand = (v.car_model?.car_brand?.name || '');
              const model = (v.car_model?.name || '');
              const title = `${brand} ${model} – ${v.name || ''}`.trim();
              return `
              <th class="p-2 sm:p-3 md:p-4 align-top bg-gray-50 min-w-[180px] overflow-visible">
                <div class="relative overflow-visible">
                  <button class="absolute -top-2 -right-2 z-20 bg-white/95 border border-red-200 text-red-600 hover:bg-red-600 hover:text-white rounded-full w-6 h-6 flex items-center justify-center shadow" data-compare-remove="${v.id}" title="Bỏ"><i class="fas fa-times text-[10px]"></i></button>
                  <div class="flex flex-col items-start gap-1">
                    ${imgTag}
                    <div class="max-w-[160px] text-[11px] text-gray-700 truncate" title="${title}">${title}</div>
                  </div>
                </div>
              </th>`;
            }).join('');

            const val = (x) => (x !== undefined && x !== null && x !== '' ? x : '');
            const toPriceVN = (p) => {
              const n = typeof p === 'number' ? p : (p ? Number(p) : NaN);
              return isNaN(n) ? '' : n.toLocaleString('vi-VN') + '₫';
            };
            const fuelVI = (s) => {
              const m = { gasoline: 'Xăng', petrol: 'Xăng', diesel: 'Dầu', hybrid: 'Hybrid', electric: 'Điện', 'plug-in_hybrid': 'Hybrid sạc ngoài', hydrogen: 'Hydro' };
              return m[(s||'').toLowerCase()] || (s || '');
            };
            const engineVI = (s) => {
              const m = { petrol: 'Xăng', gasoline: 'Xăng', diesel: 'Dầu', hybrid: 'Hybrid', electric: 'Điện' };
              return m[(s||'').toLowerCase()] || (s || '');
            };
            const getSpec = (v, names=[]) => {
              const specs = Array.isArray(v.specifications) ? v.specifications : [];
              const found = specs.find(s => names.includes((s.spec_name || '').toLowerCase()));
              return found ? (found.spec_value + (found.unit ? ` ${found.unit}` : '')) : '';
            };
            // Build engine display: prefer displacement in L + type
            const engineDisplay = (v) => {
              let disp = v.engine_displacement || '';
              let liters = '';
              if (disp) {
                const num = parseFloat(String(disp).replace(/[^0-9.]/g,''));
                if (!isNaN(num)) {
                  liters = num > 50 ? (num/1000).toFixed(1) + 'L' : num.toFixed(1) + 'L';
                }
              }
              const type = engineVI((v.specifications||[]).find(s=>String(s.spec_name).toLowerCase()==='engine_type')?.spec_value);
              return (liters || type) ? [liters, type].filter(Boolean).join(' ') : '';
            };

            const cells = {
              'Hãng': items.map(v => val(v.car_model?.car_brand?.name || '')),
              'Dòng xe': items.map(v => val(v.car_model?.name || '')),
              'Phiên bản': items.map(v => val(v.name || '')),
              'Giá': items.map(v => {
                const price = toPriceVN(v.price);
                const badge = (v.has_discount && v.discount_percentage) ? `<span class=\\"ml-2 inline-flex items-center px-1.5 py-0.5 rounded bg-red-50 text-red-600 text-[10px]\\">-${Number(v.discount_percentage)}%</span>` : '';
                return `${price}${badge}`;
              }),
              'Nhiên liệu': items.map(v => val(fuelVI((v.specifications||[]).find(s=>String(s.spec_name).toLowerCase()==='fuel_type')?.spec_value))),
              'Động cơ': items.map(v => val(engineDisplay(v))),
              'Mô-men xoắn': items.map(v => val(v.torque ? (v.torque + ' Nm') : '')),
              'Hộp số': items.map(v => val((v.specifications||[]).find(s=>String(s.spec_name).toLowerCase()==='transmission')?.spec_value)),
              'Dẫn động': items.map(v => val((v.specifications||[]).find(s=>String(s.spec_name).toLowerCase()==='drivetrain')?.spec_value)),
              'Số chỗ': items.map(v => val((v.specifications||[]).find(s=>String(s.spec_name).toLowerCase()==='seating_capacity')?.spec_value)),
              'Công suất': items.map(v => val((v.specifications||[]).find(s=>String(s.spec_name).toLowerCase()==='power_output')?.spec_value)),
              'Dài': items.map(v => val(getSpec(v, ['dài','length']))),
              'Rộng': items.map(v => val(getSpec(v, ['rộng','width']))),
              'Cao': items.map(v => val(getSpec(v, ['cao','height']))),
              'Chiều dài cơ sở': items.map(v => val(getSpec(v, ['chiều dài cơ sở','wheelbase']))),
              'Khoảng sáng gầm': items.map(v => val(getSpec(v, ['khoảng sáng gầm','ground clearance']))),
              'Dung tích bình nhiên liệu': items.map(v => val(v.fuel_tank_capacity ? (v.fuel_tank_capacity + ' L') : '')),
              'Tăng tốc 0-100 km/h': items.map(v => val(v.acceleration ? (v.acceleration + ' s') : '')),
              'Tốc độ tối đa': items.map(v => val(v.max_speed ? (v.max_speed + ' km/h') : '')),
            };

            // Build options/features/colors rows (compact)
            // Color swatch helpers
            const strToHsl = (str) => {
              if (!str) return 'hsl(0,0%,80%)';
              const s = String(str).toLowerCase().trim();
              const hexMatch = s.match(/^#?([0-9a-f]{3}|[0-9a-f]{6})$/i);
              if (hexMatch) return `#${hexMatch[1]}`;
              let hash = 0; for (let i=0;i<s.length;i++){ hash = s.charCodeAt(i) + ((hash<<5) - hash); }
              const hue = Math.abs(hash) % 360; return `hsl(${hue}, 65%, 55%)`;
            };
            const colorsRow = (()=>{
              const arrs = items.map(v => {
                const colors = Array.isArray(v.colors) ? v.colors : [];
                if (!colors.length) return '';
                const names = colors.map(c => (c.color_name || '').replace(/_/g,' ').replace(/\s+/g,' ').trim()).filter(Boolean);
                const list = names.slice(0,3).join(' - ');
                return `<div class=\\"text-xs md:text-[13px] text-gray-700\\">${list || '-'}</div>`;
              });
              return arrs.some(x=>x) ? `<tr><td class=\\"p-2 sm:p-3 md:p-4 font-medium bg-gray-50\\">Màu sắc</td>${arrs.map(x=>`<td class=\\"p-2 sm:p-3 md:p-4\\">${x || '-'}</td>`).join('')}</tr>` : '';
            })();

            const featuresRow = (()=>{
              const arrs = items.map(v => {
                const feats = Array.isArray(v.features_relation) ? v.features_relation : (Array.isArray(v.featuresRelation) ? v.featuresRelation : []);
                if (!feats.length) return '';
                const names = feats.filter(f=>f?.feature_name || f?.name).map(f=> (f.feature_name||f.name)).map(s=>s.replace(/_/g,' ').replace(/\s+/g,' ').trim());
                const list = names.slice(0,3).join(' - ');
                return `<div class=\\"text-xs md:text-[13px] text-gray-700\\">${list}</div>`;
              });
              return arrs.some(x=>x) ? `<tr><td class=\\"p-2 sm:p-3 md:p-4 font-medium bg-gray-50\\">Tính năng nổi bật</td>${arrs.map(x=>`<td class=\\"p-2 sm:p-3 md:p-4\\">${x || '-'}</td>`).join('')}</tr>` : '';
            })();

            const optionsRow = (()=>{
              const arrs = items.map(v => {
                const opts = Array.isArray(v.options) ? v.options : [];
                if (!opts.length) return '';
                const names = opts.filter(o=>o?.option_name).map(o=> (o.option_name || '').replace(/_/g,' ').replace(/\s+/g,' ').trim());
                const list = names.slice(0,3).join(' - ');
                return `<div class=\\"text-xs md:text-[13px] text-gray-700\\">${list}</div>`;
              });
              return arrs.some(x=>x) ? `<tr><td class=\\"p-2 sm:p-3 md:p-4 font-medium bg-gray-50\\">Tùy chọn</td>${arrs.map(x=>`<td class=\\"p-2 sm:p-3 md:p-4\\">${x || '-'}</td>`).join('')}</tr>` : '';
            })();

            const rowHtml = Object.entries(cells)
              .filter(([_, arr]) => arr.some(x => x !== ''))
              .map(([label, arr]) => {
                const compareLen = arr.length;
                const normalized = arr.map(v => String(v).replace(/<[^>]*>/g,'').trim());
                const allSame = compareLen <= 1 ? true : normalized.every(v => v === normalized[0]);
                if (diffOnly && allSame) return '';
                const tds = arr.map(v => `<td class=\\"p-2 sm:p-3 md:p-4 ${(!allSame ? 'text-indigo-700 font-semibold' : '')}\\">${v || '-'}</td>`).join('');
                return `<tr class=\\"cmp-snap\\"><td class=\\"sticky left-0 p-2 sm:p-3 md:p-4 font-medium bg-gray-50 z-10\\">${label}</td>${tds}</tr>`;
              })
              .join('') + colorsRow + featuresRow + optionsRow;

            // Footer summary: min, max, difference and clear-all
            const prices = items.map(v => (typeof v.price === 'number' ? v.price : Number(v.price || 0))).filter(n=>!isNaN(n));
            const minPrice = prices.length ? Math.min(...prices) : null;
            const maxPrice = prices.length ? Math.max(...prices) : null;
            const diffPrice = (minPrice !== null && maxPrice !== null) ? (maxPrice - minPrice) : null;

            const summary = (items.length >= 2) ? `
              <div class=\"flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mt-4 p-3 bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-100\">
                <div class=\"text-sm text-gray-700 space-x-4\">
                  ${minPrice !== null ? `<span>Giá thấp nhất: <strong class=\\"text-emerald-600\\">${toPriceVN(minPrice)}</strong></span>` : ''}
                  ${maxPrice !== null ? `<span>Giá cao nhất: <strong class=\\"text-rose-600\\">${toPriceVN(maxPrice)}</strong></span>` : ''}
                  ${diffPrice !== null ? `<span>Chênh lệch: <strong class=\\"text-indigo-600\\">${toPriceVN(diffPrice)}</strong></span>` : ''}
                </div>
                <button id=\"compare-clear-all\" class=\"inline-flex items-center gap-2 px-3 py-2 rounded-lg text-red-600 hover:text-white hover:bg-red-600 text-sm font-semibold border border-red-200\"><i class=\"fas fa-trash\"></i> Xóa tất cả</button>
              </div>` : '';

            body.innerHTML = `
              <div class=\"overflow-x-auto rounded-xl cmp-table\">
                <table class=\"min-w-full text-[11px] xs:text-xs md:text-sm\">
                  <thead class=\"sticky top-0 bg-gray-50\"><tr><th class=\"p-2 sm:p-3 md:p-4 text-left text-gray-500 bg-gray-50 min-w-[120px]\">Thông số</th>${headers}</tr></thead>
                  <tbody class=\"divide-y divide-gray-100\">${rowHtml}</tbody>
                </table>
              </div>
              ${summary}
            `;
          })
          .catch(() => {
            body.innerHTML = '<div class="text-center text-gray-500 py-10">Không tải được dữ liệu so sánh</div>';
          });
      }

      // Initialize on load
      function initCompareUI(){
        // Force modal hidden on first paint (guards against any leftover state)
        const modal = document.getElementById('compare-modal');
        if (modal) { modal.classList.add('hidden'); modal.style.display = 'none'; }
        updateCompareFab();
      }
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCompareUI);
      } else {
        initCompareUI();
      }

      // Close modal when clicking backdrop or pressing ESC
      document.addEventListener('click', function(e){
        const modal = document.getElementById('compare-modal');
        if (!modal || modal.classList.contains('hidden')) return;
        if (e.target === modal) { closeCompareModal(); }
      });
      document.addEventListener('keydown', function(e){
        if (e.key === 'Escape') { closeCompareModal(); }
        if (e.key === 'Tab') {
          // basic focus trap: keep tab focus within modal when opened
          const modal = document.getElementById('compare-modal');
          if (!modal || modal.classList.contains('hidden')) return;
          const focusable = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
          const first = focusable[0];
          const last = focusable[focusable.length - 1];
          if (document.activeElement === last && !e.shiftKey) { e.preventDefault(); first?.focus(); }
          if (document.activeElement === first && e.shiftKey) { e.preventDefault(); last?.focus(); }
        }
      });

      // Persist and handle differences-only toggle
      document.addEventListener('change', function(e){
        const toggle = e.target.closest('#compare-toggle-diff');
        if (!toggle) return;
        try { localStorage.setItem('compare.showDiffOnly', JSON.stringify(!!toggle.checked)); } catch {}
        renderCompareContent();
      });
    })();
    </script>


    <script>
        // Replace jQuery handler by vanilla (prevent full-page navigation reliably)
        (function(){
          if (window.__bindAddToCartVanilla) return; window.__bindAddToCartVanilla = true;
          document.addEventListener('submit', function(e){
            const form = e.target;
            if (!form || !form.classList || !form.classList.contains('add-to-cart-form')) return;
            e.preventDefault();
            const button = form.querySelector('button[type="submit"]');
            const originalHtml = button ? button.innerHTML : '';
            if (button){ button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm...'; button.disabled = true; }
            const fd = new FormData(form);
            fetch(form.getAttribute('action'), {
                method: 'POST',
              headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
              body: fd
            }).then(r=>r.json()).then(res=>{
              if (res && res.success){
                if (typeof updateCartCount === 'function' && (res.cart_count !== undefined)) updateCartCount(res.cart_count);
                showMessage(res.message || 'Đã thêm vào giỏ hàng!', 'success');
                    } else {
                showMessage((res && res.message) || 'Có lỗi xảy ra khi thêm vào giỏ hàng', 'error');
                    }
            }).catch(()=>{
                    showMessage('Có lỗi xảy ra khi thêm vào giỏ hàng', 'error');
            }).finally(()=>{
              if (button){ button.innerHTML = originalHtml; button.disabled = false; }
            });
          }, true); // capture to ensure we intercept before native submit
        })();

        // Quote form submit handled by vanilla listener above to avoid duplicate submissions

        // Open quote from card buttons
        document.addEventListener('click', function(e) {
            if (e.target.closest('.js-open-quote')) {
                const button = e.target.closest('.js-open-quote');
                const id = button.dataset.variantId;
            openQuoteModal(id);
            }
        });





        // Update cart count function (global) - using vanilla JS
        function updateCartCount(count) {
            console.log('Updating cart count to:', count);
            
            // Update all cart count badges on the page
            const cartCounters = document.querySelectorAll('.cart-count');
            cartCounters.forEach(counter => {
                counter.textContent = count > 99 ? '99+' : count;
                counter.style.display = count === 0 ? 'none' : 'flex';
                // Ensure proper centering
                counter.style.justifyContent = 'center';
                counter.style.alignItems = 'center';
                counter.style.textAlign = 'center';
            });
            
            // Also update the cart count badge in the header navigation
            const cartBadges = document.querySelectorAll('#cart-count-badge, #cart-count-badge-mobile');
            cartBadges.forEach(badge => {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = count === 0 ? 'none' : 'flex';
                // Ensure proper centering
                badge.style.justifyContent = 'center';
                badge.style.alignItems = 'center';
                badge.style.textAlign = 'center';
            });

            // Update any other cart count elements
            const cartDataElements = document.querySelectorAll('[data-cart-count]');
            cartDataElements.forEach(element => {
                element.textContent = count > 99 ? '99+' : count;
                element.style.display = count === 0 ? 'none' : 'flex';
                // Ensure proper centering
                element.style.justifyContent = 'center';
                element.style.alignItems = 'center';
                element.style.textAlign = 'center';
            });
            
            console.log('Cart count update completed. Found badges:', cartBadges.length, 'counters:', cartCounters.length);
        }

        // Update wishlist count function (global) - using vanilla JS
        function updateWishlistCount(count) {
            console.log('Updating wishlist count to:', count);
            
            // Update all wishlist count badges on the page
            const wishlistCounters = document.querySelectorAll('.wishlist-count');
            wishlistCounters.forEach(counter => {
                counter.textContent = count > 99 ? '99+' : count;
                counter.style.display = count === 0 ? 'none' : 'flex';
                // Ensure proper centering
                counter.style.justifyContent = 'center';
                counter.style.alignItems = 'center';
                counter.style.textAlign = 'center';
            });
            
            // Also update the wishlist count badge in the header navigation
            const wishlistBadges = document.querySelectorAll('#wishlist-count-badge, #wishlist-count-badge-mobile');
            wishlistBadges.forEach(badge => {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = count === 0 ? 'none' : 'flex';
                // Ensure proper centering
                badge.style.justifyContent = 'center';
                badge.style.alignItems = 'center';
                badge.style.textAlign = 'center';
            });

            // Update any other wishlist count elements
            const wishlistDataElements = document.querySelectorAll('[data-wishlist-count]');
            wishlistDataElements.forEach(element => {
                element.textContent = count > 99 ? '99+' : count;
                element.style.display = count === 0 ? 'none' : 'flex';
                // Ensure proper centering
                element.style.justifyContent = 'center';
                element.style.alignItems = 'center';
                element.style.textAlign = 'center';
            });
            
            console.log('Wishlist count update completed. Found badges:', wishlistBadges.length, 'counters:', wishlistCounters.length);
        }

        // Refresh cart count from server
        function refreshCartCount() {
            fetch('{{ route("cart.count") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
                .then(function(r){ return r.json(); })
                .then(function(response){ if (response && response.success) updateCartCount(response.cart_count); })
                .catch(function(){ console.error('Failed to refresh cart count'); });
        }

        // Refresh wishlist count from server
        function refreshWishlistCount() {
            $.ajax({
                url: '{{ route("wishlist.count") }}',
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function(response) {
                    if (response.success) {
                        updateWishlistCount(response.wishlist_count);
                    }
                },
                error: function() {
                    console.error('Failed to refresh wishlist count');
                }
            });
        }

        // Initialize counts on page load
        $(document).ready(function() {
            // Get initial wishlist count from server
            refreshWishlistCount();

            // Get initial cart count from server
            refreshCartCount();
        });

        // Also initialize on page show (for browser navigation)
        window.addEventListener('pageshow', function() {
            // Get initial wishlist count from server
            refreshWishlistCount();

            // Get initial cart count from server
            refreshCartCount();
        });

        // Force refresh counts function (global)
        window.refreshCounts = function() {
            console.log('Force refreshing counts...');
            refreshWishlistCount();
            refreshCartCount();
        };

        // Handle newsletter form submission
        $(document).on('submit', '#newsletter-form', function(e) {
            e.preventDefault();
            var $form = $(this);
            var $button = $form.find('button[type="submit"]');
            var $input = $form.find('input[name="newsletter_email"]');
            var email = $input.val();
            var originalText = $button.html();
            
            // Validate email
            if (!email || !email.includes('@')) {
                showMessage('Vui lòng nhập email hợp lệ', 'error');
                return false;
            }
            
            // Show loading state
            $button.html('<i class="fas fa-spinner fa-spin"></i>');
            $button.prop('disabled', true);
            
            // Simulate an AJAX call (replace with actual endpoint when available)
            setTimeout(function() {
                // Success response (this would be an actual AJAX call in production)
                showMessage('Cảm ơn bạn đã đăng ký nhận tin!', 'success');
                $input.val(''); // Clear the input
                
                // Restore button state
                $button.html(originalText);
                $button.prop('disabled', false);
            }, 1000);
            
            return false;
        });

        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInputs = document.querySelectorAll('.search-input');
            
            searchInputs.forEach(input => {
                const suggestionsContainer = input.parentElement.querySelector('.search-suggestions');
                const suggestionsList = suggestionsContainer?.querySelector('.search-suggestions-list');
                
                if (!suggestionsContainer || !suggestionsList) return;
                
                let searchTimeout;
                
                input.addEventListener('input', function() {
                    const query = this.value.trim();
                    
                    // Clear previous timeout
                    clearTimeout(searchTimeout);
                    
                    if (query.length < 2) {
                        suggestionsContainer.classList.add('hidden');
                        return;
                    }
                    
                    // Debounce search
                    searchTimeout = setTimeout(() => {
                        fetchSearchSuggestions(query, suggestionsList, suggestionsContainer);
                    }, 300);
                });
                
                // Show suggestions on focus
                input.addEventListener('focus', function() {
                    const query = this.value.trim();
                    if (query.length >= 2) {
                        suggestionsContainer.classList.remove('hidden');
                    }
                });
                
                // Hide suggestions when clicking outside
                document.addEventListener('click', function(e) {
                    if (!input.contains(e.target) && !suggestionsContainer.contains(e.target)) {
                        suggestionsContainer.classList.add('hidden');
                    }
                });
            });
        });

        function fetchSearchSuggestions(query, suggestionsList, container) {
            // Client-side suggestions leading to valid search route with q/category
            const suggestions = [
                { text: `${query} - Tất cả xe`, url: `/search?q=${encodeURIComponent(query)}&category=cars` },
                { text: `${query} - Phụ kiện`, url: `/search?q=${encodeURIComponent(query)}&category=accessories` },
                { text: `${query} - Theo hãng`, url: `/search?q=${encodeURIComponent(query)}&category=cars` },
                { text: `${query} - Theo model`, url: `/search?q=${encodeURIComponent(query)}&category=cars` },
            ];
            
            suggestionsList.innerHTML = '';
            
            suggestions.forEach(suggestion => {
                const item = document.createElement('div');
                item.className = 'px-2 py-1 hover:bg-gray-100 rounded cursor-pointer text-sm';
                item.innerHTML = `<i class="fas fa-search mr-2 text-gray-400"></i>${suggestion.text}`;
                
                item.addEventListener('click', () => {
                    window.location.href = suggestion.url;
                });
                
                suggestionsList.appendChild(item);
            });
            
            container.classList.remove('hidden');
        }

        // Lazy Loading System for Images
        function initLazyLoading() {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        const dataSrc = img.dataset.src;
                        
                        if (dataSrc) {
                            img.src = dataSrc;
                            img.classList.remove('lazy');
                            img.classList.add('loaded');
                            imageObserver.unobserve(img);
                        }
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });

            // Observe all lazy images
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }

        // Initialize lazy loading when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initLazyLoading);
        } else {
            initLazyLoading();
        }

        // Performance optimization: Debounce function
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Enhanced search with debouncing
        const debouncedSearch = debounce(function(searchTerm) {
            // Implement search functionality here
            console.log('Searching for:', searchTerm);
        }, 300);

        // Add search input listener
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    debouncedSearch(this.value);
                });
            }
        });

        // Loading Skeleton System
        function showSkeleton(container, type = 'card') {
            const skeleton = document.createElement('div');
            skeleton.className = 'skeleton-loading';
            
            const skeletonTemplates = {
                card: `
                    <div class="bg-white rounded-lg shadow-md p-4 animate-pulse">
                        <div class="bg-gray-300 h-48 rounded-lg mb-4"></div>
                        <div class="space-y-3">
                            <div class="bg-gray-300 h-4 rounded w-3/4"></div>
                            <div class="bg-gray-300 h-4 rounded w-1/2"></div>
                            <div class="bg-gray-300 h-4 rounded w-2/3"></div>
                        </div>
                    </div>
                `,
                list: `
                    <div class="bg-white rounded-lg shadow-md p-4 animate-pulse">
                        <div class="space-y-3">
                            <div class="bg-gray-300 h-4 rounded w-full"></div>
                            <div class="bg-gray-300 h-4 rounded w-3/4"></div>
                            <div class="bg-gray-300 h-4 rounded w-1/2"></div>
                        </div>
                    </div>
                `,
                table: `
                    <div class="bg-white rounded-lg shadow-md animate-pulse">
                        <div class="p-4 border-b">
                            <div class="bg-gray-300 h-6 rounded w-1/4"></div>
                        </div>
                        <div class="p-4 space-y-3">
                            <div class="bg-gray-300 h-4 rounded w-full"></div>
                            <div class="bg-gray-300 h-4 rounded w-3/4"></div>
                            <div class="bg-gray-300 h-4 rounded w-1/2"></div>
                        </div>
                    </div>
                `
            };
            
            skeleton.innerHTML = skeletonTemplates[type] || skeletonTemplates.card;
            container.appendChild(skeleton);
            return skeleton;
        }

        function hideSkeleton(skeleton) {
            if (skeleton && skeleton.parentElement) {
                skeleton.remove();
            }
        }

        // Loading state management
        function showLoading(button) {
            if (button) {
                button.disabled = true;
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';
                button.dataset.originalText = originalText;
            }
        }

        function hideLoading(button) {
            if (button) {
                button.disabled = false;
                const originalText = button.dataset.originalText;
                if (originalText) {
                    button.innerHTML = originalText;
                    delete button.dataset.originalText;
                }
            }
        }

        // Accessibility System
        function initAccessibility() {
            // Keyboard navigation for dropdowns
            document.querySelectorAll('[data-dropdown]').forEach(dropdown => {
                const trigger = dropdown.querySelector('[data-dropdown-trigger]');
                const menu = dropdown.querySelector('[data-dropdown-menu]');
                
                if (trigger && menu) {
                    trigger.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            toggleDropdown(dropdown);
                        }
                    });
                }
            });

            // Skip to content link
            const skipLink = document.createElement('a');
            skipLink.href = '#main-content';
            skipLink.textContent = 'Bỏ qua đến nội dung chính';
            skipLink.className = 'sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-blue-600 text-white px-4 py-2 rounded z-50';
            document.body.insertBefore(skipLink, document.body.firstChild);

            // Focus management for modals
            document.querySelectorAll('[data-modal]').forEach(modal => {
                const trigger = document.querySelector(`[data-modal-trigger="${modal.dataset.modal}"]`);
                const closeBtn = modal.querySelector('[data-modal-close]');
                
                if (trigger) {
                    trigger.addEventListener('click', () => {
                        modal.classList.remove('hidden');
                        const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                        if (focusableElements.length > 0) {
                            focusableElements[0].focus();
                        }
                    });
                }
                
                if (closeBtn) {
                    closeBtn.addEventListener('click', () => {
                        modal.classList.add('hidden');
                        trigger?.focus();
                    });
                }
            });
        }

        function toggleDropdown(dropdown) {
            const menu = dropdown.querySelector('[data-dropdown-menu]');
            const isOpen = menu.classList.contains('hidden');
            
            // Close all other dropdowns
            document.querySelectorAll('[data-dropdown-menu]').forEach(otherMenu => {
                if (otherMenu !== menu) {
                    otherMenu.classList.add('hidden');
                }
            });
            
            // Toggle current dropdown
            if (isOpen) {
                menu.classList.remove('hidden');
            } else {
                menu.classList.add('hidden');
            }
        }

        // Initialize accessibility when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initAccessibility);
        } else {
            initAccessibility();
        }


    </script>

    <script></script>
</body>

</html>