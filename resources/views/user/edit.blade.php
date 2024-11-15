<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | {{ auth()->user()->level }} | Edit Data User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fa;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card-header {
            background: linear-gradient(45deg, #EB8153, #FF9A85);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
        }
        .form-control {
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            padding: 12px;
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
            background-color: #FF9A85;
            border-color: #FF9A85;
        }
        .btn-danger {
            background-color: #FF6B6B;
            border-color: #FF6B6B;
        }
        .btn-danger:hover {
            background-color: #FF8E8E;
            border-color: #FF8E8E;
        }
    </style>
</head>

<body>
    @include('template.topbarr')
    @include('template.sidebarr')

    <div class="content-body" style="margin-top: -100px;">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0"></div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Form</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit User</a></li>
                    </ol>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card animate__animated animate__fadeIn">
                        <div class="card-header">
                            <h4 class="card-title mb-0 text-white">Edit Data User</h4>
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
                            <div class="basic-form">
                                <form action="/guruu/{{ $guruu->id }}" method="POST" enctype="multipart/form-data">
                                    @method('put')
                                    @csrf
                                    <div class="form-group">
                                        <label class="text-label">Nama *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="name" value="{{ $guruu->name }}" placeholder="Masukkan nama..." required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-label">Email *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                            </div>
                                            <input type="email" class="form-control" name="email" value="{{ $guruu->email }}" placeholder="Masukkan email..." required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-label">Password *</label>
                                        <div class="input-group transparent-append">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                            </div>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Pilih password yang aman...">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fa fa-eye-slash" id="togglePassword"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <small class="text-danger mt-2 d-block">* Jika tidak ada perubahan, tidak perlu diisi</small>
                                    </div>
                                    <div class="form-group">
                                        <label>Jenis Kelamin *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                            </div>
                                            <select class="form-control default-select" name="kelamin" required>
                                                <option value="">-- Pilih Jenis Kelamin --</option>
                                                <option value="laki-laki" {{ old('kelamin', $guruu->kelamin) == 'laki-laki' ? 'selected' : '' }}>Laki-Laki</option>
                                                <option value="perempuan" {{ old('kelamin', $guruu->kelamin) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-label">Alamat *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="alamat" value="{{ $guruu->alamat }}" placeholder="Masukkan alamat..." required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-label">Pilih Peran *</label>
                                        <div class="input-group">
                                            <div class="role-checkboxes">
                                                @foreach($roles as $role)
                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input 
                                                        type="checkbox" 
                                                        class="custom-control-input" 
                                                        id="checkbox-{{ $role->name }}"
                                                        name="roles[]"  
                                                        value="{{ $role->name }}"
                                                        {{ in_array($role->name, old('roles', $guruu->roles->pluck('name')->toArray())) ? 'checked' : '' }}
                                                    >
                                                    <label class="custom-control-label" for="checkbox-{{ $role->name }}">{{ $role->name }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @error('roles')
                                        <span class="text-danger text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group mt-4 text-right">
                                        <a href="/user" class="btn btn-danger btn-cancel mr-2"><i class="fas fa-times mr-1"></i> Batal</a>
                                        <button type="submit" class="btn btn-primary btn-submit"><i class="fas fa-save mr-1"></i> Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('template.scripts')
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        
        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
