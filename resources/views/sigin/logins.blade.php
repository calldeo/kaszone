<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <!-- BEGIN: Head -->
    <meta charset="utf-8">
    <link href="{{ asset('dashboards/dist/images/logo.svg') }}" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Icewall admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Icewall Admin Template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="LEFT4CODE">
    <title>Login - PityCash</title>
    <!-- BEGIN: CSS Assets-->
    <link rel="stylesheet" href="{{ asset('dashboards/dist/css/app.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <style>
        .password-container {
            position: relative;
            margin-top: 20px;
        }
        .password-container input {
            width: 100%;
            padding-right: 40px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            height: 45px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .password-container input:focus {
            border-color: #EB8153;
            box-shadow: 0 0 0 2px rgba(235, 129, 83, 0.2);
            outline: none;
        }
        .password-container .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #718096;
        }
        .login-input {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            height: 45px;
            padding: 0 15px;
            width: 100%;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .login-input:focus {
            border-color: #EB8153;
            box-shadow: 0 0 0 2px rgba(235, 129, 83, 0.2);
            outline: none;
        }
        .login-button {
            background-color: #EB8153;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 0;
            width: 100%;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        .login-button:hover {
            background-color: #e06b3d;
            transform: translateY(-1px);
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin: auto;
        }
        .form-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 25px;
            color: #2d3748;
        }
        .swal2-popup {
            font-size: 14px !important;
            border-radius: 15px !important;
        }
        .swal2-title {
            color: #2d3748 !important;
            font-size: 20px !important;
        }
        .swal2-confirm {
            background-color: #EB8153 !important;
            color: white !important;
            border-radius: 8px !important;
            padding: 12px 25px !important;
            font-weight: 600 !important;
        }
        .swal2-confirm:hover {
            background-color: #e06b3d !important;
        }
    </style>
    <!-- END: CSS Assets -->
</head>
<body class="login">
    <div class="container sm:px-10">
        <div class="block xl:grid grid-cols-2 gap-4">
            <!-- BEGIN: Login Info -->
            <div class="hidden xl:flex flex-col min-h-screen">
                <div class="my-auto">
                    <img alt="Brand Title" class="-intro-x w-1/2 -mt-16" src="{{ asset('dashboards/dist/images/piticash.png') }}" width="150" height="50">
                    <div class="-intro-x text-white font-medium text-1xl leading-tight mt-10">
                        Nothing is impossible. Anything can happen as long as we believe.
                        <br>
                        Sign in to your account.
                    </div>
                </div>
            </div>
            <!-- END: Login Info -->
            
            <!-- BEGIN: Login Form -->
            <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                <div class="form-container">
                    <h2 class="form-title">Sign In</h2>
                    <form class="user" method="post" action="/postlogin">
                        {{ csrf_field() }}
                        <div class="space-y-5">
                            <div>
                                <input type="email" 
                                       name="email" 
                                       value="{{ Session::get('email') }}" 
                                       class="login-input" 
                                       placeholder="Email"
                                       required>
                            </div>
                            
                            <div class="password-container">
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="login-input" 
                                       placeholder="Password"
                                       required>
                                <i class="fas fa-eye-slash toggle-password" id="togglePasswordIcon"></i>
                            </div>

                            <button type="submit" class="login-button">
                                Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        
        </div>
    </div>


    <script src="{{ asset('dashboards/dist/js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const togglePasswordIcon = document.getElementById('togglePasswordIcon');
        const passwordInput = document.getElementById('password');

        togglePasswordIcon.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        passwordInput.addEventListener('input', function () {
            if (passwordInput.value.length > 0) {
                togglePasswordIcon.style.display = 'block';
            } else {
                togglePasswordIcon.style.display = 'none';
            }
        });

        window.addEventListener('DOMContentLoaded', function () {
            togglePasswordIcon.style.display = passwordInput.value.length > 0 ? 'block' : 'none';
        });

        @if (session('login_error'))
            Swal.fire({
                title: 'Login Gagal!',
                text: '{{ session('login_error') }}',
                icon: 'error',
                confirmButtonText: 'OK',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                },
                customClass: {
                    popup: 'swal2-popup',
                    title: 'swal2-title',
                    confirmButton: 'swal2-confirm'
                },
                buttonsStyling: false
            });
        @endif
    </script>

</body>
</html>