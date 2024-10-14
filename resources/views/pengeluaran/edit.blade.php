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
            <div class="col-sm-6 p-md-0"></div>
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

                    <!-- Container untuk Pengeluaran -->
                    <div id="accordion-one" class="accordion accordion-primary">
                        @foreach($parentPengeluaran->pengeluaran as $key => $pengeluaran)
                            <div class="accordion__item">
                                <div class="accordion__header rounded-lg" data-toggle="collapse" data-target="#collapse_{{ $key }}">
                                    <span class="accordion__header--text">Edit Pengeluaran {{ $loop->iteration }}</span>
                                    <span class="accordion__header--indicator"></span>
                                </div>
                                <div id="collapse_{{ $key }}" class="collapse accordion__body {{ $loop->first ? 'show' : '' }}" data-parent="#accordion-one">
                                    <div class="accordion__body--text">
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
                                                    <input type="text" id="jumlah_satuan_{{ $key }}" name="jumlah_satuan[]" class="form-control jumlah_satuan" data-key="{{ $key }}" value="{{ old('jumlah_satuan.'.$key, $pengeluaran->jumlah_satuan) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="nominal_{{ $key }}">Nominal</label>
                                                    <input type="text" id="nominal_{{ $key }}" name="nominal[]" class="form-control nominal" data-key="{{ $key }}" 
                                                        value="{{ old('nominal.'.$key, 'Rp' . number_format($pengeluaran->nominal, 0, ',', '.')) }}" required >
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="jumlah_{{ $key }}">Total</label>
                                                    <input type="text" id="jumlah_{{ $key }}" name="jumlah[]" class="form-control jumlah" 
                                                        value="{{ old('jumlah.'.$key, 'Rp' . number_format($pengeluaran->jumlah, 0, ',', '.')) }}" required readonly>
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
                                                            <option value="{{ $category->id }}" {{ $pengeluaran->id == $category->id ? 'selected' : '' }}>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="dll_{{ $key }}">Lain-lain</label>
                                                    <input type="text" id="dll_{{ $key }}" name="dll[]" class="form-control dll" data-key="{{ $key }}" 
                                                        value="{{ old('dll.'.$key, 'Rp' . number_format($pengeluaran->dll, 0, ',', '.')) }}" required>
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
                                        
                                        @if($parentPengeluaran->pengeluaran->count() > 1)
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <a href="{{ route('pengeluaran.delete', $pengeluaran->id_data) }}" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?')">
                                                        <i class="fas fa-trash-alt"></i> Hapus
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Container untuk menambah pengeluaran baru -->
                    <div id="pengeluaran-container"></div>

                    <!-- Tombol untuk menambah pengeluaran -->
                    <div class="mt-3 text-right">
                        <button type="button" class="btn btn-info rounded" id="add-pengeluaran">
                            <i class="fas fa-plus"></i> Tambah Pengeluaran
                        </button>
                    </div>

                  <div class="mt-4">
                        <button type="button" class="btn btn-danger" onclick="window.location.href='/pengeluaran'">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
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

