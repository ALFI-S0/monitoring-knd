@extends('layouts.guest')

@section('title', 'Login')

@section('content')

<div class="login-page">

    <div class="login-card">

        <div class="text-center mb-4">

            <img src="{{ asset('assets/img/logo.png') }}"
                alt="Logo"
                class="logo">

            <h2 class="mt-3 mb-1 fw-bold">
                Monitoring Kendaraan
            </h2>

            <p class="text-muted mb-0">
                Sistem Monitoring Kendaraan Operasional
            </p>

        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-3">

                <label class="form-label">
                    Email
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-envelope"></i>
                    </span>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="Masukkan Email"
                        required>

                </div>

            </div>

            <div class="mb-4">

                <label class="form-label">
                    Password
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        placeholder="Masukkan Password"
                        required>

                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        onclick="togglePassword()">

                        <i class="bi bi-eye"></i>

                    </button>

                </div>

            </div>

            <div class="mb-4">

                <div class="form-check">

                    <input
                        type="checkbox"
                        name="remember"
                        class="form-check-input">

                    <label class="form-check-label">

                        Ingat Saya

                    </label>

                </div>

            </div>

            <button
                type="submit"
                class="btn btn-primary w-100 btn-login">

                <i class="bi bi-box-arrow-in-right me-2"></i>

                Login

            </button>

        </form>

        <hr>

        <div class="text-center text-muted small">

            © {{ date('Y') }}
            Monitoring Kendaraan

        </div>

    </div>

</div>

<style>

body{
    background:linear-gradient(
        135deg,
        #f8fafc 0%,
        #eef4ff 100%
    );
}

.login-page{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}

.login-card{
    width:100%;
    max-width:450px;
    background:#fff;
    border-radius:20px;
    padding:40px;
    box-shadow:0 20px 50px rgba(0,0,0,.08);
}

.logo{
    width:120px;
}

.form-control{
    height:50px;
}

.input-group-text{
    background:#fff;
}

.btn-login{
    height:50px;
    border-radius:10px;
    font-weight:600;
}

.login-card:hover{
    transform:translateY(-3px);
    transition:.3s;
}

</style>

<script>

function togglePassword()
{
    let input = document.getElementById('password');

    if(input.type === 'password')
    {
        input.type = 'text';
    }
    else
    {
        input.type = 'password';
    }
}

</script>

@endsection