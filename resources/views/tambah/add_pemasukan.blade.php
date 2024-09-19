<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | {{auth()->user()->level}} | Add Pemasukan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> --}}
    
</head>

<body>
    <!--*******
        Preloader start
    ********-->
    @include('template.topbarr')
    <!--************
        Header end ti-comment-alt
    *************-->

    <!--************
        Sidebar start
    *************-->
    @include('template.sidebarr')
    <!--************
        Sidebar end
    *************-->

    <!--************
        Content body start
    *************-->
    <div class="content-body">
        <div class="container-fluid">
            <!-- row -->
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Hi, Welcome Back!</h4>
                        <p class="mb-0">Add Pemasukan</p>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Form</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Add Pemasukan</a></li>
                    </ol>
                </div>
            </div>

            <!-- Error Message -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
                </div>
            @endif

            <!-- Add Pemasukan Form -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Add Pemasukan</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                <form class="form-valide-with-icon" action="/pemasukan/store" method="post">
                                   @csrf
                                <div class="form-group">
                                    <label class="text-label">Nama Pemasukan*</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                        </div>
                                        <input type="text" class="form-control" name="name" placeholder="Enter name.." value="{{ old('name') }}" required>
                                    </div>
                                    @error('name')
                                    <span class="mt-2 text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="text-label">Deskripsi</label>
                                    <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-book"></i> <!-- Ikon buku -->
                                                </span>
                                            </div>
                             <textarea class="form-control" name="description" placeholder="Enter description..">{{ old('description') }}</textarea>

                                        </div>
                                </div>

                                <div class="form-group">
                                    <label class="text-label">Date *</label>
                                     <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-calendar"></i> <!-- Ikon kalender -->
                                                </span>
                                            </div>
                                             <input type="date" class="form-control" name="date" value="{{ old('date') }}" required>
                                    @error('date')
                                    <span class="mt-2 text-danger">{{ $message }}</span>
                                    @enderror
                                   
                                </div>

                            <div class="form-group">
    <label class="text-label">Jumlah (Rp)*</label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="fas fa-dollar-sign"></i> <!-- Ikon dolar -->
            </span>
        </div>
        <input type="number" step="0.01" min="0" class="form-control" name="jumlah" placeholder="Enter amount.." value="{{ old('jumlah') }}" required>
    </div>
    @error('jumlah')
    <span class="mt-2 text-danger">{{ $message }}</span>
    @enderror
</div>


                                <div class="form-group">
                                    <label class="text-label">Category *</label>
                                     <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-list"></i> <!-- Ikon daftar -->
                                                </span>
                                            </div>
                                            {{-- <option value="">--PILIH KATEGORI--</option> --}}
                                          <select class="form-control default-select" id="category" name="category_id" required>
                                        
                                        {{-- @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach --}}
                                    </select>
                                    @error('category_id')
                                    <span class="mt-2 text-danger">{{ $message }}</span>
                                    @enderror
                                        </div>
                                    
                                </div>

                                    <button type="button" class="btn btn-danger btn-cancel" onclick="window.location.href='/pemasukan'">Cancel</button>

                                    <button type="submit" class="btn mr-2 btn-primary btn-submit">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--************
        Content body end
    *************-->

    <!--************
        Footer start
    *************-->
    <div class="footer">
        <div class="copyright">
            <p>Copyright © Designed &amp; Developed by <a href="/home" target="_blank">SYNC</a> 2024</p>
        </div>
    </div>
    <!--************
        Footer end
    *************-->

    <!--************
        Scripts
    *************-->
    <!-- Required vendors -->
    @include('template.scripts')
</body>
<script>
    $(document).ready(function() {
        
        getCategories();

        function getCategories() {
            $.ajax({
                url: '/get-categories/1',
                method: 'GET',
                success: function(data) {
                    var $dropdown = $('#category');
                    $dropdown.empty(); 

                    $dropdown.append($('<option>', {
                        value: '',
                        text: 'PILIH KATEGORI'
                    }));

                    
                    $.each(data, function(index, item) {
                        $dropdown.append($('<option>', {
                            value: item.id,
                            text: item.name
                        }));
                    });

                 
                    // $dropdown.select2({
                    //     placeholder: 'PILIH KATEGORI',
                    //     allowClear: true,
                    //     width: '100%', 
                    //     minimumResultsForSearch: 0 
                    // });
                },
                error: function(xhr) {
                    console.error('Error fetching options:', xhr);
                }
            });
        }
    });
</script>




{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

</html>
