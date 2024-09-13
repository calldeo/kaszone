<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | {{auth()->user()->level}} | Pengeluaran</title>
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
                            {{-- <link href="https://cdn.datatables.net/v/bs5/dt-2.1.3/datatables.min.css" rel="stylesheet"> --}}
                            <div class="table-responsive">
                                <table id="pengeluaranTable" class="table table-responsive-md">
                                    <thead>
                                        <tr>
                                            <th style="width:50px;">
                                                <strong>No</strong>
                                            </th>
                                            <th><strong>Nama</strong></th>
                                            <th><strong>Deskripsi</strong></th>
                                            <th><strong>Kategori</strong></th>
                                            <th><strong>Tanggal</strong></th> 
                                            <th><strong>Jumlah Satuan</strong></th>
                                            <th><strong>Nominal(Rp)</strong></th>  
                                            <th><strong>Lain - lain</strong></th>
                                            <th><strong>Image</strong></th> 
                                            <th><strong>Total(Rp)</strong></th>
                                            {{-- <th><strong>Status</strong></th> --}}
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



<input type="hidden" id="table-url" value="{{ route('production') }}">
<script src="{{ asset('main.js') }}"></script>
<script src="https://cdn.datatables.net/v/bs5/dt-2.1.3/datatables.min.js"></script>
 

  

  
    <script>

        $(document).ready(function() {
    // Tampilkan modal dan gambar
    $('#dataTable').on('click', '.preview-image', function() {
        var imageUrl = $(this).data('image-url');
        $('#modalImage').attr('src', imageUrl);
        $('#imageModal').modal('show');
    });

    // Menutup modal saat klik di luar
    $(window).on('click', function(event) {
        if ($(event.target).is('#imageModal')) {
            $('#imageModal').modal('hide');
        }
    });
});

        </script>

</body>

</html>