@extends('layouts.master')

@section('title', 'FAQ - Pertanyaan yang Sering Diajukan')

@section('content')
<div class="bg-gray-100 min-h-screen p-6">
    <!-- Header -->
    <div class="text-center">
        <h1 class="text-3xl font-bold">FAQ</h1>
        <p class="text-gray-600 mt-2">Lihat pertanyaan yang sering diajukan atau cari solusi dengan cepat!</p>
    </div>

    <!-- Search Bar -->
    <div class="flex justify-center mt-4">
        <form action="{{ route('user.faq') }}" method="GET" class="flex w-full max-w-xl">
            <input type="text" name="search" value="{{ request('search') }}" class="border border-gray-400 rounded-l-lg px-4 py-2 w-full" placeholder="Cari pertanyaan atau kata kunci...">
            <button type="submit" class="bg-black text-white px-6 py-2 rounded-r-lg">Cari</button>
        </form>
    </div>

    <!-- FAQ List (Accordion) -->
    <div class="max-w-4xl mx-auto mt-8 space-y-4">
        @forelse($faqs as $faq)
            <div x-data="{ open: false }" class="bg-white rounded-lg shadow-sm border">
                <h2>
                    <button type="button" @click="open = !open" class="flex items-center justify-between w-full p-5 font-medium text-left text-gray-700">
                        <span>{{ $faq->title }}</span>
                        <svg :class="{'rotate-180': open, 'rotate-0': !open}" class="w-6 h-6 shrink-0 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                </h2>
                <div x-show="open" class="p-5 border-t prose max-w-none text-gray-600">
                    {!! nl2br(e($faq->content)) !!}
                </div>
            </div>
        @empty
            <div class="text-center p-8 bg-white rounded-lg shadow-sm">
                <p class="text-gray-500">Tidak ada FAQ yang cocok dengan pencarian Anda.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
