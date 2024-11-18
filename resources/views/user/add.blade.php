<!DOCTYPE html>
<html lang="en">
<head>
    @include('template.headerr')
    <title>PityCash | {{auth()->user()->level}} | Tambah Data Pengguna</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        .card-header {
            background: linear-gradient(45deg, #EB8153, #EB8153);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
        }
        .form-control {
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            padding: 12px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(235, 129, 83, 0.2);
            border-color: #EB8153;
        }
        .btn {
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background-color: #EB8153;
            border-color: #EB8153;
        }
        .btn-primary:hover {
            background-color: #3498db;
            border-color: #3498db;
            transform: translateY(-2px);
        }
        .btn-danger {
            background-color: #FF6B6B;
            border-color: #FF6B6B;
        }
        .btn-danger:hover {
            background-color: #ff4757;
            border-color: #ff4757;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    @include('template.topbarr')
    @include('template.sidebarr')
    
    <div class="content-body" style="margin-top: -100px;">
        <div class="container-fluid py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card animate__animated animate__fadeIn">
                        <div class="card-header">
                            <h4 class="card-title mb-0 text-white">Tambah Data Pengguna</h4>
                        </div>
                        <div class="card-body">
                            @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <strong>Error!</strong> {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif
                            
                            <form class="form-valide-with-icon" action="/user/store" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label class="text-label">Nama *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                        </div>
                                        <input type="text" class="form-control" id="val-username1" name="name" placeholder="Masukkan nama..." value="{{old('name')}}" required>
                                    </div>
                                    @error('name')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label class="text-label">Email *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
                                        </div>
                                        <input type="email" class="form-control" id="val-email" name="email" placeholder="Masukkan email..." value="{{old('email')}}" required>
                                    </div>
                                    @error('email')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label class="text-label">Password *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                                        </div>
                                        <input type="password" class="form-control" id="val-password" name="password" placeholder="Pilih password yang aman..." required>
                                        <div class="input-group-append">
                                            <span class="input-group-text password-toggle">
                                                <i class="fa fa-eye-slash"></i>
                                            </span>
                                        </div>
                                    </div>
                                    @error('password')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label class="text-label">Jenis Kelamin *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-venus-mars"></i> </span>
                                        </div>
                                        <select class="form-control" id="val-gender" name="kelamin" required>
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="laki-laki">Laki-Laki</option>
                                            <option value="perempuan">Perempuan</option>
                                        </select>
                                    </div>
                                    @error('kelamin')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label class="text-label">Alamat *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-map-marker-alt"></i> </span>
                                        </div>
                                        <input type="text" class="form-control" id="val-address" name="alamat" placeholder="Masukkan alamat..." value="{{old('alamat')}}" required>
                                    </div>
                                    @error('alamat')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label class="text-label">Peran *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        </div>
                                        <select class="form-control select2" id="val-role" name="level[]" multiple required>
                                            <option value="" disabled>Pilih Peran</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('level')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                
                                    <div class="form-group mt-4 text-right">
                                        <button type="button" class="btn btn-danger btn-cancel mr-2" onclick="redirectToAdmin()"><i class="fas fa-times mr-1"></i> Batal</button>
                                        <button type="submit" class="btn btn-primary btn-submit"><i class="fas fa-save mr-1"></i> Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="copyright">
            <p>Copyright © Designed &amp; Developed by <a href="/home" target="_blank">SYNC</a> 2024</p>
        </div>
    </div>

    @include('template.scripts')
    
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            
            $('.password-toggle').click(function() {
                var passwordField = $('#val-password');
                var passwordFieldType = passwordField.attr('type');
                if (passwordFieldType == 'password') {
                    passwordField.attr('type', 'text');
                    $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                } else {
                    passwordField.attr('type', 'password');
                    $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                }
            });
        });
        
        function redirectToAdmin() {
            window.location.href = "/user";
        }
    </script>
</body>
</html>