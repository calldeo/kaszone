<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | {{ auth()->user()->level }} | Tambah Data Pemasukan</title>
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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Tambah Data Pemasukan</a></li>
                    </ol>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card animate__animated animate__fadeIn">
                        <div class="card-header">
                            <h4 class="card-title mb-0 text-white">Tambah Data Pemasukan</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                <form class="form-valide-with-icon" action="/pemasukan/store" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label class="text-label">Nama Pemasukan *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="name" placeholder="Masukkan Nama.." value="{{ old('name') }}" required>
                                        </div>
                                        @error('name')
                                        <span class="mt-2 text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                        
                                    <div class="form-group">
                                        <label class="text-label">Deskripsi</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-book"></i></span>
                                            </div>
                                            <textarea class="form-control" name="description" placeholder="Masukkan Deskripsi..">{{ old('description') }}</textarea>
                                        </div>
                                    </div>
                        
                                    <div class="form-group">
                                        <label class="text-label">Tanggal *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                            </div>
                                            <input type="date" class="form-control" name="date" value="{{ old('date') }}" required>
                                        </div>
                                        @error('date')
                                        <span class="mt-2 text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                        
                                    <div class="form-group">
                                        <label class="text-label">Jumlah (Rp) *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="jumlah_display" id="jumlah" placeholder="Rp 0" value="{{ old('jumlah_display') }}" required oninput="formatInput()">
                                            <input type="hidden" name="jumlah" id="jumlah_value">
                                        </div>
                                        @error('jumlah')
                                        <span class="mt-2 text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="text-label">Kategori *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                        
                                            </div>
                                            <select class="form-control" id="category" name="category_id" required>
                                                <option value="">PILIH KATEGORI</option>
                                            </select>
                                        </div>
                                        @error('category_id')
                                        <span class="mt-2 text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                        
                                    <div class="form-group mt-4 text-right">
                                        <button type="button" class="btn btn-danger btn-cancel mr-2" onclick="window.location.href='/pemasukan'"><i class="fas fa-times mr-1"></i> Batal</button>
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
        $(document).ready(function() {
            getCategories();

            function getCategories() {
                $.ajax({
                    url: '/get-categories/1',
                    method: 'GET',
                    success: function(data) {
                        var $dropdown = $('#category');
                        $dropdown.empty();

                        $dropdown.append($('<option>', {
                            value: '',
                            text: '--PILIH KATEGORI--'
                        }));

                        $.each(data, function(index, item) {
                            $dropdown.append($('<option>', {
                                value: item.id,
                                text: item.name
                            }));
                        });

                        $dropdown.select2();
                    },
                    error: function(xhr) {
                        console.error('Error fetching categories:', xhr);
                        $('#category').append($('<option>', {
                            value: '',
                            text: 'Error loading categories'
                        }));
                    }
                });
            }
        });

        function formatInput() {
            const input = document.getElementById('jumlah');
            const hiddenInput = document.getElementById('jumlah_value');
            
            let value = input.value.replace(/[^0-9]/g, '');

            if (value) {
                const formattedValue = parseInt(value).toLocaleString('id-ID');
                input.value = 'Rp' + formattedValue;
                hiddenInput.value = value;
            } else {
                input.value = 'Rp 0';
                hiddenInput.value = '';
            }
        }

        $('form').on('submit', function() {
            const input = document.getElementById('jumlah_value');
            if (!input.value) {
                input.value = '0';
            }
        });
    </script>
</body>
</html>
