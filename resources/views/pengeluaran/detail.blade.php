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
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span>Informasi</span>
                    @if($pengeluaran = $parentPengeluaran->pengeluaran->first())
                        <a href="{{ route('pengeluaran.edit', $pengeluaran->id_parent) }}" class="btn btn-warning btn-xs mr-1">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    @else
                        <span>Tidak ada pengeluaran yang tersedia.</span>
                    @endif
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

            <div class="d-flex flex-column">
                @foreach($parentPengeluaran->pengeluaran as $pengeluaran)
                    <div class="col-md-12 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <span>Detail Pengeluaran {{ $loop->iteration }}</span>
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
                                            <textarea name="description" class="form-control editable-input" rows="3" style="resize: none; overflow: hidden; font-size: 16px; height: 100px;" readonly>{{ $pengeluaran->description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Nominal</label>
                                            <input type="text" name="nominal" class="form-control editable-input" value="Rp{{ number_format($pengeluaran->nominal, 2, ',', '.') }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Jumlah Satuan</label>
                                            <input type="text" name="jumlah_satuan" class="form-control editable-input" value="{{ $pengeluaran->jumlah_satuan }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Lain-lain</label>
                                            <input type="text" name="dll" class="form-control editable-input" value="Rp{{ $pengeluaran->dll }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Total</label>
                                            <input type="text" name="jumlah" class="form-control editable-input"value="Rp{{ number_format($pengeluaran->jumlah, 2, ',', '.') }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Kategori</label>
                                            <input type="text" name="category" class="form-control editable-input" value="{{ $pengeluaran->category->name ?? 'Tidak ada kategori' }}" readonly>
                                        </div>
                                    </div>
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
                            <div class="card-footer">
                                <button type="button" class="btn btn-danger btn-cancel" onclick="window.location.href='/pengeluaran'">Cancel</button>
                            </div>
                        </div>
                    </div>
                @endforeach
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

    <!-- Required Scripts -->
    @include('template.scripts')

    <!-- Custom Scripts -->
    <input type="hidden" id="table-url" value="{{ route('production') }}">
    <script src="{{ asset('main.js') }}"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-2.1.3/datatables.min.js"></script>
</body>

</html>
