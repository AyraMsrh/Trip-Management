@extends('layouts.guest')

@section('content')
    <div class="auth-wrapper">
        <section class="auth-intro">
            <div class="auth-intro-logo">
                <img
                    src="{{ asset('images/insaaf-logo.png') }}"
                    alt="Logo In Saaff Travel and Tours"
                >
            </div>

            <div class="auth-intro-content">
                <h1>
                    Daftar akaun sistem
                </h1>

                <p>
                    Cipta akaun untuk menguruskan lawatan dan peserta dengan
                    lebih mudah.
                </p>

                <div class="auth-tag">
                    <i class="bi bi-person-check"></i>
                    Akses untuk pasukan operasi
                </div>
            </div>
        </section>

        <section class="auth-form-panel">
            <div class="auth-form-container">
                <h2>
                    Daftar akaun
                </h2>

                <p class="auth-subtitle">
                    Masukkan maklumat anda untuk mencipta akaun baharu.
                </p>

                @if($errors->any())
                    <div class="auth-error">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('register') }}"
                >
                    @csrf

                    <div class="auth-field">
                        <label
                            for="name"
                            class="auth-form-label"
                        >
                            Nama
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="auth-form-control"
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama penuh"
                            required
                            autofocus
                        >
                    </div>

                    <div class="auth-field">
                        <label
                            for="email"
                            class="auth-form-label"
                        >
                            Alamat emel
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="auth-form-control"
                            value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            required
                        >
                    </div>

                    <div class="auth-field">
                        <label
                            for="password"
                            class="auth-form-label"
                        >
                            Kata laluan
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="auth-form-control"
                            placeholder="Masukkan kata laluan"
                            required
                        >
                    </div>

                    <div class="auth-field">
                        <label
                            for="password_confirmation"
                            class="auth-form-label"
                        >
                            Sahkan kata laluan
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="auth-form-control"
                            placeholder="Masukkan semula kata laluan"
                            required
                        >
                    </div>

                    <button
                        type="submit"
                        class="auth-submit"
                    >
                        Daftar akaun
                        <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </form>

                <div class="auth-footer">
                    Sudah mempunyai akaun?

                    <a href="{{ route('login') }}">
                        Log masuk
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection