@extends('layouts.master')

@section('content')
<div style="width: 100%; min-height: 100vh; background: #F3F2F2; display: flex; justify-content: center; align-items: center; padding: 40px;">

    <div style="width: 800px; background: white; padding: 24px; border-radius: 8px; box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.25);">
        
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                {{-- Data Dinamis --}}
                <h2 style="color: #0089D0; margin: 0;">#{{ $ticket->id }} - {{ $ticket->title }}</h2>
                <p style="margin: 0; font-size: 14px; color: #666;">{{ $ticket->created_at->format('d F Y') }}</p>
            </div>
            {{-- Status Dinamis --}}
            <span style="background: #0089D0; color: white; padding: 5px 10px; border-radius: 8px; font-size: 14px;">{{ optional($ticket->status)->name ?? 'N/A' }}</span>
        </div>

        <!-- Informasi Tiket -->
        <div style="margin-top: 20px;">
            {{-- Data Dinamis --}}
            <p><strong>Pembuat Tiket:</strong> {{ optional($ticket->author)->name ?? 'N/A' }} ({{ optional(optional($ticket->author)->role)->name ?? 'N/A' }})</p>
            <p><strong>Lokasi:</strong> {{ optional($ticket->department)->name ?? 'N/A' }}</p>
            <p><strong>Kategori Tiket:</strong> {{ optional($ticket->category)->name ?? 'N/A' }}</p>
        </div>

        <!-- Gambar Bukti -->
        @if($ticket->attachments->isNotEmpty())
        <div style="display: flex; gap: 10px; margin-top: 15px;">
            @foreach($ticket->attachments as $attachment)
            <a href="{{ asset('storage/' . $attachment->path) }}" target="_blank">
                <img src="{{ asset('storage/' . $attachment->path) }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
            </a>
            @endforeach
        </div>
        @endif

        <!-- Deskripsi Masalah -->
        <div style="margin-top: 15px; font-size: 14px; color: #333;">
            {{ $ticket->description }}
        </div>

        <!-- Tombol Batalkan Tiket (Sekarang Fungsional) -->
        @if(!in_array(optional($ticket->status)->name, ['Closed', 'Cancelled']))
        <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
            <form action="{{ route('user.tickets.cancel', $ticket->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan tiket ini?')">
                @csrf
                <button type="submit" style="padding: 12px 20px; border: 2px solid #FF5A00; color: #FF5A00; background: transparent; border-radius: 8px; font-weight: bold; cursor: pointer;"
                    onmouseover="this.style.background='#FF5A00'; this.style.color='white';"
                    onmouseout="this.style.background='transparent'; this.style.color='#FF5A00';">
                    Batalkan Tiket
                </button>
            </form>
        </div>
        @endif
    </div>

</div>
@endsection
