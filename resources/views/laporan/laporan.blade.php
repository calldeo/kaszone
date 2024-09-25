<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | {{ auth()->user()->level }} | Laporan</title>
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
                    <div class="welcome-text">
                        <h4>Hi, Welcome Back!</h4>
                        <p class="mb-0">Data Laporan</p>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Laporan</a></li>
                    </ol>
                </div>
            </div>

          

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
                                 <div class="text-right mt-4">
                                    @hasrole('Admin|Bendahara') 
                                    <form method="GET" action="{{ route('export.laporan') }}" id="export-pdf-form">
                                        <input type="hidden" name="year" id="export-year" value="{{ old('year') }}" required />
                                        <button type="submit" class="btn btn-info ml-"><i class="fa fa-print"></i>
                                         </button>
                                    </form>
                                    
                                    <a href="{{ url('/export-pemasukan') }}" class="btn btn-success" title="Export to Excel">
                                        <i class="fa fa-file"></i>
                                    </a>
                                    @endhasrole
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
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
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <strong>Total Jumlah Pemasukan: Rp{{ number_format($totalJumlah, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Data Pengeluaran</h4>
                            <div class="text-right">
                                @hasrole('Admin|Bendahara') 
                                <a href="/cetak-pengeluaran" target="_blank" class="btn btn-info ml-1" title="Print Report">
                                    <i class="fa fa-print"></i>
                                </a>
                                <a href="{{ url('/export-pengeluaran') }}" class="btn btn-success" title="Export to Excel">
                                    <i class="fa fa-file"></i>
                                </a>
                                @endhasrole
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="pengeluaranTables" class="table table-responsive-md">
                                    <thead>
                                        <tr>
                                            <th style="width:50px;"><strong>No</strong></th>
                                            <th><strong>Nama</strong></th>
                                            <th><strong>Deskripsi</strong></th>
                                            <th><strong>Kategori</strong></th>
                                            <th><strong>Tanggal</strong></th>
                                            <th><strong>Jumlah Satuan</strong></th>
                                            <th><strong>Nominal(Rp)</strong></th>  
                                            <th><strong>Lain - lain</strong></th>
                                            <th><strong>Image</strong></th> 
                                            <th><strong>Total(Rp)</strong></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <strong>Total Jumlah Pengeluaran: Rp{{ number_format($totalJumlah1, 0, ',', '.') }}</strong>
                        </div>
                        @php
                        $selisih = $totalJumlah - $totalJumlah1;
                        @endphp
                        <div class="d-flex right-content-end mt-3">
                            <strong>Selisih Pemasukan dan Pengeluaran: Rp{{ number_format($selisih, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content body end -->

    <!-- Scripts -->
    @include('template.scripts')

    <input type="hidden" id="table-url-pemasukan" value="{{ route('income') }}">
    <input type="hidden" id="table-url-pengeluaran" value="{{ route('production') }}">
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
    
            
            $('.input-daterange-datepicker').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
    
                    
                $('#filter-year').on('change', function() {
                var selectedYear = $(this).val();
                $('#export-year').val(selectedYear);
                if (selectedYear !== "") {
                $('.input-daterange-datepicker').prop('disabled', false);
                filterData.year = selectedYear;

                filterData.start_created_at = selectedYear + '-01-01';
                filterData.end_created_at = selectedYear + '-12-31';

                $('.input-daterange-datepicker').data('daterangepicker').setStartDate(filterData.start_created_at);
                $('.input-daterange-datepicker').data('daterangepicker').setEndDate(filterData.end_created_at);
                
                $('.input-daterange-datepicker').trigger('apply.daterangepicker', {
                    startDate: moment(filterData.start_created_at),
                    endDate: moment(filterData.end_created_at)
                });
            } else {
                $('.input-daterange-datepicker').prop('disabled', true);
                filterData.year = null;
                filterData.start_created_at = null;
                filterData.end_created_at = null;
            }
        });

            
            
            $('.input-daterange-datepicker').on('apply.daterangepicker', function(ev, picker) {
                if (filterData.year === null) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian!',
                        text: 'Harap pilih tahun terlebih dahulu.'
                    });
                    return;
                }
    
                filterData.start_created_at = picker.startDate.format('YYYY-MM-DD');
                filterData.end_created_at = picker.endDate.format('YYYY-MM-DD');
    
                
                 pemasukanTables(filterData);
                  pengeluaranTables(filterData);

            });

            pemasukanTables(filterData);
            pengeluaranTables(filterData);
        });
    
        // Fungsi untuk memuat tabel pemasukan
        function pemasukanTables(filter) {
            $('#pemasukanTables').DataTable({
                processing: true,
                serverSide: true,
                destroy:true,
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
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'description', name: 'description' },
                    { data: 'category', name: 'category' },
                    { data: 'date', name: 'date' },
                    { data: 'jumlah', name: 'jumlah' }
                ],
                "lengthMenu": [5, 10, 25, 50, 100],
            });
        }
    
        // Fungsi untuk memuat tabel pengeluaran
        function pengeluaranTables(filter) {
            $('#pengeluaranTables').DataTable({
                processing: true,
                serverSide: true,
                destroy:true,
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
                    { data: 'tanggal', name: 'tanggal' },
                    { data: 'jumlah_satuan', name: 'jumlah_satuan' },
                    { data: 'nominal', name: 'nominal' },
                    { data: 'dll', name: 'dll' },
                    { data: 'image', name: 'image' },
                    { data: 'jumlah', name: 'jumlah' },
                ],
                "lengthMenu": [5, 10, 25, 50, 100],
            });
        }
    </script>
    

</body>

</html>