<script>
    $(document).ready(function() {
        // Inisialisasi Select2
        $('.select2').select2();
    
        // Fungsi untuk mendapatkan kategori dari server
        // getCategories();
    
        function getCategories(countItem = null) {
            $.ajax({
                url: '/get-categories/2', // Sesuaikan URL jika perlu
                method: 'GET',
                success: function(data) {
                    var $dropdown = $('.category-dropdown'); // Ganti dengan selector yang sesuai
                    if (countItem != null) {
                        var $dropdown = $('.category-dropdown' + countItem);
                    }
                    var selectedCategoryId = "{{ $pengeluaran->id }}"; // Mengambil ID kategori dari objek pengeluaran
    
                    // Kosongkan dropdown tapi pertahankan kategori yang dipilih
                    $dropdown.empty(); 
    
                    // Tambahkan opsi default
                    $dropdown.append($('<option>', {
                        value: '',
                        text: 'Pilih Kategori'
                    }));
    
                    // Iterasi data yang diterima dari server
                    $.each(data, function(index, item) {
                        var $option = $('<option>', {
                            value: item.id,
                            text: item.name
                        });
    
                        // Pastikan opsi yang sesuai tetap terpilih
                        if (countItem == null) {
                            if (item.id == selectedCategoryId) {
                                $option.prop('selected', true);
                            }
                        }
    
                        $dropdown.append($option);
                    });
    
                    if (isAddItem == false) {
                        // Refresh Select2 untuk menampilkan opsi terbaru
                        $dropdown.trigger('change.select2');
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching options:', xhr);
                    $('.category-dropdown').append($('<option>', {
                        value: '',
                        text: 'Error loading categories'
                    }));
                }
            });
        }

   // Fungsi untuk menambahkan pemisah ribuan dan prefix "Rp"
function formatCurrency(input) {
    // Menghapus "Rp" dan pemisah ribuan, kemudian trim nilai
    var value = $(input).val().replace(/Rp/g, '').replace(/\./g, '').trim();
    
    // Cek jika nilai adalah angka
    if (!isNaN(value) && value !== '') {
        // Format kembali dengan "Rp" dan pemisah ribuan
        $(input).val('Rp' + numberWithCommas(value));
    } else {
        // Reset jika tidak valid
        $(input).val('');
    }
}

// Fungsi untuk menghitung total
function calculateTotal(key) {
    const jumlahSatuan = parseFloat($(`#jumlah_satuan_${key}`).val().replace(/Rp/g, '').replace(/\./g, '').trim()) || 0;
    const nominal = parseFloat($(`#nominal_${key}`).val().replace(/Rp/g, '').replace(/\./g, '').trim()) || 0;
    const dll = parseFloat($(`#dll_${key}`).val().replace(/Rp/g, '').replace(/\./g, '').trim()) || 0;
    
    // Hitung total
    const total = (jumlahSatuan * nominal) + dll;
    $(`#jumlah_${key}`).val('Rp' + numberWithCommas(total.toFixed(0))); // Format total ke "Rp"
}

// Event listener untuk format currency saat input pada nominal dan dll
$(document).on('input', '.nominal, .dll', function() {
    formatCurrency(this); // Format nilai saat input
    const key = $(this).data('key'); // Ambil key dari data atribut
    calculateTotal(key); // Hitung total setelah format
});

// Fungsi untuk menghitung total ketika input jumlah satuan berubah
$(document).on('input', '.jumlah_satuan', function() {
    const key = $(this).data('key'); // Ambil key dari data atribut
    calculateTotal(key); // Hitung total
});

// Fungsi untuk menambahkan pemisah ribuan
function numberWithCommas(x) {
    return x.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Mengubah nilai sebelum mengirim form
$('form').on('submit', function() {
    // Untuk setiap input nominal dan dll
    $('.nominal, .dll').each(function() {
        var inputVal = $(this).val().replace(/Rp/g, '').replace(/\./g, '').trim(); // Hapus "Rp" dan pemisah ribuan
        $(this).val(inputVal); // Pastikan nilai yang dikirim adalah angka
    });
    
    // Jika Anda ingin melakukan hal yang sama untuk jumlah, Anda bisa menambahkannya juga
    $('.jumlah').each(function() {
        var inputVal = $(this).val().replace(/Rp/g, '').replace(/\./g, '').trim(); // Hapus "Rp" dan pemisah ribuan
        $(this).val(inputVal); // Pastikan nilai yang dikirim adalah angka
    });
});


    // Tambah pengeluaran
    let pengeluaranCount = {{ $parentPengeluaran->pengeluaran->count() }};
    $('#add-pengeluaran').on('click', function() {
        pengeluaranCount++;
        const newPengeluaran = `
            <div id="accordion-one" class="accordion accordion-primary">
            <div class="accordion__item pengeluaran-item" data-key="${pengeluaranCount}">
                <div class="accordion__header rounded-lg" data-toggle="collapse" data-target="#pengeluaran_collapse_${pengeluaranCount}">
                    <span class="accordion__header--text">Pengeluaran ${pengeluaranCount}</span>
                    <span class="accordion__header--indicator"></span>
                </div>
                <div id="pengeluaran_collapse_${pengeluaranCount}" class="collapse accordion__body" data-parent="#pengeluaran-container">
                    <div class="accordion__body--text">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name_${pengeluaranCount}">Nama</label>
                                    <input type="text" id="name_${pengeluaranCount}" name="name[]" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description_${pengeluaranCount}">Deskripsi</label>
                                    <textarea id="description_${pengeluaranCount}" name="description[]" class="form-control" rows="1"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="jumlah_satuan_${pengeluaranCount}">Jumlah Satuan</label>
                                    <input type="text" id="jumlah_satuan_${pengeluaranCount}" name="jumlah_satuan[]" class="form-control jumlah_satuan" data-key="${pengeluaranCount}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nominal_${pengeluaranCount}">Nominal</label>
                                    <input type="text" id="nominal_${pengeluaranCount}" name="nominal[]" class="form-control nominal" data-key="${pengeluaranCount}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="jumlah_${pengeluaranCount}">Total</label>
                                    <input type="text" id="jumlah_${pengeluaranCount}" name="jumlah[]" class="form-control jumlah" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_${pengeluaranCount}">Kategori</label>
                                    <select id="category_${pengeluaranCount}" name="category_id[]" class="form-control select2 category-dropdown${pengeluaranCount}" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $pengeluaran->id == $category->id ? 'selected' : '' }}>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dll_${pengeluaranCount}">Lain-lain</label>
                                    <input type="text" id="dll_${pengeluaranCount}" name="dll[]" class="form-control dll" data-key="${pengeluaranCount}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image_${pengeluaranCount}">Gambar</label>
                                    <input type="file" id="image_${pengeluaranCount}" name="image[]" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger btn-sm remove-pengeluaran">
                                    <i class="fas fa-trash-alt"></i> Hapus 
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('#pengeluaran-container').append(newPengeluaran);
        $('.select2').select2();
        getCategories(pengeluaranCount); // Inisialisasi Select2 setelah menambah elemen baru
    });

    // Menghapus pengeluaran
    $(document).on('click', '.remove-pengeluaran', function() {
        $(this).closest('.accordion__item').remove();
    });
});




</script>

</body>
</html>