<!DOCTYPE html>
<html lang="en">
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
                            <div class="d-flex align-items-center"> <!-- Menggunakan align-items-center -->
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
                                
                                <div class="d-flex align-items-center mt-4"> <!-- Menghapus margin-top -->
                                    @hasrole('Admin|Bendahara') 
                                    <a href="/add_pengeluaran" class="btn btn-warning mr-2" title="Add">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                    @endhasrole
                                    
                                    @hasrole('Admin|Bendahara|Reader') 
                                    <a href="/cetakpgl" target="_blank" class="btn btn-info mr-2" title="Print Report">
                                        <i class="fa fa-print"></i>
                                    </a>
                                    @endhasrole
                                    
                                    @hasrole('Admin|Bendahara|Reader') 
                                    <form method="POST" action="{{ route('export.pengeluaran.excel') }}" id="export-excel-form" class="mr-2 d-inline"> <!-- Menambahkan d-inline agar tidak ada blok baru -->
                                        @csrf
                                        <input type="hidden" name="year" id="export-year-excel" value="{{ old('year') }}" />
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
                                <strong>Success!</strong> {{ session('success') }}
                                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
                            </div>
                            @endif
                            @if(session('update_success'))
                            <div class="alert alert-warning alert-dismissible fade show">
                                <svg viewbox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                    <polyline points="9 11 12 14 22 4"></polyline>
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                </svg>
                                <strong>Success!</strong> {{ session('update_success') }}
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
    // Disable date picker at the start
    $('.input-daterange-datepicker').prop('disabled', true);

    // Initialize daterangepicker with DD/MM/YYYY format
    $('.input-daterange-datepicker').daterangepicker({
        opens: 'left',
        locale: { 
            format: 'DD/MM/YYYY' // Changed format to DD/MM/YYYY
        },
        autoUpdateInput: false // Prevent auto-filling on initialization
    });

    // Event handler for year filter
    $('#filter-year').on('change', function() {
        var selectedYear = $(this).val();
        $('#export-year').val(selectedYear); // Update hidden export year fields
        $('#export-year-excel').val(selectedYear);

        if (selectedYear !== "") {
            // Enable date picker and reset date range input
            $('.input-daterange-datepicker').prop('disabled', false);

            // Set filter data with selected year
            filterData.year = selectedYear;

            // Set default start and end date for the selected year
            filterData.start_created_at = selectedYear + '-01-01';
            filterData.end_created_at = selectedYear + '-12-31';

            // Update daterangepicker dates and display them in DD/MM/YYYY format
            $('.input-daterange-datepicker').data('daterangepicker').setStartDate(filterData.start_created_at);
            $('.input-daterange-datepicker').data('daterangepicker').setEndDate(filterData.end_created_at);

            // Automatically fill the input with start and end date in new format
            var formattedStartDate = moment(filterData.start_created_at).format('DD/MM/YYYY');
            var formattedEndDate = moment(filterData.end_created_at).format('DD/MM/YYYY');
            $('.input-daterange-datepicker').val(formattedStartDate + ' - ' + formattedEndDate);

            // Reload the tables
            pengeluaranTables(filterData);
        } else {
            // Disable date picker and reset filter data when year is cleared
            $('.input-daterange-datepicker').prop('disabled', true);
            $('.input-daterange-datepicker').val(''); // Clear date range input
            filterData.year = null;
            filterData.start_created_at = null;
            filterData.end_created_at = null;

            // Reload the tables with no year filter
            pengeluaranTables(filterData);
        }
    });

    // Update input after selecting date range
    $('.input-daterange-datepicker').on('apply.daterangepicker', function(ev, picker) {
        // Update input value with selected date range in DD/MM/YYYY format
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));

        // Update filter data with selected date range
        filterData.start_created_at = picker.startDate.format('YYYY-MM-DD');
        filterData.end_created_at = picker.endDate.format('YYYY-MM-DD');

        // Reload the tables with new date range filter
        pengeluaranTables(filterData);
    });

    // Load tables on page load
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

                // Calculate total amount
                api.rows().every(function(rowIdx, tableLoop, rowLoop) {
                    var data = this.data();
                    var jumlah = parseFloat(data.jumlah.replace(/Rp/g, '').replace(/\./g, '').replace(/,/g, '').trim()) || 0;
                    totalJumlah += jumlah;
                });

                // Display total amount in IDR format
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
