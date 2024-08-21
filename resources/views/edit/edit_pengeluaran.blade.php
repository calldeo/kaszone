<!DOCTYPE html>
<html lang="en">
<head>
    @include('template.headerr')
    <title>E-vote | {{ auth()->user()->level }} | Edit</title>
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
            <!-- Page Title and Breadcrumb -->
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Hi, Welcome Back!</h4>
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
                            <h4 class="card-title">Edit Data Pengeluaran</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                <form action="/pengeluaran/{{ $id_data }}" method="POST" enctype="multipart/form-data">
                                    @method('PUT')
                                    @csrf

                                    <div class="form-group">
                                        <label class="text-label">Nama Pengeluaran *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-money-bill-wave"></i> <!-- Ikon uang -->
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" id="val-username1" name="name" value="{{ old('name', $pengeluaran->name ?? '') }}" placeholder="Masukkan nama.." required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="text-label">Deskripsi *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-book"></i> <!-- Ikon buku -->
                                                </span>
                                            </div>
                                            <textarea class="form-control" id="val-description" name="description" placeholder="Masukkan deskripsi.." required>{{ old('description', $pengeluaran->description ?? '') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="text-label">Tanggal *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-calendar"></i> <!-- Ikon kalender -->
                                                </span>
                                            </div>
                                            <input type="date" class="form-control" id="val-date" name="date" value="{{ old('date', $pengeluaran->date ?? '') }}" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="text-label">Jumlah *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-dollar-sign"></i> <!-- Ikon dolar -->
                                                </span>
                                            </div>
                                            <input type="number" class="form-control" id="val-jumlah" name="jumlah" value="{{ old('jumlah', $pengeluaran->jumlah ?? '') }}" placeholder="Masukkan jumlah.." required>
                                        </div>
                                    </div>
      <div class="form-group">
    <label class="text-label">Kategori *</label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="fas fa-list"></i> <!-- Ikon daftar -->
            </span>
        </div>
        <select class="form-control default-select" id="val-category" name="id">
            <option value="">--Pilih Kategori--</option>
            @foreach($category as $cat)
                <option value="{{ $cat->id }}" {{ old('id', $pengeluaran->id) == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

                                  
                                    <button type="button" class="btn btn-danger btn-cancel" onclick="redirectToPengeluaran()">Cancel</button>

                                    <button type="submit" class="btn mr-2 btn-primary btn-submit">Submit</button>
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
        function redirectToPengeluaran() {
            window.location.href = "/pengeluaran";
        }
    </script>
</body>
</html>
