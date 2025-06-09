@extends('layouts.master')

@section('title', 'Detail Tiket #' . $ticket->id)

@section('content')
<div style="width: 100%; min-height: 100vh; background: #F3F2F2; display: flex; flex-direction: column; align-items: center; padding: 40px;">
    {{-- Menampilkan pesan sukses atau error --}}
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; width: 800px;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; width: 800px;">{{ session('error') }}</div>
    @endif

    <div style="width: 800px; background: white; padding: 24px; border-radius: 8px; box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.1);">
        
        {{-- PENYESUAIAN: Semua data header dibuat dinamis --}}
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 16px;">
            <div>
                <h2 style="color: #0089D0; margin: 0; font-family: monospace, sans-serif; font-size: 1.25rem;">TIKET #{{ $ticket->id }}</h2>
                <p style="margin: 0; font-size: 14px; color: #666;">Dibuat pada: {{ $ticket->created_at->format('d M Y, H:i') }}</p>
            </div>
            <span style="color: white; padding: 5px 12px; border-radius: 16px; font-size: 14px; font-weight: bold; {{ 
                match($ticket->status->name) {
                    'Open' => 'background: #0089D0;',
                    'In Progress' => 'background: #f59e0b;',
                    'Closed' => 'background: #16a34a;',
                    default => 'background: #6b7280;'
                } 
            }}">
                {{ $ticket->status->name }}
            </span>
        </div>

        {{-- PENYESUAIAN: Semua data info dibuat dinamis --}}
        <div style="margin-top: 20px; font-size: 15px; line-height: 1.6;">
            <p><strong>Pembuat Tiket:</strong> {{ $ticket->author->name }} ({{ $ticket->author->position->name ?? 'N/A' }})</p>
            <p><strong>Lokasi:</strong> {{ $ticket->sbu->name ?? 'N/A' }}</p>
            <p><strong>Kategori Tiket:</strong> {{ $ticket->category->name ?? 'N/A' }}</p>
        </div>

        {{-- PENYESUAIAN: Gambar bukti dinamis dari lampiran --}}
        @if($ticket->attachments->isNotEmpty())
        <div style="margin-top: 20px;">
            <p style="font-weight: bold; font-size: 15px; margin-bottom: 10px;">Lampiran:</p>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                @foreach($ticket->attachments as $attachment)
                <a href="{{ Storage::url($attachment->path) }}" target="_blank">
                    <img src="{{ Storage::url($attachment->path) }}" style="width: 240px; height: 160px; object-fit: cover; border-radius: 8px;">
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
            <p style="font-weight: bold; font-size: 15px; margin-bottom: 10px;">Deskripsi Masalah:</p>
            <div style="font-size: 14px; color: #333; line-height: 1.7;">
                {!! nl2br(e($ticket->description)) !!}
            </div>
        </div>

        {{-- PENYESUAIAN: Tombol dibungkus form dan hanya tampil jika status 'Open' --}}
        @if($ticket->status->name === 'Open')
        <div style="margin-top: 30px; display: flex; justify-content: flex-end; padding-top: 20px; border-top: 1px solid #eee;">
            <form action="{{ route('user.tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan tiket ini? Aksi ini tidak dapat diurungkan.')">
                @csrf
                @method('DELETE')
                <button type="submit" style="padding: 12px 20px; border: 2px solid #FF5A00; color: #FF5A00; background: transparent; border-radius: 8px; font-weight: bold; cursor: pointer; transition: all 0.2s;"
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