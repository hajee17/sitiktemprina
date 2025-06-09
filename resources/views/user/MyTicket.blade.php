@extends('layouts.master')

@section('content')
<div class="container">
    <h1 class="text-center my-4">Tiket Saya</h1>
    <p class="text-center">Berikut adalah tiket yang telah Anda buat. Kami akan segera menangani setiap permintaan Anda!</p>

    @if($tickets->count() >= 0)
        <div class="d-flex flex-column align-items-center">
            @foreach($tickets as $ticket)
                <div class="card mb-3" style="width: 80%; border-radius: 12px; border: 1px solid #ddd;">
                    <div class="card-body d-flex">
                        <!-- Gambar bisa disesuaikan berdasarkan kategori tiket -->
                        @php
                            $imagePath = 'images/';
                            switch($ticket->Category) {
                                case 'Network':
                                    $imagePath .= 'router.jpg';
                                    break;
                                case 'Hardware':
                                    $imagePath .= 'hardware.jpg';
                                    break;
                                case 'Software':
                                    $imagePath .= 'software.jpg';
                                    break;
                                default:
                                    $imagePath .= 'default.jpg';
                            }
                        @endphp
                        <img src="{{ asset($imagePath) }}" alt="Gambar Tiket" class="rounded" style="width: 120px; height: 120px; object-fit: cover;">
                        
                        <div class="ms-3" style="flex-grow: 1;">
                            <h5 class="card-title text-primary">{{ $ticket->code }}</h5>
                            <p class="mb-1"><strong>Judul:</strong> {{ $ticket->Judul_Tiket }}</p>
                            <p class="mb-1"><strong>Tanggal Dibuat:</strong> {{ date('d M Y', strtotime($ticket->created_at ?? now())) }}</p>
                            <p class="mb-1"><strong>Lokasi:</strong> {{ $ticket->Location }}</p>
                            <p class="mb-1"><strong>Kategori:</strong> {{ $ticket->Category }}</p>
                            <p class="text-muted">{{ Str::limit($ticket->Desc, 100, '...') }}</p>
                        </div>

                        <div class="d-flex align-items-center">
                            @php
                                $statusColors = [
                                    'Diverifikasi' => 'primary',
                                    'Diproses' => 'warning',
                                    'Selesai' => 'success',
                                    'Dibatalkan' => 'danger',
                                    'Pending' => 'info',
                                    'Konfirmasi' => 'secondary'
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$ticket->status] ?? 'secondary' }} p-2" style="border-radius: 8px;">
                                {{ $ticket->status }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Tombol aksi -->
                    <div class="card-footer bg-transparent d-flex justify-content-end">
                        <a href="{{ route('tickets.show', $ticket->ID_Ticket) }}" class="btn btn-sm btn-outline-primary me-2">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        @if($ticket->status == 'Diverifikasi' || $ticket->status == 'Diproses')
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelTicketModal{{ $ticket->ID_Ticket }}">
                                <i class="fas fa-times"></i> Batalkan
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Modal untuk pembatalan tiket -->
                <div class="modal fade" id="cancelTicketModal{{ $ticket->ID_Ticket }}" tabindex="-1" aria-labelledby="cancelTicketModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="cancelTicketModalLabel">Batalkan Tiket</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('tickets.status', $ticket->ID_Ticket) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <p>Apakah Anda yakin ingin membatalkan tiket ini?</p>
                                    <input type="hidden" name="Status" value="Dibatalkan">
                                    <div class="mb-3">
                                        <label for="cancelReason" class="form-label">Alasan Pembatalan</label>
                                        <textarea class="form-control" id="cancelReason" name="Desc" rows="3" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-danger">Batalkan Tiket</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $tickets->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <img src="{{ asset('images/empty-ticket.png') }}" alt="No tickets" class="img-fluid mb-3" style="max-width: 200px;">
            <p class="text-muted fs-5">Belum ada tiket yang Anda buat.</p>
            <a href="{{ route('tickets.create') }}" class="btn btn-primary mt-3">
                <i class="fas fa-plus-circle"></i> Buat Tiket Baru
            </a>
        </div>
    @endif
</div>
@endsection
