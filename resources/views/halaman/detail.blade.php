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
            <!-- Page Title and Breadcrumb -->
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

            <!-- Informasi Umum -->
            <div class="card">
                <div class="card-header">Informasi Umum</div>
                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="text" class="form-control" value="{{ $parentPengeluaran->tanggal }}" readonly>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Detail Pengeluaran -->
            @foreach($parentPengeluaran->pengeluaran as $pengeluaran)
            <div class="card mb-4">
                <div class="card-header">Detail Pengeluaran #{{ $loop->iteration }}</div>
                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" value="{{ $pengeluaran->name }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea class="form-control" rows="3" readonly>{{ $pengeluaran->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Jumlah Satuan</label>
                            <input type="text" class="form-control" value="{{ $pengeluaran->jumlah_satuan }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Nominal</label>
                            <input type="text" class="form-control" value="{{ number_format($pengeluaran->nominal, 2, ',', '.') }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Kategori</label>
                            <input type="text" class="form-control" value="{{ $pengeluaran->category->name ?? 'Tidak ada kategori' }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Jumlah</label>
                            <input type="text" class="form-control" value="{{ $pengeluaran->jumlah }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Lain-lain</label>
                            <input type="text" class="form-control" value="{{ $pengeluaran->dll }}" readonly>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
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

    <!-- Required Scripts -->
    @include('template.scripts')

    <!-- Custom Scripts -->
    <input type="hidden" id="table-url" value="{{ route('production') }}">
    <script src="{{ asset('main.js') }}"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-2.1.3/datatables.min.js"></script>

</body>

</html>
