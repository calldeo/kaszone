<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | {{auth()->user()->level}} | kategori</title>
    
</head>

<body>


    @include('template.topbarr')

    @include('template.sidebarr')
   

    
    <div class="content-body" style="margin-top: -60px;"> <!-- Atur margin-top untuk menggeser konten ke atas -->
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Kategori</a></li>
                    </ol>
                </div>
            </div>
           

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Data Kategori</h4>
                            <div class="text-right">
                    
                           <a href="/add" class="btn btn-warning ml-0" title="Add">
                        <i class="fa fa-plus"></i>
                    </a>
                    <a href="/export-kategori" target="blank" class="btn btn-info ml-2" title="Print Report">
                                    <i class="fa fa-print"></i> 
                               </a>
                      <button type="button" class="btn btn-success ml-2" title="Import" data-toggle="modal" data-target="#importModal">
            <i class="fa fa-upload"></i> 
        </button>
        
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Data Katagori</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <form action="{{ route('import-kategori') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group" style="text-align: left;">
                                <label for="file">Pilih File Excel</label>
                                <input type="file" class="dropify" id="file" name="file" required accept=".xls,.xlsx">
                            </div>
                            <div style="text-align: left; margin-top: 10px;">
                                <a href="{{ route('download-template-kategori') }}">
                                    Download Template Excel
                                </a>
                            </div>
                            <div style="text-align: left; margin-top: 10px;">
                                <button type="submit" class="btn btn-primary">Import</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        
        
                   
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
                                <table id="kategoriTable" class="table table-responsive-md">
                                    <thead>
                                        <tr>
                                             <th style="width:50px;">
                                                <strong>No</strong>
                                            </th>
                                            <th><strong>Nama Kategori</strong></th>
                                            <th><strong>Jenis Kategori</strong></th>
                                            <th><strong>Deskripsi</strong></th>
                                            <th><strong>Opsi</strong></th>
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

    <div class="footer">
        <div class="copyright">
            <p>Copyright © Designed &amp; Developed by <a href="/home" target="_blank">SYNC</a> 2024</p>
        </div>
    </div>

    @include('template.scripts')
    

  

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        $(document).ready(function() {
            $('.delete-btn').click(function() {
                var id = $(this).data('id');
                
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
                        
                        $('#deleteForm_' + id).submit();
                        
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

<input type="hidden" id="table-url" value="{{ route('kategoris') }}">
<script src="{{ asset('main.js') }}"></script>

<script>

$(document).ready(function() {
    $('#adminDetailModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); 
        var url = button.data('url'); 
        var modal = $(this);
        $.ajax({
            url: url,
            method: 'GET',
            success: function(data) {
                modal.find('#id').text(data.id);
                modal.find('#name').text(data.name);
                modal.find('#description').text(data.description);
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText); 
                modal.find('.modal-body').html('Terjadi kesalahan saat memuat detail');
            }
        });
    });
});


    </script>
    
    


<div class="modal fade" id="adminDetailModal" tabindex="-1" role="dialog" aria-labelledby="adminDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adminDetailModalLabel">Detail Kategori</h5>
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
                    </div>
                    <div class="col-sm-8">
                        <div id="id"></div>
                        <div id="name"></div>
                        <div id="description"></div>
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
    $(document).ready(function(){
        
        $('.dropify').dropify();

        $('.dropify-wrapper .dropify-message p').css('font-size', '20px'); 
    });
</script>


</body>

</html>
