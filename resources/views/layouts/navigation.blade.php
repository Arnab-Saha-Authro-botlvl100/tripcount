<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        :root {
            --primary-color: #007AE8;
            --secondary-color: #67B7FF;
            --accent-color: #00B4C6;
            --sidebar-width: 300px;
        }

        .nav-gradient {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        .clock {
            font-family: 'Segoe UI', monospace;
            background: rgba(142, 138, 138, 0.54);
            border-radius: 8px;
            padding: 4px 12px;
        }

        .dropdown-enter-active,
        .dropdown-leave-active {
            transition: all 0.3s ease;
        }

        .dropdown-enter-from,
        .dropdown-leave-to {
            opacity: 0;
            transform: translateY(-10px);
        }

        .hamburger-line {
            @apply w-6 h-0.5 my-1.5 block bg-white transition ease transform duration-300;
        }

        .hamburger-active>span:nth-child(1) {
            @apply rotate-45 translate-y-2;
        }

        .hamburger-active>span:nth-child(2) {
            @apply opacity-0;
        }

        .hamburger-active>span:nth-child(3) {
            @apply -rotate-45 -translate-y-2;
        }

        @media (max-width: 768px) {
            .mobile-menu {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.5s ease-out;
            }

            .mobile-menu-open {
                max-height: 500px;
            }
        }

        .max-h-60 {
            max-height: 15rem;
        }

        /* For the loading spinner */
        .fa-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <style>
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }

        .animate-fade-out {
            animation: fadeOut 0.3s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }
    </style>
    <style>
        /* Responsive design adjustments */
        @media (min-width: 640px) {
            .addagent form {
                max-width: 600px;
                margin: 0 auto;
            }
        }


        @media (min-width: 768px) {
            .addagent form {
                max-width: 700px;
            }

            /* For larger screens, you could arrange some fields side by side */
            .grid-cols-2 {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 1rem;
            }
        }

        /* General styling */
        .hidden {
            display: none;
        }

        .alert-warning {
            padding: 0.75rem;
            background-color: #fef3c7;
            color: #92400e;
            border-radius: 0.375rem;
        }

        #navdiv {
            margin-left: var(--sidebar-width) !important;
            width: calc(100% - var(--sidebar-width));
        }
        @media (max-width: 999px) {
            #navdiv {
                margin-left: 0px !important;
                width: auto
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navigation Bar -->
    <nav x-data="{ open: false, mobileMenuOpen: false }" class="nav-gradient shadow-lg sticky top-0 z-50">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" id="navdiv">
            <div class="flex justify-center h-16 items-center">
                <!-- Left Section -->
                <div class="flex items-center">
                    <button class="hamburger-menu" id="hamburger-menu">
                        <i class="fa-solid fa-bars-staggered"></i>
                    </button>
                    <!-- Mobile menu button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="lg:hidden flex items-center justify-center p-2 rounded-md text-white focus:outline-none">
                        <div class="hamburger" :class="{ 'hamburger-active': mobileMenuOpen }">
                            <span class="hamburger-line"></span>
                            <span class="hamburger-line"></span>
                            <span class="hamburger-line"></span>
                        </div>
                    </button>

                    <!-- Logo/Dashboard Link -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/dashboard" class="text-white text-lg font-semibold no-underline flex items-center">
                            <i class="fas fa-compass mr-2 text-xl"></i>
                            <span class="hidden sm:inline">Dashboard</span>
                        </a>
                    </div>

                    <!-- Clock -->
                    <div class="ml-4 sm:ml-6 flex items-center">
                        <div id="clock" class="clock text-white font-mono"></div>
                    </div>
                </div>

                <!-- Center Title -->
                <div class="hidden lg:flex md:none items-center justify-center flex-1">
                    <h1 class="text-white text-xl font-bold tracking-wider">Travel Management System</h1>
                </div>

                <!-- Right Section -->
                <div class="flex items-center">
                    <!-- Search Dropdown -->
                    <div class="px-4 py-2" x-data="{
                        searchQuery: '',
                        searchResults: [],
                        isSearching: false,
                    
                        async search() {
                            if (this.searchQuery.length < 3) {
                                this.searchResults = [];
                                return;
                            }
                    
                            this.isSearching = true;
                            try {
                                const response = await fetch(`/search-whole-database?q=${encodeURIComponent(this.searchQuery)}`);
                                this.searchResults = await response.json();
                            } catch (error) {
                                console.error('Search failed:', error);
                                this.searchResults = [];
                            }
                            this.isSearching = false;
                        }
                    }" @click.away="searchResults = []">
                        <div class="relative">
                            <input type="text" id="wholesearch" x-model="searchQuery"
                                @input.debounce.300ms="search()" placeholder="Search..."
                                class="w-full pl-8 pr-3 py-1 text-sm rounded-md border border-gray-300 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-blue-500">
                            <i class="fas fa-search absolute left-2 top-2 text-gray-400 text-sm"></i>

                            <!-- Loading indicator -->
                            <i x-show="isSearching"
                                class="fas fa-spinner fa-spin absolute right-2 top-2 text-gray-400 text-sm"></i>

                            <!-- Search results dropdown -->
                            <div x-show="searchResults.length > 0 && searchQuery.length > 0"
                                class="absolute z-50 left-0 right-0 mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                                <template x-for="result in searchResults" :key="result.id">
                                    <a target="_blank" :href="result.link"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        x-text="result.name + 
                                                (result.type ? ' (' + result.type + ')' : '') + 
                                                (result.passport ? ' (Passport: ' + result.passport + ')' : '') +
                                                (result.ticket ? ' (Ticket No: ' + result.ticket + ')' : '')"></a>

                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- User Dropdown -->
                    <div class="ml-4 relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center text-sm rounded-full text-white focus:outline-none px-3 py-1.5
                            focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-800 focus:ring-white 
                            bg-blue-600 hover:bg-blue-700 transition-colors duration-200
                            w-full max-w-xs sm:w-auto truncate">
                            <div class="flex items-center min-w-0">
                                <!-- Truncate long names and ensure avatar stays visible -->
                                <span class="mr-2 font-medium truncate max-w-[120px] sm:max-w-[160px]">
                                    {{ Auth::user()->name }}
                                </span>
                                <img class="h-8 w-8 rounded-full object-cover flex-shrink-0"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random"
                                    alt="User profile">
                            </div>
                            <i class="fas fa-chevron-down ml-1 text-xs transition-transform duration-200 flex-shrink-0"
                                :class="{ 'transform rotate-180': open }"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" x-transition:enter="dropdown-enter"
                            x-transition:enter-start="dropdown-enter-from" x-transition:enter-end="dropdown-enter-to"
                            x-transition:leave="dropdown-leave" x-transition:leave-start="dropdown-leave-from"
                            x-transition:leave-end="dropdown-leave-to"
                            class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user-circle mr-2"></i> Profile
                            </a>
                            <form method="POST" action="#" id="logoutForm">
                                <a href="#" onclick="event.preventDefault(); confirmLogout();"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </a>
                            </form>
                        </div>
                    </div>

                    <!-- Mobile Logout Button -->
                    <button id="logoutBtn" class="ml-4 lg:hidden block p-2 text-white focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
                            <path fill-rule="evenodd"
                                d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    @if (session('success'))
        <div id="success-notification"
            class="fixed inset-0 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end z-50">
            <div
                class="max-w-sm w-full bg-green-50 shadow-lg rounded-lg pointer-events-auto ring-1 ring-green-100 overflow-hidden transition-all duration-300 ease-in-out">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium text-green-800">Success</p>
                            <p class="mt-1 text-sm text-green-600">{{ session('success') }}</p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button onclick="closeNotification('success-notification')"
                                class="bg-green-50 rounded-md inline-flex text-green-400 hover:text-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <span class="sr-only">Close</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div id="error-notification"
            class="fixed inset-0 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end z-50">
            <div
                class="max-w-sm w-full bg-red-50 shadow-lg rounded-lg pointer-events-auto ring-1 ring-red-100 overflow-hidden transition-all duration-300 ease-in-out">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium text-red-800">Error</p>
                            <p class="mt-1 text-sm text-red-600">{{ session('error') }}</p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button onclick="closeNotification('error-notification')"
                                class="bg-red-50 rounded-md inline-flex text-red-400 hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <span class="sr-only">Close</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        // Close notification function
        function closeNotification(id) {
            const notification = document.getElementById(id);
            if (notification) {
                // Add fade-out animation
                notification.querySelector('div > div').style.transition = 'all 0.3s ease-in-out';
                notification.querySelector('div > div').style.transform = 'translateY(100%)';
                notification.querySelector('div > div').style.opacity = '0';

                // Remove element after animation completes
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }
        }

        // Auto-close notifications after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const successNotification = document.getElementById('success-notification');
            const errorNotification = document.getElementById('error-notification');

            if (successNotification) {
                setTimeout(() => closeNotification('success-notification'), 5000);
            }

            if (errorNotification) {
                setTimeout(() => closeNotification('error-notification'), 5000);
            }
        });
    </script>

    <script>
        // Clock functionality
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeString = `${hours}:${minutes}:${seconds}`;

            document.getElementById('clock').textContent = timeString;
        }

        // Update clock every second
        setInterval(updateClock, 1000);
        updateClock(); // Initialize immediately

        // Logout confirmation
        function confirmLogout() {
            if (confirm('Are you sure you want to logout?')) {
                document.getElementById('logoutForm').submit();
            }
        }

        // Mobile logout button
        document.getElementById('logoutBtn')?.addEventListener('click', confirmLogout);
    </script>
</body>

</html>
