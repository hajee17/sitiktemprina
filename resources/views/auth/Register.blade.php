@extends('layouts.master')

@section('content')
<div style="position: relative; width: 100vw; min-height: 100vh; background: #FFFFFF; display: flex; justify-content: center; align-items: center; padding: 2rem 0;">
    <div style="width: 1200px; max-height: 760px; background: #ECEBE4; border: 4px solid #000000; border-radius: 40px; display: flex; overflow: hidden;">
        
        <div style="width: 50%; background: #FFFFFF; padding: 40px 50px; display: flex; flex-direction: column; justify-content: center; position: relative; overflow-y: auto;">
            
            <div style="position: absolute; top: 20px; right: 30px;">
                <a href="{{ route('login') }}" style="text-decoration: none; background: black; color: white; padding: 10px 20px; border-radius: 20px; font-size: 14px;">Masuk</a>
            </div>

            <div style="text-align: center; margin-bottom: 20px;">
                <h2 style="margin: 0; font-size: 28px;">Selamat Datang!</h2>
                <p style="color: #5E5E5E;">Buat akun Anda dan mulai sekarang.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" style="display: flex; flex-direction: column; gap: 15px;">
                @csrf

                <div>
                    {{-- PERUBAHAN: for, id, name, old(), dan @error disesuaikan menjadi 'name' (lowercase) --}}
                    <label for="name" style="font-weight: bold; display: block; margin-bottom: 5px;">Nama Lengkap</label>
                    <input type="text" id="name" name="name" placeholder="Masukkan nama lengkap" 
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;"
                        value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <span style="color: red; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    {{-- PERUBAHAN: for, id, name, old(), dan @error disesuaikan menjadi 'email' (lowercase) --}}
                    <label for="email" style="font-weight: bold; display: block; margin-bottom: 5px;">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email" 
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;"
                        value="{{ old('email') }}" required>
                    @error('email')
                        <span style="color: red; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    {{-- PERUBAHAN: for, id, name, old(), dan @error disesuaikan menjadi 'phone' --}}
                    <label for="phone" style="font-weight: bold; display: block; margin-bottom: 5px;">Nomor Telepon</label>
                    <input type="text" id="phone" name="phone" placeholder="Masukkan nomor telepon" 
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;"
                        value="{{ old('phone') }}" required>
                    @error('phone')
                        <span style="color: red; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password" style="font-weight: bold; display: block; margin-bottom: 5px;">Kata Sandi</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan kata sandi (minimal 8 karakter)" 
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;"
                        required autocomplete="new-password">
                    @error('password')
                        <span style="color: red; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" style="font-weight: bold; display: block; margin-bottom: 5px;">Konfirmasi Kata Sandi</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                        placeholder="Masukkan ulang kata sandi" 
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required>
                </div>

                {{-- PERUBAHAN: Input hidden untuk role_id dihapus karena role diatur di controller --}}
                
                <div style="margin-top: 5px;">
                    <input type="checkbox" id="terms" name="terms" required style="margin-right: 5px;">
                    <label for="terms">Saya menyetujui <a href="#" style="color: black;">Syarat Penggunaan</a> dan <a href="#" style="color: black;">Kebijakan Privasi</a></label>
                    @error('terms')
                        <span style="color: red; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" style="width: 100%; padding: 12px; background: black; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; margin-top: 10px;">
                    Buat Akun
                </button>
                
                @error('msg')
                    <div style="margin-top: 10px; padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; text-align: center;">
                        {{ $message }}
                    </div>
                @enderror
            </form>
        </div>

        <div style="width: 50%; background: url('{{ asset('images/login-image.png') }}') no-repeat center center; background-size: cover;"></div>

    </div>
</div>
@endsection