<!DOCTYPE html>
<html lang="id">

<head>
    @include('template.headerr')
    <title>PityCash | {{ auth()->user()->level }} | Detail Pengeluaran</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .card {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
        .btn-xs {
            padding: .25rem .5rem;
            font-size: .875rem;
            line-height: 1.5;
            border-radius: .2rem;
        }
        .accordion__header {
            background-color: #f1f3f5;
            transition: all 0.3s ease;
        }
        .accordion__header:hover {
            background-color: #e9ecef;
        }
        .accordion__body {
            background-color: #ffffff;
        }
        .form-control:read-only {
            background-color: #f8f9fa;
        }
        .img-thumbnail {
            max-width: 300px;
            height: auto;
        }
    </style>
</head>

<body>
    @include('template.topbarr')

    @include('template.sidebarr')

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <h4 class="text-primary">Detail Pengeluaran</h4>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Tabel</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Pengeluaran</a></li>
                    </ol>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Informasi Pengeluaran</h5>
                    <div class="d-flex align-items-center">
                        @if(session('activeRole') == 'Admin' || session('activeRole') == 'Bendahara')
                            @if($pengeluaran = $parentPengeluaran->pengeluaran->first())
                                <a href="{{ route('pengeluaran.edit', $pengeluaran->id_parent) }}" class="btn btn-warning btn-xs mr-2">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            @else
                                <span class="text-muted">Tidak ada pengeluaran yang tersedia.</span>
                            @endif
                        @endif
                        <button onclick="window.location.href='/pengeluaran'" class="btn btn-danger btn-xs">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
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
                            <strong>Berhasil!</strong> {{ session('success') }}
                            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
                        </div>
                    @endif

                    @if(session('update_success'))
                        <div class="alert alert-warning alert-dismissible fade show">
                            <svg viewbox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                <polyline points="9 11 12 14 22 4"></polyline>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                            <strong>Berhasil!</strong> {{ session('update_success') }}
                            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
                        </div>
                    @endif

                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="text" class="form-control" value="{{ date('d/m/Y', strtotime($parentPengeluaran->tanggal)) }}" readonly>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div id="accordion-one" class="accordion accordion-primary">
                @foreach($parentPengeluaran->pengeluaran as $index => $pengeluaran)
                    <div class="accordion__item mb-3">
                        <div class="accordion__header rounded-lg" data-toggle="collapse" data-target="#collapse_{{ $index }}">
                            <span class="accordion__header--text">Detail Pengeluaran {{ $loop->iteration }}</span>
                            <span class="accordion__header--indicator"></span>
                        </div>
                        <div id="collapse_{{ $index }}" class="collapse accordion__body {{ $loop->first ? 'show' : '' }}" data-parent="#accordion-one">
                            <div class="accordion__body--text p-4">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Nama</label>
                                            <input type="text" name="name" class="form-control editable-input" value="{{ $pengeluaran->name }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Kategori</label>
                                            <input type="text" name="category" class="form-control editable-input" value="{{ $pengeluaran->category->name ?? 'Tidak ada kategori' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Nominal</label>
                                            <input type="text" name="nominal" class="form-control editable-input" value="Rp{{ number_format($pengeluaran->nominal, 0, ',', '.') }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Jumlah Satuan</label>
                                            <input type="text" name="jumlah_satuan" class="form-control editable-input" value="{{ $pengeluaran->jumlah_satuan }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Lain-lain</label>
                                            <input type="text" name="dll" class="form-control editable-input" value="Rp{{ number_format($pengeluaran->dll, 0, ',', '.') }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Total</label>
                                            <input type="text" name="jumlah" class="form-control editable-input" value="Rp{{ number_format($pengeluaran->jumlah, 0, ',', '.') }}" readonly>
                                        </div>
                                    </div>
                                       <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Deskripsi</label>
                                            <textarea name="description" class="form-control editable-input" rows="3" style="resize: none; overflow: hidden; font-size: 16px; height: 100px;" readonly>{{ $pengeluaran->description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                
                             
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Gambar</label>
                                            <img src="{{ $pengeluaran->image ? asset('storage/' . $pengeluaran->image) : asset('dash/images/cash.png') }}" alt="Gambar" class="img-thumbnail mt-2">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="copyright">
            <p>Copyright © Designed &amp; Developed by <a href="/home" target="_blank">SYNC</a> 2024</p>
        </div>
    </div>

    @include('template.scripts')

    <input type="hidden" id="table-url" value="{{ route('production') }}">
    <script src="{{ asset('main.js') }}"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-2.1.3/datatables.min.js"></script>
</body>

</html>
