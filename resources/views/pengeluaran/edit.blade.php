<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>Edit Pengeluaran</title>
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
                        <h4>Edit Pengeluaran</h4>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('pengeluaran.index') }}">Pengeluaran</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit</a></li>
                    </ol>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="card">
                <div class="card-header">Edit Detail Pengeluaran</div>
                <div class="card-body">
                    <form action="{{ route('pengeluaran.update', $pengeluaranItem->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $pengeluaranItem->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Deskripsi</label>
                                    <textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $pengeluaranItem->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jumlah_satuan">Jumlah Satuan</label>
                                    <input type="number" id="jumlah_satuan" name="jumlah_satuan" class="form-control" value="{{ old('jumlah_satuan', $pengeluaranItem->jumlah_satuan) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nominal">Nominal</label>
                                    <input type="number" id="nominal" name="nominal" class="form-control" value="{{ old('nominal', $pengeluaranItem->nominal) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category">Kategori</label>
                                    <select id="category" name="category_id" class="form-control" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ old('category_id', $pengeluaranItem->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jumlah">Total</label>
                                    <input type="text" id="jumlah" name="jumlah" class="form-control" value="{{ old('jumlah', $pengeluaranItem->jumlah) }}" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="dll">Lain-lain</label>
                            <input type="number" id="dll" name="dll" class="form-control" value="{{ old('dll', $pengeluaranItem->dll) }}">
                        </div>

                        <div class="form-group">
                            <label for="image">Gambar</label>
                            <input type="file" id="image" name="image" class="form-control">
                            @if($pengeluaranItem->image)
                                <img src="{{ asset('storage/' . $pengeluaranItem->image) }}" alt="Gambar" class="img-thumbnail mt-2" width="100">
                            @endif
                        </div>

                        <a href="{{ route('pengeluaran.index') }}" class="btn btn-danger btn-cancel">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-submit">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('template.scripts')
    <script>
        $(document).ready(function() {
            // Perhitungan otomatis
            $('#jumlah_satuan, #nominal, #dll').on('input', function() {
                let jumlahSatuan = parseFloat($('#jumlah_satuan').val()) || 0;
                let nominal = parseFloat($('#nominal').val()) || 0;
                let dll = parseFloat($('#dll').val()) || 0;

                let jumlah = jumlahSatuan * nominal + dll;

                $('#jumlah').val(jumlah.toFixed(2)); // Mengisi hasil ke input 'jumlah'
            });
        });
    </script>
</body>

</html>
