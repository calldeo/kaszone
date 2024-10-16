<!DOCTYPE html>
<html lang="en">
<head>
    @include('template.headerr')
    <title>PityCash | {{ auth()->user()->level }} | Laporan</title>
</head>
<body>

    @include('template.topbarr')
    @include('template.sidebarr')

    <div class="content-body" style="margin-top: -60px;"> <!-- Atur margin-top untuk menggeser konten ke atas -->
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0"></div>
            </div>

            <!-- Filter Section -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
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
                                <div class="d-flex align-items-center mt-3">
                                    @hasrole('Admin|Bendahara')
                                    <form method="GET" action="{{ route('export.laporan') }}" id="export-pdf-form" class="mr-1">
                                        <input type="hidden" name="year" id="export-year-pdf" value="{{ old('year') }}" />
                                        <input type="hidden" name="start_date" id="export-start-date-pdf" value="{{ old('start_date') }}" />
                                        <input type="hidden" name="end_date" id="export-end-date-pdf" value="{{ old('end_date') }}" />
                                        <button type="submit" class="btn btn-info">
                                            <i class="fa fa-print"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('export.laporan.excel') }}" id="export-excel-form" class="mr-1">
                                        @csrf
                                        <input type="hidden" name="year" id="export-year-excel" value="{{ old('year') }}" />
                                        <input type="hidden" name="start_date" id="export-start-date-excel" value="{{ old('start_date') }}" />
                                        <input type="hidden" name="end_date" id="export-end-date-excel" value="{{ old('end_date') }}" />
                                        <button type="submit" class="btn btn-success">
                                            <i class="fa fa-file-excel"></i>
                                        </button>
                                    </form>
                                    @endhasrole
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="pemasukanTables" class="table table-responsive-md">
                                    <thead>
                                        <tr>
                                            <th><strong>No</strong></th>
                                            <th><strong>Nama</strong></th>
                                            <th><strong>Deskripsi</strong></th>
                                            <th><strong>Kategori</strong></th>
                                            <th><strong>Tanggal</strong></th>
                                            <th><strong>Jumlah(Rp)</strong></th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" style="text-align: left; font-size: 1.25em; font-weight: bold;"><strong>Total Jumlah:</strong></th>
                                            <th id="total-pemasukan" style="text-align: left; font-size: 1.25em; font-weight: bold;">0</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pengeluaran Section -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Data Pengeluaran</h4>
                        </div>
                        <div class="card-body">
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
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" style="text-align: left; font-size: 1.25em; font-weight: bold;"><strong>Total Jumlah:</strong></td>
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

    <input type="hidden" id="table-url-pemasukan" value="{{ route('income') }}">
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
        $('.input-daterange-datepicker').val('');
        $('.input-daterange-datepicker').daterangepicker({
            opens: 'left',
            locale: {
                format: 'DD-MM-YYYY'
            },
            autoUpdateInput: false,
            minDate: moment().startOf('year'),
            maxDate: moment().endOf('year')
        });

        // Event handler for year filter
        $('#filter-year').on('change', function() {
            var selectedYear = $(this).val();
            if (selectedYear !== "") {
                $('.input-daterange-datepicker').prop('disabled', false);
                filterData.year = selectedYear;

                // Reset start and end date
                filterData.start_created_at = selectedYear + '-01-01';
                filterData.end_created_at = selectedYear + '-12-31';

                // Update daterangepicker dates and options
                $('.input-daterange-datepicker').data('daterangepicker').setStartDate(moment(filterData.start_created_at));
                $('.input-daterange-datepicker').data('daterangepicker').setEndDate(moment(filterData.end_created_at));
                $('.input-daterange-datepicker').data('daterangepicker').minDate = moment(filterData.start_created_at);
                $('.input-daterange-datepicker').data('daterangepicker').maxDate = moment(filterData.end_created_at);

                // Update input value
                $('.input-daterange-datepicker').val(moment(filterData.start_created_at).format('DD-MM-YYYY') + ' - ' + moment(filterData.end_created_at).format('DD-MM-YYYY'));

                // Update hidden inputs for export
                $('#export-year-pdf, #export-year-excel').val(selectedYear);
                $('#export-start-date-pdf, #export-start-date-excel').val(filterData.start_created_at);
                $('#export-end-date-pdf, #export-end-date-excel').val(filterData.end_created_at);

            } else {
                // Jika "Pilih Tahun" dipilih, hapus filter dan tampilkan seluruh data
                filterData.year = null;
                filterData.start_created_at = null;
                filterData.end_created_at = null;

                $('.input-daterange-datepicker').prop('disabled', true);
                $('.input-daterange-datepicker').val(''); // Menghapus nilai input

                // Reset daterangepicker
                $('.input-daterange-datepicker').data('daterangepicker').setStartDate(moment().startOf('year'));
                $('.input-daterange-datepicker').data('daterangepicker').setEndDate(moment().endOf('year'));
                $('.input-daterange-datepicker').data('daterangepicker').minDate = moment().startOf('year');
                $('.input-daterange-datepicker').data('daterangepicker').maxDate = moment().endOf('year');

                // Reset hidden inputs for export
                $('#export-year-pdf, #export-year-excel, #export-start-date-pdf, #export-start-date-excel, #export-end-date-pdf, #export-end-date-excel').val('');
            }

            // Reload the tables with the updated filter data
            pemasukanTables(filterData);
            pengeluaranTables(filterData);
        });

        // Update input after selecting date range
        $('.input-daterange-datepicker').on('apply.daterangepicker', function(ev, picker) {
            // Update input value
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));

            // Update filter data
            filterData.start_created_at = picker.startDate.format('YYYY-MM-DD');
            filterData.end_created_at = picker.endDate.format('YYYY-MM-DD');

            // Update hidden inputs for export
            $('#export-start-date-pdf, #export-start-date-excel').val(filterData.start_created_at);
            $('#export-end-date-pdf, #export-end-date-excel').val(filterData.end_created_at);

            // Reload the tables
            pemasukanTables(filterData);
            pengeluaranTables(filterData);
        });

        // Load tables on page load
        pemasukanTables(filterData);
        pengeluaranTables(filterData);
    });

    function pemasukanTables(filter) {
        $('#pemasukanTables').DataTable({
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
                url: $('#table-url-pemasukan').val(),
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
                { data: 'date' },
                { data: 'jumlah' }
            ],
            footerCallback: function(row, data, start, end, display) {
                filter.total_data = true
                totalPemasukan(filter)
            }
        });
    }
    function totalPemasukan(filter){
        $.ajax({
            url: $('#table-url-pemasukan').val(),
            method: 'GET',
            data: filter,
            success: function(data) {
                console.log(data);
                var jumlah = data.replace(/Rp/g, '').replace(/\./g, '').trim();
                $('#total-pemasukan').html('Rp' + Number(jumlah).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 }));
            },
            error: function(xhr, error, thrown) {
                console.error('AJAX Error:', xhr.responseText);
            }
        });
    }

    function totalPengeluaran(filter){
        $.ajax({
            url: $('#table-url-pengeluaran').val(),
            method: 'GET',
            data: filter,
            success: function(data) {
                console.log(data);
                var jumlah = data.replace(/Rp/g, '').replace(/\./g, '').trim();
                $('#total-pengeluaran').html('Rp' + Number(jumlah).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 }));
            },
            error: function(xhr, error, thrown) {
                console.error('AJAX Error:', xhr.responseText);
            }
        });
    }

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
                { data: 'jumlah' },
                { data: 'tanggal' }
            ],
                footerCallback: function(row, data, start, end, display) {
                 filter.total_data = true
                totalPengeluaran(filter)
            }
        });
    }
</script>

</body>
</html>
