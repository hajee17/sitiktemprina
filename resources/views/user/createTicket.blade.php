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

        <div>
            <label for="sbu_input" class="block font-semibold text-gray-700 mb-1">Sub Bisnis Unit*</label>
            <div class="relative combobox-container" data-name="sbu_id" data-items="{{ json_encode($sbus) }}" data-old-value="{{ old('sbu_id') }}">
                <input type="hidden" name="sbu_id" class="combobox-hidden-input">
                <input
                    type="text"
                    id="sbu_input"
                    class="w-full border border-gray-300 rounded-lg p-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 combobox-input"
                    placeholder="Pilih Sub Bisnis Unit Anda"
                    autocomplete="off"
                >
                <ul class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto mt-1 hidden combobox-list">
                    </ul>
            </div>
        </div>

        <div>
            <label for="department_input" class="block font-semibold text-gray-700 mb-1">Divisi / Departemen*</label>
            <div class="relative combobox-container" data-name="department_id" data-items="{{ json_encode($departments) }}" data-old-value="{{ old('department_id') }}">
                <input type="hidden" name="department_id" class="combobox-hidden-input">
                <input
                    type="text"
                    id="department_input"
                    class="w-full border border-gray-300 rounded-lg p-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 combobox-input"
                    placeholder="Pilih Divisi / Departemen Anda"
                    autocomplete="off"
                >
                <ul class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto mt-1 hidden combobox-list">
                    </ul>
            </div>
        </div>

        <div>
            <label for="title" class="block font-semibold text-gray-700 mb-1">Judul Tiket*</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required class="w-full border border-gray-300 rounded-lg p-3">
        </div>

        <div>
            <label for="category_input" class="block font-semibold text-gray-700 mb-1">Kategori Tiket*</label>
            <div class="relative combobox-container" data-name="category_id" data-items="{{ json_encode($categories) }}" data-old-value="{{ old('category_id') }}">
                <input type="hidden" name="category_id" class="combobox-hidden-input">
                <input
                    type="text"
                    id="category_input"
                    class="w-full border border-gray-300 rounded-lg p-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 combobox-input"
                    placeholder="Pilih Kategori Tiket"
                    autocomplete="off"
                >
                <ul class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto mt-1 hidden combobox-list">
                    </ul>
            </div>
        </div>

        <div>
            <label for="description" class="block font-semibold text-gray-700 mb-1">Deskripsi Masalah*</label>
            <textarea id="description" name="description" rows="5" required class="w-full border border-gray-300 rounded-lg p-3 resize-none" placeholder="Jelaskan masalah yang Anda alami secara detail.">{{ old('description') }}</textarea>
        </div>

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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.combobox-container').forEach(container => {
            const hiddenInput = container.querySelector('.combobox-hidden-input');
            const input = container.querySelector('.combobox-input');
            const list = container.querySelector('.combobox-list');
            const allItems = Object.entries(JSON.parse(container.dataset.items)).map(([id, name]) => ({ id: id, name: name }));
            let selectedId = container.dataset.oldValue || '';

            if (selectedId) {
                const selectedItem = allItems.find(item => item.id == selectedId);
                if (selectedItem) {
                    input.value = selectedItem.name;
                    hiddenInput.value = selectedItem.id;
                }
            }

            const filterItems = () => {
                const query = input.value.toLowerCase();
                list.innerHTML = ''; 

                const filtered = allItems.filter(item =>
                    item.name.toLowerCase().includes(query)
                );

                if (filtered.length > 0 && query !== input.dataset.selectedText) {
                    filtered.forEach(item => {
                        const listItem = document.createElement('li');
                        listItem.classList.add('p-3', 'cursor-pointer', 'hover:bg-blue-500', 'hover:text-white');
                        if (selectedId == item.id) {
                            listItem.classList.add('bg-blue-600', 'text-white');
                        }
                        listItem.textContent = item.name;
                        listItem.dataset.id = item.id;
                        listItem.dataset.name = item.name;
                        list.appendChild(listItem);
                    });
                    list.classList.remove('hidden');
                } else {
                    list.classList.add('hidden');
                }
            };

            const selectItem = (id, name) => {
                input.value = name;
                hiddenInput.value = id;
                selectedId = id;
                list.classList.add('hidden');
                input.dataset.selectedText = name; 
            };

            input.addEventListener('focus', () => {
                filterItems();
            });

            input.addEventListener('input', () => {
                delete input.dataset.selectedText;
                filterItems();
            });

            list.addEventListener('click', (event) => {
                const clickedItem = event.target.closest('li');
                if (clickedItem && clickedItem.dataset.id && clickedItem.dataset.name) {
                    selectItem(clickedItem.dataset.id, clickedItem.dataset.name);
                }
            });

            document.addEventListener('click', (event) => {
                if (!container.contains(event.target)) {
                    list.classList.add('hidden');
                }
            });
        });
    });
</script>
@endpush