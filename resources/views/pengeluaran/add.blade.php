<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | {{ auth()->user()->level }} | Tambbah Pengeluaran</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    @include('template.topbarr')

    @include('template.sidebarr')

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                  
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Form</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Tambah Pengeluaran</a></li>
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

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Tambah Data Pengeluaran</h4>
                        </div>
                        <div class="card-body">
                            <form class="form-valide-with-icon" action="/pengeluaran/store" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label class="text-label">Tanggal *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input type="date" class="form-control" name="tanggal" value="{{ old('tanggal') }}" required>
                                    </div>
                                    @error('tanggal')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div id="dynamic-fields-container">
                                    <div class="dynamic-field">
                                        <div class="form-group">
                                            <label class="text-label">Nama Pengeluaran *</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                                                </div>
                                                <input type="text" class="form-control" name="name[]" placeholder="Masukkan Nama.." value="{{ old('name') }}" required>
                                            </div>
                                            @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="text-label">Deskripsi</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-book"></i></span>
                                                </div>
                                                <textarea class="form-control" name="description[]" placeholder="Masukkan Deskripsi..">{{ old('description') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label class="text-label">Nominal *</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control nominal" name="nominal[]" placeholder="Masukkan Nominal.." required oninput="formatInputNominal(event); calculateTotal(event);">
                                                    <input type="hidden" name="nominal_hidden[]">
                                                </div>
                                                @error('nominal')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label class="text-label">Jumlah Satuan *</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                                    </div>
                                                    <input type="number" step="0.01" class="form-control" name="jumlah_satuan[]" placeholder="Massukan Jumlah Satuan.." value="{{ old('jumlah_satuan') }}" required oninput="calculateTotal(event);">
                                                </div>
                                                @error('jumlah_satuan')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label class="text-label">Dll *</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control dll" name="dll[]" placeholder="Masukkan Dll.." required oninput="formatInputDll(event); calculateTotal(event);">
                                                    <input type="hidden" name="dll_hidden[]">
                                                </div>
                                                @error('dll')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-label">Jumlah *</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                                </div>
                                                <input type="text" class="form-control jumlah" name="jumlah[]" placeholder="Masukkan jumlah.." value="{{ old('jumlah') }}" required readonly>
                                                <input type="hidden" name="jumlah_hidden[]">
                                            </div>
                                            @error('jumlah')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="text-label">Kategori *</label>
                                            <select class="select2-with-label-single js-states form-control" name="category_id[]" required>
                                                <option value="">PILIH KATEGORI</option>
                                            </select>
                                            @error('category_id')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="image">Foto Bukti Pengeluaran</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="image" name="image[]" accept="image/*" onchange="updateImagePreview(event, 'profile-image')">
                                                <label class="custom-file-label" for="image">Pilih file</label>
                                            </div>
                                            <div class="mt-3">
                                                <img id="profile-image" src="{{ asset('dash/images/cash.png') }}" alt="Gambar Bukti Pengeluaran" class="img-fluid" style="max-width: 150px; max-height: 150px;">
                                            </div>
                                            {{-- <small class="form-text text-muted">* Jika tidak ada perubahan, tidak perlu diisi</small> --}}
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right mt-4">
                                    <button type="button" class="btn btn-info" id="add-more-fields">
                                        <i class="fas fa-plus-circle"></i> Tambah Pengeluaran
                                    </button>
                                </div>

                                <div class="form-group text-left mt-5">
                                    <button type="button" class="btn btn-danger btn-cancel" onclick="window.location.href='/pengeluaran'">
                                        <i class="fas fa-times-circle"></i> Batal
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-submit">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="copyright">
            <p>Copyright © Designed &amp; Developed by <a href="/home" target="_blank">SYNC</a> 2024</p>
        </div>
    </div>

    @include('template.scripts')
</body>
<script>
   function formatInputNominal(event) {
    const input = event.target;
    const hiddenInput = input.nextElementSibling; 
    let value = input.value.replace(/[^0-9]/g, ''); 

    if (value) {
        const formattedValue = parseInt(value).toLocaleString('id-ID'); 
        input.value = 'Rp' + formattedValue; 
        hiddenInput.value = value;
    } else {
        input.value = 'Rp0';
        hiddenInput.value = ''; 
    }
}

function formatInputDll(event) {
    const input = event.target; 
    const hiddenInput = input.nextElementSibling; 
    let value = input.value.replace(/[^0-9]/g, ''); 

    if (value) {
        const formattedValue = parseInt(value).toLocaleString('id-ID'); 
        input.value = 'Rp' + formattedValue; 
        hiddenInput.value = value; 
    } else {
        input.value = 'Rp0';
        hiddenInput.value = '';
    }
}

function calculateTotal(event) {
    const container = event.target.closest('.dynamic-field');
    const jumlahSatuan = parseInt(container.querySelector('[name="jumlah_satuan[]"]').value) || 0; 

    const nominalString = container.querySelector('[name="nominal_hidden[]"]').value;
    const nominal = parseInt(nominalString) || 0; 

    const dllString = container.querySelector('[name="dll_hidden[]"]').value;
    const dll = parseInt(dllString) || 0; 

    const total = jumlahSatuan * nominal + dll; 

    const formattedTotal = 'Rp' + total.toLocaleString('id-ID'); 

    container.querySelector('[name="jumlah[]"]').value = formattedTotal; 
    container.querySelector('[name="jumlah_hidden[]"]').value = total; 

    updateGrandTotal(); 
}

function updateGrandTotal() {
    let grandTotal = 0;
    document.querySelectorAll('[name="jumlah_hidden[]"]').forEach(function(input) {
        grandTotal += parseInt(input.value) || 0; 
    });

    const formattedGrandTotal = 'Rp' + grandTotal.toLocaleString('id-ID'); 
    document.getElementById('grand-total').textContent = formattedGrandTotal; 
}


$('form').on('submit', function() {
    document.querySelectorAll('input[name="nominal_hidden[]"], input[name="dll_hidden[]"]').forEach(function(input) {
        if (!input.value) {
            input.value = '0'; 
        }
    });
});

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

function updateImagePreview(event, imageId) {
    const input = event.target;
    const file = input.files[0];
    const image = document.getElementById(imageId);
    const fileInfo = input.nextElementSibling;

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            image.src = e.target.result;
            fileInfo.textContent = file.name;
        };
        reader.readAsDataURL(file);
    } else {
        image.src = '{{ asset('dash/images/cash.png') }}';
        fileInfo.textContent = 'Choose file';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    let iterate = 1;

    getCategories(function(categories) {
        document.querySelectorAll('#dynamic-fields-container .dynamic-field select[name="category_id[]"]').forEach(function(dropdown) {
            dropdown.innerHTML = '<option value="">--PILIH KATEGORI--</option>';
            categories.forEach(function(item) {
                dropdown.innerHTML += `<option value="${item.id}">${item.name}</option>`;
            });
            $(dropdown).select2();
        });
    });

    document.getElementById('add-more-fields').addEventListener('click', function() {
        const newFieldSet = `
            <div class="dynamic-field mt-4">
                <div class="form-group">
                    <label class="text-label">Nama Pengeluaran *</label>
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

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label class="text-label">Nominal *</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="text" class="form-control nominal${iterate}" name="nominal[]" placeholder="Enter amount.." required oninput="formatInputNominal(event); calculateTotal(event);">
                            <input type="hidden" name="nominal_hidden[]">
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-label">Jumlah Satuan *</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                            </div>
                            <input type="number" step="1" class="form-control jumlah_satuan" name="jumlah_satuan[]" placeholder="Enter amount.." required oninput="calculateTotal(event);">
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="text-label">Dll *</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="text" class="form-control dll${iterate}" name="dll[]" placeholder="Enter amount.." required oninput="formatInputDll(event); calculateTotal(event);">
                            <input type="hidden" name="dll_hidden[]">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="text-label">Jumlah *</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                        </div>
                        <input type="text" class="form-control jumlah" name="jumlah[]" placeholder="Enter amount.." required readonly>
                        <input type="hidden" name="jumlah_hidden[]">
                    </div>
                </div>

                <div class="form-group">
                    <label class="text-label">Kategori *</label>
                    <select class="select2-with-label-single js-states form-control" name="category_id[]" required>
                        <option value="">PILIH KATEGORI</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="image${iterate}">Foto Bukti Pengeluaran</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="image${iterate}" name="image[]" accept="image/*" onchange="updateImagePreview(event, 'profile-image${iterate}')">
                        <label class="custom-file-label" for="image${iterate}">Choose file</label>
                    </div>
                    <div class="mt-3">
                        <img id="profile-image${iterate}" src="{{ asset('dash/images/cash.png') }}" alt="Gambar Bukti Pengeluaran" class="img-fluid" style="max-width: 150px; max-height: 150px;">
                    </div>
                    {{-- <small class="form-text text-muted">* Jika tidak ada perubahan, tidak perlu diisi</small> --}}
                </div>

                <button type="button" class="btn btn-danger remove-field">Hapus</button>
            </div>
        `;

        document.getElementById('dynamic-fields-container').insertAdjacentHTML('beforeend', newFieldSet);

        getCategories(function(categories) {
            const newDropdown = document.querySelector('#dynamic-fields-container .dynamic-field:last-child select[name="category_id[]"]');
            newDropdown.innerHTML = '<option value="">~PILIH KATEGORI~</option>';
            categories.forEach(function(item) {
                newDropdown.innerHTML += `<option value="${item.id}">${item.name}</option>`;
            });
            $(newDropdown).select2();
        });

        iterate++;
    });

    document.getElementById('dynamic-fields-container').addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-field')) {
            event.target.closest('.dynamic-field').remove();
            updateGrandTotal();
        }
    });

    // Tambahkan elemen untuk menampilkan grand total
    const grandTotalElement = document.createElement('div');
    grandTotalElement.innerHTML = '<strong>Total Keseluruhan: <span id="grand-total">Rp 0</span></strong>';
    document.querySelector('form').appendChild(grandTotalElement);
});

</script>
</html>