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
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-tag"></i>
                                            </span>
                                        </div>
                                        <input type="text" id="name" name="name" class="form-control" value="{{ $pemasukan->name }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Deskripsi</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-align-left"></i>
                                            </span>
                                        </div>
                                        <textarea id="description" name="description" class="form-control" rows="3">{{ $pemasukan->description }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="text-label">Tanggal *</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar"></i>
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
                                        <i class="fas fa-dollar-sign"></i>
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
            $('.select2').select2();
    
            getCategories();
    
            function getCategories() {
                $.ajax({
                    url: '/get-categories/1',
                    method: 'GET',
                    success: function(data) {
                        var $dropdown = $('#category');
                        var selectedCategoryId = "{{ $pemasukan->id }}";
                        
                        $dropdown.empty(); 
    
                        $dropdown.append($('<option>', {
                            value: '',
                            text: 'Pilih Kategori'
                        }));
    
                        $.each(data, function(index, item) {
                            var $option = $('<option>', {
                                value: item.id,
                                text: item.name
                            });
    
                            if (item.id == selectedCategoryId) {
                                $option.prop('selected', true);
                            }
    
                            $dropdown.append($option);
                        });
    
                        $dropdown.trigger('change.select2');
                    },
                    error: function(xhr) {
                        console.error('Error fetching options:', xhr);
                    }
                });
            }
    
            var jumlahValue = $('#val-jumlah').val();
            if (jumlahValue) {
                jumlahValue = jumlahValue.replace(/Rp/g, '').replace(/\./g, '').trim();
                $('#val-jumlah').val('Rp' + numberWithCommas(jumlahValue));
            }
    
            $('#val-jumlah').on('input', function() {
                var value = $(this).val().replace(/Rp/g, '').replace(/\./g, '').trim();
                if (!isNaN(value) && value !== '') {
                    $(this).val('Rp' + numberWithCommas(value));
                } else {
                    $(this).val('');
                }
            });
    
            function numberWithCommas(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
    
            $('form').on('submit', function() {
                var jumlahInput = $('#val-jumlah').val().replace(/Rp/g, '').replace(/\./g, '').trim();
                $('#val-jumlah').val(jumlahInput);
            });
        });
    </script>
    
</body>

</html>
