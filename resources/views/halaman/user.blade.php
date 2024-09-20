<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash| {{auth()->user()->level}} | User</title>

      <style>
        .dataTables_wrapper .bendaharaTable_length .bootstrap-select .dropdown-toggle {
            font-size: 2rem; /* Ukuran font yang lebih besar */
            padding: 1rem 2rem; /* Padding yang lebih besar di sekitar teks */
            border-radius: 4px; /* Radius sudut untuk dropdown */
            border: 1px solid #ccc; /* Border untuk dropdown */
        }

        /* Jika dropdown memiliki elemen select yang di-custom */
        .dataTables_wrapper .BendaharaTable_length .bootstrap-select .dropdown-menu {
            font-size: 1.5rem; /* Ukuran font di dalam menu dropdown */
            padding: 1rem; /* Padding dalam menu dropdown */
        }
    </style>

    
    
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
                        <p class="mb-0">Data User</p>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">User</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Data User</h4>
                            <div class="text-right">
                        
                    <a href="/add_user" class="btn btn-success" title="Add">
                        <i class="fa fa-plus"></i>
                    </a>
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
                          
                            <div class="table-responsive">
                                <table id="bendaharaTable" class="table table-responsive-md">
                                    <thead>
                                        <tr>
                                            <th style="width:50px;">
                                                <strong>No</strong>
                                            </th>
                                            <th><strong>Name</strong></th>
                                            <th><strong>Email</strong></th>
                                         
                                            <th><strong>Gender</strong></th>
                                            <th><strong>Address</strong></th>
                                            <th><strong>Role</strong></th>
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

    <script>
        $(document).ready(function() {
            $('.delete-btn').click(function() {
                var id = $(this).data('id');
                // Tampilkan sweet alert ketika tombol hapus diklik
                Swal.fire({
                    title: 'Apakah anda yakin hapus data ini?',
                    text: "Data akan dihapus secara permanen",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, hapus data!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika pengguna mengonfirmasi penghapusan, kirim formulir hapus
                        $('#deleteForm_' + id).submit();
                        // Tampilkan alert ketika data berhasil dihapus
                        Swal.fire(
                            'Data dihapus!',
                            'Data berhasil dihapus',
                            'success'
                        )
                    }
                });
            });
        });
    </script>

<input type="hidden" id="table-url" value="{{ route('users') }}">
<script src="{{ asset('main.js') }}"></script>
<script src="https://cdn.datatables.net/v/bs5/dt-2.1.3/datatables.min.js"></script>



!-- Modal HTML -->
    <div class="modal fade" id="adminDetailModal" tabindex="-1" role="dialog" aria-labelledby="adminDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adminDetailModalLabel">Detail User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <strong>Nama:</strong><br />
                            <strong>Email :</strong><br />
                            <strong>Kelamin :</strong><br />
                            <strong>Alamat :</strong><br />
                            {{-- <strong>Kategori:</strong><br /> --}}

                        </div>
                        <div class="col-sm-8">
                            <div id="name"></div>
                            <div id="email"></div>
                            <div id="kelamin"></div>
                            <div id="alamat"></div>
                            
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
            $('#bendaharaTable').DataTable();
            
            $('#adminDetailModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Tombol yang memicu modal
                var url = button.data('url'); // Ambil info dari atribut data-*
                
                var modal = $(this);
                
                // Kosongkan konten modal sebelum memuat data baru
                // modal.find('#id_data').text('');
                modal.find('#name').text('');
                modal.find('#email').text('');
                modal.find('#kelamin').text('');
                modal.find('#alamat').text('');
           // Kosongkan kategori

                
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(data) {
                        // Isi modal dengan data baru
                        // modal.find('#id_data').text(data.id_data || 'N/A');
                        modal.find('#name').text(data.name || 'N/A');
                        modal.find('#email').text(data.email || 'N/A');
                        modal.find('#kelamin').text(data.kelamin || 'N/A');
                        modal.find('#alamat').text(data.alamat || 'N/A');

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