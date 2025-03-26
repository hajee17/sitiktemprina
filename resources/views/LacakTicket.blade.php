@extends('layouts.master')

@section('content')
<div style="width: 100%; min-height: 100vh; background: #F3F2F2; display: flex; justify-content: center; align-items: center; padding: 40px;">

    <div style="width: 800px; background: white; padding: 24px; border-radius: 8px; box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.25);">
        
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="color: #0089D0; margin: 0;">NET130325001</h2>
                <p style="margin: 0; font-size: 14px; color: #666;">13 Maret 2025</p>
            </div>
            <span style="background: #0089D0; color: white; padding: 5px 10px; border-radius: 8px; font-size: 14px;">Diverifikasi</span>
        </div>

        <!-- Informasi Tiket -->
        <div style="margin-top: 20px;">
            <p><strong>Pembuat Tiket:</strong> Hafidz Irham Ar Ridlo (IT Consultant - Staff Helpdesk)</p>
            <p><strong>Lokasi:</strong> Ruang Kantor B JPM</p>
            <p><strong>Kategori Tiket:</strong> Jaringan</p>
        </div>

        <!-- Gambar Bukti -->
        <div style="display: flex; gap: 10px; margin-top: 15px;">
            <img src="{{ asset('images/sample1.jpg') }}" style="width: 30%; border-radius: 8px;">
            <img src="{{ asset('images/sample2.jpg') }}" style="width: 30%; border-radius: 8px;">
            <img src="{{ asset('images/sample3.jpg') }}" style="width: 30%; border-radius: 8px;">
        </div>

        <!-- Deskripsi Masalah -->
        <div style="margin-top: 15px; font-size: 14px; color: #333;">
            Sejak pagi ini, koneksi internet di ruang kantor B JPM sering terputus dan mengalami kecepatan yang sangat lambat. Beberapa rekan kerja juga melaporkan kesulitan mengakses sistem internal perusahaan dan layanan cloud. Saya sudah mencoba merestart router, tetapi masalah masih terjadi. Mohon dilakukan pengecekan lebih lanjut.
        </div>

        <!-- Tombol Batalkan Tiket -->
        <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
            <button style="padding: 12px 20px; border: 2px solid #FF5A00; color: #FF5A00; background: transparent; border-radius: 8px; font-weight: bold; cursor: pointer;"
                onmouseover="this.style.background='#FF5A00'; this.style.color='white';"
                onmouseout="this.style.background='transparent'; this.style.color='#FF5A00';">
                Batalkan Tiket
            </button>
        </div>

    </div>

</div>
@endsection
