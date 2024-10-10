<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | {{ auth()->user()->level }} | Pemasukan</title>
</head>
<body>
    <!-- Preloader start -->
    @include('template.topbarr')
    <!-- Header end -->

    <!-- Sidebar start -->
    @include('template.sidebarr')
    <!-- Sidebar end -->

    <!-- Content body start -->
    <div class="content-body">
        <div class="container-fluid">
            <!-- Add Project -->
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Pemasukan</a></li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Data Pemasukan</h4>
                            {{-- <h5 id="total-jumlah" class="mt-2">Total Jumlah: Rp <span id="total-jumlah-value">0</span></h5> --}}

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
                                    <a href="/add_pemasukan" class="btn btn-warning mr-2" title="Add">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                    @endhasrole
                                    @hasrole('Admin|Bendahara|Reader') 
                                    <form method="GET" action="{{ route('export.pemasukan') }}" id="export-pdf-form" class="mr-2">
                                        <input type="hidden" name="year" id="export-year" value="{{ old('year') }}" />
                                        <input type="hidden" name="start_date" id="export-start-date" value="{{ old('start_date') }}" />
                                        <input type="hidden" name="end_date" id="export-end-date" value="{{ old('end_date') }}" />
                                        <button type="submit" title="Export PDF" class="btn btn-info"><i class="fa fa-print"></i></button>
                                    </form>
                                    
                                    <form method="POST" action="{{ route('export.pemasukan.excel') }}" id="export-excel-form" class="mr-2">
                                        @csrf
                                        <input type="hidden" name="year" id="export-year-excel" value="{{ old('year') }}" />
                                        <input type="hidden" name="start_date" id="export-start-date-excel" value="{{ old('start_date') }}" />
                                        <input type="hidden" name="end_date" id="export-end-date-excel" value="{{ old('end_date') }}" />
                                        <button type="submit" title="Export Excel" class="btn btn-success"><i class="fa fa-file-excel"></i></button>
                                    </form>
                                    @endhasrole

                                    @hasrole('Admin|Bendahara')
                                    <button type="button" class="btn btn-primary" title="Import Data" data-toggle="modal" data-target="#importModal">
                                        <i class="fa fa-file-import"></i> 
                                    </button>
                                    @endhasrole
                                </div>

                                @hasrole('Admin|Bendahara') 
                                <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="importModalLabel">Import Data Pemasukan</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('import-pemasukan') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="file">Pilih File Excel</label>
                                                        <input type="file" class="dropify" id="file" name="file" required accept=".xls,.xlsx">
                                                      <a href="{{ route('download.template.pemasukan') }}" class="btn btn-secondary mr-2" title="Download Template Excel">
                                                        <i class="fa fa-download"></i> Download Template
                                                    </a>

                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Import</button>
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
                                <strong>Success!</strong> {{ session('success') }}
                                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
                            </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <strong>Error!</strong> {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span><i class="fa fa-times"></i></span></button>
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
                                        <td id="total-jumlah-value" style="text-align: left; font-size: 1.25em; font-weight: bold;">0</td>
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

    <!-- Detail Modal -->
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

    <!-- Scripts -->
    @include('template.scripts')

    <input type="hidden" id="table-url-pemasukan" value="{{ route('income') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('main.js') }}"></script>

    <script>
        var filterData = {
            year: null,
            start_created_at: null,
            end_created_at: null
        };

        $(document).ready(function() {
            $('.input-daterange-datepicker').prop('disabled', true);

            // DateRangePicker setup
            $('.input-daterange-datepicker').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            // Event listener for year filter change
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
                    
                    // Update hidden inputs for export
                    $('#export-start-date').val(filterData.start_created_at);
                    $('#export-end-date').val(filterData.end_created_at);
                    $('#export-start-date-excel').val(filterData.start_created_at);
                    $('#export-end-date-excel').val(filterData.end_created_at);
                } else {
                    $('.input-daterange-datepicker').prop('disabled', true);
                    filterData.year = null;
                    filterData.start_created_at = null;
                    filterData.end_created_at = null;
                    
                    // Clear hidden inputs for export
                    $('#export-start-date').val('');
                    $('#export-end-date').val('');
                    $('#export-start-date-excel').val('');
                    $('#export-end-date-excel').val('');
                }

                reloadTable(); // Reload table with new filters
            });

            // Event listener for date range change
            $('.input-daterange-datepicker').on('apply.daterangepicker', function(ev, picker) {
                filterData.start_created_at = picker.startDate.format('YYYY-MM-DD');
                filterData.end_created_at = picker.endDate.format('YYYY-MM-DD');
                
                // Update hidden inputs for export
                $('#export-start-date').val(filterData.start_created_at);
                $('#export-end-date').val(filterData.end_created_at);
                $('#export-start-date-excel').val(filterData.start_created_at);
                $('#export-end-date-excel').val(filterData.end_created_at);
                
                reloadTable(); // Reload table with new date filters
            });

            // Initialize the DataTable
            $('#pemasukanTables').DataTable({
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
                        console.error('AJAX Error:', xhr.responseText); // Log AJAX error response
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
   footerCallback: function(row, data, start, end, display) {
    var totalJumlah = 0;
    data.forEach(function(item) {
        var jumlah = item.jumlah.replace(/Rp/g, '').replace(/\./g, '').trim(); // Hapus 'Rp ' dan '.' untuk parsing
        totalJumlah += parseFloat(jumlah) || 0;
    });
    // Format total jumlah
    $('#total-jumlah-value').text('Rp ' + totalJumlah.toLocaleString('id-ID')); // Format dengan locale Indonesia
}


            });
        });

        function reloadTable() {
            $('#pemasukanTables').DataTable().ajax.reload(null, false);
        }

    </script>


    <script>
        $(document).ready(function() {
            $('#kategoriTable').DataTable();
            
            $('#adminDetailModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Tombol yang memicu modal
                var url = button.data('url'); // Ambil info dari atribut data-*
                
                var modal = $(this);
                
                // Kosongkan konten modal sebelum memuat data baru
                modal.find('#id_data').text('');
                modal.find('#name').text('');
                modal.find('#description').text('');
                modal.find('#date').text('');
                modal.find('#jumlah').text('');
            modal.find('#category').text(''); // Kosongkan kategori

                
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(data) {
                        // Isi modal dengan data baru
                        modal.find('#id_data').text(data.id_data || 'N/A');
                        modal.find('#name').text(data.name || 'N/A');
                        modal.find('#description').text(data.description || 'N/A');
                        modal.find('#date').text(data.date || 'N/A');
                        modal.find('#jumlah').text(data.jumlah || 'N/A');
                    modal.find('#category').text(data.category_name || 'N/A');

                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText); // Tampilkan pesan kesalahan di konsol
                        modal.find('.modal-body').html('Terjadi kesalahan saat memuat detail');
                    }
                });
            });
        });
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