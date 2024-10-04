<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | {{ auth()->user()->level }} | Add Pengeluaran</title>
    <!-- Include necessary CSS libraries -->
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
                  
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Form</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Add Pemasukan</a></li>
                    </ol>
                </div>
            </div>

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
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <strong>Error!</strong> {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span><i class="fa fa-times"></i></span></button>
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

         <!-- Add Pemasukan Form -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Add Data Pengeluaran</h4>

             
            </div>

            <div class="card-body">
                <form class="form-valide-with-icon" action="/pengeluaran/store" method="post" enctype="multipart/form-data">
                    @csrf
   <!-- Tanggal input di sebelah kanan judul -->
                <div class="form-group mb-2 d-flex align-items-center">
                    {{-- <label class="text-label mr-2 mb-0">Tanggal *</label> --}}
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                        <!-- Menggunakan input tipe date bawaan HTML5 -->
                        <input type="date" class="form-control" name="tanggal" value="{{ old('tanggal') }}" required>
                    </div>
                    @error('tanggal')
                    <span class="mt-2 text-danger">{{ $message }}</span>
                    @enderror
                </div>
                    <!-- Container for dynamically added fields -->
                    <div id="dynamic-fields-container">
                        <div class="dynamic-field">
                            <!-- Nama Pengeluaran Field -->
                            <div class="form-group">
                                <label class="text-label">Nama Pengeluaran *</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="name[]" placeholder="Enter name.." value="{{ old('name') }}" required>
                                </div>
                                @error('name')
                                <span class="mt-2 text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Deskripsi Field -->
                            <div class="form-group">
                                <label class="text-label">Deskripsi</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-book"></i></span>
                                    </div>
                                    <textarea class="form-control" name="description[]" placeholder="Enter description..">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            

                            <!-- Nominal Field -->
                            <div class="form-group">
                                <label class="text-label">Nominal (Rp) *</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control" id="nominal" name="nominal[]" placeholder="Enter amount.." value="{{ old('nominal') }}" required>
                                </div>
                                @error('nominal')
                                <span class="mt-2 text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Jumlah Satuan Field -->
                            <div class="form-group">
                                <label class="text-label">Jumlah Satuan *</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control" id="jumlah_satuan" name="jumlah_satuan[]" placeholder="Enter amount.." value="{{ old('jumlah_satuan') }}" required>
                                </div>
                                @error('jumlah_satuan')
                                <span class="mt-2 text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Dll Field -->
                            <div class="form-group">
                                <label class="text-label">Dll *</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control" id="dll" name="dll[]" placeholder="Enter amount.." value="{{ old('dll') }}" required>
                                </div>
                                @error('dll')
                                <span class="mt-2 text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Jumlah Field -->
                            <div class="form-group">
                                <label class="text-label">Jumlah *</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control" id="jumlah" name="jumlah[]" placeholder="Enter amount.." value="{{ old('jumlah') }}" required>
                                </div>
                                @error('jumlah')
                                <span class="mt-2 text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="text-label">Kategori *</label>
                                <select class="select2-with-label-single js-states form-control" id="category" name="category_id[]" required>
                                    <option value="">PILIH KATEGORI</option>
                            
                                </select>
                                @error('category_id')
                                <span class="mt-2 text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Foto Bukti Pengeluaran Field -->
                            <div class="form-group">
                                <label for="image">Foto Bukti Pengeluaran</label>
                                <div class="mb-3">
                                    <img id="profile-image" src="{{ asset('dash/images/cash.png') }}" alt="Gambar Bukti Pengeluaran" width="150" height="150">
                                </div>
                                <div class="file-upload-wrapper">
                                    <label class="file-upload-label" for="image">Pilih file</label>
                                    <input type="file" id="image" name="image[]" accept="image/*" onchange="updateImagePreview(this, 'profile-image')">
                                    <div id="file-upload-info" class="file-upload-info">Tidak ada file yang dipilih</div>
                                </div>
                                <label class="text-label text-danger mt-3">* Jika tidak ada perubahan, tidak perlu diisi</label>
                            </div>
                        </div>

                        <hr>
                    </div>

                    <!-- Button to add new set of fields -->
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-info" id="add-more-fields">Add Pengeluaran</button>
                    </div>

                    <!-- Submit and Cancel buttons -->
                    <button type="button" class="btn btn-danger btn-cancel" onclick="window.location.href='/pengeluaran'">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-submit">Submit</button>
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

    <!-- Scripts -->
    @include('template.scripts')
</body>
<script>
    $(document).ready(function() {
        // Fungsi untuk mengambil kategori dari server
        function getCategories(callback) {
            $.ajax({
                url: '/get-categories/2',
                method: 'GET',
                success: function(data) {
                    if (callback) callback(data);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching categories:', error);
                }
            });
        }

        // Memanggil getCategories pada saat memuat halaman
        getCategories(function(categories) {
            // Setiap dropdown yang ada di halaman ini diisi dengan kategori
            $('#dynamic-fields-container .dynamic-field select[name="category_id[]"]').each(function() {
                var $dropdown = $(this);
                $dropdown.empty(); // Kosongkan opsi yang ada
                $dropdown.append('<option value="">--PILIH KATEGORI--</option>');
                $.each(categories, function(index, item) {
                    $dropdown.append($('<option>', {
                        value: item.id,
                        text: item.name
                    }));
                });
            });
        });
        

        // Fungsi untuk memperbarui pratinjau gambar
        function updateImagePreview(input, imageId) {
            var file = input.files[0];
            var $image = $('#' + imageId);
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $image.attr('src', e.target.result);
                    $(input).siblings('.file-upload-info').text(file.name);
                };
                reader.readAsDataURL(file);
            } else {
                $image.attr('src', '{{ asset('dash/images/cash.png') }}');
                $(input).siblings('.file-upload-info').text('Tidak ada file yang dipilih');
            }
        }

        // Mendengarkan perubahan pada input file
        $(document).on('change', 'input[type="file"]', function() {
            updateImagePreview(this, $(this).siblings('img').attr('id'));
        });

        $(document).ready(function() {
    // Menambahkan lebih banyak bidang formulir
$('#add-more-fields').on('click', function() {
    var newFieldSet = `
        <div class="dynamic-field">
            <div class="form-group">
                <label class="text-label">Nama Pengeluaran*</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                    </div>
                    <input type="text" class="form-control" name="name[]" placeholder="Enter name.." required>
                </div>
            </div>

            <div class="form-group">
                <label class="text-label">Deskripsi</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-book"></i></span>
                    </div>
                    <textarea class="form-control" name="description[]" placeholder="Enter description.."></textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="text-label">Nominal (Rp) *</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                    </div>
                    <input type="number" step="0.01" class="form-control nominal" name="nominal[]" placeholder="Enter amount.." required>
                </div>
            </div>

            <div class="form-group">
                <label class="text-label">Jumlah Satuan *</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                    </div>
                    <input type="number" step="0.01" class="form-control jumlah_satuan" name="jumlah_satuan[]" placeholder="Enter amount.." required>
                </div>
            </div>


            <div class="form-group">
                <label class="text-label">Dll *</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                    </div>
                    <input type="number" step="0.01" class="form-control dll" name="dll[]" placeholder="Enter amount.." required>
                </div>
            </div>

            <div class="form-group">
                <label class="text-label">Jumlah *</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                    </div>
                    <input type="number" step="0.01" class="form-control jumlah" name="jumlah[]" placeholder="Enter amount.." required readonly>
                </div>
            </div>

           <div class="form-group">
                                        <label class="text-label">Kategori *</label>
                                        <select class="select2-with-label-single js-states form-control" id="category" name="category_id[]" required>
                                            <option value="">PILIH KATEGORI</option>
                                    
                                        </select>
                                        @error('category_id')
                                        <span class="mt-2 text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

            <!-- Field untuk Foto Bukti Pengeluaran -->
       <div class="form-group">
    <label for="image1">Foto Bukti Pengeluaran 1</label>
    <div class="mb-3">
        <img id="profile-image1" src="{{ asset('dash/images/cash.png') }}" alt="Gambar Bukti Pengeluaran 1" width="150" height="150">
    </div>
    <div class="file-upload-wrapper">
        <label class="file-upload-label" for="image1">Pilih file</label>
        <input type="file" id="image1" name="image[]" accept="image/*" onchange="updateImagePreview(this, 'profile-image1', 'file-upload-info1')">
        <div id="file-upload-info1" class="file-upload-info">Tidak ada file yang dipilih</div>
    </div>
    <label class="text-label text-danger mt-3">* Jika tidak ada perubahan, tidak perlu diisi</label>
</div>

            <button type="button" class="btn btn-danger remove-field">Remove</button>
            <hr>
        </div>
    `;

        $('#dynamic-fields-container').append(newFieldSet);

        // Set event listener untuk menghitung jumlah otomatis
        calculateTotal();

        // Fungsi untuk menghitung jumlah otomatis
        function calculateTotal() {
            $('#dynamic-fields-container').on('input', '.jumlah_satuan, .nominal, .dll', function() {
                var $parent = $(this).closest('.dynamic-field');
                var jumlahSatuan = parseFloat($parent.find('.jumlah_satuan').val()) || 0;
                var nominal = parseFloat($parent.find('.nominal').val()) || 0;
                var dll = parseFloat($parent.find('.dll').val()) || 0;

                // Hitung total dan isi field jumlah
                var total = jumlahSatuan * nominal + dll;
                $parent.find('.jumlah').val(total.toFixed(2)); // Isi field jumlah dengan nilai total
            });
        }

        // Memuat ulang kategori untuk bidang baru
        getCategories(function(categories) {
            $('#dynamic-fields-container .dynamic-field:last-child select[name="category_id[]"]').each(function() {
                var $dropdown = $(this);
                $dropdown.empty(); // Kosongkan opsi yang ada
                $dropdown.append('<option value="">--PILIH KATEGORI--</option>');
                $.each(categories, function(index, item) {
                    $dropdown.append($('<option>', {
                        value: item.id,
                        text: item.name
                    }));
                });
                   $dropdown.select2();
            });
            
        });
    });
});


    // Menangani penghapusan bidang formulir
    $('#dynamic-fields-container').on('click', '.remove-field', function() {
        $(this).closest('.dynamic-field').remove();
    });

    // Memastikan tombol "Remove" terlihat pada saat memuat
    $('#dynamic-fields-container').on('DOMNodeInserted', function(event) {
        if ($(event.target).hasClass('dynamic-field')) {
            $(event.target).find('.remove-field').show();
        }
    });
});
</script>
</script>
  <script>
  $(document).ready(function() {
    getCategories(); // Memanggil fungsi untuk mengambil kategori

    function getCategories() {
        $.ajax({
            url: '/get-categories/2 ', // Sesuaikan URL sesuai kebutuhan
            method: 'GET',
            success: function(data) {
                var $dropdown = $('#category'); // Mengambil elemen dropdown dengan ID 'category'
                $dropdown.empty(); // Menghapus opsi yang ada sebelumnya

                // Menambahkan opsi default
                $dropdown.append($('<option>', {
                    value: '',
                    text: 'Select Category'
                }));

                // Menambahkan kategori ke dropdown
                $.each(data, function(index, item) {
                    $dropdown.append($('<option>', {
                        value: item.id, // Memastikan ini sesuai dengan respons API
                        text: item.name // Menggunakan 'nama_kategori' sesuai dengan respons API
                    }));
                });

                $dropdown.select2(); // Inisialisasi Select2
            },
            error: function(xhr) {
                console.error('Error fetching categories:', xhr); // Mencetak kesalahan di konsol
                // Menampilkan pesan kesalahan di UI
                $('#category').append($('<option>', {
                    value: '',
                    text: 'Error loading categories'
                }));
            }
        });
    }
});


        </script>
       <script>
        
  function updateImagePreview(input, imageId, infoId) {
        const file = input.files[0];
        const fileUploadInfo = document.getElementById(infoId);
        const imagePreview = document.getElementById(imageId);
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                fileUploadInfo.textContent = file.name; // Tampilkan nama file yang dipilih
            }
            reader.readAsDataURL(file);
        } else {
            imagePreview.src = "{{ asset('dash/images/cash.png') }}"; // Ganti dengan gambar default
            fileUploadInfo.textContent = "Tidak ada file yang dipilih";
        }
    }


