@extends('layouts.master')

@section('title', 'Detail Tiket #' . $ticket->id)

@section('content')
<div style="width: 100%; padding: 40px 20px; background: #F3F2F2;">
    <div style="max-width: 900px; margin: auto; display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        
        <!-- Kolom Kiri: Detail dan Form Komentar -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Detail Tiket -->
            <div style="background: white; padding: 24px; border-radius: 8px; box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <h2 style="color: #333; margin: 0; font-size: 20px; font-weight: bold;">{{ $ticket->title }}</h2>
                        <p style="margin: 0; font-size: 14px; color: #666;">ID: #{{ $ticket->id }}</p>
                    </div>
                    @php
                        $statusName = optional($ticket->status)->name ?? 'Unknown';
                        $statusColors = ['Open' => '#3B82F6','In Progress' => '#F59E0B','Closed' => '#10B981','Cancelled' => '#EF4444', 'On Hold' => '#F97316'];
                    @endphp
                    <span style="background: {{ $statusColors[$statusName] ?? '#6B7280' }}; color: white; padding: 5px 12px; border-radius: 99px; font-size: 12px; font-weight: 600;">{{ $statusName }}</span>
                </div>
                <hr style="margin: 16px 0; border-top: 1px solid #E5E7EB;">
                <p style="margin-top: 15px; font-size: 14px; color: #333; white-space: pre-wrap;">{{ $ticket->description }}</p>
                @if($ticket->attachments->isNotEmpty())
                    <div style="margin-top: 16px;">
                        <p style="font-weight: 600; font-size: 14px; margin-bottom: 8px;">Lampiran Awal:</p>
                        @foreach($ticket->attachments as $attachment)
                         <a href="{{ asset('storage/' . $attachment->path) }}" target="_blank" style="color: #3B82F6; text-decoration: underline; font-size: 14px;">{{ basename($attachment->path) }}</a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Form Tambah Komentar -->
            <div style="background: white; padding: 24px; border-radius: 8px; box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.1);">
                <h3 style="font-size: 18px; font-weight: bold; margin: 0 0 16px 0;">Beri Tanggapan atau Jawaban</h3>
                <form action="{{ route('user.comments.store', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <textarea name="message" rows="4" placeholder="Tulis komentar Anda di sini..." style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 8px; margin-bottom: 12px;"></textarea>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <input type="file" name="comment_file" id="comment_file" style="font-size: 12px;">
                        <button type="submit" style="padding: 10px 20px; background: black; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">Kirim</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Kolom Kanan: Info & Riwayat Diskusi -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <div style="background: white; padding: 24px; border-radius: 8px; box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.1);">
                <h3 style="font-size: 16px; font-weight: bold; margin: 0 0 16px 0; border-bottom: 1px solid #E5E7EB; padding-bottom: 12px;">Detail Info</h3>
                <div style="font-size: 14px; color: #374151; line-height: 1.8;">
                    <p><strong>Pelapor:</strong><br>{{ optional($ticket->author)->name }}</p>
                    <p><strong>Kategori:</strong><br>{{ optional($ticket->category)->name }}</p>
                    <p><strong>Prioritas:</strong><br>{{ optional($ticket->priority)->name }}</p>
                    <p><strong>Penanggung Jawab:</strong><br>{{ optional($ticket->assignee)->name ?? 'Belum ada' }}</p>
                </div>
            </div>
            @if(isset($recommendations) && $recommendations->isNotEmpty())
            <div style="background: white; padding: 24px; border-radius: 8px; box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.1);">
                <h3 style="font-size: 16px; font-weight: bold; margin: 0 0 16px 0; border-bottom: 1px solid #E5E7EB; padding-bottom: 12px;">💡 Rekomendasi Solusi</h3>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @foreach($recommendations as $kb)
                        <a href="{{ route('user.knowledgebase.show', $kb->id) }}" style="text-decoration: none; color: inherit;">
                            <div style="padding: 12px; background: #F9FAFB; border-radius: 8px; border: 1px solid #E5E7EB; hover: border-color: #3B82F6;">
                                <p style="font-weight: 600; font-size: 14px; margin: 0 0 4px 0;">{{ $kb->title }}</p>
                                <p style="font-size: 13px; color: #6B7280; margin: 0;">{{ Str::limit($kb->content, 80) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            <div style="background: white; padding: 24px; border-radius: 8px; box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.1);">
                <h3 style="font-size: 16px; font-weight: bold; margin: 0 0 16px 0; border-bottom: 1px solid #E5E7EB; padding-bottom: 12px;">Riwayat Diskusi</h3>
                <div style="max-height: 400px; overflow-y: auto; padding-right: 10px;">
                    @forelse($ticket->comments->sortBy('created_at') as $comment)
                    <div style="display: flex; gap: 12px; margin-bottom: 16px;">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(optional($comment->author)->name) }}&background=random" style="width: 40px; height: 40px; border-radius: 50%;">
                        <div>
                            <p style="font-weight: 600; font-size: 14px; margin: 0;">{{ optional($comment->author)->name }} <span style="font-weight: normal; color: #6B7280; font-size: 12px;">• {{ $comment->created_at->diffForHumans() }}</span></p>
                            <div style="background: #F3F4F6; padding: 12px; border-radius: 8px; margin-top: 4px;">
                                <p style="font-size: 14px; color: #1F2937; margin: 0; white-space: pre-wrap;">{{ $comment->message }}</p>
                                @if($comment->file_path)
                                    <a href="{{ asset('storage/' . $comment->file_path) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $comment->file_path) }}" style="margin-top: 8px; border-radius: 8px; max-width: 200px; max-height: 150px;">
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <p style="text-align: center; font-size: 14px; color: #6B7280;">Belum ada diskusi pada tiket ini.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
