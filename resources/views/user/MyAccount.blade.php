@extends('layouts.master')

@section('content')
<div style="position: relative; width: 100%; min-height: 100vh; background: #F3F2F2; display: flex; justify-content: center; align-items: center;">

    <div style="width: 600px; background: white; padding: 40px; border-radius: 12px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); text-align: center;">
        
        <!-- Header Profil -->
        <div style="position: relative; width: 100%; text-align: center;">
            <div style="width: 100%; height: 120px; background: url('{{ asset('images/banner.png') }}') no-repeat center center; background-size: cover; border-radius: 12px;"></div>
            {{-- Avatar dinamis --}}
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=100&background=EBF4FF&color=007BFF" alt="Foto Profil" 
                style="width: 100px; height: 100px; border-radius: 50%; border: 3px solid white; position: relative; top: -50px;">
        </div>

        <!-- Form Data User -->
        <div style="display: flex; flex-direction: column; gap: 15px; margin-top: -40px;">

            <div style="text-align: left;">
                <label style="font-weight: bold;">Nama Lengkap</label>
                {{-- Data Dinamis --}}
                <input type="text" value="{{ Auth::user()->name }}" disabled 
                    style="width: 100%; padding: 10px; border: 1px solid #E4E4E4; border-radius: 8px; background: #F9F9F9;">
            </div>

            <div style="text-align: left;">
                <label style="font-weight: bold;">Email</label>
                {{-- Data Dinamis --}}
                <input type="email" value="{{ Auth::user()->email }}" disabled 
                    style="width: 100%; padding: 10px; border: 1px solid #E4E4E4; border-radius: 8px; background: #F9F9F9;">
            </div>

            <div style="text-align: left;">
                <label style="font-weight: bold;">Nomor Telepon</label>
                {{-- Data Dinamis --}}
                <input type="text" value="{{ Auth::user()->phone ?? 'Belum diisi' }}" disabled 
                    style="width: 100%; padding: 10px; border: 1px solid #E4E4E4; border-radius: 8px; background: #F9F9F9;">
            </div>

            {{-- @todo: Buat halaman/modal untuk fungsionalitas ini --}}
            <button style="width: 100%; display: flex; justify-content: space-between; align-items: center; background: #FFF; border: 1px solid #E4E4E4; padding: 10px; border-radius: 8px; cursor: pointer;">
                <span>🔑 Ubah Kata Sandi</span>
                <span>➡</span>
            </button>

            {{-- @todo: Buat fungsionalitas hapus akun di controller --}}
            <button style="width: 100%; padding: 12px; border: 2px solid red; color: red; background: transparent; border-radius: 8px; font-weight: bold; cursor: pointer;"
                onmouseover="this.style.background='red'; this.style.color='white';"
                onmouseout="this.style.background='transparent'; this.style.color='red';">
                🚨 Hapus Akun
            </button>

        </div>
    </div>
</div>
@endsection
