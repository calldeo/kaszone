<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | {{ auth()->user()->level }} | Pengeluaran</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/dt-2.1.3/datatables.min.css"> --}}
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="text" class="form-control" value="{{ $parentPengeluaran->tanggal }}" readonly>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Detail Pengeluaran -->
            @foreach($parentPengeluaran->pengeluaran as $pengeluaran)
            
            <div class="card mb-4">
                <div class="card-header">
                    Detail Pengeluaran #{{ $loop->iteration }}
                    <!-- Tombol Edit -->
                    <a href="{{ route('pengeluaran.edit', $pengeluaran->id_data) }}" class="btn btn-primary float-right">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
                <div class="card-body">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" name="name" class="form-control editable-input" value="{{ $pengeluaran->name }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="description" class="form-control editable-input" rows="3" readonly>{{ $pengeluaran->description }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah Satuan</label>
                                <input type="text" name="jumlah_satuan" class="form-control editable-input" value="{{ $pengeluaran->jumlah_satuan }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nominal</label>
                                <input type="text" name="nominal" class="form-control editable-input" value="{{ number_format($pengeluaran->nominal, 2, ',', '.') }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kategori</label>
                                <input type="text" name="category" class="form-control editable-input" value="{{ $pengeluaran->category->name ?? 'Tidak ada kategori' }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Total</label>
                                <input type="text" name="jumlah" class="form-control editable-input" value="{{ $pengeluaran->jumlah }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Lain-lain</label>
                            <input type="text" name="dll" class="form-control editable-input" value="{{ $pengeluaran->dll }}" readonly>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gambar</label>
                                    <img src="{{ $pengeluaran->image ? asset('storage/' . $pengeluaran->image) : asset('dash/images/cash.png') }}" alt="Gambar" class="img-thumbnail">
                                </div>
                            </div>
                        </div>
                    </div>
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
