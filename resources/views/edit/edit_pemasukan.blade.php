<!DOCTYPE html>
<html lang="en">
<head>
    @include('template.headerr')
    <title>E-vote | {{ auth()->user()->level }} | Edit</title>
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
            <!-- Modal for Add Project -->
            <div class="modal fade" id="addProjectSidebar">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Create Project</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label class="text-black font-w500">Project Name</label>
                                    <input type="text" class="form-control" name="project_name">
                                </div>
                                <div class="form-group">
                                    <label class="text-black font-w500">Deadline</label>
                                    <input type="date" class="form-control" name="deadline">
                                </div>
                                <div class="form-group">
                                    <label class="text-black font-w500">Client Name</label>
                                    <input type="text" class="form-control" name="client_name">
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary">CREATE</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Title and Breadcrumb -->
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Hi, welcome back!</h4>
                        <p class="mb-0">Validation</p>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Form</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Validation</a></li>
                    </ol>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Data Kategori</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                <form action="/pemasukan/{{ $id_data }}" method="POST" enctype="multipart/form-data">
                                    @method('PUT')
                                    @csrf
                                    <div class="form-group">
                                        <label class="text-label">Nama *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="val-username1" name="name" value="{{ old('name', $pemasukan->name ?? '') }}" placeholder="Masukkan nama.." required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-label">Deskripsi *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="val-description" name="description" value="{{ old('description', $pemasukan->description ?? '') }}" placeholder="Masukkan deskripsi.." required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-label">Tanggal *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            </div>
                                            <input type="date" class="form-control" id="val-date" name="date" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-label">Jumlah *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-money"></i></span>
                                            </div>
                                            <input type="number" class="form-control" id="val-jumlah" name="jumlah" value="{{ old('jumlah', $pemasukan->jumlah ?? '') }}" placeholder="Masukkan jumlah.." required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-label">Kategori *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-list"></i></span>
                                            </div>
                                            <select class="form-control default-select" id="val-category" name="category_id" required>
                                                <option value="">--Pilih Kategori--</option>
                                                @foreach($category as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id', $pemasukan->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input id="checkbox1" class="form-check-input" type="checkbox" required>
                                            <label for="checkbox1" class="form-check-label">Check me out *</label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn mr-2 btn-primary">Submit</button>
                                    <button type="button" class="btn btn-light" onclick="redirectToKategori()">Cancel</button>
                                </form>
                            </div>
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

    <!-- Scripts -->
    @include('template.scripts')

    <script>
        function redirectToKategori() {
            window.location.href = "/pemasukan";
        }
    </script>
</body>
</html>
