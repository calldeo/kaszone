<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | {{ auth()->user()->level }} | Pengeluaran</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/dt-2.1.3/datatables.min.css">
    <style>
        .form-group {
            margin-bottom: 15px;
        }

        .btn-edit, .btn-submit, .btn-cancel {
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-edit {
            background-color: #007bff;
            color: white;
            border: none;
        }

        .btn-edit:hover {
            background-color: #0056b3;
        }

        .btn-submit {
            background-color: #28a745;
            color: white;
            border: none;
            display: none; /* Hidden initially */
        }

        .btn-cancel {
            background-color: #dc3545;
            color: white;
            border: none;
            display: none; /* Hidden initially */
        }

        .edit-button-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .img-thumbnail {
            max-width: 100px;
            height: auto;
        }
    </style>
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
                <div class="card-header">Informasi Umum</div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="text" class="form-control" value="{{ $parentPengeluaran->tanggal }}" readonly>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tombol Edit di Bagian Atas -->
            <div class="edit-button-container">
                <button id="edit-btn" class="btn-edit">Edit Semua Data</button>
                <button id="submit-btn" class="btn-submit">Submit</button>
                <button id="cancel-btn" class="btn-cancel">Cancel</button>
            </div>

            <!-- Detail Pengeluaran -->
            @foreach($parentPengeluaran->pengeluaran as $pengeluaran)
            <div class="card mb-4">
                <div class="card-header">Detail Pengeluaran #{{ $loop->iteration }}</div>
                <div class="card-body">
                    <form id="form-{{ $loop->iteration }}" method="POST" action="{{ route('pengeluaran.update', $pengeluaran->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" name="name" class="form-control editable-input" value="{{ $pengeluaran->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Deskripsi</label>
                                    <textarea name="description" class="form-control editable-input" rows="3" readonly>{{ $pengeluaran->description }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jumlah Satuan</label>
                                    <input type="text" name="jumlah_satuan" class="form-control editable-input" value="{{ $pengeluaran->jumlah_satuan }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nominal</label>
                                    <input type="text" name="nominal" class="form-control editable-input" value="{{ number_format($pengeluaran->nominal, 2, ',', '.') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <input type="text" name="category" class="form-control editable-input" value="{{ $pengeluaran->category->name ?? 'Tidak ada kategori' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Total</label>
                                    <input type="text" name="jumlah" class="form-control editable-input" value="{{ $pengeluaran->jumlah }}" readonly>
                                </div>
                            </div>
                        </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Lain-lain</label>
                                    <input type="text" name="dll" class="form-control editable-input" value="{{ $pengeluaran->dll }}" readonly>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Gambar</label>
                                            <img src="{{ $pengeluaran->image ? asset('storage/' . $pengeluaran->image) : asset('dash/images/usr.png') }}" alt="Gambar" class="img-thumbnail">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach

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
    <input type="hidden" id="table-url" value="{{ route('production') }}">
    <script src="{{ asset('main.js') }}"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-2.1.3/datatables.min.js"></script>

    <!-- JavaScript for Editing -->
    <script>
        document.getElementById('edit-btn').addEventListener('click', function() {
            // Enable all inputs for editing
            document.querySelectorAll('.editable-input').forEach(function(input) {
                input.removeAttribute('readonly');
            });

            // Show Submit and Cancel buttons
            document.getElementById('submit-btn').style.display = 'inline-block';
            document.getElementById('cancel-btn').style.display = 'inline-block';

            // Hide Edit button
            document.getElementById('edit-btn').style.display = 'none';
        });

        document.getElementById('cancel-btn').addEventListener('click', function() {
            // Revert all inputs to readonly and reset values
            document.querySelectorAll('.editable-input').forEach(function(input) {
                input.setAttribute('readonly', true);
                input.value = input.defaultValue; // Reset value to original
            });

            // Show Edit button, hide Submit and Cancel
            document.getElementById('edit-btn').style.display = 'inline-block';
            document.getElementById('submit-btn').style.display = 'none';
            document.getElementById('cancel-btn').style.display = 'none';
        });

        document.getElementById('submit-btn').addEventListener('click', function() {
            // Submit all forms
            document.querySelectorAll('form').forEach(function(form) {
                form.submit();
            });
        });
    </script>

</body>

</html>
