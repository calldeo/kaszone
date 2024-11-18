<!DOCTYPE html>
<html lang="id">

<head>
    @include('template.headerr')
    <title>PityCash | {{ auth()->user()->level }} | Pengeluaran</title>
    
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
            background: linear-gradient(45deg, #EB8153, #EB8153);
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
            color: #28a745;
            background-color: transparent;
            border: 2px solid #ffffff;
            border-radius: 30px;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }
        .btn-outline-success:hover {
            color: #fff;
            background-color: #28a745;
            border-color: #ffffff;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
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
            cursor: pointer;
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
        .btn {
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Pengeluaran</a></li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card animate__animated animate__fadeInUp">
                        <div class="card-header">
                            <h4 class="card-title text-white">Data Pengeluaran</h4>

                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="example mr-3">
                                        <p class="mb-1">Filter Tahun</p>
                                        <select class="form-control" id="filter-year">
                                            <option value="">Semua Tahun</option>
                                            @for($year = date('Y'); $year >= 2020; $year--)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="example mr-2">
                                        <p class="mb-1">Filter Tanggal</p>
                                        <input class="form-control input-daterange-datepicker" type="text" name="daterange" placeholder="Masukkan Tanggal" disabled>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center mt-4">
                                    @if(session('activeRole') == 'Admin' || session('activeRole') == 'Bendahara')
                                    <a href="/add-pengeluaran" class="btn btn-outline-warning mr-2 animate__animated animate__bounceIn" title="Tambah" style="border-color: white;">
                                        <i class="fa fa-plus" style="color: white;"></i>
                                    </a>
                                    @endif
                                    
                                    @if(session('activeRole') == 'Admin' || session('activeRole') == 'Bendahara' || session('activeRole') == 'Reader')
                                    <form method="GET" action="{{ route('export.pengeluaran.pdf') }}" id="export-pdf-form" class="mr-2" target="_blank">
                                        <input type="hidden" name="year" id="export-year" value="{{ old('year') }}" />
                                        <input type="hidden" name="start_date" id="export-start-date" value="" />
                                        <input type="hidden" name="end_date" id="export-end-date" value="" />
                                        <button type="submit" title="Ekspor PDF" class="btn btn-outline-info animate__animated animate__bounceIn" style="border-color: white;"><i class="fa fa-print" style="color: white;"></i></button>
                                    </form>
                                    <form method="POST" action="{{ route('export.pengeluaran.excel') }}" id="export-excel-form" class="mr-2">
                                        @csrf
                                        <input type="hidden" name="year" id="export-year-excel" value="{{ old('year') }}" />
                                        <input type="hidden" name="start_date" id="export-start-date-excel" value="" />
                                        <input type="hidden" name="end_date" id="export-end-date-excel" value="" />
                                        <button type="submit" title="Ekspor Excel" class="btn btn-outline-informasi animate__animated animate__bounceIn" style="border-color: white;"><i class="fa fa-file-excel" style="color: white;"></i></button>
                                    </form>
                                    @endif

                                    @if(session('activeRole') == 'Admin' || session('activeRole') == 'Bendahara')
                                    <button class="btn btn-outline-light animate__animated animate__bounceIn" data-toggle="modal" data-target="#importModal" title="Impor Data">
                                        <i class="fa fa-file-import" style="color: white;"></i> 
                                    </button>
                                    @endif
                                </div>
                            </div>
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
                               @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeInDown">
                                    <strong>Kesalahan!</strong> {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Tutup"><span><i class="fa fa-times"></i></span></button>
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
                                <table id="pengeluaranTables" class="table table-responsive-md">
                                    <thead>
                                        <tr>
                                            <th><strong>No</strong></th>
                                            <th><strong>Nama</strong></th>
                                            <th><strong>Deskripsi</strong></th>
                                            <th><strong>Kategori</strong></th>
                                            <th><strong>Total(Rp)</strong></th>
                                            <th><strong>Tanggal</strong></th>
                                             @if(session('activeRole') != 'Reader')
                                            <th><strong>Opsi</strong></th>
                                            @endif
                                        </tr>
                                    </thead>
                                  <tfoot>
                                    <tr>
                                        <td colspan="4" style="text-align: left; font-size: 1.25em; font-weight: bold;"><strong>Total Jumlah:</strong></td>
                                        <td id="total-jumlah" style="text-align: left; font-size: 1.25em; font-weight: bold;">0</td>
                                        @if(session('activeRole') != 'Reader')
                                        <td></td>
                                        @endif
                                    </tr>
                                </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="importModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel" style="color: #ffffff">Import Data Pengeluaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('import-pengeluaran') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file">Pilih File Excel</label>
                            <input type="file" class="dropify" id="file" name="file[]" multiple required accept=".xls,.xlsx">
                            <div style="text-align: left;">
                                <a href="{{ route('download-template') }}">Download Template Excel</a>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('template.scripts')

    <input type="hidden" id="table-url-pengeluaran" value="{{ route('production') }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('main.js') }}"></script>
    
    <script>
        var filterData = {
            year: null,
            start_created_at: null,
            end_created_at: null,
            total_data: true
        };
    $(document).ready(function() {
        $('.input-daterange-datepicker').prop('disabled', true);
        $('.input-daterange-datepicker').val('');
        
        $('.input-daterange-datepicker').daterangepicker({
            opens: 'left',
            locale: { 
                format: 'YYYY/MM/DD'
            },
            autoUpdateInput: false
        });
    
            $('.input-daterange-datepicker').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'DD-MM-YYYY'
                },
                autoUpdateInput: false
            });
    
            var lastSelectedDates = {};
    
            $('#filter-year').on('change', function() {
                var selectedYear = $(this).val();
                $('#export-year').val(selectedYear);
                $('#export-year-excel').val(selectedYear);
    
                if (selectedYear) {
                    $('.input-daterange-datepicker').prop('disabled', false);
                    filterData.year = selectedYear;
    
                    // Reset tanggal awal dan akhir untuk tahun yang dipilih
                    filterData.start_created_at = selectedYear + '-01-01';
                    filterData.end_created_at = selectedYear + '-12-31';
    
                    // Perbarui daterangepicker dengan tanggal baru
                    $('.input-daterange-datepicker').data('daterangepicker').setStartDate(moment(filterData.start_created_at));
                    $('.input-daterange-datepicker').data('daterangepicker').setEndDate(moment(filterData.end_created_at));
                    $('.input-daterange-datepicker').val(moment(filterData.start_created_at).format('DD-MM-YYYY') + ' - ' + moment(filterData.end_created_at).format('DD-MM-YYYY'));
    
                    $('#export-start-date').val(filterData.start_created_at);
                    $('#export-end-date').val(filterData.end_created_at);
                    $('#export-start-date-excel').val(filterData.start_created_at);
                    $('#export-end-date-excel').val(filterData.end_created_at);
                } else {
                    $('.input-daterange-datepicker').prop('disabled', true).val('');
                    filterData.year = null;
                    filterData.start_created_at = null;
                    filterData.end_created_at = null;
    
                    $('#export-start-date, #export-end-date, #export-start-date-excel, #export-end-date-excel').val('');
                }
    
                reloadTable();
            });
    
            $('.input-daterange-datepicker').on('apply.daterangepicker', function(ev, picker) {
                // Hanya memperbarui input jika filter tahun sudah dipilih
                if (filterData.year) {
                    $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
    
                    filterData.start_created_at = picker.startDate.format('YYYY-MM-DD');
                    filterData.end_created_at = picker.endDate.format('YYYY-MM-DD');
    
                    $('#export-start-date').val(filterData.start_created_at);
                    $('#export-end-date').val(filterData.end_created_at);
                    $('#export-start-date-excel').val(filterData.start_created_at);
                    $('#export-end-date-excel').val(filterData.end_created_at);
    
                    // Simpan tanggal yang dipilih untuk tahun ini
                    lastSelectedDates[filterData.year] = {
                        start: picker.startDate,
                        end: picker.endDate
                    };
    
                    reloadTable();
                }
            });
    
            // Inisialisasi DataTable
            var pengeluaranTable = $('#pengeluaranTables').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                language: {
                    paginate: {
                        next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                        previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
                    }
                },
                ajax: {
                    url: $('#table-url-pengeluaran').val(),
                    method: 'GET',
                    data: function(d) {
                        d.year = filterData.year;
                        d.start_created_at = filterData.start_created_at;
                        d.end_created_at = filterData.end_created_at;
                    },
                    error: function(xhr, error, thrown) {
                        console.error('AJAX Error:', xhr.responseText);
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'description', name: 'description' },
                    { data: 'category', name: 'category' },
                    { data: 'jumlah', name: 'jumlah' },
                    { data: 'tanggal', name: 'tanggal' },
                    @if(session('activeRole') != 'Reader')
                    { data: 'opsi', name: 'opsi', orderable: false, searchable: false }
                    @endif
                ],
                drawCallback: function(settings) {
                    totalPengeluaran(filterData);
                }
            });

            // Add click event handler for table rows
            $('#pengeluaranTables tbody').on('click', 'tr td:not(:last-child)', function() {
                var data = pengeluaranTable.row($(this).closest('tr')).data();
                if (data) {
                    window.location.href = '/pengeluaran/' + data.id;
                }
            });
    
            function totalPengeluaran(filter) {
                $.ajax({
                    url: $('#table-url-pengeluaran').val(),
                    method: 'GET',
                    data: { ...filter, total_data: true },
                    success: function(data) {
                        var jumlah = data.replace(/Rp/g, '').replace(/\./g, '').trim();
                        $('#total-jumlah').html('Rp' + Number(jumlah).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 }));
                    },
                    error: function(xhr, error, thrown) {
                        console.error('AJAX Error:', xhr.responseText);
                    }
                });
            }
    
            function reloadTable() {
                pengeluaranTable.ajax.reload(null, false);
            }
        });
    </script>
    
    <script>
        $(document).ready(function(){
            $('.dropify').dropify();

            $('.dropify-wrapper .dropify-message p').css('font-size', '20px');
        });
    </script>

    <script>
    $(document).ready(function() {
        // Konfirmasi penghapusan
        $(document).on('click', '.delete-all', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var url = $(this).attr('href');
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
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
                    window.location.href = url;
                }
            });
        });
    });
    </script>
</body>
</html>