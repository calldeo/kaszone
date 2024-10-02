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
                        <div class="card-header">Informasi </div>
                        <div class="card-body">
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <strong>Error!</strong> {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span><i class="fa fa-times"></i></span>
                                    </button>
                                </div>
                            @endif

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    <strong>Success!</strong> {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span><i class="mdi mdi-close"></i></span>
                                    </button>
                                </div>
                            @endif

                            @if(session('update_success'))
                                <div class="alert alert-warning alert-dismissible fade show">
                                    <strong>Success!</strong> {{ session('update_success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span><i class="mdi mdi-close"></i></span>
                                    </button>
                                </div>
                            @endif

                            <form action="{{ route('pengeluaran.update', $parentPengeluaran->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="text" id="tanggal" class="form-control" name="tanggal" value="{{ old('tanggal', $parentPengeluaran->tanggal) }}" readonly>
                    </div>
                </div>
            </div>

            @foreach($parentPengeluaran->pengeluaran as $key => $pengeluaran)
                <div class="card mt-4">
                    <div class="card-header bg-primary text-white">
                        Detail Pengeluaran {{ $loop->iteration }}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name_{{ $key }}">Nama</label>
                                    <input type="text" id="name_{{ $key }}" name="name[]" class="form-control" value="{{ old('name.'.$key, $pengeluaran->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description_{{ $key }}">Deskripsi</label>
                                    <textarea id="description_{{ $key }}" name="description[]" class="form-control" rows="1">{{ old('description.'.$key, $pengeluaran->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="jumlah_satuan_{{ $key }}">Jumlah Satuan</label>
                                    <input type="text" id="jumlah_satuan_{{ $key }}" name="jumlah_satuan[]" class="form-control" value="{{ old('jumlah_satuan.'.$key, $pengeluaran->jumlah_satuan) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nominal_{{ $key }}">Nominal</label>
                                    <input type="text" id="nominal_{{ $key }}" name="nominal[]" class="form-control" value="{{ old('nominal.'.$key, $pengeluaran->nominal) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="jumlah_{{ $key }}">Total</label>
                                    <input type="text" id="jumlah_{{ $key }}" name="jumlah[]" class="form-control" value="{{ old('jumlah.'.$key, $pengeluaran->jumlah) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                    <div class="col-md-6">
            <div class="form-group">
                <label for="category_{{ $key }}">Kategori</label>
                <select id="category_{{ $key }}" name="category_id[]" class="form-control select2 category-dropdown" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (old('category_id.'.$key) == $category->id || $pengeluaran->category_id == $category->id) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dll_{{ $key }}">Lain-lain</label>
                                    <input type="text" id="dll_{{ $key }}" name="dll[]" class="form-control" value="{{ old('dll.'.$key, $pengeluaran->dll) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image_{{ $key }}">Gambar</label>
                                    <input type="file" id="image_{{ $key }}" name="image[]" class="form-control">
                                    <img src="{{ $pengeluaran->image ? asset('storage/' . $pengeluaran->image) : asset('dash/images/cash.png') }}" alt="Gambar" class="img-thumbnail mt-2">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" onclick="window.location.href='/pengeluaran'">Cancel</button>
            </div>
            </form>

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

            <!-- Required Scripts -->
            @include('template.scripts')

            <!-- Custom Scripts -->
            <script src="{{ asset('main.js') }}"></script>
            <script src="https://cdn.datatables.net/v/bs5/dt-2.1.3/datatables.min.js"></script>

            </body>
            <script>
            $(document).ready(function() {
            getCategories(); // Memanggil fungsi untuk mengambil kategori

            function getCategories() {
                $.ajax({
                    url: '/get-categories/2', // Sesuaikan URL sesuai kebutuhan
                    method: 'GET',
                    success: function(data) {
                        // Ambil semua dropdown dengan kelas 'category-dropdown'
                        $('.category-dropdown').each(function() {
                            var $dropdown = $(this); // Mengambil dropdown saat ini
                            $dropdown.empty(); // Menghapus opsi yang ada sebelumnya

                            // Menambahkan opsi default
                            $dropdown.append($('<option>', {
                                value: '',
                                text: 'Pilih Kategori'
                            }));

                            // Menambahkan kategori ke dropdown
                            $.each(data, function(index, item) {
                                $dropdown.append($('<option>', {
                                    value: item.id, // Memastikan ini sesuai dengan respons API
                                    text: item.name // Menggunakan 'nama_kategori' sesuai dengan respons API
                                }));
                            });
                        });

                        // Inisialisasi Select2 setelah semua dropdown diisi
                        $('.category-dropdown').select2();
                    },
                    error: function(xhr) {
                        console.error('Error fetching categories:', xhr); // Mencetak kesalahan di konsol
                        // Menampilkan pesan kesalahan di UI
                        $('.category-dropdown').each(function() {
                            $(this).append($('<option>', {
                                value: '',
                                text: 'Error loading categories'
                            }));
                        });
                    }
                });
            }
            });
            </script>


            </html>
