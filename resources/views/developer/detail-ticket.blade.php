@extends('layouts.developer')

@section('content')
<div style="position: relative; width: 1440px; min-height: 1024px; background: #F5F6FA;">
    <!-- Main Content -->
    <div style="position: absolute; left: 280px; top: 64px; right: 20px; bottom: 20px; padding: 40px; overflow-y: auto;">
        
        <h2 style="font-weight: bold; font-size: 24px; margin-bottom: 24px;">Detail Tiket</h2>

        <div style="display: flex; gap: 32px;">

            <!-- Kartu Tiket -->
            <div style="flex: 1; background: #fff; border-radius: 12px; border: 1px solid #DADADA; padding: 32px; display: flex; flex-direction: column; gap: 16px;">

                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <div style="font-size: 20px; font-weight: bold;">Tidak bisa akses internet</div>
                        <div style="color: #555;">NET020425001</div>
                        <div style="font-size: 12px; color: #888;">2 Apr 2025 – 11:12</div>
                    </div>
                    <div style="background-color: #FF5C5C; color: white; font-size: 12px; font-weight: bold; padding: 4px 12px; border-radius: 12px;">Tinggi</div>
                </div>

                <div style="font-size: 14px;">
                    <strong>Pembuat Tiket:</strong> Dinda Ayu – Staf Administrasi<br>
                    <strong>Lokasi:</strong> Lantai 2 – Ruang Keuangan<br>
                    <strong>Kategori Tiket:</strong> Jaringan
                </div>

                <!-- Bukti Foto -->
                <div style="display: flex; gap: 8px;">
                    <img src="{{ asset('images/bukti1.png') }}" style="width: 100px; height: 60px; border-radius: 8px; object-fit: cover;">
                    <img src="{{ asset('images/bukti2.png') }}" style="width: 100px; height: 60px; border-radius: 8px; object-fit: cover;">
                    <img src="{{ asset('images/bukti3.png') }}" style="width: 100px; height: 60px; border-radius: 8px; object-fit: cover;">
                </div>

                <p style="font-size: 14px; color: #333;">
                    Sejak pagi ini, koneksi internet di Lantai 2 - Ruang Keuangan sering terputus dan mengalami kecepatan yang sangat lambat. Beberapa rekan kerja juga melaporkan kesulitan mengakses sistem internal perusahaan dan layanan cloud. Saya sudah mencoba merestart router, tetapi masalah masih terjadi. Mohon dilakukan pengecekan lebih lanjut.
                </p>

                <!-- Form update status -->
                <form method="POST" action="#">
                    @csrf
                    <label for="status" style="font-weight: 600;">Perbarui status tiket<span style="color: red;">*</span></label>
                    <select id="status" name="status" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #CCC; margin-bottom: 16px;">
                        <option value="">-- Perbarui status tiket --</option>
                        <option value="proses">Sedang diproses</option>
                        <option value="selesai">Masalah telah ditangani</option>
                    </select>

                    <label for="penjelasan" style="font-weight: 600;">Penjelasan atau tindakan perbaikan<span style="color: red;">*</span></label>
                    <textarea id="penjelasan" name="penjelasan" required style="width: 100%; height: 100px; padding: 12px; border-radius: 8px; border: 1px solid #CCC; margin-bottom: 16px;"></textarea>

                    <label style="font-weight: 600;">Unggah bukti penyelesaian</label>
                    <div style="width: 100%; padding: 20px; border: 2px dashed #DADADA; text-align: center; border-radius: 12px; font-size: 14px; color: #888; margin-bottom: 24px;">
                        Upload a file or drag and drop<br>
                        JPEG, JPG, PNG, PDF (max 10MB per file)<br>
                        Maksimal 3 file.
                    </div>

                    <button type="submit" style="padding: 12px 24px; background: black; color: white; border-radius: 8px; font-weight: bold;">Simpan Perubahan</button>
                </form>
            </div>

            <!-- Riwayat -->
            <div style="width: 280px;">
                <h3 style="font-weight: bold; font-size: 18px; margin-bottom: 16px;">Riwayat Penanganan</h3>
                <ul style="list-style: none; padding-left: 0; display: flex; flex-direction: column; gap: 12px;">
                    <li style="background: #fff; padding: 12px; border-radius: 8px; border-left: 5px solid #FF5C5C;">
                        <strong>02 April 2025 – 11:45</strong><br>
                        Permasalahan selesai ditangani, menunggu konfirmasi dari pelapor.
                    </li>
                    <li style="background: #fff; padding: 12px; border-radius: 8px; border-left: 5px solid #FF5C5C;">
                        <strong>02 April 2025 – 11:40</strong><br>
                        Masalah ditemukan, sedang dalam tahap perbaikan.
                    </li>
                    <li style="background: #fff; padding: 12px; border-radius: 8px; border-left: 5px solid #FF5C5C;">
                        <strong>02 April 2025 – 11:30</strong><br>
                        Pemeriksaan di lokasi.
                    </li>
                    <li style="background: #fff; padding: 12px; border-radius: 8px; border-left: 5px solid #FF5C5C;">
                        <strong>02 April 2025 – 11:12</strong><br>
                        Tiket dibuat.
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>
@endsection
