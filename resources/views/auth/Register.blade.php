@extends('layouts.master')

@section('content')
<div style="position: relative; width: 100vw; height: 100vh; background: #FFFFFF; display: flex; justify-content: center; align-items: center;">
    <div style="width: 1200px; height: 760px; background: #ECEBE4; border: 4px solid #000000; border-radius: 40px; display: flex; overflow: hidden;">
        
        <!-- Bagian Kanan (Background Gambar) -->
        <div style="width: 50%; background: url('{{ asset('images/login-image.png') }}') no-repeat center center; background-size: cover;"></div>

        <!-- Bagian Kiri (Form Registrasi) -->
        <div style="width: 50%; background: #FFFFFF; padding: 50px; display: flex; flex-direction: column; justify-content: center; position: relative;">
            
            <!-- Tombol Masuk -->
            <div style="position: absolute; top: 20px; right: 30px;">
                <a href="{{ route('login') }}" style="text-decoration: none; background: black; color: white; padding: 10px 20px; border-radius: 20px;">Masuk</a>
            </div>

            <!-- Judul -->
            <div style="text-align: center; margin-bottom: 20px;">
                <h2 style="margin: 0; font-size: 28px;">Selamat Datang!</h2>
                <p style="color: #5E5E5E;">Buat akun Anda dan mulai sekarang.</p>
            </div>

            <!-- Form Registrasi -->
            <form method="POST" action="{{ route('register') }}" style="display: flex; flex-direction: column; gap: 15px;">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label for="Name" style="font-weight: bold;">Nama Lengkap</label>
                    <input type="text" id="Name" name="Name" placeholder="Masukkan nama lengkap" 
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;"
                        value="{{ old('Name') }}" required autofocus>
                    @error('Name')
                        <span style="color: red; font-size: 14px;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="Email" style="font-weight: bold;">Email</label>
                    <input type="email" id="Email" name="Email" placeholder="Masukkan email" 
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;"
                        value="{{ old('Email') }}" required>
                    @error('Email')
                        <span style="color: red; font-size: 14px;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <label for="Telp_Num" style="font-weight: bold;">Nomor Telepon</label>
                    <input type="text" id="Telp_Num" name="Telp_Num" placeholder="Masukkan nomor telepon" 
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;"
                        value="{{ old('Telp_Num') }}" required>
                    @error('Telp_Num')
                        <span style="color: red; font-size: 14px;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" style="font-weight: bold;">Kata Sandi</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan kata sandi (minimal 8 karakter)" 
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;"
                        required>
                    @error('password')
                        <span style="color: red; font-size: 14px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" style="font-weight: bold;">Konfirmasi Kata Sandi</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                        placeholder="Masukkan ulang kata sandi" 
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required>
                </div>
                <!-- Role (Hidden) -->
                <input type="hidden" name="ID_Role" value="2">

                <!-- Syarat & Ketentuan -->
                <div>
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">Saya menyetujui <a href="#">Syarat Penggunaan</a> dan <a href="#">Kebijakan Privasi</a></label>
                    @error('terms')
                        <span style="color: red; font-size: 14px;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tombol Submit -->
                <button type="submit" style="width: 100%; padding: 12px; background: black; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">
                    Buat Akun
                </button>

                <!-- Divider -->
                <div style="text-align: center; margin: 10px 0; color: #5E5E5E;">atau daftar dengan</div>

                <!-- Tombol Google -->
                @if(config('services.google.client_id'))
                    <a href="{{ route('register.google') }}" style="text-decoration: none;">
                        <button type="button" style="width: 100%; padding: 10px; background: white; border: 1px solid gray; display: flex; justify-content: center; align-items: center; gap: 10px; border-radius: 5px; cursor: pointer;">
                            <img src="{{ asset('images/logo-google.png') }}" alt="Google" style="height: 20px;">
                            Daftar dengan Google
                        </button>
                    </a>
                @endif
            </form>

            <!-- Error Global -->
            @if($errors->any())
                <div style="margin-top: 20px; color: red;">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