</script>
<script>
    document.addEventListener('input', function () {
        let jumlahSatuan = parseFloat(document.getElementById('jumlah_satuan').value) || 0;
        let nominal = parseFloat(document.getElementById('nominal').value) || 0;
        let dll = parseFloat(document.getElementById('dll').value) || 0;


        let jumlah = jumlahSatuan * nominal + dll;

        document.getElementById('jumlah').value = jumlah.toFixed(2); // Mengisi hasil ke input 'jumlah'
    });
</script>
{{-- <script>
    $(document).ready(function() {
        // Inisialisasi Bootstrap Datepicker
        $('#datepicker').datepicker({
            format: 'dd/mm/yyyy', // Format tanggal
            todayHighlight: true,
            autoclose: true
        });

        // Menampilkan input saat tombol diklik
        $('#datepicker-toggle').on('click', function() {
            $('#datepicker').toggle(); // Toggle visibilitas input
        });
    });
</script> --}}
<script>
 document.addEventListener('DOMContentLoaded', function () {
    function calculateTotal() {
        const jumlahSatuan = Number(document.getElementById('jumlah_satuan').value) || 0;
        const nominal = Number(document.getElementById('nominal').value) || 0;
        const dll = Number(document.getElementById('dll').value) || 0;

        // Menghitung total
        const total = (jumlahSatuan * nominal) + dll;

        // Format total menjadi dua desimal dan menampilkan hasil
        document.getElementById('jumlah').value = total.toFixed(2);
    }

    // Tambahkan event listener untuk setiap input
    document.getElementById('jumlah_satuan').addEventListener('input', calculateTotal);
    document.getElementById('nominal').addEventListener('input', calculateTotal);
    document.getElementById('dll').addEventListener('input', calculateTotal);
});


    </script>

    
</html>