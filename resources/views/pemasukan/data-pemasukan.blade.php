<!DOCTYPE html>
<html lang="id">

<head>
    @include('template.headerr')
    <title>PityCash | {{ auth()->user()->level }} | Pemasukan</title>
</head>
<body>
    @include('template.topbarr')
    @include('template.sidebarr')

    <div class="content-body" style="margin-top: -60px;"> <!-- Atur margin-top untuk menggeser konten ke atas -->
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Tabel</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Pemasukan</a></li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card" style="margin-top: -30px;">
                        <div class="card-header">
                            <h4 class="card-title">Data Pemasukan</h4>

                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="example mr-3">
                                        <p class="mb-1">Filter Tahun</p>
                                        <select class="form-control" id="filter-year">
                                            <option value="">Pilih Tahun</option>
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
                                    @hasrole('Admin|Bendahara')
                                    <a href="/add_pemasukan" class="btn btn-warning mr-2" title="Tambah">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                    @endhasrole
                                    @hasrole('Admin|Bendahara|Reader') 
                                    <form method="GET" action="{{ route('export.pemasukan') }}" id="export-pdf-form" class="mr-2">
                                        <input type="hidden" name="year" id="export-year" value="{{ old('year') }}" />
                                        <input type="hidden" name="start_date" id="export-start-date" value="{{ old('start_date') }}" />
                                        <input type="hidden" name="end_date" id="export-end-date" value="{{ old('end_date') }}" />
                                        <button type="submit" title="Ekspor PDF" class="btn btn-info"><i class="fa fa-print"></i></button>
                                    </form>
                                    
                                    <form method="POST" action="{{ route('export.pemasukan.excel') }}" id="export-excel-form" class="mr-2">
                                        @csrf
                                        <input type="hidden" name="year" id="export-year-excel" value="{{ old('year') }}" />
                                        <input type="hidden" name="start_date" id="export-start-date-excel" value="{{ old('start_date') }}" />
                                        <input type="hidden" name="end_date" id="export-end-date-excel" value="{{ old('end_date') }}" />
                                        <button type="submit" title="Ekspor Excel" class="btn btn-success"><i class="fa fa-file-excel"></i></button>
                                    </form>
                                    @endhasrole

                                    @hasrole('Admin|Bendahara')
                                    <button type="button" class="btn btn-primary" title="Impor Data" data-toggle="modal" data-target="#importModal">
                                        <i class="fa fa-file-import"></i> 
                                    </button>
                                    @endhasrole
                                </div>

                                @hasrole('Admin|Bendahara') 
                                <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="importModalLabel">Impor Data Pemasukan</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('import-pemasukan') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="file">Pilih File Excel</label>
                                                        <input type="file" class="dropify" id="file" name="file" required accept=".xls,.xlsx">

                                                    <div style="text-align: left;">
                                                        <a href="{{ route('download.template.pemasukan') }}">Unduh Template Excel</a>
                                                    </div>

                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Impor</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                @endhasrole
                            </div>
                        </div>

                        <div class="card-body">
                            @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <svg viewbox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                    <polyline points="9 11 12 14 22 4"></polyline>
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                </svg>
                                <strong>Berhasil!</strong> {{ session('success') }}
                                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Tutup"><span><i class="mdi mdi-close"></i></span></button>
                            </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <strong>Kesalahan!</strong> {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Tutup"><span><i class="fa fa-times"></i></span></button>
                                </div>
                            @endif
                            @if(session('update_success'))
                            <div class="alert alert-warning alert-dismissible fade show">
                                <svg viewbox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                    <polyline points="9 11 12 14 22 4"></polyline>
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                </svg>
                                <strong>Berhasil!</strong> {{ session('update_success') }}
                                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Tutup"><span><i class="mdi mdi-close"></i></span></button>
                            </div>
                            @endif
                            <div class="table-responsive">
                                <table id="pemasukanTables" class="table table-responsive-md">
                                    <thead>
                                        <tr>
                                            <th style="width:50px;"><strong>No</strong></th>
                                            <th><strong>Nama</strong></th>
                                            <th><strong>Deskripsi</strong></th>
                                            <th><strong>Kategori</strong></th>
                                            <th><strong>Tanggal</strong></th>
                                            <th><strong>Jumlah(Rp)</strong></th>
                                            <th><strong>Opsi</strong></th>
                                        </tr>
                                    </thead>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" style="text-align: left; font-size: 1.25em; font-weight: bold;"><strong>Total Jumlah:</strong></td>
                                        <td id="total_jumlah" style="text-align: left; font-size: 1.25em; font-weight: bold;">0</td>
                                        <td></td>
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

    <div class="modal fade" id="adminDetailModal" tabindex="-1" role="dialog" aria-labelledby="adminDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adminDetailModalLabel">Detail Pemasukan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>No:</strong><br />
                            <strong>Nama:</strong><br />
                            <strong>Deskripsi:</strong><br />
                            <strong>Tanggal:</strong><br />
                            <strong>Jumlah:</strong><br />
                            <strong>Kategori:</strong><br />
                        </div>
                        <div class="col-sm-8">
                            <div id="id_data"></div>
                            <div id="name"></div>
                            <div id="description"></div>
                            <div id="date"></div>
                            <div id="jumlah"></div>
                            <div id="category"></div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @include('template.scripts')

    <input type="hidden" id="table-url-pemasukan" value="{{ route('income') }}">
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

            $('.input-daterange-datepicker').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            $('#filter-year').on('change', function() {
                var selectedYear = $(this).val();
                $('#export-year').val(selectedYear);
                $('#export-year-excel').val(selectedYear);

                if (selectedYear !== "") {
                    $('.input-daterange-datepicker').prop('disabled', false);
                    filterData.year = selectedYear;

                    filterData.start_created_at = selectedYear + '-01-01';
                    filterData.end_created_at = selectedYear + '-12-31';

                    $('.input-daterange-datepicker').data('daterangepicker').setStartDate(filterData.start_created_at);
                    $('.input-daterange-datepicker').data('daterangepicker').setEndDate(filterData.end_created_at);
                    
                    $('#export-start-date').val(filterData.start_created_at);
                    $('#export-end-date').val(filterData.end_created_at);
                    $('#export-start-date-excel').val(filterData.start_created_at);
                    $('#export-end-date-excel').val(filterData.end_created_at);
                } else {
                    $('.input-daterange-datepicker').prop('disabled', true);
                    filterData.year = null;
                    filterData.start_created_at = null;
                    filterData.end_created_at = null;
                    
                    $('#export-start-date').val('');
                    $('#export-end-date').val('');
                    $('#export-start-date-excel').val('');
                    $('#export-end-date-excel').val('');
                }

                reloadTable();
            });

            $('.input-daterange-datepicker').on('apply.daterangepicker', function(ev, picker) {
                filterData.start_created_at = picker.startDate.format('YYYY-MM-DD');
                filterData.end_created_at = picker.endDate.format('YYYY-MM-DD');
                
                $('#export-start-date').val(filterData.start_created_at);
                $('#export-end-date').val(filterData.end_created_at);
                $('#export-start-date-excel').val(filterData.start_created_at);
                $('#export-end-date-excel').val(filterData.end_created_at);
                
                reloadTable();
            });

            var pemasukanTable = $('#pemasukanTables').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    paginate: {
                        next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                        previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
                    }
                },
                ajax: {
                    url: $('#table-url-pemasukan').val(),
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
                    { data: 'date', name: 'date' },
                    { data: 'jumlah', name: 'jumlah' },
                    { data: 'opsi', name: 'opsi', orderable: false, searchable: false }
                ],
                drawCallback: function(settings) {
                    totalPemasukan(filterData);
                }
            });

            function totalPemasukan(filter){
                $.ajax({
                    url: $('#table-url-pemasukan').val(),
                    method: 'GET',
                    data: {...filter, total_data: true},
                    success: function(data) {
                        $('#total_jumlah').html(data);
                    },
                    error: function(xhr, error, thrown) {
                        console.error('AJAX Error:', xhr.responseText);
                    }
                });
            }

            function reloadTable() {
                pemasukanTable.ajax.reload(null, false);
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#kategoriTable').DataTable();
            
            $('#adminDetailModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var url = button.data('url');
                
                var modal = $(this);
                
                modal.find('#id_data').text('');
                modal.find('#name').text('');
                modal.find('#description').text('');
                modal.find('#date').text('');
                modal.find('#jumlah').text('');
                modal.find('#category').text(''); 

                
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(data) {
                        modal.find('#id_data').text(data.id_data || 'N/A');
                        modal.find('#name').text(data.name || 'N/A');
                        modal.find('#description').text(data.description || 'N/A');
                        var date = new Date(data.date);
                        var formattedDate = date.getDate().toString().padStart(2, '0') + '-' +
                                            (date.getMonth() + 1).toString().padStart(2, '0') + '-' +
                                            date.getFullYear();
                        modal.find('#date').text(formattedDate || 'N/A');
                        var formattedJumlah = 'Rp' + parseFloat(data.jumlah || 0).toLocaleString('id-ID');
                        modal.find('#jumlah').text(formattedJumlah);
                        modal.find('#category').text(data.category_name || 'N/A');
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        modal.find('.modal-body').html('Terjadi kesalahan saat memuat detail');
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