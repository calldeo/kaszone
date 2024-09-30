<!DOCTYPE html>
<html lang="en">
<head>
    @include('template.headerr')
    <title>PityCash | {{ auth()->user()->level }} | Laporan</title>
</head>
<body>

    @include('template.topbarr')
    @include('template.sidebarr')

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Hi, Welcome Back!</h4>
                        <p class="mb-0">Data Pengeluaran</p>
                    </div>
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
                                
                                <div class="text-right mt-3"> <!-- Menambahkan margin-top untuk menurunkan posisi -->
                                    @hasrole('Admin|Bendahara') 
                                    <a href="/add_pengeluaran" class="btn btn-warning ml-1" title="Add">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                    @endhasrole
                                
                                    @hasrole('Admin|Bendahara') 
                                    <a href="/cetakpgl" target="_blank" class="btn btn-info ml-1" title="Print Report">
                                        <i class="fa fa-print"></i>
                                    </a>
                                    @endhasrole
                                
                                    @hasrole('Admin|Bendahara') 
                                    <a href="{{ url('/export-pengeluaran') }}" class="btn btn-success ml-1" title="Export to Excel">
                                        <i class="fa fa-file-excel"></i>
                                    </a>
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

            <!-- Pengeluaran Section -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                   
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="pengeluaranTables" class="table table-responsive-md">
                                    <thead>
                                        <tr>
                                            <th><strong>No</strong></th>
                                            <th><strong>Nama</strong></th>
                                            <th><strong>Deskripsi</strong></th>
                                            <th><strong>Kategori</strong></th>
                                            <th><strong>Tanggal</strong></th>
                                            <th><strong>Jumlah Satuan</strong></th>
                                            <th><strong>Nominal(Rp)</strong></th>
                                            <th><strong>Total(Rp)</strong></th>
                                        </tr>
                                    </thead>
                                  <tfoot>
                                    <tr>
                                        <td colspan="5" style="text-align: left; font-size: 1.25em; font-weight: bold;"><strong>Total Jumlah:</strong></td>
                                        <td id="total-pengeluaran" style="text-align: left; font-size: 1.25em; font-weight: bold;">0</td>
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

            // Initialize daterangepicker
            $('.input-daterange-datepicker').daterangepicker({
                opens: 'left',
                locale: { 
                    format: 'YYYY-MM-DD'
                },
                autoUpdateInput: false // Prevent auto-filling on initialization
            });

            // Event handler for year filter
            $('#filter-year').on('change', function() {
                var selectedYear = $(this).val();
                if (selectedYear !== "") {
                    $('.input-daterange-datepicker').prop('disabled', false);
                    filterData.year = selectedYear;

                    // Set default start and end date for the selected year
                    filterData.start_created_at = selectedYear + '-01-01';
                    filterData.end_created_at = selectedYear + '-12-31';

                    // Update daterangepicker dates
                    $('.input-daterange-datepicker').data('daterangepicker').setStartDate(filterData.start_created_at);
                    $('.input-daterange-datepicker').data('daterangepicker').setEndDate(filterData.end_created_at);

                    // Reload the tables
 
                    pengeluaranTables(filterData);
                } else {
                    $('.input-daterange-datepicker').prop('disabled', true);
                }
            });

            // Update input after selecting date range
            $('.input-daterange-datepicker').on('apply.daterangepicker', function(ev, picker) {
                // Update input value
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));

                // Update filter data
                filterData.start_created_at = picker.startDate.format('YYYY-MM-DD');
                filterData.end_created_at = picker.endDate.format('YYYY-MM-DD');

                // Reload the tables
   
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
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name' },
                    { data: 'description' },
                    { data: 'category' },
                    { data: 'tanggal' },
                    { data: 'jumlah_satuan' },
                    { data: 'nominal' },
                    { data: 'jumlah' }
                ],
                drawCallback: function(settings) {
                    var total = this.api().column(7).data().reduce(function(a, b) {
                        return a + parseFloat(b);
                    }, 0);
                    $('#total-pengeluaran').html(total.toLocaleString());
                }
            });
        }
    </script>

</body>
</html>
