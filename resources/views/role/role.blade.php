<!DOCTYPE html>
<html lang="id">

<head>
    @include('template.headerr')
    <title>PityCash | {{ auth()->user()->level }} | Role</title>
    
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
    </style>
</head>

<body class="bg-light">
    @include('template.topbarr')
    @include('template.sidebarr')

    <div class="content-body" style="margin-top: -100px;">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Tabel</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Role</a></li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card animate__animated animate__fadeInUp">
                        <div class="card-header">
                            <h4 class="card-title text-white">Data Role</h4>
                            {{-- <div class="text-right">
                                <a href="/add" class="btn btn-outline-success" title="Tambah">
                                    <i class="fa fa-plus"></i> Tambah Role
                                </a>
                            </div> --}}
                        </div>

                        <div class="card-body">
                            @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown">
                                <svg viewbox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                    <polyline points="9 11 12 14 22 4"></polyline>
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                </svg>
                                <strong>Berhasil!</strong> {{ session('success') }}
                                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Tutup"><span><i class="mdi mdi-close"></i></span></button>
                            </div>
                            @endif
                            @if(session('update_success'))
                            <div class="alert alert-warning alert-dismissible fade show animate__animated animate__fadeInDown">
                                <svg viewbox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                    <polyline points="9 11 12 14 22 4"></polyline>
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                </svg>
                                <strong>Berhasil!</strong> {{ session('update_success') }}
                                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Tutup"><span><i class="mdi mdi-close"></i></span></button>
                            </div>
                            @endif
                            <div class="table-responsive">
                                <table id="roleTable" class="table table-responsive-md">
                                    <thead>
                                        <tr>
                                            <th style="width:50px;"><strong>No</strong></th>
                                            <th><strong>Nama</strong></th>
                                            {{-- <th><strong>Guard Nama</strong></th> --}}
                                            <th><strong>Opsi</strong></th>
                                        </tr>
                                    </thead>
                                </table>
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

    <input type="hidden" id="table-url" value="{{ route('roles') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('main.js') }}"></script>

    <script>
      
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                
                Swal.fire({
                    title: 'Apakah anda yakin hapus data ini?',
                    text: "Data akan dihapus secara permanen",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, hapus data!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#deleteForm_' + id).submit();
                    }
                });
            });
        });
    </script>
</body>

</html>