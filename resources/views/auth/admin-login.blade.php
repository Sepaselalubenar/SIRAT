<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <title>SIRAT Admin Login</title>

    @vite(['resources/css/app.css'])

</head>

<body class="bg-gray-100">

<div class="min-h-screen flex">

    <!-- kiri -->

    <div class="hidden lg:block w-1/2">

        <img
            src="https://images.unsplash.com/photo-1519389950473-47ba0277781c"
            class="w-full h-full object-cover"
        >

    </div>

    <!-- kanan -->

    <div class="flex w-full lg:w-1/2 items-center justify-center">

        <div class="bg-white p-10 rounded-xl shadow-lg w-[420px]">

            <h1 class="text-4xl font-bold text-blue-600 mb-2">
                SIRAT
            </h1>

            <p class="text-gray-400 text-sm mb-1">
                Sistem Reservasi Area TULT
            </p>

            <p class="text-gray-700 font-semibold mb-1">
                Masuk sebagai Admin
            </p>

            <p class="text-gray-500 text-sm mb-8">
                Gunakan email dan password admin Anda
            </p>

            @if ($errors->any())
                <div class="bg-red-50 text-red-600 text-sm rounded-lg p-3 mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.store') }}">
                @csrf

                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Masukkan email admin"
                    class="w-full border rounded-lg p-3 mb-4"
                >

                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input
                    type="password"
                    name="password"
                    placeholder="Masukkan password"
                    class="w-full border rounded-lg p-3 mb-6"
                >

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white rounded-lg p-3 hover:bg-blue-700">

                    Masuk

                </button>
            </form>

            <p class="text-center text-sm text-gray-400 mt-6">
                Dosen? <a href="{{ route('login') }}" class="text-blue-600">Masuk di sini</a>
            </p>

        </div>

    </div>

</div>

</body>

</html>