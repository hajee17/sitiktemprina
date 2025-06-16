@extends('layouts.master')

@section('content')
<div class="max-w-4xl mx-auto mt-10 mb-10 bg-white border border-gray-200 shadow-lg rounded-2xl p-8">
    <h2 class="text-2xl font-bold text-center mb-4">Buat Tiket Baru</h2>
    <p class="text-center text-gray-500 mb-8">Silahkan isi formulir berikut untuk membuat tiket baru.</p>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 text-red-700 border border-red-200 rounded-lg">
            <strong class="font-bold">Oops! Ada beberapa masalah dengan input Anda:</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('user.tickets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- Sub Bisnis Unit -->
        <div>
            <label for="sbu_id" class="block font-semibold text-gray-700 mb-1">Sub Bisnis Unit*</label>
            <select id="sbu_id" name="sbu_id" required class="w-full border border-gray-300 rounded-lg p-3">
                <option value="">Pilih Sub Bisnis Unit Anda</option>
                @foreach ($sbus as $id => $name)
                    <option value="{{ $id }}" {{ old('sbu_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Divisi -->
        <div>
            <label for="department_id" class="block font-semibold text-gray-700 mb-1">Divisi / Departemen*</label>
            <select id="department_id" name="department_id" required class="w-full border border-gray-300 rounded-lg p-3">
                <option value="">Pilih Divisi / Departemen Anda</option>
                @foreach ($departments as $id => $name)
                    <option value="{{ $id }}" {{ old('department_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Judul Tiket -->
        <div>
            <label for="title" class="block font-semibold text-gray-700 mb-1">Judul Tiket*</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required class="w-full border border-gray-300 rounded-lg p-3">
        </div>

        <!-- Kategori -->
        <div>
            <label for="category_id" class="block font-semibold text-gray-700 mb-1">Kategori Tiket*</label>
            <select id="category_id" name="category_id" required class="w-full border border-gray-300 rounded-lg p-3">
                <option value="">Pilih Kategori Tiket</option>
                @foreach ($categories as $id => $name)
                    <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Deskripsi -->
        <div>
            <label for="description" class="block font-semibold text-gray-700 mb-1">Deskripsi Masalah*</label>
            <textarea id="description" name="description" rows="5" required class="w-full border border-gray-300 rounded-lg p-3 resize-none" placeholder="Jelaskan masalah yang Anda alami secara detail.">{{ old('description') }}</textarea>
        </div>

        <!-- Lampiran -->
        <div>
            <label for="attachments" class="block font-semibold text-gray-700 mb-1">Lampirkan Bukti (Opsional)</label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                <input type="file" name="attachments[]" id="attachments" accept=".jpeg,.jpg,.png,.pdf" multiple class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:rounded-md file:bg-gray-100 file:text-gray-700">
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
