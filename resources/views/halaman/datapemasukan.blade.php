<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | {{auth()->user()->level}} | Pemasukan</title>

    
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
                        <p class="mb-0">Data Pemasukan</p>
                    </div>
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
                            <div class="text-right">
                           <div class="text-right">
                            <div class="example">
                                <p class="mb-1">Filter Tanggal</p>
                                <input class="form-control input-daterange-datepicker" type="text" name="daterange" placeholder="Masukkan Tanggal" >
                            </div>
                            
    {{-- Button untuk menambahkan data --}}
    @hasrole('Admin|Bendahara') {{-- Hanya role admin atau bendahara yang bisa melihat tombol ini --}}
    <a href="/add_pemasukan" class="btn btn-warning" title="Add">
        <i class="fa fa-plus"></i>
    </a>
    @endhasrole
    @hasrole('Admin|Bendahara') 
            <a href="/cetak-pemasukan" target="_blank" class="btn btn-info ml-1" title="Print Report">
                <i class="fa fa-print"></i>
            </a>
@endhasrole
@hasrole('Admin|Bendahara') 
                <a href="{{ url('/export-pemasukan') }}" class="btn btn-success " title="Export to Excel">
                <i class="fa fa-file"></i>
                </a>
@endhasrole


    @hasrole('Admin|Bendahara') 
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importModal">
        <i class="fa fa-upload"></i> 
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
                        <input type="file" class="form-control-file" id="file" name="file" required>
                        <div style="text-align: left;">
                            <a href="{{ route('download-template-pemasukan') }}">Download Template Excel</a>
                        </div>
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
                              @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <strong>Error!</strong> {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span><i class="fa fa-times"></i></span></button>
                                </div>
                            @endif
                          
                            <div class="table-responsive">
                                <table id="pemasukanTable" class="table table-responsive-md">
                                    <thead>
                                        <tr>
                                         <th style="width:50px;">
                                                <strong>No</strong>
                                            </th>
                                            <th><strong>Nama</strong></th>
                                            <th><strong>Deksripsi</strong></th>
                                            <th><strong>Kategori</strong></th>
                                            <th><strong>Tanggal</strong></th>
                                            <th><strong>Tanggal dibuat</strong></th>   
                                            <th><strong>Jumlah(Rp)</strong></th>
                                            <th><strong>Option</strong></th>
                                        </tr>
                                    </thead>
                                 
                                </table>
                            </div>
                        </div>
                               <div class="d-flex justify-content-end">
                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content body end -->

    <!-- Footer start -->
    <div class="footer">
        <div class="copyright">
            <p>Copyright © Designed &amp; Developed by <a href="/home" target="_blank">SYNC</a> 2024</p>
        </div>
    </div>
    <!-- Footer end -->

    <!-- Main wrapper end -->

    <!-- Scripts -->
    <!-- Required vendors -->
    @include('template.scripts')

  
 

    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <input type="hidden" id="table-url" value="{{ route('income') }}">
    <script src="{{ asset('main.js') }}"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-2.1.3/datatables.min.js"></script>    
    
    

    
    <!-- Modal HTML -->
    <div class="modal fade" id="adminDetailModal" tabindex="-1" role="dialog" aria-labelledby="adminDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adminDetailModalLabel">Detail Pengeluaran</h5>
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


</body>

</html>