<!DOCTYPE html>

<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | {{ auth()->user()->level }} | Edit</title>
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
            <!-- Page Titles -->
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                 
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Kategori</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit</a></li>
                    </ol>
                </div>
            </div>

            <!-- Form Edit Kategori -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Data Kategori</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                <form action="/kategori/{{ $category->id }}" method="POST" enctype="multipart/form-data">
                                    @method('PUT')
                                    @csrf

                                    <!-- Name Field -->
                                    <div class="form-group">
                                        <label class="text-label">Nama *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                            </div>
                                            <input type="text" class="form-control" name="name" value="{{ old('name', $category->name) }}" placeholder="Enter a name.." required>
                                        </div>
                                        @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- Description Field -->
                                    <div class="form-group">
                                        <label class="text-label">Deskripsi *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"> <i class="fa fa-file-text"></i> </span>
                                            </div>
                                            <input type="text" class="form-control" name="description" value="{{ old('description', $category->description) }}" placeholder="Enter a description.." required>
                                        </div>
                                        @error('description')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- Jenis Kategori Field -->
                                    <div class="form-group">
                                        <label class="text-label">Jenis Kategori *</label>
                                        <select class="form-control default-select" name="jenis_kategori" required>
                                            <option value="">-- Jenis Kategori --</option>
                                            <option value="1" {{ old('jenis_kategori', $category->jenis_kategori) == '1' ? 'selected' : '' }}>Pemasukan</option>
                                            <option value="2" {{ old('jenis_kategori', $category->jenis_kategori) == '2' ? 'selected' : '' }}>Pengeluaran</option>
                                        </select>
                                        @error('jenis_kategori')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- Buttons -->
                                    <button type="button" class="btn btn-danger btn-submit" onclick="redirectToKategori()">Cancel</button>
                                    <button type="submit" class="btn btn-primary btn-cancel ">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Form Edit Kategori -->

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

    <!-- Scripts -->
    @include('template.scripts')

    <!-- Redirect to Kategori Function -->
    <script>
        function redirectToKategori() {
            window.location.href = "{{ url('/kategori') }}";
        }
    </script>

</body>

</html>
