<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | {{ auth()->user()->level }} | Pengeluaran</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
                        <p class="mb-0">Data Pengeluaran</p>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Pengeluaran</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Data Pengeluaran</h4>
                            <div class="text-right">
                            <div class="d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center">
        <!-- Filter Tanggal -->
        <div class="example mr-3">
            <p class="mb-1">Filter Tanggal</p>
            <input class="form-control input-daterange-datepicker" type="text" name="daterange" placeholder="Masukkan Tanggal" disabled>
        </div>
    </div>

    <div class="d-flex align-items-center">
        @hasrole('Admin|Bendahara') 
        <!-- Button to Add Pengeluaran -->
        <a href="/add_pengeluaran" class="btn btn-warning mr-2" title="Add">
            <i class="fa fa-plus"></i>
        </a>
        @endhasrole

        @hasrole('Admin|Bendahara') 
        <!-- Print Report Button -->
        <a href="/cetakpgl" target="_blank" class="btn btn-info mr-2" title="Print Report">
            <i class="fa fa-print"></i>
        </a>
        @endhasrole

        @hasrole('Admin|Bendahara') 
        <!-- Export to Excel Button -->
        <a href="{{ url('/export-pengeluaran') }}" class="btn btn-success mr-2" title="Export to Excel">
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
                        </div>
                                                <!-- Modal untuk Import Data -->
                        <div id="importModal" class="modal fade" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Import Data Pengeluaran</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <a href="{{ route('download-template') }}" class="btn btn-success">Download Template</a>

                                    <div class="modal-body">
                                        <form action="{{ route('import-pengeluaran') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="file">Pilih File Excel</label>
                                                <input type="file" class="form-control" name="file[]" multiple required>
                                                <small class="form-text text-muted">File yang diizinkan: .xls, .xlsx</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="description">Deskripsi (optional)</label>
                                                <textarea class="form-control" name="description" rows="3"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Import</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="card-body">
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <strong>Error!</strong> {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span><i class="fa fa-times"></i></span></button>
                                </div>
                            @endif

                            @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <strong>Success!</strong> {{ session('success') }}
                                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
                            </div>
                            @endif
                            @if(session('update_success'))
                            <div class="alert alert-warning alert-dismissible fade show">
                                <strong>Success!</strong> {{ session('update_success') }}
                                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
                            </div>
                            @endif

                            <div class="table-responsive">
                                <table id="pengeluaranTable" class="table table-responsive-md">
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
                                            <th><strong>Option</strong></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            {{-- {{ $users->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal HTML -->
    <div id="imageModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Image Preview</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="" alt="Image Preview" style="width: 100%; height: auto;" />
                </div>
            </div>
        </div>
    </div>

    <!-- Footer start -->
    <div class="footer">
        <div class="copyright">
            <p>Copyright © Designed &amp; Developed by <a href="/home" target="_blank">SYNC</a> 2024</p>
        </div>
    </div>
    <!-- Footer end -->

    <!-- Scripts -->
    @include('template.scripts')

    <input type="hidden" id="table-url" value="{{ route('production') }}">
    <script src="{{ asset('main.js') }}"></script>
    {{-- <script src="https://cdn.datatables.net/v/bs5/dt-2.1.3/datatables.min.js"></script> --}}
    {{-- <script src="{{asset('dash/vendor/datatables/js/jquery.dataTables.min.js')}}"></script> --}}

    <script>
        var filterDataPengeluaran = {
            start_created_at: null,
            end_created_at: null
        };

        $(document).ready(function() {
            // Inisialisasi tabel
            pengeluaranTable(filterDataPengeluaran);
            $('.input-daterange-datepicker').val('');

            // Inisialisasi daterangepicker
            $('.input-daterange-datepicker').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'MM/DD/YYYY'
                }
            }, function(start, end, label) {
                filterDataPengeluaran.start_created_at = start.format('YYYY-MM-DD 00:00:00');
                filterDataPengeluaran.end_created_at = end.format('YYYY-MM-DD 23:59:59');
                pengeluaranTable(filterDataPengeluaran);
            });

            $('.input-daterange-datepicker').on('apply.daterangepicker', function(ev, picker) {
                var startDate = picker.startDate.format('YYYY-MM-DD');
                var endDate = picker.endDate.format('YYYY-MM-DD');

                console.log('Selected date range for pengeluaran: ' + startDate + ' to ' + endDate);
            });

            $('.input-daterange-datepicker').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                filterDataPengeluaran.start_created_at = null;
                filterDataPengeluaran.end_created_at = null;
                pengeluaranTable(filterDataPengeluaran);
            });
        });

        function pengeluaranTable(filterDataPengeluaran) {
            tablePengeluaran = $('#pengeluaranTable').DataTable({
                ordering: true,
                destroy: true,
                serverSide: true,
                processing: true,
                language: {
                    paginate: {
                    next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>' 
                    }
                },
                ajax: {
                    url: $('#table-url').val(),
                    type: 'GET',
                    dataType: 'json',
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('AJAX error:', textStatus, errorThrown);
                        alert('Terjadi kesalahan saat memuat data. Silakan coba lagi nanti.');
                    },
                    data: filterDataPengeluaran // Mengirim filterDataPengeluaran
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '10px', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'description', name: 'description' },
                    { data: 'category', name: 'category' },
                    { data: 'tanggal', name: 'tanggal' },
                    { data: 'jumlah_satuan', name: 'jumlah_satuan' },
                    { data: 'nominal', name: 'nominal' },
                    { data: 'dll', name: 'dll' },
                    { data: 'image', name: 'image' },
                    { data: 'jumlah', name: 'jumlah' },
                    { data: 'opsi', name: 'opsi', orderable: false, searchable: false }
                ],
                columnDefs: [
                    {
                        "targets": "_all",
                        "defaultContent": '<div class="align-middle text-center">-</div>'
                    },
                ]
            });
        }
    </script>
</body>

</html>
