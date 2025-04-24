@extends('layouts.master')

@section('content')
<div class="bg-gray-100 min-h-screen p-6">
    <!-- Header -->
    <div class="text-center">
        <h1 class="text-3xl font-bold">FAQ</h1>
        <p class="text-gray-600 mt-2">Lihat pertanyaan yang sering diajukan atau cari solusi dengan cepat!</p>
    </div>

    <!-- Search Bar -->
    <div class="flex justify-center mt-4">
        <input type="text" class="border border-gray-400 rounded-lg px-4 py-2 w-96" placeholder="Cari pertanyaan atau kata kunci...">
        <button class="bg-black text-white px-6 py-2 ml-2 rounded-lg">Cari</button>
    </div>

    <!-- FAQ Categories -->
    <div class="grid grid-cols-3 gap-6 mt-8 mx-auto max-w-6xl">
        @php
            $categories = [
                ['name' => 'Mesin', 'count' => 3],
                ['name' => 'Perangkat Lunak', 'count' => 5],
                ['name' => 'Perangkat Keras', 'count' => 11],
                ['name' => 'Jaringan', 'count' => 12],
                ['name' => 'Data', 'count' => 7],
                ['name' => 'Support Teknis', 'count' => 4],
            ];
        @endphp

        @foreach($categories as $category)
        <div class="border border-gray-300 rounded-lg shadow-lg bg-white">
            <!-- Header -->
            <div class="bg-blue-500 text-white font-bold text-lg px-4 py-3 flex justify-between">
                <span>{{ $category['name'] }}</span>
                <span>{{ $category['count'] }}</span>
            </div>

            <!-- Content -->
            <div class="p-4">
                <div class="flex justify-between text-sm text-gray-700 mb-2">
                    <span class="font-bold">Rekomendasi</span>
                    <span>Terbaru</span>
                </div>

                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Lorem ipsum morbi et enim</li>
                    <li>• Lorem ipsum dolor sit amet</li>
                    <li>• Vestibulum varius nisi sed magna</li>
                </ul>

                <!-- Button -->
                <div class="mt-4">
                    <a href="#" class="block text-center border border-blue-500 text-blue-500 py-2 rounded-lg font-semibold">Lihat Semua</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

   
</div>
@endsection
