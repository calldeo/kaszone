<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | {{ auth()->user()->level }} | Tambah Data Kategori</title>
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
            background: linear-gradient(45deg, #EB8153, #EB8153);
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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Tambah Kategori</a></li>
                    </ol>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card animate__animated animate__fadeIn">
                        <div class="card-header">
                            <h4 class="card-title mb-0 text-white">Tambah Data Kategori</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                <form class="form-valide-with-icon" action="/kategori/store" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label class="text-label">Nama *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="val-username1" name="name" placeholder="Massukan Nama.." value="{{old('name')}}" required>
                                        </div>
                                        @error('name')
                                        <span class="mt-2 text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-label">Deskripsi *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-align-left"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="val-username1" name="description" placeholder="Massukkan Deskripsi.." value="{{old('description')}}" required>
                                        </div>
                                        @error('description')
                                        <span class="mt-2 text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="text-label">Jenis Kategori *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-list"></i></span>
                                            </div>
                                            <select class="form-control" name="jenis_kategori" required>
                                                <option value="" disabled selected>--PILIH TIPE KATEGORI--</option>
                                                <option value="1">Pemasukan</option>
                                                <option value="2">Pengeluaran</option>
                                            </select>
                                        </div>
                                        @error('jenis_kategori')
                                        <span class="mt-2 text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group mt-4 text-right">
                                        <button type="button" class="btn btn-danger btn-cancel mr-2" onclick="redirectToKategori()"><i class="fas fa-times mr-1"></i> Batal</button>
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

    <div class="footer">
        <div class="copyright">
            <p>Copyright © Designed &amp; Developed by <a href="/home" target="_blank">SYNC</a> 2024</p>
        </div>
    </div>

    @include('template.scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        function redirectToKategori() {
            window.location.href = '/kategori';
        }
    </script>
</body>
</html>