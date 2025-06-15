@extends('layouts.developer')

@section('title', 'Kelola Tags')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen" x-data="{ showModal: false, modalMode: 'create', selectedTag: {} }">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Kelola Tags Knowledge Base</h1>
            <button @click="modalMode = 'create'; selectedTag = {}; showModal = true" class="px-4 py-2 bg-black text-white rounded-md font-semibold hover:bg-gray-800 text-sm">
                + Tambah Tag Baru
            </button>
        </div>

        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow-sm overflow-hidden border">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Tag</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Artikel Terkait</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-sm">
                    @forelse($tags as $tag)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $tag->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $tag->knowledge_bases_count }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-medium">
                            <button @click="modalMode = 'edit'; selectedTag = {{ $tag }}; showModal = true" class="text-blue-600 hover:text-blue-900">Edit</button>
                            <form action="{{ route('developer.tags.destroy', $tag->id) }}" method="POST" class="inline ml-4" onsubmit="return confirm('Yakin ingin menghapus tag ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-10 text-center text-gray-500">Belum ada tag yang dibuat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal untuk Tambah/Edit Tag -->
    <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="bg-white rounded-lg w-full max-w-md p-6" @click.away="showModal = false">
            <h2 class="text-xl font-bold mb-4" x-text="modalMode === 'create' ? 'Tambah Tag Baru' : 'Edit Tag'"></h2>
            <form :action="modalMode === 'create' ? '{{ route('developer.tags.store') }}' : `/developer/tags/${selectedTag.id}`" method="POST">
                @csrf
                <template x-if="modalMode === 'edit'">
                    @method('PUT')
                </template>
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Tag</label>
                    <input type="text" name="name" id="name" :value="selectedTag.name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-200 rounded-md">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-black text-white rounded-md font-semibold" x-text="modalMode === 'create' ? 'Simpan' : 'Simpan Perubahan'"></button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
