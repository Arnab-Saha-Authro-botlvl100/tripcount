</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | TripCount</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
            border-color: #2563eb;
        }

        body {
            background: url('{{ url('/image/hero_bg.png') }}') no-repeat center/cover, rgba(255, 255, 255, 0.3);
            background-size: cover;
            position: relative;
            background-position: 0rem 0rem;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-container {
            display: flex;
            max-width: 1000px;
            width: 100%;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .login-hero {
            flex: 1;
            background: linear-gradient(135deg, #709ffe, #1f2b53);
            color: white;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
    </style>
</head>

<body class="min-h-screen">

    @if (session('status'))
        <div id="status-alert" class="fixed right-5 bg-green-500 text-white px-4 py-2 rounded shadow-md"
            style="top: 6.25rem !important">
            {{ session('status') }}
        </div>
    @endif

    <div class="flex flex-col lg:flex-row login-container">
        <!-- Left Side (Illustration/Info) -->
        <div class="w-full lg:w-1/2 login-hero text-white p-8 md:p-12 flex flex-col justify-center">
            <div class="max-w-md mx-auto ">
                <!-- Logo -->
                <div class="mb-8 text-center lg:text-center">
                    <a href="{{ url('/') }}" class="inline-block">
                        <img src="{{ url('/image/tripcount.png') }}" alt="TripCount Logo" class="h-10 md:h-12 w-auto">
                    </a>
                </div>

                <!-- Content -->
                <div class="text-center lg:text-left">
                    <h2 class="text-2xl md:text-3xl font-bold mb-4">Simplify Your Travel Agency Accounting</h2>
                    <p class="text-blue-100 mb-6">
                        Join thousands of travel professionals who trust TripCount to manage their finances efficiently.
                    </p>
                    <ul class="space-y-3 text-blue-100">
                        <li class="flex items-start">
                            <svg class="h-5 w-5 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Real-time financial tracking</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Automated report generation</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Secure cloud storage</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Right Side (Form) -->
        <div class="w-full lg:w-1/2 p-6 md:p-12 flex items-center justify-center">
            <div class="w-full max-w-md">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Create your account</h1>
                <p class="text-gray-600 mb-8">Already registered? <a href="{{ route('login') }}"
                        class="text-[#2563eb] font-medium hover:underline">Sign in</a></p>

                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Company Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Company Name <span class="text-red-600">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none"
                            placeholder="Your company name" required autofocus>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Contact Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="tel_no" class="block text-sm font-medium text-gray-700 mb-1">
                                Telephone
                            </label>
                            <input type="text" id="tel_no" name="tel_no" value="{{ old('tel_no') }}"
                                class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none"
                                placeholder="+1 (___) ___ ____">
                        </div>
                        <div>
                            <label for="mobile_no" class="block text-sm font-medium text-gray-700 mb-1">
                                Mobile <span class="text-red-600">*</span>
                            </label>
                            <input type="text" id="mobile_no" name="mobile_no" value="{{ old('mobile_no') }}"
                                class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none"
                                placeholder="Mobile number" required>
                            <x-input-error :messages="$errors->get('mobile_no')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email <span class="text-red-600">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none"
                            placeholder="your@email.com" required>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Passwords -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password <span class="text-red-600">*</span>
                            </label>
                            <input type="password" id="password" name="password" autocomplete="off"
                                class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none"
                                placeholder="••••••••" required>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirm Password<span class="text-red-600">*</span>
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                autocomplete="off"
                                class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none"
                                placeholder="••••••••" required>
                            <p id="password-match-message" class="text-sm mt-2"></p>
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="company_address" class="block text-sm font-medium text-gray-700 mb-1">
                            Company Address <span class="text-red-600">*</span>
                        </label>
                        <textarea id="company_address" name="company_address" rows="3"
                            class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none"
                            placeholder="Full company address" required>{{ old('company_address') }}</textarea>
                    </div>

                    <!-- Logo Upload -->
                    <div>
                        <label for="company_logo" class="block text-sm font-medium text-gray-700 mb-1">
                            Company Logo <span class="text-red-600">*</span>
                        </label>
                        <input type="file" id="company_logo" name="company_logo" required
                            class="form-input w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none">
                        <x-input-error :messages="$errors->get('company_logo')" class="mt-2" />
                    </div>

                    <!-- Submit -->
                    <div>
                        <button type="submit"
                            class="w-full px-6 py-3 rounded-lg bg-[#2563eb] text-white font-medium hover:bg-[#1d4ed8] transition-colors">
                            Create Account
                        </button>
                    </div>

                    <p class="text-xs text-gray-500">
                        By registering, you agree to our Terms of Service and Privacy Policy.
                    </p>
                </form>
            </div>
        </div>
    </div>


    <script>
        // Remove the alert after 5 seconds (5000 milliseconds)
        setTimeout(() => {
            const alert = document.getElementById('status-alert');
            if (alert) {
                alert.remove();
            }
        }, 5000);
    </script>

    <script>
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        const message = document.getElementById('password-match-message');

        function validatePasswords() {
            if (confirmPassword.value === '') {
                message.textContent = '';
                confirmPassword.setCustomValidity("");
                return;
            }

            if (password.value === confirmPassword.value) {
                message.textContent = "✅ Passwords match";
                message.classList.remove('text-red-600');
                message.classList.add('text-green-600');
                confirmPassword.setCustomValidity("");
            } else {
                message.textContent = "❌ Passwords do not match";
                message.classList.remove('text-green-600');
                message.classList.add('text-red-600');
                confirmPassword.setCustomValidity("Passwords do not match");
            }
        }

        password.addEventListener('input', validatePasswords);
        confirmPassword.addEventListener('input', validatePasswords);
    </script>


</body>

</html>
