@extends('layouts.master')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white border border-gray-200 shadow-lg rounded-2xl p-8">
    <h2 class="text-2xl font-bold text-center mb-4">Buat Tiket Baru</h2>
    <p class="text-center text-gray-500 mb-8">Silahkan isi formulir berikut untuk membuat tiket baru.</p>

    <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Sub Bisnis Unit --}}
        <div>
            <label for="SBU" class="block font-semibold text-gray-700 mb-1">Sub Bisnis Unit*</label>
            <select id="SBU" name="SBU" required class="w-full border border-gray-300 rounded-lg p-3">
                <option value="">Pilih Sub Bisnis Unit Anda</option>
                <option value="IT Support">IT Support</option>
                <option value="Finance">Finance</option>
            </select>
        </div>

        {{-- Divisi --}}
        <div>
            <label for="Dept" class="block font-semibold text-gray-700 mb-1">Divisi / Departemen*</label>
            <select id="Dept" name="Dept" required class="w-full border border-gray-300 rounded-lg p-3">
                <option value="">Pilih Divisi / Departemen Anda</option>
                @foreach ($departments as $department)
                    <option value="{{ $department }}">{{ $department}}</option>
                @endforeach
            </select>
        </div>

        {{-- Jabatan --}}
        <div>
            <label for="Position" class="block font-semibold text-gray-700 mb-1">Jabatan*</label>
            <select id="Position" name="Position" required class="w-full border border-gray-300 rounded-lg p-3">
                <option value="">Pilih Jabatan Anda</option>
                @foreach ($positions as $position)
                    <option value="{{ $position }}">{{ $position }}</option>
                @endforeach
            </select>
        </div>

        {{-- Judul Tiket --}}
        <div>
            <label for="Judul_Tiket" class="block font-semibold text-gray-700 mb-1">Judul Tiket*</label>
            <input type="text" id="Judul_Tiket" name="Judul_Tiket" required class="w-full border border-gray-300 rounded-lg p-3">
        </div>

        {{-- Kategori --}}
        <div>
            <label for="Category" class="block font-semibold text-gray-700 mb-1">Kategori Tiket*</label>
            <select id="Category" name="Category" required class="w-full border border-gray-300 rounded-lg p-3">
                <option value="">Pilih Kategori Tiket</option>
                @foreach ($categories as $category)
                    <option value="{{ $category}}">{{ $category}}</option>
                @endforeach
            </select>
        </div>

        {{-- Lokasi --}}
        <div>
            <label for="Location" class="block font-semibold text-gray-700 mb-1">Lokasi*</label>
            <input type="text" id="Location" name="Location" required class="w-full border border-gray-300 rounded-lg p-3">
        </div>

        {{-- Deskripsi --}}
        <div>
            <label for="Desc" class="block font-semibold text-gray-700 mb-1">Deskripsi Masalah*</label>
            <textarea id="Desc" name="Desc" rows="5" required class="w-full border border-gray-300 rounded-lg p-3 resize-none" placeholder="Jelaskan masalah yang Anda alami secara detail."></textarea>
        </div>

        {{-- Lampiran --}}
        <div>
            <label for="Attc" class="block font-semibold text-gray-700 mb-1">Lampirkan Bukti*</label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                <input type="file" name="Attc" id="Attc" accept=".jpeg,.jpg,.png,.pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:rounded-md file:bg-gray-100 file:text-gray-700">
                <p class="text-xs mt-2 text-gray-400">JPEG, JPG, PNG, PDF. Maksimal 2MB per file.</p>
            </div>
        </div>

        <div class="flex justify-center gap-4 mt-8">
            <button type="reset" class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-full hover:bg-gray-200">Reset</button>
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700">Buat Tiket</button>
        </div>
    </form>
</div>
@endsection
