<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | {{auth()->user()->level}} | Add Pemasukan</title>
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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Add Pemasukan</a></li>
                    </ol>
                </div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Add Pemasukan</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                <form class="form-valide-with-icon" action="/pemasukan/store" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label class="text-label">Nama Pemasukan*</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="name" placeholder="Enter name.." value="{{ old('name') }}" required>
                                        </div>
                                        @error('name')
                                        <span class="mt-2 text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                        
                                    <div class="form-group">
                                        <label class="text-label">Deskripsi</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-book"></i></span>
                                            </div>
                                            <textarea class="form-control" name="description" placeholder="Enter description..">{{ old('description') }}</textarea>
                                        </div>
                                    </div>
                        
                                    <div class="form-group">
                                        <label class="text-label">Tanggal *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                            </div>
                                            <input type="date" class="form-control" name="date" value="{{ old('date') }}" required>
                                        </div>
                                        @error('date')
                                        <span class="mt-2 text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                        
                                    <div class="form-group">
                                        <label class="text-label">Jumlah (Rp)*</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="jumlah_display" id="jumlah" placeholder="Rp 0" value="{{ old('jumlah_display') }}" required oninput="formatInput()">
                                            <input type="hidden" name="jumlah" id="jumlah_value">
                                        </div>
                                        @error('jumlah')
                                        <span class="mt-2 text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="text-label">Kategori *</label>
                                        <select class="select2-with-label-single js-states form-control" id="category" name="category_id" required>
                                            <option value="">PILIH KATEGORI</option>
                                    
                                        </select>
                                        @error('category_id')
                                        <span class="mt-2 text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                        
                                    <button type="button" class="btn btn-danger btn-cancel" onclick="window.location.href='/pemasukan'">Cancel</button>
                                    <button type="submit" class="btn mr-2 btn-primary btn-submit">Submit</button>
                                </form>
                            </div>
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
  $(document).ready(function() {
    getCategories();

    function getCategories() {
        $.ajax({
            url: '/get-categories/1',
            method: 'GET',
            success: function(data) {
                var $dropdown = $('#category');
                $dropdown.empty();

                $dropdown.append($('<option>', {
                    value: '',
                    text: '--PILIH KATEGORI--'
                }));

                $.each(data, function(index, item) {
                    $dropdown.append($('<option>', {
                        value: item.id,
                        text: item.name
                    }));
                });

                $dropdown.select2();
            },
            error: function(xhr) {
                console.error('Error fetching categories:', xhr);
                $('#category').append($('<option>', {
                    value: '',
                    text: 'Error loading categories'
                }));
            }
        });
    }
});
function formatInput() {
            const input = document.getElementById('jumlah');
            const hiddenInput = document.getElementById('jumlah_value');
            
            let value = input.value.replace(/[^0-9]/g, '');

            if (value) {
                const formattedValue = parseInt(value).toLocaleString('id-ID');
                input.value = 'Rp' + formattedValue;
                hiddenInput.value = value;
            } else {
                input.value = 'Rp 0';
                hiddenInput.value = '';
            }
        }

        $('form').on('submit', function() {
            const input = document.getElementById('jumlah_value');
            if (!input.value) {
                input.value = '0';
            }
        });
    

</script>

</html>
