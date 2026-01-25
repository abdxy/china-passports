<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full bg-slate-900">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تسجيل الدخول - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-sans antialiased text-gray-900 selection:bg-china-red selection:text-white">
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8 relative overflow-hidden bg-slate-900">
        <!-- Background Effects -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5">
        </div>
        <div
            class="absolute top-0 left-1/2 w-[1000px] h-[500px] bg-china-red/20 blur-[120px] rounded-full -translate-x-1/2 -translate-y-1/2">
        </div>

        <div class="sm:mx-auto sm:w-full sm:max-w-sm z-10">
            <h2 class="mt-10 text-center text-3xl font-bold leading-9 tracking-tight text-white mb-2">تسجيل الدخول</h2>
            <p class="text-center text-china-gold/80 text-lg">نظام وكلاء التأشيرات الصينية</p>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm z-10">
            <div
                class="bg-white/5 backdrop-blur-xl rounded-2xl p-8 shadow-2xl ring-1 ring-white/10 border-t border-white/10">
                <form class="space-y-6" action="{{ route('login') }}" method="POST">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-white text-right">البريد
                            الإلكتروني</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email" required dir="ltr"
                                class="block w-full rounded-lg border-0 bg-white/5 py-2.5 px-3 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6 placeholder-gray-500 text-left">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-400 text-right">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-medium leading-6 text-white">كلمة
                                المرور</label>
                        </div>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" autocomplete="current-password"
                                required dir="ltr"
                                class="block w-full rounded-lg border-0 bg-white/5 py-2.5 px-3 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-china-red sm:text-sm sm:leading-6 placeholder-gray-500 text-left">
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-400 text-right">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-lg bg-china-red px-3 py-2.5 text-sm font-bold leading-6 text-white shadow-lg hover:bg-china-dark hover:shadow-china-red/20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-china-red transition-all duration-300 transform hover:-translate-y-0.5">
                            دخول النظام
                        </button>
                    </div>
                </form>
            </div>

            <p class="mt-10 text-center text-xs text-gray-500">
                جميع الحقوق محفوظة &copy; {{ date('Y') }}
            </p>
        </div>
    </div>
</body>

</html>