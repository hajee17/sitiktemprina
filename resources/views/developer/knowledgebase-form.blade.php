@extends('layouts.developer')

@section('title', isset($knowledge) ? 'Edit Artikel' : 'Buat Artikel Baru')

@section('content')

<div class="p-6 bg-gray-50 min-h-screen" x-data="{ type: '{{ old('type', $knowledge->type ?? 'blog') }}' }">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">{{ isset($knowledge) ? 'Edit Artikel' : 'Buat Artikel' }}</h1>
            <a href="{{ route('developer.knowledgebase.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-medium">Kembali</a>
        </div>

        <div class="bg-white p-8 rounded-xl shadow-md border">
            <form action="{{ isset($knowledge) ? route('developer.knowledgebase.update', $knowledge->id) : route('developer.knowledgebase.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($knowledge))
                    @method('PUT')
                @endif

                <div class="space-y-6">
                    <!-- Judul -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Judul Artikel</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $knowledge->title ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tipe Konten -->
                    <div>
                         <label for="type" class="block text-sm font-medium text-gray-700">Tipe Konten</label>
                         <select name="type" id="type" x-model="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="blog">Blog/Artikel Teks</option>
                            <option value="pdf">Upload PDF</option>
                            <option value="video">Embed Video (YouTube/GDrive)</option>
                         </select>
                         @error('type') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div x-show="type === 'blog'">
                        <label for="content_blog" class="block text-sm font-medium text-gray-700">Konten Artikel</label>
                        <textarea name="content" id="content_blog" :disabled="type !== 'blog'" rows="12" class="mt-1 block w-full border-gray-300 rounded-md" placeholder="Tulis konten artikel di sini.">{{ old('content', isset($knowledge) && $knowledge->type == 'blog' ? $knowledge->content : '') }}</textarea>
                        @error('content') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div x-show="type === 'pdf'">
                        <label for="file_path" class="block text-sm font-medium text-gray-700">Upload File PDF</label>
                        <input type="file" name="file_path" id="file_path" :disabled="type !== 'pdf'" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-gray-100">
                        @if(isset($knowledge) && $knowledge->file_path)
                            <p class="text-xs text-gray-500 mt-1">File saat ini: <a href="{{ asset('storage/'.$knowledge->file_path) }}" target="_blank" class="text-blue-600">{{ basename($knowledge->file_path) }}</a>. Upload file baru untuk menggantinya.</p>
                        @endif
                        @error('file_path') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                     <div x-show="type === 'video'">
                        <label for="content_video" class="block text-sm font-medium text-gray-700">URL Video</label>
                        <input type="url" name="content" id="content_video" :disabled="type !== 'video'" value="{{ old('content', isset($knowledge) && $knowledge->type == 'video' ? $knowledge->content : '') }}" class="mt-1 block w-full border-gray-300 rounded-md" placeholder="https://www.youtube.com/watch?v=...">
                        @error('content') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tags (Kategori)</label>
                        <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($tags as $tag)
                                <label class="flex items-center">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="rounded border-gray-300" @if(isset($knowledge) && $knowledge->tags->contains($tag->id)) checked @endif>
                                    <span class="ml-2 text-sm text-gray-600">{{ $tag->name }}</span>
                                </label>
                            @endforeach
                        </div>
                         @error('tags') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-black text-white rounded-md font-semibold hover:bg-gray-800">{{ isset($knowledge) ? 'Simpan' : 'Publikasikan' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
