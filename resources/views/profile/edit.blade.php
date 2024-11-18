<!DOCTYPE html>
<html lang="id">

<head>
    @include('template.headerr')
    <title>Edit Profil | {{ auth()->user()->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fa;
        }
        .content-body {
            margin-top: -60px;
            padding-top: 80px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #EB8153;
            color: white;
            border-radius: 15px 15px 0 0;
        }
        .form-control {
            border-radius: 10px;
        }
        .btn {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
        }
        .btn-primary {
            background-color: #EB8153;
            border-color: #EB8153;
        }
        .btn-danger {
            background-color: #e74a3b;
            border-color: #e74a3b;
        }
        .square-image {
            border-radius: 15px;
            object-fit: cover;
        }
        .file-upload-wrapper {
            position: relative;
            width: 100%;
            height: 40px;
        }
        .file-upload-label {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;
            background-color: #fafbfc;
            color: black;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 10px;
        }
        #foto_profil {
            opacity: 0;
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        .file-upload-info {
            margin-top: 10px;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>

<body>

    @include('template.topbarr')
    @include('template.sidebarr')

    <div class="content-body">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title text-white">Edit Profil</h4>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-group text-center">
                                    <img id="preview-image" src="{{ auth()->user()->poto ? asset('storage/' . auth()->user()->poto) : asset('dash/images/usr.png') }}" alt="Foto Profil" class="square-image mb-3" width="150" height="150">
                                    <div class="file-upload-wrapper">
                                        <label class="file-upload-label" for="foto_profil">Pilih Foto</label>
                                        <input type="file" id="foto_profil" name="foto_profil" accept="image/*" onchange="displayFileName(); previewImage();">
                                    </div>
                                    <div id="file-upload-info" class="file-upload-info">Tidak ada file yang dipilih</div>
                                    <small class="text-muted mt-2 d-block">* Jika tidak ada perubahan, tidak perlu diisi</small>
                                </div>

                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="kelamin">Jenis Kelamin</label>
                                    <select class="form-control" id="kelamin" name="kelamin" disabled>
                                        <option value="laki-laki" {{ auth()->user()->kelamin == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="perempuan" {{ auth()->user()->kelamin == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    <input type="hidden" name="kelamin" value="{{ auth()->user()->kelamin }}">
                                </div>

                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <input type="text" class="form-control" id="alamat" name="alamat" value="{{ auth()->user()->alamat }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="password">Password Baru</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                                </div>

                                <div class="form-group text-right">
                                    <button type="button" class="btn btn-danger" onclick="window.location.href='{{ route('home') }}'"><i class="fas fa-times"></i> Batal</button>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('template.scripts')
    <script>
        function displayFileName() {
            var input = document.getElementById('foto_profil');
            var info = document.getElementById('file-upload-info');
            info.textContent = input.files.length > 0 ? input.files[0].name : 'Tidak ada file yang dipilih';
        }

        function previewImage() {
            var input = document.getElementById('foto_profil');
            var preview = document.getElementById('preview-image');
            var file = input.files[0];

            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>

</html>
