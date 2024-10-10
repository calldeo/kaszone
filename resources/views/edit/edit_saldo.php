<!DOCTYPE html>

<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | {{auth()->user()->level}} | Edit Saldo</title>
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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Saldo</a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Saldo</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                <form action="{{ route('update.saldo') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label class="text-label">Saldo Saat Ini</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number" class="form-control" name="saldo" value="{{ $saldo }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-label">Minimal Saldo</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number" class="form-control" name="minimal_saldo" value="{{ $minimalSaldo }}" required>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-cancel" onclick="window.location.href='{{ route('saldo') }}'">Batal</button>
                                    <button type="submit" class="btn mr-2 btn-primary btn-submit">Simpan Perubahan</button>
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

    </div>

    @include('template.scripts')
</body>
</html>