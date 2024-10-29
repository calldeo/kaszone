<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | {{auth()->user()->level}} | Kategori</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
            color: #333;
        }
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.5s ease;
            overflow: hidden;
            background: linear-gradient(145deg, #ffffff, #e6e6e6);
        }
        .card:hover {
            transform: translateY(-10px) rotate(2deg);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background: linear-gradient(45deg, #EB8153 , #EB8153);
            color: white;
            border-bottom: none;
            padding: 25px;
            position: relative;
            overflow: hidden;
            animation: gradientBG 10s ease infinite;
        }
        @keyframes gradientBG {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }
        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
            transform: rotate(45deg);
            animation: shimmer 3s linear infinite;
        }
        @keyframes shimmer {
            0% {transform: translateX(-50%) rotate(45deg);}
            100% {transform: translateX(50%) rotate(45deg);}
        }
        .btn-outline-success {
            color: #f3f5f3;
            background-color: transparent;
            border: 2px solid #fdfdfd;
            border-radius: 30px;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }
        .btn-outline-success:hover {
            color: #fff;
            background-color: #d74709;
            border-color: #f9f9fa;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(9, 16, 87, 0.4);
        }
        .table {
            border-collapse: separate;
            border-spacing: 0 15px;
            background-color: transparent;
        }
        .table thead th {
            background-color: #fcfcfc;
            color: white;
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 15px;
            border-radius: 10px;
        }
        .table tbody tr {
            background-color: #ffffff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .table tbody tr:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .table tbody td {
            border: none;
            padding: 20px;
            vertical-align: middle;
        }
        .table tbody td:first-child {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }
        .table tbody td:last-child {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }
        .alert {
            border-radius: 15px;
            border: none;
            padding: 15px 20px;
            animation: fadeInDown 0.5s ease-out;
        }
        @keyframes fadeInDown {
            from {opacity: 0; transform: translateY(-20px);}
            to {opacity: 1; transform: translateY(0);}
        }
        .modal-content {
            border-radius: 20px;
            border: none;
            overflow: hidden;
            animation: zoomIn 0.3s ease-out;
        }
        @keyframes zoomIn {
            from {opacity: 0; transform: scale(0.9);}
            to {opacity: 1; transform: scale(1);}
        }
        .modal-header {
            background: linear-gradient(45deg, #EB8153, #EB8153);
            color: white;
            border-bottom: none;
            padding: 20px 30px;
        }
        .breadcrumb {
            background-color: transparent;
            padding: 0;
        }
        .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            font-size: 1.4em;
            vertical-align: middle;
            color: #3a7bd5;
        }
        .animate__animated {
            animation-duration: 0.8s;
        }
        .action-buttons .btn {
            padding: 5px 10px;
            margin: 2px;
            border-radius: 20px;
            transition: all 0.3s ease;
        }
        .action-buttons .btn:hover {
            transform: translateY(-2px);
        }
        .btn-export {
            color: #f3f5f3;
            background-color: transparent;
            border: 2px solid #fdfdfd;
            border-radius: 30px;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }
        .btn-export:hover, .btn-export:active, .btn-export:focus {
            color: #fff;
            background-color: #d74709;
            border-color: #f7f8f7;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }
        .btn-import {
            color: #f3f5f3;
            background-color: transparent;
            border: 2px solid #fdfdfd;
            border-radius: 30px;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }
        .btn-import:hover, .btn-import:active, .btn-import:focus {
            color: #fff;
            background-color: #d74709;
            border-color: #ffffff;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        }
    </style>
</head>

<body class="bg-light">

    @include('template.topbarr')
    @include('template.sidebarr')

    <div class="content-body animate__animated animate__fadeIn" style="margin-top: -100px;">
        <div class="container-fluid py-5">
            <div class="row mb-4">
                <div class="col-12 text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 justify-content-end">
                            <li class="breadcrumb-item"><a href="javascript:void(0)" class="text-primary">Tabel</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Kategori</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card animate__animated animate__fadeInUp">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0 text-white">Data Kategori</h4>
                            <div>
                                <a href="/add-kategori" class="btn btn-outline-success animate__animated animate__bounceIn" title="Tambah">
                                    <i class="fas fa-plus mr-2"></i> Tambah Kategori
                                </a>
                                <a href="/export-kategori" target="_blank" class="btn btn-export animate__animated animate__bounceIn" title="Cetak">
                                    <i class="fas fa-print mr-2"></i> Cetak
                                </a>
                                <button type="button" class="btn btn-import animate__animated animate__bounceIn" title="Import" data-toggle="modal" data-target="#importModal">
                                    <i class="fas fa-upload mr-2"></i> Import
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show animate__animated animate__bounceIn">
                                <i class="fas fa-check-circle mr-2"></i>
                                <strong>Berhasil!</strong> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif
                            @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show animate__animated animate__bounceIn">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <strong>Gagal!</strong> {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif
                          
                            <div class="table-responsive">
                                <table id="kategoriTable" class="table table-hover animate__animated animate__fadeIn">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Kategori</th>
                                            <th>Jenis Kategori</th>
                                            <th>Deskripsi</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data akan diisi oleh DataTables -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer mt-auto py-3 bg-white shadow-sm animate__animated animate__fadeInUp">
        <div class="container text-center">
            <span class="text-muted">Hak Cipta © Dirancang &amp; Dikembangkan oleh <a href="/home" target="_blank" class="text-primary">SYNC</a> 2024</span>
        </div>
    </footer>

    @include('template.scripts')
    <input type="hidden" id="table-url" value="{{ route('kategoris') }}">
    <script src="{{ asset('main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <div class="modal fade" id="adminDetailModal" tabindex="-1" role="dialog" aria-labelledby="adminDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="adminDetailModalLabel" style="color: #fff">Detail Kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4 font-weight-bold">
                            No:<br />
                            Nama:<br />
                            Deskripsi:<br />
                        </div>
                        <div class="col-sm-8">
                            <div id="id"></div>
                            <div id="name"></div>
                            <div id="description"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-merah" data-dismiss="modal" style="background-color: red; color: white;">Tutup</button>
                </div>
            </div>
        </div>
    </div>
  
  <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel" style="color: #fff">Import Data Katagori</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <form action="{{ route('import-kategori') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group" style="text-align: left;">
                                <label for="file">Pilih File Excel</label>
                                <input type="file" class="dropify" id="file" name="file" required accept=".xls,.xlsx">
                            </div>
                            <div style="text-align: left; margin-top: 10px;">
                                <a href="{{ route('download-template-kategori') }}">
                                    Download Template Excel
                                </a>
                            </div>
                            <div style="text-align: left; margin-top: 10px;">
                                <button type="submit" class="btn btn-primary">Import</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <script>
        $(document).ready(function() {
            // Handle modal show event
            $('#adminDetailModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var url = button.data('url');
                var modal = $(this);
                
                // Reset modal fields
                modal.find('#id').text('');
                modal.find('#name').text('');
                modal.find('#description').text('');
            });

            // Handle delete confirmation
            $(document).on('click', '.btn-danger', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                
                Swal.fire({
                    title: 'Apakah Anda yakin ingin menghapus?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EB8153',
                    cancelButtonColor: '#6e7d88',
                    confirmButtonText: '<i class="fas fa-trash-alt"></i> Ya, Hapus!',
                    cancelButtonText: '<i class="fas fa-times"></i> Batal',
                    background: '#f8f9fa',
                    borderRadius: '15px',
                    customClass: {
                        title: 'text-danger font-weight-bold',
                        content: 'text-muted',
                        confirmButton: 'btn btn-danger btn-lg px-4 py-2',
                        cancelButton: 'btn btn-secondary btn-lg px-4 py-2 ml-2'
                    },
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menghapus...',
                            html: 'Mohon tunggu sebentar.',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            },
                        });
                        form.submit();
                    }
                });
            });
        });
    </script>
    <script>
    $(document).ready(function(){
        
        $('.dropify').dropify();

        $('.dropify-wrapper .dropify-message p').css('font-size', '20px'); 
    });
</script>
</body>

</html>
