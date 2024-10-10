<!DOCTYPE html>
<html lang="id">
<head>
    @include('template.headerr')
    <title>PityCash | {{ auth()->user()->level }} | Pengeluaran</title>
</head>
<body>

    @include('template.topbarr')
    @include('template.sidebarr')

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                   
                </div>
            </div>

            <!-- Filter Section -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Data Pengeluaran</h4>
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
                                    <p class="mb-0">Filter Tanggal</p>
                                    <input class="form-control input-daterange-datepicker" type="text" name="daterange" placeholder="Masukkan Tanggal">
                                </div>
                                
                                <div class="d-flex align-items-center mt-4">
                                    @hasrole('Admin|Bendahara') 
                                    <a href="/add_pengeluaran" class="btn btn-warning mr-2" title="Add">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                    @endhasrole
                                    
                                 @hasrole('Admin|Bendahara|Reader') 
                                    <form method="GET" action="{{ route('cetakpgl') }}" id="export-pdf-form" class="mr-2">
                                        <input type="hidden" name="year" id="export-year" value="{{ old('year') }}" />
                                        <input type="hidden" name="start_date" id="export-start-date" value="" />
                                        <input type="hidden" name="end_date" id="export-end-date" value="" />
                                        <button type="submit" title="Export PDF" class="btn btn-info"><i class="fa fa-print"></i></button>
                                    </form>
                                    
                                    <form method="POST" action="{{ route('export.pengeluaran.excel') }}" id="export-excel-form" class="mr-2">
                                        @csrf
                                        <input type="hidden" name="year" id="export-year-excel" value="{{ old('year') }}" />
                                        <input type="hidden" name="start_date" id="export-start-date-excel" value="" />
                                        <input type="hidden" name="end_date" id="export-end-date-excel" value="" />
                                        <button type="submit" title="Export Excel" class="btn btn-success"><i class="fa fa-file-excel"></i></button>
                                    </form>
                                    @endhasrole
                                    @hasrole('Admin|Bendahara')
                                    <!-- Import Data Button -->
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#importModal" title="Import Data">
                                        <i class="fa fa-file-import"></i> 
                                    </button>
                                    @endhasrole
                                </div>
                            </div>
                        </div>
                        
                        <div id="importModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="importModalLabel">Import Data Pengeluaran</h5>
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
            <!-- Pengeluaran Section -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                   
                        <div class="card-body">
                               @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <svg viewbox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                    <polyline points="9 11 12 14 22 4"></polyline>
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                </svg>
                                <strong>Berhasil!</strong> {{ session('success') }}
                                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
                            </div>
                            @endif
                            @if(session('update_success'))
                            <div class="alert alert-warning alert-dismissible fade show">
                                <svg viewbox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                    <polyline points="9 11 12 14 22 4"></polyline>
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                </svg>
                                <strong>Berhasil!</strong> {{ session('update_success') }}
                                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
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
                                            {{-- <th><strong>Jumlah Satuan</strong></th> --}}
                                            {{-- <th><strong>Nominal(Rp)</strong></th> --}}
                                            <th><strong>Total(Rp)</strong></th>
                                            {{-- <th><strong>Foto</strong></th> --}}
                                            <th><strong>Tanggal</strong></th>

                                            <th><strong>Opsi<strong></th>

                                        </tr>
                                    </thead>
                                  <tfoot>
                                    <tr>
                                        <td colspan="5" style="text-align: left; font-size: 1.25em; font-weight: bold;"><strong>Total Jumlah:</strong></td>
                                        <td id="total-jumlah" style="text-align: left; font-size: 1.25em; font-weight: bold;">0</td>
                                        <td></td> <!-- Kolom Opsi dikosongkan -->
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

    @include('template.scripts')


    <input type="hidden" id="table-url-pengeluaran" value="{{ route('production') }}">
<script>
    var filterData = {
        year: null,
        start_created_at: null,
        end_created_at: null
    };
$(document).ready(function() {
    // Nonaktifkan date picker di awal
    $('.input-daterange-datepicker').prop('disabled', true);

    // Inisialisasi daterangepicker dengan format YYYY/MM/DD
    $('.input-daterange-datepicker').daterangepicker({
        opens: 'left',
        locale: { 
            format: 'YYYY/MM/DD'
        },
        autoUpdateInput: false
    });

    // Event handler untuk filter tahun
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
            var formattedStartDate = moment(filterData.start_created_at).format('YYYY/MM/DD');
            var formattedEndDate = moment(filterData.end_created_at).format('YYYY/MM/DD');
            $('.input-daterange-datepicker').val(formattedStartDate + ' - ' + formattedEndDate);
            
            // Perbarui hidden fields untuk ekspor
            $('#export-start-date').val(formattedStartDate);
            $('#export-end-date').val(formattedEndDate);
            $('#export-start-date-excel').val(formattedStartDate);
            $('#export-end-date-excel').val(formattedEndDate);

            pengeluaranTables(filterData);
        } else {
            $('.input-daterange-datepicker').prop('disabled', true);
            $('.input-daterange-datepicker').val('');
            filterData.year = null;
            filterData.start_created_at = null;
            filterData.end_created_at = null;
            
            // Kosongkan hidden fields untuk ekspor
            $('#export-start-date').val('');
            $('#export-end-date').val('');
            $('#export-start-date-excel').val('');
            $('#export-end-date-excel').val('');

            pengeluaranTables(filterData);
        }
    });

    // Perbarui input setelah memilih rentang tanggal
    $('.input-daterange-datepicker').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
        filterData.start_created_at = picker.startDate.format('YYYY-MM-DD');
        filterData.end_created_at = picker.endDate.format('YYYY-MM-DD');
        
        // Perbarui hidden fields untuk ekspor
        $('#export-start-date').val(picker.startDate.format('YYYY/MM/DD'));
        $('#export-end-date').val(picker.endDate.format('YYYY/MM/DD'));
        $('#export-start-date-excel').val(picker.startDate.format('YYYY/MM/DD'));
        $('#export-end-date-excel').val(picker.endDate.format('YYYY/MM/DD'));

        pengeluaranTables(filterData);
    });

    // Muat tabel saat halaman dimuat
    pengeluaranTables(filterData);
});


    function pengeluaranTables(filter) {
        $('#pengeluaranTables').DataTable({
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
                data: function(d) {
                    d.year = filter.year;
                    d.start_created_at = filter.start_created_at;
                    d.end_created_at = filter.end_created_at;
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'description', name: 'description' },
                { data: 'category', name: 'category' },
                { data: 'jumlah', name: 'jumlah' },
                { data: 'tanggal', name: 'tanggal' },
                { data: 'opsi', name: 'opsi', orderable: false, searchable: false }
            ],
            drawCallback: function(settings) {
                var api = this.api();
                var totalJumlah = 0;

                // Hitung total jumlah
                api.rows().every(function(rowIdx, tableLoop, rowLoop) {
                    var data = this.data();
                    var jumlah = parseFloat(data.jumlah.replace(/Rp/g, '').replace(/\./g, '').replace(/,/g, '').trim()) || 0;
                    totalJumlah += jumlah;
                });

                // Tampilkan total jumlah dalam format IDR
                $('#total-jumlah').html(totalJumlah.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }));
            }
        });
    }




      
    </script>
<script>
    $(document).ready(function(){
        // Inisialisasi Dropify
        $('.dropify').dropify();

        // Mengubah ukuran font di area Dropify setelah inisialisasi
        $('.dropify-wrapper .dropify-message p').css('font-size', '20px'); // Ganti '12px' dengan ukuran yang diinginkan
    });
</script>
</body>
</html>