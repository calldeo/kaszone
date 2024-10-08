<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>Edit Profile | {{ auth()->user()->name }}</title>
</head>

<body>

    @include('template.topbarr')
    @include('template.sidebarr')

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Hello, {{ auth()->user()->name }}!</h4>
                        <p class="mb-0">Edit Your Profile</p>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Form</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Profile</a></li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Profile</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
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

                                    <div class="form-group">
                                        <label for="foto_profil">Foto Profil</label>
                                        
                                        <!-- Menampilkan Foto Profil -->
                                        <div class="mb-3">
                                            <!-- Menampilkan Logo atau Default Profile -->
                                            <img id="preview-image" src="{{ auth()->user()->logo ? asset('storage/' . auth()->user()->logo) : asset('dash/images/usr.png') }}" alt="Logo Profil" class="square-image" width="150" height="150">
                                        </div>

                                        <div class="file-upload-wrapper ml-0">
                                            <label class="file-upload-label mr-0" for="foto_profil">Pilih file</label>
                                            <input type="file" id="foto_profil" name="foto_profil" onchange="displayFileName(); previewImage();">
                                            <div id="file-upload-info" class="file-upload-info">Tidak ada file yang dipilih</div>
                                        </div>
                                        <!-- Keterangan -->
                                        <label class="text-label text-danger mt-3">* Jika tidak ada perubahan, tidak perlu diisi</label>
                                    </div>

                                    <div class="form-group">
                                        <label class="text-label">Name *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="name" value="{{ auth()->user()->name }}" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="text-label">Email *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                            </div>
                                            <input type="email" class="form-control" name="email" value="{{ auth()->user()->email }}" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Jenis Kelamin *</label>
                                        <select class="form-control default-select" name="kelamin" disabled>
                                            <option value="laki-laki" {{ auth()->user()->kelamin == 'laki-laki' ? 'selected' : '' }}>Laki - Laki</option>
                                            <option value="perempuan" {{ auth()->user()->kelamin == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        <input type="hidden" name="kelamin" value="{{ auth()->user()->kelamin }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="text-label">Alamat *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="alamat" value="{{ auth()->user()->alamat }}" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="text-label">Password *</label>
                                        <div class="input-group transparent-append">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                            </div>
                                            <input type="password" class="form-control" name="password" placeholder="">
                                            <div class="input-group-append show-pass">
                                                <span class="input-group-text">
                                                    <i class="fa fa-eye-slash"></i>
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <label class="text-label text-danger mt-3">* Jika tidak ada perubahan, tidak perlu diisi</label>
                                    </div>

                                    <div class="form-group">
                                        <label>Level *</label>
                                        <select class="form-control default-select" name="level" disabled>
                                            <option value="admin" {{ auth()->user()->level == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="bendahara" {{ auth()->user()->level == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                                        </select>
                                        <input type="hidden" name="level" value="{{ auth()->user()->level }}">
                                    </div>

                                    <div class="form-group">
                                        <button type="button" class="btn btn-danger btn-cancel" onclick="window.location.href='{{ route('home') }}'">Cancel</button>
                                        <button type="submit" class="btn mr-2 btn-primary btn-submit">Submit</button>
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
        function displayFileName() {
            var input = document.getElementById('foto_profil');
            var info = document.getElementById('file-upload-info');
            info.textContent = input.files.length > 0 ? input.files[0].name : 'Tidak ada file yang dipilih';
        }

        function previewImage() {
            var input = document.getElementById('foto_profil');
            var preview = document.getElementById('preview-image');
            var file = input.files[0];

            // Pastikan file terpilih
            if (file) {
                var reader = new FileReader();

                // Ketika file sudah dibaca
                reader.onload = function(e) {
                    // Set source dari img preview menjadi hasil pembacaan file
                    preview.src = e.target.result;
                }

                // Baca file sebagai Data URL
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>

</html>
