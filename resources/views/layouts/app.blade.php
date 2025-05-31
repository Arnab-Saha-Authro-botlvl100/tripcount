<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TripCount || An Accounts Software for Agencys') }}</title>
    
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    
    @include('layouts.head')
   
    
</head>


<body class="bg-gray-50 min-h-screen">
    <!-- Main Layout Container -->
    <div class="flex flex-col min-h-screen">
        <!-- Top Navigation -->
        @include('layouts.navigation')

        <!-- Content Area with Sidebar -->
        <div class="flex flex-1">
            <!-- Sidebar -->
            @include('layouts.sidebar')


            <!-- Main Content -->
            <main class="flex-1 overflow-auto p-4 md:p-6 lg:p-8">
                
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Toggle sidebar on mobile
        const menuBtn = document.getElementById('menuBtn');
        const sideNav = document.getElementById('sideNav');
        
        if (menuBtn && sideNav) {
            menuBtn.addEventListener('click', () => {
                sideNav.classList.toggle('hidden');
                sideNav.classList.toggle('block');
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 768 && sideNav && !sideNav.classList.contains('hidden')) {
                if (!e.target.closest('#sideNav') && !e.target.closest('#menuBtn')) {
                    sideNav.classList.add('hidden');
                    sideNav.classList.remove('block');
                }
            }
        });
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            important: true,
            theme: {
                extend: {
                    colors: {
                        primary: "#00959E",
                        secondary: "#007780",
                        accent: "#00B4C6",
                        clifford: "#da373d",
                    },
                    transitionProperty: {
                        'width': 'width',
                        'spacing': 'margin, padding',
                    },
                },
            }
        }
    </script>
</body>

</html>
