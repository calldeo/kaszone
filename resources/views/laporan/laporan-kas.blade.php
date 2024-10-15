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
                  
                </div>
            </div>

            <!-- Filter Section -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                   <div class="card-header">
                            <h4 class="card-title">Data Pemasukan</h4>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center"> <!-- Membungkus filter dalam d-flex -->
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
                <th colspan="5"style="text-align: left; font-size: 1.25em; font-weight: bold;"><strong>Total Jumlah:</strong></th>
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
            
        </div>
    </div>

    @include('template.scripts')

    <input type="hidden" id="table-url-pemasukan" value="{{ route('income') }}">

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
                    pemasukanTables(filterData);
                 
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
                pemasukanTables(filterData);
           
            });

            // Load tables on page load
            pemasukanTables(filterData);
         
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
            var totalJumlah = 0;

            data.forEach(function(item) {
                // Hapus 'Rp' dan ganti '.' dengan kosong agar bisa diparsing
                var jumlah = item.jumlah.replace(/Rp/g, '').replace(/\./g, '').trim();
                totalJumlah += parseFloat(jumlah) || 0;
            });

            // Format total jumlah ke format Rupiah dengan dua desimal (,00)
            var formattedTotal = totalJumlah.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });

            // Tampilkan total dengan format Rupiah di elemen '#total-pemasukan'
            $('#total-pemasukan').html('Rp' + formattedTotal);
        }
    });
}

</script>


</body>
</html>
