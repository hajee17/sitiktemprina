@extends('layouts.master')

@section('title', 'Akun Saya - Temprina SITIK')

@section('content')
<div style="width: 100%; min-height: 100vh; background: #F3F2F2; display: flex; justify-content: center; align-items: center; padding: 40px;">

    <div style="width: 600px; background: white; padding: 40px; border-radius: 12px; box-shadow: 0px 4px S10px rgba(0, 0, 0, 0.1); text-align: center;">
        
        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align:left;">
                <ul style="margin:0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif

        <div style="position: relative; width: 100%; text-align: center;">
            <div style="width: 100%; height: 120px; background: url('{{ asset('images/banner.png') }}') no-repeat center center; background-size: cover; border-radius: 12px;"></div>
            <img src="{{ asset('images/profile.png') }}" alt="Foto Profil" style="width: 100px; height: 100px; border-radius: 50%; border: 3px solid white; position: relative; top: -50px;">
        </div>

        <div style="display: flex; flex-direction: column; gap: 15px; margin-top: -40px;">
            {{-- PENYESUAIAN: Data pengguna dibuat dinamis --}}
            <div style="text-align: left;">
                <label style="font-weight: bold; font-size: 14px; color: #333;">Nama Lengkap</label>
                <input type="text" value="{{ $user->name }}" disabled style="width: 100%; padding: 10px; border: 1px solid #E4E4E4; border-radius: 8px; background: #F9F9F9; margin-top: 4px;">
            </div>
            <div style="text-align: left;">
                <label style="font-weight: bold; font-size: 14px; color: #333;">Email</label>
                <input type="email" value="{{ $user->email }}" disabled style="width: 100%; padding: 10px; border: 1px solid #E4E4E4; border-radius: 8px; background: #F9F9F9; margin-top: 4px;">
            </div>
            <div style="text-align: left;">
                <label style="font-weight: bold; font-size: 14px; color: #333;">Nomor Telepon</label>
                <input type="text" value="{{ $user->phone ?? '-' }}" disabled style="width: 100%; padding: 10px; border: 1px solid #E4E4E4; border-radius: 8px; background: #F9F9F9; margin-top: 4px;">
            </div>

            {{-- PENYESUAIAN: Tombol menjadi fungsional dengan modal --}}
            <button onclick="document.getElementById('passwordModal').style.display='flex'" style="width: 100%; display: flex; justify-content: space-between; align-items: center; background: #FFF; border: 1px solid #E4E4E4; padding: 12px; border-radius: 8px; cursor: pointer; margin-top: 10px;">
                <span>🔑 Ubah Kata Sandi</span>
                <span style="font-size: 20px;">→</span>
            </button>
            <button onclick="document.getElementById('deleteModal').style.display='flex'" style="width: 100%; padding: 12px; border: 2px solid red; color: red; background: transparent; border-radius: 8px; font-weight: bold; cursor: pointer;" onmouseover="this.style.background='red'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='red';">
                🚨 Hapus Akun
            </button>
        </div>
    </div>
</div>

<div id="passwordModal" style="display: none; position: fixed; z-index: 50; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
    <div style="background: white; padding: 25px; border-radius: 8px; width: 400px; text-align: left;">
        <h3 style="font-size: 1.25rem; font-weight: bold; margin-top: 0;">Ubah Kata Sandi</h3>
        <form action="{{ route('profile.password.update') }}" method="POST" style="margin-top: 20px; display: flex; flex-direction: column; gap: 15px;">
            @csrf
            @method('PATCH')
            <div>
                <label for="current_password">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px; margin-top: 5px;">
            </div>
            <div>
                <label for="password">Kata Sandi Baru</label>
                <input type="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px; margin-top: 5px;">
            </div>
            <div>
                <label for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px; margin-top: 5px;">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                <button type="button" onclick="document.getElementById('passwordModal').style.display='none'" style="background: #6c757d; color: white; padding: 10px 15px; border: none; border-radius: 8px;">Batal</button>
                <button type="submit" style="background: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 8px;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteModal" style="display: none; position: fixed; z-index: 50; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
    <div style="background: white; padding: 25px; border-radius: 8px; width: 400px; text-align: left;">
        <h3 style="font-size: 1.25rem; font-weight: bold; color: red;">Hapus Akun</h3>
        <p style="margin-top: 10px; color: #333;">Aksi ini tidak dapat diurungkan. Semua data Anda akan dihapus permanen. Untuk melanjutkan, masukkan kata sandi Anda.</p>
        <form action="{{ route('profile.destroy') }}" method="POST" style="margin-top: 20px; display: flex; flex-direction: column; gap: 15px;">
            @csrf
            @method('DELETE')
            <div>
                <label for="password_delete">Kata Sandi</label>
                <input type="password" name="password" id="password_delete" required placeholder="Masukkan kata sandi Anda" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px; margin-top: 5px;">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                <button type="button" onclick="document.getElementById('deleteModal').style.display='none'" style="background: #6c757d; color: white; padding: 10px 15px; border: none; border-radius: 8px;">Batal</button>
                <button type="submit" style="background: red; color: white; padding: 10px 15px; border: none; border-radius: 8px;">Hapus Akun</button>
            </div>
        </form>
    </div>
</div>
@endsection