<!DOCTYPE html>
<html lang="mn">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>МБҮА ТББ - VI Чуулган</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-image: url('{{ asset('images/construction2.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-900">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div
            class="max-w-3xl bg-white bg-opacity-95 backdrop-blur-sm shadow-2xl rounded-2xl p-10 text-center drop-shadow-lg">
            <h1 class="text-5xl font-extrabold text-gray-900 mb-6 tracking-tight leading-tight">МБҮА ТББ - VI Чуулган
            </h1>
            <p class="text-lg text-gray-800 mb-4 leading-relaxed">
                “Монголын Барилгын Үндэсний Ассоциаци ТББ”-ын бүх гишүүдийн чуулган<br />
                <strong class="text-blue-700">2025.06.20-ны өдөр</strong>.
            </p>
            <p class="text-gray-700 mb-4 font-semibold text-lg">Гишүүдийн сонгууль</p>
            <p class="text-gray-700 mb-8 max-w-xl mx-auto leading-relaxed">
                Та системд нэвтрэн нэр дэвшигчдийг үзэн, өөрийн саналаа өгөөрэй.
            </p>

            @auth
                <a href="{{ auth()->user()->is_admin ? route('admin.candidates') : route('dashboard') }}"
                    class="inline-block bg-blue-600 hover:bg-blue-700 transition-colors duration-300 text-white font-semibold px-8 py-3 rounded-full shadow-md focus:outline-none focus:ring-4 focus:ring-blue-400">
                    Оролцох
                </a>
            @else
                <div class="space-x-6 text-lg">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">Нэвтрэх</a>
                    <span class="text-gray-500">|</span>
                    <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-medium">Бүртгүүлэх</a>
                </div>
            @endauth

            <p class="text-sm text-gray-500 mt-12 select-none">© {{ date('Y') }} Монголын Барилгын Үндэсний
                Ассоциаци ТББ</p>
        </div>
    </div>
</body>

</html>
