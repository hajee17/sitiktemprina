@extends('layouts.master')

@section('content')
{{--<div class="container">
    <h1 class="text-center my-4">Tiket Saya</h1>
    <p class="text-center">Berikut adalah tiket yang telah Anda buat. Kami akan segera menangani setiap permintaan Anda!</p>

    @if($tickets->count() > 0)
        <div class="d-flex flex-column align-items-center">
            @foreach($tickets as $ticket)
                <div class="card mb-3" style="width: 80%; border-radius: 12px; border: 1px solid #ddd;">
                    <div class="card-body d-flex">
                        <img src="{{ asset('images/router.jpg') }}" alt="Gambar Tiket" class="rounded" style="width: 120px; height: 120px; object-fit: cover;">
                        
                        <div class="ms-3" style="flex-grow: 1;">
                            <h5 class="card-title text-primary">{{ $ticket->code }}</h5>
                            <p class="mb-1"><strong>Tanggal Dibuat:</strong> {{ $ticket->created_at->format('d M Y') }}</p>
                            <p class="mb-1"><strong>Lokasi:</strong> {{ $ticket->location }}</p>
                            <p class="text-muted">{{ Str::limit($ticket->description, 100, '...') }}</p>
                        </div>

                        <div class="d-flex align-items-center">
                            @php
                                $statusColors = [
                                    'Diverifikasi' => 'primary',
                                    'Diproses' => 'warning',
                                    'Selesai' => 'success',
                                    'Dibatalkan' => 'danger'
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$ticket->status] ?? 'secondary' }} p-2" style="border-radius: 8px;">
                                {{ $ticket->status }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center">
            <p class="mt-4 text-muted">Belum ada tiket yang Anda buat.</p>
        </div>
    @endif
</div>--}}
<div class="container my-5">
    <h1 class="text-center mb-3">Tiket Saya</h1>
    <p class="text-center">Berikut adalah tiket yang telah Anda buat. Kami akan segera menangani setiap permintaan Anda!</p>

    <div class="d-flex flex-column align-items-center">
        
        <!-- Tiket 1 -->
        <div class="card mb-3" style="width: 80%; border-radius: 12px; border: 1px solid #ddd;">
            <div class="card-body d-flex">
                <img src="{{ asset('images/router.jpg') }}" alt="Gambar Tiket" class="rounded" style="width: 120px; height: 120px; object-fit: cover;">
                
                <div class="ms-3" style="flex-grow: 1;">
                    <h5 class="card-title text-primary">NET130325001</h5>
                    <p class="mb-1"><strong>Tanggal Dibuat:</strong> 13 Maret 2025</p>
                    <p class="mb-1"><strong>Lokasi:</strong> Ruang Kantor B JPM</p>
                    <p class="text-muted">Sejak pagi ini, koneksi internet di ruang kantor B JPM sering terputus dan mengalami kecepatan lambat...</p>
                </div>

                <div class="d-flex align-items-center">
                    <span class="badge bg-primary p-2" style="border-radius: 8px;">Diverifikasi</span>
                </div>
            </div>
        </div>

        <!-- Tiket 2 -->
        <div class="card mb-3" style="width: 80%; border-radius: 12px; border: 1px solid #ddd;">
            <div class="card-body d-flex">
                <img src="{{ asset('images/router.jpg') }}" alt="Gambar Tiket" class="rounded" style="width: 120px; height: 120px; object-fit: cover;">
                
                <div class="ms-3" style="flex-grow: 1;">
                    <h5 class="card-title text-primary">NET130325002</h5>
                    <p class="mb-1"><strong>Tanggal Dibuat:</strong> 10 Maret 2025</p>
                    <p class="mb-1"><strong>Lokasi:</strong> Ruang Server Lantai 2</p>
                    <p class="text-muted">Switch di ruang server mengalami gangguan sejak malam tadi...</p>
                </div>

                <div class="d-flex align-items-center">
                    <span class="badge bg-warning text-dark p-2" style="border-radius: 8px;">Diproses</span>
                </div>
            </div>
        </div>

        <!-- Tiket 3 -->
        <div class="card mb-3" style="width: 80%; border-radius: 12px; border: 1px solid #ddd;">
            <div class="card-body d-flex">
                <img src="{{ asset('images/router.jpg') }}" alt="Gambar Tiket" class="rounded" style="width: 120px; height: 120px; object-fit: cover;">
                
                <div class="ms-3" style="flex-grow: 1;">
                    <h5 class="card-title text-primary">NET130325003</h5>
                    <p class="mb-1"><strong>Tanggal Dibuat:</strong> 7 Maret 2025</p>
                    <p class="mb-1"><strong>Lokasi:</strong> Ruang Meeting Utama</p>
                    <p class="text-muted">Proyektor tidak dapat terhubung ke jaringan Wi-Fi...</p>
                </div>

                <div class="d-flex align-items-center">
                    <span class="badge bg-success p-2" style="border-radius: 8px;">Selesai</span>
                </div>
            </div>
        </div>

        <!-- Tiket 4 -->
        <div class="card mb-3" style="width: 80%; border-radius: 12px; border: 1px solid #ddd;">
            <div class="card-body d-flex">
                <img src="{{ asset('images/router.jpg') }}" alt="Gambar Tiket" class="rounded" style="width: 120px; height: 120px; object-fit: cover;">
                
                <div class="ms-3" style="flex-grow: 1;">
                    <h5 class="card-title text-primary">NET130325004</h5>
                    <p class="mb-1"><strong>Tanggal Dibuat:</strong> 5 Maret 2025</p>
                    <p class="mb-1"><strong>Lokasi:</strong> Ruang Kerja Divisi IT</p>
                    <p class="text-muted">Komputer utama mengalami crash dan tidak bisa booting...</p>
                </div>

                <div class="d-flex align-items-center">
                    <span class="badge bg-danger p-2" style="border-radius: 8px;">Dibatalkan</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
