<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>Edit Pemasukan</title>
</head>

<body>
    @include('template.topbarr')
    @include('template.sidebarr')

    <div class="content-body">
        <div class="container-fluid">
            <!-- Page Title and Breadcrumb -->
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Edit Pemasukan</h4>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Pemasukan</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit</a></li>
                    </ol>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="card">
                <div class="card-header">Edit Pemasukan</div>
                <div class="card-body">
                    <form action="{{ route('pemasukan.update', $pemasukan->id_data) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input type="text" id="name" name="name" class="form-control" value="{{ $pemasukan->name }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Deskripsi</label>
                                    <textarea id="description" name="description" class="form-control" rows="3">{{ $pemasukan->description }}</textarea>
                                </div>
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
                                <input type="date" class="form-control" id="val-date" name="date" value="{{ old('date', $pemasukan->date ?? '') }}" required>
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
                                <input type="text" class="form-control" id="val-jumlah" name="jumlah" value="{{ old('jumlah', 'Rp' . number_format($pemasukan->jumlah, 0, ',', '.')) }}" placeholder="Rp0,00" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category">Kategori</label>
                                    <select id="category" name="category_id" class="form-control select2">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $pemasukan->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <a href="/pemasukan" class="btn btn-danger btn-cancel">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-submit">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('template.scripts')
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2').select2();
    
            // Fungsi untuk mendapatkan kategori dari server
            getCategories();
    
            function getCategories() {
                $.ajax({
                    url: '/get-categories/1', // Sesuaikan URL jika perlu
                    method: 'GET',
                    success: function(data) {
                        var $dropdown = $('#category');
                        var selectedCategoryId = "{{ $pemasukan->id }}"; // Simpan nilai kategori yang sudah dipilih sebelumnya
                        
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
                            if (item.id == selectedCategoryId) {
                                $option.prop('selected', true);
                            }
    
                            $dropdown.append($option);
                        });
    
                        // Refresh Select2 untuk menampilkan opsi terbaru
                        $dropdown.trigger('change.select2');
                    },
                    error: function(xhr) {
                        console.error('Error fetching options:', xhr);
                    }
                });
            }
    
            // Memformat kembali nilai "Rp" saat form diisi dengan data dari database
            var jumlahValue = $('#val-jumlah').val();
            if (jumlahValue) {
                // Hapus pemisah ribuan dan Rp jika ada, lalu format kembali
                jumlahValue = jumlahValue.replace(/Rp/g, '').replace(/\./g, '').trim();
                $('#val-jumlah').val('Rp' + numberWithCommas(jumlahValue)); // Format kembali dengan "Rp"
            }
    
            // Ketika input jumlah berubah
            $('#val-jumlah').on('input', function() {
                var value = $(this).val().replace(/Rp/g, '').replace(/\./g, '').trim(); // Menghapus "Rp" dan pemisah ribuan
                // Cek apakah value valid
                if (!isNaN(value) && value !== '') {
                    // Format ke "Rp" dengan pemisah ribuan
                    $(this).val('Rp' + numberWithCommas(value));
                } else {
                    // Reset jika tidak valid
                    $(this).val('');
                }
            });
    
            // Fungsi untuk menambahkan pemisah ribuan
            function numberWithCommas(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
    
            // Mengubah nilai sebelum mengirim form
            $('form').on('submit', function() {
                var jumlahInput = $('#val-jumlah').val().replace(/Rp/g, '').replace(/\./g, '').trim(); // Menghapus "Rp" dan pemisah ribuan
                $('#val-jumlah').val(jumlahInput); // Memastikan nilai yang dikirim adalah angka tanpa "Rp" dan pemisah ribuan
            });
        });
    </script>
    
    
</body>

</html>
