<!DOCTYPE html>
<html lang="id">
<head>
    @include('template.headerr')
    <title>PityCash | {{ auth()->user()->level }} | Pengeluaran</title>
</head>
<body>

    @include('template.topbarr')
    @include('template.sidebarr')

    <div class="content-body" style="margin-top: -60px;"> <!-- Atur margin-top untuk menggeser konten ke atas -->
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                   
                </div>
            </div>

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
                                    <p class="mb-1">Filter Tanggal</p>
                                    <input class="form-control input-daterange-datepicker" type="text" name="daterange" placeholder="Masukkan Tanggal" disabled>
                                </div>
                                
                                <div class="d-flex align-items-center mt-4">
                                    @if(session('activeRole') == 'Admin' || session('activeRole') == 'Bendahara')
                                    <a href="/add-pengeluaran" class="btn btn-warning mr-2" title="Add">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                    @endif
                                    
                                    @if(session('activeRole') == 'Admin' || session('activeRole') == 'Bendahara' || session('activeRole') == 'Reader')
                                    <form method="GET" action="{{ route('export.pengeluaran.pdf') }}" id="export-pdf-form" class="mr-2">
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
                                    @endif

                                    @if(session('activeRole') == 'Admin' || session('activeRole') == 'Bendahara')
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#importModal" title="Import Data">
                                        <i class="fa fa-file-import"></i> 
                                    </button>
                                    @endif
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
                                            <th><strong>Total(Rp)</strong></th>
                                            <th><strong>Tanggal</strong></th>

                                            <th><strong>Opsi<strong></th>

                                        </tr>
                                    </thead>
                                  <tfoot>
                                    <tr>
                                        <td colspan="4" style="text-align: left; font-size: 1.25em; font-weight: bold;"><strong>Total Jumlah:</strong></td>
                                        <td id="total-jumlah" style="text-align: left; font-size: 1.25em; font-weight: bold;">0</td>
                                        <td></td>
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

    @include('template.scripts')


    <input type="hidden" id="table-url-pengeluaran" value="{{ route('production') }}">

    
    <script>
        var filterData = {
            year: null,
            start_created_at: null,
            end_created_at: null,
            total_data: true
        };
    
        $(document).ready(function() {
            $('.input-daterange-datepicker').prop('disabled', true);
    
            // Konfigurasi daterangepicker dengan format DD/MM/YYYY
            $('.input-daterange-datepicker').daterangepicker({
                opens: 'left',
                locale: { 
                    format: 'DD/MM/YYYY' // Ubah format menjadi DD/MM/YYYY
                },
                autoUpdateInput: false // Nonaktifkan pembaruan otomatis
            });
    
            $('#filter-year').on('change', function() {
                var selectedYear = $(this).val();
                $('#export-year').val(selectedYear);
                $('#export-year-excel').val(selectedYear);
    
                if (selectedYear !== "") {
                    $('.input-daterange-datepicker').prop('disabled', false);
                    filterData.year = selectedYear;
                    filterData.start_created_at = selectedYear + '-01-01'; // Set tanggal mulai ke 1 Januari
                    filterData.end_created_at = selectedYear + '-12-31'; // Set tanggal akhir ke 31 Desember
    
                    // Set tanggal sesuai tahun yang dipilih dalam format DD/MM/YYYY
                    var startDateFormatted = moment(filterData.start_created_at).format('DD/MM/YYYY');
                    var endDateFormatted = moment(filterData.end_created_at).format('DD/MM/YYYY');
                    
                    $('.input-daterange-datepicker').data('daterangepicker').setStartDate(startDateFormatted);
                    $('.input-daterange-datepicker').data('daterangepicker').setEndDate(endDateFormatted);
    
                    // Menampilkan rentang tanggal dalam input
                    $('.input-daterange-datepicker').val(startDateFormatted + ' - ' + endDateFormatted);
                    
                    $('#export-start-date').val(startDateFormatted);
                    $('#export-end-date').val(endDateFormatted);
                    $('#export-start-date-excel').val(startDateFormatted);
                    $('#export-end-date-excel').val(endDateFormatted);
    
                    pengeluaranTables(filterData);
                } else {
                    $('.input-daterange-datepicker').prop('disabled', true);
                    filterData.year = null;
                    filterData.start_created_at = null;
                    filterData.end_created_at = null;
                    
                    $('.input-daterange-datepicker').val('');
                    $('#export-start-date').val('');
                    $('#export-end-date').val('');
                    $('#export-start-date-excel').val('');
                    $('#export-end-date-excel').val('');
    
                    pengeluaranTables(filterData);
                }
            });
    
            $('.input-daterange-datepicker').on('apply.daterangepicker', function(ev, picker) {
                // Mengatur nilai input dengan format DD/MM/YYYY
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                filterData.start_created_at = picker.startDate.format('YYYY-MM-DD');
                filterData.end_created_at = picker.endDate.format('YYYY-MM-DD');
                
                $('#export-start-date').val(picker.startDate.format('DD/MM/YYYY'));
                $('#export-end-date').val(picker.endDate.format('DD/MM/YYYY'));
                $('#export-start-date-excel').val(picker.startDate.format('DD/MM/YYYY'));
                $('#export-end-date-excel').val(picker.endDate.format('DD/MM/YYYY'));
    
                pengeluaranTables(filterData);
            });
    
            pengeluaranTables(filterData); // Memanggil fungsi tabel awal
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
                footerCallback: function(row, data, start, end, display) {
                    filter.total_data = true;
                    totalPengeluaran(filter);
                }
            });
        }
    
        function totalPengeluaran(filter) {
            $.ajax({
                url: $('#table-url-pengeluaran').val(),
                method: 'GET',
                data: filter,
                success: function(data) {
                    console.log(data);
                    var jumlah = data.replace(/Rp/g, '').replace(/\./g, '').trim();
                    $('#total-jumlah').html('Rp' + Number(jumlah).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 }));
                },
                error: function(xhr, error, thrown) {
                    console.error('AJAX Error:', xhr.responseText);
                }
            });
        }
    </script>
    

    
    </script>
<script>
    $(document).ready(function(){
        $('.dropify').dropify();

        $('.dropify-wrapper .dropify-message p').css('font-size', '20px');
    });
</script>
</body>
</html>