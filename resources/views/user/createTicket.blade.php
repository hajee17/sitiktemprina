@extends('layouts.master')

@section('title', 'Buat Tiket Baru - Temprina SITIK')

@section('content')
<div class="max-w-4xl mx-auto my-10 bg-white border border-gray-200 shadow-lg rounded-2xl p-8">
    <h2 class="text-2xl font-bold text-center mb-4">Buat Tiket Baru</h2>
    <p class="text-center text-gray-500 mb-8">Silahkan isi formulir berikut untuk membuat tiket baru.</p>

    {{-- PENYESUAIAN: Route disesuaikan dengan standar resource controller --}}
    <form action="{{ route('user.tickets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Judul Tiket --}}
        <div>
            {{-- PENYESUAIAN: for dan name diubah menjadi 'title' --}}
            <label for="title" class="block font-semibold text-gray-700 mb-1">Judul Tiket*</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Kategori Tiket --}}
            <div>
                {{-- PENYESUAIAN: for dan name diubah menjadi 'category_id' --}}
                <label for="category_id" class="block font-semibold text-gray-700 mb-1">Kategori Tiket*</label>
                <select id="category_id" name="category_id" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Pilih Kategori Tiket</option>
                    {{-- PENYESUAIAN: Opsi dinamis dari controller, menggunakan id dan name --}}
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Prioritas Tiket --}}
            <div>
                {{-- PENYESUAIAN: Field baru ditambahkan sesuai kebutuhan controller --}}
                <label for="priority_id" class="block font-semibold text-gray-700 mb-1">Prioritas*</label>
                <select id="priority_id" name="priority_id" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Pilih Tingkat Prioritas</option>
                     @foreach ($priorities as $priority)
                        <option value="{{ $priority->id }}" {{ old('priority_id') == $priority->id ? 'selected' : '' }}>
                            {{ $priority->name }}
                        </option>
                    @endforeach
                </select>
                @error('priority_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Deskripsi --}}
        <div>
            {{-- PENYESUAIAN: for dan name diubah menjadi 'description' --}}
            <label for="description" class="block font-semibold text-gray-700 mb-1">Deskripsi Masalah*</label>
            <textarea id="description" name="description" rows="5" required class="w-full border border-gray-300 rounded-lg p-3 resize-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" placeholder="Jelaskan masalah yang Anda alami secara detail.">{{ old('description') }}</textarea>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Lampiran --}}
        <div>
            {{-- PENYESUAIAN: name diubah menjadi 'attachment' --}}
            <label for="attachment" class="block font-semibold text-gray-700 mb-1">Lampirkan Bukti (Opsional)</label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                <input type="file" name="attachment" id="attachment" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:rounded-md file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                <p class="text-xs mt-2 text-gray-400">JPEG, JPG, PNG, PDF, DOC, DOCX. Maksimal 5MB.</p>
            </div>
            @error('attachment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-center gap-4 mt-8 border-t pt-6">
            <a href="{{ route('user.tickets.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-full hover:bg-gray-200">Batal</a>
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700">Buat Tiket</button>
        </div>
    </form>
</div>
@endsection