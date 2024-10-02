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
                                <input type="number" step="0.01" class="form-control" id="val-jumlah" name="jumlah" value="{{ old('jumlah', $pemasukan->jumlah ?? '') }}" placeholder="Masukkan jumlah.." required>
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
                    url: '/get-categories/1',
                    method: 'GET',
                    success: function(data) {
                        var $dropdown = $('#category');
                        $dropdown.empty(); // Kosongkan dropdown

                        $dropdown.append($('<option>', {
                            value: '',
                            text: 'Pilih Kategori'
                        }));
                        $.each(data, function(index, item) {
                            $dropdown.append($('<option>', {
                                value: item.id,
                                text: item.name
                            }));
                        });
                    },
                    error: function(xhr) {
                        console.error('Error fetching options:', xhr);
                    }
                });
            }
        });
    </script>
</body>

</html>
