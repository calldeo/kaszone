<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | {{ auth()->user()->level }} | Edit Data Kategori</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fa;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card-header {
            background: linear-gradient(45deg, #EB8153, #EB8153);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
        }
        .form-control {
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            padding: 12px;
        }
        .btn {
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background-color: #EB8153;
            border-color: #EB8153;
        }
        .btn-primary:hover {
            background-color: #FF9A85;
            border-color: #FF9A85;
        }
        .btn-danger {
            background-color: #FF6B6B;
            border-color: #FF6B6B;
        }
        .btn-danger:hover {
            background-color: #FF8E8E;
            border-color: #FF8E8E;
        }
    </style>
</head>

<body>
    @include('template.topbarr')
    @include('template.sidebarr')

    <div class="content-body" style="margin-top: -100px;">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0"></div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Form</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Pemasukan</a></li>
                    </ol>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card animate__animated animate__fadeIn">
                        <div class="card-header">
                            <h4 class="card-title mb-0 text-white">Edit Data Pemasukan</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                <form action="{{ route('pemasukan.update', $pemasukan->id_data) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label class="text-label">Nama *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $pemasukan->name }}" placeholder="Enter a name.." required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-label">Deskripsi *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-align-left"></i></span>
                                            </div>
                                            <textarea id="description" name="description" class="form-control" rows="3" placeholder="Enter a description..">{{ $pemasukan->description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-label">Tanggal *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            </div>
                                            <input type="date" class="form-control" id="val-date" name="date" value="{{ old('date', $pemasukan->date ?? '') }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-label">Jumlah *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-dollar-sign"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="val-jumlah" name="jumlah" value="{{ old('jumlah', 'Rp' . number_format($pemasukan->jumlah, 0, ',', '.')) }}" placeholder="Rp0,00" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-label">Kategori *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                             
                                            </div>
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
                                    <div class="form-group mt-4 text-right">
                                        <a href="/pemasukan" class="btn btn-danger btn-cancel mr-2"><i class="fas fa-times mr-1"></i> Batal</a>
                                        <button type="submit" class="btn btn-primary btn-submit"><i class="fas fa-save mr-1"></i> Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
