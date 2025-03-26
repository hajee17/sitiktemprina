@extends('layouts.master')

@section('content')
<div style="position: relative; width: 100%; min-height: 100vh; background: #F3F2F2; display: flex; justify-content: center; align-items: center;">

    <div style="width: 600px; background: white; padding: 40px; border-radius: 12px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); text-align: center;">
        
        <!-- Header Profil -->
        <div style="position: relative; width: 100%; text-align: center;">
            <div style="width: 100%; height: 120px; background: url('{{ asset('images/banner.png') }}') no-repeat center center; background-size: cover; border-radius: 12px;"></div>
            <img src="{{ asset('images/profile.png') }}" alt="Foto Profil" 
                style="width: 100px; height: 100px; border-radius: 50%; border: 3px solid white; position: relative; top: -50px;">
            <button style="position: relative; top: -90px; left: 40px; background: white; border-radius: 50%; padding: 5px; border: none;">
                <img src="{{ asset('images/camera-icon.png') }}" width="24">
            </button>
        </div>

        <!-- Form Data User -->
        <div style="display: flex; flex-direction: column; gap: 15px; margin-top: -40px;">

            <div style="text-align: left;">
                <label style="font-weight: bold;">Nama Lengkap</label>
                <input type="text" value="Hafidz Irham Ar Ridlo" disabled 
                    style="width: 100%; padding: 10px; border: 1px solid #E4E4E4; border-radius: 8px; background: #F9F9F9;">
            </div>

            <div style="text-align: left;">
                <label style="font-weight: bold;">Email</label>
                <input type="email" value="iniemail@gmail.com" disabled 
                    style="width: 100%; padding: 10px; border: 1px solid #E4E4E4; border-radius: 8px; background: #F9F9F9;">
            </div>

            <div style="text-align: left;">
                <label style="font-weight: bold;">Nomor Telepon</label>
                <input type="text" value="081234567890" disabled 
                    style="width: 100%; padding: 10px; border: 1px solid #E4E4E4; border-radius: 8px; background: #F9F9F9;">
            </div>

            <!-- Ubah Kata Sandi -->
            <button style="width: 100%; display: flex; justify-content: space-between; align-items: center; background: #FFF; border: 1px solid #E4E4E4; padding: 10px; border-radius: 8px; cursor: pointer;">
                <span>🔑 Ubah Kata Sandi</span>
                <span>➡</span>
            </button>

            <!-- Hapus Akun -->
            <button style="width: 100%; padding: 12px; border: 2px solid red; color: red; background: transparent; border-radius: 8px; font-weight: bold; cursor: pointer;"
                onmouseover="this.style.background='red'; this.style.color='white';"
                onmouseout="this.style.background='transparent'; this.style.color='red';">
                🚨 Hapus Akun
            </button>

        </div>

    </div>

</div>
@endsection
